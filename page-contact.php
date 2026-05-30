<?php
/*
 * Template Name: Contact
 */

/* ══════════════════════════════════════════════════════════════
   FORMULAIRE DE CONTACT — Traitement sécurisé
   Sécurité : nonce · honeypot · rate-limit · sanitize · validate
   Envoi    : wp_mail → contact@lepetitlouvre.fr + accusé client
══════════════════════════════════════════════════════════════ */
$cf = [ 'success' => false, 'errors' => [], 'data' => [] ];

if ( isset( $_POST['lpl_cf_submit'] ) ) {

    /* ① Nonce */
    if ( ! wp_verify_nonce(
            sanitize_text_field( wp_unslash( $_POST['_lpl_cf_nonce'] ?? '' ) ),
            'lpl_contact_form'
         ) ) {
        $cf['errors']['global'] = 'Erreur de sécurité. Merci de recharger la page.';

    /* ② Honeypot — champ doit rester vide */
    } elseif ( ! empty( $_POST['lpl_cf_pot'] ) ) {
        $cf['success'] = true; /* bot → succès silencieux */

    } else {

        /* ③ Rate limit : max 5 soumissions / IP / heure */
        $raw_ip = $_SERVER['HTTP_X_FORWARDED_FOR'] ?? $_SERVER['REMOTE_ADDR'] ?? '0';
        $ip     = sanitize_text_field( explode( ',', $raw_ip )[0] );
        $tkey   = 'lpl_cf_' . md5( $ip );
        $hits   = (int) get_transient( $tkey );

        if ( $hits >= 5 ) {
            $cf['errors']['global'] = 'Trop de tentatives. Merci de réessayer dans une heure ou d\'appeler le restaurant.';

        } else {

            /* ④ Sanitize */
            $nom     = sanitize_text_field(  wp_unslash( $_POST['nom']       ?? '' ) );
            $prenom  = sanitize_text_field(  wp_unslash( $_POST['prenom']    ?? '' ) );
            $email   = sanitize_email(       wp_unslash( $_POST['email']     ?? '' ) );
            $tel     = sanitize_text_field(  wp_unslash( $_POST['telephone'] ?? '' ) );
            $message = sanitize_textarea_field( wp_unslash( $_POST['message'] ?? '' ) );
            $rgpd    = ! empty( $_POST['rgpd'] );

            /* ⑤ Validate */
            if ( mb_strlen( $nom )    < 2 )  $cf['errors']['nom']       = 'Nom requis (minimum 2 caractères).';
            if ( mb_strlen( $nom )    > 60 ) $cf['errors']['nom']       = 'Nom trop long (maximum 60 caractères).';
            if ( mb_strlen( $prenom ) < 2 )  $cf['errors']['prenom']    = 'Prénom requis (minimum 2 caractères).';
            if ( mb_strlen( $prenom ) > 60 ) $cf['errors']['prenom']    = 'Prénom trop long (maximum 60 caractères).';
            if ( ! is_email( $email ) )      $cf['errors']['email']     = 'Adresse email invalide.';
            if ( ! empty( $tel ) ) {
                $tel_n = preg_replace( '/[\s.\-()+]/', '', $tel );
                if ( ! preg_match( '/^[0-9]{8,15}$/', $tel_n ) )
                    $cf['errors']['telephone'] = 'Numéro de téléphone invalide.';
            }
            if ( mb_strlen( $message ) < 10 )  $cf['errors']['message'] = 'Message trop court (minimum 10 caractères).';
            if ( mb_strlen( $message ) > 255 )  $cf['errors']['message'] = 'Message trop long (maximum 255 caractères).';
            if ( ! $rgpd )                      $cf['errors']['rgpd']    = 'Vous devez accepter les conditions pour envoyer votre message.';

            if ( empty( $cf['errors'] ) ) {

                /* ⑥ Incrément rate limit */
                set_transient( $tkey, $hits + 1, HOUR_IN_SECONDS );

                /* ⑦ Email au restaurant */
                wp_mail(
                    'contact@lepetitlouvre.fr',
                    "[Contact LPL] {$prenom} {$nom}",
                    lpl_cf_email_resto( $prenom, $nom, $email, $tel, $message ),
                    [
                        'Content-Type: text/html; charset=UTF-8',
                        "Reply-To: {$prenom} {$nom} <{$email}>",
                    ]
                );

                /* ⑧ Accusé de réception au client */
                wp_mail(
                    $email,
                    'Votre message — Le Petit Louvre',
                    lpl_cf_email_client( $prenom ),
                    [
                        'Content-Type: text/html; charset=UTF-8',
                        'From: Le Petit Louvre <contact@lepetitlouvre.fr>',
                    ]
                );

                $cf['success'] = true;
                $cf['data']    = compact( 'prenom', 'nom', 'email' );

            } else {
                /* Mémorise les valeurs pour re-remplir le formulaire */
                $cf['data'] = compact( 'nom', 'prenom', 'email', 'tel', 'message' );
            }
        }
    }
}

