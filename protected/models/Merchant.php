<?php

/**
 * This is the model class for table "{{merchant}}".
 *
 * The followings are the available columns in table '{{merchant}}':
 * @property integer $id
 * @property string $merchant_no
 * @property string $wechat_merchant_no
 * @property string $wq_mchid
 * @property integer $agent_id
 * @property string $channel_id
 * @property string $account
 * @property string $pwd
 * @property string $name
 * @property string $wq_m_short_name
 * @property integer $wq_m_type
 * @property integer $charge_type
 * @property string $wq_m_name
 * @property string $wq_m_industry
 * @property string $wq_m_address
 * @property string $wq_m_business_license_no
 * @property string $wq_m_business_license
 * @property string $wq_m_organization_code
 * @property string $wq_m_organization
 * @property string $wq_m_legal_person_name
 * @property string $wq_m_legal_person_id
 * @property string $wq_m_legal_person_positive
 * @property string $wq_m_legal_person_opposite
 * @property string $wq_m_contacts_name
 * @property string $wq_m_contacts_phone
 * @property integer $wq_m_verify_status
 * @property string $wq_m_verify_pass_time
 * @property string $wq_m_reject_remark
 * @property string $wx_name
 * @property string $seller_email
 * @property string $key
 * @property string $create_time
 * @property string $last_time
 * @property integer $status
 * @property integer $flag
 * @property string $alipay_code
 * @property integer $verify_status
 * @property integer $wechat_verify_status
 * @property string $wechat_verify_status_submit_time
 * @property string $wechat_verify_status_auditpass_time
 * @property string $wechat_verify_status_verify_time
 * @property string $wechat_verify_status_sign_time
 * @property string $wechat_verify_status_reject_time
 * @property string $remark
 * @property integer $msg_num
 * @property integer $if_stored
 * @property integer $points_rule
 * @property string $merchant_number
 * @property string $gj_start_time
 * @property string $gj_end_time
 * @property integer $gj_product_id
 * @property string $yx_open_time
 * @property integer $if_tryout
 * @property integer $tryout_status
 * @property integer $gj_open_status
 * @property string $encrypt_id
 * @property string $wechat_id
 * @property string $wechat
 * @property integer $wechat_type
 * @property string $wechat_qrcode
 * @property string $wechat_account
 * @property string $wechat_appsecret
 * @property string $wechat_subscription_appsecret
 * @property string $wechat_key
 * @property string $wechat_mchid
 * @property string $t_wx_appid
 * @property string $wechat_interface_url
 * @property string $wechat_token
 * @property string $wechat_encodingaeskey
 * @property integer $wechat_encrypt_type
 * @property integer $operator_refund_time
 * @property string $wechat_apiclient_key
 * @property string $wechat_appid
 * @property string $wechat_subscription_appid
 * @property integer $dzoperator_refund_time
 * @property string $access_token_json
 * @property string $jsapi_ticket_json
 * @property string $fuwu_name
 * @property string $wechat_name
 * @property string $ums_3des_key
 * @property string $api_key
 * @property string $mchid
 * @property string $ums_mchid
 * @property string $alipay_qrcode
 * @property integer $if_wx_open
 * @property integer $wxpay_merchant_type
 * @property string $wechat_apiclient_cert
 * @property string $t_wx_mchid
 * @property integer $if_alipay_open
 * @property integer $alipay_api_version
 * @property string $partner
 * @property string $alipay_key
 * @property string $appid
 * @property string $alipay_auth_pid
 * @property string $alipay_auth_appid
 * @property string $alipay_auth_token
 * @property string $alipay_auth_refresh_token
 * @property string $alipay_auth_time
 * @property string $alipay_auth_token_expires_in
 * @property string $alipay_auth_refresh_token_expires_in
 * @property string $wechat_template_id
 * @property string $wechat_thirdparty_authorizer_info
 * @property string $wechat_thirdparty_authorizer_refresh_token
 * @property string $wechat_thirdparty_authorizer_appid
 * @property integer $wechat_thirdparty_authorizer_if_auth
 * @property string $wechat_thirdparty_authorization_info
 * @property string $wechat_thirdparty_authorizer_time
 * @property string $wechat_thirdparty_authorizer_cancel_time
 * @property string $wechat_thirdparty_authorizer_refresh_time
 * @property string $merchant_remark
 * @property string $auth_set
 * @property integer $email_confirm_status
 */
