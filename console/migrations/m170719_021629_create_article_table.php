<?php

use yii\db\Migration;

/**
 * Handles the creation of table `article`.
 */
class m170719_021629_create_article_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('article', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->comment('名称'),
            'intro' => $this->string(50)->comment('简介'),

            'article_category_id'=>$this->integer()->comment('文章分类id'),
            'sort'=>$this->integer(11)->comment('排序'),
            'status'=>$this->smallInteger(2)->comment('添加时间'),
            'create_time'=>$this->integer(11)->comment('创建时间')

        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('article');
    }
}
