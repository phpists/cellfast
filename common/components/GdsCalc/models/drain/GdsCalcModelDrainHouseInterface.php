<?php

namespace common\components\GdsCalc\models\drain;

/**
 * Interface GdsCalcModelDrainHouseInterface
 * Интерфейс здания для расчета водостоков
 * @package common\components\GdsCalc\models\drain
 */

interface GdsCalcModelDrainHouseInterface
{
    /**
     * @return float Длина желобов
     */
    public function calcDrainGutterLength();

    /**
     * @return float Количество желобов
     */
    public function calcDrainGutterCount();

    /**
     * @return integer Количество соединителей (муфт) желоба
     */
    public function calcDrainGutterConnectorsCount();

    /**
     * @return integer Количество угловых соединителей желоба
     */
    public function calcDrainGutterCornerConnectors($side);

    /**
     * @return integer Количество заглушек желоба
     */
    public function calcDrainGutterDummy($side);

    /**
     * @return integer Количество креплений желоба
     * В отдельных моделях крыш требуется переопределение
     */
    public function calcDrainGutterBrackets();

    /**
     * @return integer Количество дождеприемников
     */
    public function calcDrainGutterGullyCount($systemValue);

    /**
     * @return integer Количество стоков
     */
    public function calcDrainPipeCount($systemValue);

    /**
     * @return float Длина трубы
     */
    public function calcDrainPipeLength($systemValue);

    /**
     * @return integer Количество соединителей трубы (60 градусов)
     */
    public function calcDrainPipeConnectorsCount($systemValue);

    /**
     * @return integer Количество креплений трубы
     */
    public function calcDrainPipeBracketsCount($systemValue);
}
