<?php

use yii\db\Migration;

/**
 * Handles the creation of table `brand`.
 */
class m170718_060410_create_brand_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('brand', [
            'id' => $this->primaryKey(),
            'name'=>$this->string(50)->comment('Ãû³Æ'),
            'intro'=>$this->text()->comment('¼ò½é'),
            'logo'=>$this->string(255)->comment('Í¼Æ¬'),
            'sort'=>$this->integer(11)->comment('ÅÅÐò'),
            'status'=>$this->smallInteger(2)->comment('×´Ì¬')

        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('brand');
    }
}
