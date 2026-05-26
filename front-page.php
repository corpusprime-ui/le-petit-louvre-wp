<?php
get_header();
$pid = get_option( 'page_on_front' );
$tpl = get_template_directory_uri();

/* ── lpl_field() défini globalement dans functions.php ── */
?>

<!-- ==========================================
     HERO
========================================== -->
<section class="hero" id="hero">

  <?php
  /* ── Photos hero ──────────────────────────────────────────── */
  $hero_images_acf = function_exists( 'have_rows' ) && have_rows( 'hero_images', $pid );
  if ( $hero_images_acf ) :
    $first = true;
    while ( have_rows( 'hero_images', $pid ) ) : the_row();
      $img = get_sub_field( 'image' );
      $alt = get_sub_field( 'alt' ) ?: ( $img['alt'] ?? 'Le Petit Louvre Arcachon' );
      if ( $img ) :
        $priority = $first ? 'loading="eager" fetchpriority="high"' : 'loading="lazy" decoding="async"';
        $active   = $first ? ' active' : ''; ?>
        <img class="hero-bg<?php echo $active; ?>"
             src="<?php echo esc_url( $img['url'] ); ?>"
             alt="<?php echo esc_attr( $alt ); ?>"
             <?php echo $priority; ?>>
      <?php endif;
      $first = false;
    endwhile;
  else : /* Fallback statique */ ?>
    <img class="hero-bg active" id="heroBg"
         src="<?php echo $tpl; ?>/img/hero-salle.jpg"
         alt="Salle du restaurant Le Petit Louvre"
         fetchpriority="high" decoding="async">
    <img loading="lazy" class="hero-bg"
         src="<?php echo $tpl; ?>/img/terrasse-2.jpg"
         alt="Terrasse ensoleillée Le Petit Louvre"
         decoding="async">
  <?php endif; ?>

  <div class="hero-overlay-top"></div>
  <div class="hero-overlay-mid"></div>

  <?php get_template_part( 'template-parts/site-header' ); ?>

  <!-- CONTENU HERO -->
  <div class="hero-content">
    <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="hero-logo-mobile" aria-label="Accueil Le Petit Louvre">
      <img loading="eager" src="<?php echo $tpl; ?>/img/logo.svg" alt="Le Petit Louvre" width="100" height="100">
    </a>
    <p class="hero-label"><?php echo esc_html( lpl_field( 'hero_label', $pid, 'Cuisine Fusion · Arcachon' ) ); ?></p>
    <h1 class="hero-title"><?php echo esc_html( lpl_field( 'hero_title', $pid, 'Le Petit Louvre' ) ); ?></h1>
    <p class="hero-tagline"><?php echo esc_html( lpl_field( 'hero_tagline', $pid, 'Gastronomie, terrasse & bar à vins au cœur du Bassin' ) ); ?></p>
    <div class="hero-cta d-flex flex-wrap gap-3 justify-content-center">
      <a href="<?php echo esc_url( home_url( '/carte/' ) ); ?>" class="btn btn-outline btn-lg">Découvrir la carte</a>
      <a href="<?php echo esc_url( home_url( '/reservation/' ) ); ?>" class="btn btn-filled btn-lg">Réserver une table</a>
    </div>
    <p class="hero-availability"><?php echo esc_html( lpl_field( 'hero_availability', $pid, 'Tables disponibles ce soir · Réservation conseillée' ) ); ?></p>
  </div>

  <button class="hero-scroll-hint" id="heroScrollHint" data-target="presentation" aria-label="Découvrir le restaurant">
    <span>Découvrir</span>
    <div class="scroll-arrow"></div>
  </button>

</section>


<!-- ==========================================
     NOTRE RESTAURANT — PRÉSENTATION
