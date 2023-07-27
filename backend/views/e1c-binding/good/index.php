<?php

use yii\helpers\Html;
use backend\widgets\MetronicModal;
use kartik\grid\GridView;
use kartik\editable\Editable;
use yii\helpers\ArrayHelper;

/** @var $this yii\web\View */
/** @var $active string */
/** @var $searchModel \backend\models\E1cGroupOfGoodSearch */
/** @var $dataProvider \yii\data\ActiveDataProvider */

$url = \noIT\core\helpers\Url::to(['binding-good']);

$js = <<<JS
jQuery('#w0').tab();

$('#binding-product').on('click', function(e) {
    e.preventDefault();
    var url = '$url';
    var id = $('#binding-product-list').val();
    var ids = $('#good-grid').yiiGridView('getSelectedRows');
    console.log(url +'?ids='+ ids.join(',') +'&id='+ id);
    if (!id) {
        alert('Требуется выбрать хотябы один товар');
        return;
    }
    if (!ids.length) {
        alert('Требуется выбрать хотябы один товар 1C');
        return;
    }
    window.location.href = url +'?ids='+ ids.join(',') +'&id='+ id;
});
JS;
$this->registerJs($js);

$__params = require __DIR__ .'/__params.php';

$this->title = $__params['items'];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Good')];

$columns = [
	[
		'class' => \kartik\grid\CheckboxColumn::className(),
	],
	[
		'attribute' => 'name',
		'format' => 'raw',
		'value' => function($model, $key, $index, $column) {
			return Html::a(($model->getAppEntity()->count('id') > 0 ? '+ ' : '- ') . $model->name, ['link', 'id' => $model->id], ['style' => ($model->getAppEntity()->count('id') > 0 ? 'color: grey' : 'color: red')]);
		}
	],
    'code_vendor',
    'name_polish',
    'unit_of_measure',
    'weight',
    [
        'attribute' => 'e1cGroupOfGood.name',
        'label' => Yii::t('app', 'Category')
    ],
	/*[
		'format' => 'raw',
        'label' => Yii::t('app', 'Product type'),
		'value' => function($model, $key, $index, $column) {
            if ( ! $model->e1cGroupOfGood ) {
                return null;
            }
            $default = null;
            if ( $model->appEntity ) {
                $default = $model->appEntity->type_id;
            }
			return Html::dropDownList("product_type[{$model->id}]", $default, ArrayHelper::map($model->e1cGroupOfGood->appEntities, 'id', 'native_name'), ['class' => 'form-control']);
		}
	],*/

	/*[
		'format' => 'raw',
        'label' => Yii::t('app', 'Defined features'),
		'value' => function($model, $key, $index, $column) {


			$result = [];
            if ( $model->e1cGroupOfGood ) {
	            $type = $model->appEntity ? $model->appEntity->type : ($model->e1cGroupOfGood->appEntities ? $model->e1cGroupOfGood->appEntities[0] : null);

	            if ( $type ) {
		            foreach ($type->product_features_define as $feature) {
			            $defaultValue = $model->appEntity ? $model->appEntity->featureGroupedValueIds[$feature->product_feature_id] : null;
			            $rowFeature = \dosamigos\selectize\SelectizeDropDownList::widget([
                            'name' => "product_feature[{$model->id}][{$feature->product_feature_id}]",
                            'value' => $defaultValue,
				            'items' => ArrayHelper::map($feature->product_feature->values, 'id', 'value_label'),
				            'clientOptions' => [
					            'valueField' => 'id',
					            'labelField' => 'value_label',
					            'searchField' => 'value_label',
					            'create' => new \yii\web\JsExpression('function(input, callback) {
                                $.ajax({
                                    url: \''. \yii\helpers\Url::to(['tag/add']) .'\',
                                    data: {project: $(\'#event-project_id\').val(), value: input},
                                    type: \'post\',
                                    dataType: \'json\',
                                    success: function(data) {

                                        callback(data);
                                    }
                               });
                            }'),
				            ],
				            'options' => [
					            'placeholder' => Yii::t('app', 'Select or add value'),
					            'multiple' => false,
				            ],
			            ]);

			            ob_start();

			            $featureModel = new \common\models\ProductFeatureValue([
//				            'feature_id' => $feature->product_feature_id,
			            ]);

			            if ($featureModel->feature_id) {
				            echo '<label>Добавить свойство</label><br>';
				            $editable = Editable::begin( [
					            'model'        => $featureModel,
					            'attribute'    => 'value',
					            'asPopover'    => true,
					            'size'         => 'md',
					            'displayValue' => '',
					            'options'      => [ 'placeholder' => 'Enter value...' ]
				            ] );

				            $form = $editable->getForm();
				            echo Html::hiddenInput( 'kv-complex', '1' );

				            $afterInput = '';
				            foreach ( Yii::$app->languages->languages as $language ) {
					            echo Html::activeTextInput( $featureModel, AdminHelper::getLangField( 'value_label', $language ) );
				            }

				            $editable->afterInput = $afterInput . "\n";
				            Editable::end();
			            }

			            $rowFeature .= ob_get_contents();

			            ob_end_clean();

			            $result[] = $rowFeature;
		            }
                }

	            return Html::tag( 'div', implode('', $result), [
		            'class' => 'defined-features',
		            'data'  => [
			            'id' => $model->id,
		            ],
	            ] );
            }
		}
	],*/
	/*[
		'class' => '\kartik\grid\BooleanColumn',
		'value' => function($model) {
            return null !== $model->appEntity;
        },
	],*/
	/*[
		'class' => 'yii\grid\ActionColumn',
		'template' => '{link}',
//                'dropdown' => false,
//                'vAlign' => 'middle',
		'buttons' => [
			'link' => function ($url, $model) {
				// @var $model \common\models\soap\E1cGood
	            if ( null !== $model->appEntity ) {
	                return '<a><span class="glyphicon glyphicon-ok text-primary"></span></a>';
                } else {
		            return Html::a( '<span class="glyphicon glyphicon-plus text-danger"></span>', $url, [
			            'data-toggle'         => 'tooltip',
			            'data-original-title' => Yii::t( 'app', 'To link' )
		            ] );
	            }
			},
		],
		'urlCreator' => function($action, $model, $key, $index) {
			switch($action) {
				case 'link':
					return \yii\helpers\Url::to(['link', 'guid' => $model->guid]);
					break;
			}
		},
	],*/
];

