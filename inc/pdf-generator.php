<?php
/**
 * Le Petit Louvre — Générateur PDF de la carte
 *
 * - Génération déclenchée uniquement côté serveur (hook ACF save_post)
 * - Aucun endpoint public exposé
 * - Contenu entièrement échappé avant injection dans le PDF
 * - Verrou anti-flood via transient WordPress (30 secondes)
 * - Stockage dans wp-content/uploads/le-petit-louvre/ (hors webroot des scripts)
 */

if ( ! defined( 'ABSPATH' ) ) exit;

/* ── Chargement uniquement côté admin (économise ~150ms sur les pages publiques) ── */
if ( ! is_admin() ) return;

/* ── Autoload mPDF ── */
$mpdf_autoload = get_template_directory() . '/vendor/autoload.php';
if ( ! file_exists( $mpdf_autoload ) ) return; // mPDF non installé → on sort silencieusement
require_once $mpdf_autoload;
require_once get_template_directory() . '/inc/pdf-template.php';

use Mpdf\Mpdf;
use Mpdf\Config\ConfigVariables;
use Mpdf\Config\FontVariables;

/**
 * Chemin du dossier de destination du PDF.
 * Stocké dans uploads pour bénéficier des permissions WP.
 */
function lpl_pdf_dir(): string {
    $upload = wp_upload_dir();
    return trailingslashit( $upload['basedir'] ) . 'le-petit-louvre';
}

/**
 * URL publique du PDF (pour le bouton de téléchargement).
 */
function lpl_pdf_url(): string {
    $upload = wp_upload_dir();
    return trailingslashit( $upload['baseurl'] ) . 'le-petit-louvre/carte.pdf';
}

/**
 * Crée le dossier de destination s'il n'existe pas.
 * Ajoute un .htaccess pour bloquer l'exécution de scripts dans ce dossier.
 */
function lpl_pdf_ensure_dir(): bool {
    $dir = lpl_pdf_dir();

    if ( ! file_exists( $dir ) ) {
        if ( ! wp_mkdir_p( $dir ) ) return false;
    }

    // Sécurité : bloquer l'exécution PHP dans ce dossier
    $htaccess = $dir . '/.htaccess';
    if ( ! file_exists( $htaccess ) ) {
        $rules = "Options -Indexes\n<Files *.php>\n    Order allow,deny\n    Deny from all\n</Files>\n";
        file_put_contents( $htaccess, $rules );
    }

    // Sécurité : empêcher la navigation dans le dossier
    $index = $dir . '/index.php';
    if ( ! file_exists( $index ) ) {
        file_put_contents( $index, '<?php // Silence is golden' );
    }

    return true;
}

/**
 * Convertit une URL de media (upload ou thème) en chemin absolu sur le serveur.
 * Nécessaire pour que mPDF puisse lire les images directement depuis le filesystem.
 */
function lpl_url_to_abspath( string $url ): string {
    if ( empty( $url ) ) return '';
    $upload = wp_upload_dir();
    if ( strpos( $url, $upload['baseurl'] ) === 0 ) {
        return str_replace( $upload['baseurl'], $upload['basedir'], $url );
    }
    $theme_url = get_template_directory_uri();
    if ( strpos( $url, $theme_url ) === 0 ) {
        return str_replace( $theme_url, get_template_directory(), $url );
    }
    return ''; // URL externe → on ignore pour le PDF
}

/**
 * Collecte les données de la carte depuis les champs ACF hardcodés (fallback si ACF vide).
 *
 * @param int $pid  Post ID de la page La Carte
 * @return array
 */
