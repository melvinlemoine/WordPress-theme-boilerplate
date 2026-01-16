# Guidelines du Thème WordPress

## 🔒 Sécuriser le contenu des champs ACF

Pour sécuriser les données issues des champs ACF, utilisez les fonctions d'échappement appropriées :

- **`wp_kses_post()`** - Pour le contenu HTML avec balises autorisées
- **`esc_html()`** - Pour le texte brut (échappe les balises HTML)
- **`esc_url()`** - Pour les URLs
- **`esc_svg()`** - Pour les codes SVG inline (fonction custom du thème)

### Sécurisation des SVG

Pour afficher du code SVG inline de manière sécurisée, utilisez la fonction custom `esc_svg()` disponible dans `config/guidelines.php` :

```php
// ❌ Non sécurisé - à éviter
echo get_icon('mon-icone');

// ✅ Sécurisé - recommandé
echo esc_svg(get_icon('mon-icone'));
```

La fonction `esc_svg()` utilise `wp_kses()` avec une liste exhaustive de balises et attributs SVG autorisés. Elle protège contre les failles XSS tout en permettant l'affichage correct du SVG.

**Exemple d'utilisation avec ACF :**

```php
<?php
$icon = get_field('nav-item__icon', $item);
if ($icon): ?>
    <?php echo esc_svg(get_icon($icon)); ?>
<?php endif; ?>
```

**Pourquoi ne pas utiliser `wp_kses_post()` pour les SVG ?**

`wp_kses_post()` est conçu pour du contenu HTML standard et supprime les balises SVG comme `<svg>`, `<path>`, etc. Il faut utiliser `wp_kses()` avec une configuration spécifique pour autoriser les éléments SVG.

### 📚 Référence

Pour connaître le format des données récupérées par les champs ACF, consultez les fichiers dans le dossier `acf-json/` à la racine du projet.

---

## 🔗 Liens externes avec target="_blank"

Pour les liens qui s'ouvrent dans un nouvel onglet, utilisez toujours `rel="noopener"` pour des raisons de sécurité et de performance :

```html
<a href="https://example.com" target="_blank" rel="noopener">Lien externe</a>
```

### Pourquoi utiliser noopener ?

- **Sécurité** : Empêche la page ouverte d'accéder à `window.opener` et de modifier la page d'origine
- **Performance** : La nouvelle page s'exécute dans un processus séparé, sans ralentir la page d'origine

### Exemple avec ACF

```php
<a href="<?php echo esc_url(get_field('external_link')); ?>" target="_blank" rel="noopener">
    <?php echo esc_html(get_field('link_text')); ?>
</a>
```

---

## 📦 Inclure des fichiers et assets du thème

### Inclure des templates PHP

Pour inclure des fichiers de template PHP, utilisez **toujours** la fonction WordPress `get_template_part()` :

```php
// ❌ Non recommandé - méthode PHP classique
<?php include 'includes/formation-card-line.php'; ?>
<?php require_once 'template-parts/header-nav.php'; ?>

// ✅ Recommandé - fonction WordPress
<?php get_template_part('includes/formation-card-line'); ?>
<?php get_template_part('template-parts/header-nav'); ?>
```

**Avantages de `get_template_part()` :**
- Fonction native WordPress sécurisée
- Supporte automatiquement les thèmes enfants
- Gère les chemins relatifs au thème
- Validation des chemins intégrée
- Possibilité de passer des variables (WordPress 5.5+)

#### Passer des variables à un template (WordPress 5.5+)

```php
<?php
get_template_part('includes/formation-card-line', null, [
    'formation_id' => $formation_id,
    'custom_data' => $data
]);
?>
```

Dans le fichier inclus, accédez aux variables via `$args` :

```php
<?php
// Dans includes/formation-card-line.php
$formation_id = $args['formation_id'] ?? null;
$custom_data = $args['custom_data'] ?? [];
?>
```

#### Alternative avec `locate_template()`

