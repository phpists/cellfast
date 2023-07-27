<?php

namespace common\components\GdsCalc\models\house;

use common\components\GdsCalc\models\GdsCalcModel;
use yii\helpers\ArrayHelper;
use Yii;

/**
 * Class GdsCalcModelHouseAbstract
 * @package common\components\GdsCalc\models\house
 * @property GdsCalcDrain $component
 *
 * calc-params:
 * * Желоб - calcDrainGutterCount
 * * Муфта желоба - calcDrainGutterConnectorsCount
 */
abstract class GdsCalcModelHouseAbstract extends GdsCalcModel
{
    /**
     * http://cdn.noit.me/1/1579532278560.jpg
     */

    /**
     * @var float Длина крыши
     */
    public $sideA;

    /**
     * @var float Горизонтальная проекция ширины одного ската крыши
     */
    public $sideB;

    /**
     * @var float Ширина свеса крыши (выступ крыши от стены здания)
     */
    public $sideC;

    /**
     * @var float Высота здания до основания крыши
     */
    public $height;

    /**
     * @var float Высота здания до конька крыши
     */
    public $height_full;

    public $drain_sides = [];

    /**
     * @var int Количество сливов
     */
    public $drain_gutter_gullies = 1;

    /**
     * @var int Длина желобов
     */
    public $drain_gutter_length = 3;

    /**
     * @var int Длина труб
     */
    public $drain_pipe_length = 3;

    /**
     * @var int Количество внутренних угловых стыков желоба
     */
    public $drain_getter_corner_connectors_inner = 0;

    /**
     * @var int Количество внешних угловых стыков желоба
     */
    public $drain_getter_corner_connectors_outer = 0;

    /**
     * @var int Плотность (частота) креплений желоба - кол-во на метр
     */
    public $drain_pipe_brackets_density = 2;

    /**
     * @var int Плотность (частота) креплений желоба - кол-во на метр
     */
    public $drain_gutter_brackets_density = 2;

    /**
     * @var int Количество правых заглушек желоба
     */
    public $drain_gutter_dummy_right;

    /**
     * @var int Количество левых заглушек желоба
     */
    public $drain_gutter_dummy_left;

    /**
     * @var float Толщина утеплителя / навесного фасада
     */
    public $drain_insulation;

    /**
     * @var float Тип монтажа желоба:
     * 1 - к лобовой доске (в расчете используем кронштейн ПВХ)
     * 2 - к стропилам сверху (в расчете используем кронштейн металлический прямой)
     * 3 - к стропилам сбоку (в расчете используем кронштейн металлический изогнутый)
     */
    public $drain_gutter_install_type;

    public function rules()
    {
        return ArrayHelper::merge(parent::rules(), [
            [['height', 'height_full', 'drain_insulation', 'drain_gutter_install_type'], 'number', 'min' => 0],
            [['sideA', 'sideB', 'sideC', 'height', 'height_full', 'drain_insulation', 'drain_gutter_install_type'], 'required']
        ]);
    }

    public function attributeLabels()
    {
        return ArrayHelper::merge(parent::attributeLabels(), [
            'height' => Yii::t('app', 'Высота до основания крыши (H)'),
            'height_full' => Yii::t('app', 'Высота до конька крыши (H+)'),
            'sideA' => Yii::t('app', 'Длина крыши (A)'),
            'sideB' => Yii::t('app', 'Горизонтальное расстояние от угла до конька крыши (B)'),
            'sideC' => Yii::t('app', 'Ширина отвеса (C)'),
            'drain_insulation' => Yii::t('app', 'Insulation'),
            'drain_gutter_install_type' => Yii::t('app', 'Gutter Install Type'),
        ]);
    }

    /**
     * @return float Высота здания до основания крыши
     */
    public function calcHeight() {
        return $this->height;
    }

    /**
     * @return float Полная высота здания
     */
    public function calcHeightFull() {
        return $this->height_full;
    }

    /**
     * @return float Площадь крыши
     */
    abstract public function calcRoofArea();

    /**
     * @return float Периметр здания
     */
    abstract public function calcPerimeter();

    /**
     * + Длина желоба
     * @return float
     */
    public function calcDrainGutterLength()
    {
        $result = 0;
        foreach ($this->drain_sides as $side) {
            $result += $this->$side;
        }
        return $result;
    }

    /**
     * + Количество желобов
     * @return int
     */
    public function calcDrainGutterCount()
    {
        return intval(ceil($this->calc('DrainGutterLength') / 3));
    }

    /**
     * + Количество соединений (муфт) желоба
     * @return int
     */
    public function calcDrainGutterConnectorsCount() {
        return $this->calc('DrainGutterCount') - 1;
    }

    /**
     * Расчет кол-ва соединителей
     * @param $length Длина
     * @param $element_length Длина элементов
     * @return int
     */
    protected function ConnectorsBySideCount($length, $element_length) {
        return ceil($length / $element_length) - 1;

    }

    /**
     * Расчет кол-ва креплений
     * @param $length Длина
     * @param $density Частота
     * @return int
     */
    protected function BracketsBySide($length, $density) {
        return intval(ceil($length * $density));
    }

