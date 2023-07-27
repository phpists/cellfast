<?php

namespace noIT\feedback;

/**
 * feedback module definition class
 */
class FeedbackModule extends \yii\base\Module
{

    public $adminEmails = [];
    /**
     * {@inheritdoc}
     */
    public $controllerNamespace = 'noIT\feedback\controllers';

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();
    }
}
