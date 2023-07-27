<?php

namespace common\components\GdsCalc\models\house;

use yii\helpers\ArrayHelper;

abstract class GdsCalcModelHouseG extends GdsCalcModelHouseAbstract
{
    public $name = 'Type G';

    public $alias = 'houseG';

    public $sideA, $sideB, $sideC, $sideD, $sideE, $sideF, $sideG;

    public function rules()
    {
        return ArrayHelper::merge(parent::rules(), [
            [['sideA', 'sideB', 'sideC', 'sideD', 'sideE', 'sideF'], 'number', 'min' => 0]
        ]);
    }

    public function attributeLabels()
    {
        return ArrayHelper::merge(parent::attributeLabels(), [
            'sideA' => 'Длина крыши (A)',
            'sideB' => 'Ширина ската (B)',
            'sideC' => 'Ширина отвеса (C)',
            'sideD' => 'Отрезок D',
            'sideE' => 'Отрезок E',
            'sideF' => 'Длина крыши (F)',
        ]);
    }

    public function calcRoofArea()
    {
        // @todo Релазиовать при надобности опредедения площади крыши (решить какими параметрами задавать площадь)
    }

    public function calcPerimeter()
    {
        return $this->sideA * 2 + $this->sideG * 2;
    }
}