    /**
     * Рассчетная площадь ската крыши
     * @param $systemValue string|int
     * @return int
     */
    protected function getCalcRoofArea($systemValue) {
        return isset($this->component->feature_system_calc_areas[$systemValue]) ? $this->component->feature_system_calc_areas[$systemValue] : null;
    }

    /**
     * + Количество ливнеприемников (воронок)
     * @return integer
     */
    abstract public function calcDrainGutterGullyCount($systemValue);

    /**
     * todo Проверить
     * + Количество соединителей трубы
     * @return int
     */
    public function calcDrainPipeConnectorsCount($systemValue) {
        return intval($this->sideC < 0.03 ? ceil($this->height / $this->drain_pipe_length) * $this->calc('DrainGutterGullyCount', $systemValue) : ceil(($this->height - $this->sideC * 0.25) / $this->drain_pipe_length - 1) * $this->calc('DrainGutterGullyCount', $systemValue) );
    }

    /**
     * todo Проверить
     * + Длина всех труб
     * @return float
     */
//    public function calcDrainPipeLength($systemValue) {
//        if ($this->sideC < 0.03) {
//            return ($this->height) * $this->calc('DrainGutterGullyCount', $systemValue);
//        } else {
//            return ($this->height + $this->sideC * 0.25) * $this->calc('DrainGutterGullyCount', $systemValue);
//        }
//    }

    /*
     * Количество креплений желоба
     */
    public function calcDrainGutterBracketsCount()
    {
        $result = 0;
        // Каждые 0,5 метра
        foreach ($this->drain_sides as $side) {
            $result += $this->BracketsBySide($this->$side, $this->drain_gutter_brackets_density);
        }
        return $result;
    }

    /*
     * Количество креплений трубы (хомут и крюк хомута)
     */
    public function calcDrainPipeBracketsCount($systemValue) {
        return $this->sideC < 0.03 ? intval($this->calc('DrainGutterGullyCount', $systemValue) * ceil(($this->height / 1.5 + 1))) : intval($this->calc('DrainGutterGullyCount', $systemValue) * ceil(($this->height / 1.5)));
    }

    /*
     * Количество угловых соединений желоба
     */
    public function calcDrainGutterCornerConnectors($side)
    {
        if (!in_array($side, ['inner', 'outer'])) {
            var_dump($side);exit;
        }

        $attributeName = "drain_getter_corner_connectors_{$side}";

        return $this->$attributeName;
    }

    /*
     * Количество заглушек желоба
     */
    public function calcDrainGutterDummy($side)
    {
        if (!in_array($side, ['left', 'right'])) {
            return;
        }

        $attributeName = "drain_gutter_dummy_{$side}";

        return $this->$attributeName;
    }

    /**
     * ++ Количество труб (по 3 метра)
     * @return int
     */
    public function calcDrainPipeCount($systemValue) {
        $height = $this->sideC < 0.03 ? $this->height : $this->height + $this->sideC * 0.25;

        return intval(ceil($height / 3 * $this->calc('DrainGutterGullyCount', $systemValue)));
    }

    /**
     * ++ Количество колен трубы
     * @return int
     */
    public function calcDrainPipeKneeCount($systemValue) {
        return intval(ceil($this->sideC < 0.03 ? $this->calc('DrainGutterGullyCount', $systemValue) : 3 * $this->calc('DrainGutterGullyCount', $systemValue)));
    }

    /**
     * Коэффициент для расчета площади ЕПД
     * базовая формула http://cdn.noit.me/1/1579348632797.jpg
     * @param $l - длина ската крыши
     * @param $b - ширина ската крыши
     * @param $b0 - высота от от основания крыши до конька крыши (если null, то вычисляется из height и height_full модели)
     * @return float
     */
    public function calcEpd() {
        return ($this->sideB + ($this->height_full - $this->height) / 2) * $this->sideA;
    }

    public function getComponent() {
        return \Yii::$app->gdscalcDrain;
    }

    public function getComponentOption($attribute) {
        return property_exists($this->component, $attribute) ? $this->component->$attribute : null;
    }

    public static function listDrainGutterInsulation() {
        return [
            0 => Yii::t('app', 'без утеплителя'), // в расчете используем крюк хомута 120 мм (артикул 70-004)
            40 => Yii::t('app', 'толщина {width} мм', ['width' => 40]), // в расчете используем крюк хомута 160 мм (артикул 70-005)
            60 => Yii::t('app', 'толщина {width} мм', ['width' => 60]), // в расчете используем крюк хомута 180 мм (артикул 70-006)
            100 => Yii::t('app', 'толщина {width} мм', ['width' => 100]), // в расчете используем крюк хомута 220 мм (артикул 70-010)
            130 => Yii::t('app', 'толщина {width} мм', ['width' => 130]), // в расчете используем крюк хомута 250 мм (артикул 70-019)
        ];
    }

    public static function listDrainGutterInstallType() {
        return [
            1 => Yii::t('app', 'к лобовой доске'),
            2 => Yii::t('app', 'к стропилам сверху'),
            3 => Yii::t('app', 'к стропилам сбоку'),
        ];
    }
}
