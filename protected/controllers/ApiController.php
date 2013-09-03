<?php

class ApiController extends CController
{
    public static $ERROR_USER = 1;

    private function response($data)
    {
        echo json_encode($data);
        Yii::app()->end();
    }

    private function success($message, $data)
    {
        $this->response(array(
            'Code' => 0,
            'Message' => $message,
            'Data' => $data
        ));
    }

    private function error($code, $message)
    {
        $code = (int)$code;
        if ($code === 0) {
            throw new Exception("Error must have errorCode > 0");
        }

        $this->response(array(
            'Code' => $code,
            'Message' => $message,
        ));
    }


    public function actionAuth()
    {
        $phone = Yii::app()->request->getPost('phone');

        if (empty($phone)) {
            $this->error(self::$ERROR_USER, 'Телефон пустой');
        }

        $driver = Driver::model()->findByAttributes(array(
            'phone' => $phone
        ));

        if (!$driver) {
            $this->error(self::$ERROR_USER, 'Водитель не найден');
        }

        $driver->GenerateToken();

        $this->success('Авторизация успешна', array(
            'token' => $driver->token
        ));
    }
}