/* ──────────────────────────────────────────────────────────────
   EMAIL — Notification restaurant
────────────────────────────────────────────────────────────── */
function lpl_cf_email_resto( string $prenom, string $nom, string $email, string $tel, string $msg ): string {
    ob_start(); ?>
<!DOCTYPE html>
<html lang="fr">
<head><meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1"></head>
<body style="margin:0;padding:0;background:#f5f0e8;font-family:'Helvetica Neue',Arial,sans-serif;">
<table width="100%" cellpadding="0" cellspacing="0" style="background:#f5f0e8;padding:32px 16px;">
  <tr><td align="center">
  <table width="600" cellpadding="0" cellspacing="0" style="background:#ffffff;border-radius:12px;overflow:hidden;max-width:600px;">

    <tr><td style="background:#1a2e0a;padding:30px 32px;text-align:center;">
      <p style="margin:0;font-size:10px;letter-spacing:4px;text-transform:uppercase;color:rgba(255,255,255,.45);">Le Petit Louvre · Arcachon</p>
      <h1 style="margin:10px 0 0;font-size:20px;font-weight:700;color:#ffffff;letter-spacing:.5px;">Nouveau message de contact</h1>
    </td></tr>

    <tr><td style="padding:32px;">
      <table width="100%" cellpadding="0" cellspacing="0" style="background:#faf8f4;border-radius:8px;border:1px solid #e8e2d6;overflow:hidden;">
        <tr><td style="padding:16px 22px;border-bottom:1px solid #e8e2d6;">
          <p style="margin:0 0 3px;font-size:10px;font-weight:700;letter-spacing:3px;text-transform:uppercase;color:#33520b;">De</p>
          <p style="margin:0;font-size:17px;font-weight:700;color:#1a2e0a;"><?php echo esc_html( $prenom . ' ' . $nom ); ?></p>
        </td></tr>
        <tr><td style="padding:14px 22px;border-bottom:1px solid #e8e2d6;">
          <p style="margin:0 0 3px;font-size:10px;font-weight:700;letter-spacing:3px;text-transform:uppercase;color:#33520b;">Email</p>
          <a href="mailto:<?php echo esc_attr( $email ); ?>" style="font-size:14px;color:#33520b;text-decoration:none;"><?php echo esc_html( $email ); ?></a>
        </td></tr>
        <?php if ( $tel ) : ?>
        <tr><td style="padding:14px 22px;border-bottom:1px solid #e8e2d6;">
          <p style="margin:0 0 3px;font-size:10px;font-weight:700;letter-spacing:3px;text-transform:uppercase;color:#33520b;">Téléphone</p>
          <a href="tel:<?php echo esc_attr( preg_replace( '/\s/', '', $tel ) ); ?>" style="font-size:14px;color:#33520b;text-decoration:none;"><?php echo esc_html( $tel ); ?></a>
        </td></tr>
        <?php endif; ?>
        <tr><td style="padding:16px 22px;">
          <p style="margin:0 0 10px;font-size:10px;font-weight:700;letter-spacing:3px;text-transform:uppercase;color:#33520b;">Message</p>
          <p style="margin:0;font-size:14px;color:#444;line-height:1.7;"><?php echo nl2br( esc_html( $msg ) ); ?></p>
        </td></tr>
      </table>
      <div style="margin-top:24px;">
        <a href="mailto:<?php echo esc_attr( $email ); ?>?subject=Re%3A+votre+message+Le+Petit+Louvre"
           style="display:inline-block;background:#33520b;color:#ffffff;text-decoration:none;padding:12px 28px;border-radius:6px;font-size:13px;font-weight:700;letter-spacing:1px;text-transform:uppercase;">
          Répondre →
        </a>
      </div>
    </td></tr>

    <tr><td style="background:#faf8f4;padding:18px 32px;border-top:1px solid #e8e2d6;text-align:center;">
      <p style="margin:0;font-size:11px;color:#aaa;letter-spacing:.3px;">Le Petit Louvre · 14 Pl. Lucien de Gracia, 33120 Arcachon · 05 57 15 73 59</p>
    </td></tr>

  </table>
  </td></tr>
</table>
</body>
</html>
    <?php return ob_get_clean();
}

/* ──────────────────────────────────────────────────────────────
   EMAIL — Accusé de réception client
────────────────────────────────────────────────────────────── */
function lpl_cf_email_client( string $prenom ): string {
    ob_start(); ?>
<!DOCTYPE html>
<html lang="fr">
<head><meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1"></head>
<body style="margin:0;padding:0;background:#f5f0e8;font-family:'Helvetica Neue',Arial,sans-serif;">
<table width="100%" cellpadding="0" cellspacing="0" style="background:#f5f0e8;padding:32px 16px;">
  <tr><td align="center">
  <table width="600" cellpadding="0" cellspacing="0" style="background:#ffffff;border-radius:12px;overflow:hidden;max-width:600px;">

    <tr><td style="background:linear-gradient(135deg,#1a2e0a 0%,#2c4a0e 100%);padding:40px 32px;text-align:center;">
      <p style="margin:0 0 6px;font-size:10px;letter-spacing:5px;text-transform:uppercase;color:rgba(255,255,255,.45);">Restaurant · Arcachon</p>
      <h1 style="margin:0;font-size:26px;font-weight:700;color:#ffffff;letter-spacing:3px;text-transform:uppercase;">Le Petit Louvre</h1>
      <p style="margin:8px 0 0;font-size:12px;color:rgba(255,255,255,.45);font-style:italic;">Cuisine Fusion</p>
    </td></tr>

    <tr><td style="padding:44px 32px 32px;text-align:center;">
      <div style="display:inline-flex;align-items:center;justify-content:center;width:56px;height:56px;background:#eef5e4;border-radius:50%;margin-bottom:22px;">
        <span style="font-size:22px;line-height:1;">✓</span>
      </div>
      <h2 style="margin:0 0 12px;font-size:23px;font-weight:700;color:#1a2e0a;line-height:1.2;">Merci <?php echo esc_html( $prenom ); ?>&nbsp;!</h2>
      <p style="margin:0 auto;font-size:15px;color:#666;line-height:1.7;max-width:360px;">
        Votre message a bien été reçu par l'équipe du Petit Louvre.<br>
        Nous vous répondrons <strong style="color:#1a2e0a;">dans les meilleurs délais</strong>.
      </p>
    </td></tr>

    <tr><td style="padding:0 32px 32px;text-align:center;">
      <p style="margin:0 0 22px;font-size:13px;color:#aaa;line-height:1.6;">
        Vous pouvez aussi nous joindre directement :<br>
        <a href="tel:+33557157359" style="color:#33520b;text-decoration:none;font-weight:700;">05 57 15 73 59</a>
        &nbsp;·&nbsp;
        <a href="mailto:contact@lepetitlouvre.fr" style="color:#33520b;text-decoration:none;">contact@lepetitlouvre.fr</a>
      </p>
      <a href="<?php echo esc_url( home_url( '/carte/' ) ); ?>"
         style="display:inline-block;background:#33520b;color:#ffffff;text-decoration:none;padding:13px 30px;border-radius:6px;font-size:13px;font-weight:700;letter-spacing:1px;text-transform:uppercase;">
        Découvrir la carte →
      </a>
    </td></tr>

    <tr><td style="background:#1a2e0a;padding:22px 32px;text-align:center;">
      <p style="margin:0;font-size:11px;color:rgba(255,255,255,.4);line-height:1.6;">14 Pl. Lucien de Gracia · 33120 Arcachon<br>contact@lepetitlouvre.fr · lepetitlouvre.fr</p>
    </td></tr>

  </table>
  </td></tr>
</table>
</body>
</html>
    <?php return ob_get_clean();
}

/* ── Schema.org JSON-LD ── */
add_action( 'wp_head', function () { ?>
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "FoodEstablishment",
  "name": "Le Petit Louvre",
  "url": "<?php echo esc_url( home_url('/') ); ?>",
  "telephone": "+33557157359",
  "email": "contact@lepetitlouvre.fr",
  "address": {
    "@type": "PostalAddress",
    "streetAddress": "14 Place Lucien de Gracia",
    "addressLocality": "Arcachon",
    "postalCode": "33120",
    "addressCountry": "FR"
  }
}
</script>
<?php } );

get_header();

/* ──────────────────────────────────────
   ACF — Page Contact
────────────────────────────────────── */

/* Hero */
$c_hero_label   = get_field( 'contact_hero_label' )   ?: 'Restaurant · Arcachon';
$c_hero_title   = get_field( 'contact_hero_title' )   ?: 'Infos & Contact';
$c_hero_tagline_1 = get_field( 'contact_hero_tagline_1' ) ?: 'Nous sommes à votre écoute';
$c_hero_tagline_2 = get_field( 'contact_hero_tagline_2' ) ?: 'Répondons à toutes vos questions';
$c_hero_images  = get_field( 'contact_hero_images' )  ?: [];

