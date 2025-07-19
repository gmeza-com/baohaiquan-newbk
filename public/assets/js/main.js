"use strict";

var Main = function () {
  /* UI Intialize */
  var UIInit = function () {
    // Initialize Chosen
    $(".select-chosen").chosen({ width: "100%" });

    // Initialize Select2
    $("select:not(.non-select2)").select2();

    // Initialize Bootstrap Colorpicker
    $(".input-colorpicker").colorpicker({ format: "hex" });
    $(".input-colorpicker-rgba").colorpicker({ format: "rgba" });

    // Initialize Slider for Bootstrap
    $(".input-slider").slider();

    // Initialize Tags Input
    $(".input-tags").tagsInput({ width: "auto", height: "auto" });

    // Initialize Datepicker
    $(".input-datepicker, .input-daterange").datepicker({
      format: "dd-mm-yyyy",
    });
    $(".input-datepicker-close")
      .datepicker({ weekStart: 1 })
      .on("changeDate", function (e) {
        $(this).datepicker("hide");
      });

    // Initialize Timepicker
    $(".input-timepicker").timepicker({
      minuteStep: 1,
      showSeconds: false,
      showMeridian: true,
    });
    $(".input-timepicker24").timepicker({
      minuteStep: 1,
      showSeconds: false,
      showMeridian: false,
    });

    // Easy Pie Chart
    $(".pie-chart").easyPieChart({
      barColor: $(this).data("bar-color")
        ? $(this).data("bar-color")
        : "#777777",
      trackColor: $(this).data("track-color")
        ? $(this).data("track-color")
        : "#eeeeee",
      lineWidth: $(this).data("line-width") ? $(this).data("line-width") : 3,
      size: $(this).data("size") ? $(this).data("size") : "80",
      animate: 800,
      scaleColor: false,
    });

    // Initialize tabs
    $('[data-toggle="tabs"] a, .enable-tabs a').click(function (e) {
      e.preventDefault();
      $(this).tab("show");
    });

    // Initialize Tooltips
    $('[data-toggle="tooltip"], .enable-tooltip').tooltip({
      container: "body",
      animation: false,
    });

    // Initialize Popovers
    $('[data-toggle="popover"], .enable-popover').popover({
      container: "body",
      animation: true,
    });

    // Initialize single image lightbox
    $('[data-toggle="lightbox-image"]').magnificPopup({
      type: "image",
      image: { titleSrc: "title" },
    });

    // Initialize image gallery lightbox
    $('[data-toggle="lightbox-gallery"]').each(function () {
      $(this).magnificPopup({
        delegate: "a.gallery-link",
        type: "image",
        gallery: {
          enabled: true,
          navigateByImgClick: true,
          arrowMarkup:
            '<button type="button" class="mfp-arrow mfp-arrow-%dir%" title="%title%"></button>',
          tPrev: "Previous",
          tNext: "Next",
          tCounter: '<span class="mfp-counter">%curr% of %total%</span>',
        },
        image: { titleSrc: "title" },
      });
    });

    // Initialize Placeholder
    $("input, textarea").placeholder();

    // auto add class active sibebar
    $(".sidebar-nav a").each(function (e) {
      var item = $(this);
      if (window.location.href.replace(CNV.baseUrl, "") === item.attr("href")) {
        item.addClass("active");
        if (item.parent().parent().parent().is("li")) {
          item.parent().parent().show();
          item.parent().parent().parent().children("a").addClass("open");
        }
      }
    });
  };
  /* Toastr init */
  var toastrInit = function () {
    toastr.options = {
      closeButton: true,
      debug: false,
      newestOnTop: false,
      progressBar: true,
      positionClass: "toast-top-right",
      preventDuplicates: false,
      onclick: null,
      showDuration: "300",
      hideDuration: "1000",
      timeOut: "5000",
      extendedTimeOut: "1000",
      showEasing: "swing",
      hideEasing: "linear",
      showMethod: "fadeIn",
      hideMethod: "fadeOut",
    };
  };

  /**
   * Ajax form
   */
  var ajaxFormInit = function () {
    validationInit();
    var form = $(".form-validate");
    form.each(function (e) {
      var o = this;
      var b = $(this);
      var validator = b.validate();
      var btn = b.find('[type="submit"], button.btn-primary');
      b.data("validator", validator);

      // Add custom validation rules for EditorJS
      $.validator.addMethod(
        "ignoreEditorJS",
        function (value, element) {
          // Always return true for EditorJS elements to ignore validation
          return true;
        },
        ""
      );

      // Apply ignore rule to EditorJS containers
      $(".longform-content, .ignore-validation")
        .find("input, textarea, select")
        .each(function () {
          $(this).rules("add", {
            ignoreEditorJS: true,
          });
        });

      b.ajaxForm({
        dataType: "json",
        beforeSerialize: function (form, options) {},
        beforeSubmit: function () {
          btn.button("loading");
        },
        success: function (data) {
          btn.button("reset");
          if (data) {
            if (data.status == 200) {
              toastr.success(data.message, CNV.language.success);

              if (
                b.is("[data-callback]") &&
                typeof eval(b.data("callback")) === "function"
              ) {
                eval(b.data("callback"))(b, data);
              } else {
                b[0].reset();
              }
            } else if (data.status == 500) {
              toastr.warning(data.message, CNV.language.warning);
            } else {
              toastr.error(CNV.language.unknown_error, CNV.language.error);
            }
          } else {
            toastr.error(CNV.language.internet_error, mes.error);
          }
        },
        error: function (response) {
          btn.button("reset");
          if (response.status == 422) {
            var errors = response.responseJSON;
            var text = "";
            $.each(errors, function (index, value) {
              text += value[0] + "<br />";
            });
            toastr.error(text, CNV.language.error);
          } else {
            toastr.error(CNV.language.internet_error, CNV.language.error);
          }
        },
      });
    });

    //fix for validation required select2
    $(document).on("change", ".select-select2", function () {
      $(this).trigger("blur");
    });

    // Handle dynamically loaded EditorJS content
    $(document).on(
      "DOMNodeInserted",
      ".longform-content, .ignore-validation, .codex-editor__redactor",
      function () {
        var $container = $(this);
        // Add a small delay to ensure EditorJS is fully initialized
        setTimeout(function () {
          $container
            .find(
              "input, textarea, select, [contenteditable], .codex-editor__redactor *"
            )
            .each(function () {
              if ($(this).rules) {
                $(this).rules("add", {
                  ignoreEditorJS: true,
                });
              }
            });
        }, 100);
      }
    );

    // Also handle EditorJS initialization events
    $(document).on("editorjs:ready", function () {
      handleEditorJSValidation();
    });
  };

  var validationInit = function () {
    if (!$.isFunction($.fn.validate)) {
      return;
    }

    $.validator.setDefaults({
      errorClass: "help-block animation-slideDown",
      errorElement: "div",
      errorPlacement: function (error, e) {
        e.parents(".form-group > div").append(error);
      },
      highlight: function (e) {
        $(e)
          .closest(".form-group")
          .removeClass("has-success has-error")
          .addClass("has-error");
        $(e).closest(".help-block").remove();
      },
      success: function (e) {
        e.closest(".form-group").removeClass("has-success has-error");
        e.closest(".help-block").remove();
      },
      // Ignore EditorJS containers and their content
      ignore:
        ".longform-content, .longform-content *, .ignore-validation, .ignore-validation *",
    });
  };

  var ajaxInit = function () {
    $.ajaxSetup({
      headers: {
        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
      },
      beforeSend: function () {
        NProgress.start();
      },
      complete: function () {
        NProgress.done();
      },
    });
  };

  var dataAction = function () {
    $(document).on("click", "[data-action]", function (event) {
      var btn = $(this);

      if (typeof eval(btn.data("action")) === "function") {
        return eval(btn.data("action"))(event, btn);
      }
    });
  };

  var handleEditorJSValidation = function () {
    // Add custom validation method for EditorJS
    if ($.validator && $.validator.addMethod) {
      $.validator.addMethod(
        "ignoreEditorJS",
        function (value, element) {
          return true; // Always return true to ignore validation
        },
        ""
      );

      // Apply to existing EditorJS elements
      $(".longform-content, .ignore-validation, .codex-editor__redactor")
        .find("input, textarea, select, [contenteditable]")
        .each(function () {
          $(this).rules("add", {
            ignoreEditorJS: true,
          });
        });
    }
  };

  return {
    init: function () {
      UIInit(); // UI Initialize
      toastrInit(); // Initialize toastr
      ajaxInit(); // CSRF setup
      ajaxFormInit(); // Initialize form
      dataAction(); // auto load button
      handleEditorJSValidation(); // Handle EditorJS validation
    },
  };
};

