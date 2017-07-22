<?php
use yii\web\JsExpression;
/**
 * Created by PhpStorm.
 * User: a5040
 * Date: 2017/7/21
 * Time: 21:14
 */
$form=\yii\bootstrap\ActiveForm::begin();
echo $form->field($model,'name');

echo $form->field($model,'logo')->hiddenInput();
echo \yii\bootstrap\Html::fileInput('test', NULL, ['id' => 'test']);
echo \flyok666\uploadifive\Uploadifive::widget([
    'url' => yii\helpers\Url::to(['brand/s-upload']),
    'id' => 'test',
    'csrf' => true,
    'renderTag' => false,
    'jsOptions' => [
        'formData'=>['someKey' => 'someValue'],
        'width' => 120,
        'height' => 40,
        'onError' => new JsExpression(<<<EOF
function(file, errorCode, errorMsg, errorString) {
    console.log('The file ' + file.name + ' could not be uploaded: ' + errorString + errorCode + errorMsg);
}
EOF
        ),
        'onUploadComplete' => new JsExpression(<<<EOF
function(file, data, response) {
    data = JSON.parse(data);
    if (data.error) {
        console.log(data.msg);
    } else {
        console.log(data.fileUrl);
    }
    $("#brand-logo").val(data.fileUrl);
    $("#img").attr('src',data.fileUrl);
}
EOF
        ),
    ]
]);
echo $form->field($model,'goods_category_id')->dropDownList(\backend\models\Goods::getCategory());//下拉框
echo $form->field($model,'brand_id')->dropDownList(\backend\models\Goods::getBrand());//下拉框
echo $form->field($model,'market_price');
echo $form->field($model,'shop_price');
echo $form->field($model,'stock');
echo $form->field($model,'in_on_sale')->radioList([1=>'在售',0=>'下架']);//单选框
echo $form->field($model,'status')->radioList([1=>'正常',0=>'回收']);//单选框
echo $form->field($model,'sort');
echo \yii\bootstrap\Html::submitButton('提交',['class'=>'btn btn-info']);
//\yii\bootstrap\ActiveForm::end();