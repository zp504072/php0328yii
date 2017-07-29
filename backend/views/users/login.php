<?php
/**
 * Created by PhpStorm.
 * User: a5040
 * Date: 2017/7/24
 * Time: 11:48
 */
$form=\yii\bootstrap\ActiveForm::begin();
echo $form->field($login,'username');
echo $form->field($login,'password')->passwordInput();
echo \yii\bootstrap\Html::radioList([1=>'记住密码']);
echo $form->field($login,'remember')->checkbox([1=>'记住密码']);
echo $form->field($login,'code')->widget(\yii\captcha\Captcha::className(),
    ['captchaAction'=>'users/captcha',
        'template'=>'<div class="row"><div class="col-lg-1">{image}</div><div class="col-lg-1">{input}</div></div>'])->label('验证码');
echo \yii\bootstrap\Html::submitButton('登陆',['class'=>'btn btn-info']);

\yii\bootstrap\ActiveForm::end();