<?php

namespace backend\widgets;

use yii\helpers\BaseHtml;
use yii\helpers\Html;
use yii\widgets\InputWidget;

class MetronicSingleCheckbox extends InputWidget
{

	const ACTIVE_POSITION = 10;
	const DEFAULT_POSITION = 0;

	public $label;
	private $inputTemplate = '{input} {field_label}<span></span>';

	public function run()
	{
		return $this->checkbox($this->model, $this->attribute, $this->options);
	}

	public function checkbox($model, $attribute, $options = [])
	{
		$name = isset($options['name']) ? $options['name'] : Html::getInputName($model, $attribute);

		$value = isset($options['value']) ? $options['value'] : Html::getAttributeValue($model, $attribute);

		if (!array_key_exists('value', $options)) {
			$options['value'] = self::ACTIVE_POSITION;
		}

		if (!array_key_exists('uncheck', $options)) {
			$options['uncheck'] = self::DEFAULT_POSITION;
		} else if ($options['uncheck'] === false) {
			unset($options['uncheck']);
		}

		$checked = "$value" === "{$options['value']}";

		if (!array_key_exists('id', $options)) {
			$options['id'] = BaseHtml::getInputId($model, $attribute);
		}

		$input = strtr($this->inputTemplate, [
			'{input}' => Html::checkbox($name, $checked, $options),
			'{field_label}' => $this->label
		]);

		$label = Html::tag('label', $input, ['class' => 'm-checkbox']);

		$checkbox = Html::tag('div', $label, ['class' => 'm-checkbox-list']);

		return $checkbox;
	}

}