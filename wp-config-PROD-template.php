<?php
/**
 * wp-config.php — PRODUCTION
 * Le Petit Louvre · lepetitlouvre.fr
 *
 * ⚠️  CHANGER le mot de passe DB et les salts avant de mettre en ligne
 *     Nouveau mot de passe DB → panneau Ionos > Base de données
 *     Nouveaux salts          → https://api.wordpress.org/secret-key/1.1/salt/
 */

// ═══════════════════════════════════════════════════
//  BASE DE DONNÉES
// ═══════════════════════════════════════════════════
define( 'DB_NAME',     'wp_i8qi5' );
define( 'DB_USER',     'lpl_hr5yp' );
define( 'DB_PASSWORD', 'NOUVEAU_MOT_DE_PASSE_ICI' ); // ← changer après compromission
define( 'DB_HOST',     'localhost:3306' );
define( 'DB_CHARSET',  'utf8mb4' );   // utf8mb4 > utf8 (supporte les emojis)
define( 'DB_COLLATE',  '' );

// ═══════════════════════════════════════════════════
//  CLÉS ET SALTS — RÉGÉNÉRER après exposition
//  https://api.wordpress.org/secret-key/1.1/salt/
// ═══════════════════════════════════════════════════
define( 'AUTH_KEY',         'COLLER_NOUVELLES_CLES_ICI' );
define( 'SECURE_AUTH_KEY',  'COLLER_NOUVELLES_CLES_ICI' );
define( 'LOGGED_IN_KEY',    'COLLER_NOUVELLES_CLES_ICI' );
define( 'NONCE_KEY',        'COLLER_NOUVELLES_CLES_ICI' );
define( 'AUTH_SALT',        'COLLER_NOUVELLES_CLES_ICI' );
define( 'SECURE_AUTH_SALT', 'COLLER_NOUVELLES_CLES_ICI' );
define( 'LOGGED_IN_SALT',   'COLLER_NOUVELLES_CLES_ICI' );
define( 'NONCE_SALT',       'COLLER_NOUVELLES_CLES_ICI' );

// ═══════════════════════════════════════════════════
//  PRÉFIXE TABLE — déjà personnalisé ✓
// ═══════════════════════════════════════════════════
$table_prefix = 'H1l80_';

// ═══════════════════════════════════════════════════
//  ENVIRONNEMENT
// ═══════════════════════════════════════════════════
define( 'WP_ENVIRONMENT_TYPE', 'production' );

// ═══════════════════════════════════════════════════
//  CRON — activé en production (sauvegardes, emails)
// ═══════════════════════════════════════════════════
define( 'DISABLE_WP_CRON', false );

// ═══════════════════════════════════════════════════
//  DEBUG — TOUJOURS false en production
// ═══════════════════════════════════════════════════
define( 'WP_DEBUG',         false );
define( 'WP_DEBUG_LOG',     false );
define( 'WP_DEBUG_DISPLAY', false );
@ini_set( 'display_errors', 0 );

// ═══════════════════════════════════════════════════
//  SÉCURITÉ
// ═══════════════════════════════════════════════════

// Force HTTPS pour l'admin WordPress
define( 'FORCE_SSL_ADMIN', true );

// Interdit la modification des fichiers thème/plugin depuis l'admin WP
// Empêche un attaquant qui prend le contrôle de l'admin d'injecter du code
define( 'DISALLOW_FILE_EDIT', true );

// Interdit aussi l'installation de plugins/thèmes depuis l'admin
// (optionnel — commenter si tu veux installer des plugins via l'interface)
define( 'DISALLOW_FILE_MODS', true );

// ═══════════════════════════════════════════════════
//  PERFORMANCE
// ═══════════════════════════════════════════════════

// Limite les révisions d'articles (évite de gonfler la BDD)
define( 'WP_POST_REVISIONS', 5 );

// Intervalle de sauvegarde automatique en secondes (5 min)
define( 'AUTOSAVE_INTERVAL', 300 );

// Vide automatiquement la corbeille toutes les 30 jours
define( 'EMPTY_TRASH_DAYS', 30 );

// Mémoire PHP allouée à WordPress
define( 'WP_MEMORY_LIMIT',     '256M' );
define( 'WP_MAX_MEMORY_LIMIT', '512M' );

// ═══════════════════════════════════════════════════
//  MULTISITE — désactivé (inutile pour ce site)
//  ⚠️ Le WP_ALLOW_MULTISITE actuel n'active pas
//  vraiment le multisite, mais il pollue la config.
//  Supprimer cette ligne est plus propre.
// ═══════════════════════════════════════════════════
// define( 'WP_ALLOW_MULTISITE', true ); ← retiré

// ═══════════════════════════════════════════════════
//  NE PAS METTRE ICI — tokens temporaires WP Toolkit
//  Le WP_TOOLKIT_API_TOKEN est généré à chaque session
//  par Plesk, ne doit pas être stocké dans ce fichier.
// ═══════════════════════════════════════════════════

/* That's all, stop editing! Happy publishing. */

if ( ! defined( 'ABSPATH' ) ) {
    define( 'ABSPATH', __DIR__ . '/' );
}
require_once ABSPATH . 'wp-settings.php';
