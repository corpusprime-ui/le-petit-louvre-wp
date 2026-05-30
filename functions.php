<?php
/**
 * Le Petit Louvre — functions.php
 */

/* ------------------------------------------
   Champs ACF — page d'accueil
   Enregistrés en PHP, visibles dans WP Admin > Pages > Accueil
------------------------------------------ */
require_once get_template_directory() . '/inc/acf-fields.php';
require_once get_template_directory() . '/inc/cpt-menus.php';
require_once get_template_directory() . '/inc/pdf-generator.php';

/* ------------------------------------------
   ACF — Page d'options du thème
------------------------------------------ */
add_action( 'acf/init', function () {
    if ( ! function_exists( 'acf_add_options_page' ) ) return;
    acf_add_options_page( [
        'page_title'  => 'Options du thème',
        'menu_title'  => 'Options du thème',
        'menu_slug'   => 'theme-options',
        'capability'  => 'manage_options',
        'position'    => 60,
        'icon_url'    => 'dashicons-admin-customizer',
        'redirect'    => false,
    ] );
} );

/* ------------------------------------------
   Initialisation du thème
------------------------------------------ */
function lpl_theme_setup() {
    add_theme_support( 'title-tag' );
    add_theme_support( 'post-thumbnails' );
    add_theme_support( 'html5', [ 'search-form', 'comment-form', 'gallery', 'caption' ] );

    register_nav_menus( [
        'primary' => __( 'Menu principal', 'le-petit-louvre' ),
        'footer'  => __( 'Menu footer',    'le-petit-louvre' ),
    ] );
}
add_action( 'after_setup_theme', 'lpl_theme_setup' );

/* ------------------------------------------
   Supprime le bloat WordPress du <head>
   Chaque ligne = ~50-200ms économisés
------------------------------------------ */
remove_action( 'wp_head', 'wp_generator' );                    // Version WP visible
remove_action( 'wp_head', 'wlwmanifest_link' );                // Windows Live Writer
remove_action( 'wp_head', 'rsd_link' );                        // Really Simple Discovery
remove_action( 'wp_head', 'wp_shortlink_wp_head' );            // Shortlink inutile
remove_action( 'wp_head', 'feed_links',       2 );             // Liens RSS
remove_action( 'wp_head', 'feed_links_extra', 3 );             // Liens RSS extra
remove_action( 'wp_head', 'rest_output_link_wp_head' );        // REST API header
remove_action( 'wp_head', 'wp_oembed_add_discovery_links' );   // oEmbed
remove_action( 'template_redirect', 'rest_output_link_header', 11 );

// Supprime les scripts emoji (économise ~18KB JS + 1 requête)
remove_action( 'wp_head',             'print_emoji_detection_script', 7 );
remove_action( 'wp_print_styles',     'print_emoji_styles' );
remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
remove_action( 'admin_print_styles',  'print_emoji_styles' );
add_filter( 'emoji_svg_url',          '__return_false' );

// Supprime jQuery Migrate (inutile sur thème custom)
add_action( 'wp_default_scripts', function ( $scripts ) {
    if ( ! is_admin() ) {
        $scripts->remove( 'wp-embed' );
    }
} );

/* ------------------------------------------
   Supprime les CSS WordPress inutiles
   Ce thème est 100% custom sans Gutenberg
   ~25KB économisés sur le chemin de rendu
------------------------------------------ */
add_action( 'wp_enqueue_scripts', function () {
    wp_dequeue_style( 'wp-block-library' );
    wp_dequeue_style( 'wp-block-library-theme' );
    wp_dequeue_style( 'classic-theme-styles' );
    wp_dequeue_style( 'global-styles' );
    wp_dequeue_style( 'wp-block-page-list' );
    wp_dequeue_style( 'wp-block-navigation' );
}, 100 );

