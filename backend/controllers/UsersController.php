<?php

namespace backend\controllers;

use backend\filters\RbacFilter;
use backend\models\EditForm;
use backend\models\LoginForm;
use Yii;
use backend\models\Users;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Request;
use yii\captcha\CaptchaAction;

/**
 * UsersController implements the CRUD actions for Users model.
 */
class UsersController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Users models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Users::find(),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Users model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Users model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Users();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            //var_dump($model->roles);exit;
            $authManager=Yii::$app->authManager;
            if(is_array($model->roles)) {
                foreach ($model->roles as $role) {
                    $ro = $authManager->getRole($role);
                    if ($ro) {
                        //如果权限存在就赋值给角色
                        $authManager->assign($ro,$model->id);
                    }
                }
            }
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Users model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            $model->roles=Yii::$app->authManager->getRole($id);
           // var_dump($model->roles);exit;
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Users model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Users model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Users the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Users::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    public function actionLogin(){
            $login=new LoginForm();

        $request=new Request();
        if($request->isPost){

            $login->load($request->post());
            if($login->login()){
               // $ip = $_SERVER['REMOTE_ADDR'];
              //  var_dump($ip);exit;
               // var_dump($login);exit;
               // Yii::app()->request->userHostAddress;
               // var_dump($request);exit;

                $admin=Users::findOne(['username'=>$login->username]);
                $admin->last_login_ip=Yii::$app->request->userIP;
                $admin->last_login_time=time();
                $admin->save();

                //var_dump($admin->getErrors());exit;
                \Yii::$app->session->setFlash('success','登陆成功');
                return $this->redirect('index');
            }
        }
        return $this->render('login',['login'=>$login]);
    }
    public function actions(){
        return [
            'captcha'=>[
//                'class'=>'yii\captcha\CaptchaAction',
                'class'=>CaptchaAction::className(),
                //
                'minLength'=>3,
                'maxLength'=>3,
            ]
        ];
    }
    public function  actionEdit(){

       $admin=Yii::$app->user->isGuest;
        //判断是否登陆

        if(!$admin){
            //如果是登陆状态
            //找到当前登陆用户
            $edit=new EditForm();
            $request=new Request();
            $id=\Yii::$app->user->id;

            $users=Users::findOne(['id'=>$id]);
            //var_dump($user);exit;
            if($request->isPost){
               // var_dump($edit);exit;
                if($edit->load($request->post())&&$edit->validate()){
                    if($edit->Edit()){
                       $users->password=$edit->new_password;
                        //var_dump($users);exit;
                        $users->save();
                        return $this->redirect('index');
                    }else{
                        var_dump($edit->getErrors());exit;
                    }
                }else{
                    var_dump($edit->getErrors());exit;
                }


            }
            return $this->render('edit',['edit'=>$edit]);

        }else{
            throw new NotFoundHttpException('请先登陆');
        }

    }

}
