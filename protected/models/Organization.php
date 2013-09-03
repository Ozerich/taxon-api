<?php

/**
 * Организация
 *
 * @property integer $id
 * @property string $name
 */
class Organization extends CActiveRecord
{
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }

	public function tableName()
	{
		return 'organizations';
	}


	public function rules()
	{
		return array(
			array('name', 'required'),
			array('name', 'length', 'max'=>255),
		);
	}

	public function relations()
	{
		return array(
		);
	}

	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'name' => 'Name',
		);
	}
}
