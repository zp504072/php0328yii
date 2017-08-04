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
use frontend\models\Address;
use frontend\models\Cart;
use frontend\models\Order;
use frontend\models\OrderGoods;
use yii\base\ErrorException;
use yii\base\Exception;
use yii\web\Controller;
use yii\web\Cookie;

class GoodsController extends Controller
{
  public $enableCsrfValidation=false;

    public $layout = false;

    public function actionIndex($id)
    {
        $cate = GoodsCategory::findOne(['id' => $id]);
        //var_dump($cate);exit;
        if ($cate->depth == 2) {
            $model = Goods::find()->where(['goods_category_id' => $id])->all();
        } else {
            $ids = $cate->leaves()->column();
            //var_dump($ids);exit;
            $model = Goods::find()->where(['in', 'goods_category_id', $ids])->all();
            //var_dump($model);exit;

        }
        //var_dump($model);exit;

        $brand = Brand::find()->where(['!=', 'status', '-1'])->all();
        return $this->render('list', ['model' => $model, 'brand' => $brand]);
    }

    public function actionContent($id)
    {
        //var_dump(111);exit;
        //var_dump($id);exit;
        $model = Goods::findOne(['id' => $id]);
        $intro = GoodsIntro::findOne(['goods_id' => $id]);
        $pic = GoodsGallery::find()->where(['goods_id' => $id])->all();

        // var_dump($pic);exit;
        return $this->render('goods', ['model' => $model, 'intro' => $intro, 'pic' => $pic]);
    }

    public function actionAddToCart($goods_id, $amount)
    {
        //未登录

        if (\Yii::$app->user->isGuest) {

            //如果没有登录就存放在cookie中
            $cookies = \Yii::$app->request->cookies;
            //获取cookie中的购物车数据
            $cart = $cookies->get('cart');
            if ($cart == null) {
                $carts = [$goods_id => $amount];
            } else {
                $carts = unserialize($cart->value);//[1=>99，2=》1]
                if (isset($carts[$goods_id])) {
                    //购物车中已经有该商品，数量累加
                    $carts[$goods_id] += $amount;
                } else {
                    //购物车中没有该商品
                    $carts[$goods_id] = $amount;
                }
            }

            //将商品id和商品数量写入cookie
            $cookies = \Yii::$app->response->cookies;
            $cookie = new Cookie([
                'name' => 'cart',
                'value' => serialize($carts),
                'expire' => 7 * 24 * 3600 + time()
            ]);
            $cookies->add($cookie);
            return $this->redirect(['cart']);
            //var_dump($cookies->get('cart'));
        } else {
            //用户已登录，操作购物车数据表
            $cart = Cart::find()->where(['goods_id' => $goods_id])->andWhere(['member_id'=>\Yii::$app->user->getId()])->one();
            if ($cart == null) {
                //---没有 添加数据到数据表------------
                $cart = new Cart();
                $cart->goods_id = $goods_id;
                $cart->amount = $amount;
                $cart->member_id = \Yii::$app->user->getId();
                $cart->insert();
                $cart->save();
            } else {
                //---已有该商品数据 修改数据到数据表------------
                $cart->updateall(['amount' => $cart['amount'] + $amount], ['id' => $cart['id']]);
                $cart->save();

            }
            return $this->redirect(['cart']);

        }
    }

    //购物车页面
    public function actionCart()
    {
        $this->layout = false;
        //1 用户未登录，购物车数据从cookie取出
        if (\Yii::$app->user->isGuest) {
            $cookies = \Yii::$app->request->cookies;

            $cart = $cookies->get('cart');
            if ($cart == null) {
                $carts = [];
            } else {
                $carts = unserialize($cart->value);
            }

            //获取商品数据
            $models = Goods::find()->where(['in', 'id', array_keys($carts)])->asArray()->all();
            return $this->render('cart', ['models' => $models, 'carts' => $carts]);
        } else {
            //2 用户已登录，购物车数据从数据表取
            //找到当前登陆用户ID  从数据表中提取
            $goods_ids=Cart::find()->select('goods_id')->where(['member_id'=>\Yii::$app->user->getId()])->asArray()->column();
            //查询数组中商品ID的商品数据
            $models=Goods::find()->where(['in','id',$goods_ids])->all();

            //商品数量在商品模型中建立get方法

            return $this->render('cart',['models'=>$models]);
            }
        }
    //修改购物车数据
    public function actionAjaxCart()
    {
        $goods_id = \Yii::$app->request->post('goods_id');
        $amount = \Yii::$app->request->post('amount');
        //数据验证

        if (\Yii::$app->user->isGuest) {
            $cookies = \Yii::$app->request->cookies;
            //获取cookie中的购物车数据
            $cart = $cookies->get('cart');
            if ($cart == null) {
                $carts = [$goods_id => $amount];
            } else {
                $carts = unserialize($cart->value);//[1=>99，2=》1]
                if (isset($carts[$goods_id])) {
                    //购物车中已经有该商品，更新数量
                    $carts[$goods_id] = $amount;
                    //echo $carts;exit;
                } else {
                    //购物车中没有该商品
                    $carts[$goods_id] = $amount;
                }
            }
            //将商品id和商品数量写入cookie
            $cookies = \Yii::$app->response->cookies;
            $cookie = new Cookie([
                'name' => 'cart',
                'value' => serialize($carts),
                'expire' => 7 * 24 * 3600 + time()
            ]);
            $cookies->add($cookie);
            return 'success';
        }else{
            $cart=Cart::findOne(['goods_id'=>$goods_id,'member_id'=>\Yii::$app->user->id]);
            //---已有该商品数据 修改数据到数据表------------
            $cart->updateall(['amount'=>$amount],['id'=>$cart['id']]);
            $cart->save();
            return 'success';

        }
    }

