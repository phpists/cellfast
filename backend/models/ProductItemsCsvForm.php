<?php

namespace backend\models;

use noIT\core\helpers\AdminHelper;
use Yii;
use yii\base\Exception;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Article;

class ProductItemsCsvForm extends Model
{
    public $data;

    public $product_id;

    public $delimiter;

    public $delimiters = [
        '	' => 'Табуляция',
        ';' => 'Точка с запятой',
        ',' => 'Запятая',
    ];

    protected $product;

    public function init()
    {
        parent::init();

        $this->getProduct();
    }

    public function rules()
    {
        return [
            [['product_id'], 'required'],
            [['product_id'], 'integer'],
            [['data', 'delimiter'], 'string'],
        ];
    }

    /**
     * @return Product
     * @throws Exception
     */
    protected function getProduct() {
        if (null === $this->product) {
            if ( !($this->product = Product::findOne($this->product_id)) ) {
                throw new Exception("The product diws not set or exist.");
            }
        }
        return $this->product;
    }

    public function getFormat() {
        static $columns;
        if (null === $columns) {
            $columns = [];

            $columns['sku'] = Yii::t('app', 'SKU');

            foreach ($this->product->type->product_features_define as $feature) {
                $columns["feature"][$feature->product_feature_id] = $feature->product_feature->name;
            }

            $columns['price'] = Yii::t('app', 'Price');
            $name = [];
            foreach (Yii::$app->languages->languages as $language) {
                $name[AdminHelper::getLangField('name', $language)] = AdminHelper::LangsFieldLabel('name', $language);
            }
            $columns[] = Yii::t('app', 'Native Name') . '|' . implode('|', $name);
        }
        return $columns;
    }

    public function getFormatCaption() {
        $caption = [];
        foreach ($this->getFormat() as $key => $vals) {
            if (!is_array($vals)) {
                $vals = [$vals];
            }
            foreach ($vals as $val) {
                $caption[] = $val;
            }
        }
        return implode($this->delimiter, $caption);
    }

    protected function prepareRow($row) {
        return trim($row);
    }

    protected function getRowData($columnValue, $key, $id = null) {
        switch ($key) {
            case 'feature':
                $columnValue = explode("|", $columnValue);
                $feature = ProductFeature::findOne($id);
                if ( !($value = ProductFeatureValue::find()->where(["value_label" => $columnValue[0]])->one()) ) {
                    $value = new ProductFeatureValue();
                    $value->feature_id = $id;
                    $value->value = $columnValue;
                    foreach (array_values(Yii::$app->languages->languages) as $i => $language) {
                        $field = AdminHelper::getLangField('value_label', $language);
                        $value->$field = @$columnValue[($i+1)];
                    }
                    $value->save();
                    return $value->id;
                }
                break;
            case 'price':
                return floatval(str_replace([',', ' '], ['.', ''], $columnValue));
                break;
            default:
                return $columnValue;
                break;
        }
    }

    protected function parseRow($row) {
        $rowData = explode($this->delimiter, $this->prepareRow($row));
        $columns = $this->getFormat();

        $resultData = [];

        $i = 0;
        foreach ($columns as $key => $values) {
            if (!isset($rowData[$i])) {
                break;
            }
            if (is_array($values)) {
                foreach ($values as $id => $value) {
                    $resultData[$key][$id] = $this->getRowData($rowData[$i], $key, $id);
                }
            } else {
                $resultData[$key] = $this->getRowData($rowData[$i], $key);
            }
            $i++;
        }
        return $resultData;
    }

    public function run() {
        $product = $this->getProduct();

        $rows = explode("\r\n", $this->data);

        foreach ($rows as $row) {
            if ( !($data = $this->parseRow($row)) ) {
                continue;
            }

            // Create and save product item
//            $itemModel = new ProductItem(['product_id' => $product->id]);
//            $itemModel->load($data);
//            $itemModel->save();
        }

        var_dump($data);
        exit;

        return true;
    }
}
