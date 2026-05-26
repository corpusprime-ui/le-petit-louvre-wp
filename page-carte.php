<?php
/*
 * Template Name: La Carte
 */
get_header();
$pid = get_the_ID();

/* ══════════════════════════════════════════════════════════════
   DONNÉES DU MENU — CPT lpl_menu (avec fallback hardcodé)
══════════════════════════════════════════════════════════════ */
$active_menu_id = function_exists( 'lpl_get_active_menu_id' ) ? lpl_get_active_menu_id() : 0;

if ( $active_menu_id && function_exists( 'lpl_get_menu_items' ) ) {

    /* ── Source : CPT "Les Menus" ── */
    $items_entrees  = lpl_get_menu_items( 'menu_entrees',        $active_menu_id );
    $items_partager = lpl_get_menu_items( 'menu_partager',       $active_menu_id );
    $items_plats    = lpl_get_menu_items( 'menu_plats',          $active_menu_id );
    $items_desserts = lpl_get_menu_items( 'menu_desserts_items', $active_menu_id );
    $glaces_boules  = lpl_field( 'menu_glaces_boules',  $active_menu_id, '1 boule, 2 boules, 3 boules' );
    $glaces_parfums = lpl_field( 'menu_glaces_parfums', $active_menu_id, '' );
    $cafe_prix      = lpl_field( 'menu_cafe_prix',      $active_menu_id, '10/12' );
    $menu_footnote  = lpl_field( 'menu_footnote',       $active_menu_id, '' );

} else {

    /* ── Fallback hardcodé (si CPT pas encore initialisé) ── */
    $tpl_img = get_template_directory_uri() . '/img/';
    /* photo       = pleine taille pour la lightbox
       photo_thumb = vignette 144px (2× retina pour 72px affiché) */
    $items_entrees = [
        [ 'nom' => 'La Burrata',                     'badge' => 'Nouveauté', 'desc' => "Tomates cerises, pêches, huile d'olive, basilic",                               'prix' => '11', 'photo' => $tpl_img.'plat-6-opt.jpg',  'photo_thumb' => $tpl_img.'plat-6-opt-thumb.jpg'  ],
        [ 'nom' => 'Ceviche de poisson aux agrumes',  'badge' => '',          'desc' => 'Marinade 3 agrumes, brunoise pastèque kiwi, graines de courge, pickles maison', 'prix' => '13', 'photo' => $tpl_img.'plat-7.jpg',      'photo_thumb' => $tpl_img.'plat-7-thumb.jpg'      ],
        [ 'nom' => 'Carpaccio de Boeuf',              'badge' => '',          'desc' => 'Tomates cerises, mesclun, copeaux de parmesan',                                  'prix' => '13', 'photo' => $tpl_img.'plat-22-opt.jpg', 'photo_thumb' => $tpl_img.'plat-22-opt-thumb.jpg' ],
        [ 'nom' => 'Feta grillée au miel',            'badge' => '',          'desc' => 'Tartare de légumes de saison, salade',                                           'prix' => '13', 'photo' => $tpl_img.'plat-8.jpg',      'photo_thumb' => $tpl_img.'plat-8-thumb.jpg'      ],
        [ 'nom' => 'Carpaccio de Melon',              'badge' => 'Nouveauté', 'desc' => "Jambon serrano, crumble d'olives noires et parmesan, basilic",                  'prix' => '13', 'photo' => $tpl_img.'plat-9.jpg',      'photo_thumb' => $tpl_img.'plat-9-thumb.jpg'      ],
    ];
    $items_partager = [
        [ 'nom' => 'Planche de charcuterie',      'badge' => 'Nouveauté', 'desc' => 'Terrine maison, charcuterie artisanale',  'prix' => '18', 'photo' => $tpl_img.'plat-15-opt.jpg', 'photo_thumb' => $tpl_img.'plat-15-opt-thumb.jpg' ],
        [ 'nom' => 'Planche de fromages',         'badge' => '',          'desc' => 'Sélection de fromages affinés',            'prix' => '18', 'photo' => $tpl_img.'plat-16-opt.jpg', 'photo_thumb' => $tpl_img.'plat-16-opt-thumb.jpg' ],
        [ 'nom' => 'Planche mixte',               'badge' => '',          'desc' => 'Entre copains',                             'prix' => '27', 'photo' => $tpl_img.'plat-17-opt.jpg', 'photo_thumb' => $tpl_img.'plat-17-opt-thumb.jpg' ],
        [ 'nom' => 'Cannelés salés',              'badge' => '',          'desc' => "Lard et piment d'espelette",               'prix' => '13', 'photo' => $tpl_img.'plat-10.jpg',     'photo_thumb' => $tpl_img.'plat-10-thumb.jpg'     ],
        [ 'nom' => 'Panier de légumes croquants', 'badge' => 'Nouveauté', 'desc' => 'Sauce tzatziki et tapenade',               'prix' => '13', 'photo' => $tpl_img.'plat-11.jpg',     'photo_thumb' => $tpl_img.'plat-11-thumb.jpg'     ],
    ];
    $items_plats = [
        [ 'nom' => 'Poulpe grillé',                 'badge' => 'Nouveauté', 'desc' => 'Risotto façon paëlla, sauce gremolata',                                                                                          'prix' => '25', 'photo' => $tpl_img.'plat-1.jpg',      'photo_thumb' => $tpl_img.'plat-1-thumb.jpg'      ],
        [ 'nom' => 'Toast avocado',                 'badge' => '',          'desc' => 'Pain de campagne toasté, guacamole, paprika fumé, oeuf parfait, chips de lard, graines de sésame, salade verte',               'prix' => '19', 'photo' => $tpl_img.'plat-2.jpg',      'photo_thumb' => $tpl_img.'plat-2-thumb.jpg'      ],
        [ 'nom' => 'Ceviche de poisson aux agrumes','badge' => '',          'desc' => "Marinade 3 agrumes, brunoise de pastèque et kiwi, avocat, menthe fraîche, graines de courge, quinoa aux légumes croquants et oignons frits", 'prix' => '22', 'photo' => $tpl_img.'plat-3-opt.jpg',  'photo_thumb' => $tpl_img.'plat-3-opt-thumb.jpg'  ],
        [ 'nom' => 'Tataki de boeuf',               'badge' => '',          'desc' => 'Marinade soja, citron vert, gingembre, herbes thaï, salade, frites maison',                                                     'prix' => '19', 'photo' => $tpl_img.'plat-4.jpg',      'photo_thumb' => $tpl_img.'plat-4-thumb.jpg'      ],
        [ 'nom' => 'Escalope milanaise',            'badge' => '',          'desc' => "Veau pané, polenta snackée, poêlée d'aubergines, tomates cerises et basilic frais",                                             'prix' => '23', 'photo' => $tpl_img.'plat-18-opt.jpg', 'photo_thumb' => $tpl_img.'plat-18-opt-thumb.jpg' ],
        [ 'nom' => 'Poisson sauvage à la plancha',  'badge' => '',          'desc' => 'Légumes de saison',                                                                                                              'prix' => '25', 'photo' => $tpl_img.'plat-12.jpg',     'photo_thumb' => $tpl_img.'plat-12-thumb.jpg'     ],
        [ 'nom' => 'Pièce du boucher 250g',         'badge' => '',          'desc' => 'Frites maison et salade, jus de viande corsé',                                                                                  'prix' => '27', 'photo' => $tpl_img.'plat-13.jpg',     'photo_thumb' => $tpl_img.'plat-13-thumb.jpg'     ],
        [ 'nom' => 'Aubergine farcie',              'badge' => '',          'desc' => 'Tomates, mozzarella, parmesan gratiné, basilic frais et salade',                                                                'prix' => '18', 'photo' => $tpl_img.'plat-14.jpg',     'photo_thumb' => $tpl_img.'plat-14-thumb.jpg'     ],
    ];
    $items_desserts = [
        [ 'nom' => 'Tarte aux Nectarines',              'badge' => '', 'desc' => 'Sorbet citron vert yuzu',                                                                                    'prix' => '11', 'photo' => $tpl_img.'plat-20-opt.jpg', 'photo_thumb' => $tpl_img.'plat-20-opt-thumb.jpg' ],
        [ 'nom' => 'Brioche Perdue Glacée Vanille',     'badge' => '', 'desc' => 'Caramel et fruits rouges',                                                                                   'prix' => '12', 'photo' => $tpl_img.'plat-21-opt.jpg', 'photo_thumb' => $tpl_img.'plat-21-opt-thumb.jpg' ],
        [ 'nom' => 'Tiramisu Maison',                   'badge' => '', 'desc' => '',                                                                                                           'prix' => '9',  'photo' => $tpl_img.'plat-5.jpg',      'photo_thumb' => $tpl_img.'plat-5-thumb.jpg'      ],
        [ 'nom' => 'Mousse au Chocolat',                'badge' => '', 'desc' => 'Croquant au praliné',                                                                                        'prix' => '10', 'photo' => $tpl_img.'plat-19.jpg',     'photo_thumb' => $tpl_img.'plat-19-thumb.jpg'     ],
        [ 'nom' => "L'Instant Fraise du Petit Louvre",  'badge' => '', 'desc' => "Fraise, menthe, basilic, glace vanille, crème fouettée, écrasé de biscuits, et coulis de fraise maison",   'prix' => '12', 'photo' => $tpl_img.'plat-3.jpg',      'photo_thumb' => $tpl_img.'plat-3-thumb.jpg'      ],
    ];
    $glaces_boules  = '1 boule, 2 boules, 3 boules';
    $glaces_parfums = "Vanille, chocolat, café, caramel au beurre d'isigny, noix de coco, fraise\nTagada, rhum raisin, pistache\nMangue, framboise, pêche de vigne, passion, citron vert Yuzu, melon";
    $cafe_prix      = '10/12';
    $menu_footnote  = '';
}

