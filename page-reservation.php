<?php
/*
 * Template Name: Réservation
 */

/* ── Schema.org JSON-LD injecté dans <head> ── */
add_action( 'wp_head', function () { ?>
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "FoodEstablishment",
  "name": "Le Petit Louvre",
  "url": "<?php echo esc_url( home_url('/') ); ?>",
  "telephone": "+33557157359",
  "email": "reservation@lepetitlouvre.fr",
  "address": {
    "@type": "PostalAddress",
    "streetAddress": "14 Place Lucien de Gracia",
    "addressLocality": "Arcachon",
    "postalCode": "33120",
    "addressCountry": "FR"
  },
  "openingHoursSpecification": [
    { "@type": "OpeningHoursSpecification", "dayOfWeek": ["Monday","Tuesday","Wednesday","Thursday","Friday","Saturday","Sunday"], "opens": "09:00", "closes": "23:00" }
  ],
  "hasMap": "https://www.google.com/maps/dir//14+Pl.+Lucien+de+Gracia,+33120+Arcachon",
  "servesCuisine": "French Fusion",
  "priceRange": "€€",
  "aggregateRating": {
    "@type": "AggregateRating",
    "ratingValue": "4.8",
    "bestRating": "5",
    "ratingCount": "120"
  },
  "acceptsReservations": "True"
}
</script>
<?php } );

get_header();
$pid      = get_the_ID();
$pid_home = (int) get_option( 'page_on_front' ); // pour réutiliser les avis de l'accueil
$tpl      = get_template_directory_uri();
?>

<!-- ==========================================
     HERO
     Biais : Scarcité · Réciprocité · Preuve sociale
========================================== -->
<section class="hero" id="hero" aria-label="Réservation au restaurant Le Petit Louvre">

  <?php
  $resa_hero_images_acf = function_exists( 'have_rows' ) && have_rows( 'resa_hero_images', $pid );
  if ( $resa_hero_images_acf ) :
    $first = true;
    while ( have_rows( 'resa_hero_images', $pid ) ) : the_row();
      $img = get_sub_field( 'image' );
      $alt = get_sub_field( 'alt' ) ?: ( $img['alt'] ?? 'Le Petit Louvre Arcachon' );
      if ( $img ) :
        $priority = $first ? 'fetchpriority="high" decoding="async"' : 'loading="lazy" decoding="async"';
        $active   = $first ? ' active' : ''; ?>
        <img class="hero-bg<?php echo $active; ?>"
             src="<?php echo esc_url( $img['url'] ); ?>"
             alt="<?php echo esc_attr( $alt ); ?>"
             <?php echo $priority; ?>>
      <?php endif;
      $first = false;
    endwhile;
  else : /* Fallback statique */ ?>
    <img class="hero-bg active"
         src="<?php echo $tpl; ?>/img/interieur.jpg"
         alt="Salle du restaurant Le Petit Louvre à Arcachon"
         fetchpriority="high" decoding="async" width="1440" height="960">
    <img loading="lazy" class="hero-bg"
         src="<?php echo $tpl; ?>/img/interieur-1-opt.jpg"
         alt="Salle du restaurant Le Petit Louvre en service, ambiance chaleureuse"
         loading="lazy" decoding="async" width="1440" height="960">
  <?php endif; ?>

  <div class="hero-overlay-top" aria-hidden="true"></div>
  <div class="hero-overlay-mid"  aria-hidden="true"></div>

  <?php get_template_part('template-parts/site-header'); ?>

  <div class="hero-content">
    <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="hero-logo-mobile" aria-label="Accueil Le Petit Louvre">
      <img loading="lazy" src="<?php echo esc_url( get_template_directory_uri() ); ?>/img/logo.svg" alt="Le Petit Louvre" width="100" height="100">
    </a>
    <div class="resa-live-badge" id="heroBadge" role="status" aria-live="polite">
      <span class="resa-dot" id="heroDot" aria-hidden="true"></span><span id="heroText">Tables disponibles ce soir</span>
    </div>
    <p class="hero-label"><?php echo esc_html( lpl_field( 'resa_hero_label', $pid, 'Restaurant · Arcachon' ) ); ?></p>
    <h1 class="hero-title" style="white-space: nowrap; font-size: clamp(26px, 6.5vw, 60px);"><?php echo esc_html( lpl_field( 'resa_hero_title', $pid, 'Réservez votre table' ) ); ?></h1>
    <?php
    $tagline_1 = lpl_field( 'resa_hero_tagline_1', $pid, 'Votre table garantie' );
    $tagline_2 = lpl_field( 'resa_hero_tagline_2', $pid, 'Confirmation par email sous 2h' );
    if ( $tagline_1 || $tagline_2 ) : ?>
    <p class="hero-tagline">
      <?php if ( $tagline_1 ) : ?><?php echo esc_html( $tagline_1 ); ?><?php endif; ?>
      <?php if ( $tagline_1 && $tagline_2 ) : ?><br><?php endif; ?>
      <?php if ( $tagline_2 ) : ?><?php echo esc_html( $tagline_2 ); ?><?php endif; ?>
    </p>
    <?php endif; ?>
    <div class="hero-cta d-flex flex-wrap gap-3 justify-content-center">
      <a href="#resa-form" class="btn btn-filled btn-lg btn-compact">Réserver en ligne</a>
      <a href="tel:0557157359" class="btn btn-outline btn-lg">05 57 15 73 59</a>
    </div>
  </div>

  <button class="hero-scroll-hint" id="heroScrollHint" data-target="resa-form" aria-label="Faire défiler vers le formulaire de réservation">
    <span>Réserver</span>
    <div class="scroll-arrow" aria-hidden="true"></div>
  </button>

</section>


<!-- ==========================================
     TRUST STRIP — Bootstrap row, 4 colonnes
     Biais : Autorité · Preuve sociale
========================================== -->
<div class="resa-trust-strip" role="complementary" aria-label="Nos engagements">
  <div class="container">
    <ul class="row g-0 align-items-center justify-content-center list-unstyled mb-0" role="list">

      <li class="col-6 col-md-4 reveal d1">
        <div class="resa-trust-item">
          <!-- Intérieur : table et chaises -->
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true" focusable="false"><path d="M3 7h18M3 7v13M21 7v13M6 7V4M18 7V4M9 20v-6h6v6"/><rect x="6" y="14" width="12" height="6" rx="1"/></svg>
          <span><?php echo esc_html( lpl_field( 'resa_trust_1', $pid, '50 couverts en intérieur' ) ); ?></span>
        </div>
      </li>

      <li class="col-6 col-md-4 reveal d2">
        <div class="resa-trust-item">
          <!-- Extérieur : parasol / terrasse -->
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true" focusable="false"><path d="M12 2C6.5 2 2 6 2 11h20c0-5-4.5-9-10-9z"/><line x1="12" y1="11" x2="12" y2="22"/><line x1="8" y1="22" x2="16" y2="22"/><path d="M5 17h4M15 17h4"/></svg>
          <span><?php echo esc_html( lpl_field( 'resa_trust_2', $pid, '70 couverts en extérieur' ) ); ?></span>
        </div>
      </li>

      <li class="col-6 col-md-4 reveal d3">
        <div class="resa-trust-item">
          <!-- Confirmation : enveloppe / check -->
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true" focusable="false"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
          <span><?php echo esc_html( lpl_field( 'resa_trust_3', $pid, 'Table confirmée sous 2h' ) ); ?></span>
        </div>
      </li>

    </ul>
  </div>
</div>


<!-- ==========================================
     RÉSERVATION — Zenchef centré + Infos en dessous
