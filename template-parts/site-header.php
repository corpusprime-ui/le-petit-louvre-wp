<?php
/**
 * Template Part : Header principal du site
 * Inclus via get_template_part('template-parts/site-header') sur toutes les pages.
 * Modifier ici = mis à jour partout.
 */

/* ── Options du thème ── */
$h_telephone      = get_field( 'opt_telephone', 'option' ) ?: '05 57 15 73 59';
$h_telephone_href = 'tel:+33' . ltrim( preg_replace( '/[^0-9]/', '', $h_telephone ), '0' );

/* ── Page active ── */
$h_is_bar  = is_page_template(['page-carte.php','page-carte-des-vins.php','page-carte-des-boissons.php','page-carte-des-cocktails.php','page-carte-des-alcools.php']);
$h_is_home = is_front_page() || is_home();
$h_is_resa = is_page_template('page-reservation.php');
$h_is_cont = is_page_template('page-contact.php');
?>
<header class="header" role="banner">

  <nav class="nav-group" aria-label="Navigation gauche">
    <a href="<?php echo esc_url( home_url( '/#presentation' ) ); ?>" class="nav-link<?php echo $h_is_home ? ' active' : ''; ?>">Déjeuner / Dîner</a>
    <a href="<?php echo esc_url( home_url( '/carte-des-vins/' ) ); ?>" class="nav-link<?php echo $h_is_bar  ? ' active' : ''; ?>">Le bar</a>
  </nav>

  <a href="<?php echo esc_url( home_url( '/' ) ); ?>" aria-label="Accueil Le Petit Louvre">
    <img class="nav-logo"
         src="<?php echo get_template_directory_uri(); ?>/img/logo.svg"
         alt="Le Petit Louvre"
         width="110" height="102">
  </a>

  <nav class="nav-group" aria-label="Navigation droite">
    <a href="<?php echo esc_url( home_url( '/reservation/' ) ); ?>" class="nav-link<?php echo $h_is_resa ? ' active' : ''; ?>">Réservation</a>
    <a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>" class="nav-link<?php echo $h_is_cont ? ' active' : ''; ?>">Contact</a>
  </nav>

  <!-- Bouton menu mobile -->
  <button class="mobile-menu-btn" id="mobileMenuBtn" aria-label="Ouvrir le menu" aria-expanded="false" aria-controls="mobileOverlay">
    <span></span><span></span><span></span>
  </button>

</header>
