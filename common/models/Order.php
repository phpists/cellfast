<?php
namespace common\models;

use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use Yii;

/**
 * This is the model class for table "{{%order}}".
 *
 * @property integer $id
 * @property integer $status_id
 * @property integer $user_id
 * @property integer $warehouse_id
 * @property integer $delivery_id
 * @property integer $payment_id
 * @property string $number
 * @property string $token
 * @property integer $is_quick
 * @property string $order_comment
 * @property string $delivery_comment
 * @property string $delivery_data
 * @property double $delivery_cost
 * @property string $payment_comment
 * @property string $payment_data
 * @property double $payment_cost
 * @property double $discount_abs
 * @property double $discount_percent
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property OrderStatus $status
 * @property User $user
 * @property Warehouse $warehouse
 * @property Delivery $delivery
 * @property Payment $payment
 */
class Order extends ActiveRecord {
    const STATUS_DISABLE = 0;
    const STATUS_ACTIVE = 10;

	/**
	 * Related models
	 */
	public $orderStatusModelClass = 'common\models\OrderStatus';
	public $orderProductModelClass = 'common\models\OrderProduct';
	public $warehouseModelClass = 'common\models\Warehouse';
	public $userModelClass = 'common\models\User';

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%order}}';
    }

	/**
	 * @inheritdoc
	 */
	public function behaviors()
	{
		$behaviors = [
			'timestamp' => [
				'class' => TimestampBehavior::className(),
			],
		];

		return $behaviors;
	}

	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['user_id'], 'required'],
			[['status_id', 'user_id', 'warehouse_id', 'delivery_id', 'payment_id', 'is_quick', 'created_at', 'updated_at'], 'integer'],
			[['order_comment', 'delivery_comment', 'delivery_data', 'payment_comment', 'payment_data'], 'string'],
			[['delivery_cost', 'payment_cost', 'discount_abs', 'discount_percent'], 'number'],
			[['number'], 'string', 'max' => 50],
			[['token'], 'string', 'max' => 64],
			[['token'], 'unique'],
			[['status_id'], 'exist', 'skipOnError' => true, 'targetClass' => OrderStatus::className(), 'targetAttribute' => ['status_id' => 'id']],
			[['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
			[['warehouse_id'], 'exist', 'skipOnError' => true, 'targetClass' => Warehouse::className(), 'targetAttribute' => ['warehouse_id' => 'id']],
		];
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getStatus()
	{
		return $this->hasOne($this->orderStatusModelClass, ['id' => 'status_id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getUser()
	{
		return $this->hasOne($this->userModelClass, ['id' => 'user_id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getDelivery()
	{
		return $this->hasOne(Delivery::className(), ['id' => 'delivery_id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getWarehouse()
	{
		return $this->hasOne($this->warehouseModelClass, ['id' => 'warehouse_id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getPayment()
	{
		return $this->hasOne(Payment::className(), ['id' => 'payment_id']);
	}

	public function beforeSave( $insert ) {
		if ( null !== $this->status_id && empty($this->number) ) {
			// Set number if not cart
			$this->number = $this->id;
		}

		return parent::beforeSave( $insert );
	}
}