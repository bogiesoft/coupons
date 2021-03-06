<?php

/**
 * This is the model class for table "{{user_pointsdetail}}".
 *
 * The followings are the available columns in table '{{user_pointsdetail}}':
 * @property integer $id
 * @property integer $user_id
 * @property integer $order_id
 * @property integer $points
 * @property integer $balance_of_payments
 * @property integer $from
 * @property integer $ratio
 * @property integer $user_points
 * @property string $create_time
 * @property string $last_time
 * @property integer $activity_type
 * @property integer $activity_id
 * @property string $order_no
 * @property integer $coupon_num
 * @property string $user_coupon_id
 */
class UserPointsdetail extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{user_pointsdetail}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('user_id, order_id, points, balance_of_payments, from, ratio, user_points, activity_type, activity_id, coupon_num', 'numerical', 'integerOnly'=>true),
			array('order_no', 'length', 'max'=>32),
			array('user_coupon_id', 'length', 'max'=>100),
			array('create_time, last_time', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, user_id, order_id, points, balance_of_payments, from, ratio, user_points, create_time, last_time, activity_type, activity_id, order_no, coupon_num, user_coupon_id', 'safe', 'on'=>'search'),
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
		    'order'=>array(self::BELONGS_TO, 'Order', 'order_id'),
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
			'order_id' => 'Order',
			'points' => 'Points',
			'balance_of_payments' => 'Balance Of Payments',
			'from' => 'From',
			'ratio' => 'Ratio',
			'user_points' => 'User Points',
			'create_time' => 'Create Time',
			'last_time' => 'Last Time',
			'activity_type' => 'Activity Type',
			'activity_id' => 'Activity',
			'order_no' => 'Order No',
			'coupon_num' => 'Coupon Num',
			'user_coupon_id' => 'User Coupon',
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
		$criteria->compare('order_id',$this->order_id);
		$criteria->compare('points',$this->points);
		$criteria->compare('balance_of_payments',$this->balance_of_payments);
		$criteria->compare('from',$this->from);
		$criteria->compare('ratio',$this->ratio);
		$criteria->compare('user_points',$this->user_points);
		$criteria->compare('create_time',$this->create_time,true);
		$criteria->compare('last_time',$this->last_time,true);
		$criteria->compare('activity_type',$this->activity_type);
		$criteria->compare('activity_id',$this->activity_id);
		$criteria->compare('order_no',$this->order_no,true);
		$criteria->compare('coupon_num',$this->coupon_num);
		$criteria->compare('user_coupon_id',$this->user_coupon_id,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return UserPointsdetail the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