function lpl_pdf_collect_data( int $pid ): array {

    /* ── Helper local : sanitize string ── */
    $s = fn( $v ) => sanitize_text_field( $v ?? '' );

    $img   = fn( string $f ) => get_template_directory() . '/img/' . $f;
    $thumb = fn( string $f ) => get_template_directory() . '/img/' . str_replace( '.jpg', '-thumb.jpg', $f );

    /* ── ENTRÉES ── */
    $entrees = [
        [ 'nom' => 'La Burrata',                     'badge' => 'Nouveauté', 'desc' => 'Tomates cerises, pêches, huile d\'olive, basilic',                                'prix' => '11', 'photo' => $img('plat-6-opt.jpg'),  'photo_thumb' => $thumb('plat-6-opt.jpg')  ],
        [ 'nom' => 'Ceviche de poisson aux agrumes',  'badge' => '',          'desc' => 'Marinade 3 agrumes, brunoise pastèque kiwi, graines de courge, pickles maison',   'prix' => '13', 'photo' => $img('plat-7.jpg'),      'photo_thumb' => $thumb('plat-7.jpg')      ],
        [ 'nom' => 'Carpaccio de Boeuf',              'badge' => '',          'desc' => 'Tomates cerises, mesclun, copeaux de parmesan',                                    'prix' => '13', 'photo' => $img('plat-22-opt.jpg'), 'photo_thumb' => $thumb('plat-22-opt.jpg') ],
        [ 'nom' => 'Feta grillée au miel',            'badge' => '',          'desc' => 'Tartare de légumes de saison, salade',                                             'prix' => '13', 'photo' => $img('plat-8.jpg'),      'photo_thumb' => $thumb('plat-8.jpg')      ],
        [ 'nom' => 'Carpaccio de Melon',              'badge' => 'Nouveauté', 'desc' => 'Jambon serrano, crumble d\'olives noires et parmesan, basilic',                   'prix' => '13', 'photo' => $img('plat-9.jpg'),      'photo_thumb' => $thumb('plat-9.jpg')      ],
    ];

    /* ── À PARTAGER ── */
    $partager = [
        [ 'nom' => 'Planche de charcuterie',      'badge' => 'Nouveauté', 'desc' => 'Terrine maison, charcuterie artisanale',  'prix' => '18', 'photo' => $img('plat-15-opt.jpg'), 'photo_thumb' => $thumb('plat-15-opt.jpg') ],
        [ 'nom' => 'Planche de fromages',         'badge' => '',           'desc' => 'Sélection de fromages affinés',           'prix' => '18', 'photo' => $img('plat-16-opt.jpg'), 'photo_thumb' => $thumb('plat-16-opt.jpg') ],
        [ 'nom' => 'Planche mixte',               'badge' => '',           'desc' => 'Entre copains',                            'prix' => '27', 'photo' => $img('plat-17-opt.jpg'), 'photo_thumb' => $thumb('plat-17-opt.jpg') ],
        [ 'nom' => 'Cannelés salés',              'badge' => '',           'desc' => 'Lard et piment d\'espelette',              'prix' => '13', 'photo' => $img('plat-10.jpg'),     'photo_thumb' => $thumb('plat-10.jpg')     ],
        [ 'nom' => 'Panier de légumes croquants', 'badge' => 'Nouveauté', 'desc' => 'Sauce tzatziki et tapenade',                'prix' => '13', 'photo' => $img('plat-11.jpg'),     'photo_thumb' => $thumb('plat-11.jpg')     ],
    ];

    /* ── PLATS ── */
    $plats = [
        [ 'nom' => 'Poulpe grillé',               'badge' => 'Nouveauté', 'desc' => 'Risotto façon paëlla, sauce gremolata',                                       'prix' => '25', 'photo' => $img('plat-1.jpg'),      'photo_thumb' => $thumb('plat-1.jpg')      ],
        [ 'nom' => 'Toast avocado',               'badge' => '',           'desc' => 'Pain de campagne toasté, guacamole, paprika fumé, oeuf parfait, chips de lard', 'prix' => '19', 'photo' => $img('plat-2.jpg'),      'photo_thumb' => $thumb('plat-2.jpg')      ],
        [ 'nom' => 'Ceviche de poisson',          'badge' => '',           'desc' => 'Marinade 3 agrumes, avocat, menthe fraîche, quinoa aux légumes croquants',     'prix' => '22', 'photo' => $img('plat-3-opt.jpg'),  'photo_thumb' => $thumb('plat-3-opt.jpg')  ],
        [ 'nom' => 'Tataki de boeuf',             'badge' => '',           'desc' => 'Marinade soja, citron vert, gingembre, herbes thaï, salade, frites maison',    'prix' => '19', 'photo' => $img('plat-4.jpg'),      'photo_thumb' => $thumb('plat-4.jpg')      ],
        [ 'nom' => 'Escalope milanaise',          'badge' => '',           'desc' => 'Veau pané, polenta snackée, poêlée d\'aubergines, tomates cerises et basilic', 'prix' => '23', 'photo' => $img('plat-18-opt.jpg'), 'photo_thumb' => $thumb('plat-18-opt.jpg') ],
        [ 'nom' => 'Poisson sauvage à la plancha','badge' => '',           'desc' => 'Légumes de saison',                                                             'prix' => '25', 'photo' => $img('plat-12.jpg'),     'photo_thumb' => $thumb('plat-12.jpg')     ],
        [ 'nom' => 'Pièce du boucher 250g',       'badge' => '',           'desc' => 'Frites maison et salade, jus de viande corsé',                                 'prix' => '27', 'photo' => $img('plat-13.jpg'),     'photo_thumb' => $thumb('plat-13.jpg')     ],
        [ 'nom' => 'Aubergine farcie',            'badge' => '',           'desc' => 'Tomates, mozzarella, parmesan gratiné, basilic frais et salade',                'prix' => '18', 'photo' => $img('plat-14.jpg'),     'photo_thumb' => $thumb('plat-14.jpg')     ],
    ];

    /* ── DESSERTS (ACF si renseignés, sinon fallback) ── */
    $desserts = [];
    if ( function_exists( 'have_rows' ) && have_rows( 'desserts_items', $pid ) ) {
        while ( have_rows( 'desserts_items', $pid ) ) {
            the_row();
            $desserts[] = [
                'nom'   => $s( get_sub_field( 'nom' ) ),
                'badge' => $s( get_sub_field( 'badge' ) ),
                'desc'  => $s( get_sub_field( 'description' ) ),
                'prix'  => $s( get_sub_field( 'prix' ) ),
            ];
        }
    } else {
        $img   = fn( string $f ) => get_template_directory() . '/img/' . $f;
        $thumb = fn( string $f ) => get_template_directory() . '/img/' . str_replace( '.jpg', '-thumb.jpg', $f );
        $desserts = [
            [ 'nom' => 'Tarte aux Nectarines',              'badge' => '', 'desc' => 'Sorbet citron vert yuzu',                                                               'prix' => '11', 'photo' => $img('plat-20-opt.jpg'), 'photo_thumb' => $thumb('plat-20-opt.jpg') ],
            [ 'nom' => 'Brioche Perdue Glacée Vanille',     'badge' => '', 'desc' => 'Caramel et fruits rouges',                                                               'prix' => '12', 'photo' => $img('plat-21-opt.jpg'), 'photo_thumb' => $thumb('plat-21-opt.jpg') ],
            [ 'nom' => 'Tiramisu Maison',                   'badge' => '', 'desc' => '',                                                                                       'prix' => '9',  'photo' => $img('plat-5.jpg'),      'photo_thumb' => $thumb('plat-5.jpg')      ],
            [ 'nom' => 'Mousse au Chocolat',                'badge' => '', 'desc' => 'Croquant au praliné',                                                                    'prix' => '10', 'photo' => $img('plat-19.jpg'),     'photo_thumb' => $thumb('plat-19.jpg')     ],
            [ 'nom' => "L'Instant Fraise du Petit Louvre", 'badge' => '', 'desc' => 'Fraise, menthe, basilic, glace vanille, crème fouettée, écrasé de biscuits',             'prix' => '12', 'photo' => $img('plat-3.jpg'),      'photo_thumb' => $thumb('plat-3.jpg')      ],
        ];
    }

    $glaces_boules  = function_exists('get_field') ? ( get_field('desserts_glaces_boules',  $pid) ?: '1 boule, 2 boules, 3 boules' ) : '1 boule, 2 boules, 3 boules';
    $glaces_parfums = function_exists('get_field') ? ( get_field('desserts_glaces_parfums', $pid) ?: '' )                            : '';
    $cafe_prix      = function_exists('get_field') ? ( get_field('desserts_cafe_prix',      $pid) ?: '10/12' )                       : '10/12';

    return compact( 'entrees', 'partager', 'plats', 'desserts', 'glaces_boules', 'glaces_parfums', 'cafe_prix' ) + [
        'footnote' => 'Prix nets en € · Service compris · Chèque non accepté · CB minimum 5€ · 🌿 Plat végétarien disponible',
    ];
}

