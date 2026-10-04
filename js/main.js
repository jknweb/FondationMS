/* =========================================================
   Fondation MS — Interactions
   ========================================================= */
(function () {
  "use strict";

  document.documentElement.classList.remove("no-js");

  var CONTACT_EMAIL = "contact@fondation-ms.org"; // à remplacer par l'adresse réelle

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

  // --- Pré-sélection du sujet de contact ------------------
  var subject = $("#subject");
  $$("[data-subject]").forEach(function (a) {
    a.addEventListener("click", function () {
      if (subject) subject.value = a.getAttribute("data-subject");
    });
  });

  // --- Validation simple des formulaires ------------------
  function validate(form) {
    var ok = true;
    $$("[required]", form).forEach(function (field) {
      var valid = field.type === "checkbox" ? field.checked : field.checkValidity() && field.value.trim() !== "";
      field.classList.toggle("is-invalid", !valid);
      if (!valid && ok) { field.focus(); ok = false; }
    });
    return ok;
  }

  var contactForm = $("#contact-form");
  if (contactForm) {
    var status = $("#form-status");
    contactForm.addEventListener("submit", function (e) {
      e.preventDefault();
      if (!validate(contactForm)) {
        status.textContent = "Merci de compléter les champs obligatoires.";
        status.className = "form__status err";
        return;
      }
      // Sans serveur : ouverture du client mail pré-rempli.
      // Pour un envoi direct, branchez Formspree, Netlify Forms, etc.
      var data = new FormData(contactForm);
      var body = data.get("message") + "\n\n— " + data.get("name") + " (" + data.get("email") + ")";
      var label = subject.options[subject.selectedIndex].text;
      window.location.href = "mailto:" + CONTACT_EMAIL +
        "?subject=" + encodeURIComponent("[Site] " + label) +
        "&body=" + encodeURIComponent(body);
      status.textContent = "Merci ! Votre messagerie va s'ouvrir pour finaliser l'envoi.";
      status.className = "form__status ok";
    });
  }

})();
