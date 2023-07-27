<?php

namespace common\components\GdsCalc\models\house;

use yii\helpers\ArrayHelper;

abstract class GdsCalcModelHouseD extends GdsCalcModelHouseAbstract
{
    public $name = 'Type D';

    public $alias = 'houseD';

    public $sideA, $sideB, $sideC, $sideD, $sideE, $sideF, $sideG;

    public function rules()
    {
        return ArrayHelper::merge(parent::rules(), [
            [['sideA', 'sideB', 'sideC', 'sideD', 'sideG', 'sideE', 'sideF'], 'number', 'min' => 0],
        ]);
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
            'sideG' => 'Глубина выступа (G)',
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