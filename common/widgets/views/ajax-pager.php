<?php

/** @var \yii\data\Pagination $pagination */
/** @var string $listView */
/** @var string $url */
/** @var string $someMoreCaption */
/** @var string $wrapper */

$this->registerJs("
$('.ajax-pager a').click(function(e) {
    e.preventDefault();

    var a = $(this);
    overlayShow(a);
    var page = parseInt(a.attr('data-page'));
    if (!page) {
        page = 1;
    }
    var data = ". json_encode(Yii::$app->request->get()) .";
    data.page = parseInt(a.attr('data-page')) + 1;
    data['per-page'] = ". $pagination->pageSize .";
    data.view = '$listView';
    $.ajax({
        url: '$url',
        data: data,
        cache: false,
        success: function(html){
            $('$wrapper').append(html);
            initSameHeight();
            initPriceCaption();
            initProductCompare();
            addToCart();
            page++;
            overlayHide(a);
            if (page >= ". $pagination->pageCount .") {
                a.hide();
                $('.pagination-with-ajaxpager').hide();
            } else {
                a.attr('data-page', page);
            }
        }
    });
});
");
?>

<div class="ajax-pager">
    <a href="#" data-page="<?= $pagination->page + 1?>"><?= Yii::t('app', $someMoreCaption, ['count' => $pagination->pageSize])?>?></a>
</div>