/* ── Champs ACF de la page (hero + titres sections) ── */
$carte_hero_label    = lpl_field( 'carte_hero_label',    $pid, 'Cuisine Fusion · Arcachon' );
$carte_hero_tagline  = lpl_field( 'carte_hero_tagline',  $pid, 'Par le chef Marco · Une invitation à partager saveurs et émotions' );
$carte_hero_images   = ( function_exists( 'get_field' ) && get_field( 'carte_hero_images', $pid ) ) ? get_field( 'carte_hero_images', $pid ) : [];
$carte_main_title    = lpl_field( 'carte_main_title',    $pid, 'LA CARTE' );
$titre_entrees       = lpl_field( 'carte_titre_entrees', $pid, 'Entrées' );
$titre_partager      = lpl_field( 'carte_titre_partager',$pid, 'À Partager' );
$titre_plats         = lpl_field( 'carte_titre_plats',   $pid, 'Plats' );
$titre_desserts      = lpl_field( 'carte_titre_desserts',$pid, 'Desserts' );

/* Images hero par défaut (si ACF vide) */
$hero_images_fallback = [
    [ 'url' => get_template_directory_uri() . '/img/plat-22-opt.jpg', 'alt' => 'Tartare de bœuf, chips de parmesan et œuf confit — Le Petit Louvre' ],
    [ 'url' => get_template_directory_uri() . '/img/plat-6.jpg',      'alt' => 'Burrata, tomates rôties et herbes fraîches — Le Petit Louvre' ],
    [ 'url' => get_template_directory_uri() . '/img/plat-3.jpg',      'alt' => 'Assiette gastronomique du chef au Petit Louvre' ],
    [ 'url' => get_template_directory_uri() . '/img/plat-5.jpg',      'alt' => 'Tiramisu maison en cocotte céramique — Le Petit Louvre' ],
];

