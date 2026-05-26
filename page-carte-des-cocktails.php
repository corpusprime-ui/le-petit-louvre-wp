<?php
/*
 * Template Name: Carte des Cocktails
 */
get_header();
$pid = get_the_ID();
$tpl = get_template_directory_uri();

/* ── ACF Hero ── */
$hero_label   = get_field('cocktails_hero_label')   ?: 'Bar · Arcachon';
$hero_title   = get_field('cocktails_hero_title')   ?: 'Carte des Cocktails';
$hero_tagline = get_field('cocktails_hero_tagline') ?: 'Des créations uniques à partager';
$hero_images  = get_field('cocktails_hero_images')  ?: [];
?>

<!-- ==========================================
     HERO CARTE DES COCKTAILS
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
       src="<?php echo $tpl; ?>/img/verre-cocktail-1.jpg"
       alt="Espresso martini préparé par le barman du Petit Louvre"
       fetchpriority="high" decoding="async">
  <img loading="lazy" class="hero-bg"
       src="<?php echo $tpl; ?>/img/verre-cocktail-2.jpg"
       alt="Bar du Petit Louvre — whiskies et verres à cocktail"
       decoding="async">
  <img loading="lazy" class="hero-bg"
       src="<?php echo $tpl; ?>/img/verre-cocktail-4.jpg"
       alt="Sélection de cocktails en salle au Petit Louvre"
       decoding="async">
  <img loading="lazy" class="hero-bg"
       src="<?php echo $tpl; ?>/img/verre-cocktail-6.jpg"
       alt="Spritz Aperol en terrasse au Petit Louvre Arcachon"
       decoding="async">
  <img loading="lazy" class="hero-bg"
       src="<?php echo $tpl; ?>/img/verre-cocktail-7.jpg"
       alt="Cocktail Tiki signature du Petit Louvre"
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
      <a href="<?php echo esc_url( home_url( '/carte-des-vins/' ) ); ?>" class="btn btn-filled btn-lg">Carte des vins</a>
      <a href="<?php echo esc_url( home_url( '/carte-des-boissons/' ) ); ?>" class="btn btn-outline btn-lg">Nos boissons</a>
      <a href="<?php echo esc_url( home_url( '/reservation/' ) ); ?>" class="btn btn-outline btn-lg">Réserver</a>
    </div>
  </div>

  <button class="hero-scroll-hint" id="heroScrollHint" data-target="cocktails-menu" aria-label="Découvrir">
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
      <a href="<?php echo esc_url( home_url( '/carte-des-cocktails/' ) ); ?>" class="carte-nav-link active">Carte des Cocktails</a>
      <a href="<?php echo esc_url( home_url( '/carte-des-vins/' ) ); ?>"      class="carte-nav-link">Carte des Vins</a>
    </div>
  </div>
</nav>