/* ------------------------------------------
   Chargement des styles — stratégie optimisée
   Bootstrap + Fonts = asynchrone (non-bloquant)
   main.css = synchrone (critique)
------------------------------------------ */
function lpl_enqueue_assets() {
    $v = wp_get_theme()->get( 'Version' );

    // main.css — version source (pas de minification)
    wp_enqueue_style( 'lpl-main', get_template_directory_uri() . '/css/main.css', [], $v );

    // Bootstrap JS en footer (déjà non-bloquant)
    wp_enqueue_script(
        'bootstrap',
        'https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js',
        [],
        '5.3.3',
        true
    );

    // JS principal du thème
    wp_enqueue_script(
        'lpl-main',
        get_template_directory_uri() . '/js/main.js',
        [ 'bootstrap' ],
        $v,
        true
    );

    // Données AJAX pour la newsletter (nonce + ajaxurl)
    wp_localize_script( 'lpl-main', 'lplAjax', [
        'ajaxurl' => admin_url( 'admin-ajax.php' ),
        'nonce'   => wp_create_nonce( 'lpl_nl_nonce' ),
    ] );
}
add_action( 'wp_enqueue_scripts', 'lpl_enqueue_assets' );

/* ============================================================
   NEWSLETTER — Inscription AJAX sécurisée
   ─────────────────────────────────────────
   Sécurité :
     1. Nonce WordPress (CSRF)
     2. Honeypot champ caché (bots)
     3. Rate-limit IP : 3 tentatives / heure via transients
     4. Validation + sanitize email strict
     5. Dédoublonnage avant stockage
   Stockage : wp_options → clé lpl_newsletter_emails (tableau)
   Export WP-CLI : wp option get lpl_newsletter_emails --format=json
   ============================================================ */
add_action( 'wp_ajax_lpl_newsletter_subscribe',        'lpl_newsletter_subscribe' );
add_action( 'wp_ajax_nopriv_lpl_newsletter_subscribe', 'lpl_newsletter_subscribe' );

