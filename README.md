# Fondation MS — Site web

Site vitrine statique (HTML / CSS / JS, sans dépendance) : responsive, accessible, prêt à héberger
sur GitHub Pages, Netlify, OVH, etc.

## Structure

```
index.html          Page unique (toutes les sections)
css/style.css       Styles — couleurs et polices dans :root en haut du fichier
js/data.js          CONTENUS À MODIFIER : articles, photos de la galerie, programme radio
js/main.js          Interactions (menu, compteurs, actualités, galerie, diaporama, radio)
assets/img/         Images
```

## Sections

Accueil (« À la une ») · Chiffres clés · La fondation + mot du fondateur ·
Domaines d'intervention (Éducation, Santé, Jeunesse, Social, Entrepreneuriat) ·
Projets phares (stade Dominique Sakombi, marché central de Lisala, pont Kaba) ·
Fil d'actualité (12 derniers articles, 4 par ligne) · Galerie + Radio Lisala ·
S'engager · Diaporama photo aléatoire.

## Mettre à jour le contenu (js/data.js)

- **Articles** : ajoutez un bloc dans `ARTICLES` (date `AAAA-MM-JJ`, catégorie, titre, résumé, image).
  Les 12 plus récents s'affichent automatiquement.
- **Photos** : copiez l'image dans `assets/img/`, puis ajoutez une ligne dans `GALERIE`.
  Elle apparaît dans la galerie et dans le diaporama.
- **Radio** : `RADIO.site` = adresse du site de la radio ; `RADIO.programme` = émissions du jour.
  Le programme actuel est un EXEMPLE ; mettez `indicatif: false` une fois le vrai programme saisi.

## À compléter

1. **Mot du fondateur** : texte dans `index.html` (bloc `<blockquote class="founder__quote">`).
2. **Programme et site de Radio Lisala** : `js/data.js`.
3. **Dates des articles** : `js/data.js`.
4. **Photo du stade** : `assets/img/stade-sakombi.jpg`.
5. **E-mail** : `contact@fondation-ms.org` est un exemple (pied de page et boutons « S'engager »).
6. **Réseaux sociaux** : liens du pied de page.

## Aperçu local

Ouvrez `index.html` dans un navigateur, ou lancez `python3 -m http.server` puis http://localhost:8000.

## Mise en ligne avec GitHub Pages

Settings → Pages → Source : branche `main`, dossier `/ (root)`.
