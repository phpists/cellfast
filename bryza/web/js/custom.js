var overlayShow = function(el) {
    $('<div id="process-overlay"></div>').appendTo(el);
};

var overlayHide = function(el) {
    $('#process-overlay', el).remove();
};

// функция для определения ширины скролла
function getScrollbarWidth() {
    var outer = document.createElement("div");
    outer.style.visibility = "hidden";
    outer.style.width = "100px";
    outer.style.msOverflowStyle = "scrollbar";
    document.body.appendChild(outer);
    var widthNoScroll = outer.offsetWidth;
    outer.style.overflow = "scroll";
    var inner = document.createElement("div");
    inner.style.width = "100%";
    outer.appendChild(inner);
    var widthWithScroll = inner.offsetWidth;
    outer.parentNode.removeChild(outer);
    return widthNoScroll - widthWithScroll;
}

/* моб меню */
$(document).on('click', '.header__mmenu a', function(event) {
    $('header.header').toggleClass('_mmob-menu');
    event.preventDefault();
});
$('body').on('click', function() {
    if ($(event.target).closest(".header__mmenu").length) return;
    if ($(event.target).closest(".header__nav").length) return;
    $('header.header').removeClass('_mmob-menu');
});

/* слайдер сверху страницы */
$('.mtop__slider-wrap').each(function(index, el) {
    var th = $(this),
        slider = th.find('.mtop__slider'),
        dotsWrap = slider.siblings('.mtop__slider-dots');

    slider.slick({
        prevArrow: '',
        nextArrow: '',
        edgeFriction: 0,
        slidesToShow: 1,
        speed: 500,
        infinite: true,
        dots: true,
        appendDots: dotsWrap,
        autoplay: true,
        autoplaySpeed: 5000,
    })
});

/* слайдер сертификатов */
$('.mserts__slider-wrap').each(function(index, el) {
    var th = $(this),
        slider = th.find('.mserts__slider'),
        prev = slider.siblings('.mserts__arr.__prev'),
        next = slider.siblings('.mserts__arr.__next');

    slider.slick({
        prevArrow: prev,
        nextArrow: next,
        edgeFriction: 0,
        slidesToShow: 3,
        speed: 500,
        infinite: true,
        dots: false,
        responsive: [
            {
                breakpoint: 992,
                settings: {
                    slidesToShow: 2,
                    slidesToScroll: 2
                }
            },
            {
                breakpoint: 768,
                settings: {
                    slidesToShow: 1,
                    slidesToScroll: 1
                }
            }
        ]
    })
});

/* слайдер отзывов в карточке */
$('.product__info__rews').each(function(index, el) {
    var th = $(this),
        slider = th.find('.product__info__rews__slider'),
        prev = th.find('.product__info__rews__btn._prev'),
        next = th.find('.product__info__rews__btn._next');

    slider.slick({
        prevArrow: prev,
        nextArrow: next,
        edgeFriction: 0,
        slidesToShow: 1,
        speed: 500,
        infinite: true,
        dots: false,
        adaptiveHeight: true
    })
});

/* слайдер других статей */
$('.related__content__slider').each(function(index, el) {

    var th = $(this),
        slider = th.find('.slider__wrapper'),
        prev = th.find('.slider__arrow.__prev'),
        next = th.find('.slider__arrow.__next');

    slider.slick({
        prevArrow: prev,
        nextArrow: next,
        edgeFriction: 0,
        slidesToShow: 3,
        speed: 500,
        infinite: true,
        dots: false,
        responsive: [
            {
                breakpoint: 992,
                settings: {
                    slidesToShow: 2,
                    slidesToScroll: 2
                }
            },
            {
                breakpoint: 768,
                settings: {
                    slidesToShow: 1,
                    slidesToScroll: 1
                }
            }
        ]
    })
});


