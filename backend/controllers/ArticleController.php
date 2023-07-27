<?php

namespace backend\controllers;

use Yii;
use noIT\content\controllers\AdminController;

/**
 * ArticleController implements the CRUD actions for Article model.
 */
class ArticleController extends AdminController
{
	protected $modelClass = 'common\models\Article';
	protected $modelSearchClass = 'backend\models\ArticleSearch';

	public function actions()
	{
		return [
			'body-image-upload' => [
				'class' => 'vova07\imperavi\actions\UploadFileAction',
				'url' => Yii::getAlias('@cdnUrl/article/body'),
				'path' => Yii::getAlias('@cdn/article/body'),
				'unique' => true,
				'translit' => true,
			],
			'body-images-get' => [
				'class' => 'vova07\imperavi\actions\GetImagesAction',
				'url' => Yii::getAlias('@cdnUrl/article/body'),
				'path' => Yii::getAlias('@cdn/article/body'),
				'options' => ['only' => ['*.jpg', '*.jpeg', '*.png', '*.gif']],
			],
			'body-file-delete' => [
				'class' => 'vova07\imperavi\actions\DeleteFileAction',
				'url' => Yii::getAlias('@cdnUrl/article/body'),
				'path' => Yii::getAlias('@cdn/article/body'),
			]
		];
	}
}