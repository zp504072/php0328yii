<?php

namespace backend\controllers;

use backend\models\Article;
use backend\models\ArticleDetail;
use yii\data\Pagination;
use yii\web\Request;

class ArticleController extends \yii\web\Controller
{
    public function actionRecovery()
    {
        $query = Article::find();
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

    public function actionIndex($keywords='')
    {

        $query = Article::find()->where(['and', 'status>-1',"name like '{$keywords}%'"]);
        //var_dump($query);exit;
        $total = $query->count();
        //var_dump($total);exit;

        $perPage = 5;
        $pager = new Pagination([
            'totalCount' => $total,
            'defaultPageSize' => $perPage
        ]);
        $models = $query->limit($pager->limit)->offset($pager->offset)->all();
        return $this->render('index', ['models' => $models, 'pager' => $pager]);
    }

    public function actionAdd()
    {
        //实例化文章表
        $model = new Article();
        //实例化文章详情表
        $models = new ArticleDetail();
        //实例化判断请求
        $request = new Request();

        //如果是POST方式提交
        if ($request->isPost) {
            $time=time();
            //加载文章表提交内容
            $model->load($request->post());
            //加载文章详情表提交内容
            $models->load($request->post());
            //  var_dump($models);exit;
            //如果验证通过
            if ($model->validate()) {
                //保存文章表内容
                $model->create_time=$time;
                $model->save();
                //var_dump($model);exit;
                //如果文章详情表验证通过
                if ($models->validate()) {
                    //从文章表获得ID 赋值给文章详情表
                    $models->article_id = $model->id;
                    //保存内容
                    $models->save();
                }
            } else {
                \Yii::$app->session->setFlash('danger', '添加失败');
                return $this->redirect(['article/index']);
            }
            \Yii::$app->session->setFlash('success', '添加成功');
            return $this->redirect(['article/index']);
        }

        return $this->render('add', ['model' => $model, 'models' => $models]);
    }

    public function actionEdit($id)
    {
        $model = Article::findOne($id);
        $models = ArticleDetail::findOne($id);
        $request = new Request();
        //如果是POST方式提交
        if ($request->isPost) {
            //加载文章表提交内容
            $model->load($request->post());
            //加载文章详情表提交内容
            $models->load($request->post());
            //  var_dump($models);exit;
            //如果验证通过
            if ($model->validate()) {
                //保存文章表内容
                $model->save();
                //var_dump($model);exit;
                //如果文章详情表验证通过
                if ($models->validate()) {
                    //从文章表获得ID 赋值给文章详情表
                    $models->article_id = $model->id;
                    //保存内容
                    $models->save();
                }
            }
            \Yii::$app->session->setFlash('success', '修改成功');
            return $this->redirect(['article/index']);
        }

        return $this->render('add', ['model' => $model, 'models' => $models]);

    }

    public function actionDelete($id)
    {
        $model = Article::findOne($id);
        $model->status = -1;
        $model->save();
        \Yii::$app->session->setFlash('danger', '删除成功');
        return $this->redirect(['article/index']);
    }

    public function actionDecided($id)
    {
        $model = Article::findOne($id);
        $model->status = 1;
        $model->save();
        return $this->redirect(['article/recovery']);
    }

    public function actionBye($id)
    {
        $model = Article::findOne($id);
        $models=ArticleDetail::findOne($id);
       $model->delete();
        $models->delete();
        return $this->redirect(['article/recovery']);
        }
    public function actions()
    {
        return [
            'upload' => [
                'class' => 'kucha\ueditor\UEditorAction',
                'config' => [
                    "imageUrlPrefix"  => "http://www.baidu.com",//图片访问路径前缀
                    "imagePathFormat" => "/upload/image/{yyyy}{mm}{dd}/{time}{rand:6}",//上传保存路径
                    "imageRoot" => \Yii::getAlias('@webroot')
                ]
            ]
        ];
    }

}
