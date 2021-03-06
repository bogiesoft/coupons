<?php

/**
 * This is the model class for table "{{points_rule}}".
 *
 * The followings are the available columns in table '{{points_rule}}':
 * @property integer $id
 * @property integer $merchant_id
 * @property string $name
 * @property integer $type
 * @property integer $stored_points
 * @property integer $if_storedpay_get_points
 * @property integer $cycle
 * @property string $clean_start_time
 * @property integer $clean_date_type
 * @property string $clean_date
 * @property string $create_time
 * @property string $last_time
 * @property integer $flag
 */
class PointsRule extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{points_rule}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('merchant_id, type, stored_points, if_storedpay_get_points, cycle, clean_date_type, flag', 'numerical', 'integerOnly'=>true),
			array('name', 'length', 'max'=>100),
			array('clean_start_time, clean_date', 'length', 'max'=>32),
			array('create_time, last_time', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, merchant_id, name, type, stored_points, if_storedpay_get_points, cycle, clean_start_time, clean_date_type, clean_date, create_time, last_time, flag', 'safe', 'on'=>'search'),
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
			'stored_points' => 'Stored Points',
			'if_storedpay_get_points' => 'If Storedpay Get Points',
			'cycle' => 'Cycle',
			'clean_start_time' => 'Clean Start Time',
			'clean_date_type' => 'Clean Date Type',
			'clean_date' => 'Clean Date',
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
		$criteria->compare('name',$this->name,true);
		$criteria->compare('type',$this->type);
		$criteria->compare('stored_points',$this->stored_points);
		$criteria->compare('if_storedpay_get_points',$this->if_storedpay_get_points);
		$criteria->compare('cycle',$this->cycle);
		$criteria->compare('clean_start_time',$this->clean_start_time,true);
		$criteria->compare('clean_date_type',$this->clean_date_type);
		$criteria->compare('clean_date',$this->clean_date,true);
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
	 * @return PointsRule the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
