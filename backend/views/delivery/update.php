<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\Product */

$__params = require __DIR__ .'/__params.php';

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Product type',
]) . $model->native_name;
$this->params['breadcrumbs'][] = ['label' => $__params['items'], 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->native_name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');

?>
<div class="custom-form-section">
    <div class="custom-form-section-box">

		<?= $this->render('_form', [
			'model' => $model,
		]) ?>

    </div>
</div>
