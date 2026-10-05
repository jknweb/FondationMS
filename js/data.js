/* =========================================================
   Fondation MS — Contenus modifiables
   Modifiez ce fichier pour mettre à jour le site sans toucher au HTML.
   ========================================================= */

/* ---------------------------------------------------------
   FIL D'ACTUALITÉ
   Les 12 articles les plus récents sont affichés (4 par ligne).
   - date   : "AAAA-MM-JJ" ou "AAAA-MM" (sert au tri). Laisser "" si inconnue :
              l'article est alors placé après les articles datés.
   - label  : texte de date affiché (facultatif, sinon calculé depuis la date).
   - cat    : catégorie (Éducation, Santé, Jeunesse, Social, Entrepreneuriat, Infrastructure…).
   - image  : chemin de la photo (facultatif ; sinon un visuel coloré s'affiche).
   - lien   : adresse de l'article complet (facultatif).
   - source : origine de l'information (facultatif).
   --------------------------------------------------------- */
var ARTICLES = [
  {
    date: "2026-09",
    label: "Rentrée 2026-2027",
    cat: "Éducation",
    titre: "Rentrée scolaire 2026-2027 : la Fondation aux côtés des élèves de Lisala",
    resume: "La Fondation a renouvelé ses actions de soutien aux élèves et aux écoles de Lisala, perpétuant son engagement éducatif.",
    source: "Radio la Voix de la Mongala",
    image: "",
    lien: ""
  },
  {
    date: "",
    cat: "Éducation",
    titre: "Opération « Pas une école sans banc »",
    resume: "Une initiative en faveur des infrastructures scolaires pour que chaque élève étudie dans de bonnes conditions.",
    image: "",
    lien: ""
  },
  {
    date: "",
    cat: "Infrastructure",
    titre: "Le pont Kaba, don de la Fondation",
    resume: "Un ouvrage offert par la Fondation pour relier les communautés et faciliter les déplacements.",
    image: "assets/img/pont-kaba.jpg",
    lien: ""
  },
  {
    date: "",
    cat: "Économie locale",
    titre: "Le marché central de Lisala",
    resume: "Un espace d'échanges au service des commerçants et de l'activité économique de la ville.",
    image: "assets/img/marcheLisala.jpg",
    lien: ""
  },
  {
    date: "",
    cat: "Jeunesse",
    titre: "Le stade Dominique Sakombi",
    resume: "Un équipement sportif dédié à la jeunesse.",
    image: "",
    lien: ""
  }
];

/* ---------------------------------------------------------
   GALERIE PHOTOS
   Utilisée par la galerie « En images » et par le diaporama.
   Ajoutez vos photos dans assets/img/ puis une ligne ici.
   --------------------------------------------------------- */
var GALERIE = [
  { image: "assets/img/mol.jpg",          legende: "Molendo Sakombi, fondateur de la Fondation MS" },
  { image: "assets/img/pont-kaba.jpg",    legende: "Le pont Kaba, don de la Fondation" },
  { image: "assets/img/marcheLisala.jpg", legende: "Le marché central de Lisala" },
  { image: "assets/img/molendo.webp",     legende: "Molendo Sakombi" }
];

/* ---------------------------------------------------------
   RADIO LISALA
   - site       : adresse du site de la radio.
   - programme  : émissions de la journée (heure de début "HH:MM", heure de fin, titre).
   - indicatif  : true tant que le programme n'est pas confirmé
                  (affiche la mention « Programme indicatif, à confirmer »).
   ATTENTION : le programme ci-dessous est un EXEMPLE à remplacer.
   --------------------------------------------------------- */
var RADIO = {
  nom: "Radio Lisala",
  // Logo : déposez le fichier dans assets/img/ puis indiquez son chemin, ex. "assets/img/radio-logo.png".
  // Il remplace l'icône, « Partenaire média » et le nom. Laisser "" pour garder l'affichage actuel.
  logo: "",
  site: "#",
  indicatif: true,
  programme: [
    { debut: "06:00", fin: "08:00", titre: "La matinale" },
    { debut: "08:00", fin: "09:00", titre: "Journal du matin" },
    { debut: "09:00", fin: "12:00", titre: "Magazine communautaire" },
    { debut: "12:00", fin: "13:00", titre: "Journal de la mi-journée" },
    { debut: "13:00", fin: "17:00", titre: "Musique et dédicaces" },
    { debut: "17:00", fin: "18:00", titre: "Espace jeunesse" },
    { debut: "18:00", fin: "19:00", titre: "Journal du soir" },
    { debut: "19:00", fin: "22:00", titre: "Soirée musicale" }
  ]
};
