<?php

/** @var $this \yii\web\View */
/** @var $cart \common\models\Cart */

use yii\helpers\Url;

$cart = Yii::$container->get(\common\models\Cart::className());

?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
<style>
    /* CSS стилі для вирівнювання іконки лупи всередені поля для ввода */
    .input-group .input-group-prepend a {
        position: relative;
        left: -30px; /* Відстань, на яке переносим іконку вліво */
        top: 2px; /* Вирівнюється вертикальне положення іконки */
        z-index: 1;
        color: #495057; /* Колір іконки */
        text-decoration: none;
    }

    .input-group input {
        padding-right: 30px;
    }
</style>
<header class="header">
    <div class="header__top">
        <div class="container">
            <div class="header__top__inn">
                <div class="header__lang"><?= \common\widgets\LanguageSwitcher::widget() ?></div>
                <label><?= $jsonData ?></label>

                <div class="header__calculator_link">
                    <a href="/catalog/gds-calc">
                        <span class="icon"><svg class="svg-icon calc-icon"><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="/img/template/svg-sprite.svg#calc-icon"></use></svg></span>
                        <span class="anchor"><?= Yii::t('app', 'Калькулятор водосточной системы') ?></span>
                    </a>
                </div>


                    <div class="header__auth">
                        <div class="search">
                            <form action="<?= Url::to(['site/search'])?>" method="get" class="form-inline">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <input type="text" class="form-control" placeholder="Пошук" name="search_header" id="search_header">
                                        <a><i class="fas fa-search"></i></a>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <?php echo $jsonData?>
                        <script>
                            var jsonData = <?php echo $jsonData; ?>;
                            console.log(jsonData);
                        </script>
                        <div class="header__link">
                            <a href="https://www.facebook.com/Bryza-Україна-107857921130721/" target="_blank">
                                <div class="header__link__icon">
                                    <svg class="svg-icon fb-icon">
                                        <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="/img/template/svg-sprite.svg?335#fb-icon"></use>
                                    </svg>
                                </div>
                                <div class="header__link__txt"><span>facebook</span></div>
                            </a>
                        </div>
                        <div class="header__reg">
                            <a href="<?= \yii\helpers\Url::to(['user/signin'])?>">
                                <div class="header__reg__icon">
                                    <svg class="svg-icon enter-icon">
                                        <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="/img/template/svg-sprite.svg#enter-icon"></use>
                                    </svg>
                                </div>
                                <div class="header__reg__txt"><span>Вход</span></div>
                            </a>
                        </div>
                        <div class="header__reg">
                            <a href="<?= \yii\helpers\Url::to(['user/signup'])?>">
                                <div class="header__reg__icon">
                                    <svg class="svg-icon user-icon">
                                        <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="/img/template/svg-sprite.svg#user-icon"></use>
                                    </svg>
                                </div>
                                <div class="header__reg__txt"><span>Регистрация</span></div>
                            </a>
                        </div>
                    </div>

            </div>
        </div>
    </div>
    <!--- --->
    <div class="header__bottom">
        <div class="container">
            <div class="header__bottom__inn">
                <div class="header__logo">
                    <a href="/"><img src="/img/template/logotype.png?312" alt="Bryza" ></a>
                </div>
                <div class="header__icons">
                    <?php /*
  <!--<div class="header__search">
                        <a href="#" data-env="dev">
                            <svg class="svg-icon search-icon">
                                <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="/img/template/svg-sprite.svg#search-icon"></use>
                            </svg></a>
                    </div>-->
                    <!--<div id="checkout_badge" class="header__card">
                        echo $this->render('/order/badge.php', [ 'cart' => $cart ]);
                </div>-->
 */ ?>
                    <div class="header__mmenu visible-xs">
                        <a href="#" data-env="dev"><span></span><span></span><span></span></a>
                    </div>
                </div>
                <nav class="header-nav">
                    <ul class="header-nav-list">
                        <li class="header-nav-list-item">
                            <a href="<?= Url::to(['/site/about']) ?>" class="header-nav-list-item__link"><?= Yii::t('app', 'About company') ?></a>
                        </li>
                        <li class="header-nav-list-item">
                            <a href="<?= Url::to(['/catalog/index']) ?>" class="header-nav-list-item__link"><?= Yii::t('app', 'Catalog') ?></a>
                        </li>
                        <li class="header-nav-list-item">
                            <a href="<?= Url::to(['/event/index']) ?>" class="header-nav-list-item__link"><?= Yii::t('app', 'Events') ?></a>
                        </li>
                        <li class="header-nav-list-item">
                            <a href="<?= Url::to(['/article/index']) ?>" class="header-nav-list-item__link"><?= Yii::t('app', 'Articles') ?></a>
                        </li>

                        <?php if (!empty(Yii::$app->params['documentType'])) : ?>
                            <li class="header-nav-list-item">
                                <a href="#" class="header-nav-list-item__link"><?= Yii::t('app', 'Downloads') ?></a>
                                <div class="header-nav-list-item-dropdown">
                                    <ul>
                                        <?php foreach (Yii::$app->params['documentType'] as $type) : ?>
                                            <li>
                                                <a href="<?= Url::to(["/download/{$type['value']}"]) ?>"><?= Yii::t('app', $type['labels']) ?></a>
                                            </li>
                                        <?php endforeach ?>
                                    </ul>
                                </div>
                            </li>
                        <?php endif ?>

                        <li class="header-nav-list-item">
                            <a href="<?= Url::to(['/site/partners']) ?>" class="header-nav-list-item__link"><?= Yii::t('app', 'Where can I buy') ?></a>
                        </li>

                        <li class="header-nav-list-item">
                            <a href="<?= Url::to(['/site/contact']) ?>" class="header-nav-list-item__link"><?= Yii::t('app', 'Contact') ?></a>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
    </div>
    <!--- --->
    </div>
</header>