<?php

/**
 * This is the model class for table "{{send_log}}".
 *
 * The followings are the available columns in table '{{send_log}}':
 * @property integer $id
 * @property integer $type
 * @property integer $activity_id
 * @property integer $wechat_status
 * @property string $wechat_msg
 * @property integer $alipay_status
 * @property string $alipay_msg
 * @property string $create_time
 * @property string $last_time
 * @property integer $flag
 */
class SendLog extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{send_log}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('type, activity_id, wechat_status, alipay_status, flag', 'numerical', 'integerOnly'=>true),
			array('wechat_msg, alipay_msg', 'length', 'max'=>1000),
			array('create_time, last_time', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, type, activity_id, wechat_status, wechat_msg, alipay_status, alipay_msg, create_time, last_time, flag', 'safe', 'on'=>'search'),
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
			'type' => 'Type',
			'activity_id' => 'Activity',
			'wechat_status' => 'Wechat Status',
			'wechat_msg' => 'Wechat Msg',
			'alipay_status' => 'Alipay Status',
			'alipay_msg' => 'Alipay Msg',
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
		$criteria->compare('type',$this->type);
		$criteria->compare('activity_id',$this->activity_id);
		$criteria->compare('wechat_status',$this->wechat_status);
		$criteria->compare('wechat_msg',$this->wechat_msg,true);
		$criteria->compare('alipay_status',$this->alipay_status);
		$criteria->compare('alipay_msg',$this->alipay_msg,true);
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
	 * @return SendLog the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
