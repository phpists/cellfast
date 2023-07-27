<?php

/* @var $this yii\web\View */
/* @var $model common\models\AboutUs */

$__params = require __DIR__ .'/__params.php';

$this->title = Yii::t('app', 'Update {modelClass}: ', [
		'modelClass' => $__params['item'],
	]) . $model->name_ru_ru;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', $__params['items']), 'url' => ['index']];
$this->params['breadcrumbs'][] = Yii::t('app', $model->name_ru_ru);

?>
<div class="custom-form-section">
    <div class="custom-form-section-box">

		<?= $this->render('_form', [
			'model' => $model,
		]) ?>

    </div>
</div>