$gridOptions = [
	'id' => $__params['id'] ."-grid",
	'dataProvider' => $dataProvider,
	'columns' => $columns,

	'responsive' => true,
	'striped' => true,
	'hover' => true,
	'pjax' => false,
	'persistResize' => false,
	'pjaxSettings' => [
		'neverTimeout' => true,
	],
	'floatHeader' => true,
	'floatHeaderOptions' => [
		'position' => 'auto',
	],

	'summary' => false,
];

$gridOptions = array_merge($gridOptions, [
	'panel' => [
		'type' => GridView::TYPE_PRIMARY,
		'heading' => '<i class="glyphicon glyphicon-book"></i> '. $__params['items'],
	],
	'toolbar'=> [
		['content'=>
            '<div class="btn-group" style="margin-right: 30px">'.
            \kartik\select2\Select2::widget([
                'data' => ArrayHelper::map(\backend\models\Product::find()->orderBy(['native_name' => SORT_ASC])->all(), 'id', 'native_name'),
                'id' => 'binding-product-list',
                'name' => 'binding_product',
                'options' => [
                    'prompt' => 'Выберите товар',
                ],
            ]).'</div>' .

			 Html::button('<i class="glyphicon glyphicon-link"></i>', ['type'=>'button', 'title'=>Yii::t('app', 'Связать'), 'class' => 'btn btn-info', 'id' => 'binding-product']) . ' '.
			 Html::button('<i class="glyphicon glyphicon-filter"></i>', ['type'=>'button', 'title'=>Yii::t('app', 'Filter'), 'class' => 'btn btn-info', 'data-toggle' => 'modal', 'data-target' => '#search-filter']) . ' '.
			 Html::a('<i class="glyphicon glyphicon-repeat"></i>', ['index'], ['data-pjax'=>0, 'class'=>'btn btn-default', 'title' => Yii::t('app', 'Reset filters')])
		],
		'{toggleData}',
	],
]);
?>

<?php
$js = <<<JS
$('#binding-button').on('click', function(e) {
   if ($(this).attr('aria-expanded') == 'true') {
       $('#binding-label').text('Связать выбранные');
   } else {
       $('#binding-label').text('Выберите тип товара');
   }
});
$('.binding-link').on('click', function(e) {
    e.preventDefault();
    var url = $(this).attr('href');
    var ids = $('#{$__params['id']}-grid').yiiGridView('getSelectedRows');
    console.log(ids);
    if (!ids.length) {
        alert('Требуется выбрать хотябы один товар');
        return;
    }
    window.location.href = url +'?ids='+ ids.join(',');
});
JS;
$this->registerJs($js);
?>

<!-- MODALS -->
<?php MetronicModal::begin([
	'header' => '<span class="h3">Фильтр по параметрам</span>',
	'id' => 'search-filter',
])?>
<?= $this->render('_search', ['model' => $searchModel]) ?>
<?php MetronicModal::end()?>
<!-- END MODALS -->

<?= GridView::widget($gridOptions); ?>
