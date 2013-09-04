<?php

/**
 * Форма регистрации
 */
class RegisterForm extends CFormModel
{
    static $car_types = array(
        'van' => 'Минивен',
        'sedan' => 'Легковой',
        'universal' => 'Универсал'
    );
    public $name;
    public $surname;
    public $phone;
    public $license_num;
    public $organization;
    public $car_model;
    public $car_type;
    public $car_num;
    public $car_color;

    public function rules()
    {
        return array(
            array('name, surname, phone, license_num, organization, car_model, car_type, car_num, car_color', 'required'),

            array('phone', 'unique_item', 'key' => 'phone', 'message' => 'Водитель с таким телефоном уже есть в базе'),
            array('car_num', 'unique_item', 'key' => 'car_number', 'message' => 'Водитель с таким номером машины уже есть в базе'),
            array('license_num', 'unique_item', 'key' => 'document_number', 'message' => 'Водитель с таким вод. удостоверением уже есть в базе'),

        );
    }

    public function attributeLabels()
    {
        return array(
            'name' => 'Имя',
            'surname' => 'Фамилия',
            'phone' => 'Телефон',
            'license_num' => 'Номер вод. удостоверения',
            'organization' => 'Организация',
            'car_model' => 'Марка машины',
            'car_type' => 'Тип машины',
            'car_num' => 'Номер машины',
            'car_color' => 'Цвет машины',
        );
    }

    public function unique_item($attribute, $params)
    {
        if (Driver::model()->findByAttributes(array(
            $params['key'] => $this->$attribute
        ))
        ) {
            $this->addError($attribute, $params['message']);
        }

        return true;
    }

    public function submit()
    {
        $model = new Driver();

        $model->name = $this->name;
        $model->surname = $this->surname;
        $model->phone = $this->phone;
        $model->document_number = $this->license_num;
        $model->organization_id = $this->organization;
        $model->car_number = $this->car_num;
        $model->car = $this->car_model;
        $model->car_type = $this->car_type;
        $model->car_color = $this->car_color;

        return $model->save();
    }

}
