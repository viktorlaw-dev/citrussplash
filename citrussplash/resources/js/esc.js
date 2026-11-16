// CitrusSplash — shared HTML-escaping helper (rule F6).
// Any text we place inside JS-built HTML must pass through this first,
// so one day, when item names or settings contain characters like < > &,
// they display as text instead of being read as HTML.
export function esc(value) {
  return String(value).replace(/[&<>"']/g, function (c) {
    return { "&": "&amp;", "<": "&lt;", ">": "&gt;", '"': "&quot;", "'": "&#39;" }[c];
  });
}