/* Formulaire */
$c_form_label     = get_field( 'contact_form_label' )   ?: 'Nous écrire';
$c_form_title     = get_field( 'contact_form_title' )   ?: 'Vous avez des questions ?';
$c_telephone      = get_field( 'contact_telephone' )    ?: '05 57 15 73 59';
$c_telephone_href = '+33' . ltrim( preg_replace( '/[^0-9]/', '', $c_telephone ), '0' );
$c_photo          = get_field( 'contact_photo' )        ?: null;

/* Infos pratiques */
$c_adresse         = get_field( 'contact_adresse' )          ?: "14 Pl. Lucien de Gracia,\n33120 Arcachon";
$c_email           = get_field( 'contact_email' )            ?: 'contact@lepetitlouvre.fr';
$c_horaires_1      = get_field( 'contact_horaires_1' )       ?: 'Lun – Dim  ·  9h → 23h';
$c_horaires_2      = get_field( 'contact_horaires_2' )       ?: '';
$c_horaires_closed = get_field( 'contact_horaires_closed' )  ?: '';
$c_maps_embed      = get_field( 'contact_maps_embed' )       ?: 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2826.5477020065387!2d-1.1697042235937494!3d44.661378989785936!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0xd54942783b3f3fd%3A0x8d48b1df21b277!2s14%20Pl.%20Lucien%20de%20Gracia%2C%2033120%20Arcachon!5e0!3m2!1sfr!2sfr!4v1716139200000!5m2!1sfr!2sfr';
$c_maps_link       = get_field( 'contact_maps_link' )        ?: 'https://www.google.com/maps/dir//14+Pl.+Lucien+de+Gracia,+33120+Arcachon';

/* Recrutement */
/* ── Moyens de paiement & services ── */
$paiements_all = [
    'amex'        => 'American Express',
    'mastercard'  => 'Mastercard',
    'visa'        => 'Visa',
    'carte_debit' => 'Carte de débit',
    'cheques_vac' => 'Chèques Vacances',
    'cash'        => 'Cash',
];
$services_all = [
    'pmr'      => [ 'label' => 'Accès PMR',     'icon' => '<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75"/></svg>' ],
    'terrasse' => [ 'label' => 'Terrasse',       'icon' => '<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>' ],
    'clim'     => [ 'label' => 'Climatisation', 'icon' => '<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M12 2v4M12 18v4M4.93 4.93l2.83 2.83M16.24 16.24l2.83 2.83M2 12h4M18 12h4M4.93 19.07l2.83-2.83M16.24 7.76l2.83-2.83"/></svg>' ],
    'wifi'     => [ 'label' => 'Wifi',          'icon' => '<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M5 12.55a11 11 0 0 1 14.08 0"/><path d="M1.42 9a16 16 0 0 1 21.16 0"/><path d="M8.53 16.11a6 6 0 0 1 6.95 0"/><circle cx="12" cy="20" r="1" fill="currentColor"/></svg>' ],
];
$c_paiements_checked = get_field( 'contact_moyens_paiement' ) ?: array_keys( $paiements_all );
$c_services_checked  = get_field( 'contact_services' )        ?: [ 'pmr', 'terrasse', 'clim' ];

$c_recrutement_actif       = get_field( 'contact_recrutement_actif' )       ?? true;
$c_recrutement_label       = get_field( 'contact_recrutement_label' )       ?: 'Rejoindre l\'équipe';
$c_recrutement_titre       = get_field( 'contact_recrutement_titre' )       ?: 'Nous recrutons pour la saison';
$c_recrutement_texte       = get_field( 'contact_recrutement_texte' )       ?: "Serveur·se, commis de salle, barman·aid — vous aimez l'accueil et le sud-ouest ?\nRejoignez une équipe passionnée dans un restaurant emblématique d'Arcachon.";
$c_recrutement_image       = get_field( 'contact_recrutement_image' )       ?: null;
$c_recrutement_badges      = get_field( 'contact_recrutement_badges' )      ?: [];
$c_recrutement_email_sujet = get_field( 'contact_recrutement_email_sujet' ) ?: 'Candidature saisonnière – Le Petit Louvre';
$c_recrutement_badges_render = $c_recrutement_badges ?: [
    [ 'texte' => 'Saison été 2026' ],
    [ 'texte' => 'Arcachon · 33120' ],
    [ 'texte' => 'CDI / CDD' ],
];
/* Pas de body= : les URL mailto longues déclenchent le popup navigateur
   "Ce site essaie d'ouvrir une application" — le sujet seul suffit */
$c_recrutement_mailto = 'mailto:' . $c_email
    . '?subject=' . rawurlencode( $c_recrutement_email_sujet );
?>


<!-- ==========================================
     HERO
========================================== -->
<section class="hero" id="hero" aria-label="Infos et Contact — Le Petit Louvre">

  <?php
  $valid_hero_imgs = array_filter( (array) $c_hero_images, fn($s) => ! empty( $s['image']['url'] ) );
  if ( $valid_hero_imgs ) :
      $idx = 0;
      foreach ( $valid_hero_imgs as $slide ) :
          $src = $slide['image']['url'];
          $alt = $slide['alt'] ?: ( $slide['image']['alt'] ?? '' );
  ?>
  <img class="hero-bg<?php echo $idx === 0 ? ' active' : ''; ?>"
       src="<?php echo esc_url( $src ); ?>"
       alt="<?php echo esc_attr( $alt ); ?>"
       <?php echo $idx === 0 ? 'fetchpriority="high"' : 'loading="lazy"'; ?> decoding="async" width="1440" height="960">
  <?php $idx++; endforeach; else : ?>
  <img class="hero-bg active"
       src="<?php echo esc_url( get_template_directory_uri() ); ?>/img/interieur.jpg"
       alt="Salle du restaurant Le Petit Louvre"
       fetchpriority="high" decoding="async" width="1440" height="960">
  <img class="hero-bg"
       src="<?php echo esc_url( get_template_directory_uri() ); ?>/img/privatisation.jpg"
       alt="Table dressée au Petit Louvre"
       loading="lazy" decoding="async" width="1440" height="960">
  <?php endif; ?>

  <div class="hero-overlay-top" aria-hidden="true"></div>
  <div class="hero-overlay-mid"  aria-hidden="true"></div>

  <?php get_template_part('template-parts/site-header'); ?>

  <div class="hero-content">
    <p class="hero-label"><?php echo esc_html( $c_hero_label ); ?></p>
    <h1 class="hero-title"><?php echo esc_html( $c_hero_title ); ?></h1>
    <?php if ( $c_hero_tagline_1 || $c_hero_tagline_2 ) : ?>
    <p class="hero-tagline" style="text-align:center;">
      <?php if ( $c_hero_tagline_1 ) : ?><?php echo esc_html( $c_hero_tagline_1 ); ?><?php endif; ?>
      <?php if ( $c_hero_tagline_1 && $c_hero_tagline_2 ) : ?><br><?php endif; ?>
      <?php if ( $c_hero_tagline_2 ) : ?><?php echo esc_html( $c_hero_tagline_2 ); ?><?php endif; ?>
    </p>
    <?php endif; ?>
  </div>

  <button class="hero-scroll-hint" id="heroScrollHint" data-target="contact-main" aria-label="Découvrir la page contact">
    <span>Découvrir</span>
    <div class="scroll-arrow" aria-hidden="true"></div>
  </button>

