(function () {
  var toggle = document.querySelector(".hfe-nav-menu__toggle, .menu-toggle");
  var nav = document.querySelector(".hfe-nav-menu__layout-horizontal, #site-nav");
  if (!toggle || !nav) return;
  toggle.addEventListener("click", function () {
    var open = nav.classList.toggle("is-open");
    toggle.setAttribute("aria-expanded", String(open));
  });
})();




