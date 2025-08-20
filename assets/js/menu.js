(function ($) {
  "use strict";
  let windowWidth = $(window).width();
  let windowHeight = $(window).height();
  let lastScrollTop = 150;

  $(window).on("load", function () {
    windowWidth = $(window).width();
    windowHeight = $(window).height();
  });

  $(window).on("resize", function () {
    windowWidth = $(window).width();
    windowHeight = $(window).height();
  });

  $(document).ready(function () {
    portfoliocraftToggleMobileMenu();
    setTimeout(function () {
      portfoliocraftHandleSubmenu();
      activeMenuToSubmenu();
    }, 150);
    handleMenuPanel();
  });
  $(window).on("scroll", function () {
    let scrollTop = $(this).scrollTop();
    if (scrollTop > 150 && windowWidth >= 1200) {
      if (scrollTop > lastScrollTop) {
        $(".pxl-header-sticky.scroll-down").css("transform", "translateY(0)");
        $(".pxl-header-sticky.scroll-up").css("transform", "translateY(-100%)");
      } else {
        $(".pxl-header-sticky.scroll-up").css("transform", "translateY(0)");
        $(".pxl-header-sticky.scroll-down").css(
          "transform",
          "translateY(-100%)"
        );
      }
    } else if (scrollTop < 100 && windowWidth >= 1200) {
      $(".pxl-header-sticky.scroll-up").css("transform", "translateY(-100%)");
    }
    lastScrollTop = scrollTop <= 0 ? 0 : scrollTop;
  });

  // Toggle Mobile Menu
  function portfoliocraftToggleMobileMenu() {
    $(".pxl-toggle-menu").on("click", function (e) {
      e.preventDefault();
      $(this).siblings(".pxl-sidebar-menu").toggleClass("active");
      $(this).siblings(".pxl-header-backdrop").toggleClass("active");
      $("body").addClass("body-overflow");
    });

    $(".pxl-header-backdrop").on("click", function (e) {
      $(this).siblings(".pxl-sidebar-menu").toggleClass("active");
      $(this).toggleClass("active");
      $("body").removeClass("body-overflow");
    });

    $(".pxl-header .pxl-close-menu").on("click", function () {
      $(this).closest(".pxl-sidebar-menu").toggleClass("active");
      $("body").removeClass("body-overflow");
      $(".pxl-header .pxl-header-backdrop").toggleClass("active");
    });
  }
  // Menu Responsive Dropdown
  function portfoliocraftHandleSubmenu() {
    const menu_items = $(".pxl-header li.menu-item-has-children");
    if (!menu_items.length) return;
    menu_items.each(function (i, menu_item) {
      let submenu = $(menu_item).find("> .sub-menu").first();
      const menuLink = $(menu_item).find("> a");
      if (windowWidth < 1200) {
        $(menuLink).append('<span class="menu-icon-toggle"></span>');
      }
      if (!submenu.length) return;
      if (submenu.offset().left + submenu.width() + 0 > $(window).width())
        submenu.addClass("submenu-reverse");
      $(menuLink).on("click", function (e) {
        if (windowWidth >= 1200) return;
        e.preventDefault();
        e.stopPropagation();
        $(submenu).toggleClass("active").slideToggle(500);
      });
    });
  }

  function handleMenuPanel() {
    const menu_items = $(".menu-panel li.menu-item-has-children");
    if (!menu_items.length) return;
    menu_items.each(function (i, menu_item) {
      let submenu = $(menu_item).find("> .sub-menu").first();
      const menuLink = $(menu_item).find("> a");
      $(menuLink).append('<span class="menu-icon-toggle"></span>');
      if (!submenu.length) return;
      $(menuLink).on("click", function (e) {
        e.preventDefault();
        e.stopPropagation();
        $(submenu).toggleClass("active").slideToggle(750);
      });
    });
  }
  // Active menu to submenu
  function activeMenuToSubmenu() {
    let currentUrl = window.location.href;
    let megaMenuItems = $(".pxl-menu-primary .sub-menu.pxl-mega-menu");
    if (!megaMenuItems.length) return;
    megaMenuItems.each(function (i, menuItem) {
      const megaMenuItemLinks = $(menuItem).find("a");
      const currentMenuItem = $(this);
      if (!megaMenuItemLinks.length) return;
      megaMenuItemLinks.each(function (i, menuItemLink) {
        let menuItemLinkHref = String($(menuItemLink).attr("href") + "/");
        let currentUrlString = String(currentUrl);
        if (menuItemLinkHref === currentUrlString) {
          $(currentMenuItem).parent(".pxl-megamenu").first().addClass("active");
        }
      });
    });
  }
})(jQuery);