Pour plus de contrôle ou pour vérifier l'existence du fichier :

```php
<?php
$template = locate_template('includes/formation-card-line.php');
if ($template) {
    include $template;
}
?>
```

### Inclure des assets (CSS, JS, images)

Pour récupérer l'URL d'un asset du thème :

```php
get_template_directory_uri()
```

**Exemple d'utilisation :**

```php
<img src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/logo.png'); ?>" alt="Logo">
```

---

## 📧 Désactiver les emails de mise à jour

Pour désactiver l'envoi d'emails lors des mises à jour automatiques des plugins et du core, ajoutez dans `functions.php` :

```php
// Désactiver les notifications par email pour les mises à jour automatiques
add_filter('auto_core_update_send_email', '__return_false');
add_filter('auto_plugin_update_send_email', '__return_false');
add_filter('auto_theme_update_send_email', '__return_false');
```

---

## ⚡ Optimisations des performances

### Défilement fluide (Smooth Scrolling)

Pour activer un défilement fluide lors des navigations avec ancres (liens internes), ajoutez cette propriété CSS au niveau global :

```css
html {
    scroll-behavior: smooth;
}
```

**Avantages :**
- Améliore l'expérience utilisateur lors de la navigation via ancres
- Effet visuel professionnel et moderne
- Compatible avec tous les navigateurs modernes
- Aucun JavaScript nécessaire

**Exemple d'utilisation :**
```html
<!-- Lien vers une section de la page -->
<a href="#contact">Aller à la section contact</a>

<!-- Section ciblée -->
<section id="contact">
    <!-- Contenu de contact -->
</section>
```

**Note :** Le défilement sera fluide automatiquement lors du clic sur le lien.

### Exécution des scripts après le chargement de la page

Pour garantir que tous les scripts s'exécutent une fois que la page et tous ses assets (images, styles, etc.) sont complètement chargés, utilisez toujours `window.addEventListener('load')` :

```javascript
window.addEventListener('load', function() {
    // Votre code ici
    // console.log('Page entièrement chargée');

    // Exemple : Initialisation de bibliothèques
    // initCarousel();
    // initAnimations();
});
```

### ⚠️ Gestion des console.log en production

**IMPORTANT** : Tous les `console.log()` doivent être commentés avant la mise en production pour éviter :
- La pollution de la console du navigateur
- Les ralentissements potentiels
- L'exposition d'informations de débogage

```javascript
// ❌ Non recommandé en production
console.log('Données chargées:', data);
console.error('Erreur détectée:', error);

// ✅ Recommandé pour la production
// console.log('Données chargées:', data);
// console.error('Erreur détectée:', error);
```

**Bonnes pratiques :**
- Commentez systématiquement les `console.log()`, `console.warn()`, `console.error()` avant le déploiement
- Utilisez un système de logging conditionnel si nécessaire :

```javascript
const DEBUG = false; // Passer à true pour le développement

if (DEBUG) {
    console.log('Mode débogage actif');
}
```

### Pourquoi utiliser 'load' ?

- **Fiabilité** : Assure que tous les éléments DOM, images et ressources sont disponibles
- **Évite les erreurs** : Prévient les erreurs liées à des éléments non encore chargés
- **Performance** : Permet au navigateur de prioriser le rendu initial de la page

### Alternative avec DOMContentLoaded

Si vous n'avez pas besoin d'attendre le chargement des images et autres ressources, vous pouvez utiliser `DOMContentLoaded` (plus rapide) :

```javascript
document.addEventListener('DOMContentLoaded', function() {
    // S'exécute dès que le DOM est prêt, sans attendre les images
});
```

### Utiliser defer et async pour optimiser le chargement des scripts

Ajoutez les attributs `defer` ou `async` directement sur les balises `<script>` dans `header.php`, `footer.php` ou sur les pages contenant des scripts pour améliorer les performances de chargement :

#### Defer (recommandé dans la plupart des cas)

