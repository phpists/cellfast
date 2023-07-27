<?php

namespace common\components\GdsCalc\models\house;

use yii\helpers\ArrayHelper;

abstract class GdsCalcModelHouseF extends GdsCalcModelHouseAbstract
{
    public $name = 'Type F';

    public $alias = 'houseF';

    public $sideA, $sideB, $sideC;

    public function rules()
    {
        return ArrayHelper::merge(parent::rules(), [
            [['sideA', 'sideB', 'sideC'], 'number', 'min' => 0]
        ]);
    }

    public function attributeLabels()
    {
        return ArrayHelper::merge(parent::attributeLabels(), [

        ]);
    }

    public function calcRoofArea()
    {
        return 2 * $this->sideA * sqrt(pow($this->sideB) + pow($this->height_full - $this->height) );
    }

    public function calcPerimeter()
    {
        return $this->sideA * 2 + $this->sideB * 4;
    }
}
