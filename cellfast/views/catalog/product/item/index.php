<?php

/**
 * Шаблон вывода списка комбинаций товара. Может использоваться для ajax подгрузки
 */

/* @var $this yii\web\View */
/* @var $product \common\models\Product */
/* @var $dataProvider \yii\data\ActiveDataProvider */
/* @var $empty string */

?>
<?php if (!$dataProvider->totalCount) :?>
    <div class="empty-result">
		<?= $empty?>
    </div>
<?php else :?>
    <?= \yii\grid\GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            'name',
            'sku',
            'price',
            [
                'label' => false,
                'format' => 'raw',
                'value' => function($model) use ($product) {
                    return \yii\helpers\Html::input('number', "amount[{$product->id}][{$model->id}]", 0, ['data-id' => $model->id]);
                }
            ],
        ],
    ])?>
<?php endif?>