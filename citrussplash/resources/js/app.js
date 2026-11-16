// CitrusSplash — JS entry point (bundled by Vite).
// Each module self-guards: it checks for its own elements and quietly
// does nothing on pages that don't have them. So one bundle serves
// every page — home, menu, checkout, status — without conditionals here.
import "./main.js";    // cart drawer, toast, reveal, mobile nav (every page)
import "./menu.js";    // menu page rendering/tabs/search (menu page only)
import "./depth.js";   // card tilt + hero parallax (precise pointers only)
