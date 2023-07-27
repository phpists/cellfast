<?php

/* @var $this yii\web\View */
/* @var $dataProvider \yii\data\ActiveDataProvider */

$__params = require __DIR__ .'/__params.php';

?>
<?= $this->render('/content/index', [
    'dataProvider' => $dataProvider,
    '__params' => $__params,
])?>