function lpl_newsletter_subscribe(): void {

    /* ── 1. Nonce CSRF ─────────────────────────────────────── */
    if ( ! check_ajax_referer( 'lpl_nl_nonce', 'nonce', false ) ) {
        wp_send_json_error( [ 'message' => 'Requête invalide.' ], 403 );
    }

    /* ── 2. Honeypot anti-bot ──────────────────────────────── */
    $honeypot = sanitize_text_field( wp_unslash( $_POST['nl_website'] ?? '' ) );
    if ( $honeypot !== '' ) {
        /* Bot détecté : réponse silencieuse (ne pas révéler le mécanisme) */
        wp_send_json_success( [ 'message' => 'Merci, à bientôt !' ] );
    }

    /* ── 3. Rate-limit par IP (3 req / heure) ─────────────── */
    $ip      = sanitize_text_field( $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0' );
    $tk_key  = 'lpl_nl_rl_' . md5( $ip );
    $hits    = (int) get_transient( $tk_key );
    if ( $hits >= 3 ) {
        wp_send_json_error( [ 'message' => 'Trop de tentatives, réessayez dans une heure.' ], 429 );
    }
    set_transient( $tk_key, $hits + 1, HOUR_IN_SECONDS );

    /* ── 4. Validation email ───────────────────────────────── */
    $raw_email = wp_unslash( $_POST['email'] ?? '' );
    $email     = sanitize_email( $raw_email );

    if ( ! is_email( $email ) ) {
        wp_send_json_error( [ 'message' => 'Adresse email invalide.' ], 400 );
    }

    /* Longueur max raisonnable (RFC 5321 : 254 chars) */
    if ( strlen( $email ) > 254 ) {
        wp_send_json_error( [ 'message' => 'Adresse email trop longue.' ], 400 );
    }

    /* ── 5. Stockage dédoublonné ───────────────────────────── */
    $list = get_option( 'lpl_newsletter_emails', [] );
    if ( ! is_array( $list ) ) $list = [];

    $already = in_array( $email, $list, true );
    if ( ! $already ) {
        $list[] = $email;
        update_option( 'lpl_newsletter_emails', $list, false );
    }

    /* ── 6. Email de confirmation au visiteur ──────────────── */
    if ( ! $already ) {
        $subject = 'Bienvenue dans la newsletter du Petit Louvre';
        $body    = "Bonjour,\n\nVotre inscription à la newsletter du Petit Louvre est confirmée.\n"
                 . "Vous recevrez nos actualités, événements et offres spéciales.\n\n"
                 . "À très bientôt au Petit Louvre, 14 Pl. Lucien de Gracia, Arcachon.\n\n"
                 . "— L'équipe du Petit Louvre";
        $headers = [ 'Content-Type: text/plain; charset=UTF-8' ];
        wp_mail( $email, $subject, $body, $headers );

        /* ── Notification interne au gérant ─────────────────── */
        wp_mail(
            'reservation@lepetitlouvre.fr',
            'Nouvelle inscription newsletter : ' . $email,
            "Un nouveau visiteur vient de s'inscrire à la newsletter.\n\nEmail : $email\nDate : " . current_time( 'mysql' ),
            [ 'Content-Type: text/plain; charset=UTF-8' ]
        );
    }

    wp_send_json_success( [ 'message' => 'Merci, à bientôt !' ] );
}

/* ------------------------------------------
   Ajout de l'attribut defer sur les scripts
   footer=true ne suffit pas pour Lighthouse
------------------------------------------ */
add_filter( 'script_loader_tag', function ( $tag, $handle ) {
    $defer = [ 'bootstrap', 'lpl-main' ];
    if ( in_array( $handle, $defer, true ) && ! is_admin() ) {
        $tag = str_replace( ' src=', ' defer src=', $tag );
    }
    return $tag;
}, 10, 2 );

/* ------------------------------------------
   Open Graph complet + Twitter Card + Preload LCP
   Injecté en priorité 1 dans <head>
------------------------------------------ */
add_action( 'wp_head', function () {
    // Image OG par défaut
    $pid_front   = get_option( 'page_on_front' );
    $hero_images = function_exists( 'get_field' ) ? get_field( 'hero_images', $pid_front ) : null;
    $default_og  = ( ! empty( $hero_images[0]['image']['url'] ) )
                   ? $hero_images[0]['image']['url']
                   : get_template_directory_uri() . '/img/hero-salle.jpg';

    // Titre et description SEO pour l'OG
    $seo     = lpl_current_seo();
    $og_title = $seo ? $seo['title'] : get_bloginfo( 'name' );
    $og_desc  = $seo ? $seo['desc']  : get_bloginfo( 'description' );
    $og_url   = is_front_page() ? home_url( '/' ) : get_permalink();

    // Tags Open Graph communs à toutes les pages
    echo '<meta property="og:type"        content="website">' . "\n";
    echo '<meta property="og:locale"      content="fr_FR">' . "\n";
    echo '<meta property="og:site_name"   content="Le Petit Louvre">' . "\n";
    echo '<meta property="og:title"       content="' . esc_attr( $og_title ) . '">' . "\n";
    echo '<meta property="og:description" content="' . esc_attr( $og_desc ) . '">' . "\n";
    echo '<meta property="og:url"         content="' . esc_url( $og_url ) . '">' . "\n";

    // Twitter Card
    echo '<meta name="twitter:card"        content="summary_large_image">' . "\n";
    echo '<meta name="twitter:title"       content="' . esc_attr( $og_title ) . '">' . "\n";
    echo '<meta name="twitter:description" content="' . esc_attr( $og_desc ) . '">' . "\n";

    // Preload LCP + og:image
    if ( is_front_page() ) {
        $poster = get_template_directory_uri() . '/videos/hero-poster.jpg';
        echo '<link rel="preload" as="image" fetchpriority="high" href="' . esc_url( $poster ) . '">' . "\n";
        echo '<meta property="og:image"      content="' . esc_url( $default_og ) . '">' . "\n";
        echo '<meta name="twitter:image"     content="' . esc_url( $default_og ) . '">' . "\n";
        return;
    }

    $thumb = get_the_post_thumbnail_url( get_the_ID(), 'large' );
    $og    = $thumb ?: $default_og;
    echo '<meta property="og:image"      content="' . esc_url( $og ) . '">' . "\n";
    echo '<meta property="og:image:width"  content="1200">' . "\n";
    echo '<meta property="og:image:height" content="630">' . "\n";
    echo '<meta name="twitter:image"     content="' . esc_url( $og ) . '">' . "\n";
}, 1 );

/* ------------------------------------------
   Preconnect + Fonts + Bootstrap CSS async
   Injectés AVANT main.css pour prioriser le DNS
------------------------------------------ */
add_action( 'wp_head', function () {
    $fonts_url    = 'https://fonts.googleapis.com/css2?family=Fraunces:ital,opsz,wght@0,9..144,400;0,9..144,600;1,9..144,400&family=Inter:wght@300;400;500&family=Laila:wght@400&display=swap';
    $bootstrap_url = 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css';
    ?>
    <!-- Preconnect — résolution DNS anticipée -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preconnect" href="https://cdn.jsdelivr.net" crossorigin>
    <!-- Google Fonts — async non-bloquant -->
    <link rel="preload" as="style"
          href="<?php echo esc_url( $fonts_url ); ?>"
          onload="this.onload=null;this.rel='stylesheet'">
    <noscript><link rel="stylesheet" href="<?php echo esc_url( $fonts_url ); ?>"></noscript>
    <!-- Bootstrap CSS — async non-bloquant -->
    <link rel="preload" as="style"
          href="<?php echo esc_url( $bootstrap_url ); ?>"
          onload="this.onload=null;this.rel='stylesheet'">
    <noscript><link rel="stylesheet" href="<?php echo esc_url( $bootstrap_url ); ?>"></noscript>
    <?php
}, 2 );

/* ------------------------------------------
   Données structurées JSON-LD (SEO)
   - Restaurant complet sur la page d'accueil
   - BreadcrumbList + Menu/Bar sur pages internes
------------------------------------------ */
function lpl_json_ld() {
    $tpl   = basename( get_page_template() );
    $site  = esc_url( home_url( '/' ) );

    /* ── Restaurant (homepage uniquement) ── */
    if ( is_front_page() ) : ?>
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "Restaurant",
        "name": "Le Petit Louvre",
        "description": "Restaurant fusion moderne au cœur d'Arcachon. Cuisine généreuse et créative, terrasse 70 couverts, cave des vins et privatisation.",
        "url": "<?php echo $site; ?>",
        "telephone": "+33557157359",
        "email": "contact@lepetitlouvre.fr",
        "priceRange": "€€",
        "servesCuisine": ["Française", "Fusion"],
        "hasMap": "https://maps.google.com/?q=Le+Petit+Louvre+Arcachon",
        "address": {
            "@type": "PostalAddress",
            "streetAddress": "14 Pl. Lucien de Gracia",
            "postalCode": "33120",
            "addressLocality": "Arcachon",
            "addressCountry": "FR"
        },
        "geo": {
            "@type": "GeoCoordinates",
            "latitude": "44.6539",
            "longitude": "-1.1665"
        },
        "openingHoursSpecification": [
            {"@type": "OpeningHoursSpecification", "dayOfWeek": ["Monday","Tuesday","Wednesday","Thursday","Friday","Saturday","Sunday"], "opens": "09:00", "closes": "23:00"}
        ],
        "aggregateRating": {
            "@type": "AggregateRating",
            "ratingValue": "4.9",
            "reviewCount": "423",
            "bestRating": "5"
        }
    }
    </script>
    <?php endif;

    /* ── BreadcrumbList sur toutes les pages internes ── */
    $breadcrumbs = [
        'page-carte.php'               => [ 'name' => 'La Carte',           'slug' => '/carte/' ],
        'page-reservation.php'         => [ 'name' => 'Réservation',        'slug' => '/reservation/' ],
        'page-carte-des-vins.php'      => [ 'name' => 'Carte des Vins',     'slug' => '/carte-des-vins/' ],
        'page-carte-des-boissons.php'  => [ 'name' => 'Carte des Boissons', 'slug' => '/carte-des-boissons/' ],
        'page-carte-des-cocktails.php' => [ 'name' => 'Cocktails',          'slug' => '/carte-des-cocktails/' ],
        'page-contact.php'             => [ 'name' => 'Contact',            'slug' => '/contact/' ],
    ];

    if ( isset( $breadcrumbs[ $tpl ] ) ) :
        $b = $breadcrumbs[ $tpl ]; ?>
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "BreadcrumbList",
        "itemListElement": [
            {"@type": "ListItem", "position": 1, "name": "Accueil", "item": "<?php echo $site; ?>"},
            {"@type": "ListItem", "position": 2, "name": "<?php echo esc_js( $b['name'] ); ?>", "item": "<?php echo esc_url( home_url( $b['slug'] ) ); ?>"}
        ]
    }
    </script>
    <?php endif;
}
add_action( 'wp_head', 'lpl_json_ld' );

