<?php
include_once(dirname(__FILE__) . '/../mainClass.php');

/**
 * 精准营销管理类（活动列表、删除活动、编辑活动、启用活动、停止活动）
 * 姚小磊
 * 2016/7/15
 */
class MarketingClass extends mainClass
{
    public $page = null;

    /** 添加基础营销活动
     * @param $merchant_id 商户id
     * @param $info 营销活动信息
     */
    public function addMarketingActivity($merchant_id, $info)
    {

    }

    /** 编辑基础营销活动
     * @param $ma_id 营销活动id
     * @param $info 营销活动信息
     */
    public function editMarketingActivity($ma_id, $info)
    {

    }

    /** 添加精准营销活动
     * @param $merchant_id 商户id
     * @param $info 添加的活动数据 如下：
     * name:名字
     * type:活动类型
     * group_id:分组id
     * image_text_imageurl:封面图片
     * image_text_title:图文简介
     * coupon_id:优惠券id
     * send_channel:发送渠道
     * time_type:时间类型
     * start_time:开始时间
     * end_time:结束时间
     * status:活动状态
     * @return string
     */
    public function addMarketingActivityPrecision($merchant_id, $info)
    {
        try {
            $marketing = new MarketingActivity();

            $marketing->merchant_id = $merchant_id;
            $marketing->name = $info['name'];
            $marketing->type = $info['type'];
            $marketing->group_id = $info['group_id'];
            $marketing->image_text_imageurl = $info['image_text_imageurl'];
            $marketing->image_text_title = $info['image_text_title'];
            $marketing->coupon_id = $info['coupon_id'];
            $marketing->send_channel = $info['send_channel'];
            $marketing->time_type = $info['time_type'];
            $marketing->time_send_type = $info['time_send_type'];
            $marketing->start_time = $info['start_time'];
            $marketing->end_time = $info['end_time'];
            $marketing->status = $info['status'];
            $marketing->last_time = date('Y-m-d H:i:s', time());
            $marketing->create_time = date('Y-m-d H:i:s', time());

            if ($marketing->save()) {
                $result['status'] = ERROR_NONE;
                $result['data'] = $marketing->id;
            }
        } catch (Exception $e) {
            $result['status'] = isset($result['status']) ? $result['status'] : ERROR_EXCEPTION;
            $result['errMsg'] = $e->getMessage();
        }

        return json_encode($result);
    }

    /** 编辑精准营销活动
     * @param $marketing_id 活动id
     * @param $info 编辑的活动数据 如下：
     * name:名字
     * image_text_imageurl:封面图片
     * image_text_title:图文简介
     * coupon_id:优惠券id
     * send_channel:发送渠道
     * time_type:时间类型
     * start_time:开始时间
     * end_time:结束时间
     * status:活动状态
     * @return string
     */
    public function editMarketingActivityPrecision($marketing_id, $info)
    {
        try {
            $marketing = MarketingActivity::model()->findByPk($marketing_id);

            $marketing->name = $info['name'];
            $marketing->image_text_imageurl = $info['image_text_imageurl'];
            $marketing->image_text_title = $info['image_text_title'];
            $marketing->coupon_id = $info['coupon_id'];
            $marketing->send_channel = $info['send_channel'];
            $marketing->time_type = $info['time_type'];
            $marketing->time_send_type = $info['time_send_type'];
            $marketing->start_time = $info['start_time'];
            $marketing->end_time = $info['end_time'];
            $marketing->status = $info['status'];
            $marketing->last_time = date('Y-m-d H:i:s', time());

            if ($marketing->save()) {
                $result['status'] = ERROR_NONE;
                $result['data'] = $marketing->id;
            }
        } catch (Exception $e) {
            $result['status'] = isset($result['status']) ? $result['status'] : ERROR_EXCEPTION;
            $result['errMsg'] = $e->getMessage();
        }

        return json_encode($result);
    }

