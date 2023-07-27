<?php

namespace common\components\GdsCalc\models\house;

use yii\helpers\ArrayHelper;

abstract class GdsCalcModelHouseC extends GdsCalcModelHouseAbstract
{
    public $name = 'Type C';

    public $alias = 'houseC';

    public $sideA, $sideB, $sideC, $sideD, $sideE, $sideF;

    public function attributes()
    {
        return ArrayHelper::merge(parent::attributes(), ['sideG']);
    }

    public function getSideG() {
        return $this->sideA - ($this->sideE + $this->sideD);
    }

    public function attributeLabels()
    {
        return ArrayHelper::merge(parent::attributeLabels(), [
            'sideA' => 'Длина крыши (A)',
            'sideB' => 'Ширина крыши (B)',
            'sideC' => 'Ширина отвеса (C)',
            'sideD' => 'Ширина выступа (D)',
            'sideE' => 'Отрезок E (E)',
            'sideF' => 'Отрезок F (F)',
//            'sideG' => 'Отрезок G (G)',
        ]);
    }

    public function calcRoofArea()
    {
        return $this->sideA * $this->sideB * 2 + $this->sideF * $this->sideD;
    }

    public function calcPerimeter()
    {
        return $this->sideA * 2 + $this->sideF * 2;
    }
}