Main().init();

/**
 * confirm button
 */
var activation = function (event, btn) {
  event.preventDefault();
  btn.button("loading");
  $.ajax({
    url: btn.data("url"),
    method: "GET",
    dataType: "JSON",
    data: {},
    success: function (data) {
      if (data.status == 200) {
        toastr.success(data.message, CNV.language.success);
        TablesDatatables.table._fnAjaxUpdate();
      } else {
        toastr.warning(data.message, CNV.language.warning);
      }
    },
  });
};

var confirm_to_delete = function (event, btn) {
  event.preventDefault();

  var response = {
    status: 400,
    message: CNV.language.internet_error,
  };

  swal({
    title: CNV.language.warning,
    text: btn.data("message"),
    type: "warning",
    showCancelButton: true,
    confirmButtonText: CNV.language.yes,
    cancelButtonText: CNV.language.cancel,
    showLoaderOnConfirm: true,
    preConfirm: function (confirm) {
      return new Promise(function (resolve, reject) {
        $.ajax({
          url: btn.data("url"),
          method: "POST",
          dataType: "JSON",
          data: { _method: "DELETE" },
          success: function (data) {
            response = data;
          },
          complete: function (data) {
            resolve();
          },
        });
      });
    },
    allowOutsideClick: false,
  }).then(function () {
    if (response.status == 200) {
      toastr.success(response.message, CNV.language.success);
      if (
        btn.is("[data-callback]") &&
        typeof eval(btn.data("callback")) === "function"
      ) {
        eval(btn.data("callback"))(btn, response);
      } else {
        TablesDatatables.table._fnAjaxUpdate();
      }
    } else if (response.status == 500) {
      if (response.check_item == "check_item") {
        toastr.warning(response.message, CNV.language.error);
        setTimeout(function () {
          window.location.href = response.redirect;
        }, 2000);
      } else {
        toastr.warning(response.message, CNV.language.warning);
      }
    } else {
      toastr.error(CNV.language.unknown_error, CNV.language.error);
    }
  });
};