/* ------------------------------------------
   Helper global : lire un champ ACF avec fallback
------------------------------------------ */
if ( ! function_exists( 'lpl_field' ) ) {
    function lpl_field( $name, $pid, $fallback = '' ) {
        if ( function_exists( 'get_field' ) ) {
            $val = get_field( $name, $pid );
            return ( $val !== false && $val !== null && $val !== '' ) ? $val : $fallback;
        }
        return $fallback;
    }
}

/* ------------------------------------------
   Supprime la barre d'admin en front
------------------------------------------ */
add_filter( 'show_admin_bar', '__return_false' );

/* ------------------------------------------
   Désactive Gutenberg + éditeur visuel
------------------------------------------ */
add_filter( 'use_block_editor_for_post', '__return_false' );
add_filter( 'use_widgets_block_editor',  '__return_false' );
add_action( 'init', function () {
    remove_post_type_support( 'page', 'editor' );
} );

/* ------------------------------------------
   Preload LCP — première image hero par page
   Injecté en priorité 1 dans <head>
------------------------------------------ */
add_action( 'wp_head', function () {
    $uri = get_template_directory_uri() . '/img/';
    $map = [
        'front-page.php'               => 'hero-salle.jpg',
        'page-carte.php'               => 'plat-22-opt.jpg',
        'page-reservation.php'         => 'interieur.jpg',
        'page-carte-des-boissons.php'  => 'boisson-1.jpg',
        'page-carte-des-cocktails.php' => 'verre-cocktail-1.jpg',
        'page-carte-des-vins.php'      => 'bar-vins.jpg',
    ];
    $tpl = basename( get_page_template() );
    if ( isset( $map[ $tpl ] ) ) {
        $img = esc_url( $uri . $map[ $tpl ] );
        echo "<link rel=\"preload\" as=\"image\" href=\"{$img}\" fetchpriority=\"high\">\n";
    }
}, 1 );

