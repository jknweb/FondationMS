/* =========================================================
   Fondation MS — Interactions du thème
   (menu, compteurs, animations, onglets, visionneuse, diaporama, radio)
   ========================================================= */
(function () {
  "use strict";

  var CFG = window.FMS || {};
  var T = CFG.i18n || {};

  document.documentElement.classList.remove("no-js");
  document.body.classList.remove("no-js");

  var $ = function (sel, ctx) { return (ctx || document).querySelector(sel); };
  var $$ = function (sel, ctx) { return Array.prototype.slice.call((ctx || document).querySelectorAll(sel)); };
  var fmt = new Intl.NumberFormat(document.documentElement.lang || "fr-FR");
  var reduceMotion = window.matchMedia("(prefers-reduced-motion: reduce)").matches;

  // --- En-tête & bouton « haut de page » ------------------
  var header = $(".header");
  var toTop = $("#to-top");
  function onScroll() {
    var y = window.scrollY;
    if (header) header.classList.toggle("is-scrolled", y > 20);
    if (toTop) toTop.classList.toggle("is-visible", y > 700);
  }
  window.addEventListener("scroll", onScroll, { passive: true });
  onScroll();

  // --- Menu mobile et sous-menus --------------------------
  var burger = $("#burger");
  var nav = $("#nav");
  function setNav(open) {
    document.body.classList.toggle("nav-open", open);
    if (burger) {
      burger.setAttribute("aria-expanded", String(open));
      burger.setAttribute("aria-label", open ? (T.closeMenu || "Fermer le menu") : (T.openMenu || "Ouvrir le menu"));
    }
  }
  if (burger && nav) {
    burger.addEventListener("click", function () { setNav(!document.body.classList.contains("nav-open")); });
    $$("a", nav).forEach(function (a) { a.addEventListener("click", function () { setNav(false); }); });
    document.addEventListener("keydown", function (e) { if (e.key === "Escape") setNav(false); });
  }
  $$(".submenu-toggle").forEach(function (btn) {
    btn.addEventListener("click", function () {
      var li = btn.parentElement;
      var open = !li.classList.contains("is-open");
      li.classList.toggle("is-open", open);
      btn.setAttribute("aria-expanded", String(open));
    });
  });

  // --- Lien actif dans le menu (sections de l'accueil) ----
  var navLinks = $$(".nav__list a[href*='#']");
  if ("IntersectionObserver" in window && navLinks.length) {
    var sections = navLinks
      .map(function (a) {
        var id = a.getAttribute("href").split("#")[1];
        return id ? document.getElementById(id) : null;
      })
      .filter(Boolean);
    var navObserver = new IntersectionObserver(function (entries) {
      entries.forEach(function (entry) {
        if (!entry.isIntersecting) return;
        navLinks.forEach(function (a) {
          a.classList.toggle("is-active", a.getAttribute("href").split("#")[1] === entry.target.id);
        });
      });
    }, { rootMargin: "-45% 0px -50% 0px" });
    sections.forEach(function (s) { navObserver.observe(s); });
  }

  // --- Onglets Mission / Vision / Historique --------------
  $$("[data-tabs]").forEach(function (box) {
    var tabs = $$("[role=tab]", box);
    tabs.forEach(function (tab, i) {
      tab.addEventListener("click", function () { select(i); });
      tab.addEventListener("keydown", function (e) {
        if (e.key === "ArrowRight") { select((i + 1) % tabs.length); tabs[(i + 1) % tabs.length].focus(); }
        if (e.key === "ArrowLeft") { select((i - 1 + tabs.length) % tabs.length); tabs[(i - 1 + tabs.length) % tabs.length].focus(); }
      });
    });
    function select(index) {
      tabs.forEach(function (t, j) {
        t.setAttribute("aria-selected", String(j === index));
        t.tabIndex = j === index ? 0 : -1;
        var panel = document.getElementById(t.getAttribute("aria-controls"));
        if (panel) panel.hidden = j !== index;
      });
    }
  });

  // --- Compteurs animés -----------------------------------
  function animateCount(el) {
    var target = parseInt(el.getAttribute("data-count"), 10) || 0;
    var start = null;
    function step(ts) {
      if (!start) start = ts;
      var p = Math.min((ts - start) / 1600, 1);
      el.textContent = fmt.format(Math.round(target * (1 - Math.pow(1 - p, 3))));
      if (p < 1) requestAnimationFrame(step);
    }
    requestAnimationFrame(step);
  }

  // --- Apparition au défilement ---------------------------
  var animated = $$(".reveal, [data-count]");
  if ("IntersectionObserver" in window && !reduceMotion) {
    var revealObserver = new IntersectionObserver(function (entries, obs) {
      entries.forEach(function (entry) {
        if (!entry.isIntersecting) return;
        entry.target.classList.add("is-visible");
        if (entry.target.hasAttribute("data-count")) animateCount(entry.target);
        obs.unobserve(entry.target);
      });
    }, { threshold: 0.15 });
    animated.forEach(function (el) { revealObserver.observe(el); });
  } else {
    animated.forEach(function (el) { el.classList.add("is-visible"); });
  }

  // --- Visionneuse (photos de la galerie et du diaporama) -
  var lightbox = null;
  var lbItems = [];
  function buildLightbox() {
    lightbox = document.createElement("div");
    lightbox.className = "lightbox";
    lightbox.setAttribute("role", "dialog");
    lightbox.setAttribute("aria-modal", "true");
    lightbox.innerHTML =
      '<button type="button" class="lightbox__close" aria-label="' + (T.close || "Fermer") + '">&times;</button>' +
      '<button type="button" class="lightbox__nav lightbox__nav--prev" aria-label="' + (T.prev || "") + '"><svg class="icon" aria-hidden="true"><use href="#i-up"></use></svg></button>' +
      '<figure><img alt=""><figcaption></figcaption></figure>' +
      '<button type="button" class="lightbox__nav lightbox__nav--next" aria-label="' + (T.next || "") + '"><svg class="icon" aria-hidden="true"><use href="#i-up"></use></svg></button>';
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
  function openLightbox(items, index) {
    if (!lightbox) buildLightbox();
    lbItems = items;
    lightbox.lastFocus = document.activeElement;
    showLightbox(index);
    lightbox.classList.add("is-open");
    document.body.classList.add("no-scroll");
    $(".lightbox__close", lightbox).focus();
  }
  function showLightbox(index) {
    var n = lbItems.length;
    lightbox.index = (index + n) % n;
    var item = lbItems[lightbox.index];
    $("img", lightbox).src = item.src;
    $("img", lightbox).alt = item.caption || "";
    $("figcaption", lightbox).textContent = item.caption || "";
    $$(".lightbox__nav", lightbox).forEach(function (b) { b.hidden = n < 2; });
  }
  function closeLightbox() {
    lightbox.classList.remove("is-open");
    document.body.classList.remove("no-scroll");
    if (lightbox.lastFocus) lightbox.lastFocus.focus();
  }
  var thumbLinks = $$("[data-lightbox]");
  if (thumbLinks.length) {
    var thumbItems = thumbLinks.map(function (a) { return { src: a.getAttribute("href"), caption: a.getAttribute("data-caption") }; });
    thumbLinks.forEach(function (a, i) {
      a.addEventListener("click", function (e) { e.preventDefault(); openLightbox(thumbItems, i); });
    });
  }

  // --- Diaporama aléatoire --------------------------------
  $$("[data-slideshow]").forEach(function (show) {
    var stage = $(".slideshow__stage", show);
    var slides = $$(".slide", stage);
    var dots = $$(".dot", show);
    var caption = $(".slideshow__caption", show);
    if (!slides.length) return;

    // Mélange l'ordre d'affichage (la page peut être mise en cache : on remélange côté visiteur).
    var order = slides.map(function (s, i) { return i; });
    for (var i = order.length - 1; i > 0; i--) {
      var j = Math.floor(Math.random() * (i + 1));
      var t = order[i]; order[i] = order[j]; order[j] = t;
    }
    var current = 0;
    var timer = null;

    function go(pos) {
      current = (pos + order.length) % order.length;
      var to = order[current];
      slides.forEach(function (s, k) {
        s.classList.toggle("is-active", k === to);
        s.setAttribute("aria-hidden", String(k !== to));
      });
      dots.forEach(function (d, k) { d.classList.toggle("is-active", k === current); });
      if (caption) caption.textContent = slides[to].getAttribute("data-caption") || "";
      var img = $("img", slides[to]);
      if (img && img.loading === "lazy") img.loading = "eager";
    }
    function next() {
      if (order.length < 2) return;
      var r;
      do { r = Math.floor(Math.random() * order.length); } while (r === current);
      go(r);
    }
    function start() { if (!reduceMotion && !timer && order.length > 1) timer = setInterval(next, CFG.slideDelay || 5000); }
    function stop() { clearInterval(timer); timer = null; }

    go(0);
    var prev = $("[data-prev]", show), nextBtn = $("[data-next]", show);
    if (prev) prev.addEventListener("click", function () { stop(); go(current - 1); start(); });
    if (nextBtn) nextBtn.addEventListener("click", function () { stop(); go(current + 1); start(); });
    dots.forEach(function (d, k) { d.addEventListener("click", function () { stop(); go(k); start(); }); });
    show.addEventListener("mouseenter", stop);
    show.addEventListener("mouseleave", start);
    show.addEventListener("focusin", stop);
    show.addEventListener("focusout", start);

    var items = slides.map(function (s) { var img = $("img", s); return { src: img ? (img.currentSrc || img.src) : "", caption: s.getAttribute("data-caption") }; });
    slides.forEach(function (s, k) {
      var img = $("img", s);
      if (!img) return;
      img.style.cursor = "zoom-in";
      img.addEventListener("click", function () { openLightbox(items, k); });
    });
    start();
  });

  // --- Radio : émission en cours (à l'heure du site) ------
  var radioList = $("[data-radio-list]");
  if (radioList) {
    var now = $("[data-radio-now]");
    var toMin = function (hhmm) { var p = (hhmm || "0:0").split(":"); return parseInt(p[0], 10) * 60 + parseInt(p[1] || 0, 10); };
    var esc = function (s) { var d = document.createElement("div"); d.textContent = s; return d.innerHTML; };
    var renderRadio = function () {
      var d = new Date();
      var utc = d.getUTCHours() * 60 + d.getUTCMinutes();
      var m = ((utc + (CFG.tzOffset || 0)) % 1440 + 1440) % 1440;
      var live = null;
      $$(".radio__item", radioList).forEach(function (li) {
        var s = toMin(li.getAttribute("data-start")), e = toMin(li.getAttribute("data-end"));
        if (e <= s) e += 1440; // émission qui passe minuit
        var mm = m < s && e > 1440 ? m + 1440 : m;
        var on = mm >= s && mm < e;
        li.classList.toggle("is-live", on);
        li.classList.toggle("is-past", !on && mm >= e);
        var badge = $(".radio__badge", li);
        if (on && !badge) li.insertAdjacentHTML("beforeend", '<span class="radio__badge">' + esc(T.live || "En cours") + "</span>");
        if (!on && badge) badge.remove();
        if (on) live = $(".radio__show", li).textContent;
      });
      if (now) {
        now.innerHTML = live
          ? '<span class="radio__dot" aria-hidden="true"></span><span>' + esc(T.onAir || "") + " <strong>" + esc(live) + "</strong></span>"
          : '<span class="radio__dot radio__dot--off" aria-hidden="true"></span><span>' + esc(T.offAir || "") + "</span>";
      }
    };
    renderRadio();
    setInterval(renderRadio, 60000);
  }
})();