class Merchant extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{merchant}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('agent_id, wq_m_type, charge_type, wq_m_verify_status, status, flag, verify_status, wechat_verify_status, msg_num, if_stored, points_rule, gj_product_id, if_tryout, tryout_status, gj_open_status, wechat_type, wechat_encrypt_type, operator_refund_time, dzoperator_refund_time, if_wx_open, wxpay_merchant_type, if_alipay_open, alipay_api_version, wechat_thirdparty_authorizer_if_auth, email_confirm_status', 'numerical', 'integerOnly'=>true),
			array('merchant_no, wechat_merchant_no, channel_id, account, pwd, wq_m_industry, wq_m_business_license_no, wq_m_business_license, wq_m_organization, wq_m_legal_person_name, wq_m_legal_person_id, wq_m_legal_person_positive, wq_m_legal_person_opposite, wq_m_contacts_name, wq_m_contacts_phone, seller_email, key, wechat_id, wechat, wechat_qrcode, wechat_key, wechat_mchid, wechat_appid, wechat_subscription_appid, ums_3des_key, api_key, mchid, alipay_qrcode', 'length', 'max'=>32),
			array('wq_mchid', 'length', 'max'=>20),
			array('name, wq_m_short_name, wq_m_name, wx_name, wechat_account, wechat_appsecret, wechat_subscription_appsecret, wechat_token, wechat_encodingaeskey, wechat_apiclient_key, fuwu_name, wechat_name, wechat_apiclient_cert, appid, alipay_auth_token, alipay_auth_refresh_token, auth_set', 'length', 'max'=>100),
			array('wq_m_address, wq_m_reject_remark', 'length', 'max'=>225),
			array('wq_m_organization_code, t_wx_appid, ums_mchid, t_wx_mchid, alipay_key, alipay_auth_pid, alipay_auth_appid, wechat_template_id, wechat_thirdparty_authorizer_appid', 'length', 'max'=>50),
			array('alipay_code', 'length', 'max'=>12),
			array('merchant_number, encrypt_id', 'length', 'max'=>10),
			array('wechat_interface_url, access_token_json, jsapi_ticket_json', 'length', 'max'=>255),
			array('partner', 'length', 'max'=>16),
			array('wechat_thirdparty_authorizer_info, wechat_thirdparty_authorization_info', 'length', 'max'=>1000),
			array('wechat_thirdparty_authorizer_refresh_token', 'length', 'max'=>600),
			array('merchant_remark', 'length', 'max'=>200),
			array('wq_m_verify_pass_time, create_time, last_time, wechat_verify_status_submit_time, wechat_verify_status_auditpass_time, wechat_verify_status_verify_time, wechat_verify_status_sign_time, wechat_verify_status_reject_time, remark, gj_start_time, gj_end_time, yx_open_time, alipay_auth_time, alipay_auth_token_expires_in, alipay_auth_refresh_token_expires_in, wechat_thirdparty_authorizer_time, wechat_thirdparty_authorizer_cancel_time, wechat_thirdparty_authorizer_refresh_time', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, merchant_no, wechat_merchant_no, wq_mchid, agent_id, channel_id, account, pwd, name, wq_m_short_name, wq_m_type, charge_type, wq_m_name, wq_m_industry, wq_m_address, wq_m_business_license_no, wq_m_business_license, wq_m_organization_code, wq_m_organization, wq_m_legal_person_name, wq_m_legal_person_id, wq_m_legal_person_positive, wq_m_legal_person_opposite, wq_m_contacts_name, wq_m_contacts_phone, wq_m_verify_status, wq_m_verify_pass_time, wq_m_reject_remark, wx_name, seller_email, key, create_time, last_time, status, flag, alipay_code, verify_status, wechat_verify_status, wechat_verify_status_submit_time, wechat_verify_status_auditpass_time, wechat_verify_status_verify_time, wechat_verify_status_sign_time, wechat_verify_status_reject_time, remark, msg_num, if_stored, points_rule, merchant_number, gj_start_time, gj_end_time, gj_product_id, yx_open_time, if_tryout, tryout_status, gj_open_status, encrypt_id, wechat_id, wechat, wechat_type, wechat_qrcode, wechat_account, wechat_appsecret, wechat_subscription_appsecret, wechat_key, wechat_mchid, t_wx_appid, wechat_interface_url, wechat_token, wechat_encodingaeskey, wechat_encrypt_type, operator_refund_time, wechat_apiclient_key, wechat_appid, wechat_subscription_appid, dzoperator_refund_time, access_token_json, jsapi_ticket_json, fuwu_name, wechat_name, ums_3des_key, api_key, mchid, ums_mchid, alipay_qrcode, if_wx_open, wxpay_merchant_type, wechat_apiclient_cert, t_wx_mchid, if_alipay_open, alipay_api_version, partner, alipay_key, appid, alipay_auth_pid, alipay_auth_appid, alipay_auth_token, alipay_auth_refresh_token, alipay_auth_time, alipay_auth_token_expires_in, alipay_auth_refresh_token_expires_in, wechat_template_id, wechat_thirdparty_authorizer_info, wechat_thirdparty_authorizer_refresh_token, wechat_thirdparty_authorizer_appid, wechat_thirdparty_authorizer_if_auth, wechat_thirdparty_authorization_info, wechat_thirdparty_authorizer_time, wechat_thirdparty_authorizer_cancel_time, wechat_thirdparty_authorizer_refresh_time, merchant_remark, auth_set, email_confirm_status', 'safe', 'on'=>'search'),
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
		    'agent' => array(self::BELONGS_TO,'Agent','agent_id'),
		    'gjproduct' => array(self::BELONGS_TO,'GjProduct','gj_product_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'merchant_no' => 'Merchant No',
			'wechat_merchant_no' => 'Wechat Merchant No',
			'wq_mchid' => 'Wq Mchid',
			'agent_id' => 'Agent',
			'channel_id' => 'Channel',
			'account' => 'Account',
			'pwd' => 'Pwd',
			'name' => 'Name',
			'wq_m_short_name' => 'Wq M Short Name',
			'wq_m_type' => 'Wq M Type',
			'charge_type' => 'Charge Type',
			'wq_m_name' => 'Wq M Name',
			'wq_m_industry' => 'Wq M Industry',
			'wq_m_address' => 'Wq M Address',
			'wq_m_business_license_no' => 'Wq M Business License No',
			'wq_m_business_license' => 'Wq M Business License',
			'wq_m_organization_code' => 'Wq M Organization Code',
			'wq_m_organization' => 'Wq M Organization',
			'wq_m_legal_person_name' => 'Wq M Legal Person Name',
			'wq_m_legal_person_id' => 'Wq M Legal Person',
			'wq_m_legal_person_positive' => 'Wq M Legal Person Positive',
			'wq_m_legal_person_opposite' => 'Wq M Legal Person Opposite',
			'wq_m_contacts_name' => 'Wq M Contacts Name',
			'wq_m_contacts_phone' => 'Wq M Contacts Phone',
			'wq_m_verify_status' => 'Wq M Verify Status',
			'wq_m_verify_pass_time' => 'Wq M Verify Pass Time',
			'wq_m_reject_remark' => 'Wq M Reject Remark',
			'wx_name' => 'Wx Name',
			'seller_email' => 'Seller Email',
			'key' => 'Key',
			'create_time' => 'Create Time',
			'last_time' => 'Last Time',
			'status' => 'Status',
			'flag' => 'Flag',
			'alipay_code' => 'Alipay Code',
			'verify_status' => 'Verify Status',
			'wechat_verify_status' => 'Wechat Verify Status',
			'wechat_verify_status_submit_time' => 'Wechat Verify Status Submit Time',
			'wechat_verify_status_auditpass_time' => 'Wechat Verify Status Auditpass Time',
			'wechat_verify_status_verify_time' => 'Wechat Verify Status Verify Time',
			'wechat_verify_status_sign_time' => 'Wechat Verify Status Sign Time',
			'wechat_verify_status_reject_time' => 'Wechat Verify Status Reject Time',
			'remark' => 'Remark',
			'msg_num' => 'Msg Num',
			'if_stored' => 'If Stored',
			'points_rule' => 'Points Rule',
			'merchant_number' => 'Merchant Number',
			'gj_start_time' => 'Gj Start Time',
			'gj_end_time' => 'Gj End Time',
			'gj_product_id' => 'Gj Product',
			'yx_open_time' => 'Yx Open Time',
			'if_tryout' => 'If Tryout',
			'tryout_status' => 'Tryout Status',
			'gj_open_status' => 'Gj Open Status',
			'encrypt_id' => 'Encrypt',
			'wechat_id' => 'Wechat',
			'wechat' => 'Wechat',
			'wechat_type' => 'Wechat Type',
			'wechat_qrcode' => 'Wechat Qrcode',
			'wechat_account' => 'Wechat Account',
			'wechat_appsecret' => 'Wechat Appsecret',
			'wechat_subscription_appsecret' => 'Wechat Subscription Appsecret',
			'wechat_key' => 'Wechat Key',
			'wechat_mchid' => 'Wechat Mchid',
			't_wx_appid' => 'T Wx Appid',
			'wechat_interface_url' => 'Wechat Interface Url',
			'wechat_token' => 'Wechat Token',
			'wechat_encodingaeskey' => 'Wechat Encodingaeskey',
			'wechat_encrypt_type' => 'Wechat Encrypt Type',
			'operator_refund_time' => 'Operator Refund Time',
			'wechat_apiclient_key' => 'Wechat Apiclient Key',
			'wechat_appid' => 'Wechat Appid',
			'wechat_subscription_appid' => 'Wechat Subscription Appid',
			'dzoperator_refund_time' => 'Dzoperator Refund Time',
			'access_token_json' => 'Access Token Json',
			'jsapi_ticket_json' => 'Jsapi Ticket Json',
			'fuwu_name' => 'Fuwu Name',
			'wechat_name' => 'Wechat Name',
			'ums_3des_key' => 'Ums 3des Key',
			'api_key' => 'Api Key',
			'mchid' => 'Mchid',
			'ums_mchid' => 'Ums Mchid',
			'alipay_qrcode' => 'Alipay Qrcode',
			'if_wx_open' => 'If Wx Open',
			'wxpay_merchant_type' => 'Wxpay Merchant Type',
			'wechat_apiclient_cert' => 'Wechat Apiclient Cert',
			't_wx_mchid' => 'T Wx Mchid',
			'if_alipay_open' => 'If Alipay Open',
			'alipay_api_version' => 'Alipay Api Version',
			'partner' => 'Partner',
			'alipay_key' => 'Alipay Key',
			'appid' => 'Appid',
			'alipay_auth_pid' => 'Alipay Auth Pid',
			'alipay_auth_appid' => 'Alipay Auth Appid',
			'alipay_auth_token' => 'Alipay Auth Token',
			'alipay_auth_refresh_token' => 'Alipay Auth Refresh Token',
			'alipay_auth_time' => 'Alipay Auth Time',
			'alipay_auth_token_expires_in' => 'Alipay Auth Token Expires In',
			'alipay_auth_refresh_token_expires_in' => 'Alipay Auth Refresh Token Expires In',
			'wechat_template_id' => 'Wechat Template',
			'wechat_thirdparty_authorizer_info' => 'Wechat Thirdparty Authorizer Info',
			'wechat_thirdparty_authorizer_refresh_token' => 'Wechat Thirdparty Authorizer Refresh Token',
			'wechat_thirdparty_authorizer_appid' => 'Wechat Thirdparty Authorizer Appid',
			'wechat_thirdparty_authorizer_if_auth' => 'Wechat Thirdparty Authorizer If Auth',
			'wechat_thirdparty_authorization_info' => 'Wechat Thirdparty Authorization Info',
			'wechat_thirdparty_authorizer_time' => 'Wechat Thirdparty Authorizer Time',
			'wechat_thirdparty_authorizer_cancel_time' => 'Wechat Thirdparty Authorizer Cancel Time',
			'wechat_thirdparty_authorizer_refresh_time' => 'Wechat Thirdparty Authorizer Refresh Time',
			'merchant_remark' => 'Merchant Remark',
			'auth_set' => 'Auth Set',
			'email_confirm_status' => 'Email Confirm Status',
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
		$criteria->compare('merchant_no',$this->merchant_no,true);
		$criteria->compare('wechat_merchant_no',$this->wechat_merchant_no,true);
		$criteria->compare('wq_mchid',$this->wq_mchid,true);
		$criteria->compare('agent_id',$this->agent_id);
		$criteria->compare('channel_id',$this->channel_id,true);
		$criteria->compare('account',$this->account,true);
		$criteria->compare('pwd',$this->pwd,true);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('wq_m_short_name',$this->wq_m_short_name,true);
		$criteria->compare('wq_m_type',$this->wq_m_type);
		$criteria->compare('charge_type',$this->charge_type);
		$criteria->compare('wq_m_name',$this->wq_m_name,true);
		$criteria->compare('wq_m_industry',$this->wq_m_industry,true);
		$criteria->compare('wq_m_address',$this->wq_m_address,true);
		$criteria->compare('wq_m_business_license_no',$this->wq_m_business_license_no,true);
		$criteria->compare('wq_m_business_license',$this->wq_m_business_license,true);
		$criteria->compare('wq_m_organization_code',$this->wq_m_organization_code,true);
		$criteria->compare('wq_m_organization',$this->wq_m_organization,true);
		$criteria->compare('wq_m_legal_person_name',$this->wq_m_legal_person_name,true);
		$criteria->compare('wq_m_legal_person_id',$this->wq_m_legal_person_id,true);
		$criteria->compare('wq_m_legal_person_positive',$this->wq_m_legal_person_positive,true);
		$criteria->compare('wq_m_legal_person_opposite',$this->wq_m_legal_person_opposite,true);
		$criteria->compare('wq_m_contacts_name',$this->wq_m_contacts_name,true);
		$criteria->compare('wq_m_contacts_phone',$this->wq_m_contacts_phone,true);
		$criteria->compare('wq_m_verify_status',$this->wq_m_verify_status);
		$criteria->compare('wq_m_verify_pass_time',$this->wq_m_verify_pass_time,true);
		$criteria->compare('wq_m_reject_remark',$this->wq_m_reject_remark,true);
		$criteria->compare('wx_name',$this->wx_name,true);
		$criteria->compare('seller_email',$this->seller_email,true);
		$criteria->compare('key',$this->key,true);
		$criteria->compare('create_time',$this->create_time,true);
		$criteria->compare('last_time',$this->last_time,true);
		$criteria->compare('status',$this->status);
		$criteria->compare('flag',$this->flag);
		$criteria->compare('alipay_code',$this->alipay_code,true);
		$criteria->compare('verify_status',$this->verify_status);
		$criteria->compare('wechat_verify_status',$this->wechat_verify_status);
		$criteria->compare('wechat_verify_status_submit_time',$this->wechat_verify_status_submit_time,true);
		$criteria->compare('wechat_verify_status_auditpass_time',$this->wechat_verify_status_auditpass_time,true);
		$criteria->compare('wechat_verify_status_verify_time',$this->wechat_verify_status_verify_time,true);
		$criteria->compare('wechat_verify_status_sign_time',$this->wechat_verify_status_sign_time,true);
		$criteria->compare('wechat_verify_status_reject_time',$this->wechat_verify_status_reject_time,true);
		$criteria->compare('remark',$this->remark,true);
		$criteria->compare('msg_num',$this->msg_num);
		$criteria->compare('if_stored',$this->if_stored);
		$criteria->compare('points_rule',$this->points_rule);
		$criteria->compare('merchant_number',$this->merchant_number,true);
		$criteria->compare('gj_start_time',$this->gj_start_time,true);
		$criteria->compare('gj_end_time',$this->gj_end_time,true);
		$criteria->compare('gj_product_id',$this->gj_product_id);
		$criteria->compare('yx_open_time',$this->yx_open_time,true);
		$criteria->compare('if_tryout',$this->if_tryout);
		$criteria->compare('tryout_status',$this->tryout_status);
		$criteria->compare('gj_open_status',$this->gj_open_status);
		$criteria->compare('encrypt_id',$this->encrypt_id,true);
		$criteria->compare('wechat_id',$this->wechat_id,true);
		$criteria->compare('wechat',$this->wechat,true);
		$criteria->compare('wechat_type',$this->wechat_type);
		$criteria->compare('wechat_qrcode',$this->wechat_qrcode,true);
		$criteria->compare('wechat_account',$this->wechat_account,true);
		$criteria->compare('wechat_appsecret',$this->wechat_appsecret,true);
		$criteria->compare('wechat_subscription_appsecret',$this->wechat_subscription_appsecret,true);
		$criteria->compare('wechat_key',$this->wechat_key,true);
		$criteria->compare('wechat_mchid',$this->wechat_mchid,true);
		$criteria->compare('t_wx_appid',$this->t_wx_appid,true);
		$criteria->compare('wechat_interface_url',$this->wechat_interface_url,true);
		$criteria->compare('wechat_token',$this->wechat_token,true);
		$criteria->compare('wechat_encodingaeskey',$this->wechat_encodingaeskey,true);
		$criteria->compare('wechat_encrypt_type',$this->wechat_encrypt_type);
		$criteria->compare('operator_refund_time',$this->operator_refund_time);
		$criteria->compare('wechat_apiclient_key',$this->wechat_apiclient_key,true);
		$criteria->compare('wechat_appid',$this->wechat_appid,true);
		$criteria->compare('wechat_subscription_appid',$this->wechat_subscription_appid,true);
		$criteria->compare('dzoperator_refund_time',$this->dzoperator_refund_time);
		$criteria->compare('access_token_json',$this->access_token_json,true);
		$criteria->compare('jsapi_ticket_json',$this->jsapi_ticket_json,true);
		$criteria->compare('fuwu_name',$this->fuwu_name,true);
		$criteria->compare('wechat_name',$this->wechat_name,true);
		$criteria->compare('ums_3des_key',$this->ums_3des_key,true);
		$criteria->compare('api_key',$this->api_key,true);
		$criteria->compare('mchid',$this->mchid,true);
		$criteria->compare('ums_mchid',$this->ums_mchid,true);
		$criteria->compare('alipay_qrcode',$this->alipay_qrcode,true);
		$criteria->compare('if_wx_open',$this->if_wx_open);
		$criteria->compare('wxpay_merchant_type',$this->wxpay_merchant_type);
		$criteria->compare('wechat_apiclient_cert',$this->wechat_apiclient_cert,true);
		$criteria->compare('t_wx_mchid',$this->t_wx_mchid,true);
		$criteria->compare('if_alipay_open',$this->if_alipay_open);
		$criteria->compare('alipay_api_version',$this->alipay_api_version);
		$criteria->compare('partner',$this->partner,true);
		$criteria->compare('alipay_key',$this->alipay_key,true);
		$criteria->compare('appid',$this->appid,true);
		$criteria->compare('alipay_auth_pid',$this->alipay_auth_pid,true);
		$criteria->compare('alipay_auth_appid',$this->alipay_auth_appid,true);
		$criteria->compare('alipay_auth_token',$this->alipay_auth_token,true);
		$criteria->compare('alipay_auth_refresh_token',$this->alipay_auth_refresh_token,true);
		$criteria->compare('alipay_auth_time',$this->alipay_auth_time,true);
		$criteria->compare('alipay_auth_token_expires_in',$this->alipay_auth_token_expires_in,true);
		$criteria->compare('alipay_auth_refresh_token_expires_in',$this->alipay_auth_refresh_token_expires_in,true);
		$criteria->compare('wechat_template_id',$this->wechat_template_id,true);
		$criteria->compare('wechat_thirdparty_authorizer_info',$this->wechat_thirdparty_authorizer_info,true);
		$criteria->compare('wechat_thirdparty_authorizer_refresh_token',$this->wechat_thirdparty_authorizer_refresh_token,true);
		$criteria->compare('wechat_thirdparty_authorizer_appid',$this->wechat_thirdparty_authorizer_appid,true);
		$criteria->compare('wechat_thirdparty_authorizer_if_auth',$this->wechat_thirdparty_authorizer_if_auth);
		$criteria->compare('wechat_thirdparty_authorization_info',$this->wechat_thirdparty_authorization_info,true);
		$criteria->compare('wechat_thirdparty_authorizer_time',$this->wechat_thirdparty_authorizer_time,true);
		$criteria->compare('wechat_thirdparty_authorizer_cancel_time',$this->wechat_thirdparty_authorizer_cancel_time,true);
		$criteria->compare('wechat_thirdparty_authorizer_refresh_time',$this->wechat_thirdparty_authorizer_refresh_time,true);
		$criteria->compare('merchant_remark',$this->merchant_remark,true);
		$criteria->compare('auth_set',$this->auth_set,true);
		$criteria->compare('email_confirm_status',$this->email_confirm_status);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Merchant the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