========================================== -->
<section class="section-presentation" id="presentation">
  <div class="container" style="max-width:1200px;">
    <div class="row g-5 align-items-center">

      <div class="col-lg-6">
        <?php
        $pres_photo = lpl_field( 'pres_photo', $pid, null );
        if ( $pres_photo && is_array( $pres_photo ) ) :
          $src = esc_url( $pres_photo['url'] );
          $alt = esc_attr( $pres_photo['alt'] ?: 'Intérieur du restaurant' );
          $w   = (int) $pres_photo['width'];
          $h   = (int) $pres_photo['height'];
        else :
          $src = $tpl . '/img/interieur.jpg';
          $alt = 'Intérieur du restaurant';
          $w   = 800; $h = 600;
        endif; ?>
        <div class="pres-photo reveal-left d1">
          <img loading="lazy" src="<?php echo $src; ?>" alt="<?php echo $alt; ?>"
               width="<?php echo $w; ?>" height="<?php echo $h; ?>"
               decoding="async">
        </div>
      </div>

      <div class="col-lg-6">
        <div class="pres-content">
          <p class="section-label reveal d1"><?php echo esc_html( lpl_field( 'pres_label', $pid, 'Notre Restaurant' ) ); ?></p>
          <h2 class="section-title reveal d2"><?php echo esc_html( lpl_field( 'pres_title', $pid, 'Une invitation à la cuisine fusion, le temps d\'un verre ou d\'un repas' ) ); ?></h2>
          <div class="sep-line reveal d3"></div>
          <div class="section-body reveal d3">
            <?php
            $pres_body = lpl_field( 'pres_body', $pid, '' );
            if ( $pres_body ) :
              echo wp_kses_post( $pres_body );
            else : ?>
              <p>Au cœur d'Arcachon, Le Petit Louvre incarne l'esprit d'un bistrot contemporain, où les saveurs se croisent et les moments se partagent.</p>
              <p>Du déjeuner au dîner, le restaurant propose une cuisine généreuse et créative, mêlant tradition et touches actuelles, dans une ambiance chaleureuse et décontractée.</p>
              <p>En salle ou sur la terrasse, installez-vous pour profiter d'un cadre convivial, idéal pour un repas entre amis, en famille ou une pause gourmande.</p>
            <?php endif; ?>
          </div>
          <div class="btn-row d-flex flex-wrap gap-3 align-items-center reveal d4">
            <a href="<?php echo esc_url( home_url( '/carte/' ) ); ?>" class="btn btn-filled btn-lg" style="background:var(--olive-green);">Découvrir la carte</a>
            <a href="<?php echo esc_url( home_url( '/reservation/' ) ); ?>" class="btn btn-outline btn-lg">Réserver une table</a>
          </div>
        </div>
      </div>

    </div>
  </div>
</section>


<!-- ==========================================
     CITATION
========================================== -->
<section class="section-quote">
  <img loading="lazy" class="filigram"
       src="<?php echo $tpl; ?>/img/filigrane.png"
       width="860" height="643"
       alt="" aria-hidden="true">
  <div class="quote-inner">
    <img loading="lazy" class="quote-deco reveal-scale d1"
         src="<?php echo $tpl; ?>/img/quote-deco.png"
         alt="" aria-hidden="true">
    <blockquote class="quote-text">
      <?php echo esc_html( lpl_field( 'quote_text', $pid, 'Une terrasse ouverte de 70 couverts, un espace lumineux avec une équipe attentive, heureuse de vous accueillir avec le sourire.' ) ); ?>
    </blockquote>
  </div>
</section>


<!-- ==========================================
     BAR & VIN — SPEAKEASY
========================================== -->
<section class="section-speakeasy" id="speakeasy">
  <div class="container" style="max-width:1200px;">
    <div class="row g-5 align-items-center">

      <div class="col-lg-6 order-2 order-lg-1">
        <div class="speakeasy-content">
          <p class="section-label reveal d1"><?php echo esc_html( lpl_field( 'spk_label', $pid, 'BAR & VIN' ) ); ?></p>
          <h2 class="speakeasy-title reveal d2"><?php echo esc_html( lpl_field( 'spk_title', $pid, 'Notre bar à cocktails & vins' ) ); ?></h2>
          <div class="sep-line reveal d3"></div>
          <div class="speakeasy-body reveal d3">
            <?php
            $spk_body = lpl_field( 'spk_body', $pid, '' );
            if ( $spk_body ) :
              echo wp_kses_post( $spk_body );
            else : ?>
              <p>Au Petit Louvre à Arcachon, le vin est bien plus qu'un accompagnement : c'est une véritable expérience, servie avec le sourire.</p>
              <p>Notre carte des vins met à l'honneur une large sélection de vins du terroir français, soigneusement choisis à travers les grandes régions viticoles de France.</p>
              <p>Rouges de caractère, blancs élégants ou vins plus surprenants, notre sélection s'accorde parfaitement avec les plats de notre restaurant à Arcachon.</p>
            <?php endif; ?>
          </div>
          <div class="btn-row d-flex flex-wrap gap-3 align-items-center reveal d4">
            <a href="<?php echo esc_url( home_url( '/carte-des-boissons/' ) ); ?>" class="btn btn-filled btn-lg" style="white-space:nowrap;">La Carte des boissons</a>
            <a href="<?php echo esc_url( home_url( '/reservation/' ) ); ?>" class="btn btn-outline btn-lg">Réserver au bar</a>
          </div>
        </div>
      </div>

      <div class="col-lg-6 order-1 order-lg-2">
        <div class="speakeasy-images reveal-right d2">
          <?php
          $spk_images_acf = function_exists( 'have_rows' ) && have_rows( 'spk_images', $pid );
          if ( $spk_images_acf ) :
            $first = true;
            while ( have_rows( 'spk_images', $pid ) ) : the_row();
              $img = get_sub_field( 'image' );
              $alt = get_sub_field( 'alt' ) ?: ( $img['alt'] ?? 'Bar Le Petit Louvre' );
              if ( $img ) :
                $active = $first ? ' active' : ''; ?>
                <img loading="eager" class="speakeasy-bg<?php echo $active; ?>"
                     src="<?php echo esc_url( $img['url'] ); ?>"
                     alt="<?php echo esc_attr( $alt ); ?>"
                     loading="lazy" decoding="async">
              <?php endif;
              $first = false;
            endwhile;
          else : /* Fallback */ ?>
            <img loading="eager" class="speakeasy-bg active"
                 src="<?php echo $tpl; ?>/img/bar-vins.jpg"
                 alt="Sélection de vins" loading="lazy" decoding="async">
            <img loading="eager" class="speakeasy-bg"
                 src="<?php echo $tpl; ?>/img/cocktails.jpg"
                 alt="Cocktails du bar" loading="lazy" decoding="async">
          <?php endif; ?>
        </div>
      </div>

    </div>
  </div>