/**
 * Callback global
 */
var nothing_to_do = function (form, data) {
  return;
};

var reload_page = function (form, data) {
  setTimeout(function () {
    window.location.reload();
  }, 1000);
};

var redirect_to = function (form, data) {
  setTimeout(function () {
    window.location.href = data.redirect_url ? data.redirect_url : CNV.baseUrl;
  }, 1000);
};

var submitForm = function (element) {
  $(element).submit();
};

var submitFormAndCreate = function (element) {
  $("[name=create_after_save]").val(1);
  $(element).submit();
};

var reloadDistrictsAndProvinces = function () {
  var reloadDistricts = function () {
    var province_id = $("#province").find("select").val();
    $.get(
      CNV.baseUrl +
        "/iadmin-api/districts.json?form&province=" +
        province_id +
        "&district=" +
        $("#district").data("name"),
      function (data) {
        $("#district").html(data);
      }
    );
  };

  if ($("#province").length > 0) {
    $.get(
      CNV.baseUrl +
        "/iadmin-api/provinces.json?form&province=" +
        $("#province").data("name"),
      function (data) {
        $("#province").html(data);
        if ($("#province").data("name").length > 0) {
          reloadDistricts();
        }
      }
    );
  }

  $(document).on("change", "#province", function () {
    reloadDistricts();
  });
};

var slugify = function (str) {
  str = str.toString().toLowerCase();
  str = str.replace(/(à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ)/g, "a");
  str = str.replace(/(è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ)/g, "e");
  str = str.replace(/(ì|í|ị|ỉ|ĩ)/g, "i");
  str = str.replace(/(ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ)/g, "o");
  str = str.replace(/(ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ)/g, "u");
  str = str.replace(/(ỳ|ý|ỵ|ỷ|ỹ)/g, "y");
  str = str.replace(/(đ)/g, "d");
  str = str.replace(/([^0-9a-z-\s])/g, "");
  str = str.replace(/(\s+)/g, "-");
  str = str.replace(/^-+/g, "");
  str = str.replace(/-+$/g, "");

  return str;
};

var number_format = function (money) {
  return money.toFixed(2).replace(/./g, function (c, i, a) {
    return i && c !== "," && (a.length - i) % 3 === 0 ? "." + c : c;
  });
};

$(document).ready(function () {
  reloadDistrictsAndProvinces();
});

var toggleThisElement = function (element) {
  $(element).toggle();
};
