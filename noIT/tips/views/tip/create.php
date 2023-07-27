<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model noIT\tips\models\Tip */
/* @var $tipModule \noIT\tips\models\Tip */
/* @var $tipModelsName string  */
/* @var $tipModelAttributes \noIT\tips\models\Tip */

$__params = require __DIR__ .'/__params.php';

$this->title = $__params['create'];
$this->params['breadcrumbs'][] = ['label' => $__params['items'], 'url' => ['/tips/tip/index']];
$this->params['breadcrumbs'][] = ['label' => $__params['create'], 'url' => ['/tips/tip/create']];


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