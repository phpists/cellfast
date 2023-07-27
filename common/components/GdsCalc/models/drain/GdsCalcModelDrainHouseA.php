<?php

namespace common\components\GdsCalc\models\drain;

use common\components\GdsCalc\models\house\GdsCalcModelHouseA;
use yii\helpers\ArrayHelper;

/**
 * Class GdsCalcModelDrainHouseA
 * Модель здания с односкатной крышей для расчета водостоков
 * @package common\components\GdsCalc\models\drain
 */

class GdsCalcModelDrainHouseA extends GdsCalcModelHouseA
{
    public $name = 'Calculation of a gutter for a roof of type A';

    public $alias = 'DrainHouseA';

    public $drain_sides = ['sideA'];

    /**
     * @var int Количество внутренних угловых стыков желоба
     */
    public $drain_getter_corner_connectors_inner = 0;

    /**
     * @var int Количество внешних угловых стыков желоба
     */
    public $drain_getter_corner_connectors_outer = 0;

    public $drain_gutter_dummy_left = 1;

    public $drain_gutter_dummy_right = 1;

    public function rules()
    {
        return ArrayHelper::merge(parent::rules(), [
            [['sideA', 'sideB'], 'required'],
        ]);
    }

    public function calcDrainGutterConnectorsCount()
    {
        return $this->calc('DrainGutterCount') - 1;
    }

    /**
     * + Количество ливнеприемников (воронок)
     * @return integer
     */
    public function calcDrainGutterGullyCount($systemValue)
    {
        return intval(ceil($this->calcEpd() / $this->getCalcRoofArea($systemValue)));
    }
}