Le script est téléchargé en parallèle mais exécuté après l'analyse du HTML, dans l'ordre d'apparition :

```html
<script src="<?php echo get_template_directory_uri(); ?>/assets/js/script.js" defer></script>
```

**Avantages de defer :**
- N'bloque pas le parsing HTML
- Maintient l'ordre d'exécution des scripts
- Idéal pour les scripts qui dépendent du DOM ou d'autres scripts

#### Async (pour scripts indépendants)

Le script est téléchargé et exécuté dès qu'il est disponible, sans ordre garanti :

```html
<script src="<?php echo get_template_directory_uri(); ?>/assets/js/analytics.js" async></script>
```

**Avantages de async :**
- N'bloque pas le parsing HTML
- Exécution la plus rapide possible
- Idéal pour les scripts indépendants (analytics, tracking, etc.)

#### Quand utiliser defer vs async ?

| Attribut | Utilisation | Exemples |
|----------|-------------|----------|
| **defer** | Scripts qui dépendent du DOM ou d'autres scripts | Scripts principaux, bibliothèques UI, animations |
| **async** | Scripts complètement indépendants | Analytics, tracking, widgets tiers |
| **Aucun** | Scripts critiques qui doivent s'exécuter immédiatement | Inline critiques, polyfills essentiels |

### Désactiver jQuery

Pour désactiver jQuery chargé par WordPress (si non utilisé) :

```php
// Désactiver jQuery
function disable_jquery() {
    if (!is_admin()) {
        wp_deregister_script('jquery');
    }
}
add_action('wp_enqueue_scripts', 'disable_jquery');
```

> ⚠️ **ATTENTION - Formidable Forms** : Ne PAS désactiver jQuery si vous utilisez le plugin Formidable Forms. Le plugin nécessite jQuery pour fonctionner correctement et ne chargera pas son script `frm.min.js` si jQuery n'est pas disponible. Si vous devez charger votre propre version de jQuery dans le header pour d'autres librairies (Slick, Fancybox, etc.), gardez la fonction `disable_jquery()` commentée.

### Désactiver les emojis

Pour désactiver le support des emojis WordPress (améliore les performances) :

```php
// Désactiver les emojis WordPress
function disable_emojis() {
    remove_action('wp_head', 'print_emoji_detection_script', 7);
    remove_action('admin_print_scripts', 'print_emoji_detection_script');
    remove_action('wp_print_styles', 'print_emoji_styles');
    remove_action('admin_print_styles', 'print_emoji_styles');
    remove_filter('the_content_feed', 'wp_staticize_emoji');
    remove_filter('comment_text_rss', 'wp_staticize_emoji');
    remove_filter('wp_mail', 'wp_staticize_emoji_for_email');
}
add_action('init', 'disable_emojis');
```

### Désactiver Heartbeat API

Pour réduire les requêtes AJAX automatiques de WordPress :

```php
// Désactiver Heartbeat API (réduit les requêtes AJAX)
add_action('init', function() {
    wp_deregister_script('heartbeat');
}, 1);
```

### Désactiver les REST API pour les non-connectés

Si les API REST ne sont pas utilisées publiquement :

```php
// Désactiver REST API pour les visiteurs non-connectés
add_filter('rest_authentication_errors', function($result) {
    if (!is_user_logged_in()) {
        return new WP_Error('rest_disabled', 'REST API disabled', array('status' => 401));
    }
    return $result;
});
```

### Nettoyer le `<head>`

Retirer les liens et métadonnées inutiles du `<head>` :

```php
// Nettoyer le <head> WordPress
remove_action('wp_head', 'rsd_link'); // Really Simple Discovery
remove_action('wp_head', 'wlwmanifest_link'); // Windows Live Writer
remove_action('wp_head', 'wp_shortlink_wp_head'); // Shortlink
remove_action('wp_head', 'adjacent_posts_rel_link_wp_head', 10); // Liens prev/next
```

