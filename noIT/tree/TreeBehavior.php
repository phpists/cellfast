<?php

namespace noIT\tree;

use common\modules\rubrication\models\TaxOption;
use Yii;
use yii\base\Behavior;
use yii\base\Exception;
use yii\base\NotSupportedException;
use yii\db\ActiveRecord;
use yii\db\Expression;

class TreeBehavior extends Behavior {

    /** @var  ActiveRecord $owner */
    public $owner;

    public $keyNameId;
    public $keyNameParentId = 'parent_id';
    public $keyNameGroup = 'group';
    public $keyNamePath = 'path';
    public $keyNameDepth = 'depth';
    public $primaryKeyMode = true;

    /**
     * @var string
     */
    public $delimiter = '|';

    /**
     * @var ActiveRecord|self|null
     */
    protected $entity;

    /**
     * @param ActiveRecord $owner
     * @throws Exception
     */
    public function attach($owner)
    {
        parent::attach($owner);
        if ($this->keyNameId === null) {
            $primaryKey = $owner->primaryKey();
            if (!isset($primaryKey[0])) {
                throw new Exception('"' . $owner->className() . '" must have a primary key.');
            }
            $this->keyNameId = $primaryKey[0];
        }
        if ($this->owner->isNewRecord) {
            $this->owner->setAttribute($this->keyNameParentId, 0);
        }
    }

    public function events()
    {
        return [
            // @todo Use beforeSave for automatic set MP-params
            ActiveRecord::EVENT_BEFORE_UPDATE   => 'beforeUpdate',
            ActiveRecord::EVENT_AFTER_INSERT   => 'afterInsert',
        ];
    }

    /*
     * Main methods
     */

    /*
     * get one parent
     * use AL-method
     */
    public function getParent() {
        return $this->getParentAL();
    }

    /*
     * get all parents
     * use MP-method
     */
    public function getParents() {
        return $this->getParentsMP();
    }

    public function getParentByDepth($depth) {
        $path  = $this->owner->getParentPath(true);
        if (empty($path[$depth])) {
            return null;
        }
        $query = $this->owner->findOne($path[$depth]);
        return $query;
    }

    /*
     * get one-level children items
     * use AL-method
     */
    public function getChildren($where = null) {
        return $this->getChildrenAL($where);
    }

    /*
     * get all-level children items
     * use MP-method
     */
    public function getAllChildren($depth = null, $where = [], $with = null) {
        return $this->getAllChildrenMP($depth, $where, $with);
    }

    public function getAllChildrenIds($depth = null, $where = []) {
        $result = [];
        foreach ($this->getAllChildren($depth, $where)->select(['id'])->asArray()->all() as $category) {
            $result[] = $category['id'];
        }
        return $result;
    }

    /*
     * get all-level children items
     * use MP-method
     */
    public function getAllChildrenTree($depth = null, $where = [], $with = null, $orderBy = null) {
        $query = $this->getAllChildrenMP($depth, $where, $with, $orderBy);
        return $this->buildTree($query->all(), $this->owner->getAttribute($this->keyNameId));
    }

    // @todo Check algorytm
    public function buildTree(array $data, $parentId = 0) {
        $result = [];
        foreach ($data as $key => $element) {
            if ($element->getAttribute($this->keyNameParentId) == $parentId) {
                unset($data[$key]);
                $children = $this->buildTree($data, $element->getAttribute($this->keyNameId));
                $result[] = [
                    'item' => $element,
                    'children' => $children
                ];
            }
        }
        return $result;
    }


    /*
     * ================================
     * MP-methods
     * ================================
     */

    /*
     * Full-path (use MP-method)
     */
    public function getParentsMP($depth = null) {
        $tableName = $this->owner->tableName();
        $path  = $this->owner->getParentPath(true);
        $query = $this->owner->find()
            ->andWhere([ "{$tableName}.{$this->keyNameId}" => $path]);
        if ($depth > 0) {
            $query->andWhere(['>=', "{$tableName}.[[{$this->keyNameDepth}]]", $this->owner->getAttribute($this->keyNameDepth) - $depth]);
        }
//        $query->andWhere(['<', "{$tableName}.[[{$this->keyNameDepth}]]", $this->owner->getAttribute($this->keyNameDepth)]);

        $orderBy = [];
        $orderBy["{$tableName}.[[{$this->keyNameDepth}]]"] = SORT_ASC;
        $orderBy["{$tableName}.[[{$this->keyNameId}]]"]  = SORT_ASC;

        $query
            ->andWhere($this->groupWhere())
            ->addOrderBy($orderBy);
        $query->multiple = true;

        return $query;
    }

