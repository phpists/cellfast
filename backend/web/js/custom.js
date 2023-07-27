
var overlayShow = function(el) {
    $('<div id="process-overlay"></div>').appendTo(el);
}

var overlayHide = function(el) {
    $('#process-overlay', el).remove();
}

var productItemDataModalForm = function (form, wrapper, modal) {

    form.on("submit", function (e) {
        if (form.hasClass('_ok_')) {
            return false;
        }
        form.addClass('_ok_');
        e.preventDefault();
        var formData = new FormData(this);
        console.log(formData);
        overlayShow($('body'));
        $.ajax({
            url: form.attr('action'),
            type: 'post',
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            success: function (html) {
                overlayHide($('body'));
                wrapper.html(html);
                form[0].reset();
                modal.modal('hide');
            },
            error: function (e) {
                alert('Ошибка сохранения');
                console.log(e);
            }
        });
        return false;
    });
};
