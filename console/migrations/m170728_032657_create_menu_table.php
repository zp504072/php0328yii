<?php

use yii\db\Migration;

/**
 * Handles the creation of table `menu`.
 */
class m170728_032657_create_menu_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('menu', [
            'id' => $this->primaryKey(),
            'name'=>$this->string()->comment('名称'),
            'parent_id'=>$this->integer()->comment('父级ID'),
            'menu_url'=>$this->string()->comment('菜单路径'),
            'sort'=>$this->integer()->comment('排序')
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('menu');
    }
}