/**
 * Génère (ou régénère) le PDF de la carte.
 *
 * @param int $pid  Post ID de la page La Carte
 * @return bool     true si succès, false si erreur
 */
function lpl_generate_carte_pdf( int $pid ): bool {

    /* ── Verrou anti-flood : 30 secondes entre deux générations ── */
    $lock_key = 'lpl_pdf_lock_' . $pid;
    if ( get_transient( $lock_key ) ) return false;
    set_transient( $lock_key, 1, 30 );

    /* ── Vérification des permissions ── */
    if ( ! current_user_can( 'edit_pages' ) ) return false;

    /* ── Préparer le dossier ── */
    if ( ! lpl_pdf_ensure_dir() ) return false;

    /* ── Collecter les données ── */
    $data = lpl_pdf_collect_data( $pid );

    /* ── Générer le HTML ── */
    $html = lpl_pdf_get_html( $data );

    /* ── Initialiser mPDF ── */
    try {
        $mpdf = new Mpdf( [
            'mode'            => 'utf-8',
            'format'          => 'A4',
            'margin_top'      => 10,
            'margin_bottom'   => 10,
            'margin_left'     => 12,
            'margin_right'    => 12,
            'tempDir'         => sys_get_temp_dir(),
            'setAutoTopMargin'=> false,
        ] );

        $mpdf->SetTitle( 'La Carte — Le Petit Louvre' );
        $mpdf->SetAuthor( 'Le Petit Louvre Arcachon' );
        $mpdf->SetSubject( 'Menu du restaurant Le Petit Louvre' );
        $mpdf->SetCreator( 'Le Petit Louvre WP Theme' );

        /* Désactiver les liens externes pour la sécurité */
        $mpdf->allow_charset_conversion = false;

        $mpdf->WriteHTML( $html );

        $dest = lpl_pdf_dir() . '/carte.pdf';
        $mpdf->Output( $dest, 'F' ); // F = enregistrement fichier

        return file_exists( $dest );

    } catch ( \Exception $e ) {
        error_log( '[LPL PDF] Erreur génération : ' . $e->getMessage() );
        return false;
    }
}

