<?php
namespace backend\models;

use common\models\ProductItem;
use common\models\ProductSearch;
use Yii;
use yii\base\Model;
use yii\web\UploadedFile;

class ImportForm extends Model {
    public $file;

    public $delimiter = ';';

    public $result = [];

    public function rules()
    {
        return [
            [
                ['file'],
                'file',
                'skipOnEmpty' => false,
                'extensions' => 'csv',
                "checkExtensionByMimeType" => false
            ],
            [
                'delimiter', 'string'
            ],
        ];
    }

    public function upload(){
        $this->file = UploadedFile::getInstance($this, 'file');

        if(!$this->validate()) {
            return false;
        }

        return true;
    }

    public function save()
    {
        $file = fopen($this->file->tempName, 'r');

        ini_set("auto_detect_line_endings", true);

        while (!feof($file)) {

            if (!($row = fgets($file))) {
                $this->result[] = 'Пустая строка';
                continue;
            }

            list($sku, $price) = explode($this->delimiter, $row);

            $sku = trim($sku);

            $price = floatval(str_replace(',', '.', $price));

            if (!$price) {
                continue;
            }

            if ( !($productItem = self::getProductItem($sku)) ) {
                $this->result[] = "Товар с артикулом {$sku} не найден";

                continue;
            }

            $productItem->saveDefaultPrice($price);

            $this->result[] = "Товар '{$productItem->native_name}' [{$sku}] обновлен";
        }

        fclose($file);
    }

    /**
     * @param $sku
     * @return null|ProductItem
     */
    private static function getProductItem($sku)
    {
        return ProductItem::find()->innerJoinWith('product')->where(['sku' => trim($sku)])->one();
    }
}
