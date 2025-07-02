var addLineContentUpdate = function () {
    var content = $('#content'),
        key = parseInt(content.data('key')) + 1,
        template = $('#lineTemplate').data('template');

    template = template.replace('__KEY__', key).replace('__KEY__', key);
    content.prepend(template);
    content.data('key', key);
    $('select').select2();
}

var removeLineContentUpdate = function() {
    var content = $('#content'),
        key = parseInt(content.data('key'));

    key = key > 0 ? key - 1 : 0;
    content.data('key', key);
    if(content.children('.form-group').length > 0) {
        content.find('.form-group:first-child').remove();
    }
}