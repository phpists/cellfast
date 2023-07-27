<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\UserSerach */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Users');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="custom-form-section">
    <div class="custom-form-section-box">


        <h1><?= Html::encode($this->title) ?></h1>
		<?php // echo $this->render('_search', ['model' => $searchModel]); ?>

        <p>
			<?= Html::a(Yii::t('app', 'Create User'), ['create'], ['class' => 'btn btn-success']) ?>
        </p>
		<?= GridView::widget([
			'dataProvider' => $dataProvider,
			'filterModel' => $searchModel,
			'columns' => [
				['class' => 'yii\grid\SerialColumn'],

				'id',
				'email:email',
				'name',
//            'auth_key',
//            'password_hash',
				// 'password_reset_token',
				'status:boolean',
				// 'created_at',
				// 'updated_at',

				[
					'class' => 'yii\grid\ActionColumn',
					'template' => '{view} {update} {permit} {delete}',
					'buttons' =>
						[
							'permit' => function ($url, $model) {
								return Html::a('<span class="glyphicon glyphicon-wrench"></span>', Url::to(['/permit/user/view', 'id' => $model->id]), [
									'title' => Yii::t('yii', 'Change user role')
								]); },
						]
				],
			],
		]); ?>
    </div>
</div>