### Désactiver les styles Gutenberg

Si vous n'utilisez pas l'éditeur Gutenberg :

```php
// Désactiver les CSS de Gutenberg
function disable_gutenberg_styles() {
    wp_dequeue_style('wp-block-library');
    wp_dequeue_style('wp-block-library-theme');
    wp_dequeue_style('wc-blocks-style');
}
add_action('wp_enqueue_scripts', 'disable_gutenberg_styles', 100);
```

---

## 🔐 Sécurité avancée

### Désactiver l'éditeur de fichiers

Empêcher l'édition de fichiers PHP via l'interface d'administration (à ajouter dans `wp-config.php`) :

```php
// Désactiver l'éditeur de fichiers dans l'admin
define('DISALLOW_FILE_EDIT', true);
```

### Masquer la version de WordPress

Éviter d'exposer la version de WordPress (vulnérabilités connues) :

```php
// Masquer la version WordPress
remove_action('wp_head', 'wp_generator');
```

### Désactiver XML-RPC

Protection contre les attaques brute force via XML-RPC :

```php
// Désactiver XML-RPC
add_filter('xmlrpc_enabled', '__return_false');
```

---

## 📊 Tracking Analytics avec Matomo

### Événements de tracking

Pour mesurer l'interaction des utilisateurs avec les éléments de la page, utilisez l'API de tracking Matomo avec `_paq.push()`.

#### Structure des événements

```javascript
_paq.push(['trackEvent', 'Catégorie (Page)', 'Action', 'Nom de l\'élément']);
```

- **Catégorie** : Nom de la page ou section concernée
- **Action** : Type d'interaction
- **Nom** : Description de l'élément cliqué

#### Types d'actions

##### Clics effectifs (éléments cliquables)

Pour mesurer les clics sur des éléments qui sont réellement cliquables (liens, boutons, etc.) :

```html
<a href="#" onclick="_paq.push(['trackEvent', 'Nos formations', '🖱️ Click', 'Calendrier (header)']);">
    Voir le calendrier
</a>
```

##### Clics exploratoires (test d'UX)

Pour sonder le comportement des visiteurs sur des éléments non-cliquables et identifier des opportunités d'amélioration :

```html
<img src="image.jpg" onclick="_paq.push(['trackEvent', 'Formation', '❌ Click fail', 'Illustration (header)']);" alt="Formation">
```

#### Exemples d'utilisation

**Bouton de call-to-action :**
```html
<button onclick="_paq.push(['trackEvent', 'Accueil', '🖱️ Click', 'CTA Contact']);">
    Nous contacter
</button>
```

**Lien de navigation :**
```html
<a href="/formations" onclick="_paq.push(['trackEvent', 'Menu principal', '🖱️ Click', 'Formations']);">
    Formations
</a>
```

**Image décorative (test UX) :**
```html
<div class="hero-image" onclick="_paq.push(['trackEvent', 'Page produit', '❌ Click fail', 'Bannière hero']);">
    <!-- Contenu -->
</div>
```

#### Bonnes pratiques

- Utilisez des noms de catégories cohérents pour faciliter l'analyse
- Soyez descriptif dans le nom de l'élément pour identifier précisément ce qui est cliqué
- Utilisez `🖱️ Click` pour les interactions normales
- Utilisez `❌ Click fail` pour les clics exploratoires sur éléments non-cliquables
- Testez que le tracking fonctionne via la console Matomo

---

## 📷 Optimisation des images

### Désactiver les tailles d'images inutilisées

Supprimer les tailles d'images générées automatiquement mais non utilisées :

```php
// Désactiver les tailles d'images par défaut inutilisées
function disable_unused_image_sizes() {
    remove_image_size('medium_large'); // 768px
    remove_image_size('1536x1536'); // 2x medium_large
    remove_image_size('2048x2048'); // 2x large
}
add_action('init', 'disable_unused_image_sizes');
```

