<?php

use yii\db\Migration;
use common\helpers\AdminHelper;

/** TODO - noIT  - Вынести в базовую миграцию и брать данные из параметров модуля */

/**
 * Class m171111_162535_product_feature
 */
class m171111_162535_product_feature extends Migration
{
    protected $groupModelName = 'common\models\ProductFeature';
    protected $valueModelName = 'common\models\ProductFeatureValue';

    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->createTable(
            ($this->groupModelName)::tableName(),
            array_merge(
                AdminHelper::migrateTablePK($this),
                [
                    'project_id' => $this->string(20)->notNull(),
                    'native_name' => $this->string(100)->notNull(),
                    'slug' => $this->string(255)->notNull(),
                    'image' => $this->string(255),
                ],
                AdminHelper::migrateTableLangs('name', $this->string('150')->notNull()),
                AdminHelper::migrateTableLangs('caption', $this->string('150')),
                [
                    /**
                     * TRUE - свойство сквозное, общее для всех типов товаров и не требует привязки к отдельным типам (например, организованная через свойства, категория товаров)
                     */
                    'overall' => $this->boolean()->defaultValue(false),
                    /**
                     * Тип данных, определяет поле в таблице feature со значениями.
                     * Принимает значение:
                     * - str (по-умолчанию)
                     * - int
                     * - flt
                     * - txt
                     * - obj
                     */
                    'value_type' => $this->string(3)->defaultValue('str'),
                    /**
                     * Указывает возможность множественной привязки значений к целевой сущности (товару)
                     */
                    'multiple' => $this->boolean()->defaultValue(false),
                    /**
                     * Указание виджета (namespace) для админки
                     */
                    'admin_widget' => $this->string('255'),
                    /**
                     * Указание виджета (namespace) для фронтенда
                     */
                    'filter_widget' => $this->string('255'),

                ],
                AdminHelper::migrateTableStatus($this, ($this->groupModelName)::STATUS_ACTIVE),
                AdminHelper::migrateTableTS($this)
            ),
            AdminHelper::migrateTableOptions($this->db->driverName)
        );

        $this->createTable(
            ($this->valueModelName)::tableName(),
            array_merge(
                AdminHelper::migrateTablePK($this),
                [
                    'feature_id' => $this->integer()->notNull(),
                ],
                AdminHelper::migrateTableLangs('value_label', $this->string('150')->notNull()),
                [
                    'value_str' => $this->string(150),
                    'value_txt' => $this->text(),
                    'value_int' => $this->integer(),
                    'value_flt' => $this->float(),
                    'value_obj' => $this->text(), // LONGTEXT
                    'slug' => $this->string(255),
                ],
                [
                    AdminHelper::FIELDNAME_SORT => $this->integer()->defaultValue(0),
                ],
                AdminHelper::migrateTableTS($this)
            ),
            AdminHelper::migrateTableOptions($this->db->driverName)
        );
        
        $this->createIndex('product_feature_value_feature_id_index', ($this->valueModelName)::tableName(), 'feature_id');
        $this->addForeignKey('product_feature_value_feature_id_fk', ($this->valueModelName)::tableName(), 'feature_id', ($this->groupModelName)::tableName(), 'id', 'CASCADE', 'CASCADE');
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropForeignKey('product_feature_value_feature_id_fk', ($this->valueModelName)::tableName());
        $this->dropIndex('product_feature_value_feature_id_index', ($this->valueModelName)::tableName());
        $this->dropTable(($this->valueModelName)::tableName());
        $this->dropTable(($this->groupModelName)::tableName());
    }
}
