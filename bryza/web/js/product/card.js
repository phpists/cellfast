function product_change_card(data) {

    for(var name in data) {
        if (data.hasOwnProperty(name)) {
            if (name === 'imageUrl') {
                if (data.id) {
                    var imgWrapper = $('#product_image__wrapper');
                    imgWrapper.attr('href', data[name]);
                    imgWrapper.find('img').attr('src', data[name]);
                }
            } else {
                var item = document.getElementById('product_' + name);

                if (item !== null) {
                    if (name == 'price' || name == 'commonPrice') {
                        data[name] = parseFloat(data[name]).toFixed(2);
                    }
                    if (data[name] === null || data[name] === 'NaN') {
                        jQuery(item).parent().hide();
                    } else {
                        jQuery(item).parent().show();

                        if (item.tagName === 'INPUT') {
                            jQuery(item).val(data[name]).attr('val', data[name]);
                        } else {
                            jQuery(item).html(data[name]);
                        }
                    }
                } else {
                    console.warn('Элемент #product_' + name + " не был найден для подмены данных.")
                    jQuery('#product_' + name).parent().hide();
                }
            }
        }
    }

    if(data.comparePrice == undefined){
        jQuery('#product_compare-price').parent().parent().hide();
    } else {
        jQuery('#product_compare-price').parent().parent().show();
    }
}