</section>


<!-- ==========================================
     SECTION PRINCIPALE — Formulaire + Infos
========================================== -->
<section class="contact-main" id="contact-main" aria-labelledby="contact-title">
  <div class="container" style="max-width:1200px;">
    <div class="row g-5 align-items-start">

      <!-- ── COLONNE GAUCHE : Formulaire ── -->
      <div class="col-lg-7">

        <div class="contact-intro reveal d1">
          <p class="section-label"><?php echo esc_html( $c_form_label ); ?></p>
          <h2 class="section-title" id="contact-title"><?php echo esc_html( $c_form_title ); ?></h2>
          <div class="sep-line"></div>
          <p class="contact-subtitle">
            Vous pouvez également nous contacter au&nbsp;
            <a href="tel:<?php echo esc_attr( $c_telephone_href ); ?>" class="contact-tel-inline"><?php echo esc_html( $c_telephone ); ?></a>
          </p>
        </div>

        <!-- ── Ancre nommée — cible du scroll "Relancer le formulaire" ── -->
        <span id="contact-formulaire" style="display:block;height:0;overflow:hidden;" aria-hidden="true"></span>

        <!-- ═══ CARTOUCHE DE CONFIRMATION ═══ -->
        <div id="cfCartouche"
             class="contact-cartouche reveal d2"
             role="status" aria-live="polite" aria-atomic="true"
             <?php echo $cf['success'] ? '' : 'style="display:none;"'; ?>>
          <div class="contact-cartouche-icon" aria-hidden="true">
            <svg class="contact-check-svg" viewBox="0 0 52 52" fill="none" xmlns="http://www.w3.org/2000/svg">
              <circle class="cc-circle" cx="26" cy="26" r="23" stroke="#33520b" stroke-width="1.8" fill="none"/>
              <path   class="cc-check"  d="M14.5 26.5l7.5 8.5 16-19" stroke="#33520b" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" fill="none"/>
            </svg>
          </div>
          <p class="contact-cartouche-label">Message envoyé</p>
          <h3 class="contact-cartouche-title">Merci <?php echo esc_html( $cf['data']['prenom'] ?? '' ); ?>&nbsp;!</h3>
          <p class="contact-cartouche-text">
            Votre message a bien été transmis à l'équipe du Petit Louvre.<br>
            Nous vous répondrons <strong>dans les meilleurs délais</strong>.
          </p>
          <p class="contact-cartouche-email">
            Un accusé de réception a été envoyé à <strong><?php echo esc_html( $cf['data']['email'] ?? '' ); ?></strong>
          </p>
          <div class="contact-cartouche-actions">
            <a href="tel:+33557157359" class="btn btn-outline contact-cartouche-btn">
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 15.1 19.79 19.79 0 0 1 1.62 6.44 2 2 0 0 1 3.6 4.44h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L8.09 11.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
              05 57 15 73 59
            </a>
            <button type="button" id="cfRelancer" class="btn btn-filled contact-cartouche-btn">
              Relancer le formulaire
            </button>
          </div>
        </div>

        <!-- ═══ FORMULAIRE ═══ -->

        <?php if ( ! empty( $cf['errors']['global'] ) ) : ?>
        <div class="contact-feedback contact-feedback--err reveal d2" role="alert">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
          <p><?php echo esc_html( $cf['errors']['global'] ); ?></p>
        </div>
        <?php endif; ?>

        <div id="cfFormWrap" <?php echo $cf['success'] ? 'style="display:none;"' : ''; ?>>
        <form class="contact-form reveal d2"
              action="<?php echo esc_url( get_permalink() . '#contact-formulaire' ); ?>"
              method="post"
              novalidate
              id="contactForm"
              aria-label="Formulaire de contact">

          <?php wp_nonce_field( 'lpl_contact_form', '_lpl_cf_nonce' ); ?>

          <!-- Honeypot anti-bot — doit rester vide -->
          <div style="position:absolute;left:-9999px;top:-9999px;visibility:hidden;pointer-events:none;" aria-hidden="true">
            <label for="lpl_cf_pot">Ne pas remplir ce champ</label>
            <input type="text" id="lpl_cf_pot" name="lpl_cf_pot" tabindex="-1" autocomplete="off" value="">
          </div>

          <div class="row g-3">

            <div class="col-sm-6<?php echo ! empty( $cf['errors']['nom'] ) ? ' cf-has-error' : ''; ?>">
              <label for="cf-nom" class="contact-label">Nom <span class="required" aria-label="obligatoire">*</span></label>
              <input type="text" id="cf-nom" name="nom" class="contact-input"
                     placeholder="Saisissez votre nom"
                     value="<?php echo esc_attr( $cf['data']['nom'] ?? '' ); ?>"
                     required autocomplete="family-name" maxlength="60"
                     <?php echo ! empty( $cf['errors']['nom'] ) ? 'aria-invalid="true"' : ''; ?>>
              <?php if ( ! empty( $cf['errors']['nom'] ) ) : ?>
              <p class="cf-error-msg" role="alert"><?php echo esc_html( $cf['errors']['nom'] ); ?></p>
              <?php endif; ?>
            </div>

            <div class="col-sm-6<?php echo ! empty( $cf['errors']['prenom'] ) ? ' cf-has-error' : ''; ?>">
              <label for="cf-prenom" class="contact-label">Prénom <span class="required" aria-label="obligatoire">*</span></label>
              <input type="text" id="cf-prenom" name="prenom" class="contact-input"
                     placeholder="Saisissez votre prénom"
                     value="<?php echo esc_attr( $cf['data']['prenom'] ?? '' ); ?>"
                     required autocomplete="given-name" maxlength="60"
                     <?php echo ! empty( $cf['errors']['prenom'] ) ? 'aria-invalid="true"' : ''; ?>>
              <?php if ( ! empty( $cf['errors']['prenom'] ) ) : ?>
              <p class="cf-error-msg" role="alert"><?php echo esc_html( $cf['errors']['prenom'] ); ?></p>
              <?php endif; ?>
            </div>

            <div class="col-sm-6<?php echo ! empty( $cf['errors']['email'] ) ? ' cf-has-error' : ''; ?>">
              <label for="cf-email" class="contact-label">Adresse mail <span class="required" aria-label="obligatoire">*</span></label>
              <input type="email" id="cf-email" name="email" class="contact-input"
                     placeholder="Saisissez votre adresse e-mail"
                     value="<?php echo esc_attr( $cf['data']['email'] ?? '' ); ?>"
                     required autocomplete="email" maxlength="120"
                     <?php echo ! empty( $cf['errors']['email'] ) ? 'aria-invalid="true"' : ''; ?>>
              <?php if ( ! empty( $cf['errors']['email'] ) ) : ?>
              <p class="cf-error-msg" role="alert"><?php echo esc_html( $cf['errors']['email'] ); ?></p>
              <?php endif; ?>
            </div>

            <div class="col-sm-6<?php echo ! empty( $cf['errors']['telephone'] ) ? ' cf-has-error' : ''; ?>">
              <label for="cf-tel" class="contact-label">Téléphone</label>
              <input type="tel" id="cf-tel" name="telephone" class="contact-input"
                     placeholder="Saisissez votre numéro de téléphone"
                     value="<?php echo esc_attr( $cf['data']['tel'] ?? '' ); ?>"
                     autocomplete="tel" maxlength="20"
                     <?php echo ! empty( $cf['errors']['telephone'] ) ? 'aria-invalid="true"' : ''; ?>>
              <?php if ( ! empty( $cf['errors']['telephone'] ) ) : ?>
              <p class="cf-error-msg" role="alert"><?php echo esc_html( $cf['errors']['telephone'] ); ?></p>
              <?php endif; ?>
            </div>

            <div class="col-12<?php echo ! empty( $cf['errors']['message'] ) ? ' cf-has-error' : ''; ?>">
              <label for="cf-message" class="contact-label">Votre message <span class="required" aria-label="obligatoire">*</span></label>
              <textarea id="cf-message" name="message" class="contact-input contact-textarea"
                        placeholder="Saisissez votre message"
                        required maxlength="255" rows="6"
                        <?php echo ! empty( $cf['errors']['message'] ) ? 'aria-invalid="true"' : ''; ?>><?php echo esc_textarea( $cf['data']['message'] ?? '' ); ?></textarea>
              <p class="contact-hint" id="cf-msg-count" aria-live="polite">
                <span id="cf-chars">0</span> / 255 caractères
              </p>
              <?php if ( ! empty( $cf['errors']['message'] ) ) : ?>
              <p class="cf-error-msg" role="alert"><?php echo esc_html( $cf['errors']['message'] ); ?></p>
              <?php endif; ?>
            </div>

            <div class="col-12<?php echo ! empty( $cf['errors']['rgpd'] ) ? ' cf-has-error' : ''; ?>">
              <div class="contact-rgpd-check">
                <input type="checkbox" id="cf-rgpd" name="rgpd" class="contact-checkbox" required
                       <?php echo ! empty( $cf['errors']['rgpd'] ) ? 'aria-invalid="true"' : ''; ?>>
                <label for="cf-rgpd" class="contact-rgpd-label">
                  En soumettant ce formulaire, j'accepte que les informations saisies soient exploitées dans le cadre de la demande de renseignements et de la relation commerciale qui peut en découler.&nbsp;<span class="required">*</span>
                </label>
              </div>
              <?php if ( ! empty( $cf['errors']['rgpd'] ) ) : ?>
              <p class="cf-error-msg" role="alert"><?php echo esc_html( $cf['errors']['rgpd'] ); ?></p>
              <?php endif; ?>
            </div>

            <div class="col-12">
              <button type="submit" name="lpl_cf_submit" class="btn btn-filled btn-lg contact-submit">
                Envoyer le message
              </button>
            </div>

          </div><!-- /.row -->
        </form>

        </div><!-- /#cfFormWrap -->

        <!-- RGPD légal -->
        <div class="contact-legal reveal d3">
          <p>En soumettant ce formulaire, j'accepte que mes données personnelles soient utilisées pour la gestion des relations professionnelles relatives à la clientèle du Petit Louvre et notamment pour me recontacter dans le cadre de la demande indiquée dans ce formulaire. Vos données personnelles ne seront jamais cédées à des tiers.</p>
          <p>Conformément au Règlement Général sur la Protection des Données Personnelles (RGPD) n° (UE) 2016/679 du 27 avril 2016, mis en application le 25 mai 2018, vous disposez d'un droit d'accès et de rectification aux informations qui vous concernent.</p>
        </div>

        <!-- Infos pratiques / paiements -->
        <div class="contact-infos-extra reveal d4">
          <?php if ( ! empty( $c_paiements_checked ) ) : ?>
          <ul class="contact-tags">
            <?php foreach ( $c_paiements_checked as $key ) :
                if ( isset( $paiements_all[ $key ] ) ) : ?>
            <li><?php echo esc_html( $paiements_all[ $key ] ); ?></li>
            <?php endif; endforeach; ?>
          </ul>
          <?php endif; ?>
          <?php if ( ! empty( $c_services_checked ) ) : ?>
          <ul class="contact-tags contact-tags--services">
            <?php foreach ( $c_services_checked as $key ) :
                if ( isset( $services_all[ $key ] ) ) :
                    $svc = $services_all[ $key ]; ?>
            <li>
              <?php echo $svc['icon']; ?>
              <?php echo esc_html( $svc['label'] ); ?>
            </li>
            <?php endif; endforeach; ?>
          </ul>
          <?php endif; ?>
        </div>

      </div><!-- /.col-lg-7 -->


      <!-- ── COLONNE DROITE : Photo + Carte + Infos ── -->
      <div class="col-lg-5">

        <!-- Photo restaurant extérieur -->
        <div class="contact-photo-top reveal-right d1">
          <?php if ( $c_photo && ! empty( $c_photo['url'] ) ) : ?>
          <img src="<?php echo esc_url( $c_photo['url'] ); ?>"
               alt="<?php echo esc_attr( $c_photo['alt'] ?: 'Le restaurant Le Petit Louvre à Arcachon' ); ?>"
               loading="lazy" decoding="async" width="800" height="580">
          <?php else : ?>
          <img src="<?php echo esc_url( get_template_directory_uri() ); ?>/img/terrasse-4.jpeg"
               alt="Le restaurant Le Petit Louvre à Arcachon"
               loading="lazy" decoding="async" width="800" height="580">
          <?php endif; ?>
        </div>

        <!-- Carte Google Maps -->
        <div class="contact-map reveal-right d2">
          <iframe
            title="Localisation du Petit Louvre sur Google Maps"
            src="<?php echo esc_url( $c_maps_embed ); ?>"
            width="100%" height="280" style="border:0;" allowfullscreen loading="lazy"
            referrerpolicy="no-referrer-when-downgrade">
          </iframe>
          <div class="contact-map-footer">
            <p class="contact-map-address"><?php echo nl2br( esc_html( $c_adresse ) ); ?></p>
            <a href="<?php echo esc_url( $c_maps_link ); ?>"
               target="_blank" rel="noopener noreferrer" class="contact-map-link">
              <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
              Itinéraire
            </a>
          </div>
        </div>

        <!-- Infos contact avec icônes -->
        <div class="contact-info-cards reveal-right d3">

          <div class="contact-info-card">
            <div class="contact-info-icon" aria-hidden="true">
              <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
            </div>
            <div class="contact-info-body">
              <p class="contact-info-label">Adresse</p>
              <address class="contact-info-text">
                Restaurant le Petit Louvre<br>
                <?php echo nl2br( esc_html( $c_adresse ) ); ?>
              </address>
            </div>
          </div>

          <div class="contact-info-card">
            <div class="contact-info-icon" aria-hidden="true">
              <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
            </div>
            <div class="contact-info-body">
              <p class="contact-info-label">Téléphone</p>
              <a href="tel:<?php echo esc_attr( $c_telephone_href ); ?>" class="contact-phone-link"><?php echo esc_html( $c_telephone ); ?></a>
            </div>
          </div>

          <div class="contact-info-card">
            <div class="contact-info-icon" aria-hidden="true">
              <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
            </div>
            <div class="contact-info-body">
              <p class="contact-info-label">Horaires</p>
              <p class="contact-info-text"><?php echo esc_html( $c_horaires_1 ); ?></p>
              <?php if ( $c_horaires_2 ) : ?><p class="contact-info-text"><?php echo esc_html( $c_horaires_2 ); ?></p><?php endif; ?>
              <?php if ( $c_horaires_closed ) : ?><p class="contact-info-closed"><?php echo esc_html( $c_horaires_closed ); ?></p><?php endif; ?>
            </div>
          </div>

          <div class="contact-info-card">
            <div class="contact-info-icon" aria-hidden="true">
              <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><rect x="2" y="3" width="20" height="14" rx="2"/><line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/></svg>
            </div>
            <div class="contact-info-body">
              <p class="contact-info-label">Email</p>
              <a href="mailto:<?php echo esc_attr( $c_email ); ?>" class="contact-mail-link"><?php echo esc_html( $c_email ); ?></a>
            </div>
          </div>

          <a href="<?php echo esc_url( $c_maps_link ); ?>"
             target="_blank" rel="noopener noreferrer"
             class="btn btn-outline mt-3 w-100 text-center contact-itineraire">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
            Itinéraire Google Maps
          </a>

        </div><!-- /.contact-info-cards -->

      </div><!-- /.col-lg-5 -->

    </div><!-- /.row -->
  </div><!-- /.container -->
