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

    public function GenerateToken()
    {
        $this->token = md5(time() + rand() % 1000);
        $this->save();

        return $this->token;
    }
}
