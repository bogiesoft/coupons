<?php

/**
 * This is the model class for table "{{point_activity_statistics}}".
 *
 * The followings are the available columns in table '{{point_activity_statistics}}':
 * @property integer $id
 * @property integer $merchant_id
 * @property integer $point_activity_id
 * @property string $date
 * @property integer $total_browse_num
 * @property integer $browse_num
 * @property integer $browse_person_num
 * @property integer $exchange_num
 * @property integer $exchange_person_num
 * @property integer $use_num
 * @property integer $use_person_num
 * @property double $exchange_rate
 * @property double $use_rate
 * @property string $create_time
 * @property string $last_time
 * @property integer $flag
 */
class PointActivityStatistics extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{point_activity_statistics}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('merchant_id, point_activity_id, total_browse_num, browse_num, browse_person_num, exchange_num, exchange_person_num, use_num, use_person_num, flag', 'numerical', 'integerOnly'=>true),
			array('exchange_rate, use_rate', 'numerical'),
			array('date, create_time, last_time', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, merchant_id, point_activity_id, date, total_browse_num, browse_num, browse_person_num, exchange_num, exchange_person_num, use_num, use_person_num, exchange_rate, use_rate, create_time, last_time, flag', 'safe', 'on'=>'search'),
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
			'point_activity_id' => 'Point Activity',
			'date' => 'Date',
			'total_browse_num' => 'Total Browse Num',
			'browse_num' => 'Browse Num',
			'browse_person_num' => 'Browse Person Num',
			'exchange_num' => 'Exchange Num',
			'exchange_person_num' => 'Exchange Person Num',
			'use_num' => 'Use Num',
			'use_person_num' => 'Use Person Num',
			'exchange_rate' => 'Exchange Rate',
			'use_rate' => 'Use Rate',
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
		$criteria->compare('point_activity_id',$this->point_activity_id);
		$criteria->compare('date',$this->date,true);
		$criteria->compare('total_browse_num',$this->total_browse_num);
		$criteria->compare('browse_num',$this->browse_num);
		$criteria->compare('browse_person_num',$this->browse_person_num);
		$criteria->compare('exchange_num',$this->exchange_num);
		$criteria->compare('exchange_person_num',$this->exchange_person_num);
		$criteria->compare('use_num',$this->use_num);
		$criteria->compare('use_person_num',$this->use_person_num);
		$criteria->compare('exchange_rate',$this->exchange_rate);
		$criteria->compare('use_rate',$this->use_rate);
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
	 * @return PointActivityStatistics the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
