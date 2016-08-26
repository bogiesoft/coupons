<?php
/**
 * Created by PhpStorm.
 * User: sundi
 * Date: 2016/6/23
 * Time: 14:27
 */
include_once(dirname(__FILE__).'/../mainClass.php');

//小区类
class CommunityC extends mainClass
{
    /**
     * @param $info
     * @param $merchant_id
     * @return string
     * 添加小区
     */
    public function addCommunity($info,$merchant_id)
    {
        $community_name = array();
        $name = $info['name'];
        $tel = $info['tel'];
        $address = $info['address'];
        $remark = $info['remark'];

        $result_community = json_decode($this->getCommunityList($merchant_id),true);
        foreach($result_community['data'] as $k=>$v){
            $community_name[]=$v['name'];
        }
        try {
            //判断小区名称是否为空
            if(!isset($name) || empty($name)){
                throw new Exception('info_name_miss');
            }

            //判断小区名称是否在2-15个字之间
            if(mb_strlen($name)<2){
                throw new Exception('info_name_short');
            }
            if(mb_strlen($name)>15){
                throw new Exception('info_name_long');
            }
            //判断小区是否重名
            $ck_community_name = in_array($name,$community_name);
            if($ck_community_name){
                throw new Exception('info_name_same');
            }
            //判断联系方式是否为空
            if(!isset($tel) || empty($tel)){
                throw new Exception('info_tel_miss');
            }

            //判断小区地址是否为空
            if(!isset($address) || empty($address)){
                throw new Exception('info_address_miss');
            }

            //判断联系方式是否在6-11个数字之间
            if(is_numeric($tel)){
                if(mb_strlen($tel)<6){
                    throw new Exception('info_tel_short');
                }
                if(mb_strlen($tel)>11){
                    throw new Exception('info_tel_format');
                }
            }else{
                throw new Exception('info_tel_long');
            }

            $transaction = Yii::app()->db->beginTransaction();
            $model = new Community();

            //保存数据
            $model['merchant_id'] = $merchant_id;
            $model['name'] = $name;
            $model['tel'] = $tel;
            $model['address'] = $address;
            $model['remark'] = $remark;
            $model['create_time'] = date('Y-m-d H:i:s', time());
            $model['last_time'] = date('Y-m-d H:i:s', time());
            if ($model->save()) {
                $transaction->commit();
                $result['status'] = APPLY_CLASS_SUCCESS; //状态码
                $result['errMsg'] = ''; //错误信息
                $result['data'] = array('id' => $model->id);
            } else {
                $transaction->rollBack();
                $result['status'] = ERROR_DATA_BASE_ADD;
                throw new Exception('error_database_add');
            }

        } catch (Exception $e) {
            $result['status'] = isset($result['status']) ? $result['status'] : ERROR_EXCEPTION;
            $result['errMsg'] = $e->getMessage(); //错误信息
        }
        return json_encode($result);
    }

    /**
     * @param $name
     * @return CDbDataReader|mixed|string
     *
     * 验证小区名称是否重复
     */
    public function countName($name,$merchant_id)
    {
        $criteria = new CDbCriteria();
        $criteria->addCondition("flag = :flag");
        $criteria->addCondition("merchant_id = :merchant_id");
        $criteria->addCondition("name = :name");
        $criteria->params[':flag'] = FLAG_NO;
        $criteria->params[':merchant_id'] = $merchant_id;
        $criteria->params[':name'] = $name;

        $date = Community::model()->count($criteria);
        return $date;
    }

    /**
     * 获取小区列表
     */
    public function getCommunityList($merchant_id){
        $result = array();
        try {
            //分页
            $criteria = new CDbCriteria();
            $criteria->addCondition("flag = :flag");
            $criteria->addCondition("merchant_id = :merchant_id");
            $criteria->params[':flag'] = FLAG_NO;
            $criteria->params[':merchant_id'] = $merchant_id;
            $criteria->order = "create_time desc";

            $pages = new CPagination(Community::model()->count($criteria));
            $pages->pageSize = Yii::app()->params['perPage'];
            $pages->applyLimit($criteria);

            $community = Community::model()->findAll($criteria);

            $data = array();
            if (!empty($community)) {
                foreach ($community as $k => $v) {
                    $data[$k]['id'] = $v -> id;
                    $data[$k]['name'] = $v -> name;
                    $data[$k]['tel'] = $v -> tel;
                    $data[$k]['address'] = $v -> address;
                    $data[$k]['remark'] = $v -> remark;
                }
            }
            $result['status'] = APPLY_CLASS_SUCCESS;
            $result['data'] = $data;
        } catch (Exception $e) {
            $result['status'] = isset($result['status']) ? $result['status'] : APPLY_CLASS_FAIL;
            $result['errMsg'] = $e->getMessage(); //错误信息
        }

        $this->page = $pages;
        return json_encode($result);
    }

