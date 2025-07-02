var editor = function () {
    var pluginMCE = [
        "advlist autolink autosave link image lists charmap print preview hr anchor pagebreak",
        "searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking",
        "table contextmenu directionality emoticons template textcolor paste textcolor colorpicker textpattern moxiemanager"
    ],
        setupMCE = function (editor) {
            editor.on('change', function () {
                tinymce.triggerSave();
            });
        };

    var TinyMCETextEditor = function() {
        tinymce.init({
            selector: '.simple_editor',
            language: 'vi_VN',
            height: 200,
            moxiemanager_filelist_main_toolbar: 'create upload manage', // moxiemanager
            statusbar: false,
            image_advtab: true,
            plugins: pluginMCE,
            setup: setupMCE,
            menubar: false,
            relative_urls: false,
            entity_encoding : "raw"
        });

        tinymce.init({
            selector: '.editor',
            language: 'vi_VN',
            height: 500,
            theme: 'modern',
            moxiemanager_filelist_main_toolbar: 'create upload manage', // moxiemanager
            plugins: pluginMCE,
            toolbar1: "bold italic underline strikethrough | alignleft aligncenter alignright alignjustify | styleselect formatselect fontselect fontsizeselect",
            toolbar2: "cut copy paste | searchreplace | bullist numlist | outdent indent blockquote | undo redo | link unlink anchor image media code | insertdatetime preview | forecolor backcolor",
            toolbar3: "table | hr removeformat | subscript superscript | charmap emoticons | print fullscreen | ltr rtl | visualchars visualblocks nonbreaking template pagebreak restoredraft",
            menubar: false,
            image_advtab: true,
            valid_elements : '*[*]',
            statusbar: false,
            setup: setupMCE,
            relative_urls: false,
            entity_encoding : "raw",
            toolbar_items_size: 'small'
        });

        tinymce.init({
            selector: '.editor_html',
            height: 500,
            language: 'vi_VN',
            theme: 'modern',
            plugins: [
                'fullpage save advlist autolink lists link image charmap print preview hr anchor pagebreak',
                'searchreplace wordcount visualblocks visualchars code fullscreen',
                'insertdatetime media nonbreaking save table contextmenu directionality',
                'template paste textcolor colorpicker textpattern imagetools toc moxiemanager codesample'
            ],
            toolbar1: "bold italic underline strikethrough | alignleft aligncenter alignright alignjustify | styleselect formatselect fontselect fontsizeselect",
            toolbar2: "cut copy paste | searchreplace | bullist numlist | outdent indent blockquote | undo redo | link unlink anchor image media code | insertdatetime preview | forecolor backcolor",
            toolbar3: "table | hr removeformat | subscript superscript | charmap emoticons | print fullscreen | ltr rtl | visualchars visualblocks nonbreaking template pagebreak restoredraft",
            image_advtab: true,
            valid_elements : '*[*]',
            statusbar: false,
            setup: setupMCE,
            relative_urls: false,
            entity_encoding : "raw"
        });
    };

    var ChooseThumbnail = function () {
        $('.choose-thumbnail').each(function (e) {
            var target = $(this),
                input  = $(this).find('input[type=hidden]'),
                showPicture;

            // function show image in block
            showPicture = function (src) {
                var image = new Image();
                image.src = src;
                return image;
            };
            // show image default
            if(input.val().length > 0) {
                target.find('img').remove();
                target.prepend(showPicture(
                    input.val()
                ));
            }

            // click choose images
            $(this).off();
            $(this).on('click', function () {
                return moxman.browse({fields: input.attr('id'), view: 'thumbs'});
            });

            // change picture
            input.off();
            input.change(function(e) {
                target.find('img').remove();
                target.prepend(showPicture(
                    $(this).val()
                ));
            })
        });
    };

    var chooseFilesComponent = function () {
        var btn = $('button[data-toggle=files]');

        if (btn.length > 0) {
            btn.on('click', function(e) {
                e.preventDefault();
                return moxman.browse({fields: btn.data('target'), view: 'thumbs'});
            });
        }
    };

    var autoRenderSlugViaName = function () {
          var nameBox = $('input[name="name"]'),
              slugBox = $('input[name="slug"]');

         if(slugBox.length > 0) {
             nameBox.change(function () {
                 slugBox.val(
                     slugify(
                         $(this).val()
                     )
                 )
             });
         }

    };

    return {
        init: function () {
            TinyMCETextEditor();
            ChooseThumbnail();
            chooseFilesComponent();
            autoRenderSlugViaName();
        }
    }
};

$(document).ready(function () {
    editor().init();
});