/* ══════════════════════════════════════════════════════════════
   HOOK ACF — Régénération automatique à la sauvegarde
   Déclenché uniquement quand la page La Carte est sauvegardée
══════════════════════════════════════════════════════════════ */
add_action( 'acf/save_post', function ( $post_id ) {

    /* Vérifications de sécurité */
    if ( wp_is_post_revision( $post_id ) )   return;
    if ( wp_is_post_autosave( $post_id ) )   return;
    if ( ! is_numeric( $post_id ) )          return;

    /* Ne déclencher que sur la page "La Carte" (template page-carte.php) */
    $template = get_post_meta( (int) $post_id, '_wp_page_template', true );
    if ( $template !== 'page-carte.php' ) return;

    /* Vérifier les capacités utilisateur */
    if ( ! current_user_can( 'edit_page', (int) $post_id ) ) return;

    /* Vérifier le nonce WordPress (protection CSRF) */
    if ( ! isset( $_POST['_wpnonce'] ) ) return;
    if ( ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['_wpnonce'] ) ), 'update-post_' . $post_id ) ) return;

    /* Lancer la génération */
    lpl_generate_carte_pdf( (int) $post_id );

}, 20 ); // priorité 20 = après la sauvegarde ACF (priorité 10)


/* ══════════════════════════════════════════════════════════════
   FONCTIONS PDF — CPT lpl_menu
   Chaque menu CPT génère son propre PDF : menu-{ID}.pdf
══════════════════════════════════════════════════════════════ */

/**
 * Chemin absolu du fichier PDF pour un menu CPT.
 */
function lpl_menu_pdf_path( int $post_id ): string {
    return lpl_pdf_dir() . '/menu-' . $post_id . '.pdf';
}

/**
 * URL publique du PDF pour un menu CPT.
 */
function lpl_menu_pdf_url( int $post_id ): string {
    $upload = wp_upload_dir();
    return trailingslashit( $upload['baseurl'] ) . 'le-petit-louvre/menu-' . $post_id . '.pdf';
}

/**
 * Collecte les données d'un menu CPT pour la génération PDF.
 * Lit les repeaters ACF du CPT (menu_entrees, menu_partager, menu_plats, menu_desserts_items…)
 *
 * @param int $post_id  ID du post CPT lpl_menu
 * @return array
 */
