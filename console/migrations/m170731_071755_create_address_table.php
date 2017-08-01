<?php

use yii\db\Migration;

/**
 * Handles the creation of table `address`.
 */
class m170731_071755_create_address_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('address', [
            'id' => $this->primaryKey(),
            'name'=>$this->string()->comment('收件人名称'),
            'city'=>$this->string()->comment('省-市-县'),
            'address'=>$this->string()->comment('收货地址'),
            'member_id'=>$this->integer()->comment('用户ID'),
            'tel'=>$this->integer()->comment('联系电话')
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('address');
    }
}
