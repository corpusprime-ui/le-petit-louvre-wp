# CLAUDE.md — Le Petit Louvre · Instructions automatiques

> Ce fichier est lu automatiquement par Claude à chaque ouverture du projet.

---

## 🔁 VÉRIFICATIONS AU DÉMARRAGE

À chaque session, vérifier silencieusement :

1. **Git** — lancer `git status` et `git log --oneline -5` pour confirmer que le dépôt est actif et afficher les 5 derniers commits. Si git n'est pas initialisé, le signaler immédiatement.
2. **Modifications non commitées** — si des fichiers sont modifiés mais non commités, le mentionner en début de session.

---

## 📁 FICHIERS CLÉS DU THÈME

| Fichier | Rôle |
|---|---|
| `css/main.css` | **Toute la CSS** — un seul fichier, ne jamais créer de second fichier CSS |
| `js/main.js` | **Tout le JS** — animations, sliders, accordéons, nav |
| `functions.php` | Enqueue scripts/styles, ACF options, helpers `lpl_field()`, SEO/OG |
| `inc/acf-fields.php` | Définition de tous les champs ACF (back-office) |
| `header.php` | Sticky nav (apparaît au scroll) |
| `template-parts/site-header.php` | Nav dans le hero (visible au chargement) |
| `footer.php` | Footer + mobile nav overlay |
| `front-page.php` | Page d'accueil |
| `page-reservation.php` | Page réservation |
| `page-contact.php` | Page contact |
| `page-carte.php` | Carte des plats |
| `page-carte-des-vins.php` | Carte des vins |
| `page-carte-des-boissons.php` | Carte des boissons |
| `page-carte-des-cocktails.php` | Carte des cocktails |
| `CHECKLIST-MISE-EN-PROD.md` | ⚠️ Checklist complète avant mise en production |

---

## 🎨 CONVENTIONS DU PROJET

### Couleurs (CSS variables)
```css
--dark-green:      #1d3300   /* Textes principaux, titres */
--olive-green:     #606930   /* Couleur primaire, boutons, accents */
--medium-green:    #4a5a20   /* Hover boutons */
--light-green-bg:  #f0f4e8   /* Fonds clairs */
--page-bg:         #faf8f4   /* Fond général des pages */
```

### Typographies
- **Fraunces** (serif) → titres `.section-title`, `.hero-title`, prix, noms de plats
- **Inter** (sans-serif) → textes courants, labels, descriptions
- **Laila** (serif) → éléments décoratifs, badges carte
- **Optima** → réservé aux anciens styles (ne plus utiliser)
- Georgia → fallback pour les prix sur les cartes

### Breakpoints
- Mobile : `max-width: 575px`
- Tablette : `max-width: 853px`
- Desktop moyen : `max-width: 980px`
- Desktop large : `min-width: 1200px`

### Règles CSS impératives
- **Tous les `:hover`** doivent être wrappés dans `@media (hover: hover) and (pointer: fine)` → évite le double-tap iOS
- **`touch-action: manipulation`** sur `a, button, .btn` → déjà en place, ne pas supprimer
- **`will-change`** : ne pas ajouter sur des sélecteurs génériques (trop de couches GPU)
- **`body { opacity: 0 }`** : ne jamais remettre (tue le LCP)
- **`content-visibility: auto`** : déjà appliqué sur 6 sections lourdes, ne pas retirer

### Structure des sections
Toujours utiliser ce pattern pour les en-têtes de section :
```html
<p class="section-label">Sous-titre court</p>
<h2 class="section-title">Titre principal</h2>
<div class="sep-line"></div>
```

### Boutons
- `.btn.btn-filled` → CTA principal (fond vert olive)
- `.btn.btn-outline` → CTA secondaire (bordure verte)
- `.btn.btn-lg` → grande taille (height 53px)
- `.btn.btn-compact` → taille réduite (padding horizontal réduit)

---

## 🚀 MISE EN PRODUCTION — RAPPEL

**Fichier complet :** `CHECKLIST-MISE-EN-PROD.md`

Points critiques à ne pas oublier :

1. **`wp-config.php`** → changer `WP_ENVIRONMENT_TYPE` en `'production'` et `DISABLE_WP_CRON` en `false`
2. **Base de données** → Search & Replace `http://le-petit-louvre-2.local` → `https://lepetitlouvre.fr`
3. **Yoast SEO** → décocher "Bloquer les moteurs de recherche"
4. **Plugin de debug** (Query Monitor) → désactiver
5. **HTTPS** → vérifier le certificat SSL + redirect HTTP→HTTPS dans `.htaccess`
6. **Emails** → tester formulaire contact + réservation (configurer WP Mail SMTP si besoin)
7. **Google Search Console** → soumettre le sitemap `https://lepetitlouvre.fr/sitemap_index.xml`
8. **PageSpeed** → objectif > 85 sur mobile (https://pagespeed.web.dev)

---

## ⚠️ NE JAMAIS MODIFIER SANS ACCORD

- La structure des champs ACF dans `inc/acf-fields.php` — modifier les `key` casse les données en base
- Les IDs des sections (`#hero`, `#resa-form`, `#cocktails-menu`…) — utilisés dans les ancres JS
- Le widget Zenchef (iframe `bookings.zenchef.com`) — ne pas toucher aux paramètres `rid` et `pid`
- Les variables CSS `--dark-green`, `--olive-green` — utilisées partout, un changement impacte tout le site

---

## 🌐 INFOS SITE

- **URL locale :** http://le-petit-louvre-2.local
- **URL production :** https://lepetitlouvre.fr
- **Hébergeur :** Ionos
- **Stack :** WordPress + ACF Pro + Bootstrap 5.3 + Zenchef (réservations)
- **Téléphone :** 05 57 15 73 59
- **Email resa :** reservation@lepetitlouvre.fr
- **Adresse :** 14 Place Lucien de Gracia, 33120 Arcachon
