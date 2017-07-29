<?php
$form=\yii\bootstrap\ActiveForm::begin();
echo $form->field($edit,'username');
echo $form->field($edit,'password')->passwordInput();
echo $form->field($edit,'new_password')->passwordInput();
echo $form->field($edit,'yes_password')->passwordInput();
echo \yii\bootstrap\Html::submitButton('提交',['class'=>'btn btn-info']);
\yii\bootstrap\ActiveForm::end();