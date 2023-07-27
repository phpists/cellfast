<?php

namespace common\components\GdsCalc\models\drain;

use common\components\GdsCalc\models\house\GdsCalcModelHouseE;
use yii\helpers\ArrayHelper;

/**
 * Class GdsCalcModelDrainHouseD
 * Модель здания с крышей типа Т1 для расчета водостоков
 * @package common\components\GdsCalc\models\drain
 */

class GdsCalcModelDrainHouseE extends GdsCalcModelHouseE implements GdsCalcModelDrainHouseInterface
{
    public $name = 'Расчет водостока вальмовой крыши с выносом';

    public $alias = 'DrainHouseE';

    public $drain_sides = ['sideA', 'sideB', 'sideB', 'sideK', 'sideG', 'sideG', 'sideJ', 'sideF'];

    public $drain_getter_corner_connectors = 2;

    /*
     * Количество заглушек желоба
     */
    public $drain_gutter_dummy = 16;

    /*
     * Количество сливов
     */
    public $drain_gutter_gullies = 8;

    public function rules()
    {
        return ArrayHelper::merge(parent::rules(), [
            [['sideA', 'sideB', 'sideC', 'sideD', 'sideG', 'sideE', 'sideF'], 'required'],
        ]);
    }
}