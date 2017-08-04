<?php
/**
 * Created by PhpStorm.
 * User: a5040
 * Date: 2017/8/3
 * Time: 9:45
 */

namespace backend\controllers;


use yii\web\Controller;
use backend\filters\RbacFilter;
class BaseController extends Controller
{
     public function behaviors()
     {
         return [
             'rbac'=>[
                 'class'=>RbacFilter::className(),
             ]
         ];
     }

}