</section>


<!-- ==========================================
     PRIVATISATION
========================================== -->
<section class="section-privatisation" id="privatisation">
  <img loading="lazy" class="olive-branch" id="oliveBranch"
       src="<?php echo $tpl; ?>/img/olive-branch.jpg"
       alt="" aria-hidden="true">
  <div class="container" style="max-width:1200px;">
    <div class="row g-5 align-items-center">

      <div class="col-lg-5">
        <?php
        $priv_photo = lpl_field( 'priv_photo', $pid, null );
        if ( $priv_photo && is_array( $priv_photo ) ) :
          $src = esc_url( $priv_photo['url'] );
          $alt = esc_attr( $priv_photo['alt'] ?: 'Privatisation Le Petit Louvre' );
          $w   = (int) $priv_photo['width'];
          $h   = (int) $priv_photo['height'];
        else :
          $src = $tpl . '/img/privatisation.jpg';
          $alt = 'Table pour privatisation';
          $w   = 800; $h = 600;
        endif; ?>
        <div class="priv-photo reveal-left d1">
          <img loading="lazy" src="<?php echo $src; ?>" alt="<?php echo $alt; ?>"
               width="<?php echo $w; ?>" height="<?php echo $h; ?>"
               decoding="async">
        </div>
      </div>

      <div class="col-lg-7">
        <div class="priv-content">
          <p class="section-label reveal d1"><?php echo esc_html( lpl_field( 'priv_label', $pid, 'Événements' ) ); ?></p>
          <h2 class="priv-title reveal d2"><?php echo esc_html( lpl_field( 'priv_title', $pid, 'Privatisation' ) ); ?></h2>
          <div class="sep-line reveal d3"></div>
          <div class="section-body reveal d3">
            <?php
            $priv_body = lpl_field( 'priv_body', $pid, '' );
            if ( $priv_body ) :
              echo wp_kses_post( $priv_body );
            else : ?>
              <p>Le Petit Louvre à Arcachon vous propose la privatisation de ses espaces pour vos événements privés et professionnels.</p>
              <p>Déjeuner intimiste, dîner d'entreprise, anniversaire ou cocktail : nous adaptons le restaurant selon vos besoins, avec un service attentif et une cuisine soignée.</p>
              <p>Offrez à vos invités un événement sur-mesure au cœur du bassin d'Arcachon, dans un cadre élégant et convivial.</p>
            <?php endif; ?>
          </div>
          <div class="btn-row d-flex flex-wrap gap-3 align-items-center reveal d4">
            <a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>" class="btn btn-filled btn-lg">Demander un devis</a>
          </div>
        </div>
      </div>

    </div>
  </div>
</section>


<!-- ==========================================
     NOS PLATS — SLIDER
