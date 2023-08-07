<?php

use yii\helpers\Url;
use noIT\imagecache\helpers\ImagecacheHelper;

/** @var $this \yii\web\View */
/** @var $category \common\models\Category */

if($category->slug === 'vodostoki') {
	$link = '/catalog/vodostoki/sistema-vodostoki=sistema-125';
} else {

	if(empty($category->slug_outer)) {
		$link = ['catalog/category', 'category' => $category];
	} else {
		$link = $category->slug_outer;
	}

}

?>

<div class="mcatalog__col">
    <a href="<?= Url::to($link) ?>" class="mcatalog__it objectfit">
		<?= $category->image ? ImagecacheHelper::getImage($category->getUploadUrl('image'), 'catalog_list') : '' ?>
        <div class="mcatalog__it__cnt">
            <div class="mcatalog__it__title"><span><?= $category->name ?></span></div>
            <div class="mcatalog__it__txt"><span><?= $category->caption ?></span></div>
        </div>
    </a>
</div>