========================================== -->
<section class="resa-main" id="resa-form" aria-labelledby="resa-form-title">

  <!-- ── Widget Zenchef centré ── -->
  <div class="resa-booking-centered">
    <div class="resa-booking-header">
      <p class="section-label reveal d1">Réservation en ligne</p>
      <h2 class="section-title reveal d2" id="resa-form-title">Votre table vous attend</h2>
      <div class="sep-line reveal d3"></div>
    </div>

    <!-- ── Terrasse : info "sans réservation" — positionnée avant le widget pour éviter la frustration ── -->
    <div class="terrasse-info-bloc reveal d3" role="note" aria-label="Information terrasse">
      <div class="terrasse-info-icon" aria-hidden="true">
        <!-- Parasol -->
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
          <path d="M12 2C6.5 2 2 6 2 11h20c0-5-4.5-9-10-9z"/>
          <line x1="12" y1="11" x2="12" y2="22"/>
          <line x1="8" y1="22" x2="16" y2="22"/>
          <path d="M5 17h4M15 17h4"/>
        </svg>
      </div>
      <div class="terrasse-info-body">
        <p class="terrasse-info-label">La terrasse</p>
        <p class="terrasse-info-title">Pas besoin de réserver — venez directement !</p>
        <p class="terrasse-info-text">Notre terrasse est ouverte sans réservation. Présentez-vous sur place, nous vous installons avec plaisir dès qu'une table se libère.</p>
      </div>
      <span class="terrasse-info-badge" aria-label="Terrasse sans réservation">
        <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><polyline points="20 6 9 17 4 12"/></svg>
        Sans réservation
      </span>
    </div>

    <div class="zenchef-wrapper reveal d4">
      <iframe
        src="https://bookings.zenchef.com/results?rid=376712&pid=1001&backgroundColor=606930&primaryColor=606930"
        id="zenchef-iframe"
        title="Réserver une table au Petit Louvre"
        frameborder="0"
        scrolling="no"
        allow="payment"
        loading="lazy">
      </iframe>
    </div>

    <p class="resa-booking-reassure reveal d5">
      <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
      Données confidentielles &middot; Confirmation sous 2h &middot; Annulation gratuite 24h avant
    </p>

    <!-- ── Moyens de paiement — mobile uniquement ── -->
    <div class="resa-payment-block" aria-label="Moyens de paiement acceptés">
      <p class="resa-payment-label">
        <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
        Paiement sur place
      </p>
      <div class="resa-payment-icons">
        <!-- CB -->
        <span class="pay-chip" aria-label="Carte bancaire">
          <svg width="36" height="24" viewBox="0 0 54 34" fill="none" aria-hidden="true">
            <rect width="54" height="34" rx="4" fill="#f0f4ff"/>
            <rect x="4" y="4" width="46" height="26" rx="3" fill="none" stroke="#1a3a6b" stroke-width="1"/>
            <text x="27" y="21" text-anchor="middle" fill="#1a3a6b" font-size="12" font-weight="800" font-family="Arial,sans-serif">CB</text>
          </svg>
        </span>
        <!-- Visa -->
        <span class="pay-chip" aria-label="Visa">
          <svg width="44" height="24" viewBox="0 0 66 34" fill="none" aria-hidden="true">
            <rect width="66" height="34" rx="4" fill="#f0f4ff"/>
            <text x="33" y="23" text-anchor="middle" fill="#1a1f71" font-size="16" font-weight="800" font-family="Arial,sans-serif" font-style="italic">VISA</text>
          </svg>
        </span>
        <!-- Mastercard -->
        <span class="pay-chip" aria-label="Mastercard">
          <svg width="40" height="24" viewBox="0 0 54 34" aria-hidden="true">
            <rect width="54" height="34" rx="4" fill="#f9f9f9"/>
            <circle cx="20" cy="17" r="10" fill="#eb001b"/>
            <circle cx="34" cy="17" r="10" fill="#f79e1b"/>
            <path d="M27 9a10 10 0 0 1 0 16 10 10 0 0 1 0-16z" fill="#ff5f00"/>
          </svg>
        </span>
        <!-- Espèces -->
        <span class="pay-chip pay-chip--cash" aria-label="Espèces">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#33520b" stroke-width="2" stroke-linecap="round" aria-hidden="true"><circle cx="12" cy="12" r="8"/><path d="M12 6v2m0 8v2M9.5 9.5C9.5 8.1 10.6 7 12 7s2.5 1.1 2.5 2.5c0 2.5-5 2.5-5 5C9.5 15.9 10.6 17 12 17s2.5-1.1 2.5-2.5"/></svg>
          <span>Espèces</span>
        </span>
      </div>
      <p class="resa-payment-note">Chèques non acceptés &nbsp;·&nbsp; CB minimum 5&nbsp;€</p>
    </div>

  </div>

  <!-- ── Bande d'infos horizontale (sous le form) ── -->
  <div class="resa-info-bar" role="complementary" aria-label="Informations pratiques">
    <div class="resa-info-bar-inner">

      <!-- Badges statut -->
      <div class="resa-info-badges reveal d1">
        <div class="resa-open-badge" id="cardBadge">
          <span class="resa-dot" id="cardDot" aria-hidden="true"></span>
          <span id="cardText">Ouvert ce soir jusqu'à 23h</span>
        </div>
        <div class="resa-urgency" role="status" aria-live="polite">
          <span class="resa-dot resa-dot--amber" aria-hidden="true"></span>
          <span id="resaCounter">Le week-end est souvent complet dès le mercredi</span>
        </div>
      </div>

      <div class="resa-info-sep" aria-hidden="true"></div>

      <!-- Horaires -->
      <div class="resa-info-col reveal d2">
        <div class="resa-info-icon" aria-hidden="true">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
        </div>
        <div class="resa-info-content">
          <p class="resa-info-label">Horaires</p>
          <p class="resa-info-text"><?php echo esc_html( lpl_field( 'resa_horaires_1', $pid, 'Lun – Dim  ·  9h → 23h' ) ); ?></p>
        </div>
      </div>

      <div class="resa-info-sep" aria-hidden="true"></div>

      <!-- Adresse -->
      <div class="resa-info-col reveal d3">
        <div class="resa-info-icon" aria-hidden="true">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
        </div>
        <div class="resa-info-content">
          <p class="resa-info-label">Adresse</p>
          <address class="resa-info-text not-italic"><?php echo esc_html( lpl_field( 'resa_adresse', $pid, '14 Pl. Lucien de Gracia, 33120 Arcachon' ) ); ?></address>
          <a href="<?php echo esc_url( lpl_field( 'resa_maps_url', $pid, 'https://www.google.com/maps/dir//14+Pl.+Lucien+de+Gracia,+33120+Arcachon' ) ); ?>"
             target="_blank" rel="noopener noreferrer" class="resa-info-link"
             aria-label="Voir le plan d'accès sur Google Maps (nouvel onglet)">
            Voir le plan →
          </a>
        </div>
      </div>

    </div>
  </div>

</section>


<!-- ==========================================
     3 BONNES RAISONS — Panneaux photo immersifs
     Biais : Ancrage · Réciprocité · Dotation