    /** 获取营销活动列表
     * @param $merchant_id 商户id
     * @param string $activity_name 活动名称
     * @param string $type 活动类型
     * @param string $status 活动状态
     * @return string
     */
    public function getMarketingActivityList($merchant_id, $activity_name = '', $type = '', $status = '')
    {
        $result = array();
        $data = array();
        try {
            $criteria = new CDbCriteria();
            $criteria->addCondition('flag=:flag');
            $criteria->params[':flag'] = FLAG_NO;
            $criteria->addCondition('merchant_id=:merchant_id');
            $criteria->params[':merchant_id'] = $merchant_id;

            if (!empty($activity_name)) {
                $criteria->addSearchCondition('name', $activity_name);
            }
            if (!empty($type)) {
                $criteria->addCondition('type=:type');
                $criteria->params[':type'] = $type;
            }
            if (!empty($status)) {
                $criteria->addCondition('status=:status');
                $criteria->params[':status'] = $status;
            }
            $criteria->order = 'last_time desc';
            //分页
            $pages = new CPagination(MarketingActivity::model()->count($criteria));
            $pages->pageSize = Yii::app()->params['perPage'];
            $pages->applyLimit($criteria);
            $this->page = $pages;

            $model = MarketingActivity::model()->findAll($criteria);
            $coupons = Coupons::model()->findAll(array(
                'select' => array('id', 'pv'),
            ));
            foreach ($coupons as $v) {
                $coupons_attr = $v['attributes'];
                $coupons_pv[$coupons_attr["id"]] = $coupons_attr["pv"];
            }

            $cmd = Yii::app()->db->createCommand();
            $cmd->select('a.*');
            $cmd->from('(
                SELECT
                    activity_id, wechat_status, alipay_status
                FROM wq_send_log a
                WHERE a.flag = ' . FLAG_NO . '
                AND a.type = 1
                ORDER BY a.create_time DESC
                ) a');
            $cmd->group('a.activity_id');
            $send_logs = $cmd->queryAll();

            foreach ($send_logs as $v) {
                $send_status[$v['activity_id']]['wechat_status'] = $v['wechat_status'];
                $send_status[$v['activity_id']]['alipay_status'] = $v['alipay_status'];
            }

            if (!empty ($model)) {
                foreach ($model as $k => $v) {
                    $data ['list'] [$k] ['id'] = $v ['id'];
                    $data ['list'] [$k] ['name'] = $v ['name']; // 营销活动名称
                    $data ['list'] [$k] ['type'] = $v ['type']; // 营销活动类型
                    $data ['list'] [$k] ['time_type'] = $v ['time_type']; // 活动时间类型 1短期 2长期
                    $data ['list'] [$k] ['time_send_type'] = $v ['time_send_type']; // 活动时间类型 1短期 2长期
                    $data ['list'] [$k] ['start_time'] = $v ['start_time']; // 活动开始时间
                    $data ['list'] [$k] ['end_time'] = $v ['end_time']; // 活动结束时间
                    $data ['list'] [$k] ['condition'] = $v ['condition']; // 赠券满金额
                    $data ['list'] [$k] ['status'] = $v ['status']; // 活动状态 1未开始 2进行中 3已结束 4已停用
                    $data ['list'] [$k] ['create_time'] = $v ['create_time']; // 创建时间
                    $data ['list'] [$k] ['pv'] = $coupons_pv[$v ['coupon_id']]; // 优惠券浏览次数
                    $data['list'][$k]['send_status'] = 1; //默认成功

                    if ($v['send_channel'] == MARKETING_ACTIVITY_SEND_CHANNEL_WECHAT) { //发送渠道微信
                        if ($send_status[$v['id']]['wechat_status'] == 2) {
                            $data['list'][$k]['send_status'] = 1; //提交成功
                        } elseif ($send_status[$v['id']]['wechat_status'] == 3) {
                            $data['list'][$k]['send_status'] = 0; //提交失败
                        } elseif ($send_status[$v['id']]['wechat_status'] == 1) {
                            $data['list'][$k]['send_status'] = 2; //无需发送
                        } elseif (!isset($send_status[$v['id']]) || empty($send_status[$v['id']])) {
                            $data['list'][$k]['send_status'] = 2; //无需发送
                        }
                    } elseif ($v['send_channel'] == MARKETING_ACTIVITY_SEND_CHANNEL_ALIPAY) { //发送渠道支付宝
                        if ($send_status[$v['id']]['alipay_status'] == 2) {
                            $data['list'][$k]['send_status'] = 1; //提交成功
                        } elseif ($send_status[$v['id']]['alipay_status'] == 3) {
                            $data['list'][$k]['send_status'] = 0; //提交失败
                        } elseif ($send_status[$v['id']]['alipay_status'] == 1) {
                            $data['list'][$k]['send_status'] = 2; //无需发送
                        } elseif (!isset($send_status[$v['id']]) || empty($send_status[$v['id']])) {
                            $data['list'][$k]['send_status'] = 2; //无需发送
                        }
                    } elseif ($v['send_channel'] == MARKETING_ACTIVITY_SEND_CHANNEL_BOTH) { //同时
                        if ($send_status[$v['id']]['wechat_status'] == 2 && $send_status[$v['id']]['alipay_status'] == 2) {
                            $data['list'][$k]['send_status'] = 1; //提交成功
                        } elseif ($send_status[$v['id']]['wechat_status'] == 3 || $send_status[$v['id']]['alipay_status'] == 3) {
                            $data['list'][$k]['send_status'] = 0; //提交失败
                        } elseif ($send_status[$v['id']]['wechat_status'] == 1 || $send_status[$v['id']]['alipay_status'] == 1) {
                            $data['list'][$k]['send_status'] = 2; //无需发送
                        } elseif (!isset($send_status[$v['id']]) || empty($send_status[$v['id']])) {
                            $data['list'][$k]['send_status'] = 2; //无需发送
                        }
                    }
                }
                $result['status'] = ERROR_NONE;
                $result['data'] = $data;

                //查询优惠券的浏览次数
            } else {
                $result['status'] = ERROR_NO_DATA;
            }
        } catch (Exception $e) {
            $result['status'] = isset($result['status']) ? $result['status'] : ERROR_EXCEPTION;
            $result['errMsg'] = $e->getMessage();
        }
        return json_encode($result,true);
    }

    /** 获取营销活动对应的图文优惠券的显示信息
     * @param $merchant_id 商户id
     * @param string $activity_name 活动名称
     * @param string $type 活动类型
     * @return string
     */
    public function getMarketingCouponShowInfo($merchant_id, $type)
    {
        $result =array();
        try{
            $criteria = new CDbCriteria();
            $now = date('Y-m-d',time());
            $criteria->addCondition('flag=:flag');
            $criteria->params[':flag'] = FLAG_NO;
            $criteria->addCondition('merchant_id=:merchant_id');
            $criteria->params[':merchant_id'] = $merchant_id;
            $criteria->addCondition('type=:type');
            $criteria->params[':type'] = $type;
            $criteria->addCondition('status != :status1');
            $criteria->params[':status1'] = MARKETING_ACTIVITY_STATUS_END;
            $criteria->addCondition('status != :status2');
            $criteria->params[':status2'] = MARKETING_ACTIVITY_STATUS_STOP;
            $criteria->addCondition('time_type=:time_type');
            $criteria->params[':time_type'] = MARKETING_ACTIVITY_TIME_TYPE_PERIODICITY;
            $criteria->addcondition('time_send_type=:time_send_type');
            $criteria->params[':time_send_type']=MARKETING_ACTIVITY_SEND_TYPE_CRONTAB;
            $criteria->addCondition('start_time <= :now');
            $criteria->addCondition('end_time >= :now');
            $criteria->params[':now'] = $now;
            //查询符合条件的自定义周期活动
            $marketing_period = MarketingActivity::model()->find($criteria);


            $criteria1 = new CDbCriteria();
            $now = date('Y-m-d',time());
            $criteria1->addCondition('flag=:flag');
            $criteria1->params[':flag'] = FLAG_NO;
            $criteria1->addCondition('merchant_id=:merchant_id');
            $criteria1->params[':merchant_id'] = $merchant_id;
            $criteria1->addCondition('type=:type');
            $criteria1->params[':type'] = $type;
            $criteria1->addCondition('status != :status1');
            $criteria1->params[':status1'] = MARKETING_ACTIVITY_STATUS_END;
            $criteria1->addCondition('status != :status2');
            $criteria1->params[':status2'] = MARKETING_ACTIVITY_STATUS_STOP;
            $criteria1->addCondition('time_type=:time_type');
            $criteria1->params[':time_type'] = MARKETING_ACTIVITY_TIME_TYPE_PERIODICITY;
            $criteria1->addcondition('time_send_type=:time_send_type');
            $criteria1->params[':time_send_type']=MARKETING_ACTIVITY_SEND_TYPE_LONG;
            $criteria1->addCondition('start_time <= :now');
            $criteria1->params[':now'] = $now;
            //查询符合条件的长期活动
            $marketing_long = MarketingActivity::model()->find($criteria1);

            if(!empty($marketing_period) || !empty($marketing_long)){
                if(!empty($marketing_period)&&empty($marketing_long)){
                    $result['name'] = $marketing_period -> name;
                    $result['coupon_id'] = $marketing_period -> coupon_id;
                    $result['image_text_imageurl'] = $marketing_period -> image_text_imageurl;
                    $result['image_text_title'] = $marketing_period -> image_text_title;
                    $result['condition'] = $marketing_period -> condition;
                    $result['status'] = ERROR_NONE;
                }elseif(!empty($marketing_long)&&empty($marketing_period)){
                    $result['name'] = $marketing_long -> name;
                    $result['coupon_id'] = $marketing_long -> coupon_id;
                    $result['image_text_imageurl'] = $marketing_long -> image_text_imageurl;
                    $result['image_text_title'] = $marketing_long -> image_text_title;
                    $result['condition'] = $marketing_long -> condition;
                    $result['status'] = ERROR_NONE;
                }else{
                    //出现两个冲突的营销活动
                    $result['status'] = ERROR_DUPLICATE_DATA;
                }
            }else{
                $result['status'] = ERROR_NO_DATA;
            }
        }catch (Exception $e){
            $result['status'] = isset($result['status']) ? $result['status'] : ERROR_EXCEPTION;
            $result['errMsg'] = $e->getMessage();
        }
        return json_encode($result,true);
    }
    /** 删除营销活动
     * @param $ma_id 活动id
     * @return string
     */
    public function delMarketingActivity($ma_id)
    {
        $result = array();
        try {
            //修改为flag_yes状态
            $model = MarketingActivity::model()->findByPk($ma_id);
            $model->flag = FLAG_YES;
            if ($model->update()) {
                $result['status'] = ERROR_NONE;
            } else {
                $result['status'] = ERROR_SAVE_FAIL;
            }
        } catch (Exception $e) {
            $result['status'] = isset($result['status']) ? $result['status'] : ERROR_EXCEPTION;
            $result['errMsg'] = $e->getMessage();
        }
        return json_encode($result);
    }

