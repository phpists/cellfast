<?php

use backend\models\PartnerSearch;
use yii\data\ActiveDataProvider;
use yii\helpers\Html;
use backend\widgets\MetronicModal;
use kartik\grid\GridView;
use common\helpers\AdminHelper;
use yii\helpers\ArrayHelper;
use common\models\Partner;
use yii\web\View;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\PartnerSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$__params = require __DIR__ .'/__params.php';

$this->title = $__params['items'];
$this->params['breadcrumbs'][] = $this->title;

$columns = [
	[
		'attribute' => 'native_name',
		'format' => 'raw',
		'value' => function($model, $key, $index, $column) {
			return Html::a($model->name_ru_ru, ['update', 'id' => $model->id]);
		},
	],
	[
		'attribute' => 'location_region_id',
		'label' => 'Область',
		'format' => 'raw',
		'value' => function($model, $key, $index, $column) {

			$label = empty($model->locationRegion['native_name']) ? '-' : $model->locationRegion['native_name'];

			return Html::a($label, ['update', 'id' => $model->id]);
		}
	],
	[
		'attribute' => 'projects',
		'format' => 'html',
		'value' => function ($model, $key, $index, $column) {

            $projects = [];

			$label = '-';

			if($model->projects) {

	            foreach($model->projects as $project) {
		            $projects[] = ucwords($project);
	            }

	            if($projects) {
		            $label = implode(', ', $projects);
	            }

            }

			return Html::a($label, ['update', 'id' => $model->id]);
		}
	],
	[
		'attribute' => 'type',
		'format' => 'raw',
		'value' => function ($model, $key, $index, $column) {
			return Html::a($model->type, ['update', 'id' => $model->id]);
		}
	],
	[
		'class' => '\kartik\grid\BooleanColumn',
		'attribute' => 'status',
		'label' => 'Статус видимости',
		'trueLabel' => Partner::ENABLE,
		'falseLabel' => Partner::DISABLE,
	],
	[
		'class' => 'yii\grid\ActionColumn',
		'template' => '{update} {delete}',
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
