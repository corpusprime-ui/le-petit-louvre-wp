<?php
/*
 * Template Name: Carte des Boissons
 */
get_header();
$pid = get_the_ID();
$tpl = get_template_directory_uri();

/* ── ACF Hero ── */
$hero_label   = get_field('boissons_hero_label')   ?: 'Bar · Arcachon';
$hero_title   = get_field('boissons_hero_title')   ?: 'Carte des Boissons';
$hero_tagline = get_field('boissons_hero_tagline') ?: 'Apéritifs, bières, smoothies & cafeterie';
$hero_images  = get_field('boissons_hero_images')  ?: [];
?>

<!-- ==========================================
     HERO CARTE DES BOISSONS
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
       src="<?php echo $tpl; ?>/img/boisson-1.jpg"
       alt="Préparation d'un gin tonic au bar du Petit Louvre"
       fetchpriority="high" decoding="async">
  <img loading="lazy" class="hero-bg"
       src="<?php echo $tpl; ?>/img/boisson-2.jpg"
       alt="Cocktails fruités en terrasse au Petit Louvre"
       decoding="async">
  <img loading="lazy" class="hero-bg"
       src="<?php echo $tpl; ?>/img/boisson-3.jpg"
       alt="Spritz Aperol au bar du Petit Louvre"
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
      <a href="<?php echo esc_url( home_url( '/carte-des-vins/' ) ); ?>" class="btn btn-outline btn-lg">Carte des vins</a>
      <a href="<?php echo esc_url( home_url( '/reservation/' ) ); ?>" class="btn btn-outline btn-lg">Réserver</a>
    </div>
  </div>

  <button class="hero-scroll-hint" id="heroScrollHint" data-target="boissons-menu" aria-label="Découvrir">
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
      <a href="<?php echo esc_url( home_url( '/carte-des-boissons/' ) ); ?>"  class="carte-nav-link active">Carte des Boissons</a>
      <a href="<?php echo esc_url( home_url( '/carte-des-cocktails/' ) ); ?>" class="carte-nav-link">Carte des Cocktails</a>
      <a href="<?php echo esc_url( home_url( '/carte-des-vins/' ) ); ?>"      class="carte-nav-link">Carte des Vins</a>
    </div>
  </div>
</nav>


