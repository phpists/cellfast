<?php

use backend\widgets\MetronicModal;
use yii\helpers\Html;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\CategorySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$__params = require __DIR__ .'/__params.php';

$this->title = $__params['items'];
$this->params['breadcrumbs'][] = $this->title;

$action_ajax_update_url = \yii\helpers\Url::to(['ajax_update']);
$js = <<<JS
    $('.action-ajax-update').click(function(e) {
        e.preventDefault();
        var a = $(this);
        $.ajax({
            url: '$action_ajax_update_url',
            type: 'get',
            data: {
                id: a.attr('action-ajax-update-id'),
                field: a.attr('action-ajax-update-key'),
                value: a.attr('action-ajax-update-value')
            },
            dataType: 'json',
            success: function(answer) {
                $.each(answer, function(id, state) {
                    var _a = $('a.action-ajax-update[action-ajax-update-key='+ a.attr('action-ajax-update-key') +'][action-ajax-update-id='+ id +']');
                    if (state == 'enable') {
                        _a.addClass('enable');
                        _a.attr('action-ajax-update-value', 0);
                        _a.attr('data-original-title', 'Убрать из выгрузки в '+ _a.attr('data-label'));
                    } else {
                        _a.removeClass('enable');
                        _a.attr('action-ajax-update-value', 1);
                        _a.attr('data-original-title', 'Добавить в выгрузку в '+ _a.attr('data-label'));
                    }
                });
            }
        });
    });
JS;

$this->registerJs($js);

$open = (!empty($_GET['open']) && $_GET['open'] && ($openCategory = \common\models\Category::findOne($_GET['open'])) ? $openCategory->getParents()->select(['category.id'])->column() + [$openCategory->id] : []);

$columns = [
	[
		'class' => 'kartik\grid\ExpandRowColumn',
		'value' => function ($model, $key, $index) use ($open) {
			return $model->children ? (in_array($model->id, $open) ? GridView::ROW_EXPANDED : GridView::ROW_COLLAPSED) : '';
		},
		'detailUrl' => \yii\helpers\Url::to(['index']) .($open ? '?open='. $openCategory->id : ''),
	],
	[
		'attribute' => 'image',
		'format' => 'image',
		'value' => function($model) {
			return $model->getThumbUploadUrl('image', 'thumb_list');
		}
	],
	[
		'format' => 'raw',
		'value' => function($model, $key, $index, $column) use ($mode) {
			if ($mode == 'search') {
				$op = [];
				foreach ($model->parents as $c) {
					$op[] = Html::a($c->native_name, ['/category/update', 'id' => $c->id]);
				}
			}
			$op[] = Html::a($model->native_name, ['/category/update', 'id' => $model->id]);
			return implode(">", $op);
		}
	],
	[
		'attribute' => 'project.name',
		'label' => Yii::t('app', 'Project'),
	],
	'product_type.native_name',
	[
		'class' => '\kartik\grid\BooleanColumn',
		'attribute' => 'status',
		'label' => '',
		'trueLabel' => 'Видимый',
		'falseLabel' => 'Скрытый'
	],
	[
		'class' => 'yii\grid\ActionColumn',
		'template' => '{view} {update} {delete}',
		'buttons' => [
			'view' => function ($url, $model) {
				return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', $url, [
					'data-toggle' => 'tooltip',
					'data-original-title' => 'Просмотр категории'
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
	'id' => $gridId,
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

if ($mode != 'children') {
	$gridOptions = array_merge($gridOptions, [
		'panel' => [
			'type' => GridView::TYPE_PRIMARY,
			'heading' => '<i class="glyphicon glyphicon-book"></i> '. $__params['items'],
		],
		'toolbar'=> [
			['content'=>
//                Html::button('<i class="glyphicon glyphicon-plus"></i>', ['type'=>'button', 'title' => 'Создать категорию', 'class'=>'btn btn-success', 'data-toggle' => 'modal', 'data-target' => '#category-create']) . ' '.
				 Html::a('<i class="glyphicon glyphicon-plus"></i>', ['create'], ['data-pjax'=>0, 'class'=>'btn btn-success', 'title' => $__params['create']]) .
				 Html::button('<i class="glyphicon glyphicon-filter"></i>', ['type'=>'button', 'title'=>Yii::t('app', 'Filter'), 'class'=>'btn btn-info', 'data-toggle' => 'modal', 'data-target' => '#search-filter']) . ' '.
				 Html::a('<i class="glyphicon glyphicon-repeat"></i>', ['index'], ['data-pjax'=>0, 'class'=>'btn btn-default', 'title'=>Yii::t('app', 'Reset filters')])
			],
			'{toggleData}',
		],
	]);
}
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