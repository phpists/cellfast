<?php
/** @var $this \yii\web\View */
///** @var $component \common\components\GdsCalc\components\GdsCalcDrain */
/** @var $params array */
/** @var $calcResult array */
/** @var $systems \common\models\ProductFeatureValue */
///** @var $colors \common\models\ProductFeatureValue */


$js = <<<JS
$('.product__info__tab').each(function () {
 var tab = $(this);
 var color = $('.colors-tabs-item.active', tab).attr('data-colorid');
 if (color) {
     setGdsCalcColor(color, tab);
 }
 $('.colors-tabs-item', tab).click(function() {
     color = $(this).attr('data-colorid');
    setGdsCalcColor(color, tab);
 });
});

function setGdsCalcColor(color, tab) {
    $('.colors-tabs-item', tab).removeClass('active');
    $('.colors-tabs-item[data-colorid='+ color +']', tab).addClass('active');
    $('.color-cnts-item', tab).removeClass('active');
    $('.color-cnts-item[data-colorid='+ color +']', tab).addClass('active');
}
JS;
$this->registerJs($js);

$columns = [
//    'key',
    'name' => [
        'attribute' => 'name',
        'label' => Yii::t('app', 'Элемент'),
        'format' => 'raw',
        'value'=> function($model) {
            return "<span class='img'>". \noIT\core\helpers\Html::img($model['image']) ."</span>&nbsp;". $model['name'];
        }
    ],
   'sku' => [
        'attribute' => 'sku',
        'label' => Yii::t('app', 'Артикул'),
       'format' => 'raw',
       'enableSorting' => false,
        'value' => function($model) {
            return \noIT\core\helpers\Html::tag('span', $model['sku'], ['style' => 'white-space: nowrap']);
        }
    ],
    'price' => [
        'attribute' => 'price',
        'label' => Yii::t('app', 'Цена'),
    ],
    'count' => [
        'attribute' => 'count',
        'label' => Yii::t('app', 'Кол-во'),
    ],
    'summ' => [
        'attribute' => 'summ',
        'label' => Yii::t('app', 'Сумма'),
    ],
];
?>

<div class="product__info">
    <?php if (count($calcResult) > 1) :?>
    <div class="product__info__nav">
        <ul>
            <?php foreach (array_values($calcResult) as $i => $systemResult) :?>
                <li <?= ($i === 0 ? ' class="active"' : '') ?>><a href="tab<?= $i ?>"><span><?= $systemResult['system']->value_label ?></span></a></li>
            <?php endforeach ?>
        </ul>
    </div>
    <?php endif ?>
    <div class="product__info__tabs">
        <?php foreach (array_values($calcResult) as $i => $systemResult) :?>
        <div data-tab="tab<?= $i ?>" class="product__info__tab<?= ($i === 0 ? ' active' : '') ?>">
            <div class="product__info__cnt">
                <div class="product__info__cnt__txt">
                    <div class="colors-tabs">
                        <span class="colors-tabs-title"><?= Yii::t('app', 'Доступные цвета') ?></span>
                        <?php foreach (array_values($systemResult['colors']) as $i => $color) :?>
                            <div class="colors-tabs-item<?= $i == 0 ? ' active' : '' ?>" data-colorid="<?= $color->id ?>" title="<?= $color->value_label ?>">
                                <span style="background: <?= $color->value ?>;" class="_icon">
                                    <svg class="svg-icon check-icon">
                                        <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="/img/template/svg-sprite.svg#check-icon"></use>
                                    </svg>
                                </span>
                            </div>
                        <?php endforeach ?>
                    </div>
                    <div class="color-cnts">
                        <?php foreach ($systemResult['products'] as $colorId => $rows) :?>
                            <div class="color-cnts-item" data-colorid="<?= $colorId?>">
                                <?php
                                $dataProvider = new \yii\data\ArrayDataProvider([
    //                                'id' => 'products-of-system-'. $systemResult['system']->id,
                                    'allModels' => $rows,
                                ]);

                                $dataProvider->sort = new \yii\data\Sort([
                                    'attributes' => [
                                        'sku' => []
                                    ],
                                    'defaultOrder' => [
                                        'sku' => SORT_ASC,
                                    ],
                                ]);

                                $columns['summ']['footer'] = "<strong>". $systemResult['summs'][$colorId] ."&nbsp;грн.</strong>";
                                ?>
                                <?= \yii\grid\GridView::widget([
                                    'dataProvider' => $dataProvider,
                                    'columns' => $columns,
                                    'layout' => '{items}',
                                    'showFooter' => true,
                                ])?>
                            </div>
                        <?php endforeach ?>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach ?>
    </div>
    <div class="caption">
        <p style="color: #001354; font-weight: bold">
            <?= Yii::t('app', 'ВНИМАНИЕ! Данный расчет не является точным, так как каждое здание имеет свои особенности. Кроме этого, наша водосточная система – это «конструктор» и некоторые узлы можно монтировать, используя разные элементы.') ?>
        </p>
    </div>
</div>
