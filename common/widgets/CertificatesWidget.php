<?php
namespace common\widgets;

use common\models\Document;
use Yii;

class CertificatesWidget extends \yii\bootstrap\Widget
{
    public function run()
    {
        parent::run();

	    $items = Document::find()
	                     ->where([
		                     'status' => Document::ENABLE,
		                     'type' => Document::TYPE_CERTIFICATE,
		                     'project_id' => Yii::$app->projects->current->alias,
	                     ])
	                     ->all();

	    return $this->render('certificates', [
		    'items' => $items,
	    ]);
    }
}