    /** 编辑营销活动状态
     * @param $ma_id 营销活动id
     * @param $status 需要变更的状态值
     * @return  time_type
     *              MARKETING_ACTIVITY_TIME_TYPE_ONETIME        一次性活动
     *              MARKETING_ACTIVITY_TIME_TYPE_PERIODICITY    周期性活动
     *          time_send_type
     *              MARKETING_ACTIVITY_SEND_TYPE_TIMING         定时推送
     *              MARKETING_ACTIVITY_SEND_TYPE_CRONTAB        定义周期
     *              MARKETING_ACTIVITY_SEND_TYPE_LONG           长期活动
     */
    public function editMarketingActivityStatus($ma_id, $status)
    {
        $result = MarketingActivity::model()->findByPk($ma_id);
        $merchant_id = $result->merchant_id;
        $type = $result->type;
        $time_type = $result->time_type;
        $time_send_type = $result->time_send_type;

        //非精准营销状态改变
        if (!empty($merchant_id) && !empty($type) && $type != MARKETING_ACTIVITY_TYPE_BASICS_JZMA) {
            $model = MarketingActivity::model()->findAll('merchant_id = :merchant_id and type = :type', array(
                ':merchant_id' => $merchant_id,
                ':type' => $type
            ));
            if (!empty($model) && !empty($ma_id)) {
                foreach ($model as $v) {
                    //停用活动（有冲突的情况）
                    if ($ma_id != $v->attributes['id']) {
                        $marketingActivity = MarketingActivity::model()->findByPk($v->attributes['id']);
                        $marketingActivity->status = MARKETING_ACTIVITY_STATUS_STOP;
                        $marketingActivity->update();
                    } //启用活动（有冲突和无冲突两种情况）/停用活动（无冲突的情况）
                    else {
                        $marketingActivity = MarketingActivity::model()->findByPk($v->attributes['id']);
                        //添加未开始状态（伪进行中）的停用操作
                        if ($status == MARKETING_ACTIVITY_STATUS_START || $status == MARKETING_ACTIVITY_STATUS_NOSTART) {
                            $marketingActivity->status = MARKETING_ACTIVITY_STATUS_STOP;
                        } else {
                            $marketingActivity->status = MARKETING_ACTIVITY_STATUS_START;
                        }
                        $marketingActivity->update();
                    }
                }
            }
        } //活动状态改变（精准营销）
        elseif ($type == MARKETING_ACTIVITY_TYPE_BASICS_JZMA) {
            if (isset($ma_id) && !empty($ma_id)) {
                $model = MarketingActivity::model()->findByPk($ma_id);
                $start_time = $model->start_time;
                $end_time = $model->end_time;
            }
            //进行中和伪进行中（未开始）这两个状态的停用操作
            if (isset($status) && ($status == MARKETING_ACTIVITY_STATUS_START || $status == MARKETING_ACTIVITY_STATUS_NOSTART)) {
                $model->status = MARKETING_ACTIVITY_STATUS_STOP;
            } //停止状态的启用操作（三种情况）
            elseif ($status == MARKETING_ACTIVITY_STATUS_STOP) {
                //一次性活动----定时推送
                if ($time_type == MARKETING_ACTIVITY_TIME_TYPE_ONETIME && $time_send_type == MARKETING_ACTIVITY_SEND_TYPE_TIMING) {
                    if (strtotime($start_time) < time()) {
                        $model->status = MARKETING_ACTIVITY_STATUS_END;
                    } else {
                        $model->status = MARKETING_ACTIVITY_STATUS_START;
                    }
                } //周期活动  定义周期
                elseif ($time_type == MARKETING_ACTIVITY_TIME_TYPE_PERIODICITY && $time_send_type == MARKETING_ACTIVITY_SEND_TYPE_CRONTAB) {
                    if (date('Ymd', time()) >= date('Ymd', strtotime($start_time)) && date('Ymd', time()) <= date('Ymd', strtotime($end_time))) {
                        $model->status = MARKETING_ACTIVITY_STATUS_START;
                    } else {
                        $model->status = MARKETING_ACTIVITY_STATUS_END;
                    }
                } //其他情况
                else {
                    $model->status = MARKETING_ACTIVITY_STATUS_START;
                }
            }
            $model->update();
        }
        $result['status'] = ERROR_NONE;
        return json_encode($result, true);
    }

