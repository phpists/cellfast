<?php

namespace common\components\GdsCalc\models\house;

use yii\helpers\ArrayHelper;

abstract class GdsCalcModelHouseE extends GdsCalcModelHouseAbstract
{
    public $name = 'Type E';

    public $alias = 'houseE';

    public $sideA, $sideB, $sideC, $sideD, $sideE, $sideF, $sideG, $sideK, $sideJ;

    public function rules()
    {
        return ArrayHelper::merge(parent::rules(), [
            [['sideA', 'sideB', 'sideC', 'sideD', 'sideE', 'sideF', 'sideG', 'sideJ', 'sideK', 'height'], 'number', 'min' => 0]
        ]);
    }

    public function attributeLabels()
    {
        return ArrayHelper::merge(parent::attributeLabels(), [
            'sideA' => 'Длина крыши (A)',
            'sideB' => 'Ширина крыши (B)',
            'sideC' => 'Ширина отвеса (C)',
            'sideD' => 'Глубина выступа (D)',
            'sideE' => 'Отрезок E',
            'sideF' => 'Отрезок F',
            'sideK' => 'Отрезок K ',
            'sideJ' => 'Ширина выноса (J)',
            'sideG' => 'Глубина выноса (G)',
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