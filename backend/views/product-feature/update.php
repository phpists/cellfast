<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\Category */

$__params = require __DIR__ .'/__params.php';

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => $__params['item'],
]) . $model->name;
$this->params['breadcrumbs'][] = ['label' => $__params['items'], 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');

?>
<div class="custom-form-section">
    <div class="custom-form-section-box">

		<?= $this->render('_form', [
			'model' => $model,
		]) ?>

    </div>
</div>
