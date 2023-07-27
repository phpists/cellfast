<?php
namespace common\models;

use yii\base\Model;

class CartItem extends Model {
    /** @var $productItem ProductItem */
    public $productItem;
    /** @var $quantity integer */
    public $quantity;
    /** @var $cost float */
    public $cost;

    public function set($ProductItem, $quantity = 1) {
        $this->productItem = is_object($ProductItem) ? $ProductItem : ProductItemEntity::findOne($ProductItem);
        $this->setQuantity($quantity);
    }

    public function setQuantity($quantity) {
        $this->quantity = $quantity;
        $this->cost = $quantity * $this->productItem->price;
    }
}