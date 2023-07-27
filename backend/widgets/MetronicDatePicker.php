<?php

namespace backend\widgets;

use kartik\datecontrol\DateControl;

class MetronicDatePicker extends DateControl
{
	public $type = DateControl::FORMAT_DATE;
	public $ajaxConversion = true;
	public $placeholder;
	public $widgetOptions = [
		'options' => [],
		'pluginOptions' => [
			'autoclose' => true,
		]
	];
	public $pluginOptions = [
		'autoclose' => true
	];

	public function run()
	{
		$this->widgetOptions['options'] = [
			'placeholder' => $this->placeholder,
			'autocomplete' => 'off',
		];

		parent::run();
	}

}
