<?php

namespace common\widgets;

use Yii;
use yii\bootstrap\Dropdown;

class LanguageSwitcher extends Dropdown
{
    private $_isError;

    public function init()
    {
        $route = Yii::$app->controller->route;
        $params = $_GET;
        $this->_isError = $route === Yii::$app->errorHandler->errorAction;

        array_unshift($params, '/' . $route);

        foreach (Yii::$app->languages->languages as $lang_code => $language) {
            $params['language'] = $lang_code;
            $this->items[] = [
                'code' => $lang_code,
                'label' => $language->short,
                'url' => $params,
                'active' => (Yii::$app->language == $lang_code),
            ];
        }

        parent::init();
    }

    public function run()
    {
        // Only show this widget if we're not on the error page
        if ($this->_isError) {
            return '';
        } else {
            return $this->render('lang-switcher', ['items' => $this->items, 'language' => Yii::$app->languages->current]);

        }
    }

    public static function language($code) {
        return Yii::$app->languages->getLanguage($code);
    }
}