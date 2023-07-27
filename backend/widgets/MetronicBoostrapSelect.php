<?php
namespace backend\widgets;

use yii\helpers\Html;
use yii\widgets\InputWidget;

class MetronicBoostrapSelect extends InputWidget
{
	const SELECT_CLASS = 'form-control m-bootstrap-select m_selectpicker';

	public $prompt;
	public $items;

	public function run()
	{

		return Html::activeDropDownList($this->model, $this->attribute, $this->items, [
			'prompt' => $this->prompt,
			'class' => self::SELECT_CLASS
		]);

	}
}