/* ── PDF du menu actif ── */
$pdf_path = '';
$pdf_url  = '';
if ( $active_menu_id && function_exists( 'lpl_menu_pdf_path' ) ) {
    $menu_pdf_path = lpl_menu_pdf_path( $active_menu_id );
    if ( file_exists( $menu_pdf_path ) ) {
        /* PDF CPT généré → on l'utilise */
        $pdf_path = $menu_pdf_path;
        $pdf_url  = lpl_menu_pdf_url( $active_menu_id );
    } elseif ( function_exists( 'lpl_pdf_dir' ) ) {
        /* Fallback : ancien carte.pdf tant que le menu n'a pas été sauvegardé */
        $fallback_path = lpl_pdf_dir() . '/carte.pdf';
        if ( file_exists( $fallback_path ) ) {
            $pdf_path = $fallback_path;
            $pdf_url  = function_exists( 'lpl_pdf_url' ) ? lpl_pdf_url() : '';
        }
    }
} elseif ( function_exists( 'lpl_pdf_dir' ) ) {
    $pdf_path = lpl_pdf_dir() . '/carte.pdf';
    $pdf_url  = function_exists( 'lpl_pdf_url' ) ? lpl_pdf_url() : '';
}

/* ── Autres menus saisonniers disponibles ── */
$autres_menus = [];
if ( $active_menu_id ) {
    $q_autres = new WP_Query( [
        'post_type'      => 'lpl_menu',
        'post_status'    => 'publish',
        'posts_per_page' => 5,
        'post__not_in'   => [ $active_menu_id ],
        'meta_query'     => [ [ 'key' => 'menu_statut', 'value' => 'saisonnier', 'compare' => '=' ] ],
        'no_found_rows'  => true,
        'fields'         => 'ids',
    ] );
    $autres_menus = $q_autres->posts ?? [];
    wp_reset_postdata();
}
?>


