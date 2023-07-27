<?php

namespace common\components\GdsCalc\models\drain;

use common\components\GdsCalc\models\house\GdsCalcModelHouseG;
use yii\helpers\ArrayHelper;

/**
 * Class GdsCalcModelDrainHouseG
 * Модель здания с крышей типа Т1 для расчета водостоков
 * @package common\components\GdsCalc\models\drain
 */

class GdsCalcModelDrainHouseG extends GdsCalcModelHouseG implements GdsCalcModelDrainHouseInterface
{
    public $name = 'Расчет водостока двухскатной угловой крыши';

    public $alias = 'DrainHouseG';

    public $drain_sides = ['sideA', 'sideF', 'sideE', 'sideD'];

    public $drain_getter_corner_connectors = 1;

    /*
     * Количество заглушек желоба
     */
    public $drain_gutter_dummy = 6;

    /*
     * Количество сливов
     */
    public $drain_gutter_gullies = 4;

    public function rules()
    {
        return ArrayHelper::merge(parent::rules(), [
            [['sideA', 'sideB', 'sideC', 'sideD', 'sideG', 'sideE', 'sideF'], 'required'],
        ]);
    }
}