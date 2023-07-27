<?php
namespace backend\models;

use yii\base\Model;

class E1cGoodBindingDialog extends Model
{
    public $ids;

    public $product_type;

    public function rules()
    {
        return [
            [['ids'], 'safe'],
            [['product_type_id'], 'integer'],
        ];
    }
}