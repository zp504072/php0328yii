<?php

namespace backend\controllers;

use backend\models\Goods;

class GoodsController extends \yii\web\Controller
{
    public function actionIndex()
    {
        return $this->render('index');
    }
    public function actionAdd(){
        $model=new Goods();
        return $this->render('add',['model'=>$model]);
    }

}
