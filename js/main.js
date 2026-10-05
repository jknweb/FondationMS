/* =========================================================
   Fondation MS — Interactions
   ========================================================= */
(function () {
  "use strict";

  document.documentElement.classList.remove("no-js");

  var $ = function (sel, ctx) { return (ctx || document).querySelector(sel); };
  var $$ = function (sel, ctx) { return Array.prototype.slice.call((ctx || document).querySelectorAll(sel)); };
  var fmt = new Intl.NumberFormat("fr-FR");

  // Année du pied de page
  var year = $("#year");
  if (year) year.textContent = new Date().getFullYear();

  // --- En-tête & bouton « haut de page » au défilement ----------
  var header = $(".header");
  var toTop = $("#to-top");

  function onScroll() {
    var y = window.scrollY;
    header.classList.toggle("is-scrolled", y > 20);
    toTop.classList.toggle("is-visible", y > 700);
  }
  window.addEventListener("scroll", onScroll, { passive: true });
  onScroll();

  // --- Menu mobile ----------------------------------------
  var burger = $("#burger");
  var nav = $("#nav");

  function closeNav() {
    document.body.classList.remove("nav-open");
    burger.setAttribute("aria-expanded", "false");
    burger.setAttribute("aria-label", "Ouvrir le menu");
  }
  burger.addEventListener("click", function () {
    var open = document.body.classList.toggle("nav-open");
    burger.setAttribute("aria-expanded", String(open));
    burger.setAttribute("aria-label", open ? "Fermer le menu" : "Ouvrir le menu");
  });
  $$("a", nav).forEach(function (a) { a.addEventListener("click", closeNav); });
  document.addEventListener("keydown", function (e) { if (e.key === "Escape") closeNav(); });

  // --- Lien actif dans la navigation ----------------------
  var navLinks = $$(".nav__list a");
  var sections = navLinks
    .map(function (a) { return $(a.getAttribute("href")); })
    .filter(Boolean);

  if ("IntersectionObserver" in window) {
    var navObserver = new IntersectionObserver(function (entries) {
      entries.forEach(function (entry) {
        if (!entry.isIntersecting) return;
        navLinks.forEach(function (a) {
          a.classList.toggle("is-active", a.getAttribute("href") === "#" + entry.target.id);
        });
      });
    }, { rootMargin: "-45% 0px -50% 0px" });
    sections.forEach(function (s) { navObserver.observe(s); });
  }

  // --- Utilitaires d'affichage ---------------------------
  function esc(str) {
    return String(str == null ? "" : str).replace(/[&<>"']/g, function (c) {
      return { "&": "&amp;", "<": "&lt;", ">": "&gt;", '"': "&quot;", "'": "&#39;" }[c];
    });
  }
  var MOIS = ["janvier", "février", "mars", "avril", "mai", "juin", "juillet", "août", "septembre", "octobre", "novembre", "décembre"];
  function dateLabel(a) {
    if (a.label) return a.label;
    if (!a.date) return "Date à préciser";
    var p = a.date.split("-");
    var mois = p[1] ? MOIS[parseInt(p[1], 10) - 1] : "";
    return (p[2] ? parseInt(p[2], 10) + " " : "") + (mois ? mois + " " : "") + p[0];
  }
  function shuffle(list) {
    var a = list.slice();
    for (var i = a.length - 1; i > 0; i--) {
      var j = Math.floor(Math.random() * (i + 1));
      var t = a[i]; a[i] = a[j]; a[j] = t;
    }
    return a;
  }
  var CAT_ICON = {
    "Éducation": "i-book", "Santé": "i-health", "Jeunesse": "i-ball", "Social": "i-heart",
    "Entrepreneuriat": "i-spark", "Infrastructure": "i-bridge", "Économie locale": "i-market"
  };

  // --- Fil d'actualité (12 articles les plus récents) -----
  var newsGrid = $("#news-grid");
  if (newsGrid && window.ARTICLES) {
    var articles = ARTICLES
      .map(function (a, i) { return { a: a, i: i }; })
      .sort(function (x, y) {
        var dx = x.a.date || "", dy = y.a.date || "";
        if (dx && dy && dx !== dy) return dx < dy ? 1 : -1;
        if (dx && !dy) return -1;
        if (!dx && dy) return 1;
        return x.i - y.i;
      })
      .slice(0, 12)
      .map(function (o) { return o.a; });

    newsGrid.innerHTML = articles.map(function (a, i) {
      var icon = CAT_ICON[a.cat] || "i-calendar";
      var media = a.image
        ? '<img src="' + esc(a.image) + '" alt="" loading="lazy">'
        : '<span class="news-card__placeholder news-card__placeholder--' + (i % 3) + '"><svg class="icon" aria-hidden="true"><use href="#' + icon + '"/></svg></span>';
      var titre = a.lien ? '<a href="' + esc(a.lien) + '">' + esc(a.titre) + "</a>" : esc(a.titre);
      return '<article class="news-card reveal">' +
        '<div class="news-card__media">' + media + (a.cat ? '<span class="tag">' + esc(a.cat) + "</span>" : "") + "</div>" +
        '<div class="news-card__body">' +
          '<time class="news-card__date"' + (a.date ? ' datetime="' + esc(a.date) + '"' : "") + ">" +
            '<svg class="icon" aria-hidden="true"><use href="#i-calendar"/></svg>' + esc(dateLabel(a)) + "</time>" +
          "<h3>" + titre + "</h3>" +
          "<p>" + esc(a.resume) + "</p>" +
          (a.source ? '<p class="news-card__source"><svg class="icon" aria-hidden="true"><use href="#i-radio"/></svg>' + esc(a.source) + "</p>" : "") +
          (a.lien ? '<a class="link-arrow news-card__more" href="' + esc(a.lien) + '">Lire la suite</a>' : "") +
        "</div></article>";
    }).join("");
  }

  // --- Galerie « En images » + visionneuse ---------------
  var photos = window.GALERIE || [];
  var thumbs = $("#thumbs");
  var lightbox = null;

  function openLightbox(index) {
    if (!lightbox) {
      lightbox = document.createElement("div");
      lightbox.className = "lightbox";
      lightbox.setAttribute("role", "dialog");
      lightbox.setAttribute("aria-modal", "true");
      lightbox.setAttribute("aria-label", "Photo agrandie");
      lightbox.innerHTML =
        '<button class="lightbox__close" aria-label="Fermer">&times;</button>' +
        '<button class="lightbox__nav lightbox__nav--prev" aria-label="Photo précédente"><svg class="icon" aria-hidden="true"><use href="#i-up"/></svg></button>' +
        '<figure><img alt=""><figcaption></figcaption></figure>' +
        '<button class="lightbox__nav lightbox__nav--next" aria-label="Photo suivante"><svg class="icon" aria-hidden="true"><use href="#i-up"/></svg></button>';
      document.body.appendChild(lightbox);
      lightbox.addEventListener("click", function (e) {
        if (e.target === lightbox || e.target.closest(".lightbox__close")) closeLightbox();
        else if (e.target.closest(".lightbox__nav--prev")) showLightbox(lightbox.index - 1);
        else if (e.target.closest(".lightbox__nav--next")) showLightbox(lightbox.index + 1);
      });
      document.addEventListener("keydown", function (e) {
        if (!lightbox.classList.contains("is-open")) return;
        if (e.key === "Escape") closeLightbox();
        if (e.key === "ArrowLeft") showLightbox(lightbox.index - 1);
        if (e.key === "ArrowRight") showLightbox(lightbox.index + 1);
      });
    }
    lightbox.lastFocus = document.activeElement;
    showLightbox(index);
    lightbox.classList.add("is-open");
    document.body.classList.add("no-scroll");
    $(".lightbox__close", lightbox).focus();
  }
  function showLightbox(index) {
    var n = photos.length;
    lightbox.index = (index + n) % n;
    var p = photos[lightbox.index];
    $("img", lightbox).src = p.image;
    $("img", lightbox).alt = p.legende || "";
    $("figcaption", lightbox).textContent = p.legende || "";
  }
  function closeLightbox() {
    lightbox.classList.remove("is-open");
    document.body.classList.remove("no-scroll");
    if (lightbox.lastFocus) lightbox.lastFocus.focus();
  }

  if (thumbs && photos.length) {
    thumbs.setAttribute("data-n", Math.min(photos.length, 6));
    thumbs.innerHTML = photos.slice(0, 6).map(function (p, i) {
      return '<button class="thumb" data-index="' + i + '" aria-label="Agrandir : ' + esc(p.legende) + '">' +
        '<img src="' + esc(p.image) + '" alt="" loading="lazy"><span class="thumb__zoom" aria-hidden="true">+</span></button>';
    }).join("");
    thumbs.addEventListener("click", function (e) {
      var b = e.target.closest(".thumb");
      if (b) openLightbox(parseInt(b.getAttribute("data-index"), 10));
    });
  }

  // --- Radio Lisala : programme du jour ------------------
  if (window.RADIO && $("#radio-list")) {
    var link = $("#radio-link");
    if (RADIO.site && RADIO.site !== "#") link.href = RADIO.site;
    else { link.removeAttribute("target"); link.setAttribute("aria-disabled", "true"); link.textContent = "Site de la radio bientôt disponible"; link.classList.add("is-disabled"); }
    $("#radio-title").textContent = RADIO.nom || "Radio";
    if (RADIO.logo) {
      var head = $(".radio__head");
      var logo = document.createElement("h2");
      logo.className = "radio__logo";
      logo.id = "radio-title";
      logo.innerHTML = '<img src="' + esc(RADIO.logo) + '" alt="' + esc(RADIO.nom || "Radio") + '">';
      head.parentNode.replaceChild(logo, head);
    }
    $("#radio-note").textContent = RADIO.indicatif ? "Programme indicatif, à confirmer." : "";

    var toMin = function (hhmm) { var p = hhmm.split(":"); return parseInt(p[0], 10) * 60 + parseInt(p[1] || 0, 10); };
    function renderRadio() {
      var now = new Date();
      var m = now.getHours() * 60 + now.getMinutes();
      var current = null;
      $("#radio-list").innerHTML = (RADIO.programme || []).map(function (e) {
        var on = m >= toMin(e.debut) && m < toMin(e.fin);
        var past = m >= toMin(e.fin);
        if (on) current = e;
        return '<li class="radio__item' + (on ? " is-live" : "") + (past ? " is-past" : "") + '">' +
          '<span class="radio__time">' + esc(e.debut) + " – " + esc(e.fin) + "</span>" +
          '<span class="radio__show">' + esc(e.titre) + "</span>" +
          (on ? '<span class="radio__badge">En cours</span>' : "") + "</li>";
      }).join("");
      $("#radio-now").innerHTML = current
        ? '<span class="radio__dot" aria-hidden="true"></span><span>À l\'antenne : <strong>' + esc(current.titre) + "</strong></span>"
        : '<span class="radio__dot radio__dot--off" aria-hidden="true"></span><span>Aucune émission en ce moment</span>';
    }
    renderRadio();
    setInterval(renderRadio, 60000);
  }

  // --- Diaporama aléatoire --------------------------------
  var stage = $("#slideshow-stage");
  if (stage && photos.length) {
    var order = shuffle(photos);
    var dots = $("#slideshow-dots");
    var caption = $("#slideshow-caption");
    var current = 0;
    var timer = null;
    var reduce = window.matchMedia("(prefers-reduced-motion: reduce)").matches;

    stage.innerHTML = order.map(function (p, i) {
      return '<figure class="slide' + (i === 0 ? " is-active" : "") + '" aria-hidden="' + (i !== 0) + '">' +
        '<img src="' + esc(p.image) + '" alt="' + esc(p.legende) + '"' + (i > 1 ? ' loading="lazy"' : "") + "></figure>";
    }).join("");
    dots.innerHTML = order.map(function (p, i) {
      return '<button class="dot' + (i === 0 ? " is-active" : "") + '" aria-label="Photo ' + (i + 1) + '"></button>';
    }).join("");
    caption.textContent = order[0].legende || "";

    function go(i) {
      var slides = $$(".slide", stage), ds = $$(".dot", dots);
      slides[current].classList.remove("is-active"); slides[current].setAttribute("aria-hidden", "true");
      ds[current].classList.remove("is-active");
      current = (i + order.length) % order.length;
      slides[current].classList.add("is-active"); slides[current].setAttribute("aria-hidden", "false");
      ds[current].classList.add("is-active");
      caption.textContent = order[current].legende || "";
    }
    // Saut vers une photo au hasard (différente de l'actuelle)
    function next() {
      if (order.length < 2) return;
      var r;
      do { r = Math.floor(Math.random() * order.length); } while (r === current);
      go(r);
    }
    function start() { if (!reduce && !timer) timer = setInterval(next, 4500); }
    function stop() { clearInterval(timer); timer = null; }

    $("#slide-prev").addEventListener("click", function () { stop(); go(current - 1); start(); });
    $("#slide-next").addEventListener("click", function () { stop(); go(current + 1); start(); });
    dots.addEventListener("click", function (e) {
      var d = e.target.closest(".dot");
      if (d) { stop(); go($$(".dot", dots).indexOf(d)); start(); }
    });
    var show = $("#slideshow");
    show.addEventListener("mouseenter", stop);
    show.addEventListener("mouseleave", start);
    show.addEventListener("focusin", stop);
    show.addEventListener("focusout", start);
    $$(".slide img", stage).forEach(function (img, i) {
      img.style.cursor = "zoom-in";
      img.addEventListener("click", function () { openLightbox(photos.indexOf(order[i])); });
    });
    start();
  }

  // --- Compteurs animés -----------------------------------
  function animateCount(el) {
    var target = parseInt(el.getAttribute("data-count"), 10) || 0;
    var duration = 1600;
    var start = null;
    function step(ts) {
      if (!start) start = ts;
      var p = Math.min((ts - start) / duration, 1);
      var eased = 1 - Math.pow(1 - p, 3);
      el.textContent = fmt.format(Math.round(target * eased));
      if (p < 1) requestAnimationFrame(step);
    }
    requestAnimationFrame(step);
  }

  // --- Apparition au défilement ---------------------------
  var reduceMotion = window.matchMedia("(prefers-reduced-motion: reduce)").matches;
  var animated = $$(".reveal, .bar, [data-count]");

  if ("IntersectionObserver" in window && !reduceMotion) {
    var revealObserver = new IntersectionObserver(function (entries, obs) {
      entries.forEach(function (entry) {
        if (!entry.isIntersecting) return;
        var el = entry.target;
        el.classList.add("is-visible");
        if (el.hasAttribute("data-count")) animateCount(el);
        obs.unobserve(el);
      });
    }, { threshold: 0.15 });
    animated.forEach(function (el) { revealObserver.observe(el); });
  } else {
    animated.forEach(function (el) {
      el.classList.add("is-visible");
      if (el.hasAttribute("data-count")) {
        el.textContent = fmt.format(parseInt(el.getAttribute("data-count"), 10) || 0);
      }
    });
  }
})();