</section>


<!-- ==========================================
     BANNIÈRE RECRUTEMENT
========================================== -->
<?php if ( $c_recrutement_actif ) :
    $banner_img_url = ( $c_recrutement_image && ! empty( $c_recrutement_image['url'] ) )
        ? $c_recrutement_image['url']
        : get_template_directory_uri() . '/img/contact-banner2.jpg';
    $banner_img_alt = $c_recrutement_image['alt'] ?? '';
?>
<section class="contact-banner-section" aria-label="Recrutement saisonnier">
  <div class="contact-banner-photo" aria-hidden="true">
    <img src="<?php echo esc_url( $banner_img_url ); ?>"
         alt="<?php echo esc_attr( $banner_img_alt ); ?>" loading="lazy" decoding="async" width="1440" height="600">
  </div>
  <div class="contact-banner-overlay"></div>
  <div class="container position-relative text-center">
    <p class="contact-banner-label reveal d1"><?php echo esc_html( $c_recrutement_label ); ?></p>
    <h2 class="contact-banner-title reveal d2"><?php echo esc_html( $c_recrutement_titre ); ?></h2>
    <p class="contact-banner-sub reveal d3">
      <?php echo nl2br( esc_html( $c_recrutement_texte ) ); ?>
    </p>
    <div class="contact-banner-badges reveal d4">
      <?php foreach ( $c_recrutement_badges_render as $badge ) : ?>
      <span class="contact-banner-badge">
        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
        <?php echo esc_html( $badge['texte'] ?? '' ); ?>
      </span>
      <?php endforeach; ?>
    </div>
    <a href="<?php echo esc_attr( $c_recrutement_mailto ); ?>"
       class="btn contact-banner-btn reveal d5">
      <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
      Envoyer ma candidature
    </a>
  </div>
