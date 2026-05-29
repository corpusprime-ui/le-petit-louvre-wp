<?php
/*
 * Template Name: Carte des Alcools
 */
get_header();
$pid = get_the_ID();
$tpl = esc_url( get_template_directory_uri() );

/* ── ACF Hero ── */
$hero_label   = get_field('alcools_hero_label')   ?: 'Bar · Arcachon';
$hero_title   = get_field('alcools_hero_title')   ?: 'Carte des Alcools';
$hero_tagline = get_field('alcools_hero_tagline') ?: 'Vodkas, whiskies, gins, rhums & spiritueux d\'exception';
$hero_images  = get_field('alcools_hero_images')  ?: [];
?>

<!-- ==========================================
     HERO CARTE DES ALCOOLS
========================================== -->
<section class="hero hero--carte" id="hero">

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
       src="<?php echo $tpl; ?>/img/boisson-1.jpg"
       alt="Bar du Petit Louvre — sélection de spiritueux"
       fetchpriority="high" decoding="async">
  <img loading="lazy" class="hero-bg"
       src="<?php echo $tpl; ?>/img/boisson-2.jpg"
       alt="Spiritueux et alcools au Petit Louvre Arcachon"
       decoding="async">
  <img loading="lazy" class="hero-bg"
       src="<?php echo $tpl; ?>/img/boisson-3.jpg"
       alt="Cocktails et spiritueux au bar du Petit Louvre"
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

  <button class="hero-scroll-hint" id="heroScrollHint" data-target="alcools-menu" aria-label="Découvrir">
    <span>Découvrir</span>
    <div class="scroll-arrow"></div>
  </button>

</section>

<div class="carte-parallax-content">


<!-- ==========================================
     NAV ENTRE CARTES
========================================== -->
<nav class="carte-section-nav" aria-label="Navigation entre les cartes">
  <div class="container">
    <div class="d-flex justify-content-center gap-4 gap-md-5 flex-wrap">
      <a href="<?php echo esc_url( home_url( '/carte-des-boissons/' ) ); ?>"  class="carte-nav-link">Boissons</a>
      <a href="<?php echo esc_url( home_url( '/carte-des-cocktails/' ) ); ?>" class="carte-nav-link">Cocktails</a>
      <a href="<?php echo esc_url( home_url( '/carte-des-alcools/' ) ); ?>"   class="carte-nav-link active">Alcools</a>
      <a href="<?php echo esc_url( home_url( '/carte-des-vins/' ) ); ?>"      class="carte-nav-link">Vins</a>
    </div>
  </div>
</nav>


<?php
/* ── Helper : affiche une section d'alcools à prix unique ── */
if ( ! function_exists( 'lpl_render_carte_single' ) ) :
function lpl_render_carte_single( $acf_key, $titre, $volume_label, $fallback_items ) {
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
          <span class="vins-price"><?php echo esc_html( $item['prix'] ?? '' ); ?>&thinsp;€</span>
        </div>
        <?php endforeach; ?>
      </div>
    </div>
    <?php
}
endif;
?>

<!-- ==========================================
     MENU CARTE DES ALCOOLS
========================================== -->
<section class="section-carte section-carte--alcools" id="alcools-menu">

  <!-- Illustration décorative bas droite -->
  <img loading="lazy" class="alcools-illu-deco"
       src="<?php echo $tpl; ?>/img/alcools.png"
       alt="" aria-hidden="true">

  <div class="container">

    <div class="row">
      <div class="col-12 mt-5">
        <div class="carte-title-row d-flex align-items-center gap-3 mb-5">
          <span class="carte-title-line flex-grow-1"></span>
          <h2 class="carte-main-title">CARTE DES ALCOOLS</h2>
          <span class="carte-title-line flex-grow-1"></span>
        </div>
      </div>
    </div>

<?php
/* ── Titres de sections (éditables en back-office) ── */
$t_vodka    = get_field('alcools_titre_vodka')    ?: 'Vodka';
$t_rhum     = get_field('alcools_titre_rhum')     ?: 'Rhum';
$t_whisky   = get_field('alcools_titre_whisky')   ?: 'Whisky';
$t_gin      = get_field('alcools_titre_gin')      ?: 'Gin';
$t_digestifs= get_field('alcools_titre_digestifs')?: 'Digestifs';
$t_tequila  = get_field('alcools_titre_tequila')  ?: 'Tequila';
$t_cognac   = get_field('alcools_titre_cognac')   ?: 'Cognac & Armagnac';

