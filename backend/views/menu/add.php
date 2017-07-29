<?php
/**
 * Created by PhpStorm.
 * User: a5040
 * Date: 2017/7/28
 * Time: 14:24
 */
$form=\yii\bootstrap\ActiveForm::begin();
echo $form->field($model,'name')->textInput(['placeholder'=>'请输出菜单名  ']);
echo $form->field($model,'parent_id')->dropDownList(\yii\helpers\ArrayHelper::map(\backend\models\Menu::find()->all(),'id','name'),['prompt'=>'顶级分类']);
echo $form->field($model,'menu_url')->dropDownList(\yii\helpers\ArrayHelper::map(Yii::$app->authManager->getPermissions(),'name','description'),['prompt'=>'请选择菜单路径']);
echo $form->field($model,'sort')->textInput();
echo \yii\bootstrap\Html::submitButton('提交',['class'=>'btn btn-info']);
\yii\bootstrap\ActiveForm::end();