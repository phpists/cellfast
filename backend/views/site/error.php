<?php

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

use yii\helpers\Html;

$this->title = $name;
?>
<div class="site-error">

    <h1>Данная страница не существует</h1>
    <br>
    <div class="alert alert-danger">
        <strong>Внимание!</strong>
		<?= nl2br(Html::encode($message)) ?>
    </div>

</div>