========================================== -->
<section class="section-nosplats" id="nosplats">
  <div class="nosplats-header">
    <p class="section-label reveal d1"><?php echo esc_html( lpl_field( 'plats_label', $pid, 'Notre carte' ) ); ?></p>
    <h2 class="nosplats-title reveal d2"><?php echo esc_html( lpl_field( 'plats_title', $pid, 'Nos plats' ) ); ?></h2>
    <div class="sep-line reveal d3"></div>
  </div>
  <div class="gal-slider reveal d2" id="platsSlider">
    <button class="gal-btn gal-prev" id="platsPrev" aria-label="Précédent">
      <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="15 18 9 12 15 6"/></svg>
    </button>
    <div class="gal-viewport">
      <div class="gal-track" id="platsTrack">

        <?php
        $plats_acf = function_exists( 'have_rows' ) && have_rows( 'plats_slider', $pid );
        if ( $plats_acf ) :
          while ( have_rows( 'plats_slider', $pid ) ) : the_row();
            $img = get_sub_field( 'image' );
            $alt = get_sub_field( 'alt' ) ?: ( $img['alt'] ?? 'Plat du chef Le Petit Louvre' );
            if ( $img ) : ?>
              <div class="gal-slide gal-slide--plat">
                <img loading="eager" src="<?php echo esc_url( $img['url'] ); ?>"
                     alt="<?php echo esc_attr( $alt ); ?>"
                     width="<?php echo (int) $img['width']; ?>"
                     height="<?php echo (int) $img['height']; ?>"
                     loading="eager" decoding="async">
              </div>
            <?php endif;
          endwhile;
        else :
          $plats = [
            [ 'file' => 'plat-1.jpg',      'alt' => 'Burrata, tomates et basilic — entrée signature Le Petit Louvre' ],
            [ 'file' => 'plat-2.jpg',      'alt' => 'Tartare de boeuf maison, câpres et cornichons' ],
            [ 'file' => 'plat-3-opt.jpg',  'alt' => 'Filet de poisson du jour, légumes de saison' ],
            [ 'file' => 'plat-7.jpg',      'alt' => 'Côte de veau rôtie, jus corsé et pommes grenaille' ],
            [ 'file' => 'plat-8.jpg',      'alt' => 'Salade fraîche revisitée par le chef' ],
            [ 'file' => 'plat-9.jpg',      'alt' => 'Velouté de homard, crème fouettée et ciboulette' ],
            [ 'file' => 'plat-10.jpg',     'alt' => 'Gambas rôties, riz pilaf et herbes fraîches' ],
            [ 'file' => 'plat-11.jpg',     'alt' => 'Pavé de boeuf charolais, beurre noisette' ],
            [ 'file' => 'plat-12.jpg',     'alt' => 'Assiette de fruits de mer, mayonnaise maison' ],
            [ 'file' => 'plat-13.jpg',     'alt' => 'Tarte fine aux légumes du marché, pesto basilic' ],
            [ 'file' => 'plat-14.jpg',     'alt' => 'Entrecôte grillée sauce béarnaise, frites fraîches' ],
            [ 'file' => 'plat-15-opt.jpg', 'alt' => 'Crevettes rôties, beurre citronné et ciboulette' ],
            [ 'file' => 'plat-16-opt.jpg', 'alt' => 'Tiramisu maison au café et cacao' ],
            [ 'file' => 'plat-17-opt.jpg', 'alt' => 'Fondant au chocolat noir, glace vanille' ],
            [ 'file' => 'plat-18-opt.jpg', 'alt' => 'Panna cotta aux fruits rouges frais' ],
            [ 'file' => 'plat-19.jpg',     'alt' => 'Millefeuille vanille et caramel beurre salé' ],
            [ 'file' => 'plat-20-opt.jpg', 'alt' => 'Assiette gastronomique du chef, Le Petit Louvre Arcachon' ],
            [ 'file' => 'plat-21-opt.jpg', 'alt' => 'Dessert de saison, création du chef pâtissier' ],
          ];
          foreach ( $plats as $p ) : ?>
            <div class="gal-slide gal-slide--plat">
              <img loading="eager" src="<?php echo esc_url( $tpl . '/img/' . $p['file'] ); ?>"
                   alt="<?php echo esc_attr( $p['alt'] ); ?>"
                   loading="eager" decoding="async">
            </div>
          <?php endforeach;
        endif; ?>

      </div>
    </div>
    <button class="gal-btn gal-next" id="platsNext" aria-label="Suivant">
      <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"/></svg>
    </button>
    <div class="gal-dots" id="platsDots"></div>
  </div>
  <div class="nosplats-cta reveal d4">
    <a href="<?php echo esc_url( home_url( '/carte/' ) ); ?>" class="btn btn-filled btn-lg">Voir toute la carte</a>
  </div>
</section>


