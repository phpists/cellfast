<?php

namespace common\components\GdsCalc\models\drain;

use common\components\GdsCalc\models\house\GdsCalcModelHouseB;
use yii\helpers\ArrayHelper;

/**
 * Class GdsCalcModelDrainHouseA
 * Модель здания с двухскатной крышей для расчета водостоков
 * @package common\components\GdsCalc\models\drain
 */

class GdsCalcModelDrainHouseB extends GdsCalcModelHouseB
{
    public $name = 'Calculation of a gutter for a roof of type B';

    public $alias = 'DrainHouseB';

    public $drain_sides = ['sideA', 'sideA'];

    /**
     * @var int Количество внутренних угловых стыков желоба
     */
    public $drain_getter_corner_connectors_inner = 0;

    /**
     * @var int Количество внешних угловых стыков желоба
     */
    public $drain_getter_corner_connectors_outer = 0;

    public $drain_gutter_dummy_left = 2;

    public $drain_gutter_dummy_right = 2;

    /*
     * Количество сливов
     */
    public $drain_gutter_gullies = 2;

    public function rules()
    {
        return ArrayHelper::merge(parent::rules(), [
            [['sideA', 'sideB'], 'required'],
        ]);
    }

    /**
     * + Количество соединений (муфт) желоба
     * @return int
     */
    public function calcDrainGutterConnectorsCount()
    {
        return intval((ceil($this->calc('DrainGutterCount') / 2 ) - 1) * 2);
    }

    /**
     * + Количество ливнеприемников (воронок)
     * @return integer
     */
    public function calcDrainGutterGullyCount($systemValue)
    {
        return intval(ceil($this->calcEpd() / $this->getCalcRoofArea($systemValue)) * 2);
    }
}
