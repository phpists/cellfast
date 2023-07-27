<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model noIT\feedback\models\Feedback */

$__params = require __DIR__ .'/__params.php';

$this->title = $model->name;

$this->params['breadcrumbs'][] = ['label' => $__params['items'], 'url' => ['feedback/index']];
$this->params['breadcrumbs'][] = ['label' => $__params['view'], 'url' => ['feedback/view', 'id' => $model->id]];


?>
<div class="custom-form-section">
    <div class="custom-form-section-box">

        <p>
			<?= Html::a('Редактировать', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
			<?= Html::a('Удалить', ['delete', 'id' => $model->id], [
				'class' => 'btn btn-danger',
				'data' => [
					'confirm' => Yii::t('app', 'Вы уверены, что хотите удалить этот элемент?'),
					'method' => 'post',
				],
			]) ?>
        </p>

		<?= DetailView::widget([
			'model' => $model,
			'attributes' => [
				'id',
				'created_at:datetime',
				'ip',
				'name',
				'email:email',
				'phone',
				'message:ntext',
				'data:ntext',
				'model',
				'status',
			],
		]) ?>

    </div>
</div>
