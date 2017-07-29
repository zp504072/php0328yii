<?php
/**
 * Created by PhpStorm.
 * User: a5040
 * Date: 2017/7/24
 * Time: 13:44
 */
namespace backend\models;
use yii\base\Model;


class LoginForm extends Model{
        public $username;
        public $password;
        public $remember;
         public $code;
    public function rules(){
        return[
            ['remember','safe'],
            [['username','password'],'required'],
            ['code','captcha','captchaAction'=>'users/captcha'],
        ];
    }
    public function attributeLabels(){
        return[
            'remember'=>'记住密码',
            'password'=>'密码',
            'username'=>'用户名'
        ];
    }
    public function login(){
        $admin=Users::findOne(['username'=>$this->username]);
        //var_dump($admin);exit;
        if($this->validate()){

            if($admin){

                if($admin->password==$this->password){

                    \Yii::$app->user->login($admin,$this->remember ? 7*24*3600:0);

                    return true;
                }else{
                    $this->addError('password','密码错误');

                }


            }else{
                $this->addError('username','用户名不存在');

            }
            return false;
        }


        }


}