<!-- ==========================================
     LE PETIT LOUVRE — Brand story
========================================== -->
<section class="section-lpl">
  <h2 class="lpl-title reveal d1"><?php echo esc_html( lpl_field( 'lpl_title', $pid, 'Le Petit Louvre' ) ); ?></h2>
  <div class="container" style="max-width:1200px;">
    <div class="row g-4 align-items-center">
      <div class="col-lg-5">
        <?php
        $lpl_photo = lpl_field( 'lpl_photo', $pid, null );
        if ( $lpl_photo && is_array( $lpl_photo ) ) :
          $src = esc_url( $lpl_photo['url'] );
          $alt = esc_attr( $lpl_photo['alt'] ?: 'Le Petit Louvre' );
          $w   = (int) $lpl_photo['width'];
          $h   = (int) $lpl_photo['height'];
        else :
          $src = $tpl . '/img/lpl-photo.jpg';
          $alt = 'Plat signature Le Petit Louvre';
          $w   = 800; $h = 600;
        endif; ?>
        <div class="lpl-photo reveal-left d2">
          <img loading="eager" src="<?php echo $src; ?>" alt="<?php echo $alt; ?>"
               width="<?php echo $w; ?>" height="<?php echo $h; ?>"
               loading="eager" decoding="async">
        </div>
      </div>
      <div class="col-lg-7">
        <div class="lpl-text reveal-right d3">
          <?php
          $lpl_body = lpl_field( 'lpl_body', $pid, '' );
          if ( $lpl_body ) :
            echo wp_kses_post( $lpl_body );
          else : ?>
            <p>Le Petit Louvre vous invite à vivre une expérience culinaire raffinée, dans une atmosphère unique où l'élégance rencontre l'émotion.</p>
            <p>Idéalement situé au cœur d'Arcachon, Le Petit Louvre incarne l'esprit de la gastronomie parisienne mélangé à la tradition : une cuisine authentique, exigeante et profondément ancrée dans le goût.</p>
            <p>Derrière son nom discret se révèle un lieu au design contemporain et soigné, où chaque détail a été pensé pour créer une ambiance chaleureuse, zen et résolument haut de gamme.</p>
            <p>Aux fourneaux, le chef imagine des plats d'exception, alliant tradition et création, pour offrir une cuisine précise, inspirée et généreuse, aussi belle à regarder qu'à savourer.</p>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>
</section>


<!-- ==========================================
     GALERIE — SLIDER
