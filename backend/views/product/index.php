<?php
use yii\helpers\Html;
use backend\widgets\MetronicModal;
use kartik\grid\GridView;
use common\helpers\AdminHelper;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\CategorySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$__params = require __DIR__ .'/__params.php';

$this->title = $__params['items'];
$this->params['breadcrumbs'][] = $this->title;

$columns = [
	[
		'attribute' => 'native_name',
		'format' => 'raw',
		'value' => function($model, $key, $index, $column) {
			return Html::a($model->native_name, ['update', 'id' => $model->id]);
		}
	],
	[
		'attribute' => 'type.native_name',
		'label' => Yii::t('app', 'Product type'),
		'format' => 'raw',
		'value' => function($model) {
			return Html::a($model->type->native_name, ['/product-type/view', 'id' => $model->type_id]);
		}
	],
	[
		'attribute' => 'project.name',
		'label' => Yii::t('app', 'Project'),
	],
	[
		'class' => '\kartik\grid\BooleanColumn',
		'attribute' => AdminHelper::FIELDNAME_STATUS,
//        'label' => '',
		'trueLabel' => 'Видимый',
		'falseLabel' => 'Скрытый'
	],
	[
		'class' => 'yii\grid\ActionColumn',
		'template' => '{view} {update} {delete}',
//                'dropdown' => false,
//                'vAlign' => 'middle',
		'buttons' => [
			'view' => function ($url, $model) {
				return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', $url, [
					'data-toggle' => 'tooltip',
					'data-original-title' => Yii::t('app', 'Show')
				]);
			},
		],
		'urlCreator' => function($action, $model, $key, $index) {
			switch($action) {
				case 'view':
					/** TODO - link to frontend */
					return \yii\helpers\Url::to(['view', 'id' => $model->id]);
					break;
				case 'delete':
					return \yii\helpers\Url::to(['delete', 'id' => $model->id]);
					break;
				case 'update':
					return \yii\helpers\Url::to(['update', 'id' => $model->id]);
					break;
			}
		},
	],
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
			 Html::a('<i class="glyphicon glyphicon-plus"></i>', ['create'], ['data-pjax'=>0, 'class'=>'btn btn-success', 'title' => $__params['create']]) .
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
