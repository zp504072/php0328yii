<?php

namespace backend\controllers;

use backend\filters\RbacFilter;
use backend\models\Goods;
use backend\models\GoodsCategory;
use backend\models\GoodsDayCount;
use backend\models\GoodsGallery;
use backend\models\GoodsIntro;
use yii\web\Request;
use flyok666\uploadifive\UploadAction;
use flyok666\qiniu\Qiniu;

class GoodsController extends \yii\web\Controller
{
    public function actionIndex($keywords='')
    {
        $models=Goods::find()->where(['and', 'status>-1',"name like '%{$keywords}%'"])->all();
       // var_dump($models);exit;
        return $this->render('index',['models'=>$models]);
    }
    public function actionAdd(){
        $model=new Goods();
        $models=new GoodsIntro();
        $request=new Request();

        if($request->isPost){

            $model->load($request->post());
            $models->load($request->post());
            //var_dump($models);exit;
            if($model->validate() && $models->validate()){
                //var_dump($model);exit;
                //查找时间表是否创建当天日志
                if(!$mount=GoodsDayCount::findOne(['day'=>date('Y-m-d')])){
                    $mount=new GoodsDayCount();
                    $mount->day=date('Y-m-d');
                    $mount->count=1;
                }else{
                    $mount->count+=1;
                }
                //拼接得到商品货号
                $model->sn=date('Ymd').str_pad($mount->count,5,0,STR_PAD_LEFT);
                //var_dump($models);exit;
                $model->create_time=time();
                $model->save();
                $models->goods_id=$model->id;
                $models->save();

                //var_dump($mount->count);exit;
                $mount->save();
               // var_dump($row);exit;

            }



            return $this->redirect('index');
        }
        $categories = GoodsCategory::find()->select(['id','parent_id','name'])->asArray()->all();
        return $this->render('add',['model'=>$model,'models'=>$models,'categories'=>$categories]);
    }
    public function actionEdit($id){
        $model=Goods::findOne($id);
        $models=GoodsIntro::findOne($id);
        $request=new Request();

        if($request->isPost){

            $model->load($request->post());
            $models->load($request->post());
            //var_dump($models);exit;
            if($model->validate()){

                //var_dump($models);exit;

                $model->save();



                //var_dump($mount->count);exit;
            }
            if($models->validate()){
                $models->goods_id=$model->id;
                $models->save();
            }
            return $this->redirect('index');
        }
        $categories = GoodsCategory::find()->select(['id','parent_id','name'])->asArray()->all();
        return $this->render('add',['model'=>$model,'models'=>$models,'categories'=>$categories]);
    }
    public function actionDelete($id){
        $model=Goods::findOne($id);
        $model->status=0;
        $model->save();
        return $this->redirect('index');
    }
    public function actionGallery($id)
    {
        $goodsGallerys = GoodsGallery::find()->where(['goods_id'=>$id])->all();

        return $this->render('gallery', [
            'goodsGallerys'=>$goodsGallerys,
            'goods_id'=>$id
        ]);

    }


    public function actionContent($id){
        $model=GoodsIntro::findOne($id);
        return $this->render('content',['model'=>$model]);
    }
    public function actions() {
        return [
            'upload' => [
                'class' => 'kucha\ueditor\UEditorAction',
                'config' => [
                    "imageUrlPrefix"  => "http://www.baidu.com",//图片访问路径前缀
                    "imagePathFormat" => "/upload/image/{yyyy}{mm}{dd}/{time}{rand:6}",//上传保存路径
                    "imageRoot" => \Yii::getAlias('@webroot')
                ]
            ],
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
                    $goods_id = \Yii::$app->request->post('goods_id');
                    if($goods_id){
                        $model = new GoodsGallery();
                        $model->goods_id = $goods_id;
                        $model->path = $action->getWebUrl();
                        $model->save();
                        $action->output['fileUrl'] = $model->path;
                        $action->output['id'] = $model->id;
                    }else{
                        $action->output['fileUrl'] = $action->getWebUrl();//输出文件的相对路径
                    }
                    // $action->output['fileUrl'] = $action->getWebUrl();
//                    $action->getFilename(); // "image/yyyymmddtimerand.jpg"
//                    $action->getWebUrl(); //  "baseUrl + filename, /upload/image/yyyymmddtimerand.jpg"
//                    $action->getSavePath(); // "/var/www/htdocs/upload/image/yyyymmddtimerand.jpg"
//                    $qiniu=new Qiniu(\Yii::$app->params['qiniu']);
//                    $qiniu->uploadFile(
//                        $action->getSavePath(),$action->getWebUrl()
//                    );
//                    $url = $qiniu->getLink($action->getWebUrl());
//                    $action->output['fileUrl']=$url;

                },
            ],
        ];
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
