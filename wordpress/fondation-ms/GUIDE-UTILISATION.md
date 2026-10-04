# Thème WordPress « Fondation MS » — Guide d'utilisation

Ce guide explique comment installer le thème et mettre à jour le site **sans toucher au code**.

---

## 1. Installation

1. Tableau de bord WordPress › **Apparence › Thèmes › Ajouter › Téléverser un thème**.
2. Choisissez le fichier **`fondation-ms.zip`**, puis **Installer** et **Activer**.
3. Un message bleu apparaît : cliquez sur **Commencer** (ou menu **Fondation MS**).
4. Cliquez sur **Importer le contenu de départ**.
   Cela crée les domaines d'intervention, les projets phares, les premières actualités,
   les photos de la galerie et les menus, à partir du contenu actuel du site.
   Rien n'est supprimé ; relancer l'import ne crée pas de doublons.
5. **Réglages › Général** : choisissez le fuseau horaire **Kinshasa** (pour l'émission « En cours »)
   et la langue **Français**.
6. **Réglages › Permaliens** : choisissez « Titre de la publication » puis **Enregistrer**.

Aucune extension n'est nécessaire.

---

## 2. Où modifier quoi ?

| Je veux modifier… | Où ? |
|---|---|
| Logo, nom du site, icône de l'onglet (favicon) | Apparence › Personnaliser › **Identité du site** |
| Couleurs (vert, rouge, jaune), bouton « Nous contacter » | Personnaliser › Fondation MS › **Couleurs et bouton d'en-tête** |
| Liens du menu, sous-menus | Apparence › **Menus** (emplacement « Menu principal ») |
| Liens des colonnes du pied de page | Apparence › Menus (emplacements « Pied de page — colonne 1 / 2 ») ; le nom du menu sert de titre de colonne |
| E-mail, téléphone, adresses, réseaux sociaux | Personnaliser › Fondation MS › **Coordonnées et réseaux sociaux** |
| Titre, texte, image de fond, boutons de la bannière | Personnaliser › Fondation MS › **Bannière d'accueil** |
| Encadré blanc « À la une » de la bannière | Publier un **article** dans la catégorie **La une** (le plus récent s'affiche) |
| Chiffres clés (+5 000 bénéficiaires…) | Personnaliser › Fondation MS › **Chiffres clés** |
| Présentation, mission, vision, historique, valeurs | Personnaliser › Fondation MS › **La fondation et mot du fondateur** |
| Mot du fondateur (texte, photo, nom) | même section |
| Cartes « Domaines d'intervention » | Menu **Domaines d'intervention** |
| Projets phares | Menu **Projets phares** |
| Fil d'actualité | **Articles › Ajouter** + catégorie **Actualités** |
| Galerie photo et diaporama | Menu **Galerie photo** |
| Programme de la radio | Menu **Programme radio** |
| Nom et site de la radio | Personnaliser › Fondation MS › **Galerie et radio** |
| Cartes « S'engager » | Personnaliser › Fondation MS › **S'engager** |
| Masquer une section ou changer l'ordre | Personnaliser › Fondation MS › **Ordre et affichage des sections** |
| Texte et copyright du pied de page | Personnaliser › Fondation MS › **Pied de page** |

Astuce : connecté en administrateur, survolez une section de l'accueil : un bouton
**« Modifier cette section »** ouvre directement le bon réglage.

---

## 3. Tâches courantes

### Publier une actualité
1. **Articles › Ajouter**.
2. Titre, texte (éditeur de blocs), **Image mise en avant** (colonne de droite).
3. Catégorie **Actualités** (et **La une** pour l'afficher dans la bannière).
4. Facultatif, encadré « Affichage sur l'accueil » :
   - *Étiquette de date* : remplace la date sur les cartes (ex. « Rentrée scolaire 2026-2027 ») ;
   - *Source* : ex. « Radio la Voix de la Mongala ».
5. **Publier**. Les 12 actualités les plus récentes s'affichent sur l'accueil (3 lignes de 4).

La date d'un article se change dans le panneau de droite (« Publier › Date »).
Les articles importés ont la date du jour de l'import : corrigez-la si besoin.

### Ajouter un projet phare
**Projets phares › Ajouter** : titre, texte, **Extrait** (résumé affiché sur la carte),
**Image mise en avant**, **Catégorie** (ex. Mobilité). L'ordre se règle dans **Attributs › Ordre**.

### Ajouter un domaine d'intervention
**Domaines d'intervention › Ajouter** : titre, courte description, puis l'encadré
« Réglages de la carte » : icône, couleur, *Grande carte* (domaine mis en avant à gauche),
liste d'actions (une par ligne : `Titre | Description`), encadré facultatif.

### Ajouter une photo
**Galerie photo › Ajouter** : la légende dans le titre, la photo dans **Image** (à droite), **Publier**.
Les 6 premières (selon l'ordre) forment la galerie ; toutes défilent dans le diaporama, au hasard.

### Saisir le programme de la radio
**Programme radio › Ajouter** pour chaque émission : nom, heure de **début** et de **fin**,
jours de diffusion (aucune case cochée = tous les jours). Le site affiche le programme du jour
et met en évidence l'émission en cours.

### Changer l'image de fond de la bannière
Personnaliser › Fondation MS › Bannière d'accueil › **Image de fond** ; le flou se règle de 0 à 20.

---

## 4. Liens acceptés dans les boutons

- adresse complète : `https://exemple.org/page`
- section de l'accueil : `#mission`, `#domaines`, `#projets`, `#actualites`, `#radio`, `#galerie`, `#engager`, `#contact` (pied de page)
- e-mail : `mailto:contact@exemple.org`

Depuis les autres pages, les liens `#section` ramènent automatiquement à l'accueil.

---

## 5. Contenu à compléter

- Texte du **mot du fondateur**.
- **Programme** et **adresse du site** de la radio.
- **Dates** réelles des actualités importées.
- **Photo du stade** Dominique Sakombi.
- **E-mail**, téléphone et **réseaux sociaux** (pied de page).

---

## 6. Informations techniques

- WordPress 6.0 ou plus, PHP 7.4 ou plus. Aucune extension requise.
- Contenus : Articles (actualités) + 4 types de contenus du thème (Domaines, Projets, Photos, Émissions).
- Réglages : Personnalisateur WordPress (`theme_mod`), valeurs par défaut dans `inc/defaults.php`.
- Compatible avec les extensions de référencement (Yoast, Rank Math) : les métadonnées de partage
  du thème se désactivent alors automatiquement.
- Polices : Google Fonts (Fraunces, Inter).
