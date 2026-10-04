# Fondation MS — Site web

Site vitrine statique (HTML / CSS / JS, sans dépendance) : responsive, accessible, prêt à héberger
sur GitHub Pages, Netlify, OVH, etc.

## Structure

```
index.html          Page unique (toutes les sections)
css/style.css       Styles — couleurs et polices dans :root en haut du fichier
js/main.js          Menu mobile, animations, formulaire de contact
assets/img/         Images (favicon fourni, photos à ajouter)
```

## Sections

Accueil (accroche + « À la une » : rentrée scolaire 2026-2027) · En bref (implantations, domaines, projets) ·
La fondation (Lisala & Kinshasa) · Domaines d'intervention (Éducation, Autonomisation) ·
Projets phares (stade Dominique Sakombi, marché central de Lisala, pont Kaba) ·
Activités en chronologie · S'engager (bénévolat, partenariat) · Contact.

## À compléter

1. **Dates des activités** : dans la section « Activités » (`index.html`), remplacez « Date à préciser »
   et classez les éléments du plus récent au plus ancien. Pour ajouter une activité, copiez un
   `<li class="timeline__item">`.
2. **Photos** dans `assets/img/` : `hero.jpg`, `mission.jpg`, `stade-sakombi.jpg`, `marche-lisala.jpg`,
   `pont-kaba.jpg`, `og-image.jpg` (1200×630). Sans photo, un dégradé s'affiche.
3. **E-mail** : `contact@fondation-ms.org` est un exemple — remplacez-le dans `index.html` et
   `CONTACT_EMAIL` dans `js/main.js`. Ajoutez adresse précise / téléphone si souhaité.
4. **Réseaux sociaux** : liens du pied de page.
5. **Couleurs** : vert, jaune, rouge — variables `--primary`, `--gold`, `--accent` en haut de `css/style.css`.

## Aperçu local

Ouvrez `index.html` dans un navigateur, ou lancez `python3 -m http.server` puis http://localhost:8000.

## Mise en ligne avec GitHub Pages

Settings → Pages → Source : branche `main`, dossier `/ (root)`.