    public function actionDelCart($id)
    {
        //var_dump(111);exit;
        //--------用户未登录-----修改cookie数据----------
        if(\Yii::$app->user->isGuest){
            $cookies = \Yii::$app->request->cookies/*->remove()*/;
            $cart = $cookies->get('cart');
            $carts = unserialize($cart->value);//[1=>99，2=》1]
            //删除cookie数组中键为$goods_id的数据
            unset($carts[$id]);
            //---将商品id和商品数量写入cookie-----------
            $cookies = \Yii::$app->response->cookies;
            $cookie = new Cookie([
                'name'=>'cart',
                'value'=>serialize($carts),
                'expire'=>7*24*3600+time()
            ]);
            $cookies->add($cookie);
        }else{
            //--------用户已登录-----修改数据表数据----------
            Cart::findOne(['goods_id'=>$id])->delete();
        };
        return $this->redirect(['cart']);
    }
    public function  actionOrder(){
            if(\Yii::$app->user->isGuest){

                return $this->redirect(['member/login']);


            }else{
                $id=\Yii::$app->user->id;
                $path=Address::find()->where(['user_id'=>$id])->all();
                $cart=Cart::find()->where(['member_id'=>$id])->all();
                $order=new Order();
                $deliveries=Order::$deliveries;
                //var_dump($deliveries);exit;
                $payments=Order::$payments;
                $amount=[];
                $goods_id=[];
                foreach ($cart as $v){
                    $goods_id[]=$v->goods_id;
                    $amount[$v->goods_id]=$v->amount;
                }


                $goods= Goods::find()->where(['in', 'id',$goods_id])->all();
               // var_dump($amount);exit;
                //$transaction=\Yii::$app->db->beginTransaction();
                if($order->load(\Yii::$app->request->post()) && $order->validate()){

                }
                return $this->render('order',['goods'=>$goods,'path'=>$path,'order'=>$order,'deliveries'=>$deliveries,'payments'=>$payments,'amount'=>$amount,'cart'=>$cart]);
            }

    }
    public function actionAddOrder(){
        //收货人名字
        //收获人省级
        $carts=Cart::find()->where(['member_id'=>\Yii::$app->user->id])->all();
            //var_dump($goods);exit;

        $goods_id=[];
        $goods_o=[];
           foreach($carts as $a){
               $goods_id[$a->goods_id]=$a->amount;
               $goods_o[]=$a->goods_id;
    }

        $goods=Goods::find()->where(['in','id',$goods_o])->all();

        $transaction = \Yii::$app->db->beginTransaction();
        try {
            foreach ($goods as $good) {
                $cart = Cart::findOne(['goods_id' => $good->id]);
                if (($good->stock) > ($cart->amount)) {
                    $order = new Order();
                    $order_goods = new OrderGoods();
                    $address_id = \Yii::$app->request->post('address_id');
                    $address = Address::findOne(['id' => $address_id]);
                    $order->name = $address->name;
                    //收货人名字

                    $order->member_id = \Yii::$app->user->id;
                    //收获人省级
                    $order->province = $address->province;
                    //收货人市区
                    $order->city = $address->center;
                    //收货人县
                    $order->area = $address->area;
                    //收货人详细地址
                    $order->address = $address->address;
                    //收货人电话
                    $order->tel = $address->tel;
                    //送货方式ID
                    $del_id = \Yii::$app->request->post('delivery_id');
                    $order->delivery_id = $del_id;
                    //送货方式名称
                    $del = Order::$deliveries[$del_id];
                    $order->delivery_name = $del['name'];
                    //运费
                    $order->delivery_price = \Yii::$app->request->post('yf');
                    //支付方式ID
                    $pay_id = \Yii::$app->request->post('pay_id');
                    $order->payment_id = $pay_id;
                    //支付方式名字
                    $pay = Order::$payments[$pay_id];
                    $order->payment_name = $pay['name'];
                    //订单金额
                    $order->total = \Yii::$app->request->post('money');
                    $order->status = 1;
                    $order->trade_no = '我不知知道';
                    $order->create_time = time();
                    $order->save();

                    $order_goods->order_id = $order->id;
                    $order_goods->goods_id = $good->id;
                    $order_goods->goods_name = $good->name;
                    $order_goods->logo = $good->logo;
                    $order_goods->price = $good->shop_price;
                    $order_goods->amount = $goods_id[$good->id];
                    $order_goods->total = $good->shop_price * $goods_id[$good->id];
                    $order_goods->save();
                    $good->stock = $good->stock - $order_goods->amount;
                    $good->save(false);

                    //$order_goods->total=
                } else {
                    throw new \yii\db\Exception('商品库存不足，请修改购物车商品数');
                }
            }
            $del = Cart::find()->where(['member_id' => \Yii::$app->user->id])->all();
            foreach ($del as $d) {
                $ramove_cart = Cart::findOne(['id' => $d->id]);
                $ramove_cart->delete();
            }
            $transaction->commit();
        }catch (\yii\db\Exception $e){
            $transaction->rollBack();
            var_dump($e->getMessage());exit;
        }
        return $this->redirect(['order/success']);


    }
}
