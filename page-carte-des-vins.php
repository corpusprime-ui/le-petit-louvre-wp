<?php
/*
 * Template Name: Carte des Vins
 */
get_header();
$pid = get_the_ID();
$tpl = get_template_directory_uri();

/* ── ACF Hero ── */
$hero_label   = get_field('vins_hero_label')   ?: 'Sélection · Arcachon';
$hero_title   = get_field('vins_hero_title')   ?: 'Carte des Vins';
$hero_tagline = get_field('vins_hero_tagline') ?: 'Une cave soigneusement sélectionnée · Des accords pensés pour chaque plat';
$hero_images  = get_field('vins_hero_images')  ?: [];
?>

<!-- ==========================================
     HERO CARTE DES VINS
========================================== -->
<section class="hero" id="hero">

  <?php if ( $hero_images ) :
    foreach ( $hero_images as $i => $slide ) :
      $src = $slide['image']['url'] ?? '';
      $alt = $slide['alt'] ?: ( $slide['image']['alt'] ?? '' );
  ?>
  <img class="hero-bg<?php echo $i === 0 ? ' active' : ''; ?>"
       src="<?php echo esc_url( $src ); ?>"
       alt="<?php echo esc_attr( $alt ); ?>"
       <?php echo $i === 0 ? 'fetchpriority="high"' : 'loading="lazy"'; ?> decoding="async">
  <?php endforeach; else : ?>
  <img class="hero-bg active"
       src="<?php echo $tpl; ?>/img/bar-vins.jpg"
       alt="Cave à vins Le Petit Louvre"
       fetchpriority="high" decoding="async">
  <img loading="lazy" class="hero-bg"
       src="<?php echo $tpl; ?>/img/vins.jpg"
       alt="Service des vins Le Petit Louvre"
       decoding="async">
  <?php endif; ?>

  <div class="hero-overlay-top"></div>
  <div class="hero-overlay-mid"></div>

  <?php get_template_part('template-parts/site-header'); ?>

  <div class="hero-content">
    <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="hero-logo-mobile" aria-label="Accueil Le Petit Louvre">
      <img loading="lazy" src="<?php echo $tpl; ?>/img/logo.svg" alt="Le Petit Louvre" width="100" height="100">
    </a>
    <p class="hero-label"><?php echo esc_html( $hero_label ); ?></p>
    <h1 class="hero-title"><?php echo esc_html( $hero_title ); ?></h1>
    <p class="hero-tagline"><?php echo esc_html( $hero_tagline ); ?></p>
    <div class="hero-cta d-flex flex-wrap gap-3 justify-content-center">
      <a href="<?php echo esc_url( home_url( '/carte-des-cocktails/' ) ); ?>" class="btn btn-filled btn-lg">Nos cocktails</a>
      <a href="<?php echo esc_url( home_url( '/carte-des-boissons/' ) ); ?>" class="btn btn-outline btn-lg">Nos boissons</a>
      <a href="<?php echo esc_url( home_url( '/reservation/' ) ); ?>" class="btn btn-outline btn-lg">Réserver</a>
    </div>
  </div>

  <button class="hero-scroll-hint" id="heroScrollHint" data-target="vins-menu" aria-label="Découvrir">
    <span>Découvrir</span>
    <div class="scroll-arrow"></div>
  </button>

</section>


<!-- ==========================================
     NAV ENTRE CARTES
========================================== -->
<nav class="carte-section-nav" aria-label="Navigation entre les cartes">
  <div class="container">
    <div class="d-flex justify-content-center gap-4 gap-md-5 flex-wrap">
      <a href="<?php echo esc_url( home_url( '/carte-des-boissons/' ) ); ?>"  class="carte-nav-link">Carte des Boissons</a>
      <a href="<?php echo esc_url( home_url( '/carte-des-cocktails/' ) ); ?>" class="carte-nav-link">Carte des Cocktails</a>
      <a href="<?php echo esc_url( home_url( '/carte-des-vins/' ) ); ?>"      class="carte-nav-link active">Carte des Vins</a>
    </div>
  </div>
</nav>


