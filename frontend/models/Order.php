<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "order".
 *
 * @property integer $id
 * @property integer $member_id
 * @property string $name
 * @property string $province
 * @property string $city
 * @property string $area
 * @property string $address
 * @property string $tel
 * @property integer $delivery_id
 * @property string $delivery_name
 * @property double $delivery_price
 * @property string $payment_id
 * @property string $payment_name
 * @property string $total
 * @property integer $status
 * @property string $trade_no
 * @property integer $create_time
 */
class Order extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static $deliveries=[
      1=>['id'=>1,'name'=>'顺丰快递','price'=>'25','detail'=>'速度快，价格贵'],
      2=>['id'=>2,'name'=>'圆通快递','price'=>'10','detail'=>'速度一般，价格便宜'],
      3=>['id'=>3,'name'=>'中通','price'=>'20','detail'=>'速度一般，价格中等'],
    ];
    public static $payments=[
        1=>['id'=>1,'name'=>'货到付款','detail'=>'见货给钱'],
        2=>['id'=>2,'name'=>'在线支付','detail'=>'微信支付宝'],
    ];

    public static function tableName()
    {
        return 'order';
    }

    /**
     * @inheritdoc
     */

    public function rules()
    {
        return [
            [['member_id', 'delivery_id', 'status', 'create_time'], 'integer'],
            [['delivery_price','tel','total'], 'number'],
            [['name'], 'string', 'max' => 50],
            [['province', 'city', 'area'], 'string', 'max' => 20],
            [['address', 'delivery_name', 'payment_id', 'payment_name', 'trade_no'], 'string', 'max' => 255],

        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'member_id' => '用户ID',
            'name' => '收货人',
            'province' => '省',
            'city' => '市',
            'area' => '县',
            'address' => '详细地址',
            'tel' => '电话号码',
            'delivery_id' => '配送方式ID',
            'delivery_name' => '配送方式名称',
            'delivery_price' => '配送方式价格',
            'payment_id' => '支付方式ID',
            'payment_name' => '支付方式名称',
            'total' => '订单金额',
            'status' => '订单状态',
            'trade_no' => '第三方支付交易号',
            'create_time' => '创建时间',
        ];
    }
}
