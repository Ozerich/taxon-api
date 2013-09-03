<?php

class TestController extends CController
{
    public function actionIndex()
    {
        $requests_file = dirname(__FILE__) . '/../data/requests.json';

        $f = fopen($requests_file, 'r');
        $requests = json_decode(fread($f, filesize($requests_file)), true);
        fclose($f);

        $this->render('index', array('requests' => $requests));
    }

    public function actionRequest()
    {
        $command = Yii::app()->request->getPost('Command');
        if (empty($command)) {
            throw new CHttpException(500, "Команда пустая");
        }

        $post_data = array();
        $params = Yii::app()->request->getPost('Params');
        foreach ($params as $param) {
            $post_data[] = $param['key'] . '=' . urlencode($param['value']);
        }
        $post_data = implode('&', $post_data);

        $url = Yii::app()->params['api_url'] . $command;

        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $post_data);
        $response = curl_exec($curl);
        curl_close($curl);

        echo print_r(json_decode($response), true);
        Yii::app()->end();
    }
}