<?php

/* @var $this yii\web\View */
/* @var $model \noIT\feedback\models\Feedback */

$__params = require __DIR__ .'/__params.php';

$this->title = $model->name;

$this->params['breadcrumbs'][] = ['label' => $__params['items'], 'url' => ['feedback/index']];
$this->params['breadcrumbs'][] = ['label' => $__params['update'], 'url' => ['feedback/update', 'id' => $model->id]];

?>
<div class="custom-form-section">
    <div class="custom-form-section-box">
        <?= $this->render('_form', [
            'model' => $model,
        ]) ?>
    </div>
</div>