========================================== -->
<section class="section-galerie">
  <div class="galerie-header">
    <p class="section-label reveal d1">Galerie</p>
    <h2 class="galerie-title reveal d2">Notre univers</h2>
    <div class="sep-line reveal d3"></div>
  </div>

  <div class="gal-slider reveal d2" id="galerieSlider">
    <button class="gal-btn gal-prev" id="galPrev" aria-label="Précédent">
      <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="15 18 9 12 15 6"/></svg>
    </button>
    <div class="gal-viewport">
      <div class="gal-track" id="galTrack">

        <?php
        $galerie_acf = function_exists( 'have_rows' ) && have_rows( 'galerie_images', $pid );
        if ( $galerie_acf ) :
          while ( have_rows( 'galerie_images', $pid ) ) : the_row();
            $img = get_sub_field( 'image' );
            $alt = get_sub_field( 'alt' ) ?: ( $img['alt'] ?? 'Le Petit Louvre Arcachon' );
            if ( $img ) : ?>
              <div class="gal-slide">
                <img loading="eager" src="<?php echo esc_url( $img['url'] ); ?>"
                     alt="<?php echo esc_attr( $alt ); ?>"
                     width="<?php echo (int) $img['width']; ?>"
                     height="<?php echo (int) $img['height']; ?>"
                     loading="eager" decoding="async">
              </div>
            <?php endif;
          endwhile;
        else : /* Fallback images statiques */ ?>
          <div class="gal-slide"><img src="<?php echo $tpl; ?>/img/interieur-1-opt.jpg"  alt="Salle du restaurant Le Petit Louvre" width="800" height="340" decoding="async" loading="lazy"></div>
          <div class="gal-slide"><img src="<?php echo $tpl; ?>/img/interieur-2-opt.jpg"  alt="Décoration intérieure Le Petit Louvre" width="800" height="340" decoding="async" loading="lazy"></div>
          <div class="gal-slide"><img src="<?php echo $tpl; ?>/img/interieur-3.jpg"      alt="Ambiance chaleureuse en salle" width="800" height="340" decoding="async" loading="lazy"></div>
          <div class="gal-slide"><img src="<?php echo $tpl; ?>/img/interieur-4.jpg"      alt="Tables dressées au Petit Louvre" width="800" height="340" decoding="async" loading="lazy"></div>
          <div class="gal-slide"><img src="<?php echo $tpl; ?>/img/interieur-5.jpg"      alt="Cadre élégant du restaurant" width="800" height="340" decoding="async" loading="lazy"></div>
          <div class="gal-slide"><img src="<?php echo $tpl; ?>/img/interieur-6.jpg"      alt="Intérieur lumineux Le Petit Louvre" width="800" height="340" decoding="async" loading="lazy"></div>
          <div class="gal-slide"><img src="<?php echo $tpl; ?>/img/interieur-7.jpg"      alt="Espace salle restaurant Arcachon" width="800" height="340" decoding="async" loading="lazy"></div>
          <div class="gal-slide"><img src="<?php echo $tpl; ?>/img/interieur-8.jpg"      alt="Détails de décoration Le Petit Louvre" width="800" height="340" decoding="async" loading="lazy"></div>
          <div class="gal-slide"><img src="<?php echo $tpl; ?>/img/interieur-9.jpg"      alt="Vue de la salle principale" width="800" height="340" decoding="async" loading="lazy"></div>
          <div class="gal-slide"><img src="<?php echo $tpl; ?>/img/interieur-10.jpg"     alt="Mise en place soignée" width="800" height="340" decoding="async" loading="lazy"></div>
          <div class="gal-slide"><img src="<?php echo $tpl; ?>/img/interieur-11.jpg"     alt="Atmosphère du restaurant" width="800" height="340" decoding="async" loading="lazy"></div>
          <div class="gal-slide"><img src="<?php echo $tpl; ?>/img/interieur-12.jpg"     alt="Bar et comptoir Le Petit Louvre" width="800" height="340" decoding="async" loading="lazy"></div>
          <div class="gal-slide"><img src="<?php echo $tpl; ?>/img/interieur-13.jpg"     alt="Espace convivial en salle" width="800" height="340" decoding="async" loading="lazy"></div>
          <div class="gal-slide"><img src="<?php echo $tpl; ?>/img/interieur-14.jpg"     alt="Restaurant gastronomique Arcachon" width="800" height="340" decoding="async" loading="lazy"></div>
          <div class="gal-slide"><img src="<?php echo $tpl; ?>/img/interieur-15.jpg"     alt="Décor raffiné Le Petit Louvre" width="800" height="340" decoding="async" loading="lazy"></div>
          <div class="gal-slide"><img src="<?php echo $tpl; ?>/img/interieur-16.jpg"     alt="Salle intimiste bord de Bassin" width="800" height="340" decoding="async" loading="lazy"></div>
          <div class="gal-slide"><img src="<?php echo $tpl; ?>/img/interieur-17.jpg"     alt="Lumières tamisées restaurant" width="800" height="340" decoding="async" loading="lazy"></div>
          <div class="gal-slide"><img src="<?php echo $tpl; ?>/img/interieur-18.jpg"     alt="Tables en bois naturel Le Petit Louvre" width="800" height="340" decoding="async" loading="lazy"></div>
          <div class="gal-slide"><img src="<?php echo $tpl; ?>/img/interieur-19.jpeg"    alt="Coin salon du restaurant" width="800" height="340" decoding="async" loading="lazy"></div>
          <div class="gal-slide"><img src="<?php echo $tpl; ?>/img/interieur-20.jpg"     alt="Vue d'ensemble de la salle" width="800" height="340" decoding="async" loading="lazy"></div>
          <div class="gal-slide"><img src="<?php echo $tpl; ?>/img/interieur-21-opt.jpg" alt="Univers du Petit Louvre Arcachon" width="800" height="340" decoding="async" loading="lazy"></div>
        <?php endif; ?>

      </div>
    </div>
    <button class="gal-btn gal-next" id="galNext" aria-label="Suivant">
      <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"/></svg>
    </button>
    <div class="gal-dots" id="galDots"></div>
  </div>
</section>

<!-- ==========================================
     LIGHTBOX GALERIE HOME
========================================== -->
<div id="galLightbox" class="gal-lightbox" role="dialog" aria-modal="true" aria-label="Galerie photo">
  <button class="gal-lb-close" id="galLbClose" aria-label="Fermer">
    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
  </button>
  <button class="gal-lb-nav gal-lb-prev" id="galLbPrev" aria-label="Image précédente">
    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"><polyline points="15 18 9 12 15 6"/></svg>
  </button>
  <div class="gal-lb-inner">
    <img id="galLbImg" src="" alt="">
    <p id="galLbCaption" class="gal-lb-caption"></p>
    <p id="galLbCounter" class="gal-lb-counter"></p>
  </div>
  <button class="gal-lb-nav gal-lb-next" id="galLbNext" aria-label="Image suivante">
    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"><polyline points="9 18 15 12 9 6"/></svg>
  </button>
