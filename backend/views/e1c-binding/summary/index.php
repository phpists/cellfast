<?php

use yii\helpers\Html;
use kartik\grid\GridView;

/** @var $this yii\web\View */
/** @var $active string */
/** @var $searchModel \backend\models\E1cGroupOfGoodSearch */
/** @var $dataProvider \yii\data\ActiveDataProvider */

$__params = require __DIR__ .'/__params.php';

$this->title = $__params['items'];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Groups of good')];

$columns = [
	[
		'label' => Yii::t('app', 'Name'),
		'attribute' => 'name',
	],
	[
		'label' => Yii::t('app', 'Total'),
		'attribute' => 'total',
	],
	[
		'label' => Yii::t('app', 'Binded'),
		'attribute' => 'binded',
	],
	[
		'label' => Yii::t('app', 'Loners'),
		'attribute' => 'loners',
	],
	[
		'label' => Yii::t('app', 'Excluder'),
		'attribute' => 'excluder',
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
?>
<div class="e1c-binding-<?= $active?>">
	<?= GridView::widget($gridOptions); ?>
</div>
