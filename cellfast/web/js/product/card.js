function product_change_card(data) {

    if (data == null) {
        $('.product__top__right__art').hide();
    } else {

        for (var name in data) {
            var item;
            if (data.hasOwnProperty(name)) {
                if (name === 'imageUrl') {
                    if (data.id) {
                        var imgWrapper = $('#product_image__wrapper');
                        imgWrapper.show();
                        imgWrapper.attr('href', data[name]);
                        imgWrapper.find('img').attr('src', data[name]);
                    }
                } else if (name === 'packages') {
                    for (var key in data[name]) {
                        $('#product_packages_' + key).html(data[name][key]);
                    }
                } else {
                    if ((item = $('#product_' + name)).length || (item = $('.product_' + name)).length) {
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
                        console.warn('Элемент product_' + name + " не был найден для подмены данных.")
                        jQuery('#product_' + name).parent().hide();
                    }
                }
            }
        }

        $('.product__top__right__art').show();

        if (data.comparePrice == undefined) {
            jQuery('#product_compare-price').parent().parent().hide();
        } else {
            jQuery('#product_compare-price').parent().parent().show();
        }

    }
}
