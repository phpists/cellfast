<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\Document */

$this->title = 'Создат документ';
$this->params['breadcrumbs'][] = ['label' => 'Документы', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="custom-form-section">
    <div class="custom-form-section-box">

		<?= $this->render('_form', [
			'model' => $model,
		]) ?>

    </div>
</div>
