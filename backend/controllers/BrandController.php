<?php

namespace backend\controllers;

use backend\models\Brand;
use yii\data\Pagination;
use yii\web\Request;
use yii\web\UploadedFile;
use flyok666\uploadifive\UploadAction;
use flyok666\qiniu\Qiniu;

class BrandController extends \yii\web\Controller
{
    public function actionRecovery()
    {
        //回收站

        $query = Brand::find();
        //var_dump($query);exit;
        $total = $query->where(['=', 'status', '-1'])->count();
        $perPage = 5;
        $pager = new Pagination([
            'totalCount' => $total,
            'defaultPageSize' => $perPage
        ]);
        $models = $query->limit($pager->limit)->offset($pager->offset)->where(['=', 'status', '-1'])->all();

        //var_dump($model);exit;
        return $this->render('recovery', ['models' => $models,'pager'=>$pager]);

    }
    public function actionIndex()
    {
        $query=Brand::find();
        //var_dump($query);exit;
        $total=$query->where(['!=','status','-1'])->count();
        //var_dump($total);exit;

        $perPage = 5;
        $pager=new Pagination([
            'totalCount'=>$total,
            'defaultPageSize'=>$perPage
        ]);
        $models=$query->limit($pager->limit)->offset($pager->offset)->where(['!=','status','-1'])->all();
        return $this->render('index',['models'=>$models,'pager'=>$pager]);
    }
    //添加品牌
    public function actionAdd(){
        $model=new Brand();
        $request=new Request();
        if($request->isPost){
            $model->load($request->post());

            if($model->validate()){

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

            if($model->validate()){

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


    public function actions() {
        return [
            's-upload' => [
                'class' => UploadAction::className(),
                'basePath' => '@webroot/upload',
                'baseUrl' => '@web/upload',
                'enableCsrf' => true, // default
                'postFieldName' => 'Filedata', // default
                //BEGIN METHOD
               // 'format' => [$this, 'methodName'],
                //END METHOD
                //BEGIN CLOSURE BY-HASH
                'overwriteIfExist' => true,
//                'format' => function (UploadAction $action) {
//                    $fileext = $action->uploadfile->getExtension();
//                    $filename = sha1_file($action->uploadfile->tempName);
//                    return "{$filename}.{$fileext}";
//                },
                //END CLOSURE BY-HASH
                //BEGIN CLOSURE BY TIME
                'format' => function (UploadAction $action) {
                    $fileext = $action->uploadfile->getExtension();
                    $filehash = sha1(uniqid() . time());
                    $p1 = substr($filehash, 0, 2);
                    $p2 = substr($filehash, 2, 2);
                    return "{$p1}/{$p2}/{$filehash}.{$fileext}";
                },
                //END CLOSURE BY TIME
                'validateOptions' => [
                    'extensions' => ['jpg', 'png','gif'],
                    'maxSize' => 1 * 1024 * 1024, //file size
                ],
                'beforeValidate' => function (UploadAction $action) {
                    //throw new Exception('test error');
                },
                'afterValidate' => function (UploadAction $action) {},
                'beforeSave' => function (UploadAction $action) {},
                'afterSave' => function (UploadAction $action) {
                   // $action->output['fileUrl'] = $action->getWebUrl();
//                    $action->getFilename(); // "image/yyyymmddtimerand.jpg"
//                    $action->getWebUrl(); //  "baseUrl + filename, /upload/image/yyyymmddtimerand.jpg"
//                    $action->getSavePath(); // "/var/www/htdocs/upload/image/yyyymmddtimerand.jpg"
                    $qiniu=new Qiniu(\Yii::$app->params['qiniu']);
                    $qiniu->uploadFile(
                        $action->getSavePath(),$action->getWebUrl()
                    );
                    $url = $qiniu->getLink($action->getWebUrl());
                    $action->output['fileUrl']=$url;
                },
            ],
        ];
    }
    public function actionDecided($id)
    {
        $model = Brand::findOne($id);
        $model->status = 1;
        $model->save();
        return $this->redirect(['brand/recovery']);
    }
    public function actionBye($id)
    {
        $model = Brand::findOne($id);
        if($model->logo){
            unlink(\Yii::getAlias('@webroot').$model->logo);

        }
        $model->delete();

       // exit;
       // $model->delete();
        return $this->redirect(['article/recovery']);
    }

}
