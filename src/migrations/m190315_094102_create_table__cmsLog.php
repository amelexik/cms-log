<?php

use yii\db\Migration;

/**
 * Class m190315_094102_create_table__cmsLog
 */
class m190315_094102_create_table__cmsLog extends Migration
{

    const TABLE = 'cms_log';

    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%' . self::TABLE . '}}', [
            'id'             => $this->bigPrimaryKey(),
            'model_class'    => $this->string(500)->notNull(),
            'pk'             => $this->string()->notNull(),
            'operation_type' => $this->smallInteger(1)->notNull() . ' COMMENT \'1-insert, 2-update, 3-delete\'',
            'created_at'     => $this->integer(10)->unsigned()->notNull(),
            'name'           => $this->string(150)->notNull(),
            'user_id'        => $this->integer(),
            'user_name'      => $this->string()->notNull(),
            'user_ip'        => $this->string(50),
            'user_agent'     => $this->string(300),
            'data'           => $this->text()
        ], $tableOptions);
        $this->createIndex(self::TABLE . '_model_class_index', '{{%' . self::TABLE . '}}', 'model_class');
        $this->addForeignKey(
            self::TABLE . 'user_id', '{{%' . self::TABLE . '}}',
            'user_id', '{{%cms_user}}', 'id', 'SET NULL', 'SET NULL'
        );
    }

    public function down()
    {
        $this->dropTable('{{%' . self::TABLE . '}}');
    }
}
