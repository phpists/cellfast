<?php

namespace common\components\tree;

use common\components\tree\TreeHelper;

trait TreeQueryTrait {

    static public $cache_tree = [];

    /** @var \yii\db\ActiveQuery $this */
    static $model;

    /*
     * @return \yii\db\ActiveQuery
     */
    private function getModel()
    {
        if (empty(self::$model)) {
            $class = $this->modelClass;
            self::$model = new $class;
        }
        return self::$model;
    }

    public function getTree($group = null, $with = null) {
        $model = $this->getModel();
        if ($group !== null) {
            $this->andWhere([$model->keyNameGroup => $group]);
        }
        if ($with) {
            $this->with($with);
        }
        $this->orderBy([$model->keyNameDepth => SORT_ASC]);
        $data = [];
        foreach($this->all() as $item) {
            $data[$item->{$model->keyNameId}] = $item;
        }
        if (empty($data))
            return [];

        return $this->buildTree($data);
    }

    private function _recursiveRebuild($tree, $parentPath = null, $depth = 0) {
        $model = $this->getModel();

        foreach ($tree as $row) {
            $path = (is_null($parentPath) ? '' : $parentPath . $model->delimiter) . $row['item']->getAttribute($model->keyNameId);
            $row['item']->setAttribute($model->keyNamePath, $path);
            $row['item']->setAttribute($model->keyNameDepth, $depth);
            $row['item']->save();
            if (!empty($row['children'])) {
                $this->_recursiveRebuild($row['children'], $path, $depth+1);
            }
        }
    }

    /**
     * @param int $group
     */
    public function rebuildMP($group, $with = null) {
        $tree = $this->getTree($group, $with);

        $this->_recursiveRebuild($tree);
    }

    protected function buildTree(array $data) {
        $model = $this->getModel();

        $result = [];
        foreach ($data as $i => $element) {
            if ($element->{$model->keyNameDepth} == 0) {
                $result[$element->{$model->keyNameId}]['item'] = $element;
                $result[$element->{$model->keyNameId}]['children'] = [];
                continue;
            }
            $parents = explode(",", $element->{$model->keyNamePath});
            $node = &$result[$parents[0]];
            for($i=1;$i<$element->{$model->keyNameDepth};$i++) {
                $node = &$node['children'][$parents[$i]];
            }
            $node['children'][$element->{$model->keyNameId}]['item'] = $element;
            unset($data[$i]);
        }
        return $result;
    }

    public function normalizeTreeData(array $data, $parentId = null)
    {
        $model = $this->getModel();

        $result = [];
        foreach ($data as $element) {
            if ($element[$model->keyNameParentId] == $parentId) {
                $result[] = $element;
                $children = $this->normalizeTreeData($data, $element[$model->keyNameId]);
                if ($children) {
                    $result = array_merge($result, $children);
                }
            }
        }
        return $result;
    }
}