    /**
     * 获取全部小区列表
     */
    public function getCommunityAllList($merchant_id){
        $result = array();
        try {
            //分页
            $criteria = new CDbCriteria();
            $criteria->addCondition("flag = :flag");
            $criteria->addCondition("merchant_id = :merchant_id");
            $criteria->params[':flag'] = FLAG_NO;
            $criteria->params[':merchant_id'] = $merchant_id;
            $criteria->order = "create_time desc";

            $community = Community::model()->findAll($criteria);

            $data = array();
            if (!empty($community)) {
                foreach ($community as $k => $v) {
                    $data[$k]['id'] = $v -> id;
                    $data[$k]['name'] = $v -> name;
                    $data[$k]['tel'] = $v -> tel;
                    $data[$k]['address'] = $v -> address;
                    $data[$k]['remark'] = $v -> remark;
                }
            }
            $result['status'] = APPLY_CLASS_SUCCESS;
            $result['data'] = $data;
        } catch (Exception $e) {
            $result['status'] = isset($result['status']) ? $result['status'] : APPLY_CLASS_FAIL;
            $result['errMsg'] = $e->getMessage(); //错误信息
        }

        return json_encode($result);
    }

    /**
     * @param $info
     * @param $merchant_id
     * 编辑小区
     */
    public function editCommunity($info,$id){
        $name = $info['name'];
        $tel = $info['tel'];
        $address = $info['address'];
        $remark = $info['remark'];
        try {
            //判断小区名称是否为空
            if(!isset($name) || empty($name)){
                throw new Exception('info_name_miss');
            }

            //判断小区名称是否在2-15个字之间
            if(mb_strlen($name)<2){
                throw new Exception('info_name_short');
            }
            if(mb_strlen($name)>15){
                throw new Exception('info_name_long');
            }

            //判断联系方式是否为空
            if(!isset($tel) || empty($tel)){
                throw new Exception('info_tel_miss');
            }

            //判断联系方式是否在6-11个数字之间
            if(is_numeric($tel)){
                if(mb_strlen($tel)<6){
                    throw new Exception('info_tel_short');
                }
                if(mb_strlen($tel)>11){
                    throw new Exception('info_tel_format');
                }
            }else{
                throw new Exception('info_tel_long');
            }

            //判断小区地址是否为空
            if(!isset($address) || empty($address)){
                throw new Exception('info_address_miss');
            }

            $transaction = Yii::app()->db->beginTransaction();
            $model = Community::model()->findByPk($id);

            //保存数据
            $model['name'] = $name;
            $model['tel'] = $tel;
            $model['address'] = $address;
            $model['remark'] = $remark;
            $model['last_time'] = date('Y-m-d H:i:s', time());
            if ($model->save()) {
                $transaction->commit();
                $result['status'] = APPLY_CLASS_SUCCESS; //状态码
                $result['errMsg'] = ''; //错误信息
                $result['data'] = array('id' => $model->id);
            } else {
                $transaction->rollBack();
                $result['status'] = ERROR_DATA_BASE_ADD;
                throw new Exception('error_database_add');
            }

        } catch (Exception $e) {
            $result['status'] = isset($result['status']) ? $result['status'] : ERROR_EXCEPTION;
            $result['errMsg'] = $e->getMessage(); //错误信息
        }
        return json_encode($result);
    }