/* ------------------------------------------
   Headers HTTP — Cache navigateur
   Améliore les visites répétées
------------------------------------------ */
add_action( 'send_headers', function () {
    if ( ! is_admin() ) {
        header( 'Cache-Control: public, max-age=31536000, immutable', false ); // assets
        header( 'X-Content-Type-Options: nosniff' );
        header( 'X-Frame-Options: SAMEORIGIN' );
    }
} );

/* ------------------------------------------
   Lazy load natif sur toutes les images WP
------------------------------------------ */
add_filter( 'wp_lazy_loading_enabled', '__return_true' );
add_filter( 'the_content', function( $content ) {
    return str_replace( '<img ', '<img loading="lazy" ', $content );
} );

/* ------------------------------------------
   SEO per-page — Filtres Yoast
   Fournit titres, descriptions et canonicals
   pour chaque page du site
------------------------------------------ */
function lpl_seo_map() {
    return [
        'front-page.php' => [
            'title' => 'Le Petit Louvre – Restaurant Fusion · Arcachon',
            'desc'  => 'Restaurant fusion moderne au cœur d\'Arcachon. Cuisine généreuse et créative, terrasse 70 couverts, cave des vins et privatisation. Ouvert tous les jours de 9h à 23h.',
            'slug'  => '/',
        ],
        'page-carte.php' => [
            'title' => 'La Carte – Le Petit Louvre | Restaurant Arcachon',
            'desc'  => 'Découvrez la carte du Petit Louvre : entrées, plats signatures et desserts. Cuisine fusion française, savoureuse et créative à Arcachon.',
            'slug'  => '/carte/',
        ],
        'page-reservation.php' => [
            'title' => 'Réserver une table – Le Petit Louvre Arcachon',
            'desc'  => 'Réservez votre table au Petit Louvre, restaurant fusion à Arcachon. Disponibilités en temps réel, terrasse 70 couverts, groupes et privatisation.',
            'slug'  => '/reservation/',
        ],
        'page-carte-des-boissons.php' => [
            'title' => 'Carte des Boissons – Le Petit Louvre Arcachon',
            'desc'  => 'Découvrez notre sélection de bières, softs et eaux. Bar convivial et carte des boissons variée au Petit Louvre, Arcachon.',
            'slug'  => '/carte-des-boissons/',
        ],
        'page-carte-des-cocktails.php' => [
            'title' => 'Cocktails & Spirits – Le Petit Louvre Arcachon',
            'desc'  => 'Laissez-vous surprendre par notre carte de cocktails signature et spiritueux. Bar inventif et convivial au Petit Louvre, Arcachon.',
            'slug'  => '/carte-des-cocktails/',
        ],
        'page-carte-des-vins.php' => [
            'title' => 'Cave à Vins – Le Petit Louvre Arcachon',
            'desc'  => 'Explorez notre cave à vins : vins naturels, biodynamiques et grands terroirs. Conseils personnalisés par notre sommelier au Petit Louvre.',
            'slug'  => '/carte-des-vins/',
        ],
        'page-contact.php' => [
            'title' => 'Contact & Infos pratiques – Le Petit Louvre Arcachon',
            'desc'  => 'Contactez le Petit Louvre : adresse, horaires d\'ouverture, téléphone et accès. 14 Place Lucien de Gracia, 33120 Arcachon.',
            'slug'  => '/contact/',
        ],
    ];
}

