<?php
include_once(dirname(__FILE__) . '/../mainClass.php');

/**
 * 用户类
 * 姚小磊
 * 2016/7/15
 */
class UserClass extends mainClass
{
    public $page = null;

    /** 获取所有用户信息
     * @param $user_id 所有用户id（数组）
     * @return mixed
     */
    public function getAllUserLists($user_id)
    {
        $res_all_user = User::model()->findAllByPk($user_id);

        return $res_all_user;
    }

    /** 获取分组下所有用户的open_id
     * @param $group_id 分组id
     * @return mixed
     */
    public function getGroupAllUserOpenId($group_id)
    {
        $all_group_user = Group::model()->findAll('group_id = :group_id and flag = :flag', array(
            ':group_id' => $group_id,
            ':flag' => FLAG_NO
        ));

        foreach ($all_group_user as $v) {
            if (!empty($v['wechat_id'])) {
                $openid_list[] = $v['wechat_id'];
            }
        }

        return $openid_list;
    }

    /** 领取红包/优惠券
     * @param $user_id  = '' 用户id
     * @param $coupons_id 优惠券id
     * @param $get_openid = '' 领券人open_id
     * @param string $marketing_activity_type 营销活动类型
     * @param string $marketing_activity_id 营销活动id
     * @param string $wechat_code = '' 微信的code码
     * @return string
     */
    public function newReceiveCoupons($user_id = '', $coupons_id, $get_openid = '', $marketing_activity_type = '', $marketing_activity_id = '', $wechat_code = '')
    {
        $result = array();

        //创建一个数据库事务
        //$transcation = Yii::app()->db->beginTransaction();
        try {
            $coupons = Coupons::model()->findByPk($coupons_id);

            $model = new UserCoupons();
            $model['user_id'] = $user_id;
            $model['coupons_id'] = $coupons_id;
            $model['get_openid'] = $get_openid;
            $model['status'] = COUPONS_USE_STATUS_UNUSE;
            $model['wechat_code'] = $wechat_code;

            if (!empty($marketing_activity_type) && ($marketing_activity_type == MARKETING_ACTIVITY_TYPE_DMALL_SDLJ || $marketing_activity_type == MARKETING_ACTIVITY_TYPE_DMALL_ZFL)) {
                $mallActivity = MallActivity::model()->findByPk($marketing_activity_id);
                $mallActivity->receive_num++;
                if ($mallActivity->update()) {

                } else {
                    throw new Exception('营销活动更新失败');
                }
            }

            $model['marketing_activity_type'] = $marketing_activity_type;
            $model['marketing_activity_id'] = $marketing_activity_id;

            if ($coupons['time_type'] == VALID_TIME_TYPE_FIXED) {
                $model['start_time'] = $coupons['start_time'];
                $model['end_time'] = $coupons['end_time'];
            } else {
                $star_day = '+' . $coupons['start_days'] . ' ' . 'day';
                $effective_days = '+' . $coupons['effective_days'] . ' ' . 'day';

                $start_time = date('Y-m-d H:i:s', strtotime($star_day));
                $model['start_time'] = $start_time;
                $model['end_time'] = date("Y-m-d h:i:s", strtotime($start_time . $effective_days));
            }

            if ($coupons['type'] == COUPON_TYPE_CASH) {
                if ($coupons['money_type'] == FACE_VALUE_TYPE_RANDOM) {
                    $value_arr = explode(",", $coupons['money_random']);
                    $min_value = $value_arr[1];
                    $max_value = $value_arr[2];
                    $model ['money'] = rand($min_value, $max_value);
                } else {
                    $model['money'] = $coupons['money'];
                }
            }

            $model['create_time'] = date('Y-m-d H:i:s');
            $model['wechat_coupons_id'] = $coupons['card_id'];
            //创建优惠券核销码12位
            $code = $this->getRandChar(12);
            $usercode = UserCoupons::model()->find('code =:code', array(
                ':code' => $code
            ));
            while (!empty($usercode)) {
                $code = $this->getRandChar(12);
                $usercode = UserCoupons::model()->find('code =:code', array(
                    ':code' => $code
                ));
            }
            $model['code'] = $code;

            $coupons_model = Coupons::model()->findByPk($coupons_id);
            $coupons_model['get_num'] = $coupons_model['get_num'] + 1;

            if ($model->save()) {
                if ($coupons_model->update()) {
                    $result['data'] = $model['id'];
                    $result['status'] = ERROR_NONE; //状态码
                    $result['errMsg'] = ''; //错误信息
                    //$transcation->commit(); //数据提交
                } else {
                    $result['status'] = ERROR_SAVE_FAIL; //状态码
                    $result['errMsg'] = '数据保存失败'; //错误信息
                    //$transcation->rollback();
                }
            } else {
                $result['status'] = ERROR_SAVE_FAIL; //状态码
                $result['errMsg'] = '数据保存失败'; //错误信息
                //$transcation->rollback();
            }

        } catch (Exception $e) {
            $result['status'] = isset($result['status']) ? $result['status'] : ERROR_EXCEPTION;
            $result['errMsg'] = $e->getMessage(); //错误信息
            //$transcation->rollback();
        }

        return json_encode($result);
    }

    /** 判断该用户是否可以领取红包
     * @param $user_id 用户id
     * @param $coupons_id 优惠券id
     * @return string
     */
    public function judgeUserCoupons($user_id, $coupons_id)
    {
        $result = array();
        try {
            $time = date('Y-m-d H:i:s');
            $send_num = UserCoupons::model()->count('coupons_id = :coupons_id', array(
                ':coupons_id' => $coupons_id
            ));
            $coupons = Coupons::model()->findByPk($coupons_id);
            $receive_num = UserCoupons::model()->count('coupons_id = :coupons_id and user_id = :user_id', array(
                ':coupons_id' => $coupons_id,
                ':user_id' => $user_id
            ));

            if ($coupons['flag'] == FLAG_YES) {
                $result['status'] = ERROR_PARAMETER_FORMAT;
                throw new Exception('优惠券发放已结束');
            }
            if ($coupons['time_type'] == VALID_TIME_TYPE_FIXED && $coupons['end_time'] < $time) {
                $result['status'] = ERROR_PARAMETER_FORMAT;
                throw new Exception('优惠券发放已结束');
            }
            if ($send_num > $coupons['num']) {
                $result['status'] = ERROR_PARAMETER_FORMAT;
                throw new Exception('优惠券已被领完');
            }
            if ($receive_num >= $coupons['receive_num']) {
                $result['status'] = ERROR_PARAMETER_FORMAT;
                throw new Exception('优惠券已到上限');
            }

            $result['status'] = ERROR_NONE; //状态码
            $result['errMsg'] = ''; //错误信息

        } catch (Exception $e) {
            $result['status'] = isset($result['status']) ? $result['status'] : ERROR_EXCEPTION;
            $result['errMsg'] = $e->getMessage(); //错误信息
        }

        return json_encode($result);
    }

    /** 获取定长的随机字符串 首位不为零
     * @param $length 长度
     * @return null|string
     */
    private function getRandChar($length)
    {
        $str = null;
        $strPol = "012356789";
        $strPolnoZero = "12356789";
        $max = strlen($strPol) - 1;

        for ($i = 0; $i < $length; $i++) {
            if ($i == 0) {
                $str .= $strPolnoZero[rand(0, $max - 1)];
            } else {
                $str .= $strPol[rand(0, $max)]; //rand($min,$max)生成介于min和max两个数之间的一个随机整数
            }
        }
        return $str;
    }
}