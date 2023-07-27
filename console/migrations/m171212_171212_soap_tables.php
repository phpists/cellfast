<?php

use yii\db\Migration;
use \yii\console\Exception;
use common\helpers\AdminHelper;

class m171212_171212_soap_tables extends Migration
{
    const CATALOG_TYPE = 0;
    const INFORMATION_REGISTER_TYPE = 1;
    const DOCUMENT_TYPE = 10;

    const GUID_TYPE = "BINARY(16) NOT NULL";

    /**
     * @var array
     */
    protected $tablesOptions = [];

    /**
     * @var array
     */
    protected $foreignKeys = [];

    private function makeFK(string $table, string $column, string $refTable, string $refColumn = "guid") {
        return [
            'table' => $this->tablesOptions[$table]['yii_name'],
            'column' => $column,
            'ref_table' => $this->tablesOptions[$refTable]['yii_name'],
            'ref_column' => $refColumn
        ];
    }

    public function __construct(array $config = [])
    {
        parent::__construct($config);

        /**
         * 'guid' => $this->binary(16)->unique(),  такая конструкция превращает в BLOB без ограничения по длине
         * 'guid' => $this->binary(16)->primaryKey() такая конструкция фреймворком не предусмотрена вообще
         * yii2 convert binary to blob (as for me stupid idea)
         * 'group' => "binary(16) NOT NULL",
         * that's why use binary(16) lifehack
         */

        $this->tablesOptions = [
            'e1c_group_of_good' => [
                'yii_name' => "{{%e1c_group_of_good}}",
                'e1c_name' => "groupsOfGoods",
                'type' => self::CATALOG_TYPE,
                'columns' => [
                    'name' => $this->string(50)
                ]
            ],
            'e1c_code_ucgfea' => [
                'yii_name' => "{{%e1c_code_ucgfea}}",
                'e1c_name' => "codesUCGFEA",
                'type' => self::CATALOG_TYPE,
                'columns' => [
                    'name' => $this->string(50)
                ]
            ],
            'e1c_good' => [
                'yii_name' => "{{%e1c_good}}",
                'e1c_name' => "goods",
                'type' => self::CATALOG_TYPE,
                'columns' => [
                    'name' => $this->string(150),
                    'name_full' => $this->string(250),
                    'name_polish' => $this->string(250),
                    'unit_of_measure' => $this->string(50),
                    'packing_ratio' => $this->smallInteger(5)->unsigned(),
                    'pallet_ratio' => $this->integer(10)->unsigned(),
                    'weight' => $this->decimal(10,3)->unsigned(),
                    'volume' => $this->decimal(15,5)->unsigned(),
                    'oversized' => $this->boolean(),
                    'random_angle' => $this->boolean(),
                    'group_of_good_id' => $this->integer()->unsigned(),
                    "`group_of_good` ".self::GUID_TYPE,
                    'code_vendor' => $this->string(9),
                    'code_ucgfea_id' => $this->integer()->unsigned(),
                    "`code_ucgfea` ".self::GUID_TYPE,
                    'picture' => "MEDIUMBLOB NOT NULL"
                ]
            ],
            'e1c_warehouse' => [
                'yii_name' => "{{%e1c_warehouse}}",
                'e1c_name' => "warehouses",
                'type' => self::CATALOG_TYPE,
                'columns' => [
                    'name' => $this->string(150),
                    'address' => $this->string(250),
                ]
            ],
            'e1c_availability_of_good' => [
                'yii_name' => "{{%e1c_availability_of_good}}",
                'e1c_name' => "availabilityOfGoods",
                'type' => self::INFORMATION_REGISTER_TYPE,
                'columns' => [
                    'good_id' => $this->integer()->unsigned(),
                    "`good` ".self::GUID_TYPE,
                    'angle' => $this->decimal(3, 0)->unsigned(),
                    'warehouse_id' => $this->integer()->unsigned(),
                    "`warehouse` ".self::GUID_TYPE,
                    'status' => "ENUM('in stock', 'ends', 'unavailable', 'coming', 'pre-order') NOT NULL"
                ],
                'pk_columns' => ["good_id", "angle", "warehouse_id"]
            ],
            'e1c_type_of_price' => [
                'yii_name' => "{{%e1c_type_of_price}}",
                'e1c_name' => "typesOfPrices",
                'type' => self::CATALOG_TYPE,
                'columns' => [
                    'name' => $this->string(150),
                    'include_vat' => $this->boolean()
                ]
            ],
            'e1c_price' => [
                'yii_name' => "{{%e1c_price}}",
                'e1c_name' => "prices",
                'type' => self::INFORMATION_REGISTER_TYPE,
                'columns' => [
                    'good_id' => $this->integer()->unsigned(),
                    "`good` ".self::GUID_TYPE,
                    'type_of_price_id' => $this->integer()->unsigned(),
                    "`type_of_price` ".self::GUID_TYPE,
                    'value' => $this->decimal(15, 2)->unsigned()
                ],
                'pk_columns' => ["good_id", "type_of_price_id"]
            ],
            'e1c_client' => [
                'yii_name' => "{{%e1c_client}}",
                'e1c_name' => "clients",
                'type' => self::CATALOG_TYPE,
                'columns' => [
                    "`superior_id` INT(10) UNSIGNED",
                    "`superior` BINARY(16)",
                    'name' => $this->string(150),
                    'code_itn' => $this->string(12),
                    'address' => $this->string(250)
                ]
            ],
            'e1c_agreement' => [
                'yii_name' => "{{%e1c_agreement}}",
                'e1c_name' => "agreements",
                'type' => self::CATALOG_TYPE,
                'columns' => [
                    'client_id' => $this->integer()->unsigned(),
                    "`client` ".self::GUID_TYPE,
                    'name' => $this->string(50),
                    'valid_to' => $this->date()
                ]
            ],
            'e1c_requisite_of_agreement' => [
                'yii_name' => "{{%e1c_requisite_of_agreement}}",
                'e1c_name' => "requisitesOfAgreements",
                'type' => self::INFORMATION_REGISTER_TYPE,
                'columns' => [
                    'agreement_id' => $this->integer()->unsigned(),
                    "`agreement` ".self::GUID_TYPE,
                    'group_of_good_id' => $this->integer()->unsigned(),
                    "`group_of_good` ".self::GUID_TYPE,
                    'type_of_price_id' => $this->integer()->unsigned(),
                    "`type_of_price` ".self::GUID_TYPE
                ],
                'pk_columns' => ["agreement_id", "group_of_good_id"]
            ],
            'e1c_head_of_order' => [
                'yii_name' => "{{%e1c_head_of_order}}",
                'e1c_name' => "ordersHeads",
                'type' => self::DOCUMENT_TYPE,
                'columns' => [
                    'agreement_id' => $this->integer()->unsigned(),
                    "`agreement` ".self::GUID_TYPE,
                    'warehouse_id' => $this->integer()->unsigned(),
                    "`warehouse` ".self::GUID_TYPE,
                    'description_1c' => $this->string(100),
                    'self_delivery' => $this->boolean(),
                    'info_delivery' => $this->text(),
                    'status' => "ENUM('created', 'accepted', 'processed', 'ready for shipment', 
                                      'partially shipped', 'shipped', 'rejected') NOT NULL"
                ]
            ],
            'e1c_good_of_order' => [
                'yii_name' => "{{%e1c_good_of_order}}",
                'e1c_name' => "ordersGoods",
                'type' => self::INFORMATION_REGISTER_TYPE,
                'columns' => [
                    'order_head_id' => $this->integer()->unsigned(),
                    "`order_head` ".self::GUID_TYPE,
                    'good_id' => $this->integer()->unsigned(),
                    "`good` ".self::GUID_TYPE,
                    'angle' => $this->decimal(3, 0)->unsigned(),
                    'quantity' => $this->decimal(15, 3)->unsigned(),
                    'sum' => $this->decimal(15, 2)->unsigned()
                ],
                'pk_columns' => ["order_head_id", "good_id", "angle"]
            ],
            'e1c_comment_of_order' => [
                'yii_name' => "{{%e1c_comment_of_order}}",
                'e1c_name' => "commentsOfOrders",
                'type' => self::INFORMATION_REGISTER_TYPE,
                'columns' => [
                    'id' => $this->integer()->unsigned(),
                    'order_head_id' => $this->integer()->unsigned(),
                    "`order_head` ".self::GUID_TYPE,
                    'user' => $this->string(50),
                    'comment' => $this->text()
                ],
                'pk_columns' => ["id"]
            ],
            'e1c_print_form_of_order' => [
                'yii_name' => "{{%e1c_print_form_of_order}}",
                'e1c_name' => "printFormsOfOrders",
                'type' => self::INFORMATION_REGISTER_TYPE,
                'columns' => [
                    'order_head_id' => $this->integer()->unsigned(),
                    "`order_head` ".self::GUID_TYPE,
                    "`part_number` ".self::GUID_TYPE,
                    'part_description' => $this->string(50),
                    'part_type' => "ENUM('proforma', 'invoice') NOT NULL",
                    'part_print_form_eds' => "MEDIUMBLOB NOT NULL"
                ],
                'pk_columns' => ["order_head_id", "part_number"]
            ],
            'e1c_receivable' => [
                'yii_name' => "{{%e1c_receivable}}",
                'e1c_name' => "receivables",
                'type' => self::INFORMATION_REGISTER_TYPE,
                'columns' => [
                    "`id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY",
                    'client_id' => $this->integer()->unsigned(),
                    "`client` ".self::GUID_TYPE,
                    'agreement_id' => $this->integer()->unsigned(),
                    "`agreement` ".self::GUID_TYPE,
                    "`order_head_id` INT(10) UNSIGNED",
                    "`order_head` ".self::GUID_TYPE,
                    'order_view' => $this->string(100),
                    'shipping_date' => $this->date(),
                    'invoice_view' => $this->string(100),
                    'deadline_for_payment' => $this->date(),
                    'sum_of_invoice' => $this->decimal(15, 2)->unsigned(),
                    'sum_of_debt' => $this->decimal(15, 2)
                ],
            ]
        ];

