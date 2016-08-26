<?php

/**
 * This is the model class for table "{{point_activity}}".
 *
 * The followings are the available columns in table '{{point_activity}}':
 * @property integer $id
 * @property integer $merchant_id
 * @property integer $coupon_id
 * @property integer $needs_point
 * @property integer $exchange_limit
 * @property integer $stock
 * @property integer $exchange_num
 * @property integer $time_type
 * @property string $start_time
 * @property string $end_time
 * @property integer $status
 * @property string $create_time
 * @property string $last_time
 * @property integer $flag
 */
class PointActivity extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{point_activity}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('merchant_id, coupon_id, needs_point, exchange_limit, stock, exchange_num, time_type, status, flag', 'numerical', 'integerOnly'=>true),
			array('start_time, end_time, create_time, last_time', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, merchant_id, coupon_id, needs_point, exchange_limit, stock, exchange_num, time_type, start_time, end_time, status, create_time, last_time, flag', 'safe', 'on'=>'search'),
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
            'coupon' => array(self::BELONGS_TO, 'Coupons', 'coupon_id'),
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
			'coupon_id' => 'Coupon',
			'needs_point' => 'Needs Point',
			'exchange_limit' => 'Exchange Limit',
			'stock' => 'Stock',
			'exchange_num' => 'Exchange Num',
			'time_type' => 'Time Type',
			'start_time' => 'Start Time',
			'end_time' => 'End Time',
			'status' => 'Status',
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
		$criteria->compare('coupon_id',$this->coupon_id);
		$criteria->compare('needs_point',$this->needs_point);
		$criteria->compare('exchange_limit',$this->exchange_limit);
		$criteria->compare('stock',$this->stock);
		$criteria->compare('exchange_num',$this->exchange_num);
		$criteria->compare('time_type',$this->time_type);
		$criteria->compare('start_time',$this->start_time,true);
		$criteria->compare('end_time',$this->end_time,true);
		$criteria->compare('status',$this->status);
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
	 * @return PointActivity the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
