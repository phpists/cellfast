<?php
namespace cellfast\widgets;

class Nav extends \yii\bootstrap\Nav
{
	public function run()
	{
		return $this->renderItems();
	}
}