========================================== -->
<section class="resa-raisons" aria-labelledby="raisons-title">
  <div class="raisons-header reveal d1">
    <p class="section-label">Pourquoi réserver ?</p>
    <h2 class="section-title" id="raisons-title">3 bonnes raisons de réserver maintenant</h2>
    <div class="sep-line"></div>
  </div>

  <div class="raisons-panels">

    <?php
    /* SVG icons fixes pour chaque panneau */
    $raison_icons = [
      '<svg viewBox="0 0 32 32" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M10 16l4 4 8-8"/><circle cx="16" cy="16" r="13"/></svg>',
      '<svg viewBox="0 0 32 32" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><rect x="4" y="6" width="24" height="20" rx="3"/><polyline points="4 11 16 18 28 11"/></svg>',
      '<svg viewBox="0 0 32 32" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M16 4l2.5 5 5.5.8-4 3.9.95 5.5L16 16.5l-4.95 2.7L12 13.7 8 9.8l5.5-.8z"/><path d="M10 22c-2.5 1.2-4 3-4 5h20c0-2-1.5-3.8-4-5"/></svg>',
    ];

    /* Fallback si ACF vide */
    $raisons_fallback = [
      [ 'titre' => 'Table garantie',       'description' => 'Aucune file d\'attente. Votre table est réservée, prête et préparée pour votre arrivée.',                              'img_src' => $tpl . '/img/interieur-1-opt.jpg', 'img_alt' => 'Salle lumineuse du restaurant Le Petit Louvre',       'tag' => '' ],
      [ 'titre' => 'Confirmation rapide',  'description' => 'Réservez en ligne et recevez la confirmation par email sous 2h. Annulation gratuite jusqu\'à 24h avant.',             'img_src' => $tpl . '/img/interieur-2-opt.jpg', 'img_alt' => 'Table intime face aux baies vitrées',                'tag' => 'Simple &amp; sans stress' ],
      [ 'titre' => 'Service personnalisé', 'description' => 'Anniversaire, allergie, préférence de table ? Mentionnez-le — l\'équipe anticipe chaque détail.',                     'img_src' => $tpl . '/img/privatisation.jpg',   'img_alt' => 'Table dressée avec soin pour un dîner au Petit Louvre', 'tag' => '' ],
    ];

    $raisons_acf = function_exists( 'have_rows' ) && have_rows( 'resa_raisons', $pid );
    $raisons     = $raisons_acf ? null : $raisons_fallback;
    $delay_cls   = [ 'd1', 'd2', 'd3' ];
    $i           = 0;

    if ( $raisons_acf ) :
      while ( have_rows( 'resa_raisons', $pid ) ) : the_row();
        $featured_cls = ( $i === 1 ) ? ' raison-panel--featured' : '';
        $num          = str_pad( $i + 1, 2, '0', STR_PAD_LEFT );
        $img          = get_sub_field( 'image' );
        $img_src      = $img ? esc_url( $img['url'] ) : '';
        $img_alt      = $img['alt'] ?? '';
        $tag          = get_sub_field( 'tag' );
        $titre        = get_sub_field( 'titre' );
        $desc         = get_sub_field( 'description' );
        ?>
        <article class="raison-panel<?php echo $featured_cls; ?> reveal <?php echo $delay_cls[$i]; ?>" aria-label="Raison <?php echo $i+1; ?> : <?php echo esc_attr($titre); ?>">
          <?php if ( $img_src ) : ?>
            <img loading="lazy" class="raison-panel-img" src="<?php echo $img_src; ?>" alt="<?php echo esc_attr($img_alt); ?>"  decoding="async" width="800" height="1000">
          <?php endif; ?>
          <div class="raison-panel-overlay" aria-hidden="true"></div>
          <div class="raison-panel-body">
            <span class="raison-panel-num" aria-hidden="true"><?php echo $num; ?></span>
            <div class="raison-panel-icon" aria-hidden="true"><?php echo $raison_icons[$i]; ?></div>
            <h3 class="raison-panel-title"><?php echo esc_html( $titre ); ?></h3>
            <p class="raison-panel-desc"><?php echo esc_html( $desc ); ?></p>
            <?php if ( $tag ) : ?><span class="raison-panel-tag"><?php echo esc_html( $tag ); ?></span><?php endif; ?>
          </div>
        </article>
        <?php
        $i++;
      endwhile;
    else :
      foreach ( $raisons_fallback as $i => $r ) :
        $featured_cls = ( $i === 1 ) ? ' raison-panel--featured' : '';
        $num          = str_pad( $i + 1, 2, '0', STR_PAD_LEFT ); ?>
        <article class="raison-panel<?php echo $featured_cls; ?> reveal <?php echo $delay_cls[$i]; ?>" aria-label="Raison <?php echo $i+1; ?> : <?php echo esc_attr($r['titre']); ?>">
          <img loading="lazy" class="raison-panel-img" src="<?php echo $r['img_src']; ?>" alt="<?php echo esc_attr($r['img_alt']); ?>"  decoding="async" width="800" height="1000">
          <div class="raison-panel-overlay" aria-hidden="true"></div>
          <div class="raison-panel-body">
            <span class="raison-panel-num" aria-hidden="true"><?php echo $num; ?></span>
            <div class="raison-panel-icon" aria-hidden="true"><?php echo $raison_icons[$i]; ?></div>
            <h3 class="raison-panel-title"><?php echo esc_html( $r['titre'] ); ?></h3>
            <p class="raison-panel-desc"><?php echo esc_html( $r['description'] ); ?></p>
            <?php if ( $r['tag'] ) : ?><span class="raison-panel-tag" aria-label="<?php echo strip_tags($r['tag']); ?>"><?php echo $r['tag']; ?></span><?php endif; ?>
          </div>
        </article>
      <?php endforeach;
    endif; ?>

  </div>

  <div class="raisons-cta reveal d4">
    <a href="#resa-form" class="btn btn-filled">Réserver ma table →</a>
  </div>
</section>


<!-- ==========================================
     MARQUEE VERTICAL — 3 colonnes infinies
========================================== -->
<section class="resa-marquee" aria-labelledby="marquee-title">
  <div class="avis-header mb-5">
    <p class="section-label reveal d1">Notre cuisine</p>
    <h2 class="section-title reveal d2" id="marquee-title">La cuisine en images</h2>
    <div class="sep-line reveal d3"></div>
  </div>

  <div class="marquee-v-grid" aria-hidden="true">

    <?php
    /* ── Récupération des images ACF ou fallback statique ── */
    $marquee_acf = function_exists( 'get_field' ) ? get_field( 'resa_marquee_images', $pid ) : null;

    if ( $marquee_acf && count( $marquee_acf ) >= 9 ) {
      // Construire un tableau [ url => alt ]
      $marquee_map = [];
      foreach ( $marquee_acf as $row ) {
        $img = $row['image'];
        if ( $img ) $marquee_map[ $img['url'] ] = $row['alt'] ?: ( $img['alt'] ?? 'Le Petit Louvre' );
      }
    } else {
      // Fallback statique
      $imgDir      = $tpl . '/img/';
      $marquee_map = [];
      foreach ( [
        'plat-7.jpg'      => 'Plat gastronomique Le Petit Louvre',
        'plat-8.jpg'      => 'Cuisine du terroir au Petit Louvre',
        'plat-9.jpg'      => 'Entrée raffinée restaurant bord de mer',
        'plat-10.jpg'     => 'Dessert maison Le Petit Louvre',
        'plat-11.jpg'     => 'Poisson frais du jour au Petit Louvre',
        'plat-12.jpg'     => 'Assiette gastronomique estivale',
        'plat-13.jpg'     => 'Salade fraîche restaurant Le Petit Louvre',
        'plat-14.jpg'     => 'Viande grillée maison',
        'plat-15-opt.jpg' => 'Crustacés et fruits de mer Le Petit Louvre',
        'plat-16-opt.jpg' => 'Tartare de poisson frais',
        'plat-17-opt.jpg' => 'Dessert gourmand restaurant Sète',
        'plat-18-opt.jpg' => 'Cuisine méditerranéenne Le Petit Louvre',
      ] as $f => $a ) {
        $marquee_map[ $imgDir . $f ] = $a;
      }
    }

    $m_files = array_keys( $marquee_map );
    $m_count = count( $m_files );

    /* Répartir en 3 colonnes décalées */
    $cols = [
      [ 'files' => array_values( $m_files ),                                                                 'dir' => 'up',   'dur' => '48s' ],
      [ 'files' => array_merge( array_slice( $m_files, (int)($m_count/3) ), array_slice( $m_files, 0, (int)($m_count/3) ) ), 'dir' => 'down', 'dur' => '40s' ],
      [ 'files' => array_merge( array_slice( $m_files, (int)($m_count*2/3) ), array_slice( $m_files, 0, (int)($m_count*2/3) ) ), 'dir' => 'up',   'dur' => '56s' ],
    ];

    foreach ( $cols as $col ) :
      $class = 'marquee-v-track--' . $col['dir'];
    ?>
    <div class="marquee-v-col">
      <div class="marquee-v-track <?php echo $class; ?>" style="--dur:<?php echo $col['dur']; ?>">
        <?php
        $set = array_merge( $col['files'], $col['files'] );
        foreach ( $set as $url ) :
          $alt = $marquee_map[ $url ] ?? 'Le Petit Louvre';
          echo '<img src="' . esc_url( $url ) . '" alt="' . esc_attr( $alt ) . '" width="600" height="450" loading="lazy" decoding="async">' . "\n        ";
        endforeach;
        ?>
      </div>
    </div>
    <?php endforeach; ?>

  </div>
</section>


<!-- ==========================================
     AVIS CLIENTS — identique à l'accueil
     Biais : Preuve sociale
