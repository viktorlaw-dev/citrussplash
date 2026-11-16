// Citrus Splash — depth: pointer tilt on cards + hero parallax.
// Dependency-free. Skips touch devices and reduced-motion users entirely.
// Ported 1:1 from the prototype (it already respects reduced motion —
// that was the DBD §5 "known gap", closed in the current prototype).
(function () {
  "use strict";

  var mq = window.matchMedia;
  var reduce = mq && mq("(prefers-reduced-motion: reduce)").matches;
  if (reduce) return;

  var fine = !mq || mq("(hover: hover) and (pointer: fine)").matches;

  /* ---------------- tilt ---------------- */
  /* Delegated, so menu cards keep tilting after search/filter re-renders. */
  if (fine) {
    var MAX = 7; // degrees at the card edge
    var active = null;
    var raf = 0;
    var rx = 0;
    var ry = 0;

    function apply() {
      raf = 0;
      if (!active) return;
      active.style.setProperty("--rx", rx.toFixed(2) + "deg");
      active.style.setProperty("--ry", ry.toFixed(2) + "deg");
    }

    function reset(el) {
      el.style.setProperty("--rx", "0deg");
      el.style.setProperty("--ry", "0deg");
    }

    document.addEventListener(
      "pointermove",
      function (e) {
        var el = e.target && e.target.closest ? e.target.closest("[data-tilt]") : null;

        if (el !== active) {
          if (active) reset(active);
          active = el;
        }
        if (!el) return;

        var r = el.getBoundingClientRect();
        if (!r.width || !r.height) return;

        var px = (e.clientX - r.left) / r.width - 0.5;
        var py = (e.clientY - r.top) / r.height - 0.5;
        ry = px * MAX * 2;
        rx = -py * MAX * 2;
        if (!raf) raf = window.requestAnimationFrame(apply);
      },
      { passive: true }
    );

    document.addEventListener("pointerout", function (e) {
      if (active && !active.contains(e.relatedTarget)) {
        reset(active);
        active = null;
      }
    });

    window.addEventListener("blur", function () {
      if (active) reset(active);
      active = null;
    });
  }

  /* ---------------- hero parallax ---------------- */
  var hero = document.querySelector(".hero");
  if (!hero || !fine) return;

  var layers = [];
  Array.prototype.forEach.call(hero.querySelectorAll("[data-depth]"), function (el) {
    layers.push({ el: el, depth: parseFloat(el.getAttribute("data-depth")) || 8 });
  });
  if (!layers.length) return;

  var targetX = 0;
  var targetY = 0;
  var curX = 0;
  var curY = 0;
  var visible = true;
  var raf = 0;

  function tick() {
    raf = 0;
    if (!visible) return;

    curX += (targetX - curX) * 0.08;
    curY += (targetY - curY) * 0.08;

    var scrollProgress = Math.min(window.scrollY || 0, 520) / 520;

    layers.forEach(function (layer) {
      var x = curX * layer.depth;
      var y = curY * layer.depth - scrollProgress * layer.depth * 0.8;
      layer.el.style.translate = x.toFixed(2) + "px " + y.toFixed(2) + "px";
    });

    var moving =
      Math.abs(targetX - curX) > 0.002 ||
      Math.abs(targetY - curY) > 0.002 ||
      Math.abs(targetX) > 0.002 ||
      Math.abs(targetY) > 0.002;

    if (moving) schedule();
  }

  function schedule() {
    if (!raf && visible) raf = window.requestAnimationFrame(tick);
  }

  hero.addEventListener(
    "pointermove",
    function (e) {
      var r = hero.getBoundingClientRect();
      if (!r.width || !r.height) return;
      targetX = ((e.clientX - r.left) / r.width - 0.5) * 2;
      targetY = ((e.clientY - r.top) / r.height - 0.5) * 2;
      schedule();
    },
    { passive: true }
  );

  hero.addEventListener("pointerleave", function () {
    targetX = 0;
    targetY = 0;
    schedule();
  });

  window.addEventListener("scroll", schedule, { passive: true });

  if (typeof IntersectionObserver !== "undefined") {
    new IntersectionObserver(function (entries) {
      visible = entries[0].isIntersecting;
      if (visible) schedule();
    }, { threshold: 0 }).observe(hero);
  }
})();
