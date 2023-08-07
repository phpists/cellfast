<?php
namespace ines\widgets;

class Nav extends \yii\bootstrap\Nav
{
	public function run()
	{
		/** Remove BootstrapAsset used */
		return $this->renderItems();
	}
}
