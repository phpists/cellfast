<?php
namespace backend\models;

class ProductType extends \common\models\ProductType {
    protected $productModelClass = 'backend\models\Product';
    protected $itemModelClass = 'backend\models\ProductItem';
}