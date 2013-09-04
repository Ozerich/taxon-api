<?php

class AdminController extends CController
{
    public function beforeAction($action)
    {
        if (Yii::app()->user->isGuest && $action->id != 'login') {
            throw new CHttpException(404);
        }

        return parent::beforeAction($action);
    }

    public function actionLogin()
    {
        if (Yii::app()->request->isPostRequest) {
            if (Yii::app()->request->getPost('password') == Yii::app()->params['admin_password']) {
                $identity = new CUserIdentity('admin', 'admin');
                Yii::app()->user->login($identity);
                $this->redirect('/admin');
            }
        }

        $this->render('login');
    }

    public function actionAccept($id = 0)
    {
        $driver = Driver::model()->findByPk($id);
        if (!$driver) {
            throw new CHttpException(404);
        }

        $driver->accepted = 1;
        $driver->save();

        Yii::app()->end();
    }

    public function actionIndex()
    {

        $this->render('index');
    }
}