<?php
function lpl_render_cocktail_section( $acf_key, $titre, $volume_label, $fallback_items ) {
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
     MENU CARTE DES COCKTAILS
========================================== -->
<section class="section-carte section-carte--cocktails" id="cocktails-menu">
  <!-- Illustration décorative bas droite -->
  <img loading="lazy" class="cocktail-illu-deco"
       src="<?php echo get_template_directory_uri(); ?>/img/cocktail-illu.png"
       alt="" aria-hidden="true">

  <div class="container">

    <div class="row">
      <div class="col-12 mt-5">
        <div class="carte-title-row d-flex align-items-center gap-3 mb-5">
          <span class="carte-title-line flex-grow-1"></span>
          <h2 class="carte-main-title">CARTE DES COCKTAILS</h2>
          <span class="carte-title-line flex-grow-1"></span>
        </div>
      </div>
    </div>


<?php
lpl_render_cocktail_section( 'martini_cocktails', 'Martini Cocktails', '20cl', [
    ['nom'=>'Cucumber martini',      'description'=>'Vodka, concombre',                          'prix'=>'12&thinsp;€','badge'=>''],
    ['nom'=>'Pine and berries martini','description'=>'Vodka, framboise, ananas, Chambord',      'prix'=>'12&thinsp;€','badge'=>''],
    ['nom'=>'Pornstar martini',      'description'=>'Vodka, passion, vanille, champagne',         'prix'=>'12&thinsp;€','badge'=>''],
    ['nom'=>'Tira martini',          'description'=>'Amaretto, expresso, Kalhua',                 'prix'=>'12&thinsp;€','badge'=>''],
    ['nom'=>'Kiwi martini',          'description'=>'Gin, kiwi',                                  'prix'=>'12&thinsp;€','badge'=>''],
]);
lpl_render_cocktail_section( 'cocktails_signature', 'Cocktails Signature', '30cl', [
    ['nom'=>'Red lover',   'description'=>'Gin, ananas, framboise, citron',                      'prix'=>'13&thinsp;€','badge'=>'Signature'],
    ['nom'=>'Apple pie',   'description'=>'Rhum, pomme, cannelle, citron',                       'prix'=>'13&thinsp;€','badge'=>''],
    ['nom'=>'Petit louis', 'description'=>"Whisky, pain d\u{2019}\u{e9}pices, vanille, Kalhua", 'prix'=>'13&thinsp;€','badge'=>''],
    ['nom'=>'Amber drop',  'description'=>'Vodka, orange, passion, cannelle, citron',             'prix'=>'13&thinsp;€','badge'=>''],
    ['nom'=>'Exotic Tiki', 'description'=>'Rhum, mangue, passion, coco',                         'prix'=>'13&thinsp;€','badge'=>''],
    ['nom'=>'Black jack',  'description'=>'Whisky J.Daniel, framboise, Chambord, cranberry',     'prix'=>'13&thinsp;€','badge'=>''],
]);
lpl_render_cocktail_section( 'cocktails_classiques', 'Classiques Cocktails', '30cl', [
    ['nom'=>'Gin basil smash',    'description'=>'Gin, feuilles de basilic, citron',             'prix'=>'12&thinsp;€','badge'=>''],
    ['nom'=>'Moscow mule',        'description'=>'Vodka, ginger, citron vert',                   'prix'=>'12&thinsp;€','badge'=>''],
    ['nom'=>'Daiquiri',           'description'=>'Rhum, citron vert',                            'prix'=>'12&thinsp;€','badge'=>''],
    ['nom'=>'Mojito',             'description'=>'Rhum, menthe, citron vert, soda',              'prix'=>'12&thinsp;€','badge'=>''],
    ['nom'=>'Margarita',          'description'=>'Tequila, triple sec, citron',                  'prix'=>'12&thinsp;€','badge'=>''],
    ['nom'=>'Pina colada',        'description'=>'Rhum, ananas, coco',                           'prix'=>'12&thinsp;€','badge'=>''],
    ['nom'=>'Bloody Mary',        'description'=>'Vodka, tomates, épices',                       'prix'=>'12&thinsp;€','badge'=>''],
    ['nom'=>'Caïpirinha',         'description'=>'Cachaça, citron vert',                         'prix'=>'12&thinsp;€','badge'=>''],
    ['nom'=>'Kiwi collins',       'description'=>'Vodka, kiwi, soda, citron',                   'prix'=>'12&thinsp;€','badge'=>''],
    ['nom'=>'Pineapple collins',  'description'=>'Vodka, ananas, soda, citron',                  'prix'=>'12&thinsp;€','badge'=>''],
    ['nom'=>'Raspberry collins',  'description'=>'Vodka, framboise, soda, citron',               'prix'=>'12&thinsp;€','badge'=>''],
]);
lpl_render_cocktail_section( 'spritz', 'Spritz', '40cl', [
    ['nom'=>'Apérol',   'description'=>'Liqueur Apérol, prosecco, soda',                'prix'=>'12&thinsp;€','badge'=>''],
    ['nom'=>'Campari',  'description'=>'Liqueur Campari, prosecco, soda',               'prix'=>'12&thinsp;€','badge'=>''],
    ['nom'=>'Italicus', 'description'=>'Liqueur bergamote, prosecco, soda',             'prix'=>'13&thinsp;€','badge'=>''],
    ['nom'=>'Hugo',     'description'=>'Liqueur St Germain, menthe, prosecco, soda',   'prix'=>'13&thinsp;€','badge'=>''],
]);
lpl_render_cocktail_section( 'sans_alcool', 'Sans Alcool', '30cl', [
    ['nom'=>'Petit louvre',  'description'=>'Ananas, pomme, orgeat, citron',        'prix'=>'8&thinsp;€','badge'=>'Sans alcool'],
    ['nom'=>'Saint Anne',    'description'=>'Orange, pomme, cranberry, pêche',      'prix'=>'8&thinsp;€','badge'=>'Sans alcool'],
    ['nom'=>'Virgin mojito', 'description'=>'Menthe, citron, soda',                 'prix'=>'8&thinsp;€','badge'=>'Sans alcool'],
    ['nom'=>'Virgin Colada', 'description'=>'Ananas, coco',                         'prix'=>'8&thinsp;€','badge'=>'Sans alcool'],
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

<style>
/* ── Illustration décorative cocktail ─────────────────────────────── */
.section-carte--cocktails {
  position: relative;
  overflow: hidden;
}

.cocktail-illu-deco {
  position: absolute;
  bottom: -20px;
  right: -40px;
  width: clamp(260px, 28vw, 420px);
  opacity: 0.18;
  pointer-events: none;
  user-select: none;
  /* Fondu léger sur le bas pour s'intégrer avec le fond */
  mask-image: linear-gradient(to bottom, black 60%, transparent 100%);
  -webkit-mask-image: linear-gradient(to bottom, black 60%, transparent 100%);
}

@media (max-width: 991px) {
  .cocktail-illu-deco {
    width: clamp(180px, 40vw, 260px);
    opacity: 0.12;
    right: -20px;
    bottom: 0;
  }
}

@media (max-width: 575px) {
  .cocktail-illu-deco { display: none; }
}
</style>

<?php get_footer(); ?>
