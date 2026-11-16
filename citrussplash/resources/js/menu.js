// Citrus Splash — Menu page: render sections from CS_MENU, sticky tabs, live search.
// Ported 1:1 from the prototype; the only change is the shared esc() import (rule F6).
import { esc } from "./esc.js";

(function () {
  const DATA = (window.CS_MENU && window.CS_MENU.items) || [];
  const CATS = (window.CS_MENU && window.CS_MENU.cats) || [];
  const N = (n) => "₦" + Number(n).toLocaleString("en-NG");

  const $ = (s, r) => (r || document).querySelector(s);
  const $$ = (s, r) => Array.from((r || document).querySelectorAll(s));

  const sectionsEl = $("#menuSections");
  const tabsEl = $("#menuTabs");
  const searchEl = $("#menuSearch");
  const clearEl = $("#searchClear");
  const countEl = $("#resultCount");
  const emptyEl = $("#menuEmpty");
  if (!sectionsEl || !tabsEl) return;

  let activeCat = "all";
  let query = "";

  const VISUAL = { smoothies: "juice", sandwiches: "sand", salads: "salad" };
  const CATLABEL = {};
  CATS.forEach((c) => { CATLABEL[c.id] = c.label; });

  function initials(name) {
    const w = name.split(/\s+/).filter(Boolean);
    return ((w[0] || "C")[0] + ((w[1] || "S")[0] || "")).toUpperCase();
  }
  function badge(tag) {
    if (tag === "pop") return '<span class="badge pop">Popular</span>';
    if (tag === "new") return '<span class="badge new">New</span>';
    if (tag === "fresh") return '<span class="badge veg">Fresh</span>';
    return "";
  }
  // Wrap the current search query in <mark> so matches glow as you type
  function hi(text, q) {
    const safe = esc(text);
    if (!q) return safe;
    let rx = null;
    try { rx = new RegExp("(" + q.replace(/[.*+?^${}()|[\]\\]/g, "\\$&") + ")", "ig"); }
    catch (e) { return safe; }
    return safe.replace(rx, "<mark>$1</mark>");
  }
  function card(it, q, i) {
    const d = Math.min(i || 0, 8) * 45;
    return (
      '<article class="card enter" data-tilt style="animation-delay:' + d + 'ms" data-item="' + it.id + '">' +
        '<div class="card-visual ' + (VISUAL[it.cat] || "juice") + '"><span class="mono">' + initials(it.name) +
        '</span><span class="emoji">' + (it.emoji || "🍊") + "</span>" + badge(it.tag) + "</div>" +
        '<div class="card-body">' +
          '<span class="card-cat">' + (CATLABEL[it.cat] || it.cat) + "</span>" +
          "<h3 class='card-title'>" + hi(it.name, q) + "</h3>" +
          '<p class="card-desc">' + hi(it.desc, q) + "</p>" +
          '<div class="card-foot">' +
            '<span class="price">' + N(it.price) + "</span>" +
            '<button class="add-btn" data-add="' + it.id + '">Add +</button>' +
          "</div>" +
        "</div>" +
      "</article>"
    );
  }

  function matches(it) {
    if (activeCat !== "all" && it.cat !== activeCat) return false;
    if (!query) return true;
    return (it.name + " " + it.desc).toLowerCase().indexOf(query) !== -1;
  }

  function catCount(id) { return DATA.filter((it) => it.cat === id).length; }

  function renderTabs() {
    const all = DATA.length;
    let html = '<button class="tab' + (activeCat === "all" ? " active" : "") + '" data-tab="all">All · ' + all + "</button>";
    CATS.forEach((c) => {
      html += '<button class="tab' + (activeCat === c.id ? " active" : "") + '" data-tab="' + c.id + '">' +
        c.emoji + " " + c.label + " · " + catCount(c.id) + "</button>";
    });
    tabsEl.innerHTML = html;
  }

  function renderSections() {
    const shown = DATA.filter(matches);
    const catsToShow = activeCat === "all" ? CATS.map((c) => c.id) : [activeCat];
    let html = "";
    catsToShow.forEach((cid) => {
      const items = shown.filter((it) => it.cat === cid);
      if (!items.length) return;
      const meta = CATS.find((c) => c.id === cid) || { label: cid, emoji: "" };
      html +=
        '<div class="menu-section" id="sec-' + cid + '">' +
          '<div class="menu-sec-head"><h2>' + meta.emoji + " " + meta.label +
          ' <span class="sec-count">' + items.length + "</span></h2></div>" +
          '<div class="grid-items">' + items.map(function (it, i) { return card(it, query, i); }).join("") + "</div>" +
        "</div>";
    });
    sectionsEl.innerHTML = html;
    if (countEl) {
      countEl.textContent = query
        ? shown.length + " result" + (shown.length === 1 ? "" : "s") + ' for "' + query + '"'
        : shown.length + " items · prices subject to change";
    }
    if (emptyEl) emptyEl.style.display = shown.length ? "none" : "";
    armSpy();
  }

  function render() { renderTabs(); renderSections(); }

  tabsEl.addEventListener("click", (e) => {
    const t = e.target.closest("[data-tab]");
    if (!t) return;
    activeCat = t.getAttribute("data-tab");
    render();
  });

  if (searchEl) {
    searchEl.addEventListener("input", () => {
      query = searchEl.value.trim().toLowerCase();
      if (clearEl) clearEl.style.display = query ? "" : "none";
      renderSections();
    });
  }
  if (clearEl) {
    clearEl.addEventListener("click", () => {
      searchEl.value = "";
      query = "";
      clearEl.style.display = "none";
      renderSections();
      searchEl.focus();
    });
  }
  if (emptyEl) {
    emptyEl.addEventListener("click", (e) => {
      if (e.target.closest("[data-reset]")) {
        query = ""; activeCat = "all";
        if (searchEl) searchEl.value = "";
        render();
      }
    });
  }

  // Scroll-spy: in "All" mode, highlight the tab of the section in view
  let spy = null;
  function armSpy() {
    if (spy) { spy.disconnect(); spy = null; }
    if (activeCat !== "all" || !("IntersectionObserver" in window)) return;
    const secs = $$(".menu-section");
    if (!secs.length) return;
    spy = new IntersectionObserver(function (entries) {
      entries.forEach(function (en) {
        if (en.isIntersecting) {
          const id = en.target.id.replace("sec-", "");
          $$("#menuTabs .tab").forEach(function (t) {
            t.classList.toggle("active", t.getAttribute("data-tab") === id);
          });
        }
      });
    }, { rootMargin: "-160px 0px -55% 0px", threshold: 0 });
    secs.forEach(function (s) { spy.observe(s); });
  }

  // deep links from home: /menu#smoothies etc.
  const hash = (location.hash || "").replace("#", "");
  if (CATLABEL[hash]) activeCat = hash;

  render();
  if (hash && CATLABEL[hash]) {
    const bar = $("#tabBar");
    if (bar) bar.scrollIntoView({ block: "start" });
  }
})();