<!-- ==========================================
     HERO CARTE — même structure que la homepage
========================================== -->
<section class="hero hero--carte" id="hero" aria-label="La carte du restaurant Le Petit Louvre">

  <!-- Photos hero (crossfade) — ACF ou fallback -->
  <?php
  $hero_imgs = ! empty( $carte_hero_images ) ? array_map( fn($r) => [
      'url' => $r['image']['url']   ?? '',
      'alt' => $r['alt']            ?? '',
  ], $carte_hero_images ) : $hero_images_fallback;
  foreach ( $hero_imgs as $i => $img ) :
      if ( empty( $img['url'] ) ) continue;
      $is_first = ( $i === 0 );
  ?>
  <img class="hero-bg<?php echo $is_first ? ' active' : ''; ?>"
       src="<?php echo esc_url( $img['url'] ); ?>"
       alt="<?php echo esc_attr( $img['alt'] ); ?>"
       <?php echo $is_first ? 'fetchpriority="high"' : 'loading="lazy"'; ?>
       decoding="async" width="1440" height="960">
  <?php endforeach; ?>

  <div class="hero-overlay-top" aria-hidden="true"></div>
  <div class="hero-overlay-mid" aria-hidden="true"></div>

  <!-- NAV HERO (identique homepage) -->
  <?php get_template_part('template-parts/site-header'); ?>

  <!-- CONTENU HERO CARTE -->
  <div class="hero-content">
    <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="hero-logo-mobile" aria-label="Accueil Le Petit Louvre">
      <img loading="lazy" src="<?php echo get_template_directory_uri(); ?>/img/logo.svg" alt="Le Petit Louvre" width="100" height="100">
    </a>
    <p class="hero-label"><?php echo esc_html( $carte_hero_label ); ?></p>
    <h1 class="hero-title">La Carte</h1>
    <p class="hero-tagline"><?php echo esc_html( $carte_hero_tagline ); ?></p>
    <div class="hero-cta d-flex flex-wrap gap-3 justify-content-center">
      <a href="#carte-menu" class="btn btn-filled btn-lg">Découvrir la Carte</a>
      <a href="<?php echo esc_url( home_url( '/carte-des-boissons/' ) ); ?>" class="btn btn-outline btn-lg">Carte des Boissons</a>
    </div>
  </div>

  <button class="hero-scroll-hint" id="heroScrollHint" aria-label="Découvrir la carte">
    <span>Découvrir</span>
    <div class="scroll-arrow"></div>
  </button>

