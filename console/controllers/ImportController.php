<?php

namespace console\controllers;

use Yii;
use common\models\Option;
use common\models\Product;
use yii\console\Controller;
use common\models\ProductGroup;
use common\models\ProductOption;
use common\models\ProductSearch;

class ImportController extends Controller {
    public function actionProducts($file, $category_id) {
        if ( !$file || ($handle = fopen($file, "r")) === false ) {
            $this->stderr("File '$file' not found\r\n");
        }

        $product_options = [
            1 => null,
            2 => null,
        ];

        while ( (list(
            $product_sku,
            $product_group_native_name,
            $product_name,
            $product_options[2],
            $product_options[1]
            ) = fgetcsv($handle, null, ';')) !== false ) {
            if ( !($product_sku = trim($product_sku)) ) {
                $this->stderr("Empty SKU\r\n");
                continue;
            }

            if ( !($product = ProductSearch::findBySku($product_sku)) ) {
                $product = new Product();
            }

            if ( !($product_group = ProductGroup::find()->where(['native_name' => $product_group_native_name, 'category_id' => $category_id])->one()) ) {
                $product_group = new ProductGroup([
                    'native_name' => $product_group_native_name,
                    'category_id' => $category_id,
                ]);
                $product_group->save();
            }

            $product->sku = $product_sku;
            $product->group_id = $product_group->id;
            $product->name_ru_ru = $product_name;
            $product->name_type = 'suffix';
            $product->save();

            $options = $product->feature_options;

            foreach ($product_options as $id => $value) {
                $value = trim($value);
                if (!$value || $value == '-') {
                    continue;
                }
                if ( !($option = Option::find()->where(['feature_id' => $id, 'value' => $value])->one()) ) {
                    $option = new Option([
                        'feature_id' => $id,
                        'value' => $value,
                    ]);
                    $option->save();
                }
                if (!isset($options[$id])) {
                    $p_option = new ProductOption([
                        'product_id' => $product->id,
                        'option_id' => $option->id,
                    ]);
                    $p_option->save();
                }
            }
        }


    }
}