<?php

namespace backend\widgets;

use yii\helpers\BaseHtml;
use yii\helpers\Html;
use yii\widgets\InputWidget;

class MetronicFileInput extends InputWidget {

	public function run()
	{

		return $this->fileInput($this->model, $this->attribute, $this->options);
	}

	public function fileInput($model, $attribute, $options)
	{

		$name = isset($options['name']) ? $options['name'] : Html::getInputName($model, $attribute);

		$value = isset($options['value']) ? $options['value'] : Html::getAttributeValue($model, $attribute);

		if (!array_key_exists('id', $options)) {
			$options['id'] = Html::getInputId($model, $attribute);
		}

		return Html::input('file', $name, $value, $options);
	}

}