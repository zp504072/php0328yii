<?php

namespace backend\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "menu".
 *
 * @property integer $id
 * @property integer $parent_id
 * @property string $menu_url
 * @property integer $sort
 */
class Menu extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'menu';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['sort','name'],'required'],
            [['parent_id', 'sort'], 'integer'],
            [['menu_url'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name'=>'菜单名称',
            'parent_id' => '父级ID',
            'menu_url' => '菜单路径',
            'sort' => '排序',
        ];
    }
    public static function getMenuOptions()
    {
        return ArrayHelper::map(self::find()->where(['parent_id'=>0])->asArray()->all(),'id','label');
    }
    public function getChildren()
    {
        return $this->hasMany(self::className(),['parent_id'=>'id']);
}

}
