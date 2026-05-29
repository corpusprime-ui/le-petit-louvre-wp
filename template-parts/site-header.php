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

  <!-- Numéro de téléphone — visible sur desktop -->
  <a href="<?php echo esc_attr( $h_telephone_href ); ?>" class="nav-phone-abs" aria-label="Appeler le restaurant : <?php echo esc_attr( $h_telephone ); ?>">
    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor"
         stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">
      <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 22 16.92z"/>
    </svg>
    <span><?php echo esc_html( str_replace( ' ', "\u{00A0}", $h_telephone ) ); ?></span>
  </a>

  <!-- Bouton menu mobile -->
  <button class="mobile-menu-btn" id="mobileMenuBtn" aria-label="Ouvrir le menu" aria-expanded="false" aria-controls="mobileOverlay">
    <span></span><span></span><span></span>
  </button>

</header>
