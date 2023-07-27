<?php

namespace common\components\GdsCalc\models\drain;

use common\components\GdsCalc\models\house\GdsCalcModelHouseD;
use yii\helpers\ArrayHelper;

/**
 * Class GdsCalcModelDrainHouseD
 * Модель здания с крышей типа Т1 для расчета водостоков
 * @package common\components\GdsCalc\models\drain
 */

class GdsCalcModelDrainHouseD extends GdsCalcModelHouseD implements GdsCalcModelDrainHouseInterface
{
    public $name = 'Расчет водостока многощипцовой крыши';

    public $alias = 'DrainHouseD';

    public $drain_sides = ['sideA', 'sideE', 'sideF', 'sideG', 'sideG'];

    public $drain_getter_corner_connectors = 2;

    /*
     * Количество заглушек желоба
     */
    public $drain_gutter_dummy = 6;

    /*
     * Количество сливов
     */
    public $drain_gutter_gullies = 5;

    public function rules()
    {
        return ArrayHelper::merge(parent::rules(), [
            [['sideA', 'sideB', 'sideC', 'sideD', 'sideG', 'sideE', 'sideF', 'height'], 'required']
        ]);
    }
}