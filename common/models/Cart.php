<?php

namespace common\models;

use Yii;
use yii\base\Model;

class Cart extends Model {
	// TODO move to the model config
	public $cookieName = 'cart';

    public $status = 0;

    /** @var $items CartItem[] */
    public $items = [];

    public $summ = 0;
    public $quantity = 0;

    public function init() {
        $cookies = Yii::$app->request->cookies;
        if ( ($items = $cookies->getValue($this->cookieName, null)) !== null ) {
            foreach($items as $item_id => $quantity) {
                $productItem = ProductItemEntity::findOne($item_id);
                $this->items[$productItem->id] = new CartItem();
                $this->items[$productItem->id]->set($productItem, $quantity);
            }
            $this->updateData();
            return true;
        }
        return false;
    }

    private function updateData() {
        $this->summ = 0;
        $this->quantity = 0;
        /** @var CartItem $item */
        foreach($this->items as $item) {
            $this->summ += $item->productItem->price * $item->quantity;
            $this->quantity += $item->quantity;
        }
    }

    public function add($productItem, $quantity = 1) {
        return $this->update($productItem, $quantity, true);
    }

    public function update($productItem, $quantity, $quantity_added = false, $quantity_down = false) {
        $quantity = intval($quantity);
        if ( $quantity <= 0 || !($item_id = is_object($productItem) ? $productItem->id : intval($productItem)) ) {
            return false;
        }
        if (array_key_exists($item_id, $this->items)) {
            if (!$quantity) {
                unset($this->items[$item_id]);
            }
            if ($quantity_added) {
                $this->items[$item_id]->setquantity($this->items[$item_id]->quantity + $quantity);
            } elseif ($quantity_down) {
	            $this->items[$item_id]->setquantity($this->items[$item_id]->quantity - $quantity);
            } else {
                $this->items[$item_id]->setquantity($quantity);
            }
        } else {
            $this->items[$item_id] = new CartItem();
            $this->items[$item_id]->set($productItem, $quantity);
        }
        $this->updateData();

        $this->save();

        return true;
    }

    public function delete($productItem) {
        if ( ($productItem = is_object($productItem) ? $productItem : ProductItem::findOne($productItem)) === null ) {
            return false;
        }
        if (array_key_exists($productItem->id, $this->items)) {
            unset($this->items[$productItem->id]);
        }
        $this->updateData();

        $this->save();
    }

    public function clear() {
        $this->items = [];
        $this->updateData();

        $this->save();
    }

    private function toCookie() {
        $items = [];
        /** @var CartItem $item */
        foreach ($this->items as $item) {
            $items[$item->productItem->id] = $item->quantity;
        }
        return $items;
    }

	public function save() {
        Yii::$app->response->cookies->add(new \yii\web\Cookie([
            'name' => $this->cookieName,
            'value' => $this->toCookie(),
            'expire' => time()+86400*7 // 1 week
        ]));
    }
}