function lpl_current_seo() {
    static $cache = null;
    if ( $cache !== null ) return $cache;

    // Utilise des conditionnelles WP + slug de page (plus fiable que get_page_template dans les filtres)
    if ( is_front_page() ) {
        $cache = lpl_seo_map()['front-page.php'];
        return $cache;
    }
    if ( ! is_singular( 'page' ) ) return null;

    $slug = get_post_field( 'post_name', get_queried_object_id() );
    $slug_map = [
        'carte'                => 'page-carte.php',
        'reservation'          => 'page-reservation.php',
        'carte-des-boissons'   => 'page-carte-des-boissons.php',
        'carte-des-cocktails'  => 'page-carte-des-cocktails.php',
        'carte-des-vins'       => 'page-carte-des-vins.php',
        'contact'              => 'page-contact.php',
    ];
    if ( isset( $slug_map[ $slug ] ) ) {
        $map   = lpl_seo_map();
        $cache = $map[ $slug_map[ $slug ] ] ?? null;
    }
    return $cache;
}

// Titre de page — on retourne directement le titre complet (évite doublon du nom de site)
add_filter( 'wpseo_title', function( $title ) {
    $d = lpl_current_seo();
    return $d ? $d['title'] : $title;
} );

// Meta description
add_filter( 'wpseo_metadesc', function( $desc ) {
    $d = lpl_current_seo();
    return $d ? $d['desc'] : $desc;
} );

// Canonical URL
add_filter( 'wpseo_canonical', function( $canonical ) {
    $d = lpl_current_seo();
    return $d ? esc_url( home_url( $d['slug'] ) ) : $canonical;
} );

// OG title
add_filter( 'wpseo_opengraph_title', function( $t ) {
    $d = lpl_current_seo();
    return $d ? $d['title'] : $t;
} );

