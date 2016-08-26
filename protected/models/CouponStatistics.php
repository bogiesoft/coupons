<?php

/**
 * This is the model class for table "{{coupon_statistics}}".
 *
 * The followings are the available columns in table '{{coupon_statistics}}':
 * @property integer $id
 * @property integer $merchant_id
 * @property integer $coupon_id
 * @property string $date
 * @property integer $new_browse_num
 * @property integer $total_browse_num
 * @property integer $new_receive_num
 * @property integer $total_receive_num
 * @property integer $new_use_num
 * @property integer $total_use_num
 * @property integer $new_order_num
 * @property integer $total_order_num
 * @property double $new_trade_money
 * @property double $total_trade_money
 * @property double $new_discount_money
 * @property double $total_discount_money
 * @property double $new_cash_money
 * @property double $total_cash_money
 * @property double $new_notcash_money
 * @property double $total_notcash_money
 * @property integer $new_browse_person_num
 * @property integer $total_browse_person_num
 * @property integer $new_receive_person_num
 * @property integer $total_receive_person_num
 * @property integer $new_consume_person_num
 * @property integer $total_consume_person_num
 * @property string $create_time
 * @property string $last_time
 * @property integer $flag
 */
class CouponStatistics extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{coupon_statistics}}';
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
			array('merchant_id, coupon_id, new_browse_num, total_browse_num, new_receive_num, total_receive_num, new_use_num, total_use_num, new_order_num, total_order_num, new_browse_person_num, total_browse_person_num, new_receive_person_num, total_receive_person_num, new_consume_person_num, total_consume_person_num, flag', 'numerical', 'integerOnly'=>true),
			array('new_trade_money, total_trade_money, new_discount_money, total_discount_money, new_cash_money, total_cash_money, new_notcash_money, total_notcash_money', 'numerical'),
			array('date, last_time', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, merchant_id, coupon_id, date, new_browse_num, total_browse_num, new_receive_num, total_receive_num, new_use_num, total_use_num, new_order_num, total_order_num, new_trade_money, total_trade_money, new_discount_money, total_discount_money, new_cash_money, total_cash_money, new_notcash_money, total_notcash_money, new_browse_person_num, total_browse_person_num, new_receive_person_num, total_receive_person_num, new_consume_person_num, total_consume_person_num, create_time, last_time, flag', 'safe', 'on'=>'search'),
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
			'coupon_id' => 'Coupon',
			'date' => 'Date',
			'new_browse_num' => 'New Browse Num',
			'total_browse_num' => 'Total Browse Num',
			'new_receive_num' => 'New Receive Num',
			'total_receive_num' => 'Total Receive Num',
			'new_use_num' => 'New Use Num',
			'total_use_num' => 'Total Use Num',
			'new_order_num' => 'New Order Num',
			'total_order_num' => 'Total Order Num',
			'new_trade_money' => 'New Trade Money',
			'total_trade_money' => 'Total Trade Money',
			'new_discount_money' => 'New Discount Money',
			'total_discount_money' => 'Total Discount Money',
			'new_cash_money' => 'New Cash Money',
			'total_cash_money' => 'Total Cash Money',
			'new_notcash_money' => 'New Notcash Money',
			'total_notcash_money' => 'Total Notcash Money',
			'new_browse_person_num' => 'New Browse Person Num',
			'total_browse_person_num' => 'Total Browse Person Num',
			'new_receive_person_num' => 'New Receive Person Num',
			'total_receive_person_num' => 'Total Receive Person Num',
			'new_consume_person_num' => 'New Consume Person Num',
			'total_consume_person_num' => 'Total Consume Person Num',
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
		$criteria->compare('date',$this->date,true);
		$criteria->compare('new_browse_num',$this->new_browse_num);
		$criteria->compare('total_browse_num',$this->total_browse_num);
		$criteria->compare('new_receive_num',$this->new_receive_num);
		$criteria->compare('total_receive_num',$this->total_receive_num);
		$criteria->compare('new_use_num',$this->new_use_num);
		$criteria->compare('total_use_num',$this->total_use_num);
		$criteria->compare('new_order_num',$this->new_order_num);
		$criteria->compare('total_order_num',$this->total_order_num);
		$criteria->compare('new_trade_money',$this->new_trade_money);
		$criteria->compare('total_trade_money',$this->total_trade_money);
		$criteria->compare('new_discount_money',$this->new_discount_money);
		$criteria->compare('total_discount_money',$this->total_discount_money);
		$criteria->compare('new_cash_money',$this->new_cash_money);
		$criteria->compare('total_cash_money',$this->total_cash_money);
		$criteria->compare('new_notcash_money',$this->new_notcash_money);
		$criteria->compare('total_notcash_money',$this->total_notcash_money);
		$criteria->compare('new_browse_person_num',$this->new_browse_person_num);
		$criteria->compare('total_browse_person_num',$this->total_browse_person_num);
		$criteria->compare('new_receive_person_num',$this->new_receive_person_num);
		$criteria->compare('total_receive_person_num',$this->total_receive_person_num);
		$criteria->compare('new_consume_person_num',$this->new_consume_person_num);
		$criteria->compare('total_consume_person_num',$this->total_consume_person_num);
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
	 * @return CouponStatistics the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