</section>
<?php endif; ?>


<style>
/* ====================================================
   PAGE CONTACT — styles spécifiques
==================================================== */

/* ── Intro ── */
.contact-main { padding: 60px 0 72px; background: #faf7f2; }

.contact-intro {
  margin-bottom: 40px;
  text-align: center;
  padding-top: 12px;
}
.contact-intro .section-label { display: block; margin-bottom: 8px; }
.contact-intro .section-title { margin-top: 0; margin-bottom: 24px; }
.contact-intro .sep-line { display: block; margin: 0 auto 24px; }
.contact-subtitle { font-size: 16px; color: #555; margin: 0; }
.contact-tel-inline {
  color: var(--olive-green); font-weight: 600; text-decoration: none;
  transition: color .2s;
}
.contact-tel-inline:hover { color: var(--dark-green); text-decoration: underline; }

/* ── Formulaire ── */
.contact-form {
  margin-bottom: 32px;
  background: #fff;
  border-radius: 16px;
  padding: 36px;
  border: 1px solid #ede8e0;
  box-shadow: 0 4px 24px rgba(120,100,60,.06);
}

.contact-label {
  display: block; font-size: 15px; font-weight: 500;
  color: var(--dark-green); margin-bottom: 8px;
}
.required { color: var(--olive-green); font-weight: 700; }

.contact-input {
  display: block; width: 100%;
  border: 1px solid #c8c8c8; border-radius: 6px;
  padding: 14px 16px; font-size: 14px; color: var(--dark-green);
  background: #fff;
  transition: border-color .2s, box-shadow .2s;
  appearance: none;
}
.contact-input::placeholder { color: #b4b4b4; }
.contact-input:focus {
  outline: none;
  border-color: var(--olive-green);
  box-shadow: 0 0 0 3px rgba(96,105,48,.12);
}

.contact-textarea { resize: vertical; min-height: 160px; }
.contact-hint { font-size: 12px; color: #999; margin-top: 6px; font-style: italic; }

/* ── RGPD checkbox ── */
.contact-rgpd-check { display: flex; align-items: flex-start; gap: 12px; }
.contact-checkbox {
  flex-shrink: 0; width: 20px; height: 20px; margin-top: 2px;
  border: 1px solid #707070; border-radius: 3px;
  accent-color: var(--olive-green); cursor: pointer;
}
.contact-rgpd-label {
  font-size: 14px; color: #444; line-height: 1.55; cursor: pointer;
}

/* ── Messages de retour après soumission ── */
.contact-feedback {
  display: flex; align-items: flex-start; gap: 12px;
  border-radius: 10px; padding: 16px 20px;
  margin-bottom: 24px; font-size: 14px; line-height: 1.6;
}
.contact-feedback p { margin: 0; }
.contact-feedback svg { flex-shrink: 0; margin-top: 2px; }
.contact-feedback--ok {
  background: #edf7ed; border: 1px solid #b7dfb8; color: #1e5e20;
}
.contact-feedback--ok svg { stroke: #2e7d32; }
.contact-feedback--err {
  background: #fdecea; border: 1px solid #f5c2be; color: #b71c1c;
}
.contact-feedback--err svg { stroke: #c62828; }

/* ── Bouton submit ── */
.contact-submit { min-width: 220px; display: block; margin: 0 auto; }

/* ── Texte légal RGPD ── */
.contact-legal {
  font-size: 12px; color: #888; line-height: 1.7;
  background: #f5f0e8;
  border: 1px solid #ede8e0;
  border-radius: 10px;
  padding: 18px 20px; margin-bottom: 20px;
}
.contact-legal p { margin-bottom: 8px; }
.contact-legal p:last-child { margin: 0; }

/* ── Infos extra paiements ── */
.contact-infos-extra {
  background: #fff;
  border: 1px solid #ede8e0;
  border-radius: 12px;
  padding: 20px 22px;
}

/* ── Tags paiements / services ── */
.contact-tags {
  list-style: none; padding: 0; margin: 0 0 12px;
  display: flex; flex-wrap: wrap; gap: 8px;
}
.contact-tags li {
  font-size: 12px; font-weight: 500; color: var(--olive-green);
  background: var(--light-green-bg);
  border: 1px solid rgba(96,105,48,.2);
  border-radius: 50px; padding: 4px 12px;
}
.contact-tags--services li {
  display: flex; align-items: center; gap: 6px;
}
.contact-tags--services svg { stroke: var(--olive-green); }

/* ── Photo top droite ── */
.contact-photo-top {
  border-radius: 12px; overflow: hidden;
  aspect-ratio: 4/3; margin-bottom: 20px;
}
.contact-photo-top img {
  width: 100%; height: 100%; object-fit: cover;
  transition: transform 5s ease;
}
.contact-photo-top:hover img { transform: scale(1.04); }

/* ── Carte Google Maps ── */
.contact-map {
  border-radius: 16px; overflow: hidden;
  border: 1px solid #e8e0d4;
  margin-bottom: 28px;
  background: #faf7f2;
  box-shadow: 0 4px 24px rgba(51,82,11,.07);
}
.contact-map iframe {
  display: block; width: 100%;
  border-radius: 16px 16px 0 0;
  border-bottom: 1px solid #e8e0d4;
}
.contact-map-footer {
  background: #faf7f2;
  padding: 14px 18px;
  display: flex; align-items: center; justify-content: space-between;
  border-radius: 0 0 16px 16px;
}
.contact-map-address {
  font-size: 13px; color: #7a6e5f; line-height: 1.45; margin: 0;
}
.contact-map-link {
  display: inline-flex; align-items: center; gap: 6px;
  font-size: 12px; font-weight: 600; color: var(--olive-green);
  text-decoration: none; white-space: nowrap;
  transition: color .2s;
}
.contact-map-link:hover { color: var(--dark-green); }
.contact-map-link svg { stroke: currentColor; flex-shrink: 0; }

/* ── Cartes info contact ── */
.contact-info-cards {
  display: flex; flex-direction: column; gap: 0;
  border: 1px solid #ede8e0; border-radius: 12px;
  overflow: hidden; background: #fff;
  box-shadow: 0 4px 20px rgba(120,100,60,.06);
}
.contact-info-card {
  display: flex; align-items: flex-start; gap: 16px;
  padding: 18px 20px;
  border-bottom: 1px solid #ede8e0;
}
.contact-info-card:nth-child(odd) { background: #faf7f2; }
.contact-info-card:nth-child(even) { background: #fff; }
.contact-info-card:last-of-type { border-bottom: none; }
.contact-info-icon {
  width: 40px; height: 40px; flex-shrink: 0;
  background: var(--light-green-bg); border-radius: 50%;
  display: flex; align-items: center; justify-content: center;
  margin-top: 2px;
}
.contact-info-icon svg { stroke: var(--olive-green); }
.contact-info-body { display: flex; flex-direction: column; gap: 3px; }
.contact-info-label {
  font-size: 10px; font-weight: 700; letter-spacing: .12em;
  text-transform: uppercase; color: var(--olive-green); margin: 0;
}
.contact-info-text {
  font-size: 14px; color: var(--dark-green); line-height: 1.6;
  margin: 0; font-style: normal;
}
.contact-info-closed { font-size: 13px; color: #aaa; font-style: italic; margin: 0; }
.contact-phone-link {
  font-size: 18px; font-weight: 800; letter-spacing: .04em;
  color: var(--dark-green); text-decoration: none;
  transition: color .2s;
}
.contact-phone-link:hover { color: var(--olive-green); }
.contact-mail-link {
  font-size: 14px; color: #888; text-decoration: none;
  transition: color .2s;
}
.contact-mail-link:hover { color: var(--dark-green); text-decoration: underline; }

.contact-itineraire {
  display: flex; align-items: center; justify-content: center; gap: 8px;
  border-radius: 0 0 12px 12px !important;
  border-top: 1px solid rgba(51,82,11,.1);
}
.contact-itineraire svg { stroke: var(--olive-green); }

/* ── Bannière recrutement ── */
.contact-banner-section {
  position: relative; padding: 110px 24px;
  overflow: hidden; text-align: center;
  background: var(--dark-green);
}
.contact-banner-photo {
  position: absolute; inset: 0; pointer-events: none; overflow: hidden;
}
.contact-banner-photo img {
  width: 100%; height: 160%; /* débordement pour le déplacement parallaxe */
  object-fit: cover; object-position: center center;
  position: absolute; top: -30%; left: 0;
  will-change: transform;
  transform: translateY(0);
}
.contact-banner-overlay {
  position: absolute; inset: 0;
  background: linear-gradient(to bottom, rgba(10,22,2,.68) 0%, rgba(20,38,5,.75) 100%);
  pointer-events: none;
}
.contact-banner-label {
  position: relative;
  font-size: 11px; font-weight: 700; letter-spacing: .22em;
  text-transform: uppercase; color: rgba(255,255,255,.55);
  margin-bottom: 16px;
}
.contact-banner-title {
  position: relative;
  font-family: 'Fraunces', serif; font-weight: 600;
  font-size: clamp(30px,4.5vw,54px); color: #fff;
  line-height: 1.15; margin-bottom: 20px;
}
.contact-banner-sub {
  position: relative;
  font-size: 17px; color: rgba(255,255,255,.72);
  line-height: 1.65; margin-bottom: 32px;
}
.contact-banner-badges {
  position: relative;
  display: flex; flex-wrap: wrap; justify-content: center; gap: 10px;
  margin-bottom: 40px;
}
.contact-banner-badge {
  display: inline-flex; align-items: center; gap: 6px;
  background: rgba(255,255,255,.1);
  border: 1px solid rgba(255,255,255,.22);
  border-radius: 50px; padding: 6px 16px;
  font-size: 13px; color: rgba(255,255,255,.9);
  backdrop-filter: blur(6px);
}
.contact-banner-badge svg { stroke: rgba(255,255,255,.7); flex-shrink: 0; }
.contact-banner-btn {
  position: relative;
  display: inline-flex; align-items: center; gap: 10px;
  background: #fff !important;
  color: var(--dark-green) !important;
  border: none !important;
  font-size: 15px; font-weight: 700;
  padding: 16px 40px; border-radius: 50px;
  transition: transform .25s var(--ease-expo), box-shadow .25s var(--ease-expo);
  box-shadow: 0 8px 32px rgba(0,0,0,.25);
}
.contact-banner-btn:hover {
  transform: translateY(-3px);
  box-shadow: 0 16px 40px rgba(0,0,0,.35);
}
.contact-banner-btn svg { stroke: var(--olive-green); }

/* ── Responsive ── */
@media (max-width: 853px) {
  /* Numéro de téléphone passe à la ligne en mobile */
  .contact-tel-inline { display: block; margin-top: 4px; }
}
@media (max-width: 991px) {
  .contact-main { padding: 60px 0; }
  .contact-photo-top { aspect-ratio: 16/9; }
}
@media (max-width: 575px) {
  .contact-input { padding: 12px 14px; }
  .contact-banner-section { padding: 80px 16px; }
}

/* ── focus-visible ── */
a:focus-visible, button:focus-visible, input:focus-visible, textarea:focus-visible {
  outline: 3px solid var(--olive-green); outline-offset: 3px; border-radius: 4px;
}

/* ══════════════════════════════════════════════
   CARTOUCHE DE CONFIRMATION
══════════════════════════════════════════════ */
.contact-cartouche {
  background: #fff;
  border-radius: 16px;
  padding: 52px 40px 44px;
  border: 1px solid #d4e4b0;
  box-shadow: 0 8px 40px rgba(51,82,11,.10);
  text-align: center;
  position: relative;
  overflow: hidden;
}
/* Bandeau vert en haut */
.contact-cartouche::before {
  content: '';
  position: absolute;
  top: 0; left: 0; right: 0;
  height: 4px;
  background: linear-gradient(90deg, #1a2e0a 0%, #33520b 50%, #6a8f2f 100%);
  border-radius: 16px 16px 0 0;
}

/* Fond décoratif */
.contact-cartouche::after {
  content: '';
  position: absolute;
  bottom: -60px; right: -60px;
  width: 200px; height: 200px;
  border-radius: 50%;
  background: radial-gradient(circle, rgba(51,82,11,.04) 0%, transparent 70%);
  pointer-events: none;
}

/* SVG animé */
.contact-cartouche-icon { display: inline-block; margin-bottom: 22px; }
.contact-check-svg      { width: 64px; height: 64px; }

.cc-circle {
  stroke-dasharray: 166;
  stroke-dashoffset: 166;
  animation: ccAnim 0.65s cubic-bezier(0.65,0,0.45,1) 0.1s forwards;
}
.cc-check {
  stroke-dasharray: 52;
  stroke-dashoffset: 52;
  animation: ccAnim 0.4s cubic-bezier(0.65,0,0.45,1) 0.75s forwards;
}
@keyframes ccAnim { to { stroke-dashoffset: 0; } }

/* Textes */
.contact-cartouche-label {
  font-family: 'Inter', sans-serif;
  font-size: 10px; font-weight: 700;
  letter-spacing: .22em; text-transform: uppercase;
  color: #33520b; margin: 0 0 12px;
}
.contact-cartouche-title {
  font-family: 'Fraunces', serif;
  font-size: clamp(24px, 4vw, 30px);
  font-weight: 700; color: #1a2e0a;
  margin: 0 0 14px; line-height: 1.2;
}
.contact-cartouche-text {
  font-size: 15px; color: #555; line-height: 1.7;
  margin: 0 auto 14px; max-width: 360px;
}
.contact-cartouche-text strong { color: #1a2e0a; }
.contact-cartouche-email {
  font-size: 13px; color: #aaa;
  margin: 0 auto 30px; max-width: 360px;
}
.contact-cartouche-email strong { color: #555; }

/* Boutons */
.contact-cartouche-actions {
  display: flex; flex-wrap: wrap;
  justify-content: center; gap: 12px;
}
.contact-cartouche-btn {
  display: inline-flex; align-items: center; gap: 7px;
  font-size: 14px;
}

/* ══════════════════════════════════════════════
   ÉTATS D'ERREUR PAR CHAMP
══════════════════════════════════════════════ */
.cf-has-error .contact-input {
  border-color: #c0392b;
  background: #fff8f8;
  box-shadow: 0 0 0 3px rgba(192,57,43,.09);
}
.cf-has-error .contact-input:focus {
  border-color: #c0392b;
  box-shadow: 0 0 0 3px rgba(192,57,43,.15);
}
.cf-has-error .contact-checkbox { accent-color: #c0392b; }
.cf-has-error .contact-rgpd-label { color: #c0392b; }

.cf-error-msg {
  display: flex; align-items: center; gap: 5px;
  font-size: 12px; color: #c0392b;
  margin: 6px 0 0; font-weight: 500;
}
.cf-error-msg::before {
  content: '';
  display: inline-block;
  width: 14px; height: 14px; flex-shrink: 0;
  background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%23c0392b' stroke-width='2'%3E%3Ccircle cx='12' cy='12' r='10'/%3E%3Cline x1='12' y1='8' x2='12' y2='12'/%3E%3Cline x1='12' y1='16' x2='12.01' y2='16'/%3E%3C/svg%3E");
  background-size: cover;
}
</style>

<script>
(function () {
  /* Compteur de caractères sur le textarea */
  var ta    = document.getElementById('cf-message');
  var count = document.getElementById('cf-chars');
  if (ta && count) {
    function updateCount() { count.textContent = ta.value.length; }
    ta.addEventListener('input', updateCount);
    updateCount();
  }

  /* Bouton "Relancer le formulaire" — affiche le form sans rechargement */
  var btnRelancer  = document.getElementById('cfRelancer');
  var cartouche    = document.getElementById('cfCartouche');
  var formWrap     = document.getElementById('cfFormWrap');
  var anchor       = document.getElementById('contact-formulaire');

  if (btnRelancer && cartouche && formWrap) {
    btnRelancer.addEventListener('click', function () {
      /* Cache le cartouche, révèle le formulaire */
      cartouche.style.display = 'none';
      formWrap.style.display  = 'block';

      /* Scroll smooth vers l'ancre */
      if (anchor) {
        anchor.scrollIntoView({ behavior: 'smooth', block: 'start' });
      }

      /* Focus sur le premier champ pour l'accessibilité */
      var firstInput = formWrap.querySelector('input:not([type="hidden"])');
      if (firstInput) setTimeout(function () { firstInput.focus(); }, 400);
    });
  }
}());
</script>

<?php get_footer(); ?>
