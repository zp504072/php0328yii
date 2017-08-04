<?php

namespace backend\controllers;

use backend\filters\RbacFilter;
use backend\models\GoodsCategory;
use yii\web\HttpException;
use yii\web\NotFoundHttpException;
use yii\web\Request;

class GoodsCategoryController extends BaseController
{
    public function actionIndex()
    {
        $model=GoodsCategory::find()->orderBy('tree asc,lft asc')->all();

        return $this->render('index',['model'=>$model]);
    }
    public function actionAdd()
    {
        $model = new GoodsCategory(['parent_id'=>0]);
        if($model->load(\Yii::$app->request->post()) && $model->validate()){
            //$model->save();
            //判断是否是添加一级分类
            if($model->parent_id){
                //非一级分类
                $category = GoodsCategory::findOne(['id'=>$model->parent_id]);
                if($category){
                    $model->prependTo($category);
                }else{
                    throw new HttpException(404,'上级分类不存在');
                }
            }else{
                //一级分类
                $model->makeRoot();
            }
            \Yii::$app->session->setFlash('success','分类添加成功');
            return $this->redirect(['index']);
        }
        //获取所以分类数据
        $categories = GoodsCategory::find()->select(['id','parent_id','name'])->asArray()->all();
        return $this->render('add',['model'=>$model,'categories'=>$categories]);
    }
    public function actionEdit($id)
    {
        $model = GoodsCategory::findOne(['id'=>$id]);
        if($model==null){
            throw new NotFoundHttpException('分类不存在');
        }
        if($model->load(\Yii::$app->request->post()) && $model->validate()) {
            //$model->save();
            //不能移动节点到自己节点下
            /*if($model->parent_id == $model->id){
                throw new HttpException(404,'不能移动节点到自己节点下');
            }*/

                //判断是否是添加一级分类
                if ($model->parent_id) {
                    //非一级分类

                    $category = GoodsCategory::findOne(['id' => $model->parent_id]);
                    if ($category) {
                        $model->appendTo($category);
                    } else {
                        throw new HttpException(404, '上级分类不存在');
                    }

                } else {
                    //一级分类
                    //bug fix:修复根节点修改为根节点的bug
                    if ($model->oldAttributes['parent_id'] == 0) {
                        $model->save();
                    } else {
                        $model->makeRoot();
                    }

                }
                \Yii::$app->session->setFlash('success', '分类添加成功');
                return $this->redirect(['index']);




        }


        //获取所以分类数据
        $categories = GoodsCategory::find()->select(['id','parent_id','name'])->asArray()->all();
        return $this->render('add',['model'=>$model,'categories'=>$categories]);
    }
    public function actionDelete($id){
        $model=GoodsCategory::findOne($id);
        //var_dump($model);exit;
        if($model->depth){
            $model->delete();
        }else{
            echo "不能删除最上级分类";exit;
        }

        return $this->redirect(['index']);
    }


}
