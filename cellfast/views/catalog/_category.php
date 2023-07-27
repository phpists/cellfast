<?php

use yii\helpers\Url;
use noIT\imagecache\helpers\ImagecacheHelper;

/** @var $this \yii\web\View */
/** @var $category \common\models\Category */


if(empty($category->slug_outer)) {
    $link = ['catalog/category', 'category' => $category];
} else {
    $link = $category->slug_outer;
}

?>
<div class="mcatalog__col">
    <a href="<?= Url::to($link) ?>" class="mcatalog__it">
        <div class="mcatalog__it__img objectfit"><?= $category->image ? ImagecacheHelper::getImage($category->getUploadUrl('image'), 'catalog_list') : '' ?></div>
        <div class="mcatalog__it__cnt">
            <div class="mcatalog__it__cnt__inn">
                <div class="mcatalog__it__title"><span><?= $category->name ?></span></div>
                <div class="mcatalog__it__txt"><span><?= $category->caption ?></span></div>
                <div class="mcatalog__it__btn"><span class="btn btn_fw btn_white-bd"><?= Yii::t('app', 'Readmore') ?></span></div>
            </div>
        </div>
    </a>
</div>