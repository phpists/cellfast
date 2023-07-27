<?php
namespace noIT\language;

use Yii;
use yii\base\Component;

class Language extends Component
{
    /** @var $languages LanguageModel[] */
    public $languages;

    public $languageModel = 'noIT\language\LanguageModel';

    public $current;

    public $default;

    public function init()
    {
        parent::init();

        foreach ($this->languages as $code => &$language) {
            if (is_array($language)) {
                if (empty($language['code'])) {
                    $language['code'] = $code;
                }
                if (empty($language['class']) && !empty($this->languageModel)) {
                    $language['class'] = $this->languageModel;
                }
                $language = Yii::createObject($language);
            }
        }

        if (null == $this->current) {
            $this->current = $this->getLanguage(Yii::$app->language);
        }

        if (null == $this->default) {
            $this->default = Yii::$app->language;
        }
    }

    public function getLanguage($code) {
        return isset($this->languages[$code]) ? $this->languages[$code] : null;
    }
}