<?php
/* ── Helper : affiche une section de boissons à prix unique ── */
function lpl_render_boissons_section( $acf_key, $titre, $volume_label, $fallback_items ) {
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

/* ── Helper : section à double prix (25cl / 50cl) ── */
function lpl_render_boissons_double( $acf_key, $titre, $label1, $label2, $fallback_items ) {
    $items = function_exists('get_field') ? get_field( $acf_key ) : [];
    $items = $items ?: $fallback_items;
    if ( ! $items ) return;
    ?>
    <div class="row justify-content-center mt-5">
      <div class="col-12 col-md-10 col-lg-9">
        <h3 class="carte-section-title text-center mb-4"><?php echo esc_html( $titre ); ?></h3>
        <div class="vins-header-row vins-header-double">
          <div></div>
          <div class="vins-cl-label"><?php echo esc_html( $label1 ); ?></div>
          <div class="vins-cl-label"><?php echo esc_html( $label2 ); ?></div>
        </div>
        <?php foreach ( $items as $item ) : ?>
        <div class="vins-row vins-row-double">
          <div class="vins-label">
            <div class="vins-name-row">
              <span class="vins-name"><?php echo esc_html( $item['nom'] ?? '' ); ?></span>
              <?php if ( ! empty( $item['badge'] ) ) : ?><span class="carte-badge"><?php echo esc_html( $item['badge'] ); ?></span><?php endif; ?>
              <span class="vins-dots" aria-hidden="true"></span>
            </div>
            <?php if ( ! empty( $item['description'] ) ) : ?><p class="vins-desc mb-0"><?php echo esc_html( $item['description'] ); ?></p><?php endif; ?>
          </div>
          <span class="vins-price"><?php echo esc_html( $item['prix']   ?? '' ); ?></span>
          <span class="vins-price"><?php echo esc_html( $item['prix_2'] ?? '' ); ?></span>
        </div>
        <?php endforeach; ?>
      </div>
    </div>
    <?php
}
?>

<!-- ==========================================
     MENU CARTE DES BOISSONS
========================================== -->
<section class="section-carte" id="boissons-menu">
  <div class="container">

    <div class="row">
      <div class="col-12 mt-5">
        <div class="carte-title-row d-flex align-items-center gap-3 mb-5">
          <span class="carte-title-line flex-grow-1"></span>
          <h2 class="carte-main-title">CARTE DES BOISSONS</h2>
          <span class="carte-title-line flex-grow-1"></span>
        </div>
      </div>
    </div>


<?php
lpl_render_boissons_section( 'aperitifs', 'Apéritifs', '6cl', [
    ['nom'=>'Pastis 51','description'=>'2 cl','prix'=>'4&thinsp;€','badge'=>''],
    ['nom'=>'Lillet blanc / rosé','description'=>'','prix'=>'5&thinsp;€','badge'=>''],
    ['nom'=>'Martini blanc / rouge','description'=>'','prix'=>'5&thinsp;€','badge'=>''],
    ['nom'=>'Suze','description'=>'','prix'=>'5&thinsp;€','badge'=>''],
    ['nom'=>'Kir vin blanc','description'=>'Cassis, mûre, framboise, fraise','prix'=>'6&thinsp;€','badge'=>''],
    ['nom'=>'Kir royal','description'=>'Champagne','prix'=>'11&thinsp;€','badge'=>''],
]);
lpl_render_boissons_double( 'bieres_pression', 'Bières Pression', '25cl', '50cl', [
    ['nom'=>'Meteor','description'=>'Pils','prix'=>'4,5&thinsp;€','prix_2'=>'8&thinsp;€','badge'=>''],
    ['nom'=>'Wendelinus','description'=>'Bière d\'abbaye','prix'=>'5&thinsp;€','prix_2'=>'9&thinsp;€','badge'=>''],
    ['nom'=>'Meteor','description'=>'Bière d\'hiver','prix'=>'5&thinsp;€','prix_2'=>'9&thinsp;€','badge'=>''],
    ['nom'=>'Meteor','description'=>'IPA','prix'=>'5&thinsp;€','prix_2'=>'9&thinsp;€','badge'=>''],
]);
lpl_render_boissons_section( 'bieres_bouteille', 'Bières Bouteille', '33cl', [
    ['nom'=>'Corona','description'=>'','prix'=>'7&thinsp;€','badge'=>''],
    ['nom'=>'San Miguel Blonde','description'=>'','prix'=>'7&thinsp;€','badge'=>''],
    ['nom'=>'Pelforth brune','description'=>'','prix'=>'7&thinsp;€','badge'=>''],
    ['nom'=>'1664','description'=>'','prix'=>'6&thinsp;€','badge'=>'Sans alcool'],
]);
lpl_render_boissons_section( 'jus_fruit', 'Jus de Fruit', '25cl', [
    ['nom'=>'Jus d\'orange','description'=>'','prix'=>'4,5&thinsp;€','badge'=>''],
    ['nom'=>'Jus de tomate','description'=>'','prix'=>'4,5&thinsp;€','badge'=>''],
    ['nom'=>'Jus de pomme','description'=>'','prix'=>'4,5&thinsp;€','badge'=>''],
    ['nom'=>'Jus d\'ananas','description'=>'','prix'=>'4,5&thinsp;€','badge'=>''],
]);
lpl_render_boissons_section( 'smoothies', 'Smoothies', '40cl', [
    ['nom'=>'Vitamina','description'=>'Kiwi, framboise, mangue','prix'=>'8&thinsp;€','badge'=>''],
    ['nom'=>'Énergie','description'=>'Banane, kiwi, ananas','prix'=>'8&thinsp;€','badge'=>''],
    ['nom'=>'Exotique','description'=>'Mangue, orange, banane','prix'=>'8&thinsp;€','badge'=>''],
]);
lpl_render_boissons_section( 'sodas', 'Sodas', '33cl', [
    ['nom'=>'Coca-Cola, Coca-Cola zéro','description'=>'','prix'=>'4,5&thinsp;€','badge'=>''],
    ['nom'=>'Orangina','description'=>'25 cl','prix'=>'4,5&thinsp;€','badge'=>''],
    ['nom'=>'Schweppes tonic','description'=>'25 cl','prix'=>'4,5&thinsp;€','badge'=>''],
    ['nom'=>'Sprite','description'=>'25 cl','prix'=>'4,5&thinsp;€','badge'=>''],
    ['nom'=>'Ginger beer','description'=>'25 cl','prix'=>'4,5&thinsp;€','badge'=>''],
    ['nom'=>'Thé glacé maison','description'=>'','prix'=>'5,5&thinsp;€','badge'=>''],
    ['nom'=>'Citronnade maison','description'=>'','prix'=>'5,5&thinsp;€','badge'=>''],
]);
lpl_render_boissons_section( 'presse', 'Pressé', '20cl', [
    ['nom'=>'Citron','description'=>'','prix'=>'5,5&thinsp;€','badge'=>''],
    ['nom'=>'Orange','description'=>'','prix'=>'5,5&thinsp;€','badge'=>''],
]);
lpl_render_boissons_section( 'eaux', 'Eaux', '', [
    ['nom'=>'Vittel','description'=>'100 cl','prix'=>'7&thinsp;€','badge'=>''],
    ['nom'=>'Vittel','description'=>'25 cl','prix'=>'4&thinsp;€','badge'=>''],
    ['nom'=>'San Pellegrino','description'=>'100 cl','prix'=>'7&thinsp;€','badge'=>''],
    ['nom'=>'Perrier','description'=>'33 cl','prix'=>'4,5&thinsp;€','badge'=>''],
]);
lpl_render_boissons_section( 'cafeterie', 'Cafeterie', '', [
    ['nom'=>'Expresso &amp; Déca','description'=>'','prix'=>'2&thinsp;€','badge'=>''],
    ['nom'=>'Double expresso','description'=>'','prix'=>'4&thinsp;€','badge'=>''],
    ['nom'=>'Café crème','description'=>'','prix'=>'4,5&thinsp;€','badge'=>''],
    ['nom'=>'Capuccino','description'=>'','prix'=>'5&thinsp;€','badge'=>''],
    ['nom'=>'Café viennois','description'=>'','prix'=>'5,5&thinsp;€','badge'=>''],
    ['nom'=>'Chocolat chaud','description'=>'','prix'=>'5,5&thinsp;€','badge'=>''],
]);
?>

        <!-- NOTE BAS DE PAGE -->
    <div class="row justify-content-center">
      <div class="col-12 col-md-10 col-lg-9">
        <div class="carte-footnote text-center mt-4 pb-5">
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
