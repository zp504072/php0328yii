<?php
/**
 * Created by PhpStorm.
 * User: a5040
 * Date: 2017/7/26
 * Time: 16:04
 */
namespace backend\models;
use yii\base\Model;

class RoleForm extends Model
{
    const SCENARIO_ADD = 'add';
    public $name;
    public $description;
    public $permission=[];


    public function rules(){
        return[
            [['name','description'],'required'],
            ['permission','safe'],
            ['name','validateName','on'=>self::SCENARIO_ADD],
        ];
    }
    public function attributeLabels(){
        return[
            'name'=>'角色',
            'description'=>'介绍',
            'permission'=>'权限'
        ];
    }
    public function validateName()
    {
        $authManage = \Yii::$app->authManager;

        if($authManage->getRole($this->name)){
            $this->addError('name','角色已存在');
        }
    }
}