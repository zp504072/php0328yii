<?php
/**
 * Created by PhpStorm.
 * User: a5040
 * Date: 2017/7/25
 * Time: 15:34
 */
namespace backend\models;
use yii\base\Model;

class EditForm extends Model{
    public $username;
    public $password;
    public $new_password;
    public $yes_password;

    public function rules(){
        return[
            [['username','new_password','password'],'required'],

        ];
    }
    public function attributeLabels()
    {
        return [
            'yes_password'=>'确认密码',
            'username' => '账号',
            'new_password' => '密码',
            'password' => '旧密码',
        ];
    }
    public function Edit(){

        $id=\Yii::$app->user->id;
        //var_dump($user);exit;
        $users=Users::findOne(['id'=>$id]);
        //var_dump($users);exit;
        if($users->password==$this->password){
            if($users->password==$this->new_password){
                echo '与旧密码一致不能修改';exit;
            }else{
                return true;
            }

        }else{
            echo '旧密码输入错误';exit;
        }
    }
}
