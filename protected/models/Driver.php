<?php

/**
 * Водитель
 *
 * @property integer $id
 * @property integer $accepted;
 * @property integer $sleep;
 * @property string $position;
 * @property string $name
 * @property string $surname
 * @property string $car
 * @property string $car_number
 * @property string $car_type
 * @property string $car_color
 * @property integer $organization_id
 * @property string $phone
 * @property string $document_number
 */
class Driver extends CActiveRecord
{
    public static $car_types = array(
        'sedan' => 'Легковой',
        'universal' => 'Универсал',
        'van' => 'Минивен'
    );

    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function tableName()
    {
        return 'drivers';
    }

    public function rules()
    {
        return array(
            array('name, surname, car, car_number, car_type, car_color, organization_id, phone, document_number', 'required'),
            array('organization_id', 'numerical', 'integerOnly' => true),
            array('position, sleep', 'safe'),
            array('name, surname, car, car_number, car_type, car_color, phone, document_number', 'length', 'max' => 255),
        );
    }


    public function relations()
    {
        return array(
            'organization' => array(self::BELONGS_TO, 'Organization', 'organization_id'),
        );
    }

    public function attributeLabels()
    {
        return array(
            'name' => 'Имя',
            'surname' => 'Фамилия',
            'car' => 'Машина',
            'car_number' => 'Номер машины',
            'car_type' => 'Тип машины',
            'car_color' => 'Цвет машины',
            'organization_id' => 'Организация',
            'phone' => 'Телефон',
            'document_number' => 'Номер вод. удостоверения',
        );
    }

    public function GenerateToken()
    {
        $this->token = md5(time() + rand() % 1000);
        $this->save();

        return $this->token;
    }
	
	
	public static function GetDriverForOrder($order){
		
		$order_coords = explode(';', $order->client_coords);

        // Поиск всех активных и активированных таксистов
		$drivers = self::model()->findAllByAttributes(array(
			'sleep' => 0,
			'accepted' => 1,
		));
		
		$min = 99999999;
		$result = null;

		foreach($drivers as $driver){

            // Если у таксиста не установлена позиция то пропускаем его
			if(!$driver->position)continue;

            // Машина не подходит
            if($order->car_type != 'any' && $order->car_type != $driver->car_type){
                continue;
            }

            // Проверка на то, отсылалась ли заявка данному водителю по текущему заказу
			if(OrderDriver::model()->findByAttributes(array(
				'driver_id' => $driver->id,
				'order_id' => $order->id
			))) continue;

            // Если у водителя уже есть заявка
            if(Request::model()->findByAttributes(array(
                'driver_id' => $driver->id
            )))continue;

			$driver_coords = explode(';', $driver->position);

            // Подсчет координат между водителем и заказом и поиск минимального расстояния
            $delta_x = abs($driver_coords[0] - $order_coords[0]);
            $delta_y = abs($driver_coords[1] - $order_coords[1]);
            $delta = sqrt($delta_x * $delta_x + $delta_y * $delta_y);

			if($delta < $min){
				$min = $delta;
				$result = $driver;
			}
		}
		
		return $result;
	}
}