    /**
     * @param bool $asArray = false
     * @return null|string|array
     */
    public function getParentPath($asArray = true)
    {
        return static::getParentPathInternal($this->owner->getAttribute($this->keyNamePath), $asArray);
    }
    /**
     * @return array
     */
    protected function groupWhere()
    {
        $tableName = $this->owner->tableName();
        if ($this->keyNameGroup === null) {
            return [];
        } else {
            return ["{$tableName}.[[{$this->keyNameGroup}]]" => $this->owner->getAttribute($this->keyNameGroup)];
        }
    }
    

    public function getAllChildrenMP($depth = null, $where = [], $with = null, $sort = null)
    {
        $tableName = $this->owner->tableName();
        $id = $this->owner->getAttribute($this->keyNameId);
        $path = $this->owner->getAttribute($this->keyNamePath);
        $query = $this->owner->find()
            ->andWhere(['like', $tableName .'.'. $this->keyNamePath, $path .',%', false])
            ->orWhere([$tableName .'.'. $this->keyNameId => $id]);

        if ($depth > 0) {
            $query->andWhere(['<=', "{$tableName}.[[{$this->keyNameDepth}]]", $this->owner->getAttribute($this->keyNameDepth) + $depth]);
        }

        $orderBy = [];
        $orderBy["{$tableName}.[[{$this->keyNameDepth}]]"] = SORT_ASC;
        $orderBy["{$tableName}.[[{$this->keyNameId}]]"]  = SORT_ASC;

        if ($where) {
            $query->andWhere($where);
        }
        if ($with) {
            $query->with($with);
        }
        if ($sort) {
            foreach($sort as $_key => $_dir) {
                $orderBy[$_key] = $_dir;
            }
        }

        $query
            ->andWhere($this->groupWhere())
            ->addOrderBy($orderBy);
        $query->multiple = true;

        return $query;
    }

    /*
     * ================================
     * AL methods
     * ================================
     */

    /*
    * Parent entity (use AL-method)
    * @return \yii\db\ActiveRecord
    */
    public function getParentAL() {
        $parent_id = $this->owner->getAttribute($this->keyNameParentId);
        if (empty($parent_id))
            return null;

        $where = [$this->keyNameId => $parent_id];
        if ($this->keyNameGroup) {
            $where[$this->keyNameGroup] = $this->owner->getAttribute($this->keyNameGroup);
        }

        return $this->owner->find()->where($where)->one();
    }

    /*
     * Get parents by AL-method
     * @return array
     */
    public function getParentsAL() {
        $parent_id = $this->owner->getAttribute($this->keyNameParentId);
        if ($parent_id == 0) {
            return [];
        }

        $parent = $this->owner;
        $parents = [];
        while(true) {
            $parent = $parent->getParentAL();
            if (is_null($parent))
                break;
            $parents[] = $parent;
        }

        return array_reverse($parents);
    }

    /*
    * Children entities (one-step) (use AL-method)
    * @return ActiveQuery
    */
    public function getChildrenAL($where = []) {
        $where[$this->keyNameParentId] = $this->owner->getAttribute($this->keyNameId);
        if ($this->keyNameGroup) {
            $where[$this->keyNameGroup] = $this->owner->getAttribute($this->keyNameGroup);
        }
        return $this->owner->find()->where($where);
    }

    // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

