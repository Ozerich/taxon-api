<?php

/**
 * Заявка водителю
 *
 * @property integer $id
 * @property integer $driver_id
 * @property integer $order_id
 * @property integer $timestamp
 * @property string $client_phone
 */
class Request extends CActiveRecord
{

    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function tableName()
    {
        return 'requests';
    }

    public function relations()
    {
        return array(
            'order' => array(self::BELONGS_TO, 'Order', 'order_id'),
            'driver' => array(self::BELONGS_TO, 'Driver', 'driver_id'),
        );
    }
}
