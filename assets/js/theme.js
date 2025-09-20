(function ($) {
  "use strict";

  var rmt_window_height;
  var rmt_window_width;
  var rmt_scroll_status = "";
  var lastScrollTop = 0;

  $(window).on("load", function () {
    let preloader = $(".preloader");
    if (preloader.length) {
      if (preloader.hasClass("loader-style1")) {
        setTimeout(function () {
          $(".preloader").addClass("loaded").removeClass("loading");
        }, 3000);
      } else {
        $(".preloader").addClass("loaded").removeClass("loading");
      }
    }
    $(".rmt-header-mobile-elementor, .rmt-slider").css("opacity", "1");
    $(".rmt-gallery-scroll")
      .parents("body")
      .addClass("body-overflow")
      .addClass("body-visible-sm");
    $("blockquote:not(.rmt-blockquote)").append(
      '<i class="rmt-blockquote-icon fas fa-quote-right text-gradient"></i>'
    );
    rmt_window_width = $(window).width();
    rmt_window_height = $(window).height();
    portfoliocraft_shop_quantity();
  });

  $(window).on("scroll", function () {
    let scrollTop = $(this).scrollTop();
    lastScrollTop = scrollTop;
    if (lastScrollTop <= rmt_window_height) {
      $(".rmt-back-to-top").fadeOut();
    } else {
      $(".rmt-back-to-top").fadeIn();
    }
  });

  $(window).on("resize", function () {
    rmt_window_height = $(window).height();
    rmt_window_width = $(window).width();
    updateTranslateZToParentHeight();
  });

  $(document).ready(function () {
    $(".preloader").addClass("loading");
    onSubmitFormFromID();
    portfoliocraft_type_file_upload();
    onClickCTAButton();
    updateTranslateZToParentHeight();
    setIdForSVG();
    portfoliocraftSmoothScroll();
    onClickCallActionAnchor();
    onClickBackToTop();
    setTimeout(function () {
      toggleMenu();
    }, 300);

    /* Scroll To Top */
    $(".rmt-back-to-top").on("click", function () {
      $("html, body").animate({ scrollTop: 0 }, 800);
      return false;
    });

    /* End Animate Time Delay */

    /* Lightbox Popup */
    setTimeout(function () {
      $(".rmt-action-popup").magnificPopup({
        type: "iframe",
        mainClass: "mfp-fade",
        removalDelay: 160,
        preloader: false,
        fixedContentPos: false,
      });
    }, 300);

    $(".rmt-gallery-lightbox").each(function () {
      $(this).magnificPopup({
        delegate: "a.lightbox",
        type: "image",
        gallery: {
          enabled: true,
        },
        mainClass: "mfp-fade",
      });
    });

    /* Cart Sidebar Popup */
    $(".rmt-cart-sidebar-button").on("click", function () {
      $("body").addClass("body-overflow");
      $("#rmt-cart-sidebar").addClass("active");
    });
    $(
      "#rmt-cart-sidebar .rmt-popup--overlay, #rmt-cart-sidebar .rmt-item--close, #rmt-cart-sidebar .rmt-popup--close2"
    ).on("click", function () {
      $("body").removeClass("body-overflow");
      $("#rmt-cart-sidebar").removeClass("active");
    });

    /* Select Theme Style */
    $(".wpcf7-select").each(function () {
      var $this = $(this),
        numberOfOptions = $(this).children("option").length;
      $this.addClass("rmt-select-hidden");
      $this.wrap('<div class="rmt-select"></div>');
      $this.after('<div class="rmt-select-higthlight"></div>');

      var $styledSelect = $this.next("div.rmt-select-higthlight");
      $styledSelect.text($this.children("option").eq(0).text());

      var $list = $("<ul />", {
        class: "rmt-select-options",
      }).insertAfter($styledSelect);

      for (var i = 0; i < numberOfOptions; i++) {
        $("<li />", {
          text: $this.children("option").eq(i).text(),
          rel: $this.children("option").eq(i).val(),
        }).appendTo($list);
      }

      var $listItems = $list.children("li");

      $styledSelect.on("click", function (e) {
        e.stopPropagation();
        $("div.rmt-select-higthlight.active")
          .not(this)
          .each(function () {
            $(this)
              .removeClass("active")
              .next("ul.rmt-select-options")
              .addClass("rmt-select-lists-hide");
          });
        $(this).toggleClass("active");
      });

      $listItems.on("click", function (e) {
        e.stopPropagation();
        $styledSelect.text($(this).text()).removeClass("active");
        $this.val($(this).attr("rel"));
      });

      $(document).on("click", function () {
        $styledSelect.removeClass("active");
      });
    });

    /* Nice Select */
    $(
      ".woocommerce-ordering .orderby, #rmt-sidebar-area select, .variations_form.cart .variations select, .rmt-open-table select, .rmt-nice-select"
    ).each(function () {
      $(this).niceSelect();
    });
  });

  jQuery(document).ajaxComplete(function (event, xhr, settings) {
    portfoliocraft_shop_quantity();
    if (typeof elementorFrontend !== "undefined") {
      elementorFrontend.init();
    }
  });

  jQuery(document).on("updated_wc_div", function () {
    portfoliocraft_shop_quantity();
  });

  /* WooComerce Quantity */
  function portfoliocraft_shop_quantity() {
    "use strict";
    $("#rmt-wrapper .quantity").append(
      '<span class="quantity-icon quantity-down rmt-icon--caretdown"></span><span class="quantity-icon quantity-up rmt-icon--caretup"></span>'
    );
    $(".quantity-up").on("click", function () {
      $(this).parents(".quantity").find('input[type="number"]').get(0).stepUp();
      $(this)
        .parents(".woocommerce-cart-form")
        .find(".actions .button")
        .removeAttr("disabled");
    });
    $(".quantity-down").on("click", function () {
      $(this)
        .parents(".quantity")
        .find('input[type="number"]')
        .get(0)
        .stepDown();
      $(this)
        .parents(".woocommerce-cart-form")
        .find(".actions .button")
        .removeAttr("disabled");
    });
    $(".quantity-icon").on("click", function () {
      var quantity_number = $(this)
        .parents(".quantity")
        .find('input[type="number"]')
        .val();
      var add_to_cart_button = $(this)
        .parents(".product, .woocommerce-product-inner")
        .find(".add_to_cart_button");
      add_to_cart_button.attr("data-quantity", quantity_number);
      add_to_cart_button.attr(
        "href",
        "?add-to-cart=" +
          add_to_cart_button.attr("data-product_id") +
          "&quantity=" +
          quantity_number
      );
    });
    $(".woocommerce-cart-form .actions .button").removeAttr("disabled");
  }

  /* Preloader Default */
  $.fn.extend({
    jQueryImagesLoaded: function () {
      var $imgs = this.find('img[src!=""]');

      if (!$imgs.length) {
        return $.Deferred().resolve().promise();
      }

      var dfds = [];

      $imgs.each(function () {
        var dfd = $.Deferred();
        dfds.push(dfd);
        var img = new Image();
        img.onload = function () {
          dfd.resolve();
        };
        img.onerror = function () {
          dfd.resolve();
        };
        img.src = this.src;
      });

      return $.when.apply($, dfds);
    },
  });

  /* Custom Type File Upload*/
  function portfoliocraft_type_file_upload() {
    var multipleSupport = typeof $("<input/>")[0].multiple !== "undefined",
      isIE = /msie/i.test(navigator.userAgent);

    $.fn.rmt_custom_type_file = function () {
      return this.each(function () {
        var $file = $(this).addClass("rmt-file-upload-hidden"),
          $wrap = $('<div class="rmt-file-upload-wrapper">'),
          $button = $(
            '<button type="button" class="rmt-file-upload-button">Choose File</button>'
          ),
          $input = $(
            '<input type="text" class="rmt-file-upload-input" placeholder="No File Choose" />'
          ),
          $label = $(
            '<label class="rmt-file-upload-button" for="' +
              $file[0].id +
              '">Choose File</label>'
          );
        $file.css({
          position: "absolute",
          opacity: "0",
          visibility: "hidden",
        });

        $wrap.insertAfter($file).append($file, $input, isIE ? $label : $button);

        $file.attr("tabIndex", -1);
        $button.attr("tabIndex", -1);

        $button.on("click", function () {
          $file.focus().click();
        });

        $file.change(function () {
          var files = [],
            fileArr,
            filename;

          if (multipleSupport) {
            fileArr = $file[0].files;
            for (var i = 0, len = fileArr.length; i < len; i++) {
              files.push(fileArr[i].name);
            }
            filename = files.join(", ");
          } else {
            filename = $file.val().split("\\").pop();
          }

          $input.val(filename).attr("title", filename).focus();
        });

        $input.on({
          blur: function () {
            $file.trigger("blur");
          },
          keydown: function (e) {
            if (e.which === 13) {
              if (!isIE) {
                $file.trigger("click");
              }
            } else if (e.which === 8 || e.which === 46) {
              $file.replaceWith(($file = $file.clone(true)));
              $file.trigger("change");
              $input.val("");
            } else if (e.which === 9) {
              return;
            } else {
              return false;
            }
          },
        });
      });
    };
    $(".wpcf7-file[type=file]").rmt_custom_type_file();
  }

  function onClickCallActionAnchor() {
    let anchorButtons = $(".rmt-atc-anchor");
    if (!anchorButtons.length) return;
    $(anchorButtons).on("click", function (e) {
      e.preventDefault();
      let target = $(this).attr("href");
      let offset = parseInt($(this).attr("data-target-offset"));
      if ($(target).length) {
        $("html, body").animate(
          {
            scrollTop: $(target).offset().top + offset,
          },
          1000
        );
      }
    });
  }

  function onClickBackToTop() {
    // let backToTopBtn = $(".back-to-top-button");
    // if (!backToTopBtn.length) return;
    // $(backToTopBtn).on("click", function (e) {
    //   e.preventDefault();
    //   $("html, body").animate(
    //     {
    //       scrollTop: 0,
    //     },
    //     1000
    //   );
    // });
  }

  function onSubmitFormFromID() {
    let btns = $(".rmt-atc-submit");
    if (!btns.length) return;
    $(btns).on("click", function (e) {
      e.preventDefault();
      const formID = "." + $(this).attr("data-submit");
      if (!$(formID).length && !$(formID).is("form")) return;
      $(formID).find("input:submit").click();
    });
  }

  function toggleMenu() {
    let els = $(".rmt-vertical-menu > li > a");
    if (!els.length) return;
    $(".rmt-vertical-menu .sub-menu").animate({ height: 0 }, 0);
    $(els).on("click", function (e) {
      e.preventDefault();
      let submenu = $(this).siblings(".sub-menu").first();
      if (submenu.length) {
        if (submenu.hasClass("active")) {
          submenu.removeClass("active").animate({ height: 0 }, 300);
        } else {
          submenu.addClass("active").css("height", "auto");
          let height = submenu.outerHeight();
          submenu.css("height", 0);
          submenu.animate({ height: height }, 300);
        }
      }
    });
  }

  function onClickCTAButton() {
    let currentTemplate = null;
    let links = null;
    $(document).on(
      "click",
      ".rmt-cta-button, .rmt-search-button",
      function (e) {
        e.preventDefault();
        const template = $(this).attr("href");
        if ($(template).length) {
          $(template).addClass("active");
          $("body").addClass("body-overflow");
          links = $(template).find(".rmt-links-wrapper .rmt-link-text");
          $(links).addClass("text-underline-slide");
          currentTemplate = template;
        }
      }
    );

    $(".rmt-post-template").on(
      "click",
      ".rmt-close-button, .rmt-template-overlay",
      function (e) {
        $(currentTemplate).removeClass("active");
        $("body").removeClass("body-overflow");
        $(links).removeClass("text-underline-slide");
      }
    );
  }

  function updateTranslateZToParentHeight() {
    const els = $(".hover-3d-cube-flip");
    if (!els.length) return;
    els.each(function () {
      const height = $(this).height();
      $(this).css({ "--rmt-translate-z": `${height / 2}px` });
    });
  }

  function setIdForSVG() {
    const svg = $("svg");
    if (!svg.length) return;
    $("svg").each(function (svgIndex) {
      let gradientMap = new Map();
      $(this)
        .find("linearGradient")
        .each(function (gradIndex) {
          let oldId = $(this).attr("id");
          let newId = `gradient_${svgIndex}_${gradIndex}`;

          $(this).attr("id", newId);
          gradientMap.set(oldId, newId);
        });

      $(this)
        .find("path")
        .each(function () {
          let fillAttr = $(this).attr("fill");

          if (fillAttr && fillAttr.startsWith("url(#")) {
            let oldId = fillAttr.match(/url\(#(.*?)\)/)[1];
            let newId = gradientMap.get(oldId);
            if (newId) {
              $(this).attr("fill", `url(#${newId})`);
            }
          }
        });
    });
  }

  function portfoliocraftSmoothScroll() {
    if (!$("#smooth-content").length || !$("#smooth-wrapper").length) return;
    window.smoother = ScrollSmoother.create({
      wrapper: "#smooth-wrapper",
      content: "#smooth-content",
      smooth: 3.5,
      normalizeScroll: true,
      ignoreMobileResize: true,
      effects: true,
      smoothTouch: 1,
      speed: 1,
    });
  }
})(jQuery);