<?php
/* ── Helper : section vins à triple prix (14cl / 28cl / 75cl) ── */
function lpl_render_vins_section( $acf_key, $titre, $fallback_items ) {
    $items = function_exists('get_field') ? get_field( $acf_key ) : [];
    $items = $items ?: $fallback_items;
    if ( ! $items ) return;
    ?>
    <div class="row justify-content-center mt-5">
      <div class="col-12 col-md-10 col-lg-9">
        <h3 class="carte-section-title text-center mb-4"><?php echo esc_html( $titre ); ?></h3>
        <div class="vins-header-row">
          <div></div>
          <div class="vins-cl-label">14cl</div>
          <div class="vins-cl-label">28cl</div>
          <div class="vins-cl-label">75cl</div>
        </div>
        <?php foreach ( $items as $item ) : ?>
        <div class="vins-row">
          <div class="vins-label">
            <div class="vins-name-row">
              <span class="vins-name"><?php echo esc_html( $item['nom'] ?? '' ); ?></span>
              <?php if ( ! empty( $item['badge'] ) ) : ?><span class="carte-badge"><?php echo esc_html( $item['badge'] ); ?></span><?php endif; ?>
              <span class="vins-dots" aria-hidden="true"></span>
            </div>
            <?php if ( ! empty( $item['description'] ) ) : ?><p class="vins-desc mb-0"><?php echo esc_html( $item['description'] ); ?></p><?php endif; ?>
          </div>
          <span class="vins-price<?php echo empty( $item['prix'] )   ? ' vins-price--empty' : ''; ?>"><?php echo esc_html( $item['prix']   ?? '' ); ?></span>
          <span class="vins-price<?php echo empty( $item['prix_2'] ) ? ' vins-price--empty' : ''; ?>"><?php echo esc_html( $item['prix_2'] ?? '' ); ?></span>
          <span class="vins-price<?php echo empty( $item['prix_3'] ) ? ' vins-price--empty' : ''; ?>"><?php echo esc_html( $item['prix_3'] ?? '' ); ?></span>
        </div>
        <?php endforeach; ?>
      </div>
    </div>
    <?php
}

/* ── Helper : section vins/cocktails à prix unique ── */
function lpl_render_vins_single_section( $acf_key, $titre, $volume_label, $fallback_items ) {
    $items = function_exists('get_field') ? get_field( $acf_key ) : [];
    $items = $items ?: $fallback_items;
    if ( ! $items ) return;
    ?>
    <div class="row justify-content-center mt-5">
      <div class="col-12 col-md-10 col-lg-9">
        <h3 class="carte-section-title text-center mb-4"><?php echo esc_html( $titre ); ?></h3>
        <?php if ( $volume_label ) : ?>
        <div class="vins-header-row vins-header-single"><div></div><div class="vins-cl-label"><?php echo esc_html( $volume_label ); ?></div></div>
        <?php endif; ?>
        <?php foreach ( $items as $item ) : ?>
        <div class="vins-row vins-row-single">
          <div class="vins-label">
            <div class="vins-name-row">
              <span class="vins-name"><?php echo esc_html( $item['nom'] ?? '' ); ?></span>
              <?php if ( ! empty( $item['badge'] ) ) : ?><span class="carte-badge"><?php echo esc_html( $item['badge'] ); ?></span><?php endif; ?>
              <span class="vins-dots" aria-hidden="true"></span>
            </div>
            <?php if ( ! empty( $item['description'] ) ) : ?><p class="vins-desc mb-0"><?php echo esc_html( $item['description'] ); ?></p><?php endif; ?>
          </div>
          <span class="vins-price"><?php echo esc_html( $item['prix'] ?? '' ); ?></span>
        </div>
        <?php endforeach; ?>
      </div>
    </div>
    <?php
}
?>

<!-- ==========================================
     MENU CARTE DES VINS
========================================== -->
<section class="section-carte" id="vins-menu">
  <div class="container">

    <div class="row">
      <div class="col-12 mt-5">
        <div class="carte-title-row d-flex align-items-center gap-3 mb-5">
          <span class="carte-title-line flex-grow-1"></span>
          <h2 class="carte-main-title">CARTE DES VINS</h2>
          <span class="carte-title-line flex-grow-1"></span>
        </div>
      </div>
    </div>


