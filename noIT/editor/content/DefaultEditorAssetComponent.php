<?php
namespace noIT\editor\content;

use yii\helpers\StringHelper;
use yii\helpers\Url;

class DefaultEditorAssetComponent extends \yii\base\Component
{
	/**
	 * @param $model
	 *
	 * @return array
	 */
	public function getAsset($model)
	{
		$modelClass = strtolower(StringHelper::basename($model::className()));

		return [
			'settings' => [
				'lang' => 'ru',
				'minHeight' => 260,
				'buttons' => ['html', 'formatting', 'bold', 'italic', 'underline', 'deleted', 'unorderedlist', 'orderedlist', 'image', 'table', 'link', 'alignment', 'horizontalrule'],
				'formatting' => ['p', 'blockquote', 'pre', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6'],
				'imageUpload' => Url::to(["{$modelClass}/body-image-upload"]),
				'imageManagerJson' => Url::to(["{$modelClass}/body-images-get"]),
				'imageDelete' => Url::to(["{$modelClass}/body-file-delete"]),
				'plugins' => [
					'fullscreen',
					'table',
					'video',
				],
			],
			'plugins' => [
				'imagemanager' => 'vova07\imperavi\bundles\ImageManagerAsset',
			],
		];
	}
}