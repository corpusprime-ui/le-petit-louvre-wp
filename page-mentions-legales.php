<?php
/*
Template Name: Mentions Légales
*/
get_header();
$tpl = esc_url( get_template_directory_uri() );
?>

<!-- ==========================================
     HERO MENTIONS LÉGALES
========================================== -->
<section class="hero" id="hero" aria-label="Mentions légales — Le Petit Louvre">

  <img class="hero-bg active"
       src="<?php echo $tpl; ?>/img/interieur-1-opt.jpg"
       alt="Salle du restaurant Le Petit Louvre, Arcachon"
       fetchpriority="high" decoding="async">

  <div class="hero-overlay-top" aria-hidden="true"></div>
  <div class="hero-overlay-mid" aria-hidden="true"></div>

  <?php get_template_part( 'template-parts/site-header' ); ?>

  <div class="hero-content">
    <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="hero-logo-mobile" aria-label="Accueil Le Petit Louvre">
      <img loading="lazy" src="<?php echo $tpl; ?>/img/logo.svg" alt="Le Petit Louvre" width="100" height="100">
    </a>
    <p class="hero-label">Informations légales</p>
    <h1 class="hero-title" style="font-size:clamp(28px,4vw,52px);">Mentions légales</h1>
    <p class="hero-tagline">Restaurant · 14 Place Lucien de Gracia · 33120 Arcachon</p>
  </div>

</section>

<!-- ==========================================
     CONTENU MENTIONS LÉGALES
========================================== -->
<main class="mentions-page" id="mentions-content">
  <div class="container" style="max-width:860px; padding: 80px 24px;">

    <div class="mentions-bloc reveal">
      <h2>Éditeur du site</h2>
      <p>
        <strong>Le Petit Louvre</strong><br>
        14 Place Lucien de Gracia<br>
        33120 Arcachon, France<br>
        Tél. : <a href="tel:+33557157359">05 57 15 73 59</a><br>
        Email : <a href="mailto:contact@lepetitlouvre.fr">contact@lepetitlouvre.fr</a>
      </p>
    </div>

    <div class="mentions-bloc reveal d1">
      <h2>Directeur de la publication</h2>
      <p>Le directeur de la publication est le gérant de l'établissement Le Petit Louvre.</p>
    </div>

    <div class="mentions-bloc reveal d2">
      <h2>Hébergement</h2>
      <p>
        Ce site est hébergé par :<br>
        <strong>IONOS SE</strong><br>
        Elgendorfer Str. 57, 56410 Montabaur, Allemagne<br>
        <a href="https://www.ionos.fr" target="_blank" rel="noopener noreferrer">www.ionos.fr</a>
      </p>
    </div>

    <div class="mentions-bloc reveal d2">
      <h2>Conception &amp; réalisation</h2>
      <p>
        Site conçu et réalisé par :<br>
        <strong>Corpus Prime</strong><br>
        <a href="mailto:corpus.prime@gmail.com">corpus.prime@gmail.com</a>
      </p>
    </div>

    <div class="mentions-bloc reveal d3">
      <h2>Propriété intellectuelle</h2>
      <p>
        L'ensemble du contenu de ce site (textes, photographies, illustrations, logos, icônes) est la propriété exclusive de Le Petit Louvre ou de ses partenaires et est protégé par les lois françaises et internationales relatives à la propriété intellectuelle.
      </p>
      <p>
        Toute reproduction, représentation, diffusion ou rediffusion, en tout ou partie, du contenu de ce site sur quelque support ou par tout procédé que ce soit, ainsi que toute vente, revente, retransmission ou mise à disposition de tiers de quelque manière que ce soit, sont interdites sans l'accord préalable écrit de Le Petit Louvre.
      </p>
    </div>

    <div class="mentions-bloc reveal d3">
      <h2>Données personnelles</h2>
      <p>
        Conformément au Règlement Général sur la Protection des Données (RGPD) et à la loi Informatique et Libertés du 6 janvier 1978 modifiée, vous disposez d'un droit d'accès, de rectification, d'effacement et de portabilité des données vous concernant.
      </p>
      <p>
        Pour exercer ces droits ou pour toute question relative au traitement de vos données personnelles, vous pouvez contacter Le Petit Louvre par email à <a href="mailto:contact@lepetitlouvre.fr">contact@lepetitlouvre.fr</a> ou par courrier à l'adresse indiquée ci-dessus.
      </p>
      <p>
        Les informations recueillies via le formulaire de contact sont uniquement utilisées pour répondre à vos demandes et ne sont pas transmises à des tiers.
      </p>
    </div>

    <div class="mentions-bloc reveal d4">
      <h2>Cookies</h2>
      <p>
        Ce site peut utiliser des cookies techniques nécessaires à son bon fonctionnement. Ces cookies ne collectent aucune donnée personnelle et ne nécessitent pas votre consentement préalable.
      </p>
      <p>
        Vous pouvez à tout moment désactiver les cookies dans les paramètres de votre navigateur. Cela peut toutefois affecter certaines fonctionnalités du site.
      </p>
    </div>

    <div class="mentions-bloc reveal d4">
      <h2>Liens hypertextes</h2>
      <p>
        Le Petit Louvre décline toute responsabilité quant au contenu des sites accessibles via des liens hypertextes présents sur ce site. La mise en place de liens vers ce site est soumise à l'accord préalable de l'éditeur.
      </p>
    </div>

    <div class="mentions-bloc reveal d4">
      <h2>Droit applicable</h2>
      <p>
        Le présent site et ses mentions légales sont soumis au droit français. En cas de litige, les tribunaux français seront seuls compétents.
      </p>
    </div>

  </div>
</main>

<?php get_footer(); ?>
