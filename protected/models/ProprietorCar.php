<?php

/**
 * This is the model class for table "{{proprietor_car}}".
 *
 * The followings are the available columns in table '{{proprietor_car}}':
 * @property integer $id
 * @property integer $user_id
 * @property string $car_brand
 * @property string $car_no
 * @property string $car_img
 * @property string $create_time
 * @property string $last_time
 * @property integer $flag
 */
class ProprietorCar extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{proprietor_car}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('user_id, flag', 'numerical', 'integerOnly'=>true),
			array('car_brand', 'length', 'max'=>100),
			array('car_no', 'length', 'max'=>32),
			array('car_img', 'length', 'max'=>255),
			array('create_time, last_time', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, user_id, car_brand, car_no, car_img, create_time, last_time, flag', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'user_id' => 'User',
			'car_brand' => 'Car Brand',
			'car_no' => 'Car No',
			'car_img' => 'Car Img',
			'create_time' => 'Create Time',
			'last_time' => 'Last Time',
			'flag' => 'Flag',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 *
	 * Typical usecase:
	 * - Initialize the model fields with values from filter form.
	 * - Execute this method to get CActiveDataProvider instance which will filter
	 * models according to data in model fields.
	 * - Pass data provider to CGridView, CListView or any similar widget.
	 *
	 * @return CActiveDataProvider the data provider that can return the models
	 * based on the search/filter conditions.
	 */
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('user_id',$this->user_id);
		$criteria->compare('car_brand',$this->car_brand,true);
		$criteria->compare('car_no',$this->car_no,true);
		$criteria->compare('car_img',$this->car_img,true);
		$criteria->compare('create_time',$this->create_time,true);
		$criteria->compare('last_time',$this->last_time,true);
		$criteria->compare('flag',$this->flag);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return ProprietorCar the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
