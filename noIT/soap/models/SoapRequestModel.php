<?php

namespace noIT\soap\models;

use noIT\soap\SoapServerModule as SOAP;
use yii\base\Model;
use yii\helpers\Inflector;

class SoapRequestModel extends Model
{
    const SCENARIO_START_SYNC = 'startSync';
    const SCENARIO_SAVE_DATA = 'saveData';
    const SCENARIO_GET_DATA = 'getData';
    /** @var  string */
    public $entityName;
    /** @var  array */
    public $rows;
    /** @var  boolean */
    public $needToTruncateTable;
    /** @var  \DateTime */
    public $timePoint;

    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios[self::SCENARIO_START_SYNC] = ['timePoint', 'rows'];
        $scenarios[self::SCENARIO_SAVE_DATA] = ['entityName', 'rows', 'needToTruncateTable'];
        $scenarios[self::SCENARIO_GET_DATA] = ['entityName', 'rows'];
        return $scenarios;
    }

    public function modelExist($attribute)
    {
        if (!class_exists($this->$attribute)) {
            $this->addError($attribute, "Class {$this->$attribute} doesn't exist");
        };
    }

    public function rules()
    {
        return [
            [['rows'], 'required'],
            [['timePoint'], 'required', 'on' => self::SCENARIO_START_SYNC],
            [['entityName'], 'required', 'on' => self::SCENARIO_SAVE_DATA],
            ['entityName', 'modelExist', 'on' => self::SCENARIO_SAVE_DATA],
            [['models', 'needToTruncateTable'], 'required', 'on' => self::SCENARIO_SAVE_DATA],
            ['needToTruncateTable', 'boolean', 'on' => self::SCENARIO_SAVE_DATA],
        ];
    }

    /**
     * @param array $data
     * @param null $formName
     * @return Model
     */
    public function load($data, $formName = null)
    {
        if ($this->getScenario() === self::SCENARIO_SAVE_DATA) {
            $dataEntity = end($data);
            $key = key($data);
            if (isset(SOAP::ENTITY_NAMES_MAP[$key])) {
                $this->entityName = 'common\\models\\soap\\' . Inflector::camelize(SOAP::ENTITY_NAMES_MAP[$key]);
            }
            $this->needToTruncateTable = SoapE1cModel::convertToBoolean(
                'full_dump', $data['header']['full_dump'], false);
            $e1cTimestamp = $data['header']['date'];
            array_walk(
                $dataEntity['row'],
                function (&$item, $key, $timestamp){
                    $item['timestamp'] = $timestamp;
                },
                $e1cTimestamp);
            $this->rows = $dataEntity['row'];
        }
        elseif ($this->getScenario() === self::SCENARIO_START_SYNC) {
            $e1cTimestamp = $data['header']['date'];
            $this->timePoint = SoapE1cModel::convertToTimestamp(
                'date', $e1cTimestamp, false);
            array_walk(
                $data['bundle']['row'],
                function (&$item, $key, $timestamp){
                    $item['timestamp'] = $timestamp;
                },
                $e1cTimestamp);
            $this->rows = $data['bundle']['row'];
        }
        return $this;
    }

    public function save()
    {
        return true;
    }
}