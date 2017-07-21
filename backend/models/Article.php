<?php

namespace backend\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "article".
 *
 * @property integer $id
 * @property string $name
 * @property string $intro
 * @property integer $article_category_id
 * @property integer $sort
 * @property integer $status
 * @property integer $create_time
 */
class Article extends ActiveRecord
{

    public function getArticleDetail(){
        return $this->hasOne(ArticleDetail::className(),['article_id'=>'id']);
    }
    public function getArticleCategory(){
        return $this->hasOne(ArticleCategory::className(),['id'=>'article_category_id']);
    }
    /**
     * @inheritdoc
     */
    public static function getStatusOptions($hidden_del=true){
        $options=[
            -1=>'删除',
            0=>'隐藏',
            1=>'显示'
        ];
        if($hidden_del){
            unset($options['-1']);

        }
        return $options;
    }
    public static function getArticle_Category(){
        $categories=ArticleCategory::find()->all();
        $items=[];
        foreach($categories as $category){
            $items[$category->id]=$category->name;
        }
        return $items;
    }
    public static function tableName()
    {
        return 'article';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name','sort','status','intro'],'required'],
            [['article_category_id', 'sort', 'status', 'create_time'], 'integer'],
            [['name'], 'string', 'max' => 255],
            [['intro'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => '名称',
            'intro' => '简介',
            'article_category_id' => '文章分类id',
            'sort' => '排序',
            'status' => '状态',
            'create_time' => '创建时间',
        ];
    }
}