---

## ♿ Accessibilité web - Conformité RGAA

### Qu'est-ce que le RGAA ?

Le **RGAA (Référentiel Général d'Amélioration de l'Accessibilité)** est le référentiel français d'accessibilité numérique. Il transpose les normes internationales WCAG (Web Content Accessibility Guidelines) dans le contexte légal français.

**Obligations légales :**
- **Article 47 de la loi n° 2005-102** du 11 février 2005 : obligation d'accessibilité pour les services publics
- **Directive européenne 2016/2102** : impose l'accessibilité des sites web et applications mobiles des organismes publics
- **Décret n° 2019-768** du 24 juillet 2019 : précise les modalités d'application

**Qui est concerné ?**
- Services de l'État et collectivités territoriales
- Établissements publics
- Entreprises délégataires d'une mission de service public
- Entreprises privées (selon le chiffre d'affaires et la nature de l'activité)

### Niveaux de conformité

Le RGAA définit 3 niveaux de conformité (basés sur WCAG) :

| Niveau | Conformité | Exigence |
|--------|-----------|----------|
| **A** | Simple | Critères de base (minimum légal) |
| **AA** | Intermédiaire | Recommandé pour la plupart des sites (objectif courant) |
| **AAA** | Optimal | Conformité maximale (rarement atteint) |

**Objectif recommandé** : niveau AA (double A)

### Principes fondamentaux (POUR)

L'accessibilité web repose sur 4 principes essentiels :

1. **Perceptible** : L'information doit être présentée de manière à ce que tous les utilisateurs puissent la percevoir
2. **Opérable** : Les composants de l'interface doivent être utilisables par tous
3. **Compréhensible** : L'information et l'interface doivent être compréhensibles
4. **Robuste** : Le contenu doit être compatible avec les technologies d'assistance

### Critères essentiels à respecter

#### 1. Images et médias

**Attribut alt obligatoire :**
```html
<!-- ✅ Image informative -->
<img src="logo.png" alt="Logo Tyeco - Formation professionnelle">

<!-- ✅ Image décorative (alt vide) -->
<img src="decoration.png" alt="" aria-hidden="true">

<!-- ❌ À éviter -->
<img src="photo.png">
<img src="photo.png" alt="photo">
```

**Vidéos et contenus audio :**
```html
<video controls>
    <source src="video.mp4" type="video/mp4">
    <track kind="captions" src="captions-fr.vtt" srclang="fr" label="Français">
    <track kind="descriptions" src="descriptions-fr.vtt" srclang="fr" label="Descriptions">
</video>
```

#### 2. Structure sémantique HTML

**Utiliser les balises HTML5 appropriées :**
```html
<!-- ✅ Structure sémantique -->
<header>
    <nav aria-label="Navigation principale">
        <!-- Navigation -->
    </nav>
</header>

<main>
    <article>
        <h1>Titre principal</h1>
        <section>
            <h2>Sous-titre</h2>
            <!-- Contenu -->
        </section>
    </article>
</main>

<aside aria-label="Informations complémentaires">
    <!-- Sidebar -->
</aside>

<footer>
    <!-- Pied de page -->
</footer>

<!-- ❌ À éviter -->
<div class="header">
    <div class="navigation">
        <!-- Navigation avec divs -->
    </div>
</div>
```

**Hiérarchie des titres respectée :**
```html
<!-- ✅ Hiérarchie correcte -->
<h1>Titre principal</h1>
    <h2>Section 1</h2>
        <h3>Sous-section 1.1</h3>
        <h3>Sous-section 1.2</h3>
    <h2>Section 2</h2>

<!-- ❌ Hiérarchie incorrecte -->
<h1>Titre principal</h1>
    <h3>Section (saute h2)</h3>
    <h2>Autre section</h2>
```

#### 3. Formulaires accessibles

