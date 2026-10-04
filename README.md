# Fondation MS — Site web

Site vitrine statique (HTML / CSS / JS, sans dépendance) : responsive, accessible, prêt à héberger
sur GitHub Pages, Netlify, OVH, etc.

## Structure

```
index.html          Page unique (toutes les sections)
css/style.css       Styles — couleurs et polices dans :root en haut du fichier
js/main.js          Menu mobile, compteurs, filtres, module de don, formulaires
assets/img/         Images (favicon fourni, photos à ajouter)
```

## Sections

Hero (accroche + chiffres clés + campagne en cours) · Partenaires · Mission & valeurs ·
Nos actions (4 axes) · Impact chiffré + répartition des dons · Projets filtrables avec jauge ·
Module de don (ponctuel / mensuel, impact concret, coût après réduction d'impôt) ·
Témoignages · S'engager (don, bénévolat, mécénat) · Actualités · FAQ · Contact · Newsletter.

## À personnaliser avant mise en ligne

Tous les textes, chiffres, noms et coordonnées sont **des exemples** à remplacer.

1. **Contenu** (`index.html`) : mission, année de création, chiffres (`data-count="…"`),
   projets et montants, témoignages, actualités, FAQ, adresse, e-mail, téléphone, réseaux sociaux.
   Retirez « reconnue d'utilité publique » et la mention de réduction d'impôt si elles ne s'appliquent pas.
2. **Photos** : déposez dans `assets/img/` — `hero.jpg`, `mission.jpg`, `projet-1.jpg` à `projet-3.jpg`,
   `actu-1.jpg` à `actu-3.jpg`, `og-image.jpg` (1200×630). Sans photo, un dégradé s'affiche.
3. **Logos partenaires** : remplacez `<li>Partenaire 1</li>` par `<li><img src="assets/img/logo.png" alt="Nom"></li>`.
4. **Don** (`js/main.js`) : `DONATION_URL` → lien de votre page HelloAsso / Stripe / Donorbox ;
   adaptez les phrases d'impact dans `IMPACTS`.
5. **Contact** : `CONTACT_EMAIL` dans `js/main.js`. Le formulaire ouvre la messagerie du visiteur ;
   pour un envoi direct, branchez Formspree ou Netlify Forms.
6. **Couleurs** : variables `--primary`, `--accent`, etc. en haut de `css/style.css`.

## Aperçu local

Ouvrez `index.html` dans un navigateur, ou lancez `python3 -m http.server` puis http://localhost:8000.

## Mise en ligne avec GitHub Pages

Settings → Pages → Source : branche `main`, dossier `/ (root)`.
