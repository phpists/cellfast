<?php

namespace backend\widgets;

use Faker\Provider\Base;
use yii\helpers\ArrayHelper;
use yii\helpers\BaseHtml;
use yii\helpers\Html;
use yii\widgets\InputWidget;

class MetronicCheckboxList extends InputWidget
{

	public $items;
	public $options;
	private $inputTemplate = '{input} {field_label}<span></span>';

	public function run()
	{
		return $this->checkboxListNew($this->model, $this->attribute, $this->items, $this->options);
	}

	public function checkboxList($model, $attribute, $items, $options)
	{

		$name = isset($options['name']) ? $options['name'] : Html::getInputName($model, $attribute);
		$selection = isset($options['value']) ? $options['value'] : Html::getAttributeValue($model, $attribute);
		if (!array_key_exists('unselect', $options)) {
			$options['unselect'] = '';
		}

		if (!array_key_exists('id', $options)) {
			$options['id'] = Html::getInputId($model, $attribute);
		}

		return Html::checkboxList($name, $selection, $items, $options);
	}


	public function checkboxListNew($model, $attribute, $items, $options)
	{
		$name = isset($options['name']) ? $options['name'] : Html::getInputName($model, $attribute);

		$selection = isset($options['value']) ? $options['value'] : Html::getAttributeValue($model, $attribute);


		if (!array_key_exists('class', $options)) {
			$options['class'] = 'm-checkbox-list';
		}

		if (!array_key_exists('unselect', $options)) {
			$options['unselect'] = '';
		}

		if (!array_key_exists('id', $options)) {
			$options['id'] = Html::getInputId($model, $attribute);
		}

		$checkList = [];

		foreach ($items as $key => $value) {

			$checked = $selection === $key;

			$input = strtr($this->inputTemplate, [
				'{input}' => Html::checkbox($name, $checked, ['value' => $key]),
				'{field_label}' => $value
			]);

			$checkList[] = Html::tag('label', $input, ['class' => 'm-checkbox']);

		}

		$checkListInString = implode('', $checkList);

		return Html::tag('div', $checkListInString, $options);
	}




}