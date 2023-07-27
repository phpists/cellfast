<?php

namespace common\components\GdsCalc\models;

use yii\base\Model;

abstract class GdsCalcModel extends Model
{
    public $name;

    public $alias;

    public $image;

    /**
     * @var array Массив кеширования данных
     */
    protected $_cache = [];

    protected function setCache($key, $value)
    {
        $this->_cache[$key] = $value;
    }

    protected function getCache($key, $default_value = null)
    {
        return isset($this->_cache[$key]) ? $this->_cache[$key] : $default_value;
    }

    public function calc($key, $system = null)
    {

        $cacheKey = $system ? "{$key}|{$system}" : $key;

        if (($result = $this->getCache($cacheKey)) === null) {
            $methodName = 'calc'. ucfirst($key);
            $result = $system ? $this->$methodName($system) : $this->$methodName();
            $this->setCache($cacheKey, $result);
        }
        return $result;
    }

    public function orderComment()
    {
        $result = [];

        $result[] = "{$this->name}";

        $result[] = "Рамеры:";
        $result[] = "------------------------------------";
        foreach ($this->attributeLabels() as $attribute => $label) {
            $result[] = "$label: {$this->$attribute}\n";
        }

        return implode("\n", $result);
    }
}