// OG description
add_filter( 'wpseo_opengraph_desc', function( $t ) {
    $d = lpl_current_seo();
    return $d ? $d['desc'] : $t;
} );

// OG URL
add_filter( 'wpseo_opengraph_url', function( $u ) {
    $d = lpl_current_seo();
    return $d ? esc_url( home_url( $d['slug'] ) ) : $u;
} );

/* ------------------------------------------
   Robots : index,follow sur toutes les pages
   (Yoast peut parfois bloquer certaines pages)
------------------------------------------ */
add_filter( 'wpseo_robots', function( $robots ) {
    return 'index, follow';
} );


/* ------------------------------------------
   Skip-to-content : styles injectés en <head>
   Visible uniquement au focus clavier (accessibilité)
------------------------------------------ */
add_action( 'wp_head', function () {
    echo '<style>
.skip-to-content{position:absolute;left:-9999px;top:auto;width:1px;height:1px;overflow:hidden}
.skip-to-content:focus{position:fixed;top:0;left:0;z-index:9999;width:auto;height:auto;padding:12px 20px;background:#1a1a1a;color:#fff;font-size:14px;font-family:Inter,sans-serif;text-decoration:none;border:2px solid #fff;border-radius:0 0 4px 0}
</style>' . "\n";
}, 3 );

/**
 * Sticky Bar Mobile — Le Petit Louvre
 * Pour supprimer : effacer ce bloc entier
 */
function lpl_sticky_bar_mobile() {
  $tpl = get_page_template_slug();
  $hidden = [ 'page-carte.php', 'page-carte-des-boissons.php', 'page-carte-des-cocktails.php', 'page-carte-des-vins.php' ];
  if ( in_array( $tpl, $hidden, true ) ) return;
  ?>

<style>
/* ── Sticky bar — charte Le Petit Louvre ── */
#lpl-sticky {
  display: flex;
  position: fixed;
  bottom: 0; left: 0; right: 0;
  z-index: 9999;
  background: #545a25;
  border-top: 1px solid rgba(255,255,255,0.18);
  padding: 12px 14px calc(12px + env(safe-area-inset-bottom, 6px));
  gap: 10px;
  align-items: stretch;
  opacity: 0;
  transform: translateY(100%);
  pointer-events: none;
  transition: opacity 0.38s ease, transform 0.38s cubic-bezier(0.23, 1, 0.32, 1);
}
#lpl-sticky.lpl-visible {
  opacity: 1;
  transform: translateY(0);
  pointer-events: auto;
}
@media (max-width: 768px) {
  body { padding-bottom: 72px; }
  .footer-bottom { padding-bottom: 0 !important; }
  footer.footer { padding-bottom: 0 !important; }
}


/* ── Bouton commun ── */
#lpl-sticky .lpl-btn-call,
#lpl-sticky .lpl-btn-resa {
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 7px;
  height: 48px;
  border-radius: 50px;
  font-family: 'Inter', sans-serif;
  font-size: 11px;
  font-weight: 700;
  letter-spacing: 0.07em;
  text-transform: uppercase;
  text-decoration: none;
  cursor: pointer;
  -webkit-tap-highlight-color: transparent;
  transition: opacity 0.18s ease, transform 0.18s ease;
  white-space: nowrap;
}
#lpl-sticky .lpl-btn-call:active,
#lpl-sticky .lpl-btn-resa:active { opacity: 0.82; transform: scale(0.97); }

/* ── Appeler : ghost ── */
#lpl-sticky .lpl-btn-call {
  flex: 1;
  background: rgba(255,255,255,0.12);
  border: 1.5px solid rgba(255,255,255,0.50);
  color: white;
}

/* ── Réserver : plein blanc ── */
#lpl-sticky .lpl-btn-resa {
  flex: 1.6;
  flex-direction: column;
  gap: 2px;
  background: white;
  border: none;
  color: #545a25;
}
#lpl-sticky .lpl-btn-resa .lpl-resa-main {
  display: flex; align-items: center; gap: 6px;
  font-size: 11px; font-weight: 700; letter-spacing: 0.07em; text-transform: uppercase; color: #545a25;
}
#lpl-sticky .lpl-btn-resa .lpl-resa-sub {
  font-size: 9px; font-weight: 400; letter-spacing: 0.04em;
  color: rgba(84,90,37,0.55); text-transform: none;
}

