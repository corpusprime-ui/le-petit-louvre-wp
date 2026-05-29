<?php
/*
 * Template Name: Carte des Vins
 */
get_header();
$pid = get_the_ID();
$tpl = esc_url( get_template_directory_uri() );

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
      <a href="<?php echo esc_url( home_url( '/reservation/' ) ); ?>" class="btn btn-outline btn-lg">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" class="icon-cal" style="margin-right:7px;flex-shrink:0;">
          <rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/>
        </svg>
        Réserver
      </a>
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
  <div class="carte-nav-inner">
    <a href="<?php echo esc_url( home_url( '/carte-des-vins/' ) ); ?>"      class="carte-nav-link active">Carte des Vins</a>
    <a href="<?php echo esc_url( home_url( '/carte-des-boissons/' ) ); ?>"  class="carte-nav-link">Carte des Boissons</a>
    <a href="<?php echo esc_url( home_url( '/carte-des-cocktails/' ) ); ?>" class="carte-nav-link">Carte des Cocktails</a>
  </div>
</nav>


<?php
/* ── Helper : section vins à triple prix ── */
function lpl_render_vins_section( $acf_key, $titre, $fallback_items, $l1 = '14cl', $l2 = '28cl', $l3 = '75cl' ) {
    $items = function_exists('get_field') ? get_field( $acf_key ) : [];
    $items = $items ?: $fallback_items;
    if ( ! $items ) return;
    ?>
    <div class="row justify-content-center mt-5">
      <div class="col-12 col-md-10 col-lg-9">
        <h3 class="carte-section-title text-center mb-4"><?php echo esc_html( $titre ); ?></h3>
        <div class="vins-header-row">
          <div></div>
          <div class="vins-cl-label"><?php echo esc_html( $l1 ); ?></div>
          <div class="vins-cl-label"><?php echo esc_html( $l2 ); ?></div>
          <div class="vins-cl-label"><?php echo esc_html( $l3 ); ?></div>
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
/* ── Titres de sections (éditables en back-office) ── */
$t_blancs      = get_field('vins_titre_blancs')     ?: 'Blanc';
$t_rouges      = get_field('vins_titre_rouges')     ?: 'Rouge';
$t_roses       = get_field('vins_titre_roses')      ?: 'Rosé';
$t_champagnes  = get_field('vins_titre_champagnes') ?: 'Champagne';

lpl_render_vins_section( 'vins_blancs', $t_blancs, [
    ['nom'=>'Provence Rollier',  'description'=>'Château de la Martinette « bio » 2023',                    'prix'=>'5&thinsp;€', 'prix_2'=>'10&thinsp;€', 'prix_3'=>'25&thinsp;€', 'badge'=>''],
    ['nom'=>'Côtes de Gascogne', 'description'=>'Domaine de Magnaut « moelleux » 2024',                    'prix'=>'6&thinsp;€', 'prix_2'=>'12&thinsp;€', 'prix_3'=>'27&thinsp;€', 'badge'=>''],
    ['nom'=>'Bordeaux Graves',   'description'=>'Château tour de Castres 2023',                            'prix'=>'7&thinsp;€', 'prix_2'=>'14&thinsp;€', 'prix_3'=>'34&thinsp;€', 'badge'=>''],
    ['nom'=>'Val de Loire',      'description'=>'Pouilly-fumé. La villaudière de Reverdy 2024',            'prix'=>'9&thinsp;€', 'prix_2'=>'18&thinsp;€', 'prix_3'=>'43&thinsp;€', 'badge'=>''],
    ['nom'=>'Provence',          'description'=>'Clos blanc. Château de la Martinette « bio » 2023',       'prix'=>'',           'prix_2'=>'',            'prix_3'=>'43&thinsp;€', 'badge'=>''],
    ['nom'=>'Rhône',             'description'=>'Château de Valcombe 2024',                                'prix'=>'',           'prix_2'=>'',            'prix_3'=>'31&thinsp;€', 'badge'=>''],
    ['nom'=>'Bordeaux',          'description'=>'Blaye. Château Bertinerie 2024',                          'prix'=>'',           'prix_2'=>'',            'prix_3'=>'31&thinsp;€', 'badge'=>''],
    ['nom'=>'Bourgogne',         'description'=>'Chablis. Dampt frères tradition 2022',                    'prix'=>'',           'prix_2'=>'',            'prix_3'=>'43&thinsp;€', 'badge'=>''],
    ['nom'=>'Bourgogne',         'description'=>'Santenay. Justin Girardin, Les Terrasses de Bievaux 2023','prix'=>'',           'prix_2'=>'',            'prix_3'=>'55&thinsp;€', 'badge'=>''],
]);
lpl_render_vins_section( 'vins_rouges', $t_rouges, [
    ['nom'=>'Rhône',         'description'=>'Côtes du Rhône. Domaine de l\'Obrieu les frangines « bio » 2022', 'prix'=>'6&thinsp;€', 'prix_2'=>'12&thinsp;€', 'prix_3'=>'27&thinsp;€', 'badge'=>''],
    ['nom'=>'Val de Loire',  'description'=>'Bourgueil. Clos de l\'Abbaye 2021',                               'prix'=>'7&thinsp;€', 'prix_2'=>'14&thinsp;€', 'prix_3'=>'29&thinsp;€', 'badge'=>''],
    ['nom'=>'Vin du Monde',  'description'=>'Argentine. Festivo Malbec 2023',                                  'prix'=>'8&thinsp;€', 'prix_2'=>'16&thinsp;€', 'prix_3'=>'32&thinsp;€', 'badge'=>''],
    ['nom'=>'Bordeaux',      'description'=>'Pessac Leognan. Domaine de la Roche 2019',                        'prix'=>'9&thinsp;€', 'prix_2'=>'18&thinsp;€', 'prix_3'=>'39&thinsp;€', 'badge'=>''],
    ['nom'=>'Bourgogne',     'description'=>'Côte de nuits. Dupasquier les Vignottes 2022',                    'prix'=>'',           'prix_2'=>'',            'prix_3'=>'49&thinsp;€', 'badge'=>''],
    ['nom'=>'Bourgogne',     'description'=>'Chassagne-Montrachet. Louis Latour 2021',                         'prix'=>'',           'prix_2'=>'',            'prix_3'=>'72&thinsp;€', 'badge'=>''],
    ['nom'=>'Languedoc',     'description'=>'Pic saint loup. Mas de l\'oncle 2023',                            'prix'=>'',           'prix_2'=>'',            'prix_3'=>'39&thinsp;€', 'badge'=>''],
    ['nom'=>'Rhône',         'description'=>'Vacqueyras. Domaine de l\'Obrieu 2020',                           'prix'=>'',           'prix_2'=>'',            'prix_3'=>'39&thinsp;€', 'badge'=>''],
    ['nom'=>'Rhône',         'description'=>'Châteauneuf-du-Pape. Château La Nerthe 2020',                     'prix'=>'',           'prix_2'=>'',            'prix_3'=>'75&thinsp;€', 'badge'=>''],
    ['nom'=>'Bordeaux',      'description'=>'Saint-Émilion. Château Pipeau grand cru 2021',                    'prix'=>'',           'prix_2'=>'',            'prix_3'=>'55&thinsp;€', 'badge'=>''],
    ['nom'=>'Bordeaux',      'description'=>'Margaux. Blason d\'Issan 2020',                                   'prix'=>'',           'prix_2'=>'',            'prix_3'=>'59&thinsp;€', 'badge'=>''],
    ['nom'=>'Bordeaux',      'description'=>'Pauillac. Fleur de Pédesclaux 2016',                              'prix'=>'',           'prix_2'=>'',            'prix_3'=>'62&thinsp;€', 'badge'=>''],
]);
lpl_render_vins_section( 'vins_roses', $t_roses, [
    ['nom'=>'Provence Rollier', 'description'=>'Château de la Martinette « bio » 2024', 'prix'=>'6&thinsp;€', 'prix_2'=>'12&thinsp;€', 'prix_3'=>'25&thinsp;€', 'badge'=>''],
    ['nom'=>'Provence',         'description'=>'Minuty Prestige 2024',                  'prix'=>'8&thinsp;€', 'prix_2'=>'16&thinsp;€', 'prix_3'=>'39&thinsp;€', 'badge'=>''],
]);
lpl_render_vins_section( 'vins_champagnes', $t_champagnes, [
    ['nom'=>'Colin Alliance Brut',         'description'=>'', 'prix'=>'10&thinsp;€', 'prix_2'=>'20&thinsp;€', 'prix_3'=>'65&thinsp;€', 'badge'=>''],
    ['nom'=>'Colin Castille Blanc de Blanc','description'=>'', 'prix'=>'12&thinsp;€', 'prix_2'=>'24&thinsp;€', 'prix_3'=>'80&thinsp;€', 'badge'=>''],
    ['nom'=>'Deutz Brut',                  'description'=>'', 'prix'=>'',            'prix_2'=>'',            'prix_3'=>'95&thinsp;€', 'badge'=>''],
], '12cl', '24cl', '75cl' );
?>

    <!-- NOTE BAS DE PAGE -->
    <div class="row justify-content-center">
      <div class="col-12 col-md-10 col-lg-9">
        <div class="carte-footnote text-center mt-4 pb-5">
          <p class="mb-1">* Les millésimes sont susceptibles de changer en fonction des arrivages</p>
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
