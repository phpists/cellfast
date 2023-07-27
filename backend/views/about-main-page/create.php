<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\AboutMainPage */

$__params = require __DIR__ .'/__params.php';

$this->title = $__params['create'];
$this->params['breadcrumbs'][] = ['label' => $__params['items'], 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>

<?= $this->render('_form', [
	'model' => $model,
]) ?>