**Labels et champs associés :**
```html
<!-- ✅ Label explicite associé -->
<label for="email">Adresse email *</label>
<input type="email" id="email" name="email" required aria-required="true">

<!-- ✅ Regroupement de champs -->
<fieldset>
    <legend>Civilité</legend>
    <input type="radio" id="mr" name="civility" value="mr">
    <label for="mr">Monsieur</label>

    <input type="radio" id="mme" name="civility" value="mme">
    <label for="mme">Madame</label>
</fieldset>

<!-- ✅ Messages d'erreur accessibles -->
<label for="phone">Téléphone *</label>
<input type="tel" id="phone" name="phone" aria-describedby="phone-error" aria-invalid="true">
<span id="phone-error" role="alert">Format invalide. Exemple : 01 23 45 67 89</span>
```

#### 4. Navigation au clavier

**Ordre de tabulation cohérent :**
```html
<!-- ✅ Navigation au clavier -->
<a href="/formations" tabindex="0">Nos formations</a>
<button type="button" tabindex="0">Ouvrir le menu</button>

<!-- ✅ Skip link (lien d'évitement) -->
<a href="#main-content" class="skip-link">Aller au contenu principal</a>

<main id="main-content">
    <!-- Contenu -->
</main>
```

**CSS pour le skip link :**
```css
.skip-link {
    position: absolute;
    top: -40px;
    left: 0;
    background: #000;
    color: #fff;
    padding: 8px;
    text-decoration: none;
    z-index: 100;
}

.skip-link:focus {
    top: 0;
}
```

**Indicateur de focus visible :**
```css
/* ✅ Focus visible personnalisé */
a:focus, button:focus, input:focus {
    outline: 2px solid #0066cc;
    outline-offset: 2px;
}

/* ❌ Ne JAMAIS supprimer le focus */
/* INTERDIT : */
*:focus {
    outline: none; /* Ne jamais faire ça ! */
}
```

#### 5. Contrastes de couleurs

**Ratios de contraste minimums (WCAG AA) :**
- Texte normal : **4.5:1** minimum
- Texte large (18pt+ ou 14pt+ gras) : **3:1** minimum
- Éléments d'interface : **3:1** minimum

