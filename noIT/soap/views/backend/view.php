<?php

use kartik\grid\GridView;
use \yii\helpers\Html;
use \yii\helpers\Url;

/*  @var $this yii\web\View
 *  @var $guid string
 *  @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('soap', 'Session details');
$this->params['breadcrumbs']['level1'] = [
    'label' => Yii::t('soap', 'The exchange log with 1C'),
    'url' => ['index'],
    ];
$this->params['breadcrumbs']['level2'] = $this->title;

$columns = [
    [
        'attribute' => 'entity',
        'label' => Yii::t('soap', 'Entity'),
    ],
    [
        'attribute' => 'size',
        'label' => Yii::t('soap', 'Size, pcs'),
    ],
    [
        'attribute' => 'duration',
        'label' => Yii::t('soap', 'Duration, s'),
    ],
    [
        'attribute' => 'status',
        'label' => Yii::t('soap', 'Status'),
    ],
    [
        'attribute' => 'timestamp',
        'label' => Yii::t('soap', 'Timestamp'),
    ],
];

$gridOptions = [
    'moduleId' => 'gridview',
    'dataProvider' => $dataProvider,
    'columns' => $columns,
    'hover' => true,
    'pjax' => false,
    'floatHeader' => true,
    'floatHeaderOptions' => [
        'position' => 'auto',
    ],
    'showPageSummary' => false,
    'panel' => [
        'type' => GridView::TYPE_PRIMARY,
        'heading' => '<i class="glyphicon glyphicons-transfer"></i> ' .
            Yii::t('soap', 'Session') . " {$guid}",
    ],
    'toolbar'=> [],
];
?>
<div class="index">
    <?= GridView::widget($gridOptions); ?>
</div>
