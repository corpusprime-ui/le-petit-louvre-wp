<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
  <meta charset="<?php bloginfo( 'charset' ); ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="theme-color" content="#1d3300">
  <link rel="icon" type="image/svg+xml" href="<?php echo get_template_directory_uri(); ?>/img/logo.svg">
  <link rel="icon" type="image/png" sizes="32x32" href="<?php echo get_template_directory_uri(); ?>/img/favicon-32.png">
  <link rel="apple-touch-icon" sizes="180x180" href="<?php echo get_template_directory_uri(); ?>/img/apple-touch-icon.png">
  <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<a class="skip-to-content" href="#hero">Aller au contenu principal</a>

<!-- ==========================================
     STICKY NAV (apparaît au scroll)
========================================== -->
<nav class="sticky-nav" id="stickyNav" role="navigation" aria-label="Navigation principale">

  <!-- Liens gauche — poussés vers la droite (vers le logo) -->
  <div class="sticky-nav-group sticky-nav-left">
    <a href="<?php echo esc_url( home_url( '/#presentation' ) ); ?>" class="nav-link">Déjeuner / Dîner</a>
    <a href="<?php echo esc_url( home_url( '/carte-des-vins/' ) ); ?>" class="nav-link">Le bar</a>
  </div>

  <!-- Logo centré (absolu) -->
  <a href="<?php echo esc_url( home_url( '/' ) ); ?>">
    <img class="nav-logo-sticky"
         src="<?php echo get_template_directory_uri(); ?>/img/logo.svg"
         alt="Le Petit Louvre">
  </a>

  <!-- Liens droite — poussés vers la gauche (vers le logo) -->
  <div class="sticky-nav-group sticky-nav-right">
    <a href="<?php echo esc_url( home_url( '/reservation/' ) ); ?>" class="nav-link">Réservation</a>
    <a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>" class="nav-link">Contact</a>
  </div>

  <!-- Téléphone — absolu à droite -->
  <a href="tel:0557157359" class="sticky-nav-phone">
    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
      <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 22 16.92z"/>
    </svg>
    <span>05&nbsp;57&nbsp;15&nbsp;73&nbsp;59</span>
  </a>

  <!-- Hamburger mobile -->
  <button class="sticky-mobile-btn" id="stickyMenuBtn" aria-label="Ouvrir le menu">
    <span></span><span></span><span></span>
  </button>

</nav>
