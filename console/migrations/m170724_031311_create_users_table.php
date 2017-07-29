<?php

use yii\db\Migration;

/**
 * Handles the creation of table `users`.
 */
class m170724_031311_create_users_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('users', [
            'id' => $this->primaryKey(),
            'username'=>$this->string(20)->comment('账号'),
            'password'=>$this->string(20)->comment('密码'),
            'last_login_time'=>$this->integer()->comment('最后登陆时间'),
            'last_login_ip'=>$this->string(50)->comment('最后登陆IP')
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('users');
    }
}