/* валидация форм */
// добавление метода для правильной валидации емейла
$.validator.addMethod("emailfull", function(value, element) {
    return this.optional(element) || /^([a-z\d!#$%&'*+\-\/=?^_`{|}~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]+(\.[a-z\d!#$%&'*+\-\/=?^_`{|}~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]+)*|"((([ \t]*\r\n)?[ \t]+)?([\x01-\x08\x0b\x0c\x0e-\x1f\x7f\x21\x23-\x5b\x5d-\x7e\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|\\[\x01-\x09\x0b\x0c\x0d-\x7f\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))*(([ \t]*\r\n)?[ \t]+)?")@(([a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|[a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF][a-z\d\-._~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]*[a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])\.)+([a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|[a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF][a-z\d\-._~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]*[a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])\.?$/i.test(value);
}, "Please enter valid email address!");

function initFormSubmitter(formName, formType) {

    var $form = $(formName);

    var $formButton = $form.find('.btn-send-handler');

    $formButton.on('click', function () {

        $form.find('input[name="secret_form_key"]').val("not-machine");

        $form.find('button[type="submit"]').click();

    });

    var message, messageError = '';

    if(formType === 'subscribeForm') {
        message = 'Вы успешно подписались на рассылку!';
        messageError = 'В данный момент невозможно совершить подписку. Пожалуйста, попробуйте подписаться еще раз позже.';
    } else if(formType === 'contactForm') {
        message = 'Наш менджер свяжется с Вами в ближайшее время!';
        messageError = 'В данный момент невозможно отправить форму. Пожалуйста, попробуйте подписаться еще раз позже.';
    }

    $form.on("beforeSubmit", function (event) {

        $.ajax({
            type: $(this).attr("method"),
            url: $(this).attr("action"),
            data: $(this).serialize(),
            success: function (response) {
                swal(response.title, message, response.status, {button: "Закрыть"})
            },
            error: function () {
                swal("Ошибка!", messageError, "error", {button: "Закрыть"})
            }
        }).done(function () {
            event.target.reset()
        });

        return false;
    })

}

initFormSubmitter('#footerSubsForm', 'subscribeForm');
initFormSubmitter('#write-to-us-form-1', 'contactForm');
initFormSubmitter('#write-to-us-form-2', 'contactForm');
initFormSubmitter('#write-to-us-form-3', 'contactForm');
initFormSubmitter('#write-to-us-form-4', 'contactForm');
initFormSubmitter('#write-to-us-form-5', 'contactForm');

// маска для инпутов
$('.masked-input').each(function(index, el) {
    var th = $(this),
        m = th.attr('data-mask');
    th.mask(m);
});

// если object-fit не поддержывается браузером
// скрипт берет картинку и вставляет ее фоном
// стилями уже заданы правильные стили для фона и
// картинки если скрипт отработает
if ($('.ofit-block').length > 0 && !Modernizr.objectfit) {
    $('.ofit-block').each(function () {
        var th = $(this),
            src = th.find('> img').attr('src');
        th.css('background-image','url(' + src + ')');
    })
}

// лайтбокс сертификатов
// $(document).on('click', '.mserts__it__img', function(event) {
//     var th = $(this),
//         slide = th.closest('.mserts__slide'),
//         slideIndex = parseInt(slide.attr('data-slick-index'));
//
//     if (th.closest('.mserts__slide').length > 0) {
//         var imgsArr = [];
//
//         $('.mserts__slide').not('.slick-cloned').each(function(index, el) {
//             var link = $(this).find('.mserts__it__img').attr('href');
//             imgsArr.push(link);
//         });
//
//         blueimp.Gallery(imgsArr,{
//             toggleControlsOnSlideClick: false,
//             continuous: true,
//             index: slideIndex,
//             onopen: function () {
//                 $('body').css('padding-right', getScrollbarWidth());
//             },
//             onclosed: function () {
//                 $('body').css('padding-right', 0);
//             },
//         });
//     } else {
//         var imgsArr = [];
//
//         imgsArr.push(th.attr('href'));
//
//         blueimp.Gallery(imgsArr,{
//             toggleControlsOnSlideClick: false,
//             continuous: true,
//             index: 0,
//             onopen: function () {
//                 $('body').css('padding-right', getScrollbarWidth());
//             },
//             onclosed: function () {
//                 $('body').css('padding-right', 0);
//             },
//         });
//     }
//
//
//     event.preventDefault();
// });

// поп-ап сертификата на странице сертификата
$(document).on('click', '.sertop__cnt__img', function(event) {
    var th = $(this);
    var imgsArr = [];

    imgsArr.push(th.attr('href'));

    blueimp.Gallery(imgsArr,{
        toggleControlsOnSlideClick: false,
        continuous: true,
        index: 0,
        onopen: function () {
            $('body').css('padding-right', getScrollbarWidth());
        },
        onclosed: function () {
            $('body').css('padding-right', 0);
        },
    });
    event.preventDefault();
});


// радио-баттон с коллапсом в фильтре
$('.ctlg__fltr__bl__radios__it._w-accord input[type="radio"]:checked').closest('.ctlg__fltr__bl__radios__it').find('._w-accord__txt').show();
$(document).on('change', '.ctlg__fltr__bl__radios__it._w-accord input[type="radio"]', function() {
    $(this).closest('.ctlg__fltr__bl__radios').find('.ctlg__fltr__bl__radios__it').each(function(index, el) {
        if ($(this).find('input[type="radio"]:checked').length) {
            $(this).find('._w-accord__txt').slideDown(500);
        } else {
            $(this).find('._w-accord__txt').slideUp(500);
        }
    });
});

// фильтр каталога на мобилке
$(document).on('click', '.catalog__filter__open button', function() {
    $('.catalog__filter__filters__wrap').addClass('active');
    $('.catalog__filter__filters__wrap').removeClass('catalog__filter__wrap__hidden');
    $('body').css('padding-right', getScrollbarWidth()).addClass('modal-open');
});
$(document).on('click', '.catalog__manuals__open button', function() {
    $('.catalog__filter__manuals__wrap').addClass('active');
    $('.catalog__filter__manuals__wrap').removeClass('catalog__filter__wrap__hidden');
    $('body').css('padding-right', getScrollbarWidth()).addClass('modal-open');
});
$(document).on('click', '.catalog__filter__close a', function(event) {
    $('.catalog__filter__wrap').removeClass('active');
    $('body').css('padding-right', 0).removeClass('modal-open');
    event.preventDefault();
});

// toggle фильров и инструкций
$('.js_catalog__filter__block').each(function () {
    var block = $(this);
    var title = $('.catalog__filter__title', block);
    title.on('click', function() {
        if (block.hasClass('catalog__filter__wrap__hidden')) {
            $('.js_catalog__filter__block').addClass('catalog__filter__wrap__hidden');
            block.removeClass('catalog__filter__wrap__hidden');
        }
    });
});

// слайдер в карточке товаров
$('.product__sliders').each(function(index, el) {
    var th = $(this),
        sliderBig = th.find('.product__slider__big'),
        sliderNav = th.find('.product__slider__nav');

    sliderBig.slick({
        prevArrow: '',
        nextArrow: '',
        edgeFriction: 0,
        slidesToShow: 1,
        speed: 500,
        infinite: false,
        dots: false,
        asNavFor: sliderNav,
    })

    sliderNav.slick({
        prevArrow: '',
        nextArrow: '',
        edgeFriction: 0,
        slidesToShow: 5,
        speed: 500,
        infinite: false,
        dots: false,
        asNavFor: sliderBig,
        focusOnSelect: true,
        responsive: [
            {
                breakpoint: 768,
                settings: {
                    slidesToShow: 4
                }
            }
        ]
    })
});

$(document).on('click', '.product__slider__big__slide a', function(event) {
    var th = $(this),
        slide = th.closest('.product__slider__big__slide'),
        slideIndex = parseInt(slide.attr('data-slick-index'));

    var imgsArr = [];

    $('.product__slider__big__slide').not('.slick-cloned').each(function(index, el) {
        var link = $(this).find('a').attr('href');
        imgsArr.push(link);
    });

    blueimp.Gallery(imgsArr,{
        toggleControlsOnSlideClick: false,
        continuous: false,
        index: slideIndex,
        onopen: function () {
            $('body').css('padding-right', getScrollbarWidth());
        },
        onclosed: function () {
            $('body').css('padding-right', 0);
        },
    });

    event.preventDefault();
    event.preventDefault();
});

// табы в карточке товара
$(document).on('click', '.product__info__nav a', function(event) {
    var th = $(this),
        tab = th.attr('href');

    th.closest('li').addClass('active').siblings('li').removeClass('active');

    $('.product__info__tab[data-tab="' + tab + '"]').addClass('active').siblings('.product__info__tab').removeClass('active');
    event.preventDefault();
});

// +/- в товара, а так-же
$(document).on('click', '.product__top__right__counter ._plus, .checkout__block__table__counter ._plus', function(event) {
    var th = $(this),
        inp = th.siblings('input'),
        val = parseInt(inp.val());
    inp.val(val+1);
    event.preventDefault();
});
$(document).on('click', '.product__top__right__counter ._minus', function(event) {
    var th = $(this),
        inp = th.siblings('input'),
        val = parseInt(inp.val());

    if (val - 1 > 0) {
        inp.val(val-1);
    }
    event.preventDefault();
});
$(document).on('click', '.checkout__block__table__counter ._minus', function(event) {
    var th = $(this),
        inp = th.siblings('input'),
        val = parseInt(inp.val());

    if (val - 1 >= 0) {
        inp.val(val-1);
    }
    event.preventDefault();
});

// карта в контактах
if ($('.contacts__map').length > 0) {
    $('.contacts__map').each(function () {
        var  th = $(this),
            coordsD = th.attr('data-coords'),
            coords = coordsD.split(',');

        GoogleMapsLoader.KEY = 'AIzaSyB02LyYGpZSmZDcXPFtK2DTxshpdOmT_LA';
        GoogleMapsLoader.VERSION = '3.14';
        GoogleMapsLoader.LIBRARIES = ['geometry', 'places'];
        GoogleMapsLoader.load(function(google) {
            var map;
            var center_w = coords[0];
            var center_h = coords[1];

            var map_center = new google.maps.LatLng(center_w, center_h);

            var mapCanvas = th[0];

            var mapStyle = [
                {
                    "featureType": "water",
                    "elementType": "geometry.fill",
                    "stylers": [
                        {
                            "color": "#d3d3d3"
                        }
                    ]
                },
                {
                    "featureType": "transit",
                    "stylers": [
                        {
                            "color": "#808080"
                        },
                        {
                            "visibility": "off"
                        }
                    ]
                },
                {
                    "featureType": "road.highway",
                    "elementType": "geometry.stroke",
                    "stylers": [
                        {
                            "visibility": "on"
                        },
                        {
                            "color": "#b3b3b3"
                        }
                    ]
                },
                {
                    "featureType": "road.highway",
                    "elementType": "geometry.fill",
                    "stylers": [
                        {
                            "color": "#ffffff"
                        }
                    ]
                },
                {
                    "featureType": "road.local",
                    "elementType": "geometry.fill",
                    "stylers": [
                        {
                            "visibility": "on"
                        },
                        {
                            "color": "#ffffff"
                        },
                        {
                            "weight": 1.8
                        }
                    ]
                },
                {
                    "featureType": "road.local",
                    "elementType": "geometry.stroke",
                    "stylers": [
                        {
                            "color": "#d7d7d7"
                        }
                    ]
                },
                {
                    "featureType": "poi",
                    "elementType": "geometry.fill",
                    "stylers": [
                        {
                            "visibility": "on"
                        },
                        {
                            "color": "#ebebeb"
                        }
                    ]
                },
                {
                    "featureType": "administrative",
                    "elementType": "geometry",
                    "stylers": [
                        {
                            "color": "#a7a7a7"
                        }
                    ]
                },
                {
                    "featureType": "road.arterial",
                    "elementType": "geometry.fill",
                    "stylers": [
                        {
                            "color": "#ffffff"
                        }
                    ]
                },
                {
                    "featureType": "road.arterial",
                    "elementType": "geometry.fill",
                    "stylers": [
                        {
                            "color": "#ffffff"
                        }
                    ]
                },
                {
                    "featureType": "landscape",
                    "elementType": "geometry.fill",
                    "stylers": [
                        {
                            "visibility": "on"
                        },
                        {
                            "color": "#efefef"
                        }
                    ]
                },
                {
                    "featureType": "road",
                    "elementType": "labels.text.fill",
                    "stylers": [
                        {
                            "color": "#696969"
                        }
                    ]
                },
                {
                    "featureType": "administrative",
                    "elementType": "labels.text.fill",
                    "stylers": [
                        {
                            "visibility": "on"
                        },
                        {
                            "color": "#737373"
                        }
                    ]
                },
                {
                    "featureType": "poi",
                    "elementType": "labels.icon",
                    "stylers": [
                        {
                            "visibility": "off"
                        }
                    ]
                },
                {
                    "featureType": "poi",
                    "elementType": "labels",
                    "stylers": [
                        {
                            "visibility": "off"
                        }
                    ]
                },
                {
                    "featureType": "road.arterial",
                    "elementType": "geometry.stroke",
                    "stylers": [
                        {
                            "color": "#d6d6d6"
                        }
                    ]
                },
                {
                    "featureType": "road",
                    "elementType": "labels.icon",
                    "stylers": [
                        {
                            "visibility": "off"
                        }
                    ]
                },
                {},
                {
                    "featureType": "poi",
                    "elementType": "geometry.fill",
                    "stylers": [
                        {
                            "color": "#dadada"
                        }
                    ]
                }
            ]

            var mapOptions = {
                center: map_center,
                zoom: 15,
                styles: mapStyle,
                mapTypeId: google.maps.MapTypeId.ROADMAP,
                scrollwheel: false,
                draggable: true,
                disableDefaultUI: true,
                zoomControl: true,
            }

            map = new google.maps.Map(mapCanvas, mapOptions);

            var icon1 = "img/template/map-marker.png";
            var myLatLng = new google.maps.LatLng(center_w, center_h);
            var beachMarker = new google.maps.Marker({
                position: myLatLng,
                map: map,
                icon: icon1
            });

            google.maps.event.addDomListener(window, "resize", function() {
                var center = map.getCenter();
                google.maps.event.trigger(map, "resize");
                map.setCenter(center);
            });
        });
    });
}

// карта в где купить
function buyMap() {
    var map,
        locations = [],
        markers = [],
        icon = "/img/svg/location-icon-g_bryza.svg",
        iconHover = "/img/svg/location-icon-g_bryza.svg";
    if ($('.js-buy-map').length > 0) {
        var $el = $('.js-buy-map');
        GoogleMapsLoader.KEY = 'AIzaSyB02LyYGpZSmZDcXPFtK2DTxshpdOmT_LA';
        GoogleMapsLoader.VERSION = '3.14';
        GoogleMapsLoader.LIBRARIES = ['geometry', 'places'];
        GoogleMapsLoader.load(function(google) {
            var map_center = new google.maps.LatLng(48.8585622,31.9657707),
                mapCanvas = $el[0],
                mapOptions = {
                    center: map_center,
                    zoom: 6,
                    mapTypeId: google.maps.MapTypeId.ROADMAP
                },
                navigationLinks = $('.js-buy-navigation-content').find('.js-buy-navigation-item-list__item-name'),
                navigationLinkId = 0;
            map = new google.maps.Map(mapCanvas, mapOptions);
            navigationLinks.each(function (i, e) {

                var $el = $(e);

                if($el.attr('data-item-location') !== undefined) {

                    var $elPosition = $el.attr('data-item-location'),
                        $elPositionArr = $elPosition.split(','),
                        $elPositionLat = $elPositionArr[0],
                        $elPositionLng = $elPositionArr[1];

                    $elLocation = new google.maps.LatLng($elPositionLat, $elPositionLng);
                    $el.attr('data-item-id', navigationLinkId);
                    addMarker($elLocation, navigationLinkId);

                }

                navigationLinkId++;
            });
            function addMarker(location, id) {
                var marker = new google.maps.Marker({
                    position: location,
                    icon: icon,
                    markerId: id
                });
                markers.push(marker);
                google.maps.event.addListener(marker, 'click', (function (marker) {
                    return function () {
                        map.panTo(this.getPosition());
                        markerClick(this.markerId);
                    }
                })(marker));
            };
            var markerCluster = new MarkerClusterer(map, markers,
                {imagePath: 'https://developers.google.com/maps/documentation/javascript/examples/markerclusterer/m'});
        });
    }
    function markerClick(id) {
        var $navigationLink = $("[data-item-id=" + id + "]"),
            $navigationLinkZoom = parseInt($navigationLink.attr('data-item-zoom'));
        map.setZoom($navigationLinkZoom);
        buyMapNavigationItemsToggle($navigationLink);
        buyMapNavigationToggle($navigationLink, 'open');
        for (i = 0; i < markers.length; i++) {
            var marker = markers[i];
            if(marker.markerId == id){
                marker.setIcon(iconHover);
            } else{
                marker.setIcon(icon);
            }
        }
    }
    function buyMapNavigationToggle(el, action) {
        var $el = el,
            $elContainer = $el.closest('.js-buy-navigation-item'),
            $elList = $elContainer.find('.js-buy-navigation-item__content');
        if(action === 'open'){
            $elContainer.attr('data-state', 'open');
            $elContainer.addClass('is-open');
            $elList.slideDown();
            setTimeout(function () {
                $('#buy-navigation-content').mCustomScrollbar("scrollTo", $el);
            }, 500);
        } else{
            $elContainer.attr('data-state', 'close');
            $elContainer.removeClass('is-open');
            $elList.slideUp('slow');
        }
    };
    function buyMapNavigationItemsToggle(el) {
        var $el = el,
            $elContainer = $el.closest('.js-buy-navigation-item-list__item');
        $('.js-buy-navigation-item-list__item').removeClass('is-active');
        $elContainer.addClass('is-active');
    }
    $('.js-buy-navigation-item__link').click(function (e) {
        var $el = $(this),
            $elContainer = $el.closest('.js-buy-navigation-item'),
            $elContainerState = $elContainer.attr('data-state'),
            $elList = $elContainer.find('.js-buy-navigation-item__content');
        if($elContainerState === 'open'){
            buyMapNavigationToggle($el, 'close');
        } else{
            buyMapNavigationToggle($el, 'open');
        }
        e.preventDefault();
    });
    $('.js-buy-navigation-item-list__item-name').click(function (e) {
        var $el = $(this),
            $elId = $el.attr('data-item-id'),
            $elZoom = parseInt($el.attr('data-item-zoom'));
        for (i = 0; i < markers.length; i++) {
            var marker = markers[i];
            if(marker.markerId == $elId){
                map.panTo(marker.getPosition());
                map.setZoom($elZoom);
                marker.setIcon(iconHover);
            } else{
                marker.setIcon(icon);
            }
        }
        buyMapNavigationItemsToggle($el);
        e.preventDefault();
    });
    $('#buy-navigation-content').mCustomScrollbar({
        theme:"green-theme"
    });
};
buyMap();

/* слайдер на странице новости */
$('.galleryblock__slider-wrap').each(function(index, el) {
    var th = $(this),
        slider = th.find('.galleryblock__slider'),
        prev = th.find('.galleryblock__slider__arr.__prev'),
        next = th.find('.galleryblock__slider__arr.__next');

    slider.slick({
        prevArrow: prev,
        nextArrow: next,
        edgeFriction: 0,
        slidesToShow: 4,
        speed: 500,
        infinite: true,
        dots: false,
        responsive: [
            {
                breakpoint: 992,
                settings: {
                    slidesToShow: 3
                }
            },
            {
                breakpoint: 768,
                settings: {
                    slidesToShow: 2
                }
            },
            {
                breakpoint: 500,
                settings: {
                    slidesToShow: 1
                }
            }
        ]
    })
});
$(document).on('click', '.galleryblock__slide a', function(event) {
    var th = $(this),
        slide = th.closest('.galleryblock__slide'),
        slideIndex = parseInt(slide.attr('data-slick-index'));

    var imgsArr = [];

    $('.galleryblock__slide').not('.slick-cloned').each(function(index, el) {
        var link = $(this).find('a').attr('href');
        imgsArr.push(link);
    });

    blueimp.Gallery(imgsArr,{
        toggleControlsOnSlideClick: false,
        continuous: true,
        index: slideIndex,
        onopen: function () {
            $('body').css('padding-right', getScrollbarWidth());
        },
        onclosed: function () {
            $('body').css('padding-right', 0);
        }
    });

    event.preventDefault();
});



$(document).on('click', '.calculator__dropdown .dropdown-toggle', function (e) {

    var Target = $(this).attr('data-target');
    var Attribute = null;
    var AttributeParam = $(this).parents('.calculator__dropdown').attr('data-params');
    if( AttributeParam != null ){
        Attribute = JSON.parse(AttributeParam);
    }
    var tBox = $(this).offset().top + 50;
    var lBox = $(this).offset().left + 30;
    var calcMenu = '';

    $('#absolute_calculator .dropdown__select .calculate_size').attr('data-target', Target);

    if ( Attribute != null ) {
        $('#absolute_calculator .dropdown__select .calculate_size').attr('data-count', Attribute[0].count).html(Attribute[0].name);
        $('#absolute_calculator .calculator_inpack').val(Attribute[0].count);
        $(Attribute).each(function (key, item) {
            calcMenu += '<li data-count="' + item.count + '" class="calculator__item">' + item.name + '</li>';
        });
        calcMenu = '<ul>' + calcMenu + '</ul>';
    } else {
        calcMenu = '<ul><li>Список параметров пуст.</li></ul>';
    }

    $('#absolute_calculator .dropdown__menu').html(calcMenu);
    $('#absolute_calculator').css({'left': lBox, 'top': tBox}).toggleClass('open');

});

$(document).on('keyup',  function(e) {
    e = e || window.event;
    var isEscape = false;
    if ("key" in e) {
        isEscape = (e.key == "Escape" || e.key == "Esc");
    } else {
        isEscape = (e.keyCode == 27);
    }
    if (isEscape) {
        if($('#absolute_calculator').hasClass('open')) {
            $('#absolute_calculator').removeClass('open');
        }
    }
});

$(document).on('click', '#absolute_calculator .close-button', function () {
    $('#absolute_calculator').removeClass('open');
});

$(document).on('click', '#absolute_calculator .dropdown__select_container', function () {
    $(this).toggleClass('open');
});

$(document).on('click', '#absolute_calculator .dropdown__menu .calculator__item', function () {

    var Count = $(this).attr('data-count');

    $('#absolute_calculator .calculate_size').data('count', Count).html($(this).html());
    $('#absolute_calculator .calculator_packaged').val(1);
    $('#absolute_calculator .calculator_inpack').val(Count);

    $(this).parents('.dropdown__select_container').removeClass('open');

});

$(document).click(function (event) {

    if (!$(event.target).closest("#absolute_calculator,.dropdown-toggle.product__top__right__calc, .dropdown-toggle.checkout__block__table__calc").length) {

        $("#absolute_calculator").removeClass('open');

    }

});

$(document).on('click', '#absolute_calculator .calculator__ok', function () {

    var Count = $('#absolute_calculator .calculator_inpack').val();

    var Target = $('#absolute_calculator .calculate_size').attr('data-target');

    $(Target).val(Count);

    $('#absolute_calculator').removeClass('open');

});

$(document).on('change keyup', '#absolute_calculator .calculator_packaged', function () {

    var pack_max_size = parseInt($('#absolute_calculator .calculate_size').attr('data-count')); // ht
    var packs = parseFloat($(this).val());

    if( packs > 0 ){
        var current_items = Math.ceil(packs * pack_max_size);

        $('#absolute_calculator .calculator_inpack').val( current_items );
    }

});

$(document).on('change keyup', '#absolute_calculator .calculator_inpack', function () {

    var pack_max_size = parseInt($('#absolute_calculator .calculate_size').attr('data-count'));
    var inpack = parseFloat($(this).val());

    if( inpack > 0 ){
        var current_packs = Math.ceil(inpack / pack_max_size);

        $('#absolute_calculator .calculator_packaged').val( current_packs );
    }

});

$('.mabout__img').each(function () {
    var div = $(this);

    var alt = $('img', div).attr('alt');

    if (alt.length) {
        div.append("<span class='img-alt'>"+ alt +"</span>");
    }
});
