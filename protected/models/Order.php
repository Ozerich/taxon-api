<?php

/**
 * Заказ
 *
 * @property integer $id
 * @property string $status
 * @property integer $timestamp
 * @property string $car_type
 * @property string $client_phone
 * @property string $client_coords
 * @property integer $driver_id
 * @property string $driver_coords
 */
class Order extends CActiveRecord
{
    static $STATUS_CREATED = "created";

    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function tableName()
    {
        return 'orders';
    }

    public function rules()
    {
        return array(
            array('status, car_type, client_phone, client_coords', 'required'),
            array('driver_id', 'numerical', 'integerOnly' => true),
            array('car_type, client_phone, client_coords, driver_coords', 'length', 'max' => 255),
        );
    }

    public function relations()
    {
        return array();
    }

    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'status' => 'Status',
            'created_time' => 'Created Time',
            'car_type' => 'Car Type',
            'client_phone' => 'Client Phone',
            'client_coords' => 'Client Coords',
            'driver_id' => 'Driver',
            'driver_coords' => 'Driver Coords',
        );
    }
}
