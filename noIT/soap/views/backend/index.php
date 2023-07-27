<?php

use kartik\grid\GridView;
use \yii\helpers\Html;
use \yii\helpers\Url;

/*  @var $this yii\web\View
    @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('soap', 'The exchange log with 1C');
$this->params['breadcrumbs'][] = $this->title;
$columns = [
    [
        'attribute' => 'guid',
        'label' => Yii::t('soap', 'GUID'),
//        'value' => function($model) {
//            /** @var $model \common\models\ProductType */
//            return implode(", ", ArrayHelper::map($model->categories, 'id', 'native_name'));
//        }
    ],
    [
        'attribute' => 'status',
        'label' => Yii::t('soap', 'Status'),
    ],
    [
        'attribute' => 'timestamp',
        'label' => Yii::t('soap', 'Timestamp'),
    ],
    [
        'class' => 'yii\grid\ActionColumn',
        'template' => '{view}',
        'buttons' => [
            'view' => function ($url, $model) {
                return Html::a('<span class="glyphicon glyphicon-eye-open"></span>',
                    Url::to(['view', 'guid' => $model->guid]),
                    [
                        'data-toggle' => 'tooltip',
                        'data-original-title' => Yii::t('app', 'Show')
                    ]
                );
            },
        ],
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
        'heading' => '<i class="glyphicon glyphicons-transfer"></i> '. Yii::t('soap', 'Sessions'),
    ],
    'toolbar'=> [
        '{toggleData}',
    ],
];
?>
<div class="index">
    <?= GridView::widget($gridOptions); ?>
</div>