/* ── VODKA ── */
lpl_render_carte_single( 'alcools_vodka', $t_vodka, '4cl', [
    ['nom'=>'Smirnoff',      'description'=>'', 'prix'=>'7',  'badge'=>''],
    ['nom'=>'Stolichnaya',   'description'=>'', 'prix'=>'9',  'badge'=>''],
    ['nom'=>'Ketel One',     'description'=>'', 'prix'=>'10', 'badge'=>''],
    ['nom'=>'Pyla française', 'description'=>'', 'prix'=>'12', 'badge'=>''],
    ['nom'=>'Grey Goose',     'description'=>'', 'prix'=>'13', 'badge'=>''],
]);

/* ── RHUM ── */
lpl_render_carte_single( 'alcools_rhum', $t_rhum, '4cl', [
    ['nom'=>'Pampero Ambré',  'description'=>'', 'prix'=>'7',  'badge'=>''],
    ['nom'=>'Trois Rivières', 'description'=>'', 'prix'=>'9',  'badge'=>''],
    ['nom'=>'Botran',         'description'=>'', 'prix'=>'11', 'badge'=>''],
    ['nom'=>'Diplomatico',    'description'=>'', 'prix'=>'12', 'badge'=>''],
    ['nom'=>'Zacapa 23 ans',  'description'=>'', 'prix'=>'18', 'badge'=>''],
]);

/* ── WHISKY ── */
lpl_render_carte_single( 'alcools_whisky', $t_whisky, '4cl', [
    ['nom'=>'Johnny Walker Red', 'description'=>'', 'prix'=>'7',  'badge'=>''],
    ['nom'=>"Jack Daniel's",     'description'=>'', 'prix'=>'10', 'badge'=>''],
    ['nom'=>'Bulleit Bourbon',   'description'=>'', 'prix'=>'11', 'badge'=>''],
    ['nom'=>'Talisker 10 ans',   'description'=>'', 'prix'=>'14', 'badge'=>''],
    ['nom'=>'Chivas 18 ans',     'description'=>'', 'prix'=>'16', 'badge'=>''],
]);

/* ── GIN ── */
lpl_render_carte_single( 'alcools_gin', $t_gin, '4cl', [
    ['nom'=>'Bombay Original', 'description'=>'', 'prix'=>'7',  'badge'=>''],
    ['nom'=>'Tanqueray',       'description'=>'', 'prix'=>'8',  'badge'=>''],
    ['nom'=>"Hendrick's",      'description'=>'', 'prix'=>'10', 'badge'=>''],
    ['nom'=>'Gin Mare',        'description'=>'', 'prix'=>'12', 'badge'=>''],
]);

/* ── DIGESTIFS ── */
lpl_render_carte_single( 'alcools_digestifs', $t_digestifs, '4cl', [
    ['nom'=>'Limoncello',    'description'=>'', 'prix'=>'6', 'badge'=>''],
    ['nom'=>'Get 27',        'description'=>'', 'prix'=>'7', 'badge'=>''],
    ['nom'=>'Baileys',       'description'=>'', 'prix'=>'7', 'badge'=>''],
    ['nom'=>'Amaretto',      'description'=>'', 'prix'=>'7', 'badge'=>''],
    ['nom'=>'Poire Williams','description'=>'', 'prix'=>'8', 'badge'=>''],
]);

/* ── TEQUILA ── */
lpl_render_carte_single( 'alcools_tequila', $t_tequila, '4cl', [
    ['nom'=>'Patron Silver',  'description'=>'', 'prix'=>'11', 'badge'=>''],
    ['nom'=>'Patron Reposado','description'=>'', 'prix'=>'13', 'badge'=>''],
    ['nom'=>'Patron Anejo',   'description'=>'', 'prix'=>'16', 'badge'=>''],
]);

/* ── COGNAC / ARMAGNAC ── */
lpl_render_carte_single( 'alcools_cognac', $t_cognac, '4cl', [
    ['nom'=>'Armagnac',       'description'=>'', 'prix'=>'10', 'badge'=>''],
    ['nom'=>'Cognac ABK6 VS', 'description'=>'', 'prix'=>'13', 'badge'=>''],
    ['nom'=>'Cognac ABK6 XO', 'description'=>'', 'prix'=>'18', 'badge'=>''],
]);
?>

    <!-- NOTES BAS DE PAGE -->
    <div class="row justify-content-center">
      <div class="col-12 col-md-10 col-lg-9">
        <div class="carte-footnote text-center mt-4 pb-5">
          <p class="mb-1">* Accompagnement Soda : 2&nbsp;&euro;</p>
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

</div><!-- /.carte-parallax-content -->

<?php get_footer(); ?>