</section>


<!-- ==========================================
     NAV ANCRE SECTIONS
========================================== -->
<nav class="carte-section-nav" aria-label="Sections de la carte">
  <div class="container">
    <div class="d-flex justify-content-center gap-4 gap-md-5">
      <a href="#entrees" class="carte-nav-link"><?php echo esc_html( $titre_entrees ); ?></a>
      <a href="#partager" class="carte-nav-link"><?php echo esc_html( $titre_partager ); ?></a>
      <a href="#plats" class="carte-nav-link"><?php echo esc_html( $titre_plats ); ?></a>
      <a href="#desserts" class="carte-nav-link"><?php echo esc_html( $titre_desserts ); ?></a>
    </div>
  </div>
</nav>

<!-- ==========================================
     MENU LA CARTE
========================================== -->
<section class="section-carte" id="carte-menu">

  <!-- Illustrations décoratives en parallaxe -->
  <img loading="lazy" id="carte-illu-1" class="carte-illu carte-illu--r1"
       src="<?php echo get_template_directory_uri(); ?>/img/moules.png"
       alt="" aria-hidden="true" loading="lazy">
  <img loading="lazy" id="carte-illu-2" class="carte-illu carte-illu--l"
       src="<?php echo get_template_directory_uri(); ?>/img/le-petit-louvre-illu.png"
       alt="" aria-hidden="true" loading="lazy">
  <img loading="lazy" id="carte-illu-3" class="carte-illu carte-illu--r2"
       src="<?php echo get_template_directory_uri(); ?>/img/apero.png"
       alt="" aria-hidden="true" loading="lazy">

  <div class="container">

    <!-- Titre "LA CARTE" avec lignes décoratives -->
    <div class="row">
      <div class="col-12 mt-5">
        <div class="carte-title-row d-flex align-items-center gap-3 mb-5">
          <span class="carte-title-line flex-grow-1"></span>
          <h2 class="carte-main-title"><?php echo esc_html( $carte_main_title ); ?></h2>
          <span class="carte-title-line flex-grow-1"></span>
        </div>
      </div>
    </div>

    <!-- ── ENTRÉES ── -->
    <div class="row justify-content-center">
      <div class="col-12 col-md-10 col-lg-8">
        <h3 class="carte-section-title text-center mb-4" id="entrees"><?php echo esc_html( $titre_entrees ); ?></h3>
        <?php lpl_render_section_items( $items_entrees ); ?>
      </div>
    </div><!-- /ENTRÉES -->


    <!-- ── À PARTAGER ── -->
    <div class="row justify-content-center mt-5">
      <div class="col-12 col-md-10 col-lg-8">
        <h3 class="carte-section-title text-center mb-4" id="partager"><?php echo esc_html( $titre_partager ); ?></h3>
        <?php lpl_render_section_items( $items_partager ); ?>
      </div>
    </div><!-- /À PARTAGER -->


    <!-- ── PLATS ── -->
    <div class="row justify-content-center mt-5">
      <div class="col-12 col-md-10 col-lg-8">
        <h3 class="carte-section-title text-center mb-4" id="plats"><?php echo esc_html( $titre_plats ); ?></h3>
        <?php lpl_render_section_items( $items_plats ); ?>
      </div>
    </div><!-- /PLATS -->


    <!-- ── DESSERTS ── -->
    <div class="row justify-content-center mt-5">
      <div class="col-12 col-md-10 col-lg-8">
        <h3 class="carte-section-title text-center mb-4" id="desserts"><?php echo esc_html( $titre_desserts ); ?></h3>

        <?php lpl_render_section_items( $items_desserts ); ?>

        <!-- Glaces & Sorbets -->
        <div class="carte-item py-2">
          <div class="carte-item-top d-flex align-items-center gap-2">
            <span class="carte-dish">Glaces &amp; Sorbets</span>
            <span class="carte-dots flex-grow-1 d-none d-sm-block" aria-hidden="true"></span>
            <span class="carte-price flex-shrink-0 ms-auto ms-sm-0">3/6/9&thinsp;€</span>
          </div>
          <p class="carte-desc mb-0"><?php echo esc_html( $glaces_boules ); ?></p>
          <?php if ( $glaces_parfums ) :
            foreach ( explode( "\n", $glaces_parfums ) as $ligne ) :
              $ligne = trim( $ligne );
              if ( $ligne ) : ?>
                <p class="carte-desc mb-0"><?php echo esc_html( $ligne ); ?></p>
              <?php endif;
            endforeach;
          endif; ?>
        </div>

        <!-- Café ou Thé Gourmand -->
        <div class="carte-item py-2 mt-4">
          <div class="carte-item-top d-flex align-items-center gap-2">
            <span class="carte-dish" style="text-transform:uppercase;letter-spacing:.06em;">Café ou Thé Gourmand</span>
            <span class="carte-dots flex-grow-1 d-none d-sm-block" aria-hidden="true"></span>
            <span class="carte-price flex-shrink-0 ms-auto ms-sm-0"><?php echo esc_html( $cafe_prix ); ?>&thinsp;€</span>
          </div>
        </div>

      </div>
    </div><!-- /DESSERTS -->


    <!-- NOTE DE BAS DE MENU -->
    <div class="row justify-content-center">
      <div class="col-12 col-md-10 col-lg-8">
        <?php if ( $menu_footnote ) : ?>
          <div class="carte-footnote text-center mt-4 pb-5">
            <p class="mb-0"><?php echo esc_html( $menu_footnote ); ?></p>
          </div>
        <?php else : ?>
          <div class="carte-footnote text-center mt-4 pb-5">
            <p class="mb-1"><strong>Garnitures&nbsp;:</strong> frites maison, quinoa de légumes croquants, salade verte, légumes de saison.</p>
            <p class="mb-1">🌿 Plat végétarien</p>
            <p class="mb-0">Prix net en &euro;&nbsp;&ndash;&nbsp;service compris&nbsp;&ndash;&nbsp;chèque non accepté&nbsp;&ndash;&nbsp;CB minimum 5&euro;</p>
          </div>
        <?php endif; ?>
      </div>
    </div>

  </div><!-- /.container -->