</div>


<!-- ==========================================
     AVIS CLIENTS
========================================== -->
<section class="section-avis">
  <div class="avis-header reveal d1">
    <p class="section-label">Avis clients</p>
    <h2 class="section-title"><?php echo esc_html( lpl_field( 'avis_section_title', $pid, 'Ce que disent nos clients' ) ); ?></h2>
    <div class="sep-line"></div>
  </div>

  <div class="avis-slider reveal d2" id="avisSlider">
    <button class="avis-btn avis-prev" id="avisPrev" aria-label="Précédent">
      <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="15 18 9 12 15 6"/></svg>
    </button>
    <div class="avis-viewport">
      <div class="avis-track" id="avisTrack">

        <?php
        $avis_acf = function_exists( 'have_rows' ) && have_rows( 'avis_list', $pid );
        if ( $avis_acf ) :
          while ( have_rows( 'avis_list', $pid ) ) : the_row();
            $quote      = get_sub_field( 'quote' );
            $name       = get_sub_field( 'name' );
            $via        = get_sub_field( 'via' ) ?: 'Via Google';
            $stars      = get_sub_field( 'stars' ) ?: '5';
            $stars_html = str_repeat( '★', (int) $stars ) . str_repeat( '☆', 5 - (int) $stars );
            $avatar_acf = get_sub_field( 'avatar' );
            $avatar_url = ( $avatar_acf && ! empty( $avatar_acf['url'] ) )
                ? $avatar_acf['url']
                : 'https://i.pravatar.cc/96?u=' . urlencode( strtolower( $name ) ); ?>
            <div class="avis-card">
              <div class="avis-stars"><?php echo $stars_html; ?></div>
              <p class="avis-quote">« <?php echo esc_html( $quote ); ?> »</p>
              <div class="avis-author">
                <div class="avis-avatar"><img loading="lazy" src="<?php echo esc_url( $avatar_url ); ?>" alt="Photo de <?php echo esc_attr( $name ); ?>" width="48" height="48"></div>
                <div>
                  <p class="avis-name"><?php echo esc_html( $name ); ?></p>
                  <p class="avis-via"><?php echo esc_html( $via ); ?></p>
                </div>
              </div>
            </div>
          <?php endwhile;
        else : /* Fallback avis hardcodés */ ?>

          <?php
          $fallback_avis = [
            ['name'=>'Malaurie Fernandez', 'via'=>'Via Google',      'avatar'=>'https://i.pravatar.cc/96?img=47', 'quote'=>'Un endroit incroyable&nbsp;! La nourriture était délicieuse et le service impeccable. Je recommande vivement ce restaurant à tous les amateurs de gastronomie fine.'],
            ['name'=>'Mélanie Hernandez',  'via'=>'Via Google',      'avatar'=>'https://i.pravatar.cc/96?img=23', 'quote'=>'Service impeccable, merci à Nils pour son professionnalisme&nbsp;! Nos plats étaient délicieux, bravo au chef&nbsp;! On reviendra avec plaisir.'],
            ['name'=>'Thomas Dupont',      'via'=>'Via Google',      'avatar'=>'https://i.pravatar.cc/96?img=12', 'quote'=>'Une expérience gastronomique exceptionnelle. Le chef sublime les produits locaux avec une créativité remarquable. Un incontournable d\'Arcachon&nbsp;!'],
            ['name'=>'Sophie Martin',      'via'=>'Via Google',      'avatar'=>'https://i.pravatar.cc/96?img=44', 'quote'=>'Cadre magnifique, accueil chaleureux et cuisine raffinée. Chaque plat est une œuvre d\'art. Nous avons passé une soirée mémorable.'],
            ['name'=>'Pierre Lefèvre',     'via'=>'Via TripAdvisor', 'avatar'=>'https://i.pravatar.cc/96?img=68', 'quote'=>'Le bar à vins est une vraie découverte. Cocktails créatifs et sélection de vins remarquable. Le lieu idéal pour une soirée d\'exception.'],
            ['name'=>'Claire Rousseau',    'via'=>'Via Google',      'avatar'=>'https://i.pravatar.cc/96?img=56', 'quote'=>'Notre anniversaire de mariage était magique ici. Service aux petits soins, menu dégustation divin. On ne pouvait pas rêver mieux.'],
          ];
          foreach ( $fallback_avis as $a ) : ?>
          <div class="avis-card">
            <div class="avis-stars">★★★★★</div>
            <p class="avis-quote">« <?php echo $a['quote']; ?> »</p>
            <div class="avis-author">
              <div class="avis-avatar"><img loading="lazy" src="<?php echo esc_url( $a['avatar'] ); ?>" alt="Photo de <?php echo esc_attr( $a['name'] ); ?>" width="48" height="48"></div>
              <div><p class="avis-name"><?php echo esc_html( $a['name'] ); ?></p><p class="avis-via"><?php echo esc_html( $a['via'] ); ?></p></div>
            </div>
          </div>
          <?php endforeach; ?>

        <?php endif; ?>

      </div>
    </div>
    <button class="avis-btn avis-next" id="avisNext" aria-label="Suivant">
      <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"/></svg>
    </button>
    <div class="avis-dots" id="avisDots"></div>
  </div>
