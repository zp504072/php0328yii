<?php

namespace frontend\controllers;

use Aliyun\Core\Config;
use backend\models\GoodsCategory;
use frontend\models\Address;
use frontend\models\Cart;
use frontend\models\Locations;
use frontend\models\LoginForm;
use frontend\models\Member;
use yii\captcha\CaptchaAction;
use yii\web\NotFoundHttpException;
use yii\web\Request;
use Aliyun\Api\Sms\Request\V20170525\SendSmsRequest;
use Aliyun\Core\DefaultAcsClient;
use Aliyun\Core\Profile\DefaultProfile;
class MemberController extends \yii\web\Controller
{
    public $layout=false;
    //注册用户
    public function actionRegister(){
        //实例化模型
        $model = new Member();
        //加载数据
        if($model->load(\Yii::$app->request->post()) && $model->validate()){
            $model->save(false);
            \yii::$app->session->setFlash('success','注册成功');
            return $this->redirect(['member/login']);
        }
        //调用视图
        return $this->render('register',['model'=>$model]);
    }

    public function actionLogout()
    {
        \Yii::$app->user->logout();

        return $this->goHome();
    }


    //登录开始
    public function actionLogin(){
        $model= new LoginForm();
        //$user = new Member();
        //加载数据
        if($model->load(\Yii::$app->request->post())){
            //验证数据
            if( $model->validate() && $model->login()){
                //var_dump($model);exit;
                 //var_dump($model);exit;
                \yii::$app->session->setFlash('success','登陆成功');
                $member_id = \Yii::$app->user->identity->getId();
                $cookies = \Yii::$app->request->cookies;
                //var_dump($cookies);exit;
                $carts = unserialize($cookies->get('cart'));
                //var_dump($carts);exit;
                if ($carts) {
                    //var_dump(111);exit;

                    foreach (array_keys($carts) as $v) {
                        $model = new Cart();
                        $models = Cart::find()
                            ->andWhere(['member_id' => $member_id])
                            ->andWhere(['goods_id' => $v])
                            ->one();
                        if (!$models) {
                            $model->amount = $carts[$v];
                            $model->goods_id = $v;
                            $model->member_id = $member_id;
                            $model->save(false);
                        } else {
                            $models->amount += $carts[$v];
                            $models->save();
                        }

                    }
                    \Yii::$app->response->cookies->remove('cart');
                }
                //$a=\Yii::$app->user->getIsGuest();
                //var_dump($a);exit;
                return $this->redirect(['index']);
            }else{
                //var_dump($model->getErrors());exit;
            }
        }
        return $this->render('login',['model'=>$model]);
    }


    //添加地址
    public function actionAddress(){
        //实例化模型
        $model = new Address();
        //$a=\Yii::$app->user->getIsGuest();
        //var_dump($a);exit;
        $user_id=\Yii::$app->user->identity->id;
        //var_dump($user_id);exit;
        $address =$model->find()->where(['user_id'=>$user_id])->all();
        $request = new Request();
        //判断提条方式
        if($request->isPost){
            //加载数据
            $model->load($request->post());
            if( $model->validate()){
                $model->save();
                return $this->redirect(['member/address']);
            }else{
                //echo 111;
                var_dump($model->getErrors());exit;
            }
        }
        //调用视图，分配数据
        return $this->render('address',['model'=>$model,'address'=>$address]);
    }
    //删除地址
    public function actionDelAddress($id){
        $model=Address::findOne(['id'=>$id]);
        if($model==null){
            throw new NotFoundHttpException('地址不存在');
        }
        $model->delete();
        return $this->redirect(['member/address']);
    }

    //修改地址
    public function actionEditAddress($id){
        //实例化模型
        $model = new Address();
        $user_id=\Yii::$app->user->identity->id;
        $address =$model->find()->where(['user_id'=>$user_id])->all();
        $model=Address::findOne(['id'=>$id]);
        $request = new Request();
        //判断提条方式
        if($request->isPost){
            //加载数据
            $model->load($request->post());
            if( $model->validate()){
                $model->save();
                return $this->redirect(['member/address']);
            }else{//验证失败，打印错误信息
                print_r($model->getErrors());exit;
            }
        }
        //调用视图，分配数据
        return $this->render('address',['model'=>$model,'address'=>$address]);
    }
    public function actionIndex(){
        $models=GoodsCategory::find()->where(['parent_id'=>0])->all();


        return $this->render('index',['models'=>$models]);

    }

    //设置默认地址
    public function actionChgStatus($id){
        $model=Address::findOne(['id'=>$id]);
        if($model->status==0){
            $model->status=1;
        }
        $model->save();
        return $this->redirect(['member/address']);
    }

    //得到三级联动城市
    public function actionLocations($id){
        $model=new Locations();
        return $model->getProvince($id);
    }
    //地址管理结束

    //定义验证码操作
    public function actions()
    {
        return [
            'captcha'=>[
                'class'=>CaptchaAction::className(),
                //验证码的长度
                'minLength'=>4,
                'maxLength'=>4,
            ]
        ];
    }

    public function actionAbc(){
        var_dump(\Yii::$app->user->isGuest);
    }

    public function actionSms($tel){
        Config::load();
       // 此处需要替换成自己的AK信息
    $accessKeyId = "LTAIrerdM4zn8GSV";//参考本文档步骤2
    $accessKeySecret = "DZtwiEYwrA3EmqIOCegn6U4xZQYOsx";//参考本文档步骤2
    //短信API产品名（短信产品名固定，无需修改）
    $product = "Dysmsapi";
    //短信API产品域名（接口地址固定，无需修改）
    $domain = "dysmsapi.aliyuncs.com";
    //暂时不支持多Region（目前仅支持cn-hangzhou请勿修改）
    $region = "cn-hangzhou";
    //初始化访问的acsCleint
    $profile = DefaultProfile::getProfile($region, $accessKeyId, $accessKeySecret);
    DefaultProfile::addEndpoint("cn-hangzhou", "cn-hangzhou", $product, $domain);
    $acsClient= new DefaultAcsClient($profile);
    $request = new SendSmsRequest;
    //必填-短信接收号码。支持以逗号分隔的形式进行批量调用，批量上限为1000个手机号码,批量调用相对于单条调用及时性稍有延迟,验证码类型的短信推荐使用单条调用的方式
    $request->setPhoneNumbers($tel);
    //必填-短信签名
    $request->setSignName("小亮的茶馆");
    //必填-短信模板Code
    $request->setTemplateCode("SMS_80195051");

    //选填-假如模板中存在变量需要替换则为必填(JSON格式),友情提示:如果JSON中需要带换行符,请参照标准的JSON协议对换行符的要求,比如短信内容中包含\r\n的情况在JSON中需要表示成\\r\\n,否则会导致JSON在服务端解析失败
        $code = rand(1000,9999);
        $request->setTemplateParam("{\"code\":\"$code\",\"product\":\"云通信服务\"}");
    //选填-发送短信流水号
    $request->setOutId("1234");
    //发起访问请求
    $acsResponse = $acsClient->getAcsResponse($request);
    var_dump($tel);
//        $code = rand(1000,9999);
//        $tel = '18728069135';
//        $res = \Yii::$app->sms->setPhoneNumbers($tel)->setTemplateParam(['code'=>$code])->send();
//        //   return $res;
//        //var_dump($res);exit;
//        //将短信验证码保存redis（session，mysql）
//        \Yii::$app->session->set('code_'.$tel,$res);
//        //验证
//        $code2 = \Yii::$app->session->get('code_'.$tel);
//        if($code == $code2){
//        }else{
//
//        }

    }
}