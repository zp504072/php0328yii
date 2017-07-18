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
            'name'=>$this->string(50)->comment('����'),
            'intro'=>$this->text()->comment('���'),
            'logo'=>$this->string(255)->comment('ͼƬ'),
            'sort'=>$this->integer(11)->comment('����'),
            'status'=>$this->smallInteger(2)->comment('״̬')

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