    /** 验证正在进行中的相同类型活动 是否存在
     * @param $ma_id  活动id
     * @return string
     */
    public function ifMarketingActivity($ma_id)
    {
        $result = array();
        $merchant_id = MarketingActivity::model()->findByPk($ma_id)->merchant_id;
        $type = MarketingActivity::model()->findByPk($ma_id)->type;
        //精准营销时返回错误
        if ($type == MARKETING_ACTIVITY_TYPE_BASICS_JZMA) {
            $result['status'] = ERROR_REQUEST_FAIL;
            return json_encode($result);
        } else {
            try {
                $criteria = new CDbCriteria();
                //相同类型
                if (isset($type) && !empty($type)) {
                    $criteria->addCondition("type = :type");
                    $criteria->params[':type'] = $type;
                }
                if (isset($merchant_id) && !empty($merchant_id)) {
                    $criteria->addCondition("merchant_id = :merchant_id");
                    $criteria->params[':merchant_id'] = $merchant_id;
                }
                //状态为进行中
                $criteria->addCondition("status = :status");
                $criteria->params[':status'] = MARKETING_ACTIVITY_STATUS_START;

                $sameActivity = MarketingActivity::model()->findAll($criteria);
                //查询结果非空时，返回成功
                if (!empty($sameActivity)) {
                    $result['status'] = ERROR_NONE;
                } else {
                    $result['status'] = ERROR_NO_DATA;
                }
            } catch (Exception $e) {
                $result['status'] = isset($result['status']) ? $result['status'] : ERROR_EXCEPTION;
                $result['errMsg'] = $e->getMessage(); //错误信息
            }
            return json_encode($result);
        }
    }

    /** 获取分组正在参加的活动
     * @param $group_id 分组id
     * @return mixed
     */
    public function getGroupMarketing($group_id)
    {
        $res_group_marketing = MarketingActivity::model()->findAll('group_id = :group_id and status = :status and flag = :flag', array(
            ':group_id' => $group_id,
            ':status' => MARKETING_ACTIVITY_STATUS_START,
            ':flag' => FLAG_NO
        ));

        if (!empty($res_group_marketing)) {
            foreach ($res_group_marketing as $v) {
                $group_marketing[$v['id']] = $v['name'];
            }
        }

        return $group_marketing;
    }

