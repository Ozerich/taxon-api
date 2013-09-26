<?php

function post_async($url, array $params)
{
    $post_params = array();
    foreach ($params as $key => &$val) {
        if (is_array($val)) $val = implode(',', $val);
        $post_params[] = $key . '=' . urlencode($val);
    }
    $post_string = implode('&', $post_params);

    $parts = parse_url($url);

    $fp = fsockopen($parts['host'],
        isset($parts['port']) ? $parts['port'] : 80,
        $errno, $errstr, 30);

    $out = "POST " . $parts['path'] . " HTTP/1.1\r\n";
    $out .= "Host: " . $parts['host'] . "\r\n";
    $out .= "Content-Type: application/x-www-form-urlencoded\r\n";
    $out .= "Content-Length: " . strlen($post_string) . "\r\n";
    $out .= "Connection: Close\r\n\r\n";
    if (isset($post_string)) $out .= $post_string;

    fwrite($fp, $out);
    fclose($fp);
}

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

    private function error($message = '', $data = array())
    {
        $this->response(array(
            "Code" => 1,
            'Message' => $message,
			'Data' => $data,
        ));
    }

    private function validateCoords($coords)
    {
        return preg_match('#^\-*\d+\.\d+\;\-*\d+\.\d+$#sui', trim($coords)) != false;
    }

    private function getDriver()
    {
        $token = Yii::app()->request->getPost('token');
        if (empty($token)) {
            $this->error('Токен не может быть пустым');
        }

        $user = Driver::model()->findByAttributes(array('token' => $token));
        if (!$user) {
            $this->error('Пользователь не найден');
        }

        return $user;
    }

    private function getOrder()
    {
        $order_id = Yii::app()->request->getPost('order_id');
        if (empty($order_id)) {
            $this->error('ID заказа не может быть пустым');
        }

        $order = Order::model()->findByPk($order_id);
        if (!$order) {
            $this->error('Заказ не найден');
        }

        return $order;
    }

    private function getRequest()
    {
        $request_id = Yii::app()->request->getPost('request_id');

        $request = Request::model()->findByPk($request_id);
        if (!$request) {
            $this->error('Заявка не найдена');
        }

        return $request;
    }

    public function actionGetOrganizations()
    {
        $result = array();

        foreach (Organization::model()->findAll() as $organization) {
            $result[] = array(
                'id' => $organization->id,
                'name' => $organization->name
            );
        }

        $this->success('', $result);
    }
	
	public function actionSendActivationSMS(){
		$phone = Yii::app()->request->getPost('phone');
		if(empty($phone)){
			$this->error("Телефон не указан");
		}
		
		if(!preg_match("#\d{12}#sui", $phone, $t)){
			$this->error('Телефон должен содержать 12 чиселок');
		}
		
		$code = rand() % 9000 + 1000;
		
		Yii::app()->sms->send($phone, "Ваш код: ".$code);
		
		$this->success('Код отправлен', array('code' => $code));
	}
	
    public function actionAuth()
    {
        $phone = Yii::app()->request->getPost('phone');

        if (empty($phone)) {
            $this->error('Телефон пустой');
        }

        $driver = Driver::model()->findByAttributes(array(
            'phone' => $phone
        ));

        if (!$driver) {
            $this->error('Водитель не найден');
        }

        if ($driver->accepted == false) {
            $this->error('Водитель не подтвержден');
        }

        $driver->GenerateToken();

        $this->success('Авторизация успешна', array(
            'token' => $driver->token
        ));
    }

    public function actionSetSleepMode()
    {
        $driver = $this->getDriver();

        $is_sleep = Yii::app()->request->getPost("is_sleep");
        if ($is_sleep == '') {
            $this->error("Не указан флаг");
        }

        $driver->sleep = $is_sleep ? 1 : 0;
        if (!$driver->save()) {
            $this->error('Ошибка установки режима');
        }

        $this->success('Режим установлен');
    }

    public function actionUpdatePosition()
    {
        $driver = $this->getDriver();

        $coords = Yii::app()->request->getPost('coords');
        if (empty($coords)) {
            $this->error("Координаты пустые");
        }

        if (!$this->validateCoords($coords)) {
            $this->error("Неправильный формат координат");
        }

        // Обновляю позицию у водителя
        $driver->position = $coords;
        if (!$driver->save()) {
            $this->error("Ошибка сохранения");
        }

        // Проврека на наличие заявки для водителя
        $request = Request::model()->findByAttributes(array(
            'driver_id' => $driver->id,
			'time' => 0,
        ));
        if($request && $request->time){
            $request = null;
        }

        $response = null;

        // Если заявка есть и заказ существует и заказ в статусе Поиска
        if ($request && $request->order && $request->order->status == Order::$STATUS_SEARCHING) {
            $response = array(
                'order_id' => $request->order->id,
                'coords' => $request->order->client_coords,
				'address' => $request->order->client_address,
                'car_type' => $request->order->car_type,
                'phone' => $request->order->client_phone,
            );
        }

        $this->success("Координаты сохранены", $response);
    }

    // Запускает асинхронный запрос для поиска водителя для заказа
    private function startSearch($order_id)
    {
        //$this->actionStartSearch($order_id);
        post_async('http://taxon.ozis.by/api/StartSearch/', array('order_id' => $order_id));
    }

    public function actionStartSearch()
    {
        $order = $this->getOrder();

        // Если заказ был удален или он не в статусе поиска или для него водила найти не может 5 минут то выходим из процедуры поиска
        if (!$order || !in_array($order->status, array(Order::$STATUS_SEARCHING, Order::$STATUS_CREATED))) {
            if($order){
                $order->status = Order::$STATUS_CAR_NO_FOUND;
                $order->save();
            }
            return false;
        }

        if(time() > ($order->timestamp + 300)){
            $order->status = Order::$STATUS_CAR_NO_FOUND;
            $order->save();
            return false;
        }

        $order->status = Order::$STATUS_SEARCHING;
        $order->save();

        // Поиск ближнего таксиста для заказа
        $driver = Driver::GetDriverForOrder($order);

        // Если таксиста в данный момент нету, то через 20 секунд повторить поиск
        if (!$driver) {
            sleep(20);
            $this->startSearch($order->id);
            die;
        }

        // Удалить все заявки для таксистов для данного заказа, так как ищется новый
        Request::model()->deleteAllByAttributes(array(
            'order_id' => $order->id
        ));

        // Создаю заявку для найденного таксиста
        $request = new Request;
        $request->order_id = $order->id;
        $request->driver_id = $driver->id;
        $request->save();

        // Запоминаю, что этому таксисту уже отсылалось предложение на данный заказ
        $request_driver = new OrderDriver;
        $request_driver->driver_id = $driver->id;
        $request_driver->order_id = $order->id;
        $request_driver->save();

        // Система делает паузу на 20 секунд, чтобы потом проверить статус заявки
        sleep(20);

        // Поиск заявки, которая была создана 20 секунд назад
        $request = Request::model()->findByPk($request->id);

        // Если заявка еще актуальна и таксист на неё не ответил, то повтор поиска (удаление заявки и поиск след водителя)
        if ($request && !$request->time) {
            $request->delete();
            $this->startSearch($order->id);
            die;
        }

        return true;
    }

    public function actionAddOrder()
    {
        $phone = Yii::app()->request->getPost('phone');
        if (empty($phone)) {
            $this->error('Телефон не указан');
        }

        // Поиск активных заказов для данного клиента
        $found = false;
        foreach (Order::model()->findAllByAttributes(array(
            'client_phone' => $phone,
        )) as $order) {
            if (in_array($order->status, array(Order::$STATUS_CREATED, Order::$STATUS_SEARCHING, Order::$STATUS_WAIT_CLIENT))) {
                $found = $order->id;
                break;
            }
        };

        if ($found) {
            $this->error('У вас уже есть активный заказ', array(
				'order_id' => $found
			));
        }

        $car_type = Yii::app()->request->getPost('car_type');
        if ($car_type != 'any' && $car_type != 'van' && $car_type != 'universal' && $car_type != 'sedan') {
            $this->error('Неправильный тип машины');
        }

        $address = Yii::app()->request->getPost('address');
        $coords = Yii::app()->request->getPost('coords');

        if (empty($address) && empty($coords)) {
            $this->error("Необходимо указать координаты или адрес");
        }

        if (empty($address) && !$this->validateCoords($coords)) {
            $this->error("Координаты должны передавать в формате aa.bbbbbb;cc.dddddd");
        }

        if (empty($coords)) {
            $request_address = 'г. Минск, ' . $address;
            $google_data = file_get_contents('http://maps.googleapis.com/maps/api/geocode/json?address=' . urlencode($request_address) . '&sensor=false');
            $google_data = json_decode($google_data);
            if ($google_data->results) {
                $google_data = array_shift($google_data->results);
                $coords = $google_data->geometry->location->lat . ';' . $google_data->geometry->location->lng;
            } else {
                $this->error("Система не может определить координаты по заданному адресу");
            }
        }

        $order = new Order;
        $order->status = Order::$STATUS_CREATED;
        $order->timestamp = time();
        $order->car_type = $car_type;
        $order->client_phone = $phone;
        $order->client_coords = $coords;
        $order->client_address = $address;
        if (!$order->save()) {
            $this->error("Ошибка добавления заказа");
        }
        $order->save();


        $this->startSearch($order->id);

        $this->success("Заказ успешно добавлен", array(
            'order_id' => $order->id
        ));

    }

    public function actionDriverAnswerOrder()
    {
        $driver = $this->getDriver();
        $order = $this->getOrder();

        $request = Request::model()->findByAttributes(array(
            'order_id' => $order->id
        ));

        if(!$request || $request->driver_id != $driver->id){
            $this->error('Заявка для заказа не найдена');
        }

        if($order->status != Order::$STATUS_SEARCHING){
            $this->error("Заказ не актуален");
        }

        $answer = Yii::app()->request->getPost('answer', -1);
        if($answer == -1){
            $this->error('Не указан ответ (0|1)');
        }

        $answer = $answer ? 1 : 0;

        if($answer){

            $time = Yii::app()->request->getPost('time', -1);
            if($answer && $time == -1){
                $this->error('Не указано время прибытия');
            }

            $request->time = (int)$time;
            $request->save();

            $order->status = Order::$STATUS_WAIT_CLIENT;
            $order->driver_id = $driver->id;
            $order->save();

            $this->success("Заявка принята, ждите подтверждения от клиента");
        }
        else{
            $order_id = $request->order_id;
            $request->delete();
            $this->startSearch($order_id);

            $this->success('Заявка отменена');
        }
    }

    public function actionClientAnswerOrder(){
        $order = $this->getOrder();
        if($order->status != Order::$STATUS_WAIT_CLIENT){
            $this->error('Заказ просрочен');
        }

        $answer = Yii::app()->request->getPost('answer', -1);
        if($answer == -1){
            $this->error('Не указан ответ (0|1)');
        }
        $answer = $answer ? 1 : 0;

        Request::model()->deleteAllByAttributes(array('order_id' => $order->id));

        if($answer){
			
			if(!$order->driver || $order->driver->sleep){
				$order->status = Order::$STATUS_CANCELLED;
				$order->save();
				$this->error("Таксист уже уехал");
			}
		
            $order->status = Order::$STATUS_SUCCESS;
            $order->save();

            $this->success('Заказ принят');
        }
        else{
            $order->status = Order::$STATUS_CANCELLED;
            $order->save();

            $this->success('Заказ отменен');
        }
    }

    public function actionCheckOrder()
    {
        $order = $this->getOrder();


        if ($order->status == Order::$STATUS_SEARCHING) {
            $this->success('Заказ еще в поиске', array('status' => 'searching', 'driver_status' => 0, 'found' => 0));
        }
		
		if($order->status == Order::$STATUS_SUCCESS || $order->status == Order::$STATUS_CANCELLED){
			$this->success('', array('driver_status' => 1, 'status' => $order->status));
		}

        if ($order->status == Order::$STATUS_WAIT_CLIENT) {

            $request = Request::model()->findByAttributes(array(
                'order_id' => $order->id
            ));

            if(!$request){
                $this->error("Заказ просрочен, не найдена заявка");
            }

            if(!$request->driver || !$request->driver->accepted || $request->driver->sleep){
                $this->error("Водитель отключился");
            }

            $this->success('Водитель найден', array(
                'found' => 1,
				'driver_status' => 0,
                'time' => $request->time,
                'car_type' => $request->driver->car_type,
                'fio' => $request->driver->name.' '.$request->driver->surname,
                'car' => $request->driver->car,
                'car_number' => $request->driver->car_number,
                'car_color' => $request->driver->car_color,
                'organization' => $request->driver->organization ? $request->driver->organization->name : '-',
                'phone' => $request->driver->phone,
                'document_number' => $request->driver->document_number,
            ));
        }
    }



}