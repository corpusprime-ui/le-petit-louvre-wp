<?php
/*
Template Name: Politique de Confidentialité
*/
get_header();
$tpl = esc_url( get_template_directory_uri() );
?>

<!-- ==========================================
     HERO POLITIQUE DE CONFIDENTIALITÉ
========================================== -->
<section class="hero" id="hero" aria-label="Politique de confidentialité — Le Petit Louvre">

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
    <p class="hero-label">Vos données, notre responsabilité</p>
    <h1 class="hero-title" style="font-size:clamp(24px,3.8vw,48px);">Politique de confidentialité</h1>
    <p class="hero-tagline">Restaurant · 14 Place Lucien de Gracia · 33120 Arcachon</p>
  </div>

</section>

<!-- ==========================================
     CONTENU POLITIQUE DE CONFIDENTIALITÉ
========================================== -->
<main class="mentions-page" id="politique-content">
  <div class="container" style="max-width:860px; padding: 80px 24px;">

    <div class="mentions-bloc reveal">
      <h2>Responsable du traitement</h2>
      <p>
        <strong>Le Petit Louvre</strong><br>
        14 Place Lucien de Gracia<br>
        33120 Arcachon, France<br>
        Tél. : <a href="tel:+33557157359">05 57 15 73 59</a><br>
        Email : <a href="mailto:contact@lepetitlouvre.fr">contact@lepetitlouvre.fr</a>
      </p>
      <p>
        Le Petit Louvre s'engage à protéger la vie privée de ses clients et visiteurs, conformément au Règlement Général sur la Protection des Données (RGPD – UE 2016/679) et à la loi Informatique et Libertés du 6 janvier 1978 modifiée.
      </p>
    </div>

    <div class="mentions-bloc reveal d1">
      <h2>Données collectées et finalités</h2>

      <p><strong>Formulaire de contact</strong></p>
      <p>
        Lorsque vous utilisez notre formulaire de contact, nous collectons : votre nom, votre adresse email, votre numéro de téléphone (facultatif) et le contenu de votre message.
        Ces données sont utilisées exclusivement pour répondre à votre demande. Base légale : intérêt légitime (traitement de votre demande).
      </p>

      <p><strong>Réservation en ligne — Zenchef</strong></p>
      <p>
        Les réservations en ligne sont gérées par notre prestataire <strong>Zenchef</strong> (bookings.zenchef.com). Lors d'une réservation, Zenchef collecte vos nom, prénom, email, numéro de téléphone, date, heure et nombre de couverts. Ces données sont nécessaires à la gestion de votre réservation. Base légale : exécution d'un contrat.<br>
        Pour consulter la politique de confidentialité de Zenchef :
        <a href="https://www.zenchef.com/politique-de-confidentialite" target="_blank" rel="noopener noreferrer">www.zenchef.com/politique-de-confidentialite</a>
      </p>

      <p><strong>Navigation sur le site</strong></p>
      <p>
        Des données techniques de navigation (adresse IP, type de navigateur, pages visitées, durée de visite) peuvent être collectées automatiquement à des fins de statistiques et de sécurité. Ces données ne permettent pas de vous identifier personnellement.
      </p>
    </div>

    <div class="mentions-bloc reveal d2">
      <h2>Durée de conservation</h2>
      <p>
        Les données issues du formulaire de contact sont conservées pendant une durée maximale de <strong>3 ans</strong> à compter de votre dernier contact, puis sont supprimées ou anonymisées.
      </p>
      <p>
        Les données de réservation sont conservées conformément à la politique de Zenchef et aux obligations légales applicables à la restauration (3 ans pour les données de facturation).
      </p>
    </div>

    <div class="mentions-bloc reveal d2">
      <h2>Destinataires des données</h2>
      <p>
        Vos données personnelles sont traitées uniquement par l'équipe du Petit Louvre pour répondre à vos demandes. Elles ne sont jamais vendues ni cédées à des tiers à des fins commerciales.
      </p>
      <p>
        Nos sous-traitants techniques (hébergeur IONOS, prestataire de réservation Zenchef) peuvent avoir accès à certaines données dans le strict cadre de leurs missions et sont soumis à des obligations de confidentialité conformes au RGPD.
      </p>
    </div>

    <div class="mentions-bloc reveal d3">
      <h2>Vos droits</h2>
      <p>
        Conformément au RGPD, vous disposez des droits suivants sur vos données personnelles :
      </p>
      <p>
        <strong>Droit d'accès</strong> — obtenir une copie des données vous concernant.<br>
        <strong>Droit de rectification</strong> — corriger des données inexactes ou incomplètes.<br>
        <strong>Droit à l'effacement</strong> — demander la suppression de vos données (« droit à l'oubli »).<br>
        <strong>Droit à la portabilité</strong> — recevoir vos données dans un format structuré et lisible.<br>
        <strong>Droit d'opposition</strong> — vous opposer au traitement de vos données pour motif légitime.<br>
        <strong>Droit à la limitation</strong> — demander la suspension temporaire du traitement.
      </p>
      <p>
        Pour exercer l'un de ces droits, contactez-nous par email à <a href="mailto:contact@lepetitlouvre.fr">contact@lepetitlouvre.fr</a> ou par courrier à l'adresse ci-dessus. Nous répondrons dans un délai maximal d'un mois.
      </p>
      <p>
        Si vous estimez que vos droits ne sont pas respectés, vous pouvez adresser une réclamation à la <strong>CNIL</strong> : <a href="https://www.cnil.fr" target="_blank" rel="noopener noreferrer">www.cnil.fr</a>
      </p>
    </div>

    <div class="mentions-bloc reveal d3">
      <h2>Cookies</h2>
      <p>
        Ce site utilise uniquement des <strong>cookies techniques</strong> nécessaires à son bon fonctionnement (session, sécurité, préférences de navigation). Ces cookies ne collectent aucune donnée personnelle à des fins publicitaires ou de traçage et ne nécessitent pas votre consentement préalable (article 82 de la loi Informatique et Libertés).
      </p>
      <p>
        Aucun cookie tiers à des fins de publicité ciblée n'est déposé sur ce site.
      </p>
      <p>
        Vous pouvez à tout moment désactiver les cookies dans les paramètres de votre navigateur. Cela peut toutefois affecter certaines fonctionnalités du site.
      </p>
    </div>

    <div class="mentions-bloc reveal d4">
      <h2>Sécurité des données</h2>
      <p>
        Le Petit Louvre met en œuvre les mesures techniques et organisationnelles appropriées pour protéger vos données contre tout accès non autorisé, perte, altération ou divulgation : connexion sécurisée HTTPS (certificat SSL), hébergement sur serveurs sécurisés IONOS, accès restreint aux données.
      </p>
    </div>

    <div class="mentions-bloc reveal d4">
      <h2>Modification de la politique de confidentialité</h2>
      <p>
        Cette politique de confidentialité peut être mise à jour à tout moment, notamment pour se conformer à l'évolution de la réglementation. La date de dernière mise à jour est indiquée ci-dessous. Nous vous invitons à consulter régulièrement cette page.
      </p>
      <p style="font-size:14px; color: var(--text-muted);">
        Dernière mise à jour : <?php echo date( 'F Y', mktime( 0, 0, 0, 5, 1, 2026 ) ); ?>
      </p>
    </div>

  </div>
</main>

<?php get_footer(); ?>
