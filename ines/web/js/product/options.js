function product_options_change() {
    var ajaxurl =  jQuery('.product').data('ajaxurl');
    var $data = new FormData();
    var options = [];

    $data.append($('meta[name="csrf-param"]').attr("content"), $('meta[name="csrf-token"]').attr("content"));

    jQuery('.product').find('select.take_to_ajax, input[type=radio]:checked.take_to_ajax, input[type=checkbox]:checked.take_to_ajax').each(function(i, item){
        $data.append(item.name, item.value);
        options[i] = item.value;
    });

    $data.append('features', options);
    
    $.ajax({
        type: 'POST',
        url: ajaxurl,
        data: $data,
        async: false,
        cache: false,
        contentType: false,
        processData: false,
        success: function (data) {
            product_change_card(data);
        },
        complete: function (data) {
            // console.log();
        }
    });
}

jQuery(document).on('change', 'select.take_to_ajax, input.take_to_ajax', function(){
    product_options_change();
});