**Outils de vérification :**
- [WebAIM Contrast Checker](https://webaim.org/resources/contrastchecker/)
- Inspecteur Chrome/Firefox (affiche le ratio de contraste)
- [Contrast Ratio par Lea Verou](https://contrast-ratio.com/)

**Exemples :**
```css
/* ✅ Contraste suffisant */
.text-primary {
    color: #1a1a1a; /* Noir sur blanc : 16.1:1 */
}

.button {
    background: #0066cc;
    color: #ffffff; /* Contraste 7.3:1 */
}

/* ⚠️ Contraste insuffisant */
.text-light {
    color: #767676; /* Gris clair sur blanc : 3.9:1 - Insuffisant pour texte normal */
}
```

#### 6. ARIA (Accessible Rich Internet Applications)

**Rôles ARIA :**
```html
<!-- Navigation -->
<nav role="navigation" aria-label="Navigation principale">
    <!-- Menu -->
</nav>

<!-- Zone de recherche -->
<form role="search">
    <input type="search" aria-label="Rechercher sur le site">
    <button type="submit">Rechercher</button>
</form>

<!-- Alerte -->
<div role="alert" aria-live="polite">
    Votre message a été envoyé avec succès.
</div>

<!-- Bouton de menu mobile -->
<button
    aria-label="Ouvrir le menu de navigation"
    aria-expanded="false"
    aria-controls="mobile-menu">
    <span aria-hidden="true">☰</span>
</button>

<div id="mobile-menu" aria-hidden="true">
    <!-- Menu mobile -->
</div>
```

**États dynamiques :**
```javascript
// Gestion de l'état du menu mobile
const menuButton = document.querySelector('[aria-controls="mobile-menu"]');
const menu = document.getElementById('mobile-menu');

menuButton.addEventListener('click', function() {
    const isExpanded = this.getAttribute('aria-expanded') === 'true';

    this.setAttribute('aria-expanded', !isExpanded);
    menu.setAttribute('aria-hidden', isExpanded);
});
```

#### 7. Langue du document

**Définir la langue principale :**
```html
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Tyeco - Formation professionnelle</title>
</head>
```

**Indiquer les changements de langue :**
```html
<p>Notre formation est disponible en
    <span lang="en">English</span> et en
    <span lang="es">Español</span>
</p>
```

#### 8. Tableaux accessibles

**Tableaux de données :**
```html
<table>
    <caption>Calendrier des formations 2025</caption>
    <thead>
        <tr>
            <th scope="col">Formation</th>
            <th scope="col">Date</th>
            <th scope="col">Durée</th>
            <th scope="col">Prix</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <th scope="row">WordPress Avancé</th>
            <td>15 mars 2025</td>
            <td>3 jours</td>
            <td>1 200 €</td>
        </tr>
    </tbody>
</table>
```

#### 9. Liens explicites

**Contexte et libellés clairs :**
```html
<!-- ✅ Lien explicite -->
<a href="/formations/wordpress">Découvrir notre formation WordPress</a>

<!-- ✅ Lien avec contexte ARIA -->
<a href="/formation-1" aria-label="En savoir plus sur la formation WordPress">
    En savoir plus
</a>

<!-- ❌ Lien non explicite -->
<a href="/formation-1">Cliquez ici</a>
<a href="/doc.pdf">Lire la suite</a>
```

#### 10. Responsive et zoom

**Permettre le zoom :**
```html
<!-- ✅ Viewport accessible -->
<meta name="viewport" content="width=device-width, initial-scale=1">

<!-- ❌ Zoom bloqué (interdit) -->
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
```

**Texte redimensionnable :**
```css
/* ✅ Utiliser rem/em pour les tailles de texte */
body {
    font-size: 16px; /* Taille de base */
}

h1 {
    font-size: 2rem; /* 32px, mais s'adapte au zoom */
}

/* ❌ Éviter les tailles fixes */
p {
    font-size: 14px; /* Ne s'adapte pas bien au zoom */
}
```

### Outils de test et validation

#### Outils automatiques

**Extensions navigateur :**
- **axe DevTools** (Chrome/Firefox) - Extension complète
- **WAVE** (Web Accessibility Evaluation Tool)
- **Lighthouse** (intégré dans Chrome DevTools)
- **Assistant RGAA** (spécifique au référentiel français)

**Vérificateurs en ligne :**
- [Validateur W3C HTML](https://validator.w3.org/)
- [WAVE Web Accessibility Evaluation Tool](https://wave.webaim.org/)
- [AChecker](https://achecker.ca/)

#### Tests manuels essentiels

**1. Navigation au clavier :**
- Tester la navigation complète avec la touche `Tab`
- Vérifier que tous les éléments interactifs sont accessibles
- S'assurer que le focus est toujours visible
- Tester `Shift + Tab` pour la navigation arrière
- Vérifier `Enter` et `Espace` pour activer les éléments

**2. Lecteurs d'écran :**
- **NVDA** (Windows, gratuit) - [https://www.nvaccess.org/](https://www.nvaccess.org/)
- **JAWS** (Windows, payant)
- **VoiceOver** (macOS/iOS, intégré)
- **TalkBack** (Android, intégré)

**3. Test de contraste :**
- Vérifier tous les textes et éléments d'interface
- Tester avec différents modes (clair/sombre)

**4. Test de zoom :**
- Zoomer jusqu'à 200% minimum
- Vérifier que le contenu reste lisible et utilisable
- Pas de débordement horizontal

**5. Test sans images :**
- Désactiver les images dans le navigateur
- Vérifier que les textes alternatifs sont pertinents

### Déclaration d'accessibilité

**Obligation légale** : publier une déclaration d'accessibilité sur le site.

**Contenu obligatoire :**
- État de conformité (non conforme, partiellement conforme, totalement conforme)
- Résultats des tests réalisés
- Contenus non accessibles et justifications
- Schéma pluriannuel de mise en accessibilité (si applicable)
- Moyen de contact pour signaler un problème d'accessibilité
- Lien vers le Défenseur des droits

**Modèle de déclaration :**
```markdown
# Déclaration d'accessibilité

[Nom de l'organisation] s'engage à rendre son site internet accessible conformément à l'article 47 de la loi n° 2005-102 du 11 février 2005.

## État de conformité

Le site [nom du site] est [non conforme / partiellement conforme / totalement conforme] avec le RGAA 4.1.

[Si partiellement conforme, lister les non-conformités]

## Résultats des tests

L'audit de conformité réalisé le [date] révèle que [X]% des critères sont respectés.

## Amélioration et contact

Vous pouvez nous aider à améliorer l'accessibilité du site en nous signalant les problèmes rencontrés :
- Email : [email]
- Téléphone : [téléphone]
- Formulaire de contact : [lien]

## Défenseur des droits

Si vous constatez un défaut d'accessibilité vous empêchant d'accéder à un contenu ou une fonctionnalité du site, que vous nous le signalez et que vous ne parvenez pas à obtenir une réponse rapide de notre part, vous êtes en droit de faire parvenir vos doléances ou une demande de saisine au Défenseur des droits.
```

### Ressources et documentation

**Références officielles :**
- [RGAA 4.1 - Référentiel officiel](https://www.numerique.gouv.fr/publications/rgaa-accessibilite/)
- [AcceDe Web - Guide pratique](https://www.accede-web.com/)
- [WCAG 2.1 Guidelines](https://www.w3.org/WAI/WCAG21/quickref/)
- [Guide de l'intégrateur RGAA](https://disic.github.io/guide-integrateur/)

**Formations et certification :**
- [Access42 - Formations RGAA](https://access42.net/formations)
- [OpenClassrooms - Cours accessibilité](https://openclassrooms.com/)
- Certification **Opquast** (bonnes pratiques web incluant l'accessibilité)

**Communauté et aide :**
- [Forum AccessiWeb](https://www.accessiweb.org/)
- [Groupe Facebook "Accessibilité Numérique"](https://www.facebook.com/groups/accessibilite.numerique/)
- [Stack Overflow - Tag accessibility](https://stackoverflow.com/questions/tagged/accessibility)

### Checklist de conformité RGAA

**Avant de mettre en production :**

- [ ] Toutes les images ont un attribut `alt` approprié
- [ ] La hiérarchie des titres est respectée (h1, h2, h3...)
- [ ] Les contrastes de couleurs respectent les ratios minimum (4.5:1)
- [ ] Le site est entièrement navigable au clavier
- [ ] Les indicateurs de focus sont visibles
- [ ] Les formulaires ont des labels explicites
- [ ] La langue du document est définie (`lang="fr"`)
- [ ] Les liens ont des libellés explicites
- [ ] Le zoom jusqu'à 200% ne casse pas la mise en page
- [ ] Les vidéos ont des sous-titres et transcriptions
- [ ] Les tableaux de données utilisent `<th>` et `scope`
- [ ] Les attributs ARIA sont utilisés correctement
- [ ] Le site fonctionne avec un lecteur d'écran
- [ ] Les messages d'erreur sont explicites et accessibles
- [ ] Un lien d'évitement ("Aller au contenu") est présent
- [ ] La déclaration d'accessibilité est publiée

**Validation technique :**

- [ ] Validateur W3C : 0 erreur HTML
- [ ] Audit Lighthouse : score accessibilité > 90
- [ ] Extension axe DevTools : 0 erreur critique
- [ ] Test WAVE : anomalies corrigées
- [ ] Test navigation clavier : 100% fonctionnel
- [ ] Test lecteur d'écran : parcours complet OK
