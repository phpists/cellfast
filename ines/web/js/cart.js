(function( $ ){
    var mainContainer = '#checkout';
    var badgeContainer = '#checkout_badge';
    var productCard = '.catalog__it';
    var removeButton = '.trash-product';
    var buyButton = '.add_product_in_cart';
    var buyProductButton = '.product-order-item-wrapper .add-to-cart';
    var urls = {
        cart: {
            get: '/cart',
            badge: '/cart/badge',
            add: '/cart/add',
            update: '/cart/update',
            remove: '/cart/remove',
            quantity_up: '/cart/up',
            quantity_down: '/cart/down',
        }
    };
    var preloader = {
        start: function( param ){
            document.openCartProcess = 1;

            if( param != null && param.refresh != null && param.refresh == true ) {
                jQuery(mainContainer).html('<div class="checkout__block"><div class="progress"><div class="indeterminate"></div></div></div>');
            }

            jQuery(mainContainer).modal('show');
        },
        end: function(){
            document.openCartProcess = 0;
            jQuery('.progress', mainContainer).hide();
        }
    }

    document.openCartProcess = 0;

    var methods = {
        /**
         * Инициализация плагина и привязка событий к элементам сайта.
         */
        init: function(){

            /**
             * Открыть корзину.
             */
            jQuery(badgeContainer, document).on('click', function (){
                $(badgeContainer).cart('basket');
            });

            /**
             * Купить продукт с выбором опций и количества products/item
             */
            jQuery(document).on('click', buyProductButton, function (e){
                e.preventDefault();
                var item_id = jQuery('#product_id').val();
                var item_product_id = jQuery('#product_product_id').val();
                var item_quantity = jQuery('#product_counter').val();
                $(badgeContainer).cart('addproduct', {
                    id: item_id,
                    item_id: item_product_id,
                    quantity: item_quantity,
                });
            });

            /**
             * Купить продукт без выбора опций и количества catalog/products[item]
             */
            jQuery(document).on('click', buyButton, function (e){
                e.preventDefault();
                var item_id = jQuery(this).data('productid');
                var item_quantity = jQuery(this).data('quantity');
                $(badgeContainer).cart('addproduct', {
                    id: item_id,
                    quantity: item_quantity,
                });
            });

            /**
             * Переключение комбинаций в products/item
             */
            jQuery(document).on('change', 'select.take_to_ajax, input.take_to_ajax', function(){
                var _t = [];
                jQuery('.product').find('select.take_to_ajax, input[type=radio]:checked.take_to_ajax, input[type=checkbox]:checked.take_to_ajax').each(function(i, item){
                    _t.push(item.value);
                });
                var currentKey = _t.sort().join(':');
                console.log(currentKey);

                if ( typeof document.featuresMap[currentKey] == 'undefined' ) {
                    jQuery(this).parents('.product__top').find('.add-to-cart').addClass('disabled').attr('disabled', true);
                    console.warn( document.itemNotFoundMessage + ". " + currentKey);
                } else {
                    jQuery(this).parents('.product__top').find('.add-to-cart').removeClass('disabled').removeAttr('disabled');
                    product_change_card( document.featuresMap[currentKey] );
                }

            });

            /**
             * Переключение комбинаций в catalog/product/_item
             */
            jQuery(document).on('change', productCard, function(){
                var _t = [],
                    _features = jQuery(this).data('features');

                jQuery(this).find('.features select').each(function(i, item){
                    _t.push(item.value);
                });

                var currentKey = _t.sort().join(':');

                if ( typeof _features[currentKey] == 'undefined' ) {
                    jQuery(this).find( buyButton ).addClass('disabled').attr('disabled', true);
                    console.warn( document.itemNotFoundMessage + ". " + currentKey);
                } else {
                    jQuery(this).find( buyButton ).removeClass('disabled').removeAttr('disabled');
                    jQuery(document).cart( 'changeCardItem', {params: _features[currentKey], item: jQuery(this)} );
                }

            });

            /**
             * Удаление продукта с корзины
             */
            jQuery(document).on('click', mainContainer + ' ' + removeButton, function (e){
                e.preventDefault();
                var item_id = jQuery(this).data('productid');
                $(badgeContainer).cart('removeproduct', {
                    id: item_id
                });
            });

            /**
             * Изменение количества продуктов в корзине
             */
            jQuery(document).on('click', mainContainer + ' ._plus', function(e) {
                e.preventDefault();
                var th = $(this),
                    inp = th.siblings('input'),
                    val = parseInt(inp.val()),
                    item_id = jQuery(this).data('productid');

                var $items = [];

                if( item_id > 0 ){
                    $items[item_id] = val + 1;
                }

                var $data = {
                    items: $items
                };

                $(badgeContainer).cart('updateproduct', $data);
            });
            jQuery(document).on('click', mainContainer + ' ._minus', function(e) {
                e.preventDefault();
                var th = $(this),
                    inp = th.siblings('input'),
                    val = parseInt(inp.val()),
                    item_id = jQuery(this).data('productid');

                var $items = [];

                val = val - 1;

                if( val <= 0 ){
                    alert('Вы не можете заказать меньше одной единицы товара.');
                    return;
                }

                if( item_id > 0 ){
                    $items[item_id] = val;
                }

                var $data = {
                    items: $items
                };

                $(badgeContainer).cart('updateproduct', $data);
            });

        },

        /**
         * Обновление значка корзины
         */
        badge : function (){
            jQuery.ajax({
                url: urls.cart.badge,
                type: 'get',
                dataType: 'html',
                success: function (data) {
                    jQuery(badgeContainer).html(data);
                },
                error: function (e) {
                    console.error('Ошибка отправки данных заказа.');
                }
            });
        },

        /**
         * Открытие корзины в модальном окне с прелоадером
         * param.refresh отвечает за прелоадер (то есть используем его, если открываем модалку впервые).
         * param.content передаем контент для модалки (то есть обновляем без запроса к серверу).
         */
        basket : function( param ) {
            if ( document.openCartProcess === 0 ){
                /** Запускаем прелоадер и открываем модалку */
                preloader.start.call( this, param );

                if ( param != null && param.content != null && param.content != "" ){
                    preloader.end.call();
                    $(mainContainer).html(param.content);
                } else {
                    jQuery.ajax({
                        url: urls.cart.get,
                        type: 'get',
                        dataType: 'html',
                        success: function (data) {
                            $(mainContainer).html(data);
                        },
                        complete: function () {
                            preloader.end.call();
                        },
                        error: function (e) {
                            $(mainContainer).modal('hide');
                            console.error('Ошибка отправки данных заказа.');
                        }
                    });
                }
            } else {
                return false;
            }
        },

        /**
         * Удаление продукта с корзины
         */
        removeproduct: function( param ){
            if( param.id === undefined) {
                return console.error('Параметр item_id не был передан.')
            }

            var $items = [];

            if( param.id > 0 ){
                $items[param.id] = param.id;
            }

            var $data = {
                items: $items
            };

            $data[$('meta[name="csrf-param"]').attr("content")] = $('meta[name="csrf-token"]').attr("content");

            jQuery.ajax({
                url: urls.cart.remove,
                data: $data,
                type: 'post',
                dataType: 'html',
                async: false,
                cache: false,
                success: function (data) {
                    $(document).cart('basket', {refresh:true, content: data});
                    $(document).cart('badge');
                },
                error: function (e) {
                    console.error('Ошибка отправки данных товара.');
                }
            });

        },

        /**
         * Обновление количества продукта(ов)
         */
        updateproduct : function ( data ){

            data[$('meta[name="csrf-param"]').attr("content")] = $('meta[name="csrf-token"]').attr("content");

            jQuery.ajax({
                url: urls.cart.update,
                type: 'post',
                data: data,
                dataType: 'html',
                async: false,
                cache: false,
                success: function (data) {
                    $(document).cart('basket', {refresh:true, content: data});
                    $(document).cart('badge');
                },
                error: function (e) {
                    console.error('Ошибка отправки данных заказа.');
                }
            });
        },

        /**
         * Добавление продукта в корзину
         */
        addproduct: function( param ){
            if( param.id === undefined) {
                return console.error('Параметр item_id не был передан.')
            }

            param.quantity = ( param.quantity === undefined ? 1 : param.quantity );

            var $items = [];

            if( param.id > 0 ){
                $items[param.id] = param.quantity;
            }

            var $data = {
                items: $items
            };

            $data[$('meta[name="csrf-param"]').attr("content")] = $('meta[name="csrf-token"]').attr("content");

            jQuery.ajax({
                url: urls.cart.add,
                data: $data,
                type: 'post',
                dataType: 'json',
                async: false,
                cache: false,
                success: function (data) {
                    $(document).cart('basket');
                    $(document).cart('badge');
                },
                error: function (e) {
                    console.error('Ошибка отправки данных товара.');
                }
            });


        },


        /**
         * Change Card Item
         */
        changeCardItem: function ( data ) {
            for(var name in data.params) {
                if (data.params.hasOwnProperty(name)) {
                    var item = jQuery(data.item).find('.product_' + name);

                    if ( item.length > 0 ){
                        console.log(  );
                        if ( name == 'price' || name == 'commonPrice') {
                            data.params[name] = parseFloat(data.params[name]).toFixed(2);
                        }
                        if( data.params[name] === null || data.params[name] === 'NaN' ) {
                            jQuery(item).parent().hide();
                        } else {
                            jQuery(item).parent().show();
                            jQuery(item).html(data.params[name]);
                        }
                    } else {
                        console.warn('Элемент .product_' + name + " не был найден для подмены данных.")
                        jQuery(data.item).find('.product_' + name).parent().hide();
                    }
                }
            }
            if ( data.params.id != null ){
                jQuery(data.item).find(buyButton).attr('data-productid', data.params.id).data('productid', data.params.id);
                jQuery(data.item).find(buyButton).attr('data-id', data.params.id).data('id', data.params.id);
            }
            if(data.params.comparePrice == null){
                jQuery(data.item).find('.product_comparePrice').parent().parent().hide();
            } else {
                jQuery(data.item).find('.product_comparePrice').parent().parent().show();
            }
        },
        /**
         * Change Card Product
         */
    };

    $.fn.cart = function (method) {
        if (methods[method]) {
            return methods[method].apply(this, Array.prototype.slice.call(arguments, 1));
        } else if (typeof method === 'object' || !method) {
            return methods.init.apply(this, arguments);
        } else {
            $.error('Метод с именем ' + method + ' не существует для jQuery.cart');
        }
    };
})(jQuery);

