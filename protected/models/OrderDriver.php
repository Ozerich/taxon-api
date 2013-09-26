<?php

class OrderDriver extends CActiveRecord
{

    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function tableName()
    {
        return 'order_drivers';
    }

    public function relations()
    {
        return array();
    }
}
