<?php

namespace noIT\tips;

use noIT\tips\models\Tip;
use yii\base\Model;
use yii\db\ActiveRecord;
use yii\db\Exception;
use yii\helpers\ArrayHelper;
use yii\helpers\StringHelper;

/**
 * tips module definition class
 */
class Module extends \yii\base\Module
{
	public $models;

	protected $modelsArray;

	/**
	 * {@inheritdoc}
	 */
	public $controllerNamespace = 'noIT\tips\controllers';

	/**
	 * {@inheritdoc}
	 */
	public function init()
	{
		parent::init();

		$models = [];

		foreach ($this->models as $className => $label) {

		    /** @var $value ActiveRecord */
		    if (is_numeric($className)) {
                $className = $label;
		        $label = StringHelper::basename(get_class($className));
            }

		    if (!class_exists($className)) {
		        throw new \yii\base\Exception("{$className} not found");
            }

            $models[$className] = $label;
        }

		$this->models = $models;
	}

    /**
     * @param $modelName string
     * @return string|null
     */
	public function getModelLabel($modelName)
	{
		return isset($this->models[$modelName]) ? $this->models[$modelName] : null;
	}

    /**
     * @param $modelName string
     * @return Model|null
     */
	public function getModelObject($modelName)
	{
        return isset($this->models[$modelName]) ? new $modelName() : null;
	}

    /**
     * @param $modelName string
     * @return array
     */
	public function getCleanModelAttributes($modelName)
	{
        if (!($model = $this->getModelObject($modelName))) {
            return [];
		}

		return $model->attributeLabels();
	}

}
