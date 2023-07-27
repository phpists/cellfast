<?php

namespace noIT\wysiwyg\controllers;

use yii\web\Controller;

/**
 * Filesystem controller for the `wysiwyg` module
 */
class FilesystemController extends Controller {
	public function actions()
	{
		return [
			'image-upload' => [
				'class' => \vova07\imperavi\actions\UploadAction::className(),
				'url' => $this->module->uploadUrl,
				'path' => $this->module->uploadPath,
			],
		];
	}

	public function indexUpload($entity, $entity_id) {
		$model = $this->findModel($id);

		$model->photos_upload = UploadedFile::getInstances($model, 'photos_upload');

		$model->uploadPhotos();

		Yii::$app->response->format = Response::FORMAT_JSON;

		return [];
	}
}
