<?php
namespace noIT\content;

use yii\base\Module;

class ContentModule extends Module {
    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'noIT\content\controllers';

    public $types = [
        'article' => 'Article'
    ];

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        foreach ($this->types as &$type) {
            $type = \Yii::t('app', $type);
        }

//        $this->setModule('admin');
    }
}