function lpl_pdf_collect_menu_data( int $post_id ): array {

    $s = fn( $v ) => sanitize_text_field( $v ?? '' );

    /* ── Lecture d'un repeater → tableau d'items ── */
    $collect = function ( string $field ) use ( $post_id, $s ): array {
        $items = [];
        if ( ! function_exists( 'have_rows' ) || ! have_rows( $field, $post_id ) ) return $items;
        while ( have_rows( $field, $post_id ) ) {
            the_row();
            $photo_field  = get_sub_field( 'photo' );
            $photo_path   = '';
            $photo_thumb  = '';
            if ( is_array( $photo_field ) && ! empty( $photo_field['url'] ) ) {
                $photo_path  = lpl_url_to_abspath( $photo_field['sizes']['large']     ?? $photo_field['url'] );
                $photo_thumb = lpl_url_to_abspath( $photo_field['sizes']['thumbnail'] ?? $photo_field['url'] );
            } elseif ( is_string( $photo_field ) && $photo_field ) {
                $photo_path  = lpl_url_to_abspath( $photo_field );
                $photo_thumb = $photo_path;
            }
            $items[] = [
                'nom'         => $s( get_sub_field( 'nom' ) ),
                'badge'       => $s( get_sub_field( 'badge' ) ),
                'desc'        => $s( get_sub_field( 'description' ) ),
                'prix'        => $s( get_sub_field( 'prix' ) ),
                'photo'       => $photo_path,
                'photo_thumb' => $photo_thumb,
            ];
        }
        return $items;
    };

    $entrees  = $collect( 'menu_entrees' );
    $partager = $collect( 'menu_partager' );
    $plats    = $collect( 'menu_plats' );
    $desserts = $collect( 'menu_desserts_items' );

    $gf = fn( $k, $fb ) => function_exists( 'get_field' ) ? ( get_field( $k, $post_id ) ?: $fb ) : $fb;

    $glaces_boules  = $gf( 'menu_glaces_boules',  '1 boule, 2 boules, 3 boules' );
    $glaces_parfums = $gf( 'menu_glaces_parfums',  '' );
    $cafe_prix      = $gf( 'menu_cafe_prix',       '10/12' );
    $footnote       = $gf( 'menu_footnote',        'Prix nets en € · Service compris · Chèque non accepté · CB minimum 5€' );

    return compact( 'entrees', 'partager', 'plats', 'desserts', 'glaces_boules', 'glaces_parfums', 'cafe_prix', 'footnote' );
}

/**
 * Génère (ou régénère) le PDF d'un menu CPT.
 *
 * @param int $post_id  ID du post CPT lpl_menu
 * @return bool         true si succès, false sinon
 */
function lpl_generate_menu_pdf( int $post_id ): bool {

    /* ── Verrou anti-flood ── */
    $lock_key = 'lpl_menu_pdf_lock_' . $post_id;
    if ( get_transient( $lock_key ) ) return false;
    set_transient( $lock_key, 1, 30 );

    /* ── Permissions ── */
    if ( ! current_user_can( 'edit_posts' ) ) return false;
    if ( ! lpl_pdf_ensure_dir() ) return false;

    /* ── Collecte + template HTML ── */
    $data = lpl_pdf_collect_menu_data( $post_id );
    $html = lpl_pdf_get_html( $data );

    /* ── Génération mPDF ── */
    try {
        $mpdf = new Mpdf( [
            'mode'             => 'utf-8',
            'format'           => 'A4',
            'margin_top'       => 10,
            'margin_bottom'    => 10,
            'margin_left'      => 12,
            'margin_right'     => 12,
            'tempDir'          => sys_get_temp_dir(),
            'setAutoTopMargin' => false,
        ] );

        $titre = get_the_title( $post_id ) ?: 'Menu';
        $mpdf->SetTitle( $titre . ' — Le Petit Louvre' );
        $mpdf->SetAuthor( 'Le Petit Louvre Arcachon' );
        $mpdf->SetSubject( 'Menu du restaurant Le Petit Louvre' );
        $mpdf->SetCreator( 'Le Petit Louvre WP Theme' );
        $mpdf->allow_charset_conversion = false;

        $mpdf->WriteHTML( $html );

        $dest = lpl_menu_pdf_path( $post_id );
        $mpdf->Output( $dest, 'F' );

        return file_exists( $dest );

    } catch ( \Exception $e ) {
        error_log( '[LPL PDF] Erreur menu #' . $post_id . ' : ' . $e->getMessage() );
        return false;
    }
}

/* ══════════════════════════════════════════════════════════════
   HOOK ACF — Régénération PDF à la sauvegarde d'un menu CPT
══════════════════════════════════════════════════════════════ */
add_action( 'acf/save_post', function ( $post_id ) {

    if ( wp_is_post_revision( $post_id ) )  return;
    if ( wp_is_post_autosave( $post_id ) )  return;
    if ( ! is_numeric( $post_id ) )         return;

    /* Ne déclencher que sur le CPT lpl_menu */
    $post = get_post( (int) $post_id );
    if ( ! $post || $post->post_type !== 'lpl_menu' ) return;
    if ( $post->post_status !== 'publish' )            return;

    /* Vérifier les capacités */
    if ( ! current_user_can( 'edit_post', (int) $post_id ) ) return;

    /* Vérifier le nonce */
    if ( ! isset( $_POST['_wpnonce'] ) ) return;
    if ( ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['_wpnonce'] ) ), 'update-post_' . $post_id ) ) return;

    lpl_generate_menu_pdf( (int) $post_id );

}, 20 );
