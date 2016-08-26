<?php

/**
 * This is the model class for table "{{marketing_activity}}".
 *
 * The followings are the available columns in table '{{marketing_activity}}':
 * @property integer $id
 * @property integer $merchant_id
 * @property string $name
 * @property string $type
 * @property integer $group_id
 * @property integer $send_channel
 * @property string $image_text_imageurl
 * @property string $image_text_title
 * @property integer $coupon_id
 * @property integer $stored_id
 * @property integer $time_type
 * @property integer $time_send_type
 * @property string $start_time
 * @property string $end_time
 * @property string $condition
 * @property string $send_time
 * @property string $stop_time
 * @property integer $status
 * @property string $last_time
 * @property string $create_time
 * @property integer $flag
 */
class MarketingActivity extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{marketing_activity}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('merchant_id, group_id, send_channel, coupon_id, stored_id, time_type, time_send_type, status, flag', 'numerical', 'integerOnly'=>true),
			array('name', 'length', 'max'=>32),
			array('type', 'length', 'max'=>10),
			array('image_text_imageurl', 'length', 'max'=>255),
			array('image_text_title', 'length', 'max'=>100),
			array('condition', 'length', 'max'=>2000),
			array('start_time, end_time, send_time, stop_time, last_time, create_time', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, merchant_id, name, type, group_id, send_channel, image_text_imageurl, image_text_title, coupon_id, stored_id, time_type, time_send_type, start_time, end_time, condition, send_time, stop_time, status, last_time, create_time, flag', 'safe', 'on'=>'search'),
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
			'name' => 'Name',
			'type' => 'Type',
			'group_id' => 'Group',
			'send_channel' => 'Send Channel',
			'image_text_imageurl' => 'Image Text Imageurl',
			'image_text_title' => 'Image Text Title',
			'coupon_id' => 'Coupon',
			'stored_id' => 'Stored',
			'time_type' => 'Time Type',
			'time_send_type' => 'Time Send Type',
			'start_time' => 'Start Time',
			'end_time' => 'End Time',
			'condition' => 'Condition',
			'send_time' => 'Send Time',
			'stop_time' => 'Stop Time',
			'status' => 'Status',
			'last_time' => 'Last Time',
			'create_time' => 'Create Time',
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
		$criteria->compare('name',$this->name,true);
		$criteria->compare('type',$this->type,true);
		$criteria->compare('group_id',$this->group_id);
		$criteria->compare('send_channel',$this->send_channel);
		$criteria->compare('image_text_imageurl',$this->image_text_imageurl,true);
		$criteria->compare('image_text_title',$this->image_text_title,true);
		$criteria->compare('coupon_id',$this->coupon_id);
		$criteria->compare('stored_id',$this->stored_id);
		$criteria->compare('time_type',$this->time_type);
		$criteria->compare('time_send_type',$this->time_send_type);
		$criteria->compare('start_time',$this->start_time,true);
		$criteria->compare('end_time',$this->end_time,true);
		$criteria->compare('condition',$this->condition,true);
		$criteria->compare('send_time',$this->send_time,true);
		$criteria->compare('stop_time',$this->stop_time,true);
		$criteria->compare('status',$this->status);
		$criteria->compare('last_time',$this->last_time,true);
		$criteria->compare('create_time',$this->create_time,true);
		$criteria->compare('flag',$this->flag);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return MarketingActivity the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
