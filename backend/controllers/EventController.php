<?php

namespace backend\controllers;

use Yii;
use noIT\content\controllers\AdminController;

/**
 * EventController implements the CRUD actions for Event model.
 */
class EventController extends AdminController
{
    protected $modelClass = 'common\models\Event';
    protected $modelSearchClass = 'backend\models\EventSearch';

	public function actions()
	{
		return [
			'body-image-upload' => [
				'class' => 'vova07\imperavi\actions\UploadFileAction',
				'url' => Yii::getAlias('@cdnUrl/event/body'),
				'path' => Yii::getAlias('@cdn/event/body'),
				'unique' => true,
				'translit' => true,
			],
			'body-images-get' => [
				'class' => 'vova07\imperavi\actions\GetImagesAction',
				'url' => Yii::getAlias('@cdnUrl/event/body'),
				'path' => Yii::getAlias('@cdn/event/body'),
				'options' => ['only' => ['*.jpg', '*.jpeg', '*.png', '*.gif']],
			],
			'body-file-delete' => [
				'class' => 'vova07\imperavi\actions\DeleteFileAction',
				'url' => Yii::getAlias('@cdnUrl/event/body'),
				'path' => Yii::getAlias('@cdn/event/body'),
			]
		];
	}
}