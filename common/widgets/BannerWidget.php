<?php
namespace common\widgets;

use common\models\Banner;
use Yii;

class BannerWidget extends \yii\bootstrap\Widget
{
    public function run()
    {
        parent::run();

        $model = new Banner();

	    $banners = $model->getGallery();

        return $this->render('banners', ['banners' => $banners]);
    }
}
