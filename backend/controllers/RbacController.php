<?php

namespace backend\controllers;

use backend\models\PermissionForm;
use backend\models\RoleForm;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;

class RbacController extends \yii\web\Controller
{
    public function actionIndex()
    {
        return $this->render('index');
    }

    //添加权限
    public function actionAddPermission()
    {
        $model = new PermissionForm();
        $model->scenario = PermissionForm::SCENARIO_ADD;
        if ($model->load(\Yii::$app->request->post()) && $model->validate()) {
            $authManager = \Yii::$app->authManager;
            //创建权限

            $permission = $authManager->createPermission($model->name);
            $permission->description = $model->description;
            //保存到数据库
            $authManager->add($permission);
            \Yii::$app->session->setFlash('success', '添加成功');
            return $this->redirect(['index-permission']);

        }
        return $this->render('add-permission', ['model' => $model]);
    }

    //展示权限页面
    public function actionIndexPermission()
    {
        $authManager = \Yii::$app->authManager;
        //获取所有权限
        $model = $authManager->getPermissions();
        return $this->render('permission-index', ['model' => $model]);
    }

    //修改权限
    public function actionEditPermission($name)
    {
        $authManager = \Yii::$app->authManager;
        $permission = $authManager->getPermission($name);
        if ($permission == null) {
            throw new NotFoundHttpException('权限不存在');
        }
        $model = new PermissionForm();
        if (\Yii::$app->request->isPost) {
            if ($model->load(\Yii::$app->request->post()) && $model->validate()) {
                //修改权限
                $permission->name = $model->name;
                $permission->description = $model->description;
                //保存到数据库
                $authManager->update($name, $permission);
                \Yii::$app->session->setFlash('success', '修改成功');
                return $this->redirect(['index-permission']);

            }
        } else {
            //回显数据
            $model->name = $permission->name;
            $model->description = $permission->description;
        }


        return $this->render('add-permission', ['model' => $model]);
    }

    public function actionDelPermission($name)
    {
        //实例化权限组件
        $authManager = \Yii::$app->authManager;
        $permission = $authManager->getPermission($name);
        if ($permission == null) {
            throw new NotFoundHttpException('权限不存在');
        }
        $authManager->remove($permission);
        \Yii::$app->session->setFlash('danger', '删除成功');
        return $this->redirect(['index-permission']);

    }

    public function actionAddRole()
    {
        $model = new RoleForm();
        $model->scenario = RoleForm::SCENARIO_ADD;

        if ($model->load(\Yii::$app->request->post()) && $model->validate()) {
            //var_dump($model);exit;
            $authManager = \Yii::$app->authManager;
            //创建角色
            $role = $authManager->createRole($model->name);
            $role->description = $model->description;
            //保存角色
            $authManager->add($role);
            //遍历选中的权限

            if (is_array($model->permission)) {
                foreach ($model->permission as $permissionName) {
                    //赋值给变量
                    $permission = $authManager->getPermission($permissionName);
                    if ($permission) {
                        $authManager->addChild($role, $permission);
                    }
                }
                \Yii::$app->session->setFlash('success', '添加角色成功');
                return $this->redirect(['index-role']);
            }
        }
        return $this->render('add-role', ['model' => $model]);


    }

    public function actionIndexRole()
    {
        $authManager = \Yii::$app->authManager;
        $model = $authManager->getRoles();
        return $this->render('index-role', ['model' => $model]);
    }

    public function actionEditRole($name)
    {
        //实例化表单模型
        $model = new RoleForm();
        //实例化组件
        $authManager = \Yii::$app->authManager;
        //根据名字找到相应角色
        $role = $authManager->getRole($name);
        if ($role == null) {
            throw new NotFoundHttpException('角色不存在');
        }
        //根据名字找到相应角色有的权限
        $permissions = $authManager->getPermissionsByRole($name);

        if (\Yii::$app->request->isPost) {
            //接收处理数据
            if ($model->load(\Yii::$app->request->post()) && $model->validate()) {
                //清楚所有的权限
                $authManager->removeChildren($role);
                //修改数据
                $role->name = $model->name;
                $role->description = $model->description;
                $authManager->update($name, $role);
                //遍历选中的权限
                if (is_array($model->permission)) {
                    foreach ($model->permission as $permissionName) {
                        $permission = $authManager->getPermission($permissionName);
                        if ($permission) {
                            //如果权限存在就赋值给角色
                            $authManager->addChild($role, $permission);
                        }
                    }
                    \Yii::$app->session->setFlash('success', '修改角色成功');
                    return $this->redirect(['index-role']);
                }
            }
        } else {
            //回显数据
            $model->name = $role->name;
            $model->description = $role->description;
            $model->permission = ArrayHelper::map($permissions, 'name', 'name');
            return $this->render('add-role', ['model' => $model]);
        }


    }
    public function actionDelRole($name){
        $authManager=\Yii::$app->authManager;
        $role=$authManager->getRole($name);
        if($role==null){
            throw new NotFoundHttpException('角色不存在');
        }
        $authManager->remove($role);
        \Yii::$app->session->setFlash('danger','删除成功');
        return $this->redirect(['index-role']);
    }
}