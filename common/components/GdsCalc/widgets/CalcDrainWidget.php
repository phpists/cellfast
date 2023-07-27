<?php

namespace common\components\GdsCalc\widgets;

use yii\base\Widget;
use yii\helpers\ArrayHelper;

class CalcDrainWidget extends Widget
{
    public $component = 'gdscalcDrain';

    public $assets;

    public $params = [];

    public function run()
    {
        $component = \Yii::$app->{$this->component};

        if ($this->assets) {
            call_user_func([$this->assets, 'register'], $this->view);
        }

        return $this->render('calc-drain/index', ArrayHelper::merge($this->params, [
        	'component' => $component,
	        'component_alias' => $this->component
        ]));
    }
}
