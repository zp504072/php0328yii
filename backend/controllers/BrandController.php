<?php

namespace backend\controllers;

use backend\models\Brand;
use yii\web\Request;
use yii\web\UploadedFile;

class BrandController extends \yii\web\Controller
{
    public function actionIndex()
    {
        //$brand=new Brand();
        //var_dump($brand);exit;
       // $status=$brand->status;
        //var_dump($status);exit;.
        $brands=Brand::find()->where(['!=','status','-1'])->all();
        //var_dump($brands);exit;

        return $this->render('index',['brands'=>$brands]);
    }
    //添加品牌
    public function actionAdd(){
        $model=new Brand();
        $request=new Request();
        if($request->isPost){
            $model->load($request->post());
            $model->imgFile=UploadedFile::getInstance($model,'imgFile');
            if($model->validate()){
                if($model->imgFile){
                   // var_dump($model);exit;
                    $d=\Yii::getAlias('@webroot').'/upload/'.date('Ymd');
                    if(!is_dir($d)){
                        mkdir($d);
                    }
                    $filename='/upload/'.date('Ymd').'/'.uniqid().'.'.$model->imgFile->extension;
                   // var_dump($model);exit;
                    $model->imgFile->saveAs(\Yii::getAlias('@webroot').$filename,false);

                    //var_dump($model);exit;
                    $model->logo=$filename;
                }
                $model->save();
                \Yii::$app->session->setFlash('warning','添加成功');
                return $this->redirect(['brand/index']);

            }else{
                \Yii::$app->session->setFlash('danger','添加失败');
                return $this->redirect(['brand/index']);
            }
        }
        return $this->render('add',['model'=>$model]);

    }
    public function actionDelete($id){
        $model=Brand::findOne(['id'=>$id]);
       // var_dump($model);exit;
        //删除得时候删除图片
//        if ($model->logo){
//            //echo 111;exit;
//            unlink(\Yii::getAlias('@webroot').$model->logo);
//        }


        $model->status='-1';
        $model->save();
        \Yii::$app->session->setFlash('danger','删除成功');
        return $this->redirect(['brand/index']);
    }
    public function actionEdit($id){
        $model=Brand::findOne(['id'=>$id]);
        $request=new Request();
        if($request->isPost){
            $model->load($request->post());
            $model->imgFile=UploadedFile::getInstance($model,'imgFile');
            if($model->validate()){
                if($model->imgFile){
                    // var_dump($model);exit;
                    $d=\Yii::getAlias('@webroot').'/upload/'.date('Ymd');
                    if(!is_dir($d)){
                        mkdir($d);
                    }
                    $filename='/upload/'.date('Ymd').'/'.uniqid().'.'.$model->imgFile->extension;
                    // var_dump($model);exit;
                    $model->imgFile->saveAs(\Yii::getAlias('@webroot').$filename,false);

                    //var_dump($model);exit;
                    $model->logo=$filename;
                }
                $model->save();
                \Yii::$app->session->setFlash('warning','修改成功');
                return $this->redirect(['brand/index']);

            }else{
                \Yii::$app->session->setFlash('danger','修改失败');
                return $this->redirect(['brand/index']);
            }
        }
        return $this->render('add',['model'=>$model]);
    }

}
