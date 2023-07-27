<?php
namespace noIT\feature\widgets;

use Yii;

/** TODO - noIT  - Вынести в параметры модуля */

class FeatureWidgetHelper {
    public static function getValueTypes() {
        return [
            'str' => Yii::t('app', 'String'),
            'txt' => Yii::t('app', 'Text'),
            'int' => Yii::t('app', 'Integer'),
            'flt' => Yii::t('app', 'Float'),
        ];
    }

    public static function getAdminWidgets() {

    }

    public static function getFilterWidgets() {
        return [
            [
                'id' => 'checkboxes',
                'label' => Yii::t('app', 'Checkboxes'),
                'view' => ''
            ],
            [
                'id' => 'select',
                'label' => Yii::t('app', 'Select'),
                'view' => ''
            ],
            [
                'id' => 'select2',
                'label' => Yii::t('app', 'Select2'),
                'view' => ''
            ],
            [
                'id' => 'color',
                'label' => Yii::t('app', 'Color'),
                'view' => ''
            ],
        ];
    }
}