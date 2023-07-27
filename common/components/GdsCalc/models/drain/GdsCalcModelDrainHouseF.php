<?php

namespace common\components\GdsCalc\models\drain;

use common\components\GdsCalc\models\house\GdsCalcModelHouseF;
use yii\helpers\ArrayHelper;

/**
 * Class GdsCalcModelDrainHouseF
 * Модель здания с крышей типа Т1 для расчета водостоков
 * @package common\components\GdsCalc\models\drain
 * @property float $sideD
 */

class GdsCalcModelDrainHouseF extends GdsCalcModelHouseF
{
    public $name = 'Calculation of a gutter for a roof of type F';

    public $alias = 'DrainHouseF';

    public $drain_sides = ['sideA', 'sideA', 'sideD', 'sideD'];

    /**
     * @var int Количество внутренних угловых стыков желоба
     */
    public $drain_getter_corner_connectors_inner = 0;

    /**
     * @var int Количество внешних угловых стыков желоба
     */
    public $drain_getter_corner_connectors_outer = 4;

    /*
     * Количество заглушек желоба
     */
    public $drain_gutter_dummy_left = 0;

    public $drain_gutter_dummy_right = 0;

    /*
     * Количество сливов
     */
    public $drain_gutter_gullies = 4;

    public function rules()
    {
        return ArrayHelper::merge(parent::rules(), [
            [['sideA', 'sideB', 'sideC'], 'required'],
        ]);
    }

    public function getSideD() {
        return $this->sideB * 2;
    }

    /**
     * + Количество соединений (муфт) желоба
     * @return int
     */
    public function calcDrainGutterConnectorsCount() {
        return intval((ceil($this->sideA / $this->drain_gutter_length) - 1) * 2 + (ceil($this->sideD / $this->drain_gutter_length) - 1) * 2);
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
