<?php

/**
 * This is the model class for table "{{coupons}}".
 *
 * The followings are the available columns in table '{{coupons}}':
 * @property integer $id
 * @property string $code
 * @property integer $merchant_id
 * @property string $logo
 * @property string $logo_url
 * @property string $title
 * @property string $vice_title
 * @property string $color
 * @property integer $time_type
 * @property string $start_time
 * @property string $end_time
 * @property integer $start_days
 * @property integer $effective_days
 * @property integer $type
 * @property string $use_time_interval
 * @property double $money
 * @property double $mini_consumption
 * @property double $discount
 * @property string $discount_illustrate
 * @property integer $if_with_userdiscount
 * @property string $use_illustrate
 * @property string $merchant_short_name
 * @property string $tel
 * @property string $cover_img
 * @property string $cover_img_url
 * @property string $cover_title
 * @property string $image_text
 * @property string $image_text_url
 * @property string $marketing_entrance
 * @property integer $num
 * @property integer $get_num
 * @property integer $receive_num
 * @property integer $if_share
 * @property integer $if_give
 * @property integer $use_channel
 * @property integer $store_limit_type
 * @property string $store_limit
 * @property string $prompt
 * @property string $card_id
 * @property integer $if_invalid
 * @property integer $status
 * @property integer $flag
 * @property string $create_time
 * @property string $last_time
 * @property integer $if_wechat
 * @property integer $money_type
 * @property string $money_random
 * @property integer $use_restriction
 * @property integer $release_status
 */