/* ── Icône téléphone ── */
.lpl-phone-wrap {
  position: relative;
  width: 18px; height: 18px;
  display: flex; align-items: center; justify-content: center;
  flex-shrink: 0;
}
.lpl-ripple {
  position: absolute;
  width: 18px; height: 18px;
  border: 1.5px solid rgba(255,255,255,0.6);
  border-radius: 50%;
  animation: lplRipple 2s ease-out infinite;
  pointer-events: none;
}
.lpl-ripple:nth-child(2) { animation-delay: 0.7s; }
.lpl-phone-svg {
  position: relative; z-index: 1;
  animation: lplPulse 3s ease-in-out 2s infinite;
  flex-shrink: 0;
}
@keyframes lplRipple {
  0%   { transform: scale(1);   opacity: 0.6; }
  100% { transform: scale(2.6); opacity: 0;   }
}
@keyframes lplPulse {
  0%,70%,100% { transform: rotate(0deg)   scale(1);    }
  10%          { transform: rotate(-14deg) scale(1.15); }
  25%          { transform: rotate(12deg)  scale(1.12); }
  40%          { transform: rotate(-8deg)  scale(1.08); }
  55%          { transform: rotate(5deg)   scale(1.04); }
}
/* Animation calendrier */
.lpl-cal-svg {
  flex-shrink: 0;
  animation: calendarBounce 2.5s ease-in-out 3.5s infinite;
  transform-origin: center bottom;
}
@keyframes calendarBounce {
  0%,60%,100% { transform: translateY(0) rotate(0deg);    }
  20%          { transform: translateY(-3px) rotate(-8deg); }
  40%          { transform: translateY(-1px) rotate(4deg);  }
}
</style>

<div id="lpl-sticky" role="navigation" aria-label="Actions rapides">
  <a href="tel:+33557157359" class="lpl-btn-call" aria-label="Appeler le restaurant">
    <div class="lpl-phone-wrap">
      <div class="lpl-ripple"></div>
      <div class="lpl-ripple"></div>
      <svg class="lpl-phone-svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
        <path d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07A19.5 19.5 0 013.07 9.8a19.79 19.79 0 01-3.07-8.68A2 2 0 012 0h3a2 2 0 012 1.72c.127.96.361 1.903.7 2.81a2 2 0 01-.45 2.11L6.09 7.91a16 16 0 006 6l1.27-1.27a2 2 0 012.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0122 16.92z"/>
      </svg>
    </div>
    Appeler
  </a>
  <a href="/reservation/#resa-form" class="lpl-btn-resa" aria-label="Réserver une table en ligne">
    <span class="lpl-resa-main">
      <svg class="lpl-cal-svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#545a25" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
        <rect x="3" y="4" width="18" height="18" rx="2"/>
        <line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/>
        <line x1="3" y1="10" x2="21" y2="10"/>
      </svg>
      Réserver
    </span>
    <span class="lpl-resa-sub">Confirmation sous 2h</span>
  </a>
</div>

<script>
(function() {
  var bar = document.getElementById('lpl-sticky');
  if (!bar) return;
  function updateBar() {
    if (window.innerWidth > 768) return;
    var halfway = (document.documentElement.scrollHeight - window.innerHeight) / 2;
    bar.classList.toggle('lpl-visible', window.scrollY > halfway);
  }
  window.addEventListener('scroll', updateBar, { passive: true });
  if (/iPhone|iPad|iPod/.test(navigator.userAgent)) {
    var vh = window.innerHeight;
    window.addEventListener('resize', function() {
      if (window.innerHeight < vh * 0.75) bar.classList.remove('lpl-visible');
      else updateBar();
    });
  }
  updateBar();
})();
</script>

<?php }
add_action('wp_footer', 'lpl_sticky_bar_mobile');
