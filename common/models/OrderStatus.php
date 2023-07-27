<?php
namespace common\models;

use common\behaviors\SlugBehavior;
use common\helpers\AdminHelper;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%order_status}}".
 *
 * @property integer $id
 * @property string $native_name
 * @property integer $cancel
 * @property integer $accept
 * @property integer $success
 * @property string $e1c_slug
 * @property integer $sort_order
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property Order[] $orders
 */

class OrderStatus extends ActiveRecord {
    const STATUS_DISABLE = 0;
    const STATUS_ACTIVE = 10;

	/**
	 * Related models
	 */
	public $orderModelClass = 'common\models\Order';

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%order_status}}';
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
			[['native_name'], 'required'],
			[['cancel', 'accept', 'success', 'sort_order', 'created_at', 'updated_at'], 'integer'],
			[['sort_order'], 'default', 'value' => 0],
			[['native_name'], 'string', 'max' => 150],
			[['e1c_slug'], 'string', 'max' => 20],
		];
	}

	public static function find() {
		return parent::find()->orderBy(['sort_order' => SORT_ASC]);
	}
}