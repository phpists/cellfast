<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\Document */

$this->title = 'Редактировать документ: ' . $model->name_ru_ru;
$this->params['breadcrumbs'][] = ['label' => 'Документы для скачивания', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name_ru_ru, 'url' => ['update', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Редатировать';

?>
<div class="custom-form-section">
    <div class="custom-form-section-box">

		<?= $this->render('_form', [
			'model' => $model,
		]) ?>

    </div>
</div>