jQuery(document).cart();
/**
var addToCart = function(section) {
    if (section === undefined) {
        section = $('body');
    }
    $('.product-order-item-wrapper', section).each(function () {
        var item = $(this);
        var button = $('.add-to-cart', item);

        button.click(function (e) {
            e.preventDefault();

            var id = item.attr('data-id');
            var quantity = $('input[name=quantity]', item);

            var el = $(this);
            if (el.hasClass('disable')) {
                window.location.href = '/cart';
            }

            quantity = quantity === undefined ? 1 : quantity.val();

            $items = [];
            $items[id] = quantity;

            var $data = {
                items: $items
            };
            $data[$('meta[name="csrf-param"]').attr("content")] = $('meta[name="csrf-token"]').attr("content");

            console.log($data);
            $.ajax({
                url: '/cart/add/',
                data: $data,
                type: 'post',
                dataType: 'json',
                async: false,
                cache: false,
                success: function (data) {
                    if (data.status !== true) {
                        console.log(data.status, data.status !== true);
                    } else {
                        if ( data.quantity * 1 > 0 ) {
                            $('#cart-modal-badge').show().html(data.quantity);
                        } else {
                            $('#cart-modal-badge').hide().html('');
                        }

                    }
                },
                error: function (e) {
                    alert('Ошибка отправки данных заказа.');
                }
            });
            return false;
        });
    });
};

jQuery(function($) {
    addToCart();
});
 */
