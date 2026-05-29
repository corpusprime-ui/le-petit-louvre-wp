<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
  <meta charset="<?php bloginfo( 'charset' ); ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="theme-color" content="#1d3300">
  <link rel="icon" type="image/svg+xml" href="<?php echo esc_url( get_template_directory_uri() ); ?>/img/logo.svg">
  <link rel="icon" type="image/png" sizes="32x32" href="<?php echo esc_url( get_template_directory_uri() ); ?>/img/favicon-32.png">
  <link rel="apple-touch-icon" sizes="180x180" href="<?php echo esc_url( get_template_directory_uri() ); ?>/img/apple-touch-icon.png">
  <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<a class="skip-to-content" href="#hero">Aller au contenu principal</a>

<!-- ==========================================
     STICKY NAV (apparaît au scroll)
========================================== -->
<nav class="sticky-nav" id="stickyNav" role="navigation" aria-label="Navigation principale">

  <?php
  $is_bar   = is_page_template(['page-carte.php','page-carte-des-vins.php','page-carte-des-boissons.php','page-carte-des-cocktails.php','page-carte-des-alcools.php']);
  $is_home  = is_front_page() || is_home();
  $is_resa  = is_page_template('page-reservation.php');
  $is_cont  = is_page_template('page-contact.php');
  ?>

  <!-- Liens gauche — poussés vers la droite (vers le logo) -->
  <div class="sticky-nav-group sticky-nav-left">
    <a href="<?php echo esc_url( home_url( '/#presentation' ) ); ?>" class="nav-link<?php echo $is_home  ? ' active' : ''; ?>">Déjeuner / Dîner</a>
    <a href="<?php echo esc_url( home_url( '/carte-des-vins/' ) ); ?>" class="nav-link<?php echo $is_bar   ? ' active' : ''; ?>">Le bar</a>
  </div>

  <!-- Logo centré (absolu) -->
  <a href="<?php echo esc_url( home_url( '/' ) ); ?>">
    <img class="nav-logo-sticky"
         src="<?php echo esc_url( get_template_directory_uri() ); ?>/img/logo.svg"
         alt="Le Petit Louvre">
  </a>

  <!-- Liens droite — poussés vers la gauche (vers le logo) -->
  <div class="sticky-nav-group sticky-nav-right">
    <a href="<?php echo esc_url( home_url( '/reservation/' ) ); ?>" class="nav-link<?php echo $is_resa  ? ' active' : ''; ?>">Réservation</a>
    <a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>" class="nav-link<?php echo $is_cont  ? ' active' : ''; ?>">Contact</a>
  </div>

  <!-- Hamburger mobile -->
  <button class="sticky-mobile-btn" id="stickyMenuBtn" aria-label="Ouvrir le menu">
    <span></span><span></span><span></span>
  </button>

</nav>