========================================== -->
<section class="section-avis" aria-labelledby="avis-title">
  <div class="avis-header reveal d1">
    <p class="section-label">Avis clients</p>
    <h2 class="section-title" id="avis-title">Ce que disent nos clients</h2>
    <div class="sep-line"></div>
  </div>

  <div class="avis-slider reveal d2" id="avisSlider" role="region" aria-label="Carrousel d'avis clients" aria-roledescription="carrousel">
    <button class="avis-btn avis-prev" id="avisPrev" aria-label="Avis précédent" aria-controls="avisTrack">
      <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true" focusable="false"><polyline points="15 18 9 12 15 6"/></svg>
    </button>

    <div class="avis-viewport">
      <div class="avis-track" id="avisTrack">

        <?php
        /* ── Avis lus depuis la page d'accueil (source unique) ── */
        $avis_acf = function_exists( 'have_rows' ) && have_rows( 'avis_list', $pid_home );
        if ( $avis_acf ) :
          while ( have_rows( 'avis_list', $pid_home ) ) : the_row();
            $quote   = get_sub_field( 'quote' );
            $name    = get_sub_field( 'name' );
            $via     = get_sub_field( 'via' ) ?: 'Via Google';
            $stars   = get_sub_field( 'stars' ) ?: '5';
            $stars_html = str_repeat( '★', (int) $stars ) . str_repeat( '☆', 5 - (int) $stars );
            $avatar_url = 'https://i.pravatar.cc/96?u=' . urlencode( strtolower( $name ) ); ?>
            <article class="avis-card" aria-label="Avis de <?php echo esc_attr( $name ); ?>">
              <div class="avis-stars" aria-label="<?php echo $stars; ?> étoiles sur 5"><?php echo $stars_html; ?></div>
              <p class="avis-quote">« <?php echo esc_html( $quote ); ?> »</p>
              <div class="avis-author">
                <div class="avis-avatar"><img loading="lazy" src="<?php echo esc_url( $avatar_url ); ?>" alt="Photo de <?php echo esc_attr( $name ); ?>" width="48" height="48"></div>
                <div><p class="avis-name"><?php echo esc_html( $name ); ?></p><p class="avis-via"><?php echo esc_html( $via ); ?></p></div>
              </div>
            </article>
          <?php endwhile;
        else : /* Fallback hardcodé */ ?>

          <?php
          $fallback_avis = [
            ['name'=>'Malaurie Fernandez', 'via'=>'Via Google',      'avatar'=>'https://i.pravatar.cc/96?img=47', 'quote'=>'Un endroit incroyable ! La nourriture était délicieuse et le service impeccable. Je recommande vivement ce restaurant à tous les amateurs de gastronomie fine.'],
            ['name'=>'Mélanie Hernandez',  'via'=>'Via Google',      'avatar'=>'https://i.pravatar.cc/96?img=23', 'quote'=>'Service impeccable, merci à Nils pour son professionnalisme ! Nos plats étaient délicieux, bravo au chef ! On reviendra avec plaisir.'],
            ['name'=>'Thomas Dupont',      'via'=>'Via Google',      'avatar'=>'https://i.pravatar.cc/96?img=12', 'quote'=>'Une expérience gastronomique exceptionnelle. Le chef sublime les produits locaux avec une créativité remarquable. Un incontournable d\'Arcachon !'],
            ['name'=>'Sophie Martin',      'via'=>'Via Google',      'avatar'=>'https://i.pravatar.cc/96?img=44', 'quote'=>'Cadre magnifique, accueil chaleureux et cuisine raffinée. Chaque plat est une œuvre d\'art. Nous avons passé une soirée mémorable.'],
            ['name'=>'Pierre Lefèvre',     'via'=>'Via TripAdvisor', 'avatar'=>'https://i.pravatar.cc/96?img=68', 'quote'=>'Le bar à vins est une vraie découverte. Cocktails créatifs et sélection de vins remarquable. Le lieu idéal pour une soirée d\'exception.'],
            ['name'=>'Claire Rousseau',    'via'=>'Via Google',      'avatar'=>'https://i.pravatar.cc/96?img=56', 'quote'=>'Notre anniversaire de mariage était magique ici. Service aux petits soins, menu dégustation divin. On ne pouvait pas rêver mieux.'],
          ];
          foreach ( $fallback_avis as $a ) : ?>
          <article class="avis-card" aria-label="Avis de <?php echo esc_attr( $a['name'] ); ?>">
            <div class="avis-stars" aria-label="5 étoiles sur 5">★★★★★</div>
            <p class="avis-quote">« <?php echo esc_html( $a['quote'] ); ?> »</p>
            <div class="avis-author">
              <div class="avis-avatar"><img loading="lazy" src="<?php echo esc_url( $a['avatar'] ); ?>" alt="Photo de <?php echo esc_attr( $a['name'] ); ?>" width="48" height="48"></div>
              <div><p class="avis-name"><?php echo esc_html( $a['name'] ); ?></p><p class="avis-via"><?php echo esc_html( $a['via'] ); ?></p></div>
            </div>
          </article>
          <?php endforeach; ?>

        <?php endif; ?>

      </div>
    </div>

    <button class="avis-btn avis-next" id="avisNext" aria-label="Avis suivant" aria-controls="avisTrack">
      <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true" focusable="false"><polyline points="9 18 15 12 9 6"/></svg>
    </button>
    <div class="avis-dots" id="avisDots" role="tablist" aria-label="Navigation du carrousel"></div>
  </div>
</section>


<!-- ==========================================
     FAQ — Levée des derniers freins
     Heuristique H10 : aide contextuelle au bon moment
========================================== -->
<section class="resa-faq" id="faq" aria-labelledby="faq-title">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-12 col-md-10 col-lg-8">

        <div class="resa-faq-header reveal d1">
          <p class="section-label">Questions fréquentes</p>
          <h2 class="section-title" id="faq-title">Tout ce qu'il faut savoir</h2>
          <div class="sep-line"></div>
        </div>

        <ul class="resa-faq-list reveal d2" role="list">

          <!-- Annulation -->
          <li class="resa-faq-item">
            <button class="resa-faq-btn" aria-expanded="false" aria-controls="faq-1">
              <span class="resa-faq-q">Puis-je annuler ma réservation ?</span>
              <span class="resa-faq-icon" aria-hidden="true">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><line x1="12" y1="5" x2="12" y2="19" class="faq-plus-v"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
              </span>
            </button>
            <div class="resa-faq-answer" id="faq-1" role="region">
              <p>Oui — <strong>annulation gratuite jusqu'à 24h avant</strong> votre repas. Passé ce délai, merci de nous prévenir par téléphone au <a href="tel:0557157359">05&nbsp;57&nbsp;15&nbsp;73&nbsp;59</a> afin que nous puissions proposer la table à d'autres clients.</p>
            </div>
          </li>

          <!-- Allergies -->
          <li class="resa-faq-item">
            <button class="resa-faq-btn" aria-expanded="false" aria-controls="faq-2">
              <span class="resa-faq-q">Pouvez-vous adapter les plats aux allergies et régimes spéciaux ?</span>
              <span class="resa-faq-icon" aria-hidden="true">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><line x1="12" y1="5" x2="12" y2="19" class="faq-plus-v"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
              </span>
            </button>
            <div class="resa-faq-answer" id="faq-2" role="region">
              <p>Absolument. Indiquez vos <strong>allergies ou préférences alimentaires</strong> (végétarien, sans gluten, intolérance lactose…) dans le champ «&nbsp;commentaire&nbsp;» lors de la réservation en ligne ou par téléphone. Notre chef anticipera pour vous.</p>
            </div>
          </li>

          <!-- Terrasse sans résa -->
          <li class="resa-faq-item">
            <button class="resa-faq-btn" aria-expanded="false" aria-controls="faq-3">
              <span class="resa-faq-q">Faut-il réserver pour accéder à la terrasse ?</span>
              <span class="resa-faq-icon" aria-hidden="true">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><line x1="12" y1="5" x2="12" y2="19" class="faq-plus-v"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
              </span>
            </button>
            <div class="resa-faq-answer" id="faq-3" role="region">
              <p><strong>Non, la terrasse est sans réservation.</strong> Présentez-vous directement — nous vous installons avec plaisir dès qu'une table se libère. Pour l'intérieur en revanche, nous recommandons fortement de réserver, notamment le week-end.</p>
            </div>
          </li>

          <!-- Parking -->
          <li class="resa-faq-item">
            <button class="resa-faq-btn" aria-expanded="false" aria-controls="faq-4">
              <span class="resa-faq-q">Y a-t-il un parking à proximité ?</span>
              <span class="resa-faq-icon" aria-hidden="true">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><line x1="12" y1="5" x2="12" y2="19" class="faq-plus-v"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
              </span>
            </button>
            <div class="resa-faq-answer" id="faq-4" role="region">
              <p>Oui — un <strong>parking public est disponible à 20 mètres</strong>, Place Lucien de Gracia. L'accès est simple depuis le centre d'Arcachon. <a href="https://www.google.com/maps/dir//14+Pl.+Lucien+de+Gracia,+33120+Arcachon" target="_blank" rel="noopener noreferrer">Voir l'itinéraire →</a></p>
            </div>
          </li>

        </ul>

      </div>
    </div>
  </div>
