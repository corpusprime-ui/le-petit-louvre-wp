<?php
/* ── Options du thème ── */
$f_telephone      = get_field( 'opt_telephone', 'option' ) ?: '05 57 15 73 59';
$f_telephone_href = 'tel:+33' . ltrim( preg_replace( '/[^0-9]/', '', $f_telephone ), '0' );
$f_email          = get_field( 'opt_email',      'option' ) ?: 'contact@lepetitlouvre.fr';
$f_adresse        = get_field( 'opt_adresse',    'option' ) ?: "14 Pl. Lucien de Gracia,\n33120 Arcachon";
$f_maps_link      = get_field( 'opt_maps_link',  'option' ) ?: 'https://www.google.com/maps/dir//14+Pl.+Lucien+de+Gracia,+33120+Arcachon';

$f_facebook     = get_field( 'opt_social_facebook',    'option' ) ?: '';
$f_instagram    = get_field( 'opt_social_instagram',   'option' ) ?: '';
$f_tiktok       = get_field( 'opt_social_tiktok',      'option' ) ?: '';
$f_tripadvisor  = get_field( 'opt_social_tripadvisor', 'option' ) ?: '';

$f_footer_desc      = get_field( 'opt_footer_desc',      'option' ) ?: 'Institution emblématique d\'Arcachon, Le Petit Louvre se réinvente avec une cuisine française fusion moderne.';
$f_footer_horaires  = get_field( 'opt_footer_horaires',  'option' ) ?: "Le Petit Louvre vous accueille\n\nDu lundi au dimanche\nde 9h à 23h";
$f_footer_copyright = get_field( 'opt_footer_copyright', 'option' ) ?: 'Le Petit Louvre restaurant';
$f_float_label      = get_field( 'opt_float_label',      'option' ) ?: 'Réserver une table';
?>

<!-- ==========================================
     FOOTER
========================================== -->
<footer class="footer" role="contentinfo">
  <div class="container" style="max-width:1200px;">
    <div class="row g-4 justify-content-center mb-5">

      <div class="col-md-3">
        <div class="footer-col reveal d1">
          <img src="<?php echo esc_url( get_template_directory_uri() ); ?>/img/logo.svg"
               alt="Le Petit Louvre" class="footer-logo-img" style="width:110px;height:auto;object-fit:contain;" loading="lazy">
          <p class="footer-desc"><?php echo esc_html( $f_footer_desc ); ?></p>
        </div>
      </div>

      <div class="col-md-3">
        <div class="footer-col reveal d2">
          <p class="footer-heading">Liens rapides</p>
          <ul class="footer-links">
            <li><a href="<?php echo esc_url( home_url( '/' ) ); ?>">Accueil</a></li>
            <li><a href="<?php echo esc_url( home_url( '/reservation/' ) ); ?>">Réservation</a></li>
            <li><a href="<?php echo esc_url( home_url( '/carte/' ) ); ?>">La carte</a></li>
            <li><a href="<?php echo esc_url( home_url( '/carte-des-vins/' ) ); ?>">Carte des Vins</a></li>
            <li><a href="<?php echo esc_url( home_url( '/#privatisation' ) ); ?>">Privatisation</a></li>
            <li><a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>">Contactez-nous</a></li>
          </ul>
        </div>
      </div>

      <div class="col-md-3">
        <div class="footer-col reveal d3">
          <p class="footer-heading">Horaires</p>
          <p class="footer-text">
            <?php echo nl2br( esc_html( $f_footer_horaires ) ); ?>
          </p>
        </div>
      </div>

      <div class="col-md-3">
        <div class="footer-col reveal d4" id="contact">
          <p class="footer-heading">Contact</p>
          <p class="footer-text">
            Restaurant Le Petit Louvre<br>
            <?php echo nl2br( esc_html( $f_adresse ) ); ?><br><br>
            <a href="<?php echo esc_attr( $f_telephone_href ); ?>" style="color:white;text-decoration:none;">Tél. <?php echo esc_html( $f_telephone ); ?></a><br>
            <a href="mailto:<?php echo esc_attr( $f_email ); ?>" style="color:white;text-decoration:none;"><?php echo esc_html( $f_email ); ?></a>
          </p>
          <div class="footer-social">

            <?php if ( $f_facebook ) : ?>
            <a href="<?php echo esc_url( $f_facebook ); ?>" class="social-icon" aria-label="Facebook" target="_blank" rel="noopener noreferrer">
              <svg width="15" height="15" viewBox="0 0 24 24" fill="currentColor">
                <path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"/>
              </svg>
            </a>
            <?php endif; ?>

            <?php if ( $f_instagram ) : ?>
            <a href="<?php echo esc_url( $f_instagram ); ?>" class="social-icon" aria-label="Instagram" target="_blank" rel="noopener noreferrer">
              <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <rect x="2" y="2" width="20" height="20" rx="5" ry="5"/>
                <path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"/>
                <line x1="17.5" y1="6.5" x2="17.51" y2="6.5" stroke-width="2.5"/>
              </svg>
            </a>
            <?php endif; ?>

            <?php if ( $f_tiktok ) : ?>
            <a href="<?php echo esc_url( $f_tiktok ); ?>" class="social-icon" aria-label="TikTok" target="_blank" rel="noopener noreferrer">
              <svg width="15" height="15" viewBox="0 0 24 24" fill="currentColor">
                <path d="M19.59 6.69a4.83 4.83 0 0 1-3.77-4.25V2h-3.45v13.67a2.89 2.89 0 0 1-2.88 2.5 2.89 2.89 0 0 1-2.89-2.89 2.89 2.89 0 0 1 2.89-2.89c.28 0 .54.04.79.1V9.01a6.27 6.27 0 0 0-.79-.05 6.34 6.34 0 0 0-6.34 6.34 6.34 6.34 0 0 0 6.34 6.34 6.34 6.34 0 0 0 6.33-6.34V8.69a8.26 8.26 0 0 0 4.83 1.54V6.78a4.85 4.85 0 0 1-1.06-.09z"/>
              </svg>
            </a>
            <?php endif; ?>

            <?php if ( $f_tripadvisor ) : ?>
            <a href="<?php echo esc_url( $f_tripadvisor ); ?>" class="social-icon" aria-label="TripAdvisor" target="_blank" rel="noopener noreferrer">
              <svg width="15" height="15" viewBox="0 0 24 24" fill="currentColor">
                <path d="M12 2C6.48 2 2 6.03 2 9.5c0 .88.32 1.7.9 2.39L2 13h3.09A6.48 6.48 0 0 0 9 17.87V20h6v-2.13A6.48 6.48 0 0 0 18.91 13H22l-.9-1.11c.58-.69.9-1.51.9-2.39C22 6.03 17.52 2 12 2zM8.5 14a2 2 0 1 1 0-4 2 2 0 0 1 0 4zm7 0a2 2 0 1 1 0-4 2 2 0 0 1 0 4zM6 9.5C6 8.12 8.69 7 12 7s6 1.12 6 2.5c0 .28-.1.56-.82A6.46 6.46 0 0 0 15.5 10a6.5 6.5 0 0 0-7 0A2.05 2.05 0 0 1 6 9.5z"/>
                <circle cx="8.5" cy="12" r="1"/>
                <circle cx="15.5" cy="12" r="1"/>
              </svg>
            </a>
            <?php endif; ?>

          </div>
          <a href="<?php echo esc_url( $f_maps_link ); ?>"
             class="footer-map-btn" target="_blank" rel="noopener noreferrer">
            Plan d'accès
          </a>
        </div>
      </div>

    </div>
  </div>

  <div class="footer-divider"></div>

  <div class="container" style="max-width:1200px;">
    <div class="footer-bottom">
      <p class="footer-bottom-copy"><?php echo esc_html( $f_footer_copyright ); ?> © <?php echo date( 'Y' ); ?> — Tous droits réservés</p>
      <p class="footer-bottom-links">
        <a href="<?php echo esc_url( home_url( '/mentions-legales/' ) ); ?>">Mentions légales</a>
        <span class="footer-sep">·</span>
        <a href="<?php echo esc_url( home_url( '/politique-de-confidentialite/' ) ); ?>">Politique de confidentialité</a>
      </p>
      <p class="footer-bottom-credit">Site réalisé par <a href="mailto:corpus.prime@gmail.com">Corpus Prime</a></p>
    </div>
  </div>