    /**
     * @param $community_id
     * 获取小区详情
     */
    public function getCommunityInfo($community_id){

        $result = array();
        $community = '';
        try {
            $community = Community::model()->findByPk($community_id);

            if (!empty($community)) {
                $result ['status'] = APPLY_CLASS_SUCCESS; // 状态码
                $result ['id'] = $community->id;
                $result ['errMsg'] = '';
            } else {
                $result ['status'] = APPLY_CLASS_FAIL; // 状态码
                throw new Exception('数据保存失败');
            }
        } catch (Exception $e) {
            $result['status'] = isset($result['status']) ? $result['status'] : ERROR_EXCEPTION;
            $result['errMsg'] = $e->getMessage(); //错误信息
        }
        return $community;
    }

    /**
     * @param $merchant_id
     * 删除小区
     */
    public function delCommunity($community_id){

        $result = array();
        $model = Community::model()->findByPk($community_id);
        if ($model->flag == FLAG_NO) {
            $model->flag = FLAG_YES;
            if ($model->save()) {
                $result ['status'] = APPLY_CLASS_SUCCESS;
                $result ['errMsg'] = '';
            } else {
                $result ['status'] = APPLY_CLASS_FAIL; // 状态码
                $result ['errMsg'] = '数据保存失败'; // 错误信息
            }
        }
        return json_encode($result);
    }

    /**
     * @param $community_id
     * 获取小区电费设置
     */
    public function getCommunityEfee($community_id){

        $result = array();
        $community = '';
        try {
        $community = Community::model()->findByPk($community_id);

            if (!empty($community)) {
                $result ['status'] = APPLY_CLASS_SUCCESS; // 状态码
                $result ['id'] = $community->id;
                $result ['errMsg'] = '';
            } else {
                $result ['status'] = APPLY_CLASS_FAIL; // 状态码
                throw new Exception('数据保存失败');
            }
        } catch (Exception $e) {
            $result['status'] = isset($result['status']) ? $result['status'] : ERROR_EXCEPTION;
            $result['errMsg'] = $e->getMessage(); //错误信息
        }
        return $community -> electricity_fee_set;

    }

    /**
     * @param $community_id
     * 获取小区水费设置
     */
    public function getCommunityWfee($community_id){

        $result = array();
        $community = '';
        try {
            $community = Community::model()->findByPk($community_id);

            if (!empty($community)) {
                $result ['status'] = APPLY_CLASS_SUCCESS; // 状态码
                $result ['id'] = $community->id;
                $result ['errMsg'] = '';
            } else {
                $result ['status'] = APPLY_CLASS_FAIL; // 状态码
                throw new Exception('数据保存失败');
            }
        } catch (Exception $e) {
            $result['status'] = isset($result['status']) ? $result['status'] : ERROR_EXCEPTION;
            $result['errMsg'] = $e->getMessage(); //错误信息
        }
        return $community -> water_fee_set;

    }

    /**
     * @param $community_id
     * @return mixed
     * 获取小区停车费设置
     */
    public function getCommunityParkingfee($community_id){

        $result = array();
        $community = '';
        try {
            $community = Community::model()->findByPk($community_id);

            if (!empty($community)) {
                $result ['status'] = APPLY_CLASS_SUCCESS; // 状态码
                $result ['id'] = $community->id;
                $result ['errMsg'] = '';
            } else {
                $result ['status'] = APPLY_CLASS_FAIL; // 状态码
                throw new Exception('数据保存失败');
            }
        } catch (Exception $e) {
            $result['status'] = isset($result['status']) ? $result['status'] : ERROR_EXCEPTION;
            $result['errMsg'] = $e->getMessage(); //错误信息
        }
        return $community -> parking_fee_set;
    }

    /**
     * @param $community_id
     * @return mixed
     * 获取小区物业费设置
     */
    public function getCommunityPropertyfee($community_id){

        $result = array();
        $community = '';
        try {
            $community = Community::model()->findByPk($community_id);

            if (!empty($community)) {
                $result ['status'] = APPLY_CLASS_SUCCESS; // 状态码
                $result ['id'] = $community->id;
                $result ['errMsg'] = '';
            } else {
                $result ['status'] = APPLY_CLASS_FAIL; // 状态码
                throw new Exception('数据保存失败');
            }
        } catch (Exception $e) {
            $result['status'] = isset($result['status']) ? $result['status'] : ERROR_EXCEPTION;
            $result['errMsg'] = $e->getMessage(); //错误信息
        }
        return $community -> property_fee_set;
    }

