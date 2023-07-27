<?php
namespace common\models;

use Imagine\Image\ManipulatorInterface;
use Yii;
use yii\base\Model;
use yii\base\ModelEvent;
use yii\imagine\BaseImage;
use zxbodya\yii2\galleryManager\GalleryBehavior;
use Zxing\NotFoundException;

class Banner extends Model
{
	const EVENT_BEFORE_DELETE = 'beforeDelete';
	const EVENT_AFTER_FIND = 'afterFind';
	const EVENT_AFTER_UPDATE = 'afterUpdate';

	public function behaviors()
	{
		return [
			'gallery__cellfast' => [
				'class' => GalleryBehavior::className(),
				'type' => 'banner-cellfast',
				'extension' => 'jpg',
				'directory' => Yii::getAlias('@cdn') . '/slider/cellfast',
				'url' => Yii::getAlias('@cdnUrl') . '/slider/cellfast',
				'versions' => [
					'thumb' => function ($img) {
						/** @var \Imagine\Image\ImageInterface $img */
						return $img
							->copy()
							->thumbnail(new \Imagine\Image\Box(1920, 630));
					},
				]
			],
			'gallery__bryza' => [
				'class' => GalleryBehavior::className(),
				'type' => 'banner-bryza',
				'extension' => 'jpg',
				'directory' => Yii::getAlias('@cdn') . '/slider/bryza',
				'url' => Yii::getAlias('@cdnUrl') . '/slider/bryza',
				'versions' => [
					'thumb' => function ($img) {
						/** @var \Imagine\Image\ImageInterface $img */
						return $img
							->copy()
							->thumbnail(new \Imagine\Image\Box(1920, 630));
					},
				]
			],
			'gallery__ines' => [
				'class' => GalleryBehavior::className(),
				'type' => 'banner-ines',
				'extension' => 'jpg',
				'directory' => Yii::getAlias('@cdn') . '/slider/ines',
				'url' => Yii::getAlias('@cdnUrl') . '/slider/ines',
				'versions' => [
					'thumb' => function ($img) {
						/** @var \Imagine\Image\ImageInterface $img */
						return $img
							->copy()
							->thumbnail(new \Imagine\Image\Box(1920, 630));
					},
				]
			],
		];
	}

	public static function getPrimaryKey()
	{
		return ['id'];
	}

	public static function primaryKey() {
		return self::getPrimaryKey();
	}

	public function beforeDelete()
	{
		$event = new ModelEvent();
		$this->trigger(self::EVENT_BEFORE_DELETE, $event);

		return $event->isValid;
	}

	public function afterFind()
	{
		$this->trigger(self::EVENT_AFTER_FIND);
	}

	public function afterUpdate()
	{
		$this->trigger(self::EVENT_AFTER_UPDATE);
	}

	public function getGallery()
	{
		$project = Yii::$app->projects->current->alias;

		return $this->getBehavior("gallery__{$project}")->getImages();
	}

	public static function findOne($id)
	{
		return new Banner();
	}

}