</section>


<!-- ==========================================
     TÉLÉCHARGER LA CARTE
========================================== -->
<section class="carte-dl-section">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-12 col-sm-8 col-md-5 col-lg-4 d-flex flex-column align-items-center gap-4">

        <?php if ( $pdf_path && file_exists( $pdf_path ) ) : ?>
          <a href="<?php echo esc_url( $pdf_url ); ?>"
             class="carte-dl-btn"
             download="carte-le-petit-louvre.pdf"
             target="_blank"
             rel="noopener">Télécharger notre carte</a>
        <?php else : ?>
          <span class="carte-dl-btn carte-dl-btn--disabled"
                title="Le PDF sera disponible après la prochaine sauvegarde du menu">
            Télécharger notre carte
          </span>
        <?php endif; ?>


      </div>
    </div>
  </div>
</section>


<style>
.carte-dl-btn--disabled {
  opacity: .45;
  cursor: not-allowed;
  pointer-events: none;
}
</style>

<script>
/* Barre d'annonce + parallaxe illustrations — spécifiques à cette page */
(function () {
  /* Bouton "Découvrir" → scroll vers la nav des sections */
  var scrollHintBtn = document.getElementById('heroScrollHint');
  if (scrollHintBtn) {
    scrollHintBtn.addEventListener('click', function () {
      var navEl = document.querySelector('.carte-section-nav');
      if (navEl) {
        navEl.scrollIntoView({ behavior: 'smooth', block: 'start' });
      }
    });
  }

  /* ── Scroll reveal : .carte-item et .carte-section-title ── */
  if ('IntersectionObserver' in window) {
    var revealObserver = new IntersectionObserver(function (entries) {
      entries.forEach(function (entry) {
        if (entry.isIntersecting) {
          entry.target.classList.add('is-visible');
          revealObserver.unobserve(entry.target);
        }
      });
    }, { threshold: 0.12 });

    document.querySelectorAll('.carte-item, .carte-section-title').forEach(function (el) {
      revealObserver.observe(el);
    });
  } else {
    /* Fallback sans IntersectionObserver */
    document.querySelectorAll('.carte-item, .carte-section-title').forEach(function (el) {
      el.classList.add('is-visible');
    });
  }

  /* ── Hint "cliquable" sur les vignettes (démarre 1.5s après le chargement) ── */
  setTimeout(function () {
    document.querySelectorAll('.carte-thumb-btn').forEach(function (btn) {
      btn.classList.add('hint-ready');
      /* Arrêt définitif après 1 seul clic */
      btn.addEventListener('click', function () {
        btn.classList.remove('hint-ready');
      }, { once: true });
    });
  }, 1500);

  var bar = document.getElementById('carteAnnounceBar');
  var closeBtn = document.getElementById('carteAnnounceClose');
  if (bar && closeBtn) {
    closeBtn.addEventListener('click', function () {
      bar.style.transition = 'max-height .35s ease, opacity .3s ease, padding .3s ease';
      bar.style.maxHeight  = bar.offsetHeight + 'px';
      requestAnimationFrame(function () {
        bar.style.maxHeight = '0';
        bar.style.opacity   = '0';
        bar.style.padding   = '0';
        bar.style.overflow  = 'hidden';
      });
    });
  }

  var illus = [
    { el: document.getElementById('carte-illu-1'), f: -0.13 },
    { el: document.getElementById('carte-illu-2'), f: -0.10 },
    { el: document.getElementById('carte-illu-3'), f: -0.16 }
  ];
  function onScroll() {
    var sy = window.pageYOffset;
    illus.forEach(function (d) {
      if (d.el) d.el.style.transform = 'translateY(' + (sy * d.f) + 'px)';
    });
  }
  window.addEventListener('scroll', onScroll, { passive: true });
  onScroll();
}());
</script>

