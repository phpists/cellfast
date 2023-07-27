<?php

namespace common\components\GdsCalc\models\house;

use yii\helpers\ArrayHelper;

/**
 * Class GdsCalcModelHouseA
 * Модель здания с односкатной крышей
 * @package common\components\GdsCalc\models\house
 */

abstract class GdsCalcModelHouseA extends GdsCalcModelHouseAbstract
{
    public $name = 'Type A';

    public $alias = 'houseA';

    public function attributeLabels()
    {
        return ArrayHelper::merge(parent::attributeLabels(), [

        ]);
    }

    /**
     * Площадь крыши
     * @return float
     */
    public function calcRoofArea()
    {
        return $this->sideA * $this->sideB;
    }

    /**
     * Полный периметр
     * @return float
     */
    public function calcPerimeter()
    {
        return $this->sideA * 2 + $this->sideB * 2;
    }
}
