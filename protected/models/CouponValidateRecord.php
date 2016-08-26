<?php

/**
 * This is the model class for table "{{coupon_validate_record}}".
 *
 * The followings are the available columns in table '{{coupon_validate_record}}':
 * @property integer $id
 * @property integer $merchant_id
 * @property integer $user_coupon_id
 * @property integer $order_id
 * @property integer $validate_channel
 * @property integer $store_id
 * @property integer $operator_id
 * @property string $validate_time
 * @property string $create_time
 * @property string $last_time
 * @property integer $flag
 */
class CouponValidateRecord extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{coupon_validate_record}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('create_time', 'required'),
			array('merchant_id, user_coupon_id, order_id, validate_channel, store_id, operator_id, flag', 'numerical', 'integerOnly'=>true),
			array('validate_time, last_time', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, merchant_id, user_coupon_id, order_id, validate_channel, store_id, operator_id, validate_time, create_time, last_time, flag', 'safe', 'on'=>'search'),
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
			'merchant_id' => 'Merchant',
			'user_coupon_id' => 'User Coupon',
			'order_id' => 'Order',
			'validate_channel' => 'Validate Channel',
			'store_id' => 'Store',
			'operator_id' => 'Operator',
			'validate_time' => 'Validate Time',
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
		$criteria->compare('merchant_id',$this->merchant_id);
		$criteria->compare('user_coupon_id',$this->user_coupon_id);
		$criteria->compare('order_id',$this->order_id);
		$criteria->compare('validate_channel',$this->validate_channel);
		$criteria->compare('store_id',$this->store_id);
		$criteria->compare('operator_id',$this->operator_id);
		$criteria->compare('validate_time',$this->validate_time,true);
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
	 * @return CouponValidateRecord the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