<?php
lpl_render_vins_section( 'vins_blancs', 'Blanc', [
    ['nom'=>'Provence Rollier',       'description'=>'Château de la Martinette « bio » 2023',                        'prix'=>'5&thinsp;€',  'prix_2'=>'10&thinsp;€', 'prix_3'=>'25&thinsp;€', 'badge'=>'Bio'],
    ['nom'=>'Côtes de Gascogne',      'description'=>'Domaine de Magnaut « moelleux » 2024',                        'prix'=>'6&thinsp;€',  'prix_2'=>'12&thinsp;€', 'prix_3'=>'27&thinsp;€', 'badge'=>''],
    ['nom'=>'Bordeaux Grave',         'description'=>'Château tour de Castres 2023',                                'prix'=>'7&thinsp;€',  'prix_2'=>'14&thinsp;€', 'prix_3'=>'34&thinsp;€', 'badge'=>''],
    ['nom'=>'Val de Loire',           'description'=>'Pouilly-fumé. La villaudière de Reverdy 2024',                'prix'=>'9&thinsp;€',  'prix_2'=>'18&thinsp;€', 'prix_3'=>'43&thinsp;€', 'badge'=>'Coup de cœur'],
    ['nom'=>'Provence',               'description'=>'Clos blanc. Château de la Martinette « bio » 2023',           'prix'=>'',            'prix_2'=>'',             'prix_3'=>'43&thinsp;€', 'badge'=>''],
    ['nom'=>'Rhône',                  'description'=>'Château de Valcombe 2024',                                     'prix'=>'',            'prix_2'=>'',             'prix_3'=>'31&thinsp;€', 'badge'=>''],
    ['nom'=>'Bordeaux',               'description'=>'Château Bertinerie 2024',                                      'prix'=>'',            'prix_2'=>'',             'prix_3'=>'31&thinsp;€', 'badge'=>''],
    ['nom'=>'Bourgogne',              'description'=>'Chablis. Dampt frères tradition 2022',                         'prix'=>'',            'prix_2'=>'',             'prix_3'=>'43&thinsp;€', 'badge'=>''],
    ['nom'=>'Bourgogne',              'description'=>'Santenay. Justin Girardin, Les Terrasses de Bievaux 2023',     'prix'=>'',            'prix_2'=>'',             'prix_3'=>'55&thinsp;€', 'badge'=>''],
]);
lpl_render_vins_section( 'vins_rouges', 'Rouge', [
    ['nom'=>'Bordeaux',               'description'=>'Château Bertinerie 2022',                                      'prix'=>'6&thinsp;€',  'prix_2'=>'12&thinsp;€', 'prix_3'=>'29&thinsp;€', 'badge'=>''],
    ['nom'=>'Côtes du Rhône',         'description'=>'Château de Valcombe 2022',                                     'prix'=>'6&thinsp;€',  'prix_2'=>'12&thinsp;€', 'prix_3'=>'29&thinsp;€', 'badge'=>''],
    ['nom'=>'Sud-Ouest',              'description'=>'Château Montus — Madiran 2020',                                'prix'=>'8&thinsp;€',  'prix_2'=>'16&thinsp;€', 'prix_3'=>'38&thinsp;€', 'badge'=>''],
    ['nom'=>'Bourgogne',              'description'=>'Pinot Noir. Côte de Nuits 2021',                               'prix'=>'',            'prix_2'=>'',             'prix_3'=>'48&thinsp;€', 'badge'=>''],
    ['nom'=>'Saint-Émilion Grand Cru','description'=>'Merlot dominant 2019',                                         'prix'=>'',            'prix_2'=>'',             'prix_3'=>'55&thinsp;€', 'badge'=>''],
]);
lpl_render_vins_section( 'vins_roses', 'Rosé', [
    ['nom'=>'Provence',               'description'=>'Château Miraval 2024',                                         'prix'=>'7&thinsp;€',  'prix_2'=>'14&thinsp;€', 'prix_3'=>'33&thinsp;€', 'badge'=>''],
    ['nom'=>'Bandol',                 'description'=>'Mourvèdre, Grenache 2023',                                     'prix'=>'8&thinsp;€',  'prix_2'=>'16&thinsp;€', 'prix_3'=>'38&thinsp;€', 'badge'=>''],
    ['nom'=>'Côtes de Gascogne',      'description'=>'Cabernet Franc 2024',                                          'prix'=>'5&thinsp;€',  'prix_2'=>'10&thinsp;€', 'prix_3'=>'24&thinsp;€', 'badge'=>''],
]);
lpl_render_vins_section( 'vins_champagnes', 'Champagnes & Pétillants', [
    ['nom'=>'Crémant de Bordeaux Brut',           'description'=>'Sémillon, Sauvignon',                              'prix'=>'7&thinsp;€',  'prix_2'=>'14&thinsp;€', 'prix_3'=>'32&thinsp;€',  'badge'=>''],
    ['nom'=>'Champagne Ruinart Blanc de Blancs',  'description'=>'100% Chardonnay · Bulles fines, notes briochées', 'prix'=>'',            'prix_2'=>'',             'prix_3'=>'95&thinsp;€',  'badge'=>''],
    ['nom'=>'Champagne Billecart-Salmon Rosé',    'description'=>'Pinot Noir, Chardonnay · Fruits rouges, finale fraîche', 'prix'=>'',    'prix_2'=>'',             'prix_3'=>'110&thinsp;€', 'badge'=>''],
]);
lpl_render_vins_single_section( 'vins_cocktails', 'Cocktails & Apéritifs', 'Prix', [
    ['nom'=>'Spritz Petit Louvre', 'description'=>'Apérol, Crémant de Bordeaux, eau pétillante, orange',            'prix'=>'12&thinsp;€','badge'=>''],
    ['nom'=>'Negroni',             'description'=>"Gin, Campari, Vermouth rouge, zeste d\u{2019}orange",            'prix'=>'13&thinsp;€','badge'=>''],
    ['nom'=>'Kir Royal',           'description'=>"Crémant de Bordeaux, crème de cassis de Bourgogne",              'prix'=>'11&thinsp;€','badge'=>''],
    ['nom'=>'Mojito Bassin',       'description'=>"Rhum blanc, menthe fraîche, citron vert, sirop d\u{2019}agave",  'prix'=>'13&thinsp;€','badge'=>''],
    ['nom'=>'Virgin Bassin',       'description'=>"Citron vert, menthe, gingembre, sirop d\u{2019}agave, eau gazeuse", 'prix'=>'8&thinsp;€','badge'=>''],
]);
?>

    <!-- NOTE BAS DE PAGE -->
    <div class="row justify-content-center">
      <div class="col-12 col-md-10 col-lg-9">
        <div class="carte-footnote text-center mt-4 pb-5">
          <p class="mb-1">Vins servis au verre (14&nbsp;cl), au pichet (28&nbsp;cl) ou à la bouteille (75&nbsp;cl).</p>
          <p class="mb-0">Prix net en &euro;&nbsp;&ndash;&nbsp;service compris&nbsp;&ndash;&nbsp;chèque non accepté&nbsp;&ndash;&nbsp;CB minimum 5&euro;</p>
        </div>
      </div>
    </div>

  </div><!-- /.container -->
</section>


<!-- CTA BAS DE PAGE -->
<section class="carte-dl-section">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-12 col-sm-8 col-md-5 col-lg-4 d-flex flex-column align-items-center gap-4">
        <a href="<?php echo esc_url( home_url( '/carte/' ) ); ?>" class="carte-dl-btn">Voir la carte des plats</a>
        <div class="d-flex align-items-center w-100 gap-3">
          <span class="carte-noel-sep flex-grow-1"></span>
          <a href="<?php echo esc_url( home_url( '/reservation/' ) ); ?>" class="carte-noel-link">
            <svg width="16" height="16" viewBox="0 0 16 16" fill="none" aria-hidden="true">
              <path d="M3 8h10M9 4l4 4-4 4" stroke="#33520b" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            Réserver une table
          </a>
          <span class="carte-noel-sep flex-grow-1"></span>
        </div>
      </div>
    </div>
  </div>
</section>

<?php get_footer(); ?>
