<?php

namespace common\components\GdsCalc\models;

use common\helpers\OrderHelper;
use common\helpers\SiteHelper;
use common\models\Orders;
use common\models\Variant;
use Yii;
use yii\base\Model;

/**
 * This is the model class for table "feedback".
 *
 */
class OrderForm extends Model
{
    public $data;

    public $phone;
    public $name;
    public $email;
    public $comment_cstm;

    public function rules()
    {
        return [
            [['phone'], 'required'],
            [['email'], 'email'],
            [['phone', 'name'], 'string', 'max' => 150],
            [['comment_cstm'], 'string'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'comment_cstm' => 'Комментарий',
        ];
    }

    public function save()
    {

        if (is_string($this->data)) {
            $this->data = json_decode($this->data);
        }

        if (($variants = Variant::find()->where(['id' => array_keys($this->data['products'])])->all()) === null) {
            return false;
        }

        $orderModel = new Orders();

        $orderModel->name = $this->name;
        $orderModel->phone = $this->phone;
        $orderModel->email = $this->email;

        $items = [
            'variant_id' => [],
            'price' => [],
            'amount' => [],
        ];

        foreach ($this->data['products'] as $vid => $amount) {
            $variant = Variant::findOne($vid);
            $items['variant_id'][] = $variant->id;
            $items['price'][] = $variant->price;
            $items['amount'][] = $amount;
        }

        $orderModel->items = $items;

        $orderModel->gasession_cid = SiteHelper::getSessionID();
        $orderModel->gasession_gaid = SiteHelper::getGaId();

        $orderModel->comment = @$this->data['comment'];

        if ($orderModel->save()) {
            $orderModel->sendAdminEmail();
            $orderModel->sendClientEmail();
        }

        return true;
    }
}