</section>

<script>
/* ── Lightbox — galerie + plats (event delegation, fonctionne sur les clones slider) ── */
(function () {
  var lb      = document.getElementById('galLightbox');
  var lbImg   = document.getElementById('galLbImg');
  var lbCap   = document.getElementById('galLbCaption');
  var lbCount = document.getElementById('galLbCounter');
  var lbClose = document.getElementById('galLbClose');
  var lbPrev  = document.getElementById('galLbPrev');
  var lbNext  = document.getElementById('galLbNext');
  if (!lb) return;

  /* Récupère le src réel même si EWWW a remplacé src par un placeholder lazy */
  function getSrc(img) {
    return img.getAttribute('data-src') || img.getAttribute('data-eio-src') || img.src;
  }

  /* Snapshot des collections AVANT que le slider ne clone les slides */
  var galData  = Array.from(document.querySelectorAll('#galTrack  .gal-slide img'))
                      .map(function (img) { return { src: getSrc(img), alt: img.alt }; });
  var platData = Array.from(document.querySelectorAll('#platsTrack .gal-slide img'))
                      .map(function (img) { return { src: getSrc(img), alt: img.alt }; });

  var activeData = galData;
  var current    = 0;

  function openLb(idx, data) {
    activeData = data;
    current    = ((idx % data.length) + data.length) % data.length;
    lbImg.src            = activeData[current].src;
    lbImg.alt            = activeData[current].alt;
    lbCap.textContent    = activeData[current].alt;
    lbCount.textContent  = (current + 1) + ' / ' + activeData.length;
    lb.classList.add('is-open');
    document.body.style.overflow = 'hidden';
    lbClose.focus();
  }

  function closeLb() {
    lb.classList.remove('is-open');
    document.body.style.overflow = '';
  }

  function showPrev() { openLb(current - 1, activeData); }
  function showNext() { openLb(current + 1, activeData); }

  /* Event delegation — fonctionne aussi sur les slides clonés par le slider */
  function bindTrack(trackId, data) {
    var track = document.getElementById(trackId);
    if (!track) return;
    track.addEventListener('click', function (e) {
      var slide = e.target.closest('.gal-slide');
      if (!slide) return;
      var img = slide.querySelector('img');
      if (!img) return;
      var src = getSrc(img);
      var idx = data.findIndex(function (d) { return d.src === src; });
      openLb(idx < 0 ? 0 : idx, data);
    });
  }

  bindTrack('galTrack',   galData);
  bindTrack('platsTrack', platData);

  lbClose.addEventListener('click', closeLb);
  lbPrev.addEventListener('click', function (e) { e.stopPropagation(); showPrev(); });
  lbNext.addEventListener('click', function (e) { e.stopPropagation(); showNext(); });

  lb.addEventListener('click', function (e) { if (e.target === lb) closeLb(); });

  document.addEventListener('keydown', function (e) {
    if (!lb.classList.contains('is-open')) return;
    if (e.key === 'Escape')     closeLb();
    if (e.key === 'ArrowLeft')  showPrev();
    if (e.key === 'ArrowRight') showNext();
  });

  var touchStartX = 0;
  lb.addEventListener('touchstart', function (e) { touchStartX = e.touches[0].clientX; }, { passive: true });
  lb.addEventListener('touchend',   function (e) {
    var diff = touchStartX - e.changedTouches[0].clientX;
    if (Math.abs(diff) > 50) { diff > 0 ? showNext() : showPrev(); }
  });
}());
</script>

<?php get_footer(); ?>
