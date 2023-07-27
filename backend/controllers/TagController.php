<?php

namespace backend\controllers;

use common\helpers\SiteHelper;
use common\models\Tag;
use noIT\core\helpers\AdminHelper;
use noIT\feature\controllers\AdminController;
use Yii;
use yii\base\Exception;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * ContentEventController implements the CRUD actions for ContentEvent model.
 */
class TagController extends AdminController
{
	protected $modelClass = 'common\models\Tag';
	protected $modelSearchClass = 'backend\models\TagSearch';

	public function actions()
	{
		return [
			'body-image-upload' => [
				'class' => 'vova07\imperavi\actions\UploadFileAction',
				'url' => Yii::getAlias('@cdnUrl/tag/body'),
				'path' => Yii::getAlias('@cdn/tag/body'),
				'unique' => true,
				'translit' => true,
			],
			'body-images-get' => [
				'class' => 'vova07\imperavi\actions\GetImagesAction',
				'url' => Yii::getAlias('@cdnUrl/tag/body'),
				'path' => Yii::getAlias('@cdn/tag/body'),
				'options' => ['only' => ['*.jpg', '*.jpeg', '*.png', '*.gif']],
			],
			'body-file-delete' => [
				'class' => 'vova07\imperavi\actions\DeleteFileAction',
				'url' => Yii::getAlias('@cdnUrl/tag/body'),
				'path' => Yii::getAlias('@cdn/tag/body'),
			]
		];
	}

	public function actionAdd()
	{
		if ( !\Yii::$app->request->isAjax || !\Yii::$app->request->isPost || !($project = \Yii::$app->request->post('project')) || !($value = \Yii::$app->request->post('value')) ) {
			throw new NotFoundHttpException();
		}
		/** @var Tag $model */
		$model = new $this->modelClass(['project_id' => $project]);
		$data = explode('|', $value);
		foreach (array_values(\Yii::$app->languages->languages) as $i => $language) {
			for($j = $i;$j>=0;$j--) {
				$name = '';
				if ( isset($data[$j])  ) {
					$name = $data[$j];
				}
			}
			$model->setAttribute(AdminHelper::getLangField('name', $language), $name);
		}
		if (!$model->save()) {
			throw new Exception(SiteHelper::getErrorMessages($model->errors));
		}
		\Yii::$app->response->format = Response::FORMAT_JSON;
		return [
			'id' => $model->id,
			'name' => $model->getAttribute(AdminHelper::getLangField('name')),
		];
	}

}