    /**
     * @param array $changedAttributes
     * @throws Exception
     */
    protected function _rebuildChildren($changedAttributes)
    {
        $path = isset($changedAttributes[$this->keyNamePath]) ? $changedAttributes[$this->keyNamePath] : $this->owner->getAttribute($this->keyNamePath);
        $update = [];
        $condition = [$this->keyNamePath => $path .'%'];
        if ($this->keyNameGroup !== null) {
            $group = isset($changedAttributes[$this->keyNameGroup]) ? $changedAttributes[$this->keyNameGroup] : $this->owner->getAttribute($this->keyNameGroup);
            $condition[$this->keyNameGroup] = $group;
        }
        $params = [];

        if (isset($changedAttributes[$this->keyNamePath])) {
            $substringExpr = $this->substringExpression(
                "$this->keyNamePath",
                'array_length(:pathOld) + 1',
                "array_length($this->keyNamePath) - array_length(:pathOld)"
            );
            $update[$this->keyNamePath] = new Expression($this->concatExpression([':pathNew', $substringExpr]));
            $params[':pathOld'] = $path;
            $params[':pathNew'] = $this->owner->getAttribute($this->keyNamePath);
        }

        if ($this->keyNameGroup !== null && isset($changedAttributes[$this->keyNameGroup])) {
            $update[$this->keyNameGroup] = $this->owner->getAttribute($this->keyNameGroup);
        }

        if ($this->keyNameDepth !== null && isset($changedAttributes[$this->keyNameDepth])) {
            $delta = $this->owner->getAttribute($this->keyNameDepth) - $changedAttributes[$this->keyNameDepth];
            $update[$this->keyNameDepth] = new Expression("$this->keyNameDepth" . sprintf('%+d', $delta));
        }
        if (!empty($update)) {
            $this->owner->updateAll($update, $condition, $params);
        }
    }

    /**
     * @param string $path
     * @param string $delimiter
     * @param bool $asArray = false
     * @return null|string|array
     */
    protected static function getParentPathInternal($path, $asArray = false)
    {
        $path = explode(',', $path);
        array_pop($path);
        if ($asArray) {
            return $path;
        }
        return count($path) > 0 ? implode(',', $path) : null;
    }

    protected function toLike($path) {
        return strtr($path . ',', ['%' => '\%', '_' => '\_', '\\' => '\\\\']) . '%';
    }

    protected function concatExpression($items)
    {
        if ($this->owner->getDb()->driverName === 'sqlite' || $this->owner->getDb()->driverName === 'pgsql') {
            return implode(' || ', $items);
        }
        return 'CONCAT(' . implode(',', $items) . ')';
    }

    protected function substringExpression($string, $from, $length)
    {
        if ($this->owner->getDb()->driverName === 'sqlite') {
            return "SUBSTR({$string}, {$from}, {$length})";
        }
        return "SUBSTRING({$string}, {$from}, {$length})";
    }

    // =======================================================
    public function afterInsert() {
        $this->withSave();
        $this->owner->updateAttributes([$this->keyNamePath => $this->owner->getAttribute($this->keyNamePath), $this->keyNameDepth => $this->owner->getAttribute($this->keyNameDepth)]);
    }

    public function beforeUpdate()
    {
        if ($this->owner->getIsNewRecord()) {
            throw new NotSupportedException('Method "' . $this->owner->className() . '::insert" is not supported for inserting new entitys.');
        }
        $this->withSave();
    }

    public function afterFind()
    {
        if ($this->owner->getIsNewRecord()) {
            $this->owner->setAttribute($this->keyNameParentId, 0);
        }
        $this->withSave();
    }

    protected function withSave() {
        $id = $this->owner->getAttribute($this->keyNameId);
        $parent_id = $this->owner->getAttribute($this->keyNameParentId);

        if (is_null($parent_id)) {
            $parent_id = 0;
        }

        // check parent_id value is changed!
        /*if ($this->owner->getOldAttribute($this->keyNameParentId) == $parent_id) {
            return;
        }*/

        // rebuild parents entities
        if ($parent_id == 0) {
            $depth = 0;
            $path = [intval($id)];
        } else {
            $parents = $this->getParentsAL();
            $path = [];
            $depth = 0;
            foreach ($parents as $entity) {
                $path[] = $entity->getAttribute($this->keyNameId);
                $depth++;
            }
            $path[] = intval($id);
        }

        $path = implode(',', $path);

        // rebuild children entities (recurcive)
//        $this->_rebuildChildren([
//            $this->keyNamePath => $path
//        ]);

        $this->owner->setAttribute($this->keyNamePath, $path);
//        $this->owner->setAttribute($this->keyNamePath, $path);
        $this->owner->setAttribute($this->keyNameDepth, $depth);
    }

    public function _recursiveRebuildChildren() {
        $children = $this->getChildrenAL()->all();
        $root_path = explode(',', $this->owner->getAttribute($this->keyNamePath));
        $root_depth = $this->owner->getAttribute($this->keyNameDepth);

        /** @var $child ActiveRecord */
        foreach ($children as $child) {
            $path = $root_path;
            $path[] = $child->getAttribute($this->keyNameId);
            $depth = $root_depth + 1;

            $child->_recursiveRebuildChildren();
        }
    }
}