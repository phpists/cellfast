<?php

namespace console\controllers;

use common\models\Product;
use common\models\ProductMe;
use yii\console\Controller;

class DevController extends Controller {


    /**
     * Сверяем таблицы старой и новой базы
     *
     * # php ./yii dev/check_products
     */
    public function actionProducts() {
        /** @var ProductMe[] $products_old */
        $products_old = ProductMe::find()->all();

        $index_old = null;
        foreach ($products_old as $index_old => $product_old) {
            // Ищем по времени создания в новой БД

            if ( ($product_new = Product::find()->where(['created_at' => $product_old->created_at])) ) {
                continue;
            }

            $this->stdout("$index_old => $product_old->native_name" . "\n");
        }

    }
}
