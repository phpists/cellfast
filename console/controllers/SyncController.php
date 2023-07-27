<?php

namespace console\controllers;

use common\models\PriceType;
use common\models\ProductItem;
use common\models\ProductPrice;
use common\models\soap\E1cGood;
use common\models\soap\E1cTypeOfPrice;
use yii\console\Controller;

class SyncController extends Controller {



    /**
     * # php ./yii sync/products
     */
    public function actionProducts() {
        $query = ProductItem::find()->innerJoinWith('e1cGood');

        if (!$query->count()) {
            $this->stdout("Binding catalog is empty\n");
            return;
        }

        $this->stdout("Total: ". $query->count() ."\n");

        $priceTypes = [];

        foreach (PriceType::find()->innerJoinWith('e1cEntity')
            ->where([E1cTypeOfPrice::tableName() .'.exclude_binding' => 0])
            ->select([E1cTypeOfPrice::tableName() .'.id as e1c_id', PriceType::tableName() .'.id'])
            ->asArray()
            ->all() as $priceType) {
            $priceTypes[$priceType['e1c_id']] = $priceType['id'];
        }

        /** @var ProductItem $productItem */
        foreach ($query->all() as $productItem) {
            /** @var E1cGood $e1cGood */
            $e1cGood = $productItem->e1cGood;
            $e1cGoodPrices = $e1cGood->getE1cPrices(array_keys($priceTypes))->all();
            $ucgfea = $e1cGood->e1cCodeUcgfea;

            $productPrices = [];
            foreach ($productItem->prices as $price) {
                $productPrices[$price->price_type_id] = $price;
            }

            foreach ($e1cGoodPrices as $e1cGoodPrice) {
                if (!isset($priceTypes[$e1cGoodPrice->type_of_price_id])) {
                    continue;
                }
                $price_type_id = $priceTypes[$e1cGoodPrice->type_of_price_id];

                $model = isset($productPrices[$price_type_id])
                    ? $productPrices[$price_type_id]
                    : new ProductPrice([
                        'product_item_id' => $productItem->id,
                        'price_type_id' => $price_type_id,
                        'common_price' => null,
                    ]);

                $model->price = $e1cGoodPrice->value;

                if (!$model->save()) {
                    $this->stderr("Product-price of '{$productItem->native_name}' not saved\n");
                }
            }

            if (null !== $e1cGood->e1cCodeUcgfea) {
                $productItem->ucgfea = $e1cGood->e1cCodeUcgfea->name;
                $productItem->save(false, ['ucgfea']);
            }
        }
    }


    /**
     * # php ./yii sync/ucgfea
     */
    public function actionUcgfea() {
        $query = ProductItem::find()->innerJoinWith('e1cGood');

        if (!$query->count()) {
            $this->stdout("Binding catalog is empty\n");
            return;
        }

        $this->stdout("Total: ". $query->count() ."\n");

        $priceTypes = [];

        foreach (PriceType::find()->innerJoinWith('e1cEntity')
            ->where([E1cTypeOfPrice::tableName() .'.exclude_binding' => 0])
            ->select([E1cTypeOfPrice::tableName() .'.id as e1c_id', PriceType::tableName() .'.id'])
            ->asArray()
            ->all() as $priceType) {
            $priceTypes[$priceType['e1c_id']] = $priceType['id'];
        }

        /** @var ProductItem $productItem */
        foreach ($query->all() as $productItem) {
            /** @var E1cGood $e1cGood */
            $e1cGood = $productItem->e1cGood;
            $e1cGoodPrices = $e1cGood->getE1cPrices(array_keys($priceTypes))->all();

            $productPrices = [];
            foreach ($productItem->prices as $price) {
                $productPrices[$price->price_type_id] = $price;
            }

            foreach ($e1cGoodPrices as $e1cGoodPrice) {
                if (!isset($priceTypes[$e1cGoodPrice->type_of_price_id])) {
                    continue;
                }
                $price_type_id = $priceTypes[$e1cGoodPrice->type_of_price_id];

                $model = isset($productPrices[$price_type_id])
                    ? $productPrices[$price_type_id]
                    : new ProductPrice([
                        'product_item_id' => $productItem->id,
                        'price_type_id' => $price_type_id,
                        'common_price' => null,
                    ]);

                $model->price = $e1cGoodPrice->value;

                if (!$model->save()) {
                    $this->stderr("Product-price of '{$productItem->native_name}' not saved\n");
                }
            }
        }
    }
}