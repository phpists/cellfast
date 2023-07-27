<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model noIT\tips\models\Tip */
/* @var $tipModule \noIT\tips\models\Tip */
/* @var $tipModelsName string  */
/* @var $tipModelAttributes \noIT\tips\models\Tip */

$__params = require __DIR__ .'/__params.php';

$this->title = "Модель: {$model->model} | Атрибут: {$model->attribute}";

$this->params['breadcrumbs'][] = ['label' => $__params['items'], 'url' => ['/tips/tip/index']];
$this->params['breadcrumbs'][] = ['label' => $__params['update'], 'url' => ['/tips/tip/update', 'id' => $model->id]];

?>
<div class="custom-form-section">
    <div class="custom-form-section-box">
		<?= $this->render('_form', [
			'model' => $model,
			'tipModelsName' => $tipModelsName,
			'tipModelAttributes' => $tipModelAttributes,
		]) ?>
    </div>
</div>