</section>


<!-- ==========================================
     CTA FINAL — Bootstrap col centré
     Biais : FOMO · Urgence
========================================== -->
<section class="resa-cta-final" aria-labelledby="cta-final-title">
  <?php
  $cta_img = lpl_field( 'resa_cta_image', $pid, null );
  $cta_img_src = ( $cta_img && is_array( $cta_img ) ) ? esc_url( $cta_img['url'] ) : $tpl . '/img/terrasse-2.jpg';
  $cta_img_alt = ( $cta_img && is_array( $cta_img ) ) ? esc_attr( $cta_img['alt'] ) : '';
  $cta_title   = lpl_field( 'resa_cta_title',    $pid, "Les meilleures tables\npartent en premier" );
  $cta_sub     = lpl_field( 'resa_cta_subtitle', $pid, "Le week-end est souvent complet dès le mercredi.\nAssurez votre place maintenant." );
  $cta_fine    = lpl_field( 'resa_cta_fine',     $pid, 'Annulation gratuite jusqu\'à 24h avant · Confirmation par email sous 2h' );
  $cta_label   = lpl_field( 'resa_cta_label',    $pid, 'Ne tardez pas' );
  ?>
  <div class="resa-cta-final-photo" aria-hidden="true">
    <img loading="lazy" src="<?php echo $cta_img_src; ?>" alt="<?php echo $cta_img_alt; ?>"  decoding="async" width="1440" height="800">
  </div>
  <div class="container position-relative">
    <div class="row justify-content-center">
      <div class="col-12 col-md-10 col-lg-7 col-xl-6 text-center">
        <p class="resa-cta-label reveal d1"><?php echo esc_html( $cta_label ); ?></p>
        <h2 class="resa-cta-title reveal d2" id="cta-final-title">
          <?php echo nl2br( esc_html( $cta_title ) ); ?>
        </h2>
        <p class="resa-cta-sub reveal d3">
          <?php echo nl2br( esc_html( $cta_sub ) ); ?>
        </p>
        <div class="d-flex flex-wrap gap-3 justify-content-center reveal d4">
          <a href="#resa-form" class="btn btn-filled btn-lg resa-pulse">Réserver ma table</a>
          <a href="tel:+33557157359" class="btn btn-outline btn-lg resa-btn-white">
            Appeler le restaurant
          </a>
        </div>
        <p class="resa-cta-fine reveal d5">
          <?php echo esc_html( $cta_fine ); ?>
        </p>
      </div>
    </div>
  </div>
</section>


<style>
/* ====================================================
   PAGE RÉSERVATION — styles spécifiques
==================================================== */

/* ── Variables locales ── */
:root {
  --resa-border: rgba(51,82,11,.08);
}