        $this->foreignKeys = [
            'e1c_good_group_of_good_fk' =>
                $this->makeFK("e1c_good", "group_of_good", "e1c_group_of_good"),
            'e1c_good_group_of_good_id_fk' =>
                $this->makeFK("e1c_good", "group_of_good_id", "e1c_group_of_good", "id"),
            'e1c_good_code__ucgfea_fk' =>
                $this->makeFK("e1c_good", "code_ucgfea", "e1c_code_ucgfea"),
            'e1c_good_code__ucgfea_id_fk' =>
                $this->makeFK("e1c_good", "code_ucgfea_id", "e1c_code_ucgfea", "id"),
            'e1c_availability_of_good_good_fk' =>
                $this->makeFK("e1c_availability_of_good", "good", "e1c_good"),
            'e1c_availability_of_good_good_id_fk' =>
                $this->makeFK("e1c_availability_of_good", "good_id", "e1c_good", "id"),
            'e1c_availability_of_good_warehouse_fk' =>
                $this->makeFK("e1c_availability_of_good", "warehouse", "e1c_warehouse"),
            'e1c_availability_of_good_warehouse_id_fk' =>
                $this->makeFK("e1c_availability_of_good", "warehouse_id", "e1c_warehouse", "id"),
            'e1c_price_good_fk' =>
                $this->makeFK("e1c_price", "good", "e1c_good"),
            'e1c_price_good_id_fk' =>
                $this->makeFK("e1c_price", "good_id", "e1c_good", "id"),
            'e1c_price_type_of_price_fk' =>
                $this->makeFK("e1c_price", "type_of_price", "e1c_type_of_price"),
            'e1c_price_type_of_price_id_fk' =>
                $this->makeFK("e1c_price", "type_of_price_id", "e1c_type_of_price", "id"),
            'e1c_client_client_fk' =>
                $this->makeFK("e1c_client", "superior", "e1c_client"),
            'e1c_client_client_id_fk' =>
                $this->makeFK("e1c_client", "superior_id", "e1c_client", "id"),
            'e1c_agreement_client_fk' =>
                $this->makeFK("e1c_agreement", "client", "e1c_client"),
            'e1c_agreement_client_id_fk' =>
                $this->makeFK("e1c_agreement", "client_id", "e1c_client", "id"),
            'e1c_e1c_requisite_of_agreement_agreement_fk' =>
                $this->makeFK("e1c_requisite_of_agreement", "agreement", "e1c_agreement"),
            'e1c_e1c_requisite_of_agreement_agreement_id_fk' =>
                $this->makeFK("e1c_requisite_of_agreement", "agreement_id", "e1c_agreement", "id"),
            'e1c_e1c_requisite_of_agreement_group_of_good_fk' =>
                $this->makeFK("e1c_requisite_of_agreement", "group_of_good", "e1c_group_of_good"),
            'e1c_e1c_requisite_of_agreement_group_of_good_id_fk' =>
                $this->makeFK("e1c_requisite_of_agreement", "group_of_good_id", "e1c_group_of_good", "id"),
            'e1c_e1c_requisite_of_agreement_type_of_price_fk' =>
                $this->makeFK("e1c_requisite_of_agreement", "type_of_price", "e1c_type_of_price"),
            'e1c_e1c_requisite_of_agreement_type_of_price_id_fk' =>
                $this->makeFK("e1c_requisite_of_agreement", "type_of_price_id", "e1c_type_of_price", "id"),
            'e1c_head_of_order_agreement_fk' =>
                $this->makeFK("e1c_head_of_order", "agreement", "e1c_agreement"),
            'e1c_head_of_order_agreement_id_fk' =>
                $this->makeFK("e1c_head_of_order", "agreement_id", "e1c_agreement", "id"),
            'e1c_head_of_order_warehouse_fk' =>
                $this->makeFK("e1c_head_of_order", "warehouse", "e1c_warehouse"),
            'e1c_head_of_order_warehouse_id_fk' =>
                $this->makeFK("e1c_head_of_order", "warehouse_id", "e1c_warehouse", "id"),
            'e1c_good_of_order_head_of_order_fk' =>
                $this->makeFK("e1c_good_of_order", "order_head", "e1c_head_of_order"),
            'e1c_good_of_order_head_of_order_id_fk' =>
                $this->makeFK("e1c_good_of_order", "order_head_id", "e1c_head_of_order", "id"),
            'e1c_good_of_order_good_fk' =>
                $this->makeFK("e1c_good_of_order", "good", "e1c_good"),
            'e1c_good_of_order_good_id_fk' =>
                $this->makeFK("e1c_good_of_order", "good_id", "e1c_good", "id"),
            'e1c_comment_of_order_head_of_order_fk' =>
                $this->makeFK("e1c_comment_of_order", "order_head", "e1c_head_of_order"),
            'e1c_comment_of_order_head_of_order_id_fk' =>
                $this->makeFK("e1c_comment_of_order", "order_head_id", "e1c_head_of_order", "id"),
            'e1c_print_form_of_order_head_of_order_fk' =>
                $this->makeFK("e1c_print_form_of_order", "order_head", "e1c_head_of_order"),
            'e1c_print_form_of_order_head_of_order_id_fk' =>
                $this->makeFK("e1c_print_form_of_order", "order_head_id", "e1c_head_of_order", "id"),
            'e1c_receivable_client_fk' =>
                $this->makeFK("e1c_receivable", "client", "e1c_client"),
            'e1c_receivable_client_id_fk' =>
                $this->makeFK("e1c_receivable", "client_id", "e1c_client", "id"),
            'e1c_receivable_agreement_fk' =>
                $this->makeFK("e1c_receivable", "agreement", "e1c_agreement"),
            'e1c_receivable_agreement_id_fk' =>
                $this->makeFK("e1c_receivable", "agreement_id", "e1c_agreement", "id"),
            'e1c_receivable_head_of_order_id_fk' =>
                $this->makeFK("e1c_receivable", "order_head_id", "e1c_head_of_order", "id"),
        ];
    }

    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        foreach ($this->tablesOptions as $tableOptions) {
            if ($tableOptions['type'] == self::CATALOG_TYPE) {
                array_unshift($tableOptions['columns'], "`guid` ".self::GUID_TYPE." UNIQUE");
                $tableOptions['columns'] = ['id' => $this->primaryKey()->unsigned()] + $tableOptions['columns'];
                $tableOptions['columns']['mark_deleted'] = $this->boolean();
            }
            elseif ($tableOptions['type'] == self::DOCUMENT_TYPE) {
                array_unshift($tableOptions['columns'], "`guid` ".self::GUID_TYPE." UNIQUE");
                $tableOptions['columns'] = ['id' => $this->primaryKey()->unsigned()] + $tableOptions['columns'];
            }
            elseif ($tableOptions['type'] == self::INFORMATION_REGISTER_TYPE) {
                //nothing to change;
            }
            else {
                Yii::error("Undefined type {$tableOptions['type']}");
                throw new Exception("Undefined type {$tableOptions['type']}");
            }
            $tableOptions['columns'] += [
                'timestamp' => $this->timestamp()
                    ->defaultValue('0000-00-00 00:00:00'),
                'created_at' => $this->timestamp()
                    ->defaultExpression('CURRENT_TIMESTAMP()'),
                'updated_at' => $this->timestamp()
                    ->defaultExpression('CURRENT_TIMESTAMP()')
                    ->append('ON UPDATE CURRENT_TIMESTAMP()')
            ];
            foreach ($tableOptions['columns'] as $columnName => $columnValue) {
                if (!is_string($columnValue)) {
                    $tableOptions['columns'][$columnName] = $columnValue->notNull();
                }
            }
            $this->createTable($tableOptions['yii_name'], $tableOptions['columns'],
                AdminHelper::migrateTableOptions($this->db->driverName));
            if ($tableOptions['type'] == self::INFORMATION_REGISTER_TYPE) {
                if (isset($tableOptions['pk_columns'])) {
                    $this->addPrimaryKey("{$tableOptions['e1c_name']}_pk", $tableOptions['yii_name'], $tableOptions['pk_columns']);
                }
            }
        }
        /*foreach ($this->tablesOptions as $tableOptions) {
            if ($tableOptions['type'] == self::CATALOG_TYPE) {
                $this->createIndex("mark_deleted_ind", $tableOptions['yii_name'], "mark_deleted");
            }
        }*/
        foreach ($this->foreignKeys as $foreignKeyName => $foreignKeyOptions) {
            $this->addForeignKey($foreignKeyName,
                $foreignKeyOptions['table'], $foreignKeyOptions['column'],
                $foreignKeyOptions['ref_table'], $foreignKeyOptions['ref_column']);
        }
        $this->execute("
            CREATE FUNCTION UuidToBin(_uuid BINARY(36))
                RETURNS BINARY(16)
                LANGUAGE SQL  DETERMINISTIC  CONTAINS SQL  SQL SECURITY INVOKER
            RETURN
                UNHEX(CONCAT(
                    SUBSTR(_uuid, 15, 4),
                    SUBSTR(_uuid, 10, 4),
                    SUBSTR(_uuid,  1, 8),
                    SUBSTR(_uuid, 20, 4),
                    SUBSTR(_uuid, 25) ))"
        );
        $this->execute("    
            CREATE FUNCTION UuidFromBin(_bin BINARY(16))
                RETURNS BINARY(36)
                LANGUAGE SQL  DETERMINISTIC  CONTAINS SQL  SQL SECURITY INVOKER
            RETURN
                LCASE(CONCAT_WS('-',
                    HEX(SUBSTR(_bin,  5, 4)),
                    HEX(SUBSTR(_bin,  3, 2)),
                    HEX(SUBSTR(_bin,  1, 2)),
                    HEX(SUBSTR(_bin,  9, 2)),
                    HEX(SUBSTR(_bin, 11))
                         ))"
        );
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        foreach ($this->foreignKeys as $foreignKeyName => $foreignKeyOptions) {
            $this->dropForeignKey($foreignKeyName, $foreignKeyOptions['table']);
        }
        foreach ($this->tablesOptions as $tableOptions) {
            $this->dropTable($tableOptions['yii_name']);
        }
        $this->execute("DROP FUNCTION IF EXISTS UuidFromBin");
        $this->execute("DROP FUNCTION IF EXISTS UuidToBin");
    }
}