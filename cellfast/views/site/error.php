<?php

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

use yii\helpers\Html;

$this->title = $name;
?>
<section class="maboutw">
    <div class="container">
        <div class="mabout">

            <h1 class="mabout__title"><span><?= Html::encode($this->title) ?></span></h1>

            <div class="alert alert-danger">
                <?= nl2br(Html::encode($message)) ?>
            </div>

<!--            <p>-->
<!--                The above error occurred while the Web server was processing your request.-->
<!--            </p>-->
<!--            <p>-->
<!--                Please contact us if you think this is a server error. Thank you.-->
<!--            </p>-->

        </div>
    </div>
</section>
