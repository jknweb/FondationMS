/* =========================================================
   Fondation MS — Interactions
   ========================================================= */
(function () {
  "use strict";

  document.documentElement.classList.remove("no-js");

  // --- Paramètres à adapter -------------------------------
  // Lien vers votre plateforme de paiement (HelloAsso, Stripe, Donorbox…).
  // Le montant et la fréquence sont ajoutés en paramètres d'URL.
  var DONATION_URL = "https://www.helloasso.com/";
  var CONTACT_EMAIL = "contact@fondation-ms.org";
  var TAX_RATE = 0.66; // réduction d'impôt particuliers (France)

  // Ce que permet chaque montant (du plus petit au plus grand).
  var IMPACTS = [
    { min: 0,   text: function (a) { return "Avec " + a + " €, vous financez des fournitures scolaires pour un enfant."; } },
    { min: 20,  text: function (a) { return "Avec " + a + " €, vous offrez un kit scolaire complet à un enfant."; } },
    { min: 50,  text: function (a) { return "Avec " + a + " €, vous offrez un kit scolaire complet à 2 enfants."; } },
    { min: 100, text: function (a) { return "Avec " + a + " €, vous financez une consultation médicale pour 10 personnes."; } },
    { min: 250, text: function (a) { return "Avec " + a + " €, vous financez la formation d'une femme entrepreneure."; } },
    { min: 500, text: function (a) { return "Avec " + a + " €, vous financez une année de scolarité pour 3 enfants."; } }
  ];
  // --------------------------------------------------------

  var $ = function (sel, ctx) { return (ctx || document).querySelector(sel); };
  var $$ = function (sel, ctx) { return Array.prototype.slice.call((ctx || document).querySelectorAll(sel)); };
  var fmt = new Intl.NumberFormat("fr-FR");

  // Année du pied de page
  var year = $("#year");
  if (year) year.textContent = new Date().getFullYear();

  // --- En-tête & boutons flottants au défilement ----------
  var header = $(".header");
  var toTop = $("#to-top");
  var sticky = $("#sticky-donate");
  var donSection = $("#don");

  function onScroll() {
    var y = window.scrollY;
    header.classList.toggle("is-scrolled", y > 20);
    toTop.classList.toggle("is-visible", y > 700);

    var nearDonate = false;
    if (donSection) {
      var r = donSection.getBoundingClientRect();
      nearDonate = r.top < window.innerHeight && r.bottom > 0;
    }
    sticky.classList.toggle("is-visible", y > 600 && !nearDonate);
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

  // --- Filtre des projets ---------------------------------
  var filters = $$(".filter");
  var projects = $$(".project");
  filters.forEach(function (btn) {
    btn.addEventListener("click", function () {
      var cat = btn.getAttribute("data-filter");
      filters.forEach(function (b) { b.classList.toggle("is-active", b === btn); });
      projects.forEach(function (p) {
        p.classList.toggle("is-hidden", cat !== "all" && p.getAttribute("data-cat") !== cat);
      });
    });
  });

  // --- Module de don --------------------------------------
  var donateForm = $("#donate-form");
  if (donateForm) {
    var customInput = $("#custom-amount");
    var impactText = $("#impact-text");
    var realCost = $("#real-cost");
    var donateBtn = $("#donate-btn");

    function currentAmount() {
      var custom = parseInt(customInput.value, 10);
      if (custom > 0) return custom;
      var checked = $('input[name="amount"]:checked', donateForm);
      return checked ? parseInt(checked.value, 10) : 0;
    }
    function isMonthly() {
      return $('input[name="freq"]:checked', donateForm).value === "monthly";
    }

    function updateDonate() {
      var amount = currentAmount();
      var monthly = isMonthly();
      var suffix = monthly ? " par mois" : "";

      var impact = IMPACTS[0];
      IMPACTS.forEach(function (i) { if (amount >= i.min) impact = i; });
      impactText.textContent = amount > 0
        ? impact.text(fmt.format(amount)) + (monthly ? " Et cela, chaque mois." : "")
        : "Choisissez un montant pour voir son impact.";

      realCost.textContent = fmt.format(Math.round(amount * (1 - TAX_RATE))) + " €" + suffix;
      donateBtn.textContent = amount > 0
        ? "Je donne " + fmt.format(amount) + " €" + suffix
        : "Je fais un don";
    }

    $$('input[name="amount"]', donateForm).forEach(function (r) {
      r.addEventListener("change", function () { customInput.value = ""; updateDonate(); });
    });
    $$('input[name="freq"]', donateForm).forEach(function (r) { r.addEventListener("change", updateDonate); });
    customInput.addEventListener("input", function () {
      if (customInput.value) $$('input[name="amount"]', donateForm).forEach(function (r) { r.checked = false; });
      customInput.classList.remove("is-invalid");
      updateDonate();
    });

    donateForm.addEventListener("submit", function (e) {
      e.preventDefault();
      var amount = currentAmount();
      if (!amount) { customInput.classList.add("is-invalid"); customInput.focus(); return; }
      var url = DONATION_URL + (DONATION_URL.indexOf("?") > -1 ? "&" : "?") +
        "amount=" + amount + "&frequency=" + (isMonthly() ? "monthly" : "once");
      window.open(url, "_blank", "noopener");
    });

    updateDonate();
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

  var newsletter = $("#newsletter-form");
  if (newsletter) {
    newsletter.addEventListener("submit", function (e) {
      e.preventDefault();
      var input = $("input", newsletter);
      if (!validate(newsletter)) return;
      // À connecter à votre outil d'e-mailing (Brevo, Mailchimp…).
      input.value = "";
      input.placeholder = "Merci, vous êtes inscrit·e !";
    });
  }
})();
