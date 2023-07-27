<?php

namespace backend\widgets;

use yii\widgets\InputWidget;
use yii\helpers\BaseHtml;
use yii\helpers\Html;

class MetronicRadioList extends InputWidget
{

	public $items;
	public $options;
	private $inputTemplate = '{input} {field_label}<span></span>';

	public function run()
	{
		return $this->radioList($this->model, $this->attribute, $this->items, $this->options);
	}

	public function radioList($model, $attribute, $items, $options)
	{
		$name = isset($options['name']) ? $options['name'] : Html::getInputName($model, $attribute);

		if (!array_key_exists('class', $options)) {
			$options['class'] = 'm-radio-list';
		}

		$selection = isset($options['value']) ? $options['value'] : Html::getAttributeValue($model, $attribute);

		if (!array_key_exists('unselect', $options)) {
			$options['unselect'] = '';
		}

		if (!array_key_exists('id', $options)) {
			$options['id'] = BaseHtml::getInputId($model, $attribute);
		}

		$radioList = [];

		foreach ($items as $key => $value) {

			$checked = $selection === $key;

			$input = strtr($this->inputTemplate, [
				'{input}' => Html::radio($name, $checked, ['value' => $key]),
				'{field_label}' => $value
			]);

			$radioList[] = Html::tag('label', $input, ['class' => 'm-radio']);

		}

		$radioListInString = implode('', $radioList);

		return Html::tag('div', $radioListInString, $options);
	}

}