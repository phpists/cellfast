<?php

use yii\helpers\Url;
use yii\helpers\Html;
use kartik\grid\GridView;
use common\helpers\AdminHelper;

/* @var $this yii\web\View */
/* @var $model backend\models\Product */
$__params = require __DIR__ .'/__params.php';

$updateUrl = Url::to(['item-form']);
$js = <<<JS
$('.item-update').click(function(e) {
    e.preventDefault();
    var id = $(this).attr('data-id');
    
    $.ajax({
        url: '$updateUrl',
        type: 'get',
        data: {
            id: id
        },
        success: function(html) {
          overlayHide($('body'));
          var modal = $('#item-update-modal');
          $('.modal-body', modal).html(html);
          modal.modal('show');
          productItemDataModalForm($('#item-update-form'), $('#product-items-wrapper'), $('#item-update-modal'));
        },
          error: function(e) {
            alert('Ошибка связи');
            console.log(e);
          }
    });
    
    $('#orderdata-id').val();
});
$('.item-delete').click(function(e) {
   e.preventDefault();
   if (confirm("Вы уверены, что хотите удалить замер помещения?")) {
       overlayShow($('body'));
       $.ajax({
          url: $(this).attr('href'),
          type: 'post',
          data: {},
          success: function(html) {
              overlayHide($('body'));
              $('#product-items-wrapper').html(html);
          },
          error: function(e) {
            alert('Ошибка удаления');
            console.log(e);
          }
      });
   }
   return false;
});
JS;
$this->registerJs($js);

$columns = [];

$columns = array_merge($columns, [

	[
		'attribute' => 'image',
		'format' => 'image',
		'value' => function($model, $key, $index, $column) {
			return $model->getThumbUploadUrl('image', 'thumb_list');
		}
	],

	[
		'attribute' => 'sku',
		'format' => 'raw',
		'value' => function($model, $key, $index, $column) {
			return $model->sku;
		}
	],
]);

foreach ($model->type->product_features_define as $feature_relation) {
	$columns[] = [
		'attribute' => "featureGroupedValues[{$feature_relation->product_feature_id}]",
		'format' => 'raw',
		'label' => $feature_relation->product_feature->name,
		'value' => function($model, $key, $index, $column) use ($feature_relation) {
			/** @var \common\models\ProductItem */
			return @$model->featureGroupedValues[$feature_relation->product_feature_id]->value_label;
		}
	];
}

//$columns[] = [
//	'attribute' => 'native_name',
//	'format' => 'raw',
//	'value' => function($model, $key, $index, $column) {
//		return Html::a($model->native_name, ['update', 'id' => $model->id]);
//	}
//];

foreach (\common\models\PriceType::getPriceTypes() as $priceType) {
    $columns[] = [
        'attribute' => "price[{$priceType->id}]",
        'format' => 'raw',
        'label' => $priceType->name, // \common\models\PriceType::getDefault(true) ? \common\models\PriceType::getDefault(true)->native_name : null,
        'value' => function ($model, $key, $index, $column) use ($priceType) {
            /** @var \common\models\ProductItem $model */
            return "{$model->getPrice($priceType->id)}" . ($model->getCommonPrice($priceType->id) ? "&nbsp;<strike>{$model->getCommonPrice($priceType->id)}</strike>" : '');
        }
    ];
}

$columns[] = [
    'class' => '\kartik\grid\BooleanColumn',
    'attribute' => AdminHelper::FIELDNAME_STATUS,
//        'label' => '',
    'trueLabel' => 'Видимый',
    'falseLabel' => 'Скрытый'
];

$columns[] = [
	'class' => 'yii\grid\ActionColumn',
	'template' => '{item-update}&nbsp;&nbsp;&nbsp;{item-delete}',
	'buttons' => [
		'item-update' => function ($url, $model) {
			return Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url, [
				'class' => 'item-update',
				'data-pjax' => 0,
				'data-id' => $model->id,
			]);
		},
		'item-delete' => function ($url, $model) {
			return Html::a('<span class="glyphicon glyphicon-trash"></span>', $url, [
				'class' => 'item-delete',
				'data-pjax' => 0,
			]);
		},
	],
	'urlCreator' => function($action, $_model, $key, $index) {
		switch($action) {
			case 'item-update':
				return Url::to(['item-update', 'id' => $_model->id, 'product_id' => $_model->product_id]);
				break;
			case 'item-delete':
				return Url::to(['item-delete', 'id' => $_model->id, 'product_id' => $_model->product_id]);
				break;
		}
	},
];

$gridOptions = [
    'id' => $__params['id'] .'-items',
//    'filterModel' => new \backend\models\ProductItemSearch(),
    'dataProvider' => $model->getItemsDataProvider(Yii::$app->request->queryParams),
    'columns' => $columns,

    'responsive' => true,
    'striped' => true,
    'hover' => true,
    'pjax' => true,
    'persistResize' => false,
    'pjaxSettings' => [
        'neverTimeout' => true,
    ],
    'floatHeader' => true,
    'floatHeaderOptions' => [
        'position' => 'auto',
    ],

    'panel' => [
        'heading' => false,
        'footer' => false,
    ],
    'toolbar'=> false,

    'summary' => '',
];

?>
<div class="items">
    <?= GridView::widget($gridOptions); ?>


</div>