    /**
     * @param $info
     * @param $community_id
     * 修改小区电费设置
     */
    public function editCommunityEfee($info,$community_id){
        $result = array();
        $arr = array();
        try {
            //验证参数
            if (!isset($community_id) && empty($community_id)) {
                $result['status'] = ERROR_PARAMETER_MISS;
                throw new Exception("参数community_id缺失");
            }

            if ($info['type'] == COMMUNITY_ELECTRICITY_FEE_SET_NODAYPARTING){
                $time = $info['time'];
                $price = $info['price'];

                if (!isset($time) || empty($time)){
                    throw new Exception('time_miss');
                }
                if (!isset($price) || empty($price)){
                    throw new Exception('price_miss');
                }

                $arr['type'] = COMMUNITY_ELECTRICITY_FEE_SET_NODAYPARTING;
                $arr['community_id'] = $community_id;
                $arr['time'] = $time;
                $arr['price'] = $price;
            }else{
                $peak_time = $info['peak_time'];
                $trough_time = $info['trough_time'];
                $peak_price = $info['peak_price'];
                $trough_price = $info['trough_price'];

                if (!isset($peak_time) || empty($peak_time)){
                    throw new Exception('peak_time_miss');
                }
                if (!isset($trough_time) || empty($trough_time)){
                    throw new Exception('trough_time_miss');
                }
                if (!isset($peak_price) || empty($peak_price)){
                    throw new Exception('peak_price_miss');
                }
                if (!isset($trough_price) || empty($trough_price)){
                    throw new Exception('trough_price_miss');
                }

                $arr['type'] = COMMUNITY_ELECTRICITY_FEE_SET_DAYPARTING;
                $arr['community_id'] = $community_id;
                $arr['peak_time'] = $peak_time;
                $arr['trough_time'] = $trough_time;
                $arr['peak_price'] = $peak_price;
                $arr['trough_price'] = $trough_price;
            }

            //保存数据
            $model = Community::model()->findByPk($community_id);
            $model->electricity_fee_set = json_encode($arr);
            $model->last_time = date('Y-m-d H:i:s');

            if ($model->save()) {
                $result ['status'] = APPLY_CLASS_SUCCESS; // 状态码
                $result ['id'] = $model->id;
                $result ['errMsg'] = '';
            } else {
                $result ['status'] = APPLY_CLASS_FAIL; // 状态码
                throw new Exception('数据保存失败');
            }

        } catch (Exception $e) {
            $result['status'] = isset($result['status']) ? $result['status'] : ERROR_EXCEPTION;
            $result['errMsg'] = $e->getMessage(); //错误信息
        }
        return json_encode($result);
    }

    /**
     * @param $info
     * @param $community_id
     * 修改小区水费设置
     */
    public function editCommunityWfee($info,$community_id){

        $result = array();
        $arr = array();
        try {
            //验证参数
            if (!isset($community_id) && empty($community_id)) {
                $result['status'] = ERROR_PARAMETER_MISS;
                throw new Exception("参数community_id缺失");
            }
            
                $time = $info['time'];
                $price = $info['price'];

                if (!isset($time) || empty($time)){
                    throw new Exception('time_miss');
                }
                if (!isset($price) || empty($price)){
                    throw new Exception('price_miss');
                }
            
                $arr['community_id'] = $community_id;
                $arr['time'] = $time;
                $arr['price'] = $price;
            

            //保存数据
            $model = Community::model()->findByPk($community_id);
            $model->water_fee_set = json_encode($arr);
            $model->last_time = date('Y-m-d H:i:s');

            if ($model->save()) {
                $result ['status'] = APPLY_CLASS_SUCCESS; // 状态码
                $result ['id'] = $model->id;
                $result ['errMsg'] = '';
            } else {
                $result ['status'] = APPLY_CLASS_FAIL; // 状态码
                throw new Exception('数据保存失败');
            }

        } catch (Exception $e) {
            $result['status'] = isset($result['status']) ? $result['status'] : ERROR_EXCEPTION;
            $result['errMsg'] = $e->getMessage(); //错误信息
        }
        return json_encode($result);
    }