/* ── Hero badges ── */
.resa-live-badge {
  display: inline-flex; align-items: center; gap: 8px;
  background: rgba(255,255,255,.14); backdrop-filter: blur(8px);
  border: 1px solid rgba(255,255,255,.22); border-radius: 50px;
  padding: 6px 16px; color: #fff; font-size: 13px;
  letter-spacing: .04em; margin-bottom: 14px;
}
.resa-dot {
  display: inline-block; width: 8px; height: 8px;
  border-radius: 50%; background: #4ade80; flex-shrink: 0;
  animation: dotPulse 2s infinite;
}
.resa-dot--amber { background: #f59e0b; }
.resa-dot--red   { background: #ef4444; animation: dotPulseRed 2s infinite; }
@keyframes dotPulse {
  0%,100% { box-shadow: 0 0 0 0 rgba(74,222,128,.55); }
  60%      { box-shadow: 0 0 0 8px rgba(74,222,128,0); }
}
@keyframes dotPulseRed {
  0%,100% { box-shadow: 0 0 0 0 rgba(239,68,68,.55); }
  60%      { box-shadow: 0 0 0 8px rgba(239,68,68,0); }
}

/* ── Trust strip ── */
.resa-trust-strip {
  background: #fff;
  border-bottom: 1px solid var(--resa-border);
  padding: 14px 0;
}
.resa-trust-item {
  display: flex; align-items: center; justify-content: center;
  gap: 8px; padding: 10px 8px;
  font-size: 13px; font-weight: 500; color: var(--dark-green);
  text-align: center;
}
.resa-trust-item svg { width: 18px; height: 18px; stroke: var(--olive-green); flex-shrink: 0; }
@media (max-width: 575px) {
  .resa-trust-item { font-size: 12px; flex-direction: column; gap: 4px; }
}


/* ── Open badge sidebar ── */
.resa-open-badge {
  display: inline-flex; align-items: center; gap: 8px;
  background: rgba(74,222,128,.08);
  border: 1px solid rgba(74,222,128,.3);
  border-radius: 50px; padding: 6px 14px;
  font-size: 13px; color: #15803d; font-weight: 500;
  width: fit-content;
}

/* ── Layout principal ── */
.resa-main { background: #faf8f4; scroll-margin-top: 80px; }

/* ── Bande d'infos horizontale (sous le form) ── */
.resa-info-bar {
  background: #fff;
  border-top: 1px solid var(--resa-border);
  padding: 28px 24px;
}
.resa-info-bar-inner {
  display: flex;
  align-items: center;
  justify-content: center;
  flex-wrap: wrap;
  gap: 0;
  max-width: 1100px;
  margin: 0 auto;
}

/* Badges côte à côte */
.resa-info-badges {
  display: flex;
  flex-direction: column;
  gap: 10px;
  align-items: flex-start;
  padding: 0 32px;
}

/* Séparateur vertical */
.resa-info-sep {
  width: 1px;
  height: 64px;
  background: var(--resa-border);
  flex-shrink: 0;
}

/* Colonne info */
.resa-info-col {
  display: flex;
  align-items: flex-start;
  gap: 14px;
  padding: 0 32px;
}
.resa-info-icon {
  width: 38px; height: 38px;
  background: var(--light-green-bg);
  border-radius: 50%;
  display: flex; align-items: center; justify-content: center;
  flex-shrink: 0;
}
.resa-info-icon svg { stroke: var(--olive-green); }
.resa-info-content { display: flex; flex-direction: column; gap: 2px; }
.resa-info-label {
  font-size: 10px; font-weight: 700; letter-spacing: .12em;
  text-transform: uppercase; color: var(--olive-green); margin-bottom: 3px;
}
.resa-info-text {
  font-size: 13px; color: var(--dark-green); line-height: 1.6; margin: 0;
  font-style: normal;
}
.resa-info-closed { font-size: 12px; color: #aaa; font-style: italic; margin: 0; }
.resa-info-link {
  font-size: 12px; font-weight: 600; color: var(--olive-green);
  text-decoration: none; margin-top: 4px; display: inline-block;
}
.resa-info-link:hover { text-decoration: underline; }
.resa-phone-link {
  font-size: 18px; font-weight: 800; letter-spacing: .03em;
  color: var(--dark-green); text-decoration: none;
  transition: color .2s;
}
.resa-phone-link:hover { color: var(--olive-green); }
.resa-mail-link {
  font-size: 12px; color: #999; text-decoration: none;
  transition: color .2s; margin-top: 2px; display: inline-block;
}
.resa-mail-link:hover { color: var(--dark-green); text-decoration: underline; }

/* ── Terrasse info bloc ── */
.terrasse-info-bloc {
  display: flex;
  align-items: center;
  gap: 16px;
  background: #fff;
  border: 1px solid rgba(90,120,50,0.18);
  border-left: 3px solid var(--olive-green);
  border-radius: 12px;
  padding: 18px 20px 18px 18px;
  box-shadow: 0 2px 16px rgba(26,46,10,0.06);
  position: relative;
  overflow: hidden;
}
/* Fond décoratif très subtil */
.terrasse-info-bloc::before {
  content: '';
  position: absolute;
  inset: 0;
  background: linear-gradient(105deg, rgba(90,120,50,0.03) 0%, transparent 60%);
  pointer-events: none;
}
.terrasse-info-icon {
  flex-shrink: 0;
  width: 46px;
  height: 46px;
  border-radius: 50%;
  background: rgba(90,120,50,0.08);
  border: 1px solid rgba(90,120,50,0.15);
  display: flex;
  align-items: center;
  justify-content: center;
}
.terrasse-info-icon svg {
  width: 20px;
  height: 20px;
  stroke: var(--olive-green);
}
.terrasse-info-body {
  flex: 1;
  min-width: 0;
}
.terrasse-info-label {
  font-family: 'Inter', sans-serif;
  font-size: 10px;
  font-weight: 700;
  letter-spacing: 0.12em;
  text-transform: uppercase;
  color: var(--olive-green);
  margin: 0 0 3px;
}
.terrasse-info-title {
  font-family: 'Fraunces', serif;
  font-size: 15px;
  font-weight: 600;
  color: var(--dark-green);
  margin: 0 0 4px;
  line-height: 1.3;
}
.terrasse-info-text {
  font-family: 'Inter', sans-serif;
  font-size: 12.5px;
  color: #6b7c5a;
  margin: 0;
  line-height: 1.55;
}
.terrasse-info-badge {
  flex-shrink: 0;
  display: inline-flex;
  align-items: center;
  gap: 5px;
  padding: 5px 12px;
  background: rgba(90,120,50,0.1);
  border: 1px solid rgba(90,120,50,0.25);
  border-radius: 50px;
  font-family: 'Inter', sans-serif;
  font-size: 11px;
  font-weight: 600;
  color: var(--olive-green);
  letter-spacing: 0.02em;
  white-space: nowrap;
  align-self: flex-start;
}
.terrasse-info-badge svg { stroke: var(--olive-green); flex-shrink: 0; }

@media (max-width: 600px) {
  .terrasse-info-bloc {
    flex-wrap: nowrap;
    align-items: center;
    gap: 12px;
    padding: 14px 16px;
  }
  /* Icône masquée — trop encombrante sur petit écran */
  .terrasse-info-icon { display: none; }
  /* Description masquée — le titre suffit en mobile */
  .terrasse-info-text { display: none; }
  .terrasse-info-title { font-size: 13.5px; margin-bottom: 0; }
  .terrasse-info-badge {
    align-self: center;
    margin-left: 0;
    padding: 4px 10px;
    font-size: 10px;
  }
}

/* ── Widget Zenchef centré ── */
.resa-booking-centered {
  padding: 56px 24px 48px;
  max-width: 740px;
  margin: 0 auto;
  display: flex;
  flex-direction: column;
  gap: 24px;
}
.resa-booking-header {
  display: flex; flex-direction: column;
  align-items: center; text-align: center; gap: 10px;
}
.zenchef-wrapper {
  background: #fff;
  border-radius: 16px;
  overflow: hidden;
  min-height: 520px;
  border: 1px solid var(--resa-border);
  padding: 24px;
}
#zenchef-iframe {
  display: block; width: 100%;
  height: 100%; min-height: 520px;
  border: none;
  background: #f5f0e8;
  border-radius: 16px;
  color-scheme: light;
}
.resa-booking-reassure {
  display: flex; align-items: center; justify-content: center;
  flex-wrap: wrap; gap: 4px 8px;
  font-size: 12px; color: #bbb; text-align: center; margin: 0;
}
.resa-booking-reassure svg { stroke: #ccc; flex-shrink: 0; }

/* ── Moyens de paiement — mobile uniquement ── */
.resa-payment-block { display: none; }
@media (max-width: 853px) {
  .resa-payment-block {
    display: flex; flex-direction: column; align-items: center;
    gap: 14px; margin-top: 24px; padding: 20px 24px 18px;
    background: white;
    border-radius: 12px;
    border: 1px solid rgba(51,82,11,0.12);
    box-shadow: 0 2px 12px rgba(51,82,11,0.06);
    width: 100%;
  }
  .resa-payment-label {
    display: flex; align-items: center; gap: 6px;
    font-family: 'Inter', sans-serif; font-size: 10px; font-weight: 600;
    letter-spacing: 0.1em; text-transform: uppercase;
    color: var(--olive-green); margin: 0;
  }
  .resa-payment-icons {
    display: flex; align-items: center; justify-content: center;
    flex-wrap: wrap; gap: 10px;
  }
  .pay-chip {
    display: inline-flex; align-items: center; justify-content: center;
    background: white; border-radius: 5px;
    border: 1px solid rgba(0,0,0,0.08);
    box-shadow: 0 1px 4px rgba(0,0,0,0.08);
    padding: 4px 6px;
  }
  .pay-chip--cash {
    gap: 5px; padding: 5px 10px;
    font-family: 'Inter', sans-serif; font-size: 11px;
    font-weight: 600; color: var(--olive-green);
    border-color: rgba(51,82,11,0.2);
  }
  .resa-payment-note {
    font-family: 'Inter', sans-serif; font-size: 10px;
    color: #aaa; margin: 0; text-align: center;
  }
}

/* ── Responsive info bar ── */
@media (max-width: 980px) {
  .resa-info-bar-inner { gap: 20px; }
  .resa-info-col { padding: 0 20px; }
  .resa-info-badges { padding: 0 20px; }
}
@media (max-width: 700px) {
  .resa-info-bar-inner {
    flex-direction: column;
    align-items: flex-start;
    gap: 20px;
  }
  .resa-info-sep { width: 100%; height: 1px; }
  .resa-info-col,
  .resa-info-badges { padding: 0 8px; }
  .resa-booking-centered { padding: 40px 20px 36px; }
  .zenchef-wrapper { padding: 0; border-radius: 0; border: none; }
  #zenchef-iframe { border-radius: 0; }
}

/* ── Section 3 raisons — Panneaux photo immersifs ── */
.resa-raisons {
  padding: var(--section-py-lg) 0;
  background: var(--dark-green);
  overflow: hidden;
}

.raisons-header {
  text-align: center;
  padding: 0 24px;
  margin-bottom: 48px;
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 16px;
}
.raisons-header .section-label { color: rgba(255,255,255,.5); }
.raisons-header .section-title { color: #fff; }
.raisons-header .sep-line { background: rgba(255,255,255,.25); margin: 0 auto; }

/* Grille 3 panneaux */
.raisons-panels {
  display: grid;
  grid-template-columns: 1fr 1.15fr 1fr;
  gap: 3px;
  max-width: 1400px;
  margin: 0 auto;
}

/* Panneau individuel */
.raison-panel {
  position: relative;
  overflow: hidden;
  min-height: 520px;
  cursor: default;
  display: flex;
  flex-direction: column;
}

/* Photo de fond */
.raison-panel-img {
  position: absolute;
  inset: 0;
  width: 100%;
  height: 100%;
  object-fit: cover;
  object-position: center;
  transition: transform .9s cubic-bezier(.22,.68,0,1.2);
  will-change: transform;
}
.raison-panel:hover .raison-panel-img {
  transform: scale(1.06);
}

/* Overlay dégradé : transparent → vert sombre */
.raison-panel-overlay {
  position: absolute;
  inset: 0;
  background: linear-gradient(
    160deg,
    rgba(10,25,5,.15) 0%,
    rgba(10,25,5,.55) 45%,
    rgba(10,25,5,.88) 100%
  );
  transition: opacity .5s ease;
  z-index: 1;
}
.raison-panel--featured .raison-panel-overlay {
  background: linear-gradient(
    160deg,
    rgba(41,68,5,.2) 0%,
    rgba(41,68,5,.6) 45%,
    rgba(41,68,5,.92) 100%
  );
}
.raison-panel:hover .raison-panel-overlay { opacity: .85; }

/* Contenu positionné en bas */
.raison-panel-body {
  position: relative;
  z-index: 2;
  margin-top: auto;
  padding: 36px 32px 40px;
  display: flex;
  flex-direction: column;
  gap: 10px;
}

/* Numéro éditorial fantôme en haut à droite */
.raison-panel-num {
  position: absolute;
  top: 28px;
  right: 28px;
  font-family: var(--ff-serif);
  font-size: 88px;
  line-height: 1;
  font-weight: 700;
  color: rgba(255,255,255,.1);
  letter-spacing: -.02em;
  user-select: none;
  pointer-events: none;
  transition: color .4s ease;
}
.raison-panel:hover .raison-panel-num { color: rgba(255,255,255,.18); }

/* Icône */
.raison-panel-icon {
  width: 48px; height: 48px;
  border-radius: 50%;
  border: 1px solid rgba(255,255,255,.3);
  display: flex; align-items: center; justify-content: center;
  backdrop-filter: blur(6px);
  background: rgba(255,255,255,.08);
  flex-shrink: 0;
  transition: background .3s, border-color .3s;
}
.raison-panel:hover .raison-panel-icon {
  background: rgba(255,255,255,.16);
  border-color: rgba(255,255,255,.5);
}
.raison-panel-icon svg {
  width: 22px; height: 22px;
  stroke: #fff;
}

/* Titre */
.raison-panel-title {
  font-family: var(--ff-serif);
  font-size: clamp(20px, 2vw, 26px);
  font-weight: 700;
  color: #fff;
  line-height: 1.2;
  margin: 0;
}

/* Description — toujours visible, renforcée au hover */
.raison-panel-desc {
  font-family: var(--ff-sans);
  font-size: var(--fs-sm);
  color: rgba(255,255,255,.72);
  line-height: 1.65;
  margin: 0;
  max-width: 28ch;
  transform: translateY(6px);
  opacity: .85;
  transition: opacity .4s ease, transform .4s ease, color .3s;
}
.raison-panel:hover .raison-panel-desc {
  opacity: 1;
  transform: translateY(0);
  color: rgba(255,255,255,.92);
}

/* Tag featured */
.raison-panel-tag {
  display: inline-block;
  align-self: flex-start;
  margin-top: 6px;
  padding: 5px 14px;
  background: rgba(255,255,255,.15);
  backdrop-filter: blur(6px);
  border: 1px solid rgba(255,255,255,.3);
  border-radius: var(--radius-pill);
  font-family: var(--ff-label);
  font-size: 11px;
  font-weight: 600;
  letter-spacing: .1em;
  text-transform: uppercase;
  color: #fff;
  transition: background .3s, border-color .3s;
}
.raison-panel:hover .raison-panel-tag {
  background: rgba(255,255,255,.22);
  border-color: rgba(255,255,255,.5);
}

/* Ligne séparatrice fine featured */
.raison-panel--featured .raison-panel-body::before {
  content: '';
  display: block;
  width: 36px;
  height: 2px;
  background: rgba(255,255,255,.4);
  border-radius: 2px;
  margin-bottom: 6px;
}

/* CTA centré sous les panneaux */
.raisons-cta {
  text-align: center;
  padding: 40px 24px 8px;
}
.raisons-cta .btn-filled {
  background: rgba(255,255,255,.1);
  border: 1px solid rgba(255,255,255,.3);
  color: #fff;
  backdrop-filter: blur(8px);
  transition: background .3s, border-color .3s;
}
.raisons-cta .btn-filled:hover {
  background: rgba(255,255,255,.2);
  border-color: rgba(255,255,255,.6);
  color: #fff;
}

/* ── Responsive ── */
@media (max-width: 860px) {
  .raisons-panels {
    grid-template-columns: 1fr 1fr;
    grid-template-rows: auto auto;
  }
  .raison-panel--featured {
    grid-column: 1 / -1;
    min-height: 380px;
  }
  .raison-panel { min-height: 320px; }
  .raison-panel-num { font-size: 64px; }
}
@media (max-width: 540px) {
  .raisons-panels { grid-template-columns: 1fr; }
  .raison-panel--featured { grid-column: 1; }
  .raison-panel { min-height: 300px; }
  .raison-panel-body { padding: 28px 24px 32px; }
}

/* ── Marquee vertical 3 colonnes ── */
.resa-marquee { padding: 80px 0 0; background: #fff; overflow: hidden; }

.marquee-v-grid {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 12px;
  height: 580px;
  overflow: hidden;
  mask-image: linear-gradient(to bottom,
    transparent 0%,
    black 12%,
    black 88%,
    transparent 100%
  );
  -webkit-mask-image: linear-gradient(to bottom,
    transparent 0%,
    black 12%,
    black 88%,
    transparent 100%
  );
}

.marquee-v-col { overflow: hidden; }

.marquee-v-track {
  display: flex;
  flex-direction: column;
  gap: 12px;
  will-change: transform;
}

.marquee-v-track--up {
  animation: marqueeUp var(--dur, 28s) linear infinite;
}
.marquee-v-track--down {
  animation: marqueeDown var(--dur, 22s) linear infinite;
  transform: translateY(-50%);
}

@keyframes marqueeUp {
  0%   { transform: translateY(0); }
  100% { transform: translateY(-50%); }
}
@keyframes marqueeDown {
  0%   { transform: translateY(-50%); }
  100% { transform: translateY(0); }
}

/* Pause au survol */
.marquee-v-grid:hover .marquee-v-track {
  animation-play-state: paused;
}

.marquee-v-track img {
  width: 100%;
  aspect-ratio: 4/3;
  object-fit: cover;
  border-radius: 10px;
  display: block;
}

@media (max-width: 767px) {
  .marquee-v-grid {
    grid-template-columns: repeat(2, 1fr);
    height: 420px;
  }
  .marquee-v-col:last-child { display: none; }
}
@media (max-width: 480px) {
  .marquee-v-grid { height: 340px; }
}

/* ── Urgency chip ── */
.resa-urgency {
  display: inline-flex; align-items: center; gap: 8px;
  background: #fffbeb; border: 1px solid #fde68a;
  border-radius: 50px; padding: 6px 14px;
  font-size: 13px; color: #92400e;
  max-width: 100%;
}

/* ── CTA Final ── */
.resa-cta-final {
  position: relative; padding: 120px 24px;
  background: #2c4a08; overflow: hidden; text-align: center;
}
.resa-cta-final-photo { position: absolute; inset: 0; pointer-events: none; }
.resa-cta-final-photo img { width: 100%; height: 100%; object-fit: cover; opacity: .1; }
.resa-cta-label {
  font-family: 'Inter', sans-serif; font-size: 12px; font-weight: 600;
  letter-spacing: .2em; text-transform: uppercase;
  color: rgba(255,255,255,.5); margin-bottom: 16px;
}
.resa-cta-title {
  font-family: 'Fraunces', serif; font-weight: 600; font-size: clamp(30px,5vw,52px);
  color: #fff; line-height: 1.15; margin-bottom: 20px;
}
.resa-cta-sub { font-size: 17px; color: rgba(255,255,255,.65); line-height: 1.6; margin-bottom: 36px; }
.resa-cta-fine {
  margin-top: 20px; font-size: 12px;
  color: rgba(255,255,255,.35); letter-spacing: .03em;
}
.resa-btn-white {
  background: transparent !important;
  border-color: rgba(255,255,255,.6) !important;
  color: #fff !important;
}
.resa-btn-white:hover { background: rgba(255,255,255,.12) !important; border-color: #fff !important; }
@keyframes ctaPulse {
  0%,100% { box-shadow: 0 0 0 0 rgba(255,255,255,.3); }
  60%      { box-shadow: 0 0 0 16px rgba(255,255,255,0); }
}
.resa-pulse { animation: ctaPulse 3s ease-in-out 2s infinite; }

/* ── FAQ ── */
.resa-faq {
  background: #fff;
  padding: 80px 24px 72px;
  border-top: 1px solid var(--resa-border);
}
.resa-faq-header {
  text-align: center;
  margin-bottom: 48px;
  display: flex; flex-direction: column; align-items: center; gap: 12px;
}
.resa-faq-list {
  list-style: none; padding: 0; margin: 0;
  display: flex; flex-direction: column; gap: 0;
}
.resa-faq-item {
  border-bottom: 1px solid var(--resa-border);
}
.resa-faq-item:first-child {
  border-top: 1px solid var(--resa-border);
}
.resa-faq-btn {
  width: 100%;
  display: flex; align-items: center; justify-content: space-between; gap: 16px;
  background: none; border: none; cursor: pointer;
  padding: 22px 4px;
  text-align: left;
  color: var(--dark-green);
  transition: color 0.2s ease;
}
@media (hover: hover) and (pointer: fine) {
  .resa-faq-btn:hover { color: var(--olive-green); }
  .resa-faq-btn:hover .resa-faq-icon { background: var(--light-green-bg); border-color: var(--olive-green); }
}
.resa-faq-q {
  font-family: 'Fraunces', serif;
  font-size: clamp(16px, 2vw, 19px);
  font-weight: 500;
  line-height: 1.35;
  flex: 1;
}
.resa-faq-icon {
  flex-shrink: 0;
  width: 32px; height: 32px;
  border-radius: 50%;
  border: 1.5px solid rgba(51,82,11,0.2);
  display: flex; align-items: center; justify-content: center;
  color: var(--olive-green);
  transition: background 0.2s ease, border-color 0.2s ease, transform 0.35s var(--ease-expo);
}
.resa-faq-btn[aria-expanded="true"] .resa-faq-icon {
  background: var(--olive-green);
  border-color: var(--olive-green);
  color: white;
  transform: rotate(45deg);
}
.resa-faq-btn[aria-expanded="true"] .resa-faq-icon svg { stroke: white; }
.faq-plus-v {
  transition: transform 0.3s ease, opacity 0.3s ease;
  transform-origin: center;
}
.resa-faq-btn[aria-expanded="true"] .faq-plus-v {
  transform: scaleY(0);
  opacity: 0;
}
.resa-faq-answer {
  overflow: hidden;
  max-height: 0;
  transition: max-height 0.4s var(--ease-expo), padding 0.3s ease;
}
.resa-faq-answer.open {
  max-height: 300px;
}
.resa-faq-answer p {
  font-family: 'Inter', sans-serif;
  font-size: 15px;
  color: #5a6e45;
  line-height: 1.7;
  margin: 0;
  padding: 0 4px 22px;
}
.resa-faq-answer p strong { color: var(--dark-green); font-weight: 600; }
.resa-faq-answer p a {
  color: var(--olive-green); font-weight: 600; text-decoration: underline;
  text-underline-offset: 3px;
}
@media (max-width: 575px) {
  .resa-faq { padding: 56px 20px 52px; }
  .resa-faq-btn { padding: 18px 2px; }
  .resa-faq-q { font-size: 15px; }
}

/* ── focus-visible ── */
a:focus-visible, button:focus-visible {
  outline: 3px solid var(--olive-green); outline-offset: 3px; border-radius: 4px;
}

/* ── Accessibilité : réduction de mouvement ── */
@media (prefers-reduced-motion: reduce) {
  .raison-panel-img { transition: none; }
  .raison-panel-desc { transition: none; opacity: 1; transform: none; }
  .raison-panel-icon, .raison-panel-tag, .raison-panel-overlay { transition: none; }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {

  /* ── Statut ouvert / fermé en temps réel ── */
  (function () {
    // Horaires du restaurant (heure de Paris)
    // Index : 0=dim, 1=lun, 2=mar, 3=mer, 4=jeu, 5=ven, 6=sam
    var schedule = {
      0: { open: 9,  close: 23, label: 'jusqu\'à 23h' },  // Dimanche
      1: { open: 9,  close: 23, label: 'jusqu\'à 23h' },  // Lundi
      2: { open: 9,  close: 23, label: 'jusqu\'à 23h' },  // Mardi
      3: { open: 9,  close: 23, label: 'jusqu\'à 23h' },  // Mercredi
      4: { open: 9,  close: 23, label: 'jusqu\'à 23h' },  // Jeudi
      5: { open: 9,  close: 23, label: 'jusqu\'à 23h' },  // Vendredi
      6: { open: 9,  close: 23, label: 'jusqu\'à 23h' },  // Samedi
    };

    // Heure courante en fuseau Europe/Paris
    var paris    = new Date(new Date().toLocaleString('en-US', { timeZone: 'Europe/Paris' }));
    var day      = paris.getDay();
    var hour     = paris.getHours();
    var slot     = schedule[day];
    var isOpen   = slot !== null && hour >= slot.open && hour < slot.close;

    var dots  = [document.getElementById('heroDot'),  document.getElementById('cardDot')];
    var texts = [document.getElementById('heroText'), document.getElementById('cardText')];

    dots.forEach(function(dot) {
      if (!dot) return;
      if (isOpen) {
        dot.classList.remove('resa-dot--red');
      } else {
        dot.classList.add('resa-dot--red');
        dot.style.background = '#ef4444';
      }
    });

    texts.forEach(function(el) {
      if (!el) return;
      if (isOpen) {
        el.textContent = 'Ouvert aujourd\'hui ' + slot.label;
      } else if (slot === null) {
        el.textContent = 'Fermé aujourd\'hui';
      } else if (hour < slot.open) {
        el.textContent = 'Ouvre à ' + slot.open + 'h ce matin';
      } else {
        el.textContent = 'Fermé pour ce soir';
      }
    });
  })();

  /* Compteur social proof — message aléatoire */
  var c = document.getElementById('resaCounter');
  if (c) {
    var msgs = [
      'Le week-end est souvent complet dès le mercredi',
      'Dernières tables disponibles ce vendredi soir'
    ];
    c.textContent = msgs[Math.floor(Math.random() * msgs.length)];
  }

  /* ── Accordéon FAQ ── */
  document.querySelectorAll('.resa-faq-btn').forEach(function(btn) {
    btn.addEventListener('click', function() {
      var expanded = this.getAttribute('aria-expanded') === 'true';
      var answerId = this.getAttribute('aria-controls');
      var answer   = document.getElementById(answerId);

      /* Fermer tous les autres */
      document.querySelectorAll('.resa-faq-btn').forEach(function(b) {
        b.setAttribute('aria-expanded', 'false');
        var a = document.getElementById(b.getAttribute('aria-controls'));
        if (a) a.classList.remove('open');
      });

      /* Ouvrir / fermer celui-ci */
      if (!expanded) {
        this.setAttribute('aria-expanded', 'true');
        if (answer) answer.classList.add('open');
      }
    });
  });

  /* Redimensionnement auto de l'iframe Zenchef via postMessage */
  window.addEventListener('message', function (e) {
    if (e.origin.indexOf('zenchef.com') === -1) return;
    var iframe = document.getElementById('zenchef-iframe');
    if (!iframe) return;
    if (e.data && e.data.height) {
      iframe.style.height = (parseInt(e.data.height) + 20) + 'px';
    }
  });


});
</script>

<script>
/* Scroll vers #resa-form si présent dans l'URL */
(function () {
  if ( window.location.hash !== '#resa-form' ) return;
  var target = document.getElementById('resa-form');
  if ( !target ) return;
  /* Désactive le scroll natif immédiat du navigateur */
  history.replaceState(null, '', window.location.pathname);
  /* Rescroll après rendu complet */
  window.addEventListener('load', function () {
    setTimeout(function () {
      history.replaceState(null, '', '#resa-form');
      target.scrollIntoView({ behavior: 'smooth', block: 'start' });
    }, 500);
  });
}());
</script>

<?php get_footer(); ?>
