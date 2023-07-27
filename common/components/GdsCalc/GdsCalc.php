<?php

namespace common\components\GdsCalc;

use common\components\GdsCalc\models\GdsCalcModel;
use common\components\GdsCalc\models\house\GdsCalcModelHouseAbstract;
use yii\base\Component;

abstract class GdsCalc extends Component
{
    public $type_id;

    public $models = [];

    /** @var GdsCalcModelHouseAbstract */
    protected $model;

    protected $products;

    protected $product_kits;

    public function init()
    {
        parent::init();

        $this->setModels($this->models);
    }

    public function setModels($_models)
    {
        $models = [];

        foreach ($_models as $i => $model) {
            $model = \Yii::createObject($model);
            $models[$model->alias] = $model;
        }

        $this->models = $models;
    }

	public function setModel($model) {
		$this->model = $model;
	}

    public function getModels() {
        return $this->models;
    }

    /**
     * @return GdsCalcModel
     */
    public function getModel($alias = null, $set = true) {
        if ($alias === null) {
            return $this->model;
        }
        $model = isset($this->models[$alias]) ? $this->models[$alias] : null;
        if ($set) {
            $this->setModel($model);
        }
        
        return $model;
    }

    public function getForm($model_alias = null) {

    }

    /**
     * @return array[]
     */
    abstract public function getProductKits($systemValue, $params = null, $calc_data = null);

    /**
     * Расчитывает количество материала и возвращает его количество
     * @param $systemValue string
     * @return mixed
     */
    abstract public function getCalcData($systemValue);
}
