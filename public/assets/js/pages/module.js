"use strict";

(function ($) {

    $(".module-action").each(function (e) {
        $(this).on("click", function (event) {
            var button = $(this),
                active = button.data('active');

            if(!active) {
                swal({
                    title: 'Enter Security Key',
                    input: 'text',
                    showCancelButton: true,
                    confirmButtonText: 'Verify',
                    showLoaderOnConfirm: true,
                    preConfirm: function (license) {
                        return new Promise(function (resolve, reject) {
                                if (license === '') {
                                    reject('Please enter security key !!!')
                                } else {
                                    sendActiveModules(button, license);
                                }
                        });
                    },
                    allowOutsideClick: false
                }).then(function (email) {
                    swal({
                        type: 'success',
                        title: 'Action has been approved !'
                    })
                });
            } else {
                sendActiveModules(button, '');
            }
        });
    });

    function sendActiveModules($btn, $license) {
        $btn.button("loading");

        $.ajax({
            url: $btn.data('url'),
            method: "POST",
            dataType: "JSON",
            data: {license: $license},
            success: function (response) {
                if(response.status == 200)
                {
                    toastr.success(response.message, CNV.language.success);
                    $btn.button("reset");
                } else {
                    toastr.warning(response.message, CNV.language.warning);
                }
                reload_page(null, null);
            },
            fail: function () {
                toastr.error(CNV.language.unknown_error, CNV.language.error);
                $btn.button("reset");
            }
        });
    }

})(jQuery);