class Coupons extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{coupons}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('merchant_id, time_type, start_days, effective_days, type, if_with_userdiscount, num, get_num, receive_num, if_share, if_give, use_channel, store_limit_type, if_invalid, status, flag, if_wechat, money_type, use_restriction, release_status', 'numerical', 'integerOnly'=>true),
			array('money, mini_consumption, discount', 'numerical'),
			array('code, color, tel, card_id, money_random', 'length', 'max'=>32),
			array('logo, logo_url, cover_img_url, cover_title, store_limit', 'length', 'max'=>255),
			array('title, vice_title', 'length', 'max'=>50),
			array('use_time_interval', 'length', 'max'=>1000),
			array('merchant_short_name, cover_img, prompt', 'length', 'max'=>100),
			array('start_time, end_time, discount_illustrate, use_illustrate, image_text, image_text_url, marketing_entrance, create_time, last_time', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, code, merchant_id, logo, logo_url, title, vice_title, color, time_type, start_time, end_time, start_days, effective_days, type, use_time_interval, money, mini_consumption, discount, discount_illustrate, if_with_userdiscount, use_illustrate, merchant_short_name, tel, cover_img, cover_img_url, cover_title, image_text, image_text_url, marketing_entrance, num, get_num, receive_num, if_share, if_give, use_channel, store_limit_type, store_limit, prompt, card_id, if_invalid, status, flag, create_time, last_time, if_wechat, money_type, money_random, use_restriction, release_status', 'safe', 'on'=>'search'),
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
		    'merchant' => array(self::BELONGS_TO,'Merchant','merchant_id'),
            '__AR_COUPON' => array(self::BELONGS_TO, 'PointActivity', 'coupon_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'code' => 'Code',
			'merchant_id' => 'Merchant',
			'logo' => 'Logo',
			'logo_url' => 'Logo Url',
			'title' => 'Title',
			'vice_title' => 'Vice Title',
			'color' => 'Color',
			'time_type' => 'Time Type',
			'start_time' => 'Start Time',
			'end_time' => 'End Time',
			'start_days' => 'Start Days',
			'effective_days' => 'Effective Days',
			'type' => 'Type',
			'use_time_interval' => 'Use Time Interval',
			'money' => 'Money',
			'mini_consumption' => 'Mini Consumption',
			'discount' => 'Discount',
			'discount_illustrate' => 'Discount Illustrate',
			'if_with_userdiscount' => 'If With Userdiscount',
			'use_illustrate' => 'Use Illustrate',
			'merchant_short_name' => 'Merchant Short Name',
			'tel' => 'Tel',
			'cover_img' => 'Cover Img',
			'cover_img_url' => 'Cover Img Url',
			'cover_title' => 'Cover Title',
			'image_text' => 'Image Text',
			'image_text_url' => 'Image Text Url',
			'marketing_entrance' => 'Marketing Entrance',
			'num' => 'Num',
			'get_num' => 'Get Num',
			'receive_num' => 'Receive Num',
			'if_share' => 'If Share',
			'if_give' => 'If Give',
			'use_channel' => 'Use Channel',
			'store_limit_type' => 'Store Limit Type',
			'store_limit' => 'Store Limit',
			'prompt' => 'Prompt',
			'card_id' => 'Card',
			'if_invalid' => 'If Invalid',
			'status' => 'Status',
			'flag' => 'Flag',
			'create_time' => 'Create Time',
			'last_time' => 'Last Time',
			'if_wechat' => 'If Wechat',
			'money_type' => 'Money Type',
			'money_random' => 'Money Random',
			'use_restriction' => 'Use Restriction',
			'release_status' => 'Release Status',
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
		$criteria->compare('code',$this->code,true);
		$criteria->compare('merchant_id',$this->merchant_id);
		$criteria->compare('logo',$this->logo,true);
		$criteria->compare('logo_url',$this->logo_url,true);
		$criteria->compare('title',$this->title,true);
		$criteria->compare('vice_title',$this->vice_title,true);
		$criteria->compare('color',$this->color,true);
		$criteria->compare('time_type',$this->time_type);
		$criteria->compare('start_time',$this->start_time,true);
		$criteria->compare('end_time',$this->end_time,true);
		$criteria->compare('start_days',$this->start_days);
		$criteria->compare('effective_days',$this->effective_days);
		$criteria->compare('type',$this->type);
		$criteria->compare('use_time_interval',$this->use_time_interval,true);
		$criteria->compare('money',$this->money);
		$criteria->compare('mini_consumption',$this->mini_consumption);
		$criteria->compare('discount',$this->discount);
		$criteria->compare('discount_illustrate',$this->discount_illustrate,true);
		$criteria->compare('if_with_userdiscount',$this->if_with_userdiscount);
		$criteria->compare('use_illustrate',$this->use_illustrate,true);
		$criteria->compare('merchant_short_name',$this->merchant_short_name,true);
		$criteria->compare('tel',$this->tel,true);
		$criteria->compare('cover_img',$this->cover_img,true);
		$criteria->compare('cover_img_url',$this->cover_img_url,true);
		$criteria->compare('cover_title',$this->cover_title,true);
		$criteria->compare('image_text',$this->image_text,true);
		$criteria->compare('image_text_url',$this->image_text_url,true);
		$criteria->compare('marketing_entrance',$this->marketing_entrance,true);
		$criteria->compare('num',$this->num);
		$criteria->compare('get_num',$this->get_num);
		$criteria->compare('receive_num',$this->receive_num);
		$criteria->compare('if_share',$this->if_share);
		$criteria->compare('if_give',$this->if_give);
		$criteria->compare('use_channel',$this->use_channel);
		$criteria->compare('store_limit_type',$this->store_limit_type);
		$criteria->compare('store_limit',$this->store_limit,true);
		$criteria->compare('prompt',$this->prompt,true);
		$criteria->compare('card_id',$this->card_id,true);
		$criteria->compare('if_invalid',$this->if_invalid);
		$criteria->compare('status',$this->status);
		$criteria->compare('flag',$this->flag);
		$criteria->compare('create_time',$this->create_time,true);
		$criteria->compare('last_time',$this->last_time,true);
		$criteria->compare('if_wechat',$this->if_wechat);
		$criteria->compare('money_type',$this->money_type);
		$criteria->compare('money_random',$this->money_random,true);
		$criteria->compare('use_restriction',$this->use_restriction);
		$criteria->compare('release_status',$this->release_status);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Coupons the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
