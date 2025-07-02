"use strict";

var combackToPlainForm = function() {
    $("#form").html("").hide();
    $("#create_new_item").parent().show();
}

var savePositionAllItems = function(menu) {
    var nested = $( document ). find('#nestable') .nestable('serialize'),
        btn = $('#save-position');
    btn.button('loading');
    $.post(CNV.baseUrl + "/iadmin/menu/" + menu + "/item", {_method: 'PUT', data: nested}, function(data) {
        btn.button('reset');
    });
}

var updatePositionItems = function(e) {
    var list   = e.length ? e : $(e.target);

    if (window.JSON) {
        output.val(window.JSON.stringify(list.nestable('serialize')));
        $.post()
    } else {
        swal(CNV.language.error, CNV.language.unknown_error, 'error');
    }
}

var reloadBuilderContent = function(menu) {
    $.get(CNV.baseUrl + "/iadmin/menu/" + menu + "/item", function(data) {
        $("#content").html(data);
        $( document ). find('#nestable') .nestable() .on('change', function() {
            $('#save-position').click();
        });
    });
}

var refreshBuilder = function(form, data) {
    combackToPlainForm();
    reloadBuilderContent(data.menu_id);
}

var editItem = function(menu, item) {
    var btn = $("#create_new_item");

    $.get(CNV.baseUrl + "/iadmin/menu/" + menu + "/item/" + item + "/edit", function(html) {
        btn.parent().hide();
        $("#form").html(html).show();

        // trigger form components
        Main().init();
    });
}

$(function() {
    var realoadSelectSearchLink = function (modal) {
        var url = $('select[name=module_name] option:selected').val(),
            selectSearch = $(document).find(".select-search-link");
        selectSearch.select2({
            ajax: {
                url: url,
                method: 'GET',
                dataType: 'json',
                delay: 100,
                data: function (params) {
                    return {
                        q: params.term
                    };
                },
                processResults: function (data) {
                    return {
                        results: $.map(data.result, function (item) {
                            return {
                                text: item.name,
                                id: item.name,
                                value: JSON.stringify(item.attributes)
                            }
                        })
                    };
                },
                cache: true
            },
            escapeMarkup: function (markup) { return markup;},
            minimumInputLength: 1
        });

        selectSearch.on("select2:select",  function (e) {
            var data = $(this).select2('data');

            data.forEach(function (p) {
                var value = JSON.parse(p.value);
                value.forEach(function (attr) {
                    $(document).find('[name="' + attr.attr + '"]').val(attr.value);
                    modal.modal('hide');
                });
            })
        });
    };
    // modal search link
    $("#form-search-links").submit(function (event) {
        event.preventDefault();
    });

    $("#search-links").on('shown.bs.modal', function () {
        var modal = $(this);
        realoadSelectSearchLink(modal);
        $("select[name=module_name]").change(function() {
            realoadSelectSearchLink(modal);
        });
    });

    // change auto saved
    $("#menus-list").on("change", function(event) {
        $(this).parent().parent().submit();
    })

    // Create new item menu
    $( document ).on( "click", "#create_new_item", function(event) {
        event.preventDefault();
        var btn = $(this),
            id  = btn.data("id");

        btn.button("loading");

        $.get(CNV.baseUrl + "/iadmin/menu/" + id + "/item/create", function(html) {
            btn.parent().hide();
            $("#form").html(html).show();

            // trigger form components
            Main().init();

            btn.button("reset");
        });
    });

    // cancel
    $( document ).on( "click", "#cancel_button", function(event) {
        event.preventDefault();
        combackToPlainForm();
    });
});