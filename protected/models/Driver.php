<?php

/**
 * Водитель
 *
 * @property integer $id
 * @property integer $sleep;
 * @property string $position;
 * @property string $name
 * @property string $surname
 * @property string $car
 * @property string $car_number
 * @property string $type
 * @property string $color
 * @property integer $organization_id
 * @property string $phone
 * @property string $document_number
 * @property string $email
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
            array('name, surname, car, position, sleep, car_number, type, color, organization_id, phone, document_number, email', 'required'),
            array('organization_id', 'numerical', 'integerOnly' => true),
            array('name, surname, car, car_number, type, color, phone, document_number, email', 'length', 'max' => 255),
            array('token'),
        );
    }


    public function relations()
    {
        return array(
            array(self::BELONGS_TO, 'Organization', 'organization_id'),
        );
    }

    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'name' => 'Name',
            'surname' => 'Surname',
            'car' => 'Car',
            'car_number' => 'Car Number',
            'type' => 'Type',
            'color' => 'Color',
            'organization_id' => 'Organization',
            'phone' => 'Phone',
            'document_number' => 'Document Number',
            'email' => 'Email',
        );
    }

    public function GenerateToken()
    {
        $this->token = md5(time() + rand() % 1000);
        $this->save();

        return $this->token;
    }
}
