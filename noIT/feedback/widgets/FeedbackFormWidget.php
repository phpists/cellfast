<?php

namespace noIT\feedback\widgets;

use yii\base\Widget;

class FeedbackFormWidget extends Widget
{
    public $view;

    public $modelClass = 'noIT\feedback\models\Feedback';

    public $model;

    public $params = [];

    public function init()
    {
        parent::init();

        if ($this->model === null) {
            $this->model = new $this->modelClass();
        }
    }

    public function run()
    {
        parent::run();

        return $this->render($this->view, array_merge([
            'model' => $this->model,
        ], $this->params));
    }
}