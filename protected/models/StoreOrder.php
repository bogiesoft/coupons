<?php

/**
 * This is the model class for table "{{store_order}}".
 *
 * The followings are the available columns in table '{{store_order}}':
 * @property integer $id
 * @property integer $merchant_id
 * @property string $order_no
 * @property integer $edition_type
 * @property integer $pay_type
 * @property integer $time
 * @property string $store_id_arr
 * @property integer $store_num
 * @property integer $pay_channel
 * @property double $order_money
 * @property string $fee_detail
 * @property integer $if_invoice
 * @property string $invoice_header
 * @property string $addressee
 * @property string $tel
 * @property string $address
 * @property integer $pay_status
 * @property string $pay_time
 * @property string $trade_no
 * @property integer $flag
 * @property string $create_time
 * @property string $last_time
 */
class StoreOrder extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{store_order}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('merchant_id, edition_type, pay_type, time, store_num, pay_channel, if_invoice, pay_status, flag', 'numerical', 'integerOnly'=>true),
			array('order_money', 'numerical'),
			array('order_no, addressee, tel, trade_no', 'length', 'max'=>32),
			array('store_id_arr, address', 'length', 'max'=>255),
			array('invoice_header', 'length', 'max'=>100),
			array('fee_detail, pay_time, create_time, last_time', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, merchant_id, order_no, edition_type, pay_type, time, store_id_arr, store_num, pay_channel, order_money, fee_detail, if_invoice, invoice_header, addressee, tel, address, pay_status, pay_time, trade_no, flag, create_time, last_time', 'safe', 'on'=>'search'),
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
			'order_no' => 'Order No',
			'edition_type' => 'Edition Type',
			'pay_type' => 'Pay Type',
			'time' => 'Time',
			'store_id_arr' => 'Store Id Arr',
			'store_num' => 'Store Num',
			'pay_channel' => 'Pay Channel',
			'order_money' => 'Order Money',
			'fee_detail' => 'Fee Detail',
			'if_invoice' => 'If Invoice',
			'invoice_header' => 'Invoice Header',
			'addressee' => 'Addressee',
			'tel' => 'Tel',
			'address' => 'Address',
			'pay_status' => 'Pay Status',
			'pay_time' => 'Pay Time',
			'trade_no' => 'Trade No',
			'flag' => 'Flag',
			'create_time' => 'Create Time',
			'last_time' => 'Last Time',
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
		$criteria->compare('order_no',$this->order_no,true);
		$criteria->compare('edition_type',$this->edition_type);
		$criteria->compare('pay_type',$this->pay_type);
		$criteria->compare('time',$this->time);
		$criteria->compare('store_id_arr',$this->store_id_arr,true);
		$criteria->compare('store_num',$this->store_num);
		$criteria->compare('pay_channel',$this->pay_channel);
		$criteria->compare('order_money',$this->order_money);
		$criteria->compare('fee_detail',$this->fee_detail,true);
		$criteria->compare('if_invoice',$this->if_invoice);
		$criteria->compare('invoice_header',$this->invoice_header,true);
		$criteria->compare('addressee',$this->addressee,true);
		$criteria->compare('tel',$this->tel,true);
		$criteria->compare('address',$this->address,true);
		$criteria->compare('pay_status',$this->pay_status);
		$criteria->compare('pay_time',$this->pay_time,true);
		$criteria->compare('trade_no',$this->trade_no,true);
		$criteria->compare('flag',$this->flag);
		$criteria->compare('create_time',$this->create_time,true);
		$criteria->compare('last_time',$this->last_time,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return StoreOrder the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
