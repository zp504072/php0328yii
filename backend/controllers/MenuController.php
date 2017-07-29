<?php

namespace backend\controllers;

use backend\filters\RbacFilter;
use backend\models\Menu;
use yii\data\Pagination;

class MenuController extends \yii\web\Controller
{
    public function actionIndex()
    {
        $query=Menu::find();
        $total=$query->count();
        $perPage=5;
        $pager=new Pagination([
            'totalCount' => $total,
            'defaultPageSize' => $perPage
        ]);


        $model = $query->limit($pager->limit)->offset($pager->offset)->all();
        return $this->render('index', ['model' => $model,'pager'=>$pager]);
    }
    public function actionAdd(){
        $model=new Menu();
        if($model->load(\Yii::$app->request->post())&&$model->validate()){
               //var_dump($model);exit;
            if(!$model->parent_id){
                $model->parent_id=0;
            }
            $model->save();
            \Yii::$app->session->setFlash('success','添加成功');
            $this->redirect('index');

        }


        return $this->render('add',['model'=>$model]);
    }
    public function actionEdit($id){
        $model=Menu::findOne($id);
        if($model->load(\Yii::$app->request->post())&&$model->validate()){
            //var_dump($model);exit;
            if($model->parent_id && !empty($model->children)){
                $model->addError('parent_id','只能为顶级菜单');
            }else{
                $model->save();
                \Yii::$app->session->setFlash('success','修改成功');
                return $this->redirect(['index']);
            }


        }


        return $this->render('add',['model'=>$model]);

    }
    public function actionDel($id){
        $model=Menu::findOne($id);
        $rs = Menu::find()->where(['parent_id'=>$id]);
        $count = $rs->count();
        if($count>0){
            \Yii::$app->session->setFlash('danger','不能删除，有子分类');
            return $this->redirect('index');
        }
        $model->delete();
        \Yii::$app->session->setFlash('success','删除成功');
        return $this->redirect('index');
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