    /** 通过id获取营销活动信息
     * @param $ma_id 营销活动id
     * @return mixed
     */
    public function getMarketingInfoById($ma_id)
    {
        $cmd = Yii::app()->db->createCommand();
        $cmd->select('a.*, b.name AS group_name,
	b.num, b.last_time, c.type AS coupon_type, c.title AS coupon_name, c.status');
        $cmd->from('wq_marketing_activity a');
        $cmd->leftJoin('wq_user_group b', 'a.group_id = b.id');
        $cmd->leftJoin('wq_coupons c', 'a.coupon_id = c.id');

        $cmd->andWhere('a.id = :marketing_id');
        $cmd->params[':marketing_id'] = $ma_id;

        $cmd->andWhere('a.flag = :flag');
        $cmd->params[':flag'] = FLAG_NO;

        $marketing_info = json_encode($cmd->queryRow(),true);
        return $marketing_info;
    }

    /** 更改精准营销活动状态
     * @param $ma_id 活动id
     * @param $status 需要更改的状态值
     * @return bool
     */
    public function setMarketingActivityPrecisionStatus($ma_id, $status)
    {
        $res_marketing = MarketingActivity::model()->findByPk($ma_id);
        $res_marketing->status = $status;
        if ($res_marketing->save()) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @param $data
     * @throws Exception
     * 发送渠道消息
     */
    public function sendChannelMsg($data)
    {
        $merchant_id = $data['merchant_id'];
        $group_id = $data['group_id'];
        $coupon_id = $data['coupon_id'];
        $send_channel = $data['send_channel'];
        $image_text_title = $data['image_text_title'];
        $name = $data['name'];
        $image_text_imageurl = $data['image_text_imageurl'];
        $time_type = $data['time_type'];
        $time_send_type = $data['time_send_type'];
        $marketing_id = $data['marketing_id'];

        $userClass = new UserClass();

        //查询分组信息
        $group_info = UserGroup::model()->findByPk($group_id);
        //查询活动信息
        $marketing_info = MarketingActivity::model()->findByPk($marketing_id);

        if ($group_info['type'] == GROUP_TYPE_DYNAMIC && $time_type == MARKETING_ACTIVITY_TIME_TYPE_PERIODICITY && $time_send_type == MARKETING_ACTIVITY_SEND_TYPE_CRONTAB) { //动态分组，且定义周期活动
            if (date('Ymd', strtotime($marketing_info['start_time'])) == date('Ymd')) { //第一天，发全部
                //查找分组下所有的用户open_id
                $cmd = Yii::app()->db->createCommand();
                $cmd->select('a.user_id, b.type, b.wechat_id');
                $cmd->from('wq_group a');
                $cmd->leftJoin('wq_user b', 'a.user_id = b.id');

                $cmd->andWhere('a.group_id = :group_id');
                $cmd->params[':group_id'] = $group_id;
                $cmd->andWhere('a.flag = :flag');
                $cmd->params[':flag'] = FLAG_NO;

                $all_group_user = $cmd->queryAll();
            } else { //发新增用户
                //查找分组下新增的用户open_id
                $cmd = Yii::app()->db->createCommand();
                $cmd->select('a.user_id, b.type, b.wechat_id');
                $cmd->from('wq_group a');
                $cmd->leftJoin('wq_user b', 'a.user_id = b.id');

                $cmd->andWhere('a.group_id = :group_id');
                $cmd->params[':group_id'] = $group_id;
                $cmd->andWhere("FROM_UNIXTIME(UNIX_TIMESTAMP(a.create_time), '%Y%m%d') = :create_time");
                $cmd->params[':create_time'] = date('Ymd', time());
                $cmd->andWhere('a.flag = :flag');
                $cmd->params[':flag'] = FLAG_NO;

                $all_group_user = $cmd->queryAll();
            }
        } else {
            //查找分组下所有的用户open_id
            $cmd = Yii::app()->db->createCommand();
            $cmd->select('a.user_id, b.type, b.wechat_id');
            $cmd->from('wq_group a');
            $cmd->leftJoin('wq_user b', 'a.user_id = b.id');

            $cmd->andWhere('a.group_id = :group_id');
            $cmd->params[':group_id'] = $group_id;
            $cmd->andWhere('a.flag = :flag');
            $cmd->params[':flag'] = FLAG_NO;

            $all_group_user = $cmd->queryAll();
        }

        foreach ($all_group_user as $v2) {
            //给会员发券
            if ($v2['type'] == USER_TYPE_WANQUAN_MEMBER) {
                //判断用户是否可以领取优惠券
                $jug_res = $userClass->judgeUserCoupons($v2['user_id'], $coupon_id);
                $jug_result = json_decode($jug_res, true);

                if ($jug_result['status'] == ERROR_NONE) { //可以领取
                    $userClass->newReceiveCoupons($v2['user_id'], $coupon_id, $v2['wechat_id']);
                }
            }

            if (!empty($v2['wechat_id'])) {
                $openid_list[] = $v2['wechat_id'];
            }
        }
        //查找分组的支付宝标签
        $alipay_label_id = $group_info['label_id'];

        //查询优惠券信息
        $coupon_info = Coupons::model()->findByPk($coupon_id);

        //获取access_token
        $merchantC = new MobileMerchantC();
        $merchant = $merchantC->getMerchantInfo($merchant_id);
        $access_token = WechatWebAuth::getTokenByMerchant($merchant);

        //判断活动的发送渠道
        if ($send_channel == MARKETING_ACTIVITY_SEND_CHANNEL_WECHAT) { //仅微信
            //判断优惠券在微信是否审核通过
            if ($coupon_info['status'] == WX_CHECK_PASS) { //已通过，发送原生卡券消息
                $message['type'] = 'wxcard';
                $message['info'] = array(
                    'card_id' => $coupon_info['card_id']
                );

                $message = urldecode(json_encode($message));
                $wechat_mass_send = new WechatMassSend();
                $wechat_data = $wechat_mass_send->sendMessage($message, $openid_list, $access_token); //返回数组
            } else { //未通过，发送图文消息
                $url = WAP_DOMAIN . '/coupon_' . $coupon_info['code'] . '.html';
                $a_text = urlencode('点击领取优惠券');
                $type = 'text';
                $msg = array('touser' => $openid_list);
                $msg[$type] = array(
                    'content' => urlencode($image_text_title) . "<a href='" . $url . "'>" . $a_text . "</a>"
                );
                $msg['msgtype'] = $type;
                $wechat_mass_send = new WechatMassSend();
                $wechat_send_res = $wechat_mass_send->massSendGroud($access_token, urldecode(json_encode($msg))); //返回json

                $wechat_data = json_decode($wechat_send_res, true);
            }

            //保存发送log
            $send_log = new SendLog();
            $send_log->type = 1;
            $send_log->activity_id = $marketing_id;
            if ($wechat_data['errcode'] == 0) {
                $send_log->wechat_status = 2;
            } else {
                $send_log->wechat_status = 3;
            }
            $send_log->wechat_msg = json_encode($wechat_data);
            $send_log->create_time = date('Y-m-d H:i:s');
            $send_log->save();

        } elseif ($send_channel == MARKETING_ACTIVITY_SEND_CHANNEL_ALIPAY) { //仅支付宝
            $message['type'] = 'wqcard';
            //保存素材获取id
            $alipay = new AliServiceWindowMessageSend();

            //素材信息
            $array = array(array(
                'title' => $name,
                'cover_img' => $image_text_imageurl,
                'content' => $image_text_title,
                'link_content' => WAP_DOMAIN . '/coupon_' . $coupon_info['code'] . '.html'
            ));
            $material_id = $alipay->saveMaterial($array);

            //调用支付宝接口分组发送图文消息
            $alipay_send_res = $alipay->messageLabelSend('', $material_id, $alipay_label_id);

            //保存发送log
            $send_log = new SendLog();
            $send_log->type = 1;
            $send_log->activity_id = $marketing_id;
            if ($alipay_send_res->alipay_mobile_public_message_label_send_response->code == 200) {
                $send_log->alipay_status = 2;
            } else {
                $send_log->alipay_status = 3;
            }
            $send_log->alipay_msg = json_encode($alipay_send_res);
            $send_log->create_time = date('Y-m-d H:i:s');
            $send_log->save();

        } elseif ($send_channel == MARKETING_ACTIVITY_SEND_CHANNEL_BOTH) { //所有渠道
            //调用微信图文接口
            $url = WAP_DOMAIN . '/coupon_' . $coupon_info['code'] . '.html';
            $a_text = urlencode('点击领取优惠券');
            $type = 'text';
            $msg = array(
                'touser' => $openid_list
            );
            $msg[$type] = array(
                'content' => urlencode($image_text_title) . "，<a href='" . $url . "'>" . $a_text . "</a>"
            );
            $msg['msgtype'] = $type;
            $wechat_mass_send = new WechatMassSend();
            $wechat_send_res = $wechat_mass_send->massSendGroud($access_token, urldecode(json_encode($msg)));

            //保存素材获取id
            $alipay = new AliServiceWindowMessageSend();

            //素材信息
            $array = array(array(
                'title' => $name,
                'cover_img' => $image_text_imageurl,
                'content' => $image_text_title,
                'link_content' => WAP_DOMAIN . '/coupon_' . $coupon_info['code'] . '.html'
            ));
            $material_id = $alipay->saveMaterial($array);

            //调用支付宝接口分组发送图文消息
            $alipay_send_res = $alipay->messageLabelSend('', $material_id, $alipay_label_id);

            $wechat_data = json_decode($wechat_send_res, true);

            //保存发送log
            $send_log = new SendLog();
            $send_log->type = 1;
            $send_log->activity_id = $marketing_id;
            if ($wechat_data['errcode'] == 0) {
                $send_log->wechat_status = 2;
            } else {
                $send_log->wechat_status = 3;
            }
            if ($alipay_send_res->alipay_mobile_public_message_label_send_response->code == 200) {
                $send_log->alipay_status = 2;
            } else {
                $send_log->alipay_status = 3;
            }
            $send_log->wechat_msg = $wechat_send_res;
            $send_log->alipay_msg = json_encode($alipay_send_res);
            $send_log->create_time = date('Y-m-d H:i:s');
            $send_log->save();
        }
    }

    /**
     * @param $data
     * @throws Exception
     * 发送渠道消息(无分组)
     */
    public function sendChannelMsgElse($data)
    {
        $merchant_id = $data['merchant_id'];
        $coupon_id = $data['coupon_id'];
        $send_channel = $data['send_channel'];
        $image_text_title = $data['image_text_title'];
        $name = $data['name'];
        $image_text_imageurl = $data['image_text_imageurl'];
        $marketing_id = $data['marketing_id'];
        $type = $data['marketing_type'];
        $userInfo = json_decode($this->getUserInfo($type,$merchant_id),true);
        $userClass = new UserClass();
        //查询活动信息
        $marketing_info = MarketingActivity::model()->findByPk($marketing_id);

        foreach ($userInfo as $v) {
            //给会员发券
            if ($v['type'] == USER_TYPE_WANQUAN_MEMBER) {
                //判断用户是否可以领取优惠券
                $jug_res = $userClass->judgeUserCoupons($v['id'], $coupon_id);
                $jug_result = json_decode($jug_res, true);
                if ($jug_result['status'] == ERROR_NONE) { //可以领取
                    $userClass->newReceiveCoupons($v['id'], $coupon_id, $v['wechat_id']);
                }
            }

            if (!empty($v['wechat_id'])) {
                $openid_list[] = $v['wechat_id'];
            }
        }
        //查询优惠券信息
        $coupon_info = Coupons::model()->findByPk($coupon_id);
        //获取access_token
        $merchantC = new MobileMerchantC();
        $merchant = $merchantC->getMerchantInfo($merchant_id);
        $access_token = WechatWebAuth::getTokenByMerchant($merchant);
        //判断活动的发送渠道
        if ($send_channel == MARKETING_ACTIVITY_SEND_CHANNEL_WECHAT) { //仅微信
            //判断优惠券在微信是否审核通过
            if ($coupon_info['status'] == WX_CHECK_PASS) { //已通过，发送原生卡券消息
                $message['type'] = 'wxcard';
                $message['info'] = array(
                    'card_id' => $coupon_info['card_id']
                );
                $message = urldecode(json_encode($message));
                $wechat_mass_send = new WechatMassSend();
                $wechat_data = $wechat_mass_send->sendMessage($message, $openid_list, $access_token); //返回数组

            } else { //未通过，发送图文消息
                $url = WAP_DOMAIN . '/coupon_' . $coupon_info['code'] . '.html';
                $a_text = urlencode('点击领取优惠券');
                $type = 'text';
                $msg = array('touser' => $openid_list);
                $msg[$type] = array(
                    'content' => urlencode($image_text_title) . " <a href='" . $url . "'>" . $a_text . "</a>"
                );
                $msg['msgtype'] = $type;
                $wechat_mass_send = new WechatMassSend();
                $wechat_send_res = $wechat_mass_send->massSendGroud($access_token, urldecode(json_encode($msg))); //返回json

                $wechat_data = json_decode($wechat_send_res, true);
            }

            //保存发送log
            $send_log = new SendLog();
            $send_log->type = 1;
            $send_log->activity_id = $marketing_id;
            if ($wechat_data['errcode'] == 0) {
                $send_log->wechat_status = 2;
            } else {
                $send_log->wechat_status = 3;
            }
            $send_log->wechat_msg = json_encode($wechat_data);
            $send_log->create_time = date('Y-m-d H:i:s');
            $send_log->save();

        } elseif ($send_channel == MARKETING_ACTIVITY_SEND_CHANNEL_ALIPAY) { //仅支付宝
            $message['type'] = 'wqcard';
            //保存素材获取id
            $alipay = new AliServiceWindowMessageSend();

            //素材信息
            $array = array(array(
                'title' => $name,
                'cover_img' => $image_text_imageurl,
                'content' => $image_text_title,
                'link_content' => WAP_DOMAIN . '/coupon_' . $coupon_info['code'] . '.html'
            ));
            $material_id = $alipay->saveMaterial($array);

            //调用支付宝接口分组发送图文消息
            $alipay_send_res = $alipay->messageLabelSend('', $material_id, $alipay_label_id);

            //保存发送log
            $send_log = new SendLog();
            $send_log->type = 1;
            $send_log->activity_id = $marketing_id;
            if ($alipay_send_res->error_response->code == 200) {
                $send_log->alipay_status = 2;
            } else{
                $send_log->alipay_status = 3;
            }
            $send_log->alipay_msg = json_encode($alipay_send_res);
            $send_log->create_time = date('Y-m-d H:i:s');
            $send_log->save();

        } elseif ($send_channel == MARKETING_ACTIVITY_SEND_CHANNEL_BOTH) { //所有渠道
            //调用微信图文接口
            $url = WAP_DOMAIN . '/coupon_' . $coupon_info['code'] . '.html';
            $a_text = urlencode('点击领取优惠券');
            $type = 'text';
            $msg = array(
                'touser' => $openid_list
            );
            $msg[$type] = array(
                'content' => urlencode($image_text_title) . "，<a href='" . $url . "'>" . $a_text . "</a>"
            );
            $msg['msgtype'] = $type;
            $wechat_mass_send = new WechatMassSend();
            $wechat_send_res = $wechat_mass_send->massSendGroud($access_token, urldecode(json_encode($msg)));

            //保存素材获取id
            $alipay = new AliServiceWindowMessageSend();

            //素材信息
            $array = array(array(
                'title' => $name,
                'cover_img' => $image_text_imageurl,
                'content' => $image_text_title,
                'link_content' => WAP_DOMAIN . '/coupon_' . $coupon_info['code'] . '.html'
            ));
            $material_id = $alipay->saveMaterial($array);

            //调用支付宝接口分组发送图文消息
            $alipay_send_res = $alipay->messageLabelSend('', $material_id, $alipay_label_id);

            $wechat_data = json_decode($wechat_send_res, true);

            //保存发送log
            $send_log = new SendLog();
            $send_log->type = 1;
            $send_log->activity_id = $marketing_id;
            if ($wechat_data['errcode'] == 0) {
                $send_log->wechat_status = 2;
            } else {
                $send_log->wechat_status = 3;
            }
            if ($alipay_send_res->error_response->code == 200) {
                $send_log->alipay_status = 2;
            } else{
                $send_log->alipay_status = 3;
            }
            $send_log->wechat_msg = $wechat_send_res;
            $send_log->alipay_msg = json_encode($alipay_send_res);
            $send_log->create_time = date('Y-m-d H:i:s');
            $send_log->save();
        }
    }

    //获取符合条件的用户id
    public function getUserInfo($type,$merchant_id){
        $connection = Yii::app()->db;
        //新会员
        if($type == MARKETING_ACTIVITY_TYPE_BASICS_XHYMA){
            $sql = "select id,wechat_id,type
                    FROM wq_user
                    WHERE merchant_id = $merchant_id
                    AND TIMESTAMPDIFF(DAY,regist_time,NOW()) <30
                    AND TIMESTAMPDIFF(DAY,last_trade_time,NOW()) <30
                    AND flag = 1";
        }
        //流失客户
        elseif($type == MARKETING_ACTIVITY_TYPE_BASICS_LSKHMA){
            $sql = "select id ,wechat_id,type
                    FROM wq_user
                    WHERE merchant_id = $merchant_id
                    AND TIMESTAMPDIFF(MONTH,last_trade_time,NOW()) >= 2
                    AND flag = 1";
        }
        //老会员--
        elseif($type == MARKETING_ACTIVITY_TYPE_BASICS_LHYMA){
            $sql = "select id ,wechat_id,type
                    FROM wq_user
                    WHERE merchant_id = $merchant_id
                    AND TIMESTAMPDIFF(MONTH,regist_time,NOW()) >=1
                    AND TIMESTAMPDIFF(MONTH,last_trade_time,NOW()) <2
                    AND flag = 1";
        }
        //活跃客户--
        elseif($type == MARKETING_ACTIVITY_TYPE_BASICS_HYKHMA){
            $sql = "select *
                    FROM wq_order
                    where TIMESTAMPDIFF(MONTH,pay_time,NOW()) <2
                    AND order_type = 3
                    AND pay_status = 2
                    AND (order_status = 7
                    OR order_status = 12)
                    AND flag =1
                    AND merchant_id = $merchant_id
                    GROUP BY user_id
                    HAVING count(user_id)>=2";
        }
        $command = $connection->createCommand($sql);
        $res = $command->queryAll();
        return json_encode($res,true);
    }

    /** 添加基础营销活动（非精准营销）
     * @param $merchant_id 商户id
     * @param $info 添加的活动数据 如下：
     * name:名字
     * type:活动类型
     * image_text_imageurl:封面图片
     * image_text_title:图文简介
     * coupon_id:优惠券id
     * send_channel:发送渠道
     * time_type:时间类型
     * start_time:开始时间
     * end_time:结束时间
     * status:活动状态
     * @return string
     */
    public function addMarketingActivityElse($merchant_id, $info)
    {
        try {
            $marketing = new MarketingActivity();
            $marketing->merchant_id = $merchant_id;
            $marketing->name = $info['name'];
            $marketing->type = $info['marketing_type'];
            $marketing->image_text_imageurl = $info['image_text_imageurl'];
            $marketing->image_text_title = $info['image_text_title'];
            $marketing->coupon_id = $info['coupon_id'];
            $marketing->condition = $info['condition'];
            $marketing->send_channel = $info['send_channel'];
            $marketing->time_type = $info['time_type'];
            $marketing->time_send_type = $info['time_send_type'];
            $marketing->start_time = $info['start_time'];
            $marketing->end_time = $info['end_time'];
            $marketing->status = $info['status'];
            $marketing->last_time = date('Y-m-d H:i:s', time());
            $marketing->create_time = date('Y-m-d H:i:s', time());

            if ($marketing->save()) {
                $result['status'] = ERROR_NONE;
                $result['data'] = $marketing->id;
            }
        } catch (Exception $e) {
            $result['status'] = isset($result['status']) ? $result['status'] : ERROR_EXCEPTION;
            $result['errMsg'] = $e->getMessage();
        }

        return json_encode($result);
    }

    /** 编辑主动推送类的营销活动（新会员、流失客户、老客户、活跃客户）
     * @param $marketing_id 活动id
     * @param $info 编辑的活动数据 如下：
     * name:名字
     * image_text_imageurl:封面图片
     * image_text_title:图文简介
     * coupon_id:优惠券id
     * send_channel:发送渠道
     * time_type:时间类型
     * start_time:开始时间
     * end_time:结束时间
     * status:活动状态
     * @return string
     */
    public function editMarketingActivityElse($marketing_id, $info)
    {
        try {
            $marketing = MarketingActivity::model()->findByPk($marketing_id);

            $marketing->name = $info['name'];
            $marketing->image_text_imageurl = $info['image_text_imageurl'];
            $marketing->image_text_title = $info['image_text_title'];
            $marketing->coupon_id = $info['coupon_id'];
            $marketing->send_channel = $info['send_channel'];
            $marketing->time_type = $info['time_type'];
            $marketing->time_send_type = $info['time_send_type'];
            $marketing->start_time = $info['start_time'];
            $marketing->end_time = $info['end_time'];
            $marketing->status = $info['status'];
            $marketing->last_time = date('Y-m-d H:i:s', time());
            if ($marketing->save()) {
                $result['status'] = ERROR_NONE;
                $result['data'] = $marketing->id;
            }
        } catch (Exception $e) {
            $result['status'] = isset($result['status']) ? $result['status'] : ERROR_EXCEPTION;
            $result['errMsg'] = $e->getMessage();
        }
        return json_encode($result);
    }

    /** 微信关注事件，触发赠券
     * @param $merchant_id  商户id
     * @param $type         营销活动类型
     * @param $fromUsername 微信用户openid
     * image_text_title:    图文简介
     * coupon_id:           优惠券id
     * @return string
     */
    public function sendWechatCoupons($merchant_id,$type,$fromUsername)
    {
        try {
            //获取活动对应的优惠券id
            $marketing = json_decode($this->getMarketingCouponShowInfo($merchant_id, $type), true);

            //图文消息内容
            $title = $marketing['name'];
            $description = $marketing['image_text_title'];
            $imageUrl = IMG_GJ_LIST . $marketing['image_text_imageurl'];
            //优惠券id
            $coupon_id = $marketing['coupon_id'];

            //查询优惠券信息
            $coupon_info = Coupons::model()->findByPk($coupon_id);
            $card_id = $coupon_info->card_id;           //微信原生券id
            $coupon_status = $coupon_info->status;      //微信原生券审核状态

            $merchantC = new MobileMerchantC();
            $merchant = $merchantC->getMerchantInfo($merchant_id);
            $access_token = WechatWebAuth::getTokenByMerchant($merchant);

            //判断优惠券在微信是否审核通过
            if ($coupon_status == WX_CHECK_PASS) { //已通过，发送原生卡券消息
                //客服消息拼装
                $message = array(
                    "touser" => "$fromUsername",
                    "msgtype" => "wxcard",
                    "wxcard" => array(
                        "card_id" => $card_id
                    )
                );
            } else {                                 //未通过，发送图文消息
                //图文消息跳转链接
                $url = WAP_DOMAIN . '/coupon_' . $coupon_info['code'] . '.html';
                $description = urlencode($description);
                $title = urlencode($title);
                //客服消息拼装
                $message = array(
                    "touser" => "$fromUsername",
                    "msgtype" => "news",
                    "news" => array(
                        "articles" => array(
                            "0" => array(
                                "title" => "$title",
                                "description" => "$description",
                                "url" => "$url",
                                "picurl" => "$imageUrl"
                            )
                        )
                    )
                );
            }
            $message = urldecode(json_encode($message));
            $wechat_mass_send = new WechatMassSend();
            $result_send = $wechat_mass_send->customSend($access_token, $message);
            $res = json_decode($result_send, true);
            $result['status'] = $res['errcode'];
            //微信客服消息错误码
            //Yii::log('wechat_errcode:' . $res['errcode'], 'warning');
        } catch (Exception $e) {
            $result['status'] = isset($res['errcode']) ? $res['errcode'] : ERROR_EXCEPTION;
            return json_encode($result);
        }
    }

    /**微信支付赠券
     * @param $order_no 收银台传值  订单号
     */
    public function sendWechatCouponPaid($order_no){
        //按照订单号，和订单状态信息，查询出符合条件的订单
        $order_res = Yii::app()->db->createCommand(
            array(
                //查询 商户id 用户id 订单总金额 微信openid 支付宝openid
                'select' => 'merchant_id,user_id,order_paymoney,wechat_user_p_id,alipay_user_id',
                'from'   => 'wq_order',
                'where'  => 'order_no = :order_no
                             AND order_type = :order_type
                             AND pay_status = :pay_status
                             AND order_status = :order_status
                             AND flag = :flag',
                'params' => array(
                             ':order_no' => $order_no,
                             ':order_type'=>ORDER_TYPE_CASHIER,
                             ':pay_status'=>ORDER_STATUS_PAID,
                             ':order_status'=>ORDER_STATUS_NORMAL,
                             ':flag'=>FLAG_NO
                )
            )
        )->queryRow();

        if(!empty($order_res)){
            $merchant_id       =   $order_res['merchant_id'];      //商户id
            $user_id           =   $order_res['user_id'];          //用户id
            $order_paymoney    =   $order_res['order_paymoney'];   //订单总金额
            $wechat_user_p_id  =   $order_res['wechat_user_p_id']; //微信用户openid
            $alipay_user_id    =   $order_res['alipay_user_id'];   //支付宝用户openid
        }

        //该用户，在此商家的总消费次数
        $order_count = Order::model()->findAll(
            'user_id = :user_id
            and merchant_id = :merchant_id
            and order_type = :order_type
            and pay_status = :pay_status
            and order_status = :order_status
            and flag = :flag',
            array(
                ':user_id' => $user_id,
                ':merchant_id' => $merchant_id,
                ':order_type' => ORDER_TYPE_CASHIER,
                ':pay_status' => ORDER_STATUS_PAID,
                ':order_status' => ORDER_STATUS_NORMAL,
                ':flag' => FLAG_NO
            )
        );
        //该用户，在此商家的总消费次数
        $count = count($order_count);

        $total_paymoney = Yii::app()->db->createCommand(
            array(
                'select' => 'sum(order_paymoney) as total',
                'from' => 'wq_order',
                'where' => 'merchant_id=:merchant_id
                                AND user_id=:user_id
                                AND order_type=:order_type
                                AND pay_status=:pay_status
                                AND order_status=:order_status
                                AND flag=:flag',
                'params' => array(':merchant_id'=> $merchant_id,
                    ':user_id' =>  $user_id,
                    ':order_type' => ORDER_TYPE_CASHIER,
                    ':pay_status' => ORDER_STATUS_PAID,
                    ':order_status' => ORDER_STATUS_NORMAL,
                    ':flag' => FLAG_NO,
                ),
                'group' => 'user_id'
            )
        )->queryRow();

        $total_paymoney = $total_paymoney['total'];

        $first_pay = json_decode($this->getMarketingCouponShowInfo($merchant_id,MARKETING_ACTIVITY_TYPE_BASICS_SCZFMA),true);
        var_dump($first_pay);
        $single_pay = json_decode($this->getMarketingCouponShowInfo($merchant_id,MARKETING_ACTIVITY_TYPE_BASICS_DCXFMMA),true);
        var_dump($single_pay);

        $total_pay = $this->getMarketingCouponShowInfo($merchant_id,MARKETING_ACTIVITY_TYPE_BASICS_LJXFMMA);
        $multiple_pay = $this->getMarketingCouponShowInfo($merchant_id,MARKETING_ACTIVITY_TYPE_BASICS_XFCSMMA);
        if(!empty($single_pay)){
            $single_pay_condition = json_decode($single_pay['condition'],true);
            $single_pay_condition = $single_pay_condition['single_pay'];
        }

        if(!empty($total_pay)){
            echo 1;exit;
            $total_pay_condition = json_decode($total_pay['condition'],true);
            $total_pay_condition = $total_pay_condition['total_pay'];

        }
        if(!empty($multiple_pay)){
            $multiple_pay_condition = json_decode($multiple_pay['condition'],true);
            $multiple_pay_condition = $multiple_pay_condition['multiple_pay'];
            echo $multiple_pay_condition;
        }
    }

}