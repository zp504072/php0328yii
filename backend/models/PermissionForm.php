<?php
/**
 * Created by PhpStorm.
 * User: a5040
 * Date: 2017/7/26
 * Time: 14:12
 */
namespace backend\models;
use yii\base\Model;

class PermissionForm extends Model
{
    const SCENARIO_ADD = 'add';
    public $name;
    public $description;
    public function rules(){
        return[
          [['name','description'],'required'],
            ['name','validateName','on'=>self::SCENARIO_ADD],
        ];

    }
    public function attributeLabels(){
        return[
          'name'=>'名称',
            'description'=>'描述'
        ];
    }
    public function validateName()
    {
        $authManage = \Yii::$app->authManager;
        //var_dump(111);exit;
        if($authManage->getPermission($this->name)){
            $this->addError('name','权限已存在');
        }
    }
}
