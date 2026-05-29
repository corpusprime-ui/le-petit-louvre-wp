# ✅ CHECKLIST MISE EN PRODUCTION — Le Petit Louvre
> Cocher chaque point AVANT de passer le site en live.

---

## 1. 🔧 wp-config.php — 3 lignes à modifier

Ouvre le `wp-config.php` sur le **serveur de production** et change ces 3 lignes :

```php
// AVANT (local)                          // APRÈS (production)
define('WP_ENVIRONMENT_TYPE', 'local');   → define('WP_ENVIRONMENT_TYPE', 'production');
define('DISABLE_WP_CRON', true);          → define('DISABLE_WP_CRON', false);
define('WP_DEBUG', false);                → define('WP_DEBUG', false);  ← déjà bon, vérifier quand même
```

> **DISABLE_WP_CRON = false** = les sauvegardes et tâches planifiées des plugins
> (UpdraftPlus, etc.) se déclencheront automatiquement. Obligatoire en prod.

---

## 2. 📄 .htaccess — Ajouter HTTPS + WWW redirect

Le .htaccess actuel est bien configuré (GZIP, cache, sécurité).
Il manque juste le **redirect HTTP → HTTPS** à ajouter EN HAUT du fichier :

```apache
# ── Force HTTPS ──────────────────────────────────────────────
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteCond %{HTTPS} off
    RewriteRule ^(.*)$ https://%{HTTP_HOST}%{REQUEST_URI} [L,R=301]
</IfModule>

# ── Force sans www (si domaine sans www) ─────────────────────
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteCond %{HTTP_HOST} ^www\.lepetitlouvre\.fr [NC]
    RewriteRule ^(.*)$ https://lepetitlouvre.fr/$1 [L,R=301]
</IfModule>
```

> ⚠️ Si l'hébergeur (Ionos) gère déjà le HTTPS via le panneau d'admin,
> ne pas ajouter ces lignes — risque de boucle de redirection.
> Vérifier d'abord dans le panneau Ionos si "Redirection HTTPS" est cochée.

---

## 3. 🗄️ Base de données — URLs locales à remplacer

Après import de la base locale vers la prod, faire un **Search & Replace** :

Utiliser le plugin **"Better Search Replace"** (gratuit) ou WP-CLI :

```bash
wp search-replace 'http://le-petit-louvre-2.local' 'https://lepetitlouvre.fr' --all-tables
```

| Chercher | Remplacer par |
|----------|---------------|
| `http://le-petit-louvre-2.local` | `https://lepetitlouvre.fr` |
| `//le-petit-louvre-2.local` | `//lepetitlouvre.fr` |

> ⚠️ Ne pas oublier de cocher "Exécuter en mode simulation" d'abord pour vérifier.

---

## 4. 🔌 Plugins — Vérifier avant la mise en ligne

### À désactiver / supprimer en prod :
- [ ] Query Monitor (plugin de debug)
- [ ] WP Debugging ou tout plugin de dev
- [ ] Local par Flywheel (s'il est installé)

### À activer / vérifier en prod :
- [ ] Yoast SEO → vérifier que "Bloquer les moteurs de recherche" est **décoché**
- [ ] Plugin de cache (WP Rocket, W3 Total Cache, LiteSpeed Cache selon hébergeur)
- [ ] UpdraftPlus ou plugin de sauvegarde → planifier une sauvegarde auto

---

## 5. 🌐 DNS & SSL

- [ ] Certificat SSL actif (HTTPS vert dans le navigateur)
- [ ] DNS propagé (vérifier sur https://dnschecker.org)
- [ ] www.lepetitlouvre.fr redirige bien vers lepetitlouvre.fr (ou inverse)
- [ ] Aucune ressource chargée en HTTP (vérifier dans DevTools > Network)

---

## 6. 📧 Formulaires — Tester en prod

- [ ] Formulaire de **contact** → envoie un vrai email
- [ ] Formulaire de **réservation** → envoie un vrai email
- [ ] Email de réponse reçu sur `contact@lepetitlouvre.fr`

> Si les emails n'arrivent pas : installer le plugin **WP Mail SMTP**
> et configurer avec les identifiants SMTP d'Ionos.

---

## 7. 📊 Outils Google — Vérifier les connexions

- [ ] **Google Search Console** → ajouter la propriété `lepetitlouvre.fr` et soumettre le sitemap
  URL du sitemap : `https://lepetitlouvre.fr/sitemap_index.xml`
- [ ] **Google Analytics** → vérifier que le tag est bien présent (GA4)
- [ ] **Google My Business** → vérifier que l'URL du site est à jour

---

## 8. ⚡ Performance — Tester après mise en ligne

- [ ] PageSpeed Insights : https://pagespeed.web.dev → objectif > 85 mobile
- [ ] GTmetrix : https://gtmetrix.com
- [ ] Vérifier que les vidéos se chargent correctement (hero-video-h265.mp4 sur desktop)
- [ ] Vérifier que les fonts (Fraunces, Inter) s'affichent bien (pas de FOUT visible)

---

## 9. 🔒 Sécurité — Dernière vérification

- [ ] `WP_DEBUG = false` en prod (déjà dans wp-config.php)
- [ ] Mot de passe admin WordPress fort
- [ ] Préfixe de table BDD personnalisé (pas `wp_` par défaut)
- [ ] Fichier `wp-config.php` non accessible depuis le web (normalement protégé par Apache)
- [ ] `xmlrpc.php` bloqué → déjà dans le .htaccess ✓

---

## 10. 📱 Tests finaux

- [ ] Tester sur **iPhone** (Safari) — vidéo hero, animations, réservation
- [ ] Tester sur **Android** (Chrome) — même chose
- [ ] Tester la **page Carte** → nav ancres, sections Huîtres/Burgers/Salades
- [ ] Tester le **formulaire de contact**
- [ ] Vérifier l'affichage des **fonts** (Fraunces pour les titres, Inter pour le texte)

---

*Checklist générée le 27 mai 2026 — thème Le Petit Louvre v1.5.1*