    /**
     * @param $info
     * @param $community_id
     * 修改小区停车费设置
     */
    public function editCommunityParkingfee($info,$community_id){

        $result = array();
        $arr = array();
        try {
            //验证参数
            if (!isset($community_id) && empty($community_id)) {
                $result['status'] = ERROR_PARAMETER_MISS;
                throw new Exception("参数community_id缺失");
            }

            //查找小区信息
            $model = Community::model()->findByPk($community_id);

            if ($info['parking_type'] == COMMUNITY_PARKING_FEE_SET_OVERGROUND){
                $over_day_price = $info['over_day_price'];
                $over_half_year_price = $info['over_half_year_price'];
                $over_year_price = $info['over_year_price'];

                if (!isset($over_day_price) || empty($over_day_price)){
                    throw new Exception('over_day_price_miss');
                }
                if (!isset($over_half_year_price) || empty($over_half_year_price)){
                    throw new Exception('over_half_year_price_miss');
                }
                if (!isset($over_year_price) || empty($over_year_price)){
                    throw new Exception('over_year_price_miss');
                }

                if (!empty($model->parking_fee_set)) {
                    $arr = json_decode($model->parking_fee_set, true);
                    $arr['over_day_price'] = $over_day_price;
                    $arr['over_half_year_price'] = $over_half_year_price;
                    $arr['over_year_price'] = $over_year_price;
                } else {
                    $arr['community_id'] = $community_id;
                    $arr['over_day_price'] = $over_day_price;
                    $arr['over_half_year_price'] = $over_half_year_price;
                    $arr['over_year_price'] = $over_year_price;
                }
            }else{
                $under_half_year_price = $info['under_half_year_price'];
                $under_year_price = $info['under_year_price'];

                if (!isset($under_half_year_price) || empty($under_half_year_price)){
                    throw new Exception('under_half_year_price_miss');
                }
                if (!isset($under_year_price) || empty($under_year_price)){
                    throw new Exception('under_year_price_miss');
                }

                if (!empty($model->parking_fee_set)) {
                    $arr = json_decode($model->parking_fee_set, true);
                    $arr['under_half_year_price'] = $under_half_year_price;
                    $arr['under_year_price'] = $under_year_price;
                } else {
                    $arr['community_id'] = $community_id;
                    $arr['under_half_year_price'] = $under_half_year_price;
                    $arr['under_year_price'] = $under_year_price;
                }
            }

            //保存数据

            $model->parking_fee_set = json_encode($arr);
            $model->last_time = date('Y-m-d H:i:s');

            if ($model->save()) {
                $result ['status'] = APPLY_CLASS_SUCCESS; // 状态码
                $result ['id'] = $model->id;
                $result ['errMsg'] = '';
            } else {
                $result ['status'] = APPLY_CLASS_FAIL; // 状态码
                throw new Exception('数据保存失败');
            }

        } catch (Exception $e) {
            $result['status'] = isset($result['status']) ? $result['status'] : ERROR_EXCEPTION;
            $result['errMsg'] = $e->getMessage(); //错误信息
        }
        return json_encode($result);
    }

    /**
     * @param $info
     * @param $community_id
     * 修改小区物业费设置
     */
    public function editCommunityPropertyfee($info,$community_id){

        $result = array();
        try {
            //验证参数
            if (!isset($community_id) && empty($community_id)) {
                $result['status'] = ERROR_PARAMETER_MISS;
                throw new Exception("参数community_id缺失");
            }

            $year_price = $info['year_price'];

            if (!isset($year_price) || empty($year_price)){
                throw new Exception('year_price_miss');
            }

            $arr['community_id'] = $community_id;
            $arr['year_price'] = $year_price;

            //保存数据
            $model = Community::model()->findByPk($community_id);
            $model->property_fee_set = json_encode($arr);
            $model->last_time = date('Y-m-d H:i:s');

            if ($model->save()) {
                $result ['status'] = APPLY_CLASS_SUCCESS; // 状态码
                $result ['id'] = $model->id;
                $result ['errMsg'] = '';
            } else {
                $result ['status'] = APPLY_CLASS_FAIL; // 状态码
                throw new Exception('数据保存失败');
            }

        } catch (Exception $e) {
            $result['status'] = isset($result['status']) ? $result['status'] : ERROR_EXCEPTION;
            $result['errMsg'] = $e->getMessage(); //错误信息
        }
        return json_encode($result);
    }
}