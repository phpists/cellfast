<?php

namespace common\components\GdsCalc\models\house;

use yii\helpers\ArrayHelper;

/**
 * Class GdsCalcModelHouseA
 * Модель здания с двухскатной крышей
 * @package common\components\GdsCalc\models\house
 */

abstract class GdsCalcModelHouseB extends GdsCalcModelHouseAbstract
{
    public $name = 'Type B';

    public $alias = 'houseB';

    public $sideA, $sideB, $sideC;

    public function attributeLabels()
    {
        return ArrayHelper::merge(parent::attributeLabels(), [

        ]);
    }

    public function calcRoofArea()
    {
        return $this->sideA * $this->sideB * 2;
    }

    public function calcPerimeter()
    {
        return $this->sideA * 2 + $this->sideB * 4;
    }
}
