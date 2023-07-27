<?php

namespace common\components\tree\treelist;

use Yii;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;

class TreeListWidget extends \common\components\tree\TreeWidget {

    public $displayField = 'name';

    /**
     * Init the widget object.
     */
    public function init() {
        parent::init();
    }

    /**
     * Runs the widget.
     */
    public function run() {
        $run = parent::run();
        if (!is_null($run))
            return $run;

        $models = $this->_hierarchyTreeData(array_values($this->dataProvider->getModels()), $this->rootParentId);
        return $this->renderTreelist($models);
    }

    protected function renderTreelist($models) {
        foreach ($models as $index => $model) {
            $row = $this->renderTreelistItem($model['item']);
            $children = empty($model['children']) ? '' : $this->renderTreelist($model['children']);
            $output[] = '<li>'. $row . $children .'</li>';
        }

        if (!empty($output))
            return '<ul>'. implode("\n", $output) .'</ul>';
    }

    protected function renderTreelistItem($model)
    {
        $options = [];
        $id = ArrayHelper::getValue($model, $this->keyNameId);
        Html::addCssClass($options, "treelistitem-$id");

        $parent_id = ArrayHelper::getValue($model, $this->keyNameParentId);
        if ($parent_id) {
            Html::addCssClass($options, "treelistitem-parent-$parent_id");
        }

//        if (is_string($this->value)) {
//            return ArrayHelper::getValue($model, $this->value);
//        } else {
//            return call_user_func($this->value, $model, $key, $index, $this);
//        }

        return Html::tag('span', ArrayHelper::getValue($model, $this->displayField), $options);
    }
}