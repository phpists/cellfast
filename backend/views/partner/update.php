<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\Partner */
/* @var $locationRegion \common\models\LocationRegion */

$__params = require __DIR__ .'/__params.php';

$this->title = 'Обновить: ' . $model->name_ru_ru;
$this->params['breadcrumbs'][] = ['label' => $__params['items'], 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Update';

?>
<div class="custom-form-section">
    <div class="custom-form-section-box">

		<?= $this->render('_form', [
			'model' => $model,
			'locationRegion' => $locationRegion
		]) ?>

    </div>
</div>
