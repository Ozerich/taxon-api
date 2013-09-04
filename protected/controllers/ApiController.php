<?php

class ApiController extends CController
{
    public static $ERROR_USER = 1;

    private function response($data)
    {
        echo json_encode($data);
        Yii::app()->end();
    }

    private function success($message, $data = array())
    {
        $this->response(array(
            'Code' => 0,
            'Message' => $message,
            'Data' => $data
        ));
    }

    private function error($code, $message = '')
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


    private function validateCoords($coords)
    {
        return preg_match('#^\d+\.\d+\;\d+\.\d+$#sui', trim($coords)) != false;
    }

    private function getDriver()
    {
        $token = Yii::app()->request->getPost('token');
        if (empty($token)) {
            $this->error(self::$ERROR_USER, 'Токен не может быть пустым');
        }

        $user = Driver::model()->findByPk($token);
        if (!$user) {
            $this->error(self::$ERROR_USER, 'Пользователь не найден');
        }

        return $user;
    }

    private function getOrder()
    {
        $order_id = Yii::app()->request->getPost('order_id');
        if (empty($order_id)) {
            $this->error(self::$ERROR_USER, 'ID заказа не может быть пустым');
        }

        $order = Order::model()->findByPk($order_id);
        if (!$order) {
            $this->error(self::$ERROR_USER, 'Заказ не найден');
        }

        return $order;
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

    public function actionUpdatePosition()
    {
        $user = $this->getDriver();

        $coords = Yii::app()->request->getPost('coords');
        if (empty($coords)) {
            $this->error(self::$ERROR_USER, "Координаты пустые");
        }

        if (!$this->validateCoords($coords)) {
            $this->error(self::$ERROR_USER, "Неправильный формат координат");
        }

        $user->position = $coords;
        if (!$user->save()) {
            $this->error(self::$ERROR_USER, "Ошибка сохранения");
        }

        $this->success("Координаты сохранены");
    }

    public function actionAddOrder()
    {

        $phone = Yii::app()->request->getPost('phone');
        if (empty($phone)) {
            $this->error(self::$ERROR_USER, 'Телефон не указан');
        }

        $car_type = Yii::app()->request->getPost('car_type');

        $address = Yii::app()->request->getPost('address');
        $coords = Yii::app()->request->getPost('coords');

        if (empty($address) && empty($coords)) {
            $this->error(self::$ERROR_USER, "Необходимо указать координаты или адрес");
        }

        if (empty($address) && !$this->validateCoords($coords)) {
            $this->error(self::$ERROR_USER, "Координаты должны передавать в формате aa.bbbbbb;cc.dddddd");
        }

        if ($address) {
            $request_address = 'г. Минск, ' . $address;
            $google_data = file_get_contents('http://maps.googleapis.com/maps/api/geocode/json?address=' . urlencode($request_address) . '&sensor=false');
            $google_data = json_decode($google_data);
            if ($google_data->results) {
                $google_data = array_shift($google_data->results);
                $coords = $google_data->geometry->location->lat . ';' . $google_data->geometry->location->lng;
            } else {
                $this->error(self::$ERROR_USER, "Система не может определить координаты по заданному адресу");
            }
        }

        $order = new Order;

        $order->status = Order::$STATUS_CREATED;
        $order->timestamp = time();
        $order->car_type = $car_type || 1;
        $order->client_phone = $phone;
        $order->client_coords = $coords;

        if (!$order->save()) {
            $this->error(self::$ERROR_USER, "Ошибка добавления заказа");
        }

        $order->save();
        $this->success("Заказ успешно добавлен", array(
            'order_id' => $order->id
        ));
    }


    public function actionGetOrder()
    {
        $order = $this->getOrder();

        $this->success('', array(
            'id' => $order->id,
            'status' => $order->status,
        ));
    }
}