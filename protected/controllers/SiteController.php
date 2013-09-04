<?php

class SiteController extends CController
{
    public function actionIndex()
    {
        $this->render('index');
    }

    public function actionStatic($page)
    {
        $this->render('//static/' . $page);
    }


    public function actionError()
    {
        $error = Yii::app()->errorHandler->error;
        $this->render('/system/error404', $error);
    }


    public function actionRegister()
    {
        $form = new RegisterForm;

        if (Yii::app()->request->isPostRequest && isset($_POST['RegisterForm'])) {
            $form->attributes = $_POST['RegisterForm'];
            if ($form->validate() && $form->submit()) {
                $this->redirect('success');
            }
        }

        $this->render('register', array('model' => $form));
    }
}