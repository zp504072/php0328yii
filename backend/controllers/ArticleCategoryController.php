<?php

namespace backend\controllers;
use backend\filters\RbacFilter;
use backend\models\ArticleCategory;
use yii\data\Pagination;
use yii\web\Request;

class ArticleCategoryController extends \yii\web\Controller
{
    public function actionIndex()
    {
        $query=ArticleCategory::find();
        //var_dump($query);exit;
        $total=$query->where(['!=','status','-1'])->count();
        //var_dump($total);exit;

        $perPage = 3;
        $pager=new Pagination([
            'totalCount'=>$total,
            'defaultPageSize'=>$perPage
        ]);

        $articles=$query->limit($pager->limit)->offset($pager->offset)->where(['!=','status','-1'])->all();
        return $this->render('recovery',['articles'=>$articles,'pager'=>$pager]);
    }
    public function actionAdd(){

        $model=new ArticleCategory();
        $request=new Request();
        if($request->isPost){
            $model->load($request->post());
            if($model->validate()){
                //var_dump($model);exit;

                $model->save();
                \Yii::$app->session->setFlash('success','添加成功');
                return $this->redirect(['article-category/index']);
            }else{
                \Yii::$app->session->setFlash('danger','添加失败');
                return $this->redirect(['article-category/index']);
            }
        }
        return $this->render('add',['model'=>$model]);

    }
    public function actionEdit($id){
        $model=ArticleCategory::findOne($id);
        $request=new Request();
        if($request->isPost){
            $model->load($request->post());
            if($model->validate()){
                //var_dump($model);exit;
                $model->save();
                \Yii::$app->session->setFlash('success','修改成功');
                return $this->redirect(['article-category/index']);
            }else{
                \Yii::$app->session->setFlash('danger','修改失败');
                return $this->redirect(['article-category/index']);
            }
        }
        return $this->render('add',['model'=>$model]);
    }
    public function actionDelete($id){
        $model=ArticleCategory::findOne(['id'=>$id]);
        $model->status='-1';
        $model->save();
        \Yii::$app->session->setFlash('danger','删除成功');
        return $this->redirect(['article-category/index']);

    }
    public function behaviors()
    {
        return [
            'rbac'=>[
                'class'=>RbacFilter::className(),
            ]
        ];
    }


}