<!-- ==========================================
     LIGHTBOX PHOTO — La Carte
========================================== -->
<div id="carteLightbox" class="carte-lightbox" role="dialog" aria-modal="true" aria-label="Photo du plat">
  <div class="carte-lightbox-inner">
    <button class="carte-lightbox-close" id="carteLightboxClose" aria-label="Fermer">✕</button>
    <img id="carteLightboxImg" src="" alt="">
    <p id="carteLightboxName" class="carte-lightbox-name"></p>
  </div>
</div>

<script>
(function () {
  var lb      = document.getElementById('carteLightbox');
  var lbImg   = document.getElementById('carteLightboxImg');
  var lbName  = document.getElementById('carteLightboxName');
  var lbClose = document.getElementById('carteLightboxClose');
  if (!lb || !lbImg) return;

  function openLightbox(photoUrl, dishName) {
    lbImg.src = photoUrl;
    lbImg.alt = dishName || '';
    lbName.textContent = dishName || '';
    lb.classList.add('is-open');
    document.body.style.overflow = 'hidden';
    lbClose.focus();
  }

  function closeLightbox() {
    lb.classList.remove('is-open');
    document.body.style.overflow = '';
    lbImg.src = '';
  }

  /* Clic sur la vignette ou le bouton pastille */
  document.addEventListener('click', function (e) {
    var btn = e.target.closest('.carte-thumb-btn, .carte-photo-btn');
    if (btn) {
      e.preventDefault();
      openLightbox(btn.dataset.photo, btn.dataset.name);
      return;
    }
    /* Clic sur l'overlay (hors image) */
    if (e.target === lb || e.target === lb.querySelector('.carte-lightbox-inner') ) {
      closeLightbox();
    }
  });

  /* Bouton fermeture */
  lbClose.addEventListener('click', closeLightbox);

  /* Touche ESC */
  document.addEventListener('keydown', function (e) {
    if ((e.key === 'Escape' || e.key === 'Esc') && lb.classList.contains('is-open')) {
      closeLightbox();
    }
  });
}());
</script>

<?php get_footer(); ?>
