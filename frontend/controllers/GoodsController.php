<?php
/**
 * Created by PhpStorm.
 * User: a5040
 * Date: 2017/7/31
 * Time: 19:09
 */
namespace frontend\controllers;
use backend\models\Brand;
use backend\models\Goods;
use backend\models\GoodsCategory;
use backend\models\GoodsGallery;
use backend\models\GoodsIntro;
use yii\web\Controller;

class GoodsController extends Controller
{

    public $layout=false;
    public function actionIndex($id){
        $cate=GoodsCategory::findOne(['id'=>$id]);
        //var_dump($cate);exit;
        if($cate->depth==2){
            $model=Goods::find()->where(['goods_category_id'=>$id])->all();
        }else{
           $ids=$cate->leaves()->column();
            //var_dump($ids);exit;
            $model=Goods::find()->where(['in','goods_category_id',$ids])->all();
            //var_dump($model);exit;

        }
        //var_dump($model);exit;

        $brand=Brand::find()->where(['!=', 'status', '-1'])->all();
        return $this->render('list',['model'=>$model,'brand'=>$brand]);
    }
    public function actionContent($id){
        //var_dump(111);exit;
        //var_dump($id);exit;
        $model=Goods::findOne(['id'=>$id]);
        $intro=GoodsIntro::findOne(['goods_id'=>$id]);
        $pic=GoodsGallery::find()->where(['goods_id'=>$id])->all();

      // var_dump($pic);exit;
        return $this->render('goods',['model'=>$model,'intro'=>$intro,'pic'=>$pic]);
    }
}
