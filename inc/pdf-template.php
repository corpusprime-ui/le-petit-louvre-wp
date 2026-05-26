<?php
/**
 * Le Petit Louvre — Template HTML pour le PDF de la carte
 */
if ( ! defined( 'ABSPATH' ) ) exit;

function lpl_pdf_get_html( array $data ): string {

    $entrees        = $data['entrees']        ?? [];
    $partager       = $data['partager']       ?? [];
    $plats          = $data['plats']          ?? [];
    $desserts       = $data['desserts']       ?? [];
    $glaces_boules  = $data['glaces_boules']  ?? '1 boule, 2 boules, 3 boules';
    $glaces_parfums = $data['glaces_parfums'] ?? '';
    $cafe_prix      = $data['cafe_prix']      ?? '10/12';
    $footnote       = $data['footnote']       ?? '';

    ob_start(); ?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<style>
  * { margin: 0; padding: 0; box-sizing: border-box; }
  body {
    font-family: 'DejaVu Sans', sans-serif;
    font-size: 10pt;
    color: #1a2e0a;
    background: #faf8f4;
  }

  /* ── Wrapper centré ── */
  .wrap {
    width: 480pt;
    margin: 0 auto;
  }

  /* ── Header ── */
  .pdf-header {
    text-align: center;
    padding: 26pt 0 16pt;
    border-bottom: 1.5pt solid #33520b;
    margin-bottom: 18pt;
  }
  .pdf-restaurant {
    font-size: 22pt;
    font-weight: bold;
    letter-spacing: 5pt;
    text-transform: uppercase;
    color: #1a2e0a;
  }
  .pdf-subtitle {
    font-size: 8.5pt;
    letter-spacing: 2.5pt;
    text-transform: uppercase;
    color: #33520b;
    margin-top: 5pt;
  }
  .pdf-tagline {
    font-size: 8.5pt;
    font-style: italic;
    color: #888;
    margin-top: 4pt;
  }

  /* ── Titre LA CARTE ── */
  .main-title {
    text-align: center;
    font-size: 14pt;
    font-weight: bold;
    letter-spacing: 6pt;
    text-transform: uppercase;
    color: #1a2e0a;
    margin-bottom: 20pt;
  }

  /* ── Titre de section ── */
  .section-title {
    text-align: center;
    font-size: 10.5pt;
    font-weight: bold;
    letter-spacing: 3pt;
    text-transform: uppercase;
    color: #33520b;
    padding-bottom: 5pt;
    border-bottom: 0.5pt solid #c8d4a0;
    margin-bottom: 10pt;
  }
  .section-block {
    margin-bottom: 22pt;
  }

  /* ── Ligne de plat ── */
  .item {
    margin-bottom: 8pt;
    padding-bottom: 8pt;
    border-bottom: 0.3pt dotted #ddd;
  }
  .item:last-child { border-bottom: none; }

  .item-photo {
    width: 38pt;
    height: 38pt;
    display: block;
    border-radius: 3pt;
  }

  .item-badge {
    font-size: 7pt;
    font-weight: normal;
    color: #33520b;
    border: 0.5pt solid #33520b;
    padding: 1pt 4pt;
    margin-left: 5pt;
    text-transform: none;
    letter-spacing: 0;
  }
  .item-desc {
    font-size: 8.5pt;
    color: #666;
    font-style: italic;
    margin-top: 2pt;
    line-height: 1.4;
  }

  /* ── Glaces ── */
  .glaces-parfums {
    font-size: 8pt;
    color: #888;
    font-style: italic;
    margin-top: 2pt;
    line-height: 1.4;
  }

  /* ── Séparateur décoratif entre sections ── */
  .sep {
    text-align: center;
    color: #c8d4a0;
    font-size: 10pt;
    letter-spacing: 6pt;
    margin: 4pt 0 18pt;
  }

  /* ── Note bas de page ── */
  .footnote {
    margin-top: 18pt;
    padding-top: 10pt;
    border-top: 0.5pt solid #c8d4a0;
    font-size: 8pt;
    color: #999;
    text-align: center;
    line-height: 1.6;
  }

  /* ── Footer ── */
  .pdf-footer {
    text-align: center;
    font-size: 7.5pt;
    color: #bbb;
    margin-top: 12pt;
    letter-spacing: 0.5pt;
  }
</style>
</head>
<body>
<div class="wrap">

  <!-- En-tête -->
  <div class="pdf-header">
    <div class="pdf-restaurant">Le Petit Louvre</div>
    <div class="pdf-subtitle">Cuisine Fusion &nbsp;&middot;&nbsp; Arcachon</div>
    <div class="pdf-tagline">14 Pl. Lucien de Gracia, 33120 Arcachon &nbsp;&middot;&nbsp; 05 57 15 73 59</div>
  </div>

  <!-- Titre -->
  <div class="main-title">La Carte</div>

  <?php
  /* ── Helper : affiche une section complète ── */
  $render_section = function( array $items ) {
    foreach ( $items as $item ) :
      if ( empty( $item['nom'] ) ) continue;
      /* photo_thumb = vignette 144×144px déjà carrée → pas de distorsion dans mPDF */
      $thumb_path = ! empty( $item['photo_thumb'] ) ? $item['photo_thumb'] : ( $item['photo'] ?? '' );
      $has_photo  = ! empty( $thumb_path ) && file_exists( $thumb_path );
      $photo_src  = $has_photo ? htmlspecialchars( $thumb_path, ENT_COMPAT, 'UTF-8' ) : '';
  ?>
    <div class="item">
      <table width="100%" cellpadding="0" cellspacing="0" style="border-collapse:collapse;">
        <tr>
          <?php if ( $has_photo ) : ?>
          <td style="width:44pt;vertical-align:top;padding-right:9pt;padding-top:1pt;">
            <img src="<?php echo $photo_src; ?>" class="item-photo" alt="" width="38" height="38">
          </td>
          <?php endif; ?>
          <td style="vertical-align:top;">
            <table width="100%" cellpadding="0" cellspacing="0" style="border-collapse:collapse;">
              <tr>
                <td style="font-size:10pt;font-weight:bold;text-transform:uppercase;letter-spacing:0.4pt;color:#1a2e0a;text-align:left;vertical-align:top;padding-right:6pt;">
                  <?php echo htmlspecialchars( $item['nom'], ENT_QUOTES, 'UTF-8' ); ?>
                  <?php if ( ! empty( $item['badge'] ) ) : ?><span class="item-badge"><?php echo htmlspecialchars( $item['badge'], ENT_QUOTES, 'UTF-8' ); ?></span><?php endif; ?>
                </td>
                <td style="font-size:10pt;font-weight:bold;color:#33520b;text-align:right;vertical-align:top;white-space:nowrap;width:15%;"><?php echo htmlspecialchars( $item['prix'], ENT_QUOTES, 'UTF-8' ); ?>&thinsp;&euro;</td>
              </tr>
            </table>
            <?php if ( ! empty( $item['desc'] ) ) : ?><div class="item-desc"><?php echo htmlspecialchars( $item['desc'], ENT_QUOTES, 'UTF-8' ); ?></div><?php endif; ?>
          </td>
        </tr>
      </table>
    </div>
  <?php endforeach; }; ?>

  <!-- ENTRÉES -->
  <div class="section-block">
    <div class="section-title">Entrées</div>
    <?php $render_section( $entrees ); ?>
  </div>

  <!-- À PARTAGER -->
  <div class="section-block">
    <div class="section-title">À Partager</div>
    <?php $render_section( $partager ); ?>
  </div>

  <!-- PLATS -->
  <div class="section-block">
    <div class="section-title">Plats</div>
    <?php $render_section( $plats ); ?>
  </div>

  <!-- DESSERTS -->
  <div class="section-block">
    <div class="section-title">Desserts</div>
    <?php $render_section( $desserts ); ?>

    <!-- Glaces & Sorbets -->
    <div class="item">
      <table width="100%" cellpadding="0" cellspacing="0" style="border-collapse:collapse;">
        <tr>
          <td style="font-size:10pt;font-weight:bold;text-transform:uppercase;letter-spacing:0.4pt;color:#1a2e0a;text-align:left;vertical-align:top;">Glaces &amp; Sorbets</td>
          <td style="font-size:10pt;font-weight:bold;color:#33520b;text-align:right;vertical-align:top;white-space:nowrap;width:15%;">3/6/9&thinsp;&euro;</td>
        </tr>
      </table>
      <div class="item-desc"><?php echo htmlspecialchars( $glaces_boules, ENT_QUOTES, 'UTF-8' ); ?></div>
      <?php if ( $glaces_parfums ) :
        foreach ( explode( "\n", $glaces_parfums ) as $ligne ) :
          $ligne = trim( $ligne );
          if ( $ligne ) : ?>
            <div class="glaces-parfums"><?php echo htmlspecialchars( $ligne, ENT_QUOTES, 'UTF-8' ); ?></div>
          <?php endif;
        endforeach;
      endif; ?>
    </div>

    <!-- Café ou Thé Gourmand -->
    <div class="item" style="border-top:0.8pt solid #c8d4a0;padding-top:8pt;margin-top:4pt;border-bottom:none;">
      <table width="100%" cellpadding="0" cellspacing="0" style="border-collapse:collapse;">
        <tr>
          <td style="font-size:10pt;font-weight:bold;text-transform:uppercase;letter-spacing:0.8pt;color:#1a2e0a;text-align:left;vertical-align:middle;">Café ou Thé Gourmand</td>
          <td style="font-size:10pt;font-weight:bold;color:#33520b;text-align:right;vertical-align:middle;white-space:nowrap;width:15%;"><?php echo htmlspecialchars( $cafe_prix, ENT_QUOTES, 'UTF-8' ); ?>&thinsp;&euro;</td>
        </tr>
      </table>
    </div>

  </div><!-- /desserts -->

  <?php if ( $footnote ) : ?>
    <div class="footnote"><?php echo htmlspecialchars( $footnote, ENT_QUOTES, 'UTF-8' ); ?></div>
  <?php endif; ?>

  <div class="pdf-footer">
    lepetitlouvre.fr &nbsp;&middot;&nbsp; reservation@lepetitlouvre.fr &nbsp;&middot;&nbsp; 05 57 15 73 59
  </div>

</div><!-- /wrap -->
</body>
</html>
<?php
    return ob_get_clean();
}
