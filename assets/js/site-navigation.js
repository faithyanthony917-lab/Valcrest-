(function () {
  function closeMenus(except) {
    document.querySelectorAll(".hfe-nav-menu__layout-horizontal.is-open").forEach(function (menu) {
      if (menu !== except) {
        menu.classList.remove("is-open");
        menu.classList.remove("menu-is-active");
      }
    });
    document.querySelectorAll(".menu-item-has-children.is-open").forEach(function (item) {
      if (!except || !item.contains(except)) item.classList.remove("is-open");
    });
  }

  document.querySelectorAll(".hfe-nav-menu__toggle").forEach(function (toggle) {
    var menu = toggle.parentElement.querySelector(".hfe-nav-menu__layout-horizontal");
    if (!menu) return;
    toggle.addEventListener("click", function () {
      var open = menu.classList.toggle("is-open");
      menu.classList.toggle("menu-is-active", open);
      toggle.setAttribute("aria-expanded", open ? "true" : "false");
      if (open) closeMenus(menu);
    });
  });

  document.querySelectorAll(".menu-item-has-children > .hfe-menu-item").forEach(function (link) {
    link.addEventListener("click", function (event) {
      var item = link.parentElement;
      var submenu = item.querySelector(":scope > .sub-menu");
      if (!submenu || window.innerWidth > 1024) return;
      event.preventDefault();
      var open = item.classList.toggle("is-open");
      if (open) {
        item.parentElement.querySelectorAll(":scope > .menu-item-has-children.is-open").forEach(function (other) {
          if (other !== item) other.classList.remove("is-open");
        });
      }
    });
  });

  document.addEventListener("click", function (event) {
    if (!event.target.closest(".hfe-nav-menu")) closeMenus(null);
  });
})();
