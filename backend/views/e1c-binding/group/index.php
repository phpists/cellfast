<?php

use yii\helpers\Html;
use backend\widgets\MetronicModal;
use kartik\grid\GridView;
use kartik\editable\Editable;

/** @var $this yii\web\View */
/** @var $active string */
/** @var $searchModel \backend\models\E1cGroupOfGoodSearch */
/** @var $dataProvider \yii\data\ActiveDataProvider */

$__params = require __DIR__ .'/__params.php';

$this->title = $__params['items'];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Groups of good')];

$columns = [
	[
		'attribute' => 'name',
		'format' => 'raw',
	],
	[
		'class' => 'kartik\grid\EditableColumn',
		'attribute' => 'appEntitiesLabel',
		'format' => 'raw',
		'editableOptions' => [
			'header' => Yii::t('app', 'Product types'),
			'size' => 'md',
			'options' => ['class'=>'form-control', 'multiple' => true, 'size' => 12, 'style' => 'overflow: auto; height: 10em'],

			'format' => Editable::FORMAT_BUTTON,
			'inputType' => Editable::INPUT_CHECKBOX_LIST,
			'data' => \yii\helpers\ArrayHelper::map(\common\models\ProductType::find()->all(), 'id', 'native_name'),

			'formOptions' => [
				'action' => ['binding-group'],
			]
		],
//		'filter' => Html::activeDropDownList($searchModel, 'status', ['' => 'Все']+ ProductComment::$status_labels, ['class' => 'form-control']),
	],
	/*[
		'format' => 'raw',
        'label' => Yii::t('app', 'Product types'),
		'value' => function($model, $key, $index, $column) {
			return implode(', ', \yii\helpers\ArrayHelper::getColumn($model->appEntities, 'native_name'));
		}
	],*/
	/*[
		'class' => 'yii\grid\ActionColumn',
		'template' => '{link}',
//                'dropdown' => false,
//                'vAlign' => 'middle',
		'buttons' => [
			'link' => function ($url, $model) {
				return Html::a('<span class="glyphicon glyphicon-link"></span>', $url, [
					'data-toggle' => 'tooltip',
					'data-original-title' => Yii::t('app', 'To link')
				]);
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
	'id' => $__params['id'],
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
			 Html::a('<i class="glyphicon glyphicon-link"></i>', ['link-multiple'], ['data-pjax'=>0, 'class'=>'btn btn-success', 'title' => Yii::t('app', 'To link')]) .
			 Html::button('<i class="glyphicon glyphicon-filter"></i>', ['type'=>'button', 'title'=>Yii::t('app', 'Filter'), 'class' => 'btn btn-info', 'data-toggle' => 'modal', 'data-target' => '#search-filter']) . ' '.
			 Html::a('<i class="glyphicon glyphicon-repeat"></i>', ['index'], ['data-pjax'=>0, 'class'=>'btn btn-default', 'title' => Yii::t('app', 'Reset filters')])
		],
		'{toggleData}',
	],
]);
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
