<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\Category */

$__params = require __DIR__ .'/__params.php';

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => $__params['items'], 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="custom-form-section">
    <div class="custom-form-section-box">

        <p>
			<?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
			<?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->id], [
				'class' => 'btn btn-danger',
				'data' => [
					'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
					'method' => 'post',
				],
			]) ?>
        </p>

		<?= DetailView::widget([
			'model' => $model,
			'attributes' => [
				'id',
				'native_name',
				'name',
				'includeVAT',
			],
		]) ?>

    </div>
</div>
