<?php

namespace common\components\GdsCalc\models\drain;

use common\components\GdsCalc\models\house\GdsCalcModelHouseC;
use yii\helpers\ArrayHelper;

/**
 * Class GdsCalcModelDrainHouseA
 * Модель здания с крышей типа Т1 для расчета водостоков
 * @package common\components\GdsCalc\models\drain
 */

class GdsCalcModelDrainHouseC extends GdsCalcModelHouseC
{
    public $name = 'Расчет водостока двухскатной крыши с козырьком';

    public $alias = 'DrainHouseC';

    public $drain_sides = ['sideA', 'sideE', 'sideD', 'sideG'];

    public $drain_getter_corner_connectors = 0;

    /*
     * Количество заглушек желоба
     */
    public $drain_gutter_dummy_left = 4;

    public $drain_gutter_dummy_right = 4;

    public function rules()
    {
        return ArrayHelper::merge(parent::rules(), [
            [['sideA', 'sideB', 'sideC', 'sideD', 'sideE', 'sideF', 'height'], 'required']
        ]);
    }
}