</footer>

<!-- SCROLL TO TOP -->
<a href="#" class="scroll-top" id="scrollTop" aria-label="Retour en haut">
  <img src="<?php echo esc_url( get_template_directory_uri() ); ?>/img/scroll-top-icon.svg" alt="" loading="lazy" decoding="async">
</a>

<!-- BOUTON FLOTTANT RÉSERVATION — masqué via CSS sur la page réservation -->
<a href="<?php echo esc_url( home_url( '/reservation/#resa-form' ) ); ?>" class="float-reserve" id="floatReserve">
  <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
    <path d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07A19.5 19.5 0 013.07 9.8a19.79 19.79 0 01-3.07-8.68A2 2 0 012 0h3a2 2 0 012 1.72c.127.96.361 1.903.7 2.81a2 2 0 01-.45 2.11L6.09 7.91a16 16 0 006 6l1.27-1.27a2 2 0 012.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0122 16.92z"/>
  </svg>
  Je réserve ma table
</a>

<!-- MOBILE NAV OVERLAY -->
<div class="mobile-nav-overlay" id="mobileNavOverlay" role="dialog" aria-modal="true" aria-label="Menu mobile" aria-hidden="true">
  <button class="overlay-close" id="overlayCloseBtn" aria-label="Fermer le menu">✕</button>
  <a href="<?php echo esc_url( home_url( '/' ) ); ?>" aria-label="Accueil Le Petit Louvre">
    <img class="overlay-logo"
         src="<?php echo esc_url( get_template_directory_uri() ); ?>/img/logo.svg"
         width="110" height="106"
         alt="Le Petit Louvre">
  </a>
  <div class="overlay-sep"></div>
  <nav class="overlay-nav" aria-label="Navigation mobile">
    <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="overlay-link">Accueil</a>
    <a href="<?php echo esc_url( home_url( '/#presentation' ) ); ?>" class="overlay-link">Déjeuner / Dîner</a>
    <a href="<?php echo esc_url( home_url( '/carte-des-vins/' ) ); ?>" class="overlay-link">Le bar</a>
    <a href="<?php echo esc_url( home_url( '/reservation/' ) ); ?>" class="overlay-link">Réservation</a>
    <a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>" class="overlay-link">Contact</a>
    <a href="<?php echo esc_url( home_url( '/#privatisation' ) ); ?>" class="overlay-link">Privatisation</a>
  </nav>
  <div class="overlay-sep"></div>
  <a href="<?php echo esc_attr( $f_telephone_href ); ?>" class="overlay-phone-cartouche" aria-label="Appeler le restaurant">
    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor"
         stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
      <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 22 16.92z"/>
    </svg>
    <span><?php echo esc_html( str_replace( ' ', "\u{00A0}", $f_telephone ) ); ?></span>
  </a>
</div>

<?php wp_footer(); ?>
</body>
</html>
