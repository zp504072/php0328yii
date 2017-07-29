
<h1><?=$model->scenario==\backend\models\PermissionForm::SCENARIO_ADD?'添加':'修改'?>权限</h1>
<?php
/**
 * Created by PhpStorm.
 * User: a5040
 * Date: 2017/7/26
 * Time: 14:16
 */

$form=\yii\bootstrap\ActiveForm::begin();
echo $form->field($model,'name')->textInput(['readonly'=>$model->scenario!=\backend\models\PermissionForm::SCENARIO_ADD]);
echo $form->field($model,'description');
echo \yii\bootstrap\Html::submitButton('提交',['class'=>'btn btn-info']);
\yii\bootstrap\ActiveForm::end();