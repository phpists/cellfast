<?php

use yii\helpers\Html;

?>

<meta property="og:title" content="<?= Html::encode($this->title) ?>"/>
<meta property="og:type" content="website"/>
<meta property="og:url" content="https://ines.ua/"/>
<meta property="og:image" content="https://ines.ua/og-image.jpg"/>
<meta property="og:image:secure_url" content="https://ines.ua/og-image.jpg"/>
<meta property="og:site_name" content="<?= Html::encode($this->title) ?>"/>
<meta property="og:locale" content="<?= Yii::$app->languages->current->suffix ?>"/>
<meta property="og:description" content="<?= Html::encode($this->title) ?>"/>
<meta name="twitter:title" content="<?= Html::encode($this->title) ?>"/>
<meta name="twitter:image" content="https://ines.ua/og-image.jpg"/>

