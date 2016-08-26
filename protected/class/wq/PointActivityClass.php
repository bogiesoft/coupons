<?php

include_once(dirname(__FILE__) . '/../mainClass.php');

/**
 * 积分活动类
 */
class PointActivityClass extends mainClass
{
    public $page = null;
    /**
     * 获取积分活动列表
     * @param int $merchant_id 商户id
     * @param string $coupon_name 优惠券名称
     * @param int $time_status 活动进行状态
     * @param int $activity_status 活动上下架状态
     * @return array
     */
    public function getPointActivityList ($merchant_id, $coupon_name = '', $time_status = '', $activity_status = '')
    {
        $rs = array();
        $data = array();
        try {
            $cmd = Yii::app()->db->createCommand();
            $cmd->select = 'p.id pid, p.merchant_id, p.needs_point, p.exchange_limit, p.stock, p.exchange_num, p.time_type as ptime_type, p.start_time pstart_time, p.end_time pend_time, p.status pstatus, c.id cid, c.title, c.time_type as ctime_type, c.start_time cstart_time, c.end_time cend_time, c.type, c.num, c.get_num, c.status cstatus';

            $cmd->from = "wq_point_activity AS p";
            $cmd->where = "p.merchant_id = ".$merchant_id." and p.flag = ".FLAG_NO;
            $cmd->join = "LEFT JOIN  wq_coupons AS c ON p.coupon_id = c.id";
            $cmd->order = "p.create_time DESC";


            //优惠券名称搜索
            if(!empty($coupon_name)){
                $cmd -> andWhere("c.title like :coupon_name");
                $cmd -> params[':coupon_name'] = "%$coupon_name%";
            }

            //活动时间状态搜索
            if (!empty($time_status)){
                if ($time_status == POINT_ACTIVITY_TIME_NOT_START) {

                    $cmd->andWhere(" p.time_type=:ptime_type and UNIX_TIMESTAMP(p.start_time) > UNIX_TIMESTAMP(NOW())");
                    $cmd->params[':ptime_type'] = POINT_ACTIVITY_TIME_TYPE_FIXED;

                } elseif ($time_status == POINT_ACTIVITY_TIME_STARTING) {

                    $cmd->andWhere('c.time_type=:ctime_type1 or (c.time_type=:ctime_type2 and UNIX_TIMESTAMP(c.start_time) <= UNIX_TIMESTAMP(NOW()) and UNIX_TIMESTAMP(c.end_time) >= UNIX_TIMESTAMP(NOW()))');

                    $cmd->params[':ctime_type1'] = VALID_TIME_TYPE_RELATIVE;
                    $cmd->params[':ctime_type2'] = VALID_TIME_TYPE_FIXED;

                    $cmd->andWhere("p.time_type = :time_type OR  ( p.time_type=:ptime_type and UNIX_TIMESTAMP(p.start_time) <= UNIX_TIMESTAMP(NOW()) AND UNIX_TIMESTAMP(p.end_time) >= UNIX_TIMESTAMP(NOW()) )");
                    $cmd->params[':time_type'] = POINT_ACTIVITY_TIME_TYPE_PERMANENT;
                    $cmd->params[':ptime_type'] = POINT_ACTIVITY_TIME_TYPE_FIXED;

                    $cmd->andWhere('c.num - c.get_num > 0');
                    $cmd->andWhere('p.stock - p.exchange_num > 0');

                } elseif ($time_status == POINT_ACTIVITY_END) {

                    $cmd->andWhere("((c.time_type=:ctime_type1 and UNIX_TIMESTAMP(c.end_time) <= UNIX_TIMESTAMP(NOW())) or (c.num - c.get_num <= 0) or (p.time_type=:ptime_type1 and UNIX_TIMESTAMP(p.end_time) < UNIX_TIMESTAMP(NOW())) or (p.stock - p.exchange_num <= 0) )");
                    $cmd->params[':ptime_type1'] = POINT_ACTIVITY_TIME_TYPE_FIXED;
                    $cmd->params[':ctime_type1'] = VALID_TIME_TYPE_FIXED;

                }
            }

            //上下架状态搜索
            if (!empty($activity_status)) {
                if ($activity_status == POINT_ACTIVITY_STATUS_ON_SHELF){
                    $cmd->andWhere('p.status=:status and (c.time_type=:ctime_type1 or c.time_type=:ctime_type2 and UNIX_TIMESTAMP(c.start_time) <= UNIX_TIMESTAMP(NOW()) and UNIX_TIMESTAMP(c.end_time) >= UNIX_TIMESTAMP(NOW())) ');
                    $cmd->params[':status'] = POINT_ACTIVITY_STATUS_ON_SHELF;


                    $cmd->params[':ctime_type1'] = VALID_TIME_TYPE_RELATIVE;
                    $cmd->params[':ctime_type2'] = VALID_TIME_TYPE_FIXED;



                } elseif ($activity_status == POINT_ACTIVITY_STATUS_OFF_SHELF) {

                    $cmd->andWhere('p.status=:status1 or ( p.status=:status2 and ((c.time_type=:ctime_type4 and UNIX_TIMESTAMP(c.end_time) < UNIX_TIMESTAMP(NOW())) ) )');
                    $cmd->params[':status1'] = POINT_ACTIVITY_STATUS_OFF_SHELF;


                    $cmd->params[':status2'] = POINT_ACTIVITY_STATUS_ON_SHELF;
                    $cmd->params[':ctime_type4'] = VALID_TIME_TYPE_FIXED;


                }
            }

            //分页处理
            $cmd1 = clone $cmd;
            $cmd1->select = 'count(*)';
            $page = $cmd1->queryColumn();
            $pages = new CPagination($page[0]);
            $this->page = $pages;


            $cmd->limit = Yii::app()->params['perPage'];//每页10条记录
            $cmd->offset = (isset($_GET['page']) ? $_GET['page'] : 0) * Yii::app()->params['perPage']-10;//从当前页第1条~最后1条（共10条）
            $model = $cmd->queryAll();

//            var_dump($model);
            $data = array();
            if (!empty($model)){
                foreach ($model as $k => $v){
                    //优惠券过期、活动时间结束、优惠券的库存不足，积分兑换的库存不足
                    //优惠券信息
                    $coupon_time_type = $v['ctime_type'];
                    $coupon_num = $v['num'] - $v['get_num'];
                    $coupon_start_time = $v['cstart_time'];
                    $coupon_end_time = $v['cend_time'];

                    //积分兑换活动信息
                    $pointactivity_status = $v['pstatus'];
                    $pointactivity_time_type = $v['ptime_type'];
                    $pointactivity_start_time = $v['pstart_time'];
                    $pointactivity_end_time = $v['pend_time'];
                    $pointactivity_num = $v['stock'] - $v['exchange_num'];


                    $flag = true;
                    //不符合优惠券和积分兑换活动的条件
                    if ( ($coupon_time_type == VALID_TIME_TYPE_FIXED && time() > strtotime($coupon_end_time)) || (($coupon_num <= 0 || (($pointactivity_time_type == POINT_ACTIVITY_TIME_TYPE_FIXED && time() > strtotime($pointactivity_end_time)) || $pointactivity_num <= 0)) && $pointactivity_status == POINT_ACTIVITY_STATUS_OFF_SHELF)){
                            $flag = false;
                    }

                    $data[$v['pid']]['id'] = $v['pid'];
                    $data[$v['pid']]['title'] = $v['title'];
                    $data[$v['pid']]['type'] = $v['type'];
                    $data[$v['pid']]['coupon_time_type'] = $v['ctime_type'];
                    $data[$v['pid']]['coupon_end_time'] = $v['cend_time'];
                    $data[$v['pid']]['needs_point'] = $v['needs_point'];
                    $data[$v['pid']]['stock'] = min(($v['stock'] - $v['exchange_num']),($v['num'] - $v['get_num']));
                    $data[$v['pid']]['time_type'] = $v['ptime_type'];
                    $data[$v['pid']]['start_time'] = $v['pstart_time'];
                    $data[$v['pid']]['end_time'] = $v['pend_time'];
                    $data[$v['pid']]['point_activity_flag'] = $flag;
                    $data[$v['pid']]['status'] = $v['pstatus'];


                }


                //计算兑换人数和次数
                $pa_id = array();
                foreach ($model as $v) {
                    $pa_id[] = $v['pid'];
                }
                $sql = "select activity_id, COUNT(user_id) AS pa_num, COUNT( DISTINCT user_id) AS pa_user_num from wq_user_pointsdetail where balance_of_payments = ".BALANCE_OF_PAYMENTS_EXPEND." and activity_type = ".POINT_DETAIL_ACTIVITY_TYPE_JFHQ." and activity_id IN (".implode(',',$pa_id).") GROUP BY activity_id";
                $exchangeUser= Yii::app()->db->createCommand($sql)->queryAll();
                $exchangeUser_num = array();
                if (!empty($exchangeUser)) {
                    foreach ($exchangeUser as $v) {
                        $data[$v['activity_id']]['pa_user_num'] = $v['pa_user_num'];
                        $data[$v['activity_id']]['pa_num'] = $v['pa_num'];
                    }
                }


                //给查询出的数据排序，已下架的放在最后
                $order_data = array();
                foreach ($data as $v){
                    if ($v['point_activity_flag'] == true) {
                        $order_data['normal'][$v['id']]['id'] = $v['id'];
                        $order_data['normal'][$v['id']]['title'] = $v['title'];
                        $order_data['normal'][$v['id']]['type'] = $v['type'];
                        $order_data['normal'][$v['id']]['coupon_time_type'] = $v['coupon_time_type'];
                        $order_data['normal'][$v['id']]['coupon_end_time'] = $v['coupon_end_time'];
                        $order_data['normal'][$v['id']]['needs_point'] = $v['needs_point'];
                        $order_data['normal'][$v['id']]['stock'] = $v['stock'];
                        $order_data['normal'][$v['id']]['pa_user_num'] = isset($v['pa_user_num']) ? $v['pa_user_num'] : 0;
                        $order_data['normal'][$v['id']]['pa_num'] = isset($v['pa_num']) ? $v['pa_num'] : 0;
                        $order_data['normal'][$v['id']]['time_type'] = $v['time_type'];
                        $order_data['normal'][$v['id']]['start_time'] = $v['start_time'];
                        $order_data['normal'][$v['id']]['end_time'] = $v['end_time'];
                        $order_data['normal'][$v['id']]['point_activity_flag'] = $v['point_activity_flag'];
                        $order_data['normal'][$v['id']]['status'] = $v['status'];
                    } else {
                        $order_data['abnormal'][$v['id']]['id'] = $v['id'];
                        $order_data['abnormal'][$v['id']]['title'] = $v['title'];
                        $order_data['abnormal'][$v['id']]['type'] = $v['type'];
                        $order_data['abnormal'][$v['id']]['coupon_time_type'] = $v['coupon_time_type'];
                        $order_data['abnormal'][$v['id']]['coupon_end_time'] = $v['coupon_end_time'];
                        $order_data['abnormal'][$v['id']]['needs_point'] = $v['needs_point'];
                        $order_data['abnormal'][$v['id']]['stock'] = $v['stock'];
                        $order_data['abnormal'][$v['id']]['pa_user_num'] = isset($v['pa_user_num']) ? $v['pa_user_num'] : 0;
                        $order_data['abnormal'][$v['id']]['pa_num'] = isset($v['pa_num']) ? $v['pa_num'] : 0;
                        $order_data['abnormal'][$v['id']]['time_type'] = $v['time_type'];
                        $order_data['abnormal'][$v['id']]['start_time'] = $v['start_time'];
                        $order_data['abnormal'][$v['id']]['end_time'] = $v['end_time'];
                        $order_data['abnormal'][$v['id']]['point_activity_flag'] = $v['point_activity_flag'];
                        $order_data['abnormal'][$v['id']]['status'] = $v['status'];
                    }
                }

                $rs['status'] = ERROR_NONE;
                $rs['data'] = $order_data;
            } else {
                $rs['status'] = ERROR_NO_DATA;
            }


        } catch (Exception $e) {
            $rs['status'] = isset($result['status']) ? $result['status'] : ERROR_EXCEPTION;
            $rs['errMsg'] = $e->getMessage();
        }
        return json_encode($rs);

    }

    /**
     * 修改积分活动状态
     * @param int $pa_id 积分活动id
     * @param int $status 需转状态
     */
    public function editPointActivityStatus ($pa_id, $status)
    {
        try {
            $pointactivity = PointActivity::model() -> findByPk($pa_id);
            $pointactivity -> status = $status;

            if ($pointactivity -> save()){
                $result['status'] = ERROR_NONE;
            }
        } catch (Exception $e) {
            $result['status'] = isset($result['status']) ? $result['status'] : ERROR_EXCEPTION;
            $result['errMsg'] = $e->getMessage();
        }

        return $result;
    }


    /**
     * 获取积分活动详情
     * @param int $pa_id 积分活动id
     * @return mixed
     */
    public function getPointActivityInfo ($pa_id)
    {
//        $data = array();
        try {
            $model = PointActivity::model() -> findByPk($pa_id);
            if (!empty($model)){
                $result['status'] = ERROR_NONE;
                $result['data']= $model;
            } else {
                $result['status'] = ERROR_NO_DATA;
            }

        } catch (Exception $e) {
            $result['status'] = isset($result['status']) ? $result['status'] : ERROR_EXCEPTION;
            $result['errMsg'] = $e->getMessage();
        }

        return $result;
    }

    /**
     * 删除积分活动
     * @param int $pa_id 积分活动id
     */
    public function delPointActivity ($pa_id)
    {
        try {
            $model = PointActivity::model() -> findByPk($pa_id);
            $model -> flag = FLAG_YES;
            if ($model->update()) {
                $result['status'] = ERROR_NONE;
            } else {
                $result['status'] = ERROR_SAVE_FAIL;
            }
        } catch (Exception $e) {
            $result['status'] = isset($result['status']) ? $result['status'] : ERROR_EXCEPTION;
            $result['errMsg'] = $e->getMessage();
        }
        return $result;
    }

    /**
     * 添加积分活动
     * @param int $merchant_id 商户id
     * @param array $info 积分活动信息
     */
    public function addPointActivity ($merchant_id, $info)
    {
        try {
            $pointactivity = new PointActivity();

            $pointactivity -> merchant_id = $merchant_id;
            $pointactivity -> coupon_id = $info['coupon_id'];
            $pointactivity -> needs_point = $info['grade'];
            $pointactivity -> exchange_limit = $info['limit'];
            $pointactivity -> stock = $info['stock'];
            if ($info['time_type'] === POINT_ACTIVITY_TIME_TYPE_FIXED) {
                $pointactivity -> start_time = $info['start_time'];
                $pointactivity -> end_time = $info['end_time'];
            }

            $pointactivity -> time_type = $info['time_type'];
            $pointactivity -> create_time = date('Y-m-d H:i:s', time());
            $pointactivity -> last_time = date('Y-m-d H:i:s', time());

            if ($pointactivity -> save()){
                $result['status'] = ERROR_NONE;
            }
        } catch (Exception $e) {
            $result['status'] = isset($result['status']) ? $result['status'] : ERROR_EXCEPTION;
            $result['errMsg'] = $e->getMessage();
        }

        return $result;
    }

    /**
     * 编辑积分换券
     * @param $pa_id 积分换券活动id
     * @param $info 积分换券信息
     * @return mixed
     */
    public function editPointActivity ($pa_id, $info)
    {
        try {
            $pointactivity = PointActivity::model() -> findByPk($pa_id);

            $pointactivity -> coupon_id = $info['coupon_id'];
            $pointactivity -> needs_point = $info['grade'];
            $pointactivity -> exchange_limit = $info['limit'];
            $pointactivity -> stock = $info['stock'];
            if ($info['time_type'] === POINT_ACTIVITY_TIME_TYPE_FIXED) {
                $pointactivity -> start_time = $info['start_time'];
                $pointactivity -> end_time = $info['end_time'];
            }else{
                $pointactivity -> start_time = null;
                $pointactivity -> end_time = null;
            }

            $pointactivity -> time_type = $info['time_type'];
//            $pointactivity -> create_time = date('Y-m-d H:i:s', time());
            $pointactivity -> last_time = date('Y-m-d H:i:s', time());

            if ($pointactivity -> update()){
                $result['status'] = ERROR_NONE;
            }
        } catch (Exception $e) {
            $result['status'] = isset($result['status']) ? $result['status'] : ERROR_EXCEPTION;
            $result['errMsg'] = $e->getMessage();
        }

        return $result;
    }

    /**
     * 获取过期原因
     * @param $pa_id 积分换券活动id
     */
    public function getExpiredReason ($pa_id)
    {
        try {
            $rs = PointActivity::model() -> findByPk($pa_id);

            //优惠券信息
            $coupon_time_type = $rs->coupon->time_type;
            $coupon_num = $rs->coupon->num - $rs->coupon->get_num;
            $coupon_start_time = $rs->coupon->start_time;
            $coupon_end_time = $rs->coupon->end_time;

            //积分兑换活动信息
            $pointactivity_time_type = $rs->time_type;
            $pointactivity_start_time = $rs->start_time;
            $pointactivity_end_time = $rs->end_time;
            $pointactivity_num = $rs->stock - $rs->exchange_num;

            $reason = null;

            //优惠券过期
            if ($coupon_time_type == VALID_TIME_TYPE_FIXED  && time() > strtotime($coupon_end_time)) {
                $reason = 1;
            }

            //优惠券库存不足
            if ($coupon_num <= 0){
                $reason = 2;
            }

            //积分兑换活动结束
            if ($pointactivity_time_type == POINT_ACTIVITY_TIME_TYPE_FIXED && time() > strtotime($pointactivity_end_time)){
                $reason = 3;
            }

            //积分兑换活动库存不足
            if ($pointactivity_num <= 0) {
                $reason = 4;
            }
            $result['reason'] = $reason;

        } catch (Exception $e) {
            $result['status'] = isset($result['status']) ? $result['status'] : ERROR_EXCEPTION;
            $result['errMsg'] = $e->getMessage();
        }

        return $result;

    }

    /**
     * 获取积分活动数据
     * @param int $merchant_id 商户ID
     */
    public function getPointActivityData($merchant_id)
    {
        try {
            //查询兑换张数和兑换积分
            $cmd = Yii::app()->db->createCommand();
            $cmd -> select = 'sum(coupon_num) as e_num, sum(points) as e_point, sum(pv) as e_pv';
            $cmd -> from = 'wq_point_activity AS p';
            $cmd -> where = 'p.merchant_id = '.$merchant_id;
            $cmd -> join = 'RIGHT JOIN wq_user_pointsdetail AS up ON p.id = up.activity_id';

            $cmd ->andWhere('up.activity_type = :activity_type and up.balance_of_payments = :balance_of_payments');
            $cmd ->params[':activity_type'] = POINT_DETAIL_ACTIVITY_TYPE_JFHQ;
            $cmd ->params[':balance_of_payments'] = BALANCE_OF_PAYMENTS_EXPEND;
            $data['exchange_num_point'] = $cmd -> queryAll();

            //查询核销次数
            $cmd = Yii::app()->db->createCommand();
            $cmd -> select = 'count(*) as u_num';
            $cmd -> from = 'wq_point_activity AS p';
            $cmd -> where = 'p.merchant_id = '.$merchant_id;
            $cmd -> join = 'RIGHT JOIN wq_user_coupons AS uc ON p.id = uc.activity_id';

            $cmd -> andWhere('uc.channel_type = :channel_type and uc.status = :status');
            $cmd -> params[':channel_type'] = POINT_DETAIL_ACTIVITY_TYPE_JFHQ;
            $cmd -> params[':status'] = COUPONS_USE_STATUS_USED;
            $data['use_num'] = $cmd -> queryAll();

            $rs['status'] = ERROR_NONE;
            $rs['data'] = $data;

        } catch (Exception $e) {
            $result['status'] = isset($result['status']) ? $result['status'] : ERROR_EXCEPTION;
            $result['errMsg'] = $e->getMessage();
        }

        return $rs;

    }

    /**
     * 获取积分活动每日统计数据
     * @param $merchant_id 商户ID
     * @param $coupon_id 优惠券ID
     * @param $type 类型
     * @param $start_time 开始时间
     * @param $end_time 结束时间
     */
    public function getPointActivityStatistics($merchant_id, $coupon_id = '', $type = '', $start_time = '', $end_time = '')
    {
        try {
            $cmd = Yii::app()->db->createCommand();

            $cmd -> select = 'pa.id, pas.date, sum(pas.browse_num) as bn, sum(pas.browse_person_num) as bpn, sum(pas.exchange_num) as en , sum(pas.exchange_person_num) as epn, sum(pas.use_num) as un, sum(pas.use_person_num) as upn, pas.exchange_rate, pas.use_rate';
            $cmd -> from = 'wq_point_activity pa';
            $cmd -> where = 'pa.merchant_id = ' . $merchant_id . ' and pa.flag = ' . FLAG_NO . ' and pas.flag = ' . FLAG_NO;
            $cmd -> join = 'LEFT JOIN wq_point_activity_statistics pas ON pas.point_activity_id = pa.id';

            $cmd -> andWhere('UNIX_TIMESTAMP(pas.date) >= :start_time and UNIX_TIMESTAMP(pas.date) <= :end_time');
            $cmd -> params[':start_time'] = $start_time;
            $cmd -> params[':end_time'] = $end_time;
            $cmd -> group('pas.date');

            //优惠券ID
            if (isset($coupon_id) && !empty($coupon_id)) {
                $cmd -> andWhere('pa.coupon_id = :coupon_id');
                $cmd -> params[':coupon_id'] = $coupon_id;
            }

            //



            $trend = $cmd -> queryAll();
//            var_dump($statistics);
            if (isset($trend) && !empty($trend)) {
                $result['status'] = ERROR_NONE;
                $result['trend'] = $trend;
            } else {
                $result['status'] = ERROR_NO_DATA;
            }
        } catch (Exception $e) {
            $result['status'] = isset($result['status']) ? $result['status'] : ERROR_EXCEPTION;
            $result['errMsg'] = $e->getMessage();
        }

        return $result;
    }

    /**
     * 获取积分活动总数据(分页)
     * @param $merchant_id 商户ID
     * @param $coupon_id 优惠券ID
     * @param $start_time 开始时间
     * @param $end_time 结束时间
     */
    public function getPointActivityAllStatistics($merchant_id, $coupon_id = '', $start_time = '', $end_time = '')
    {
        try {
            $cmd = Yii::app()->db->createCommand();
            $cmd -> select = 'pa.id, c.title, pas.date, sum(pas.browse_num) as bn, sum(pas.browse_person_num) as bpn, sum(pas.exchange_num) as en , sum(pas.exchange_person_num) as epn, sum(pas.use_num) as un, sum(pas.use_person_num) as upn, pas.exchange_rate, pas.use_rate';
            $cmd -> from = 'wq_point_activity pa';
            $cmd -> where = 'pa.merchant_id = ' . $merchant_id . ' and pa.flag = ' . FLAG_NO . ' and pas.flag = ' . FLAG_NO;
            $cmd -> leftJoin('wq_point_activity_statistics pas','pas.point_activity_id = pa.id');
            $cmd -> leftJoin('wq_coupons c', 'c.id = pa.coupon_id');

            $cmd -> andWhere('UNIX_TIMESTAMP(pas.date) >= :start_time and UNIX_TIMESTAMP(pas.date) <= :end_time');
            $cmd -> params[':start_time'] = $start_time;
            $cmd -> params[':end_time'] = $end_time;

            //优惠券ID
            if (isset($coupon_id) && !empty($coupon_id)) {
                $cmd -> andWhere('pa.coupon_id = :coupon_id');
                $cmd -> params[':coupon_id'] = $coupon_id;
            }

            $cmd -> group('pas.point_activity_id');

            $statistics = $cmd -> queryAll();

            $cmd1 = clone $cmd;
            $result1 = $cmd1->queryAll();
            $count = count($result1);
            $pages = new CPagination($count);
            $pages->pageSize = Yii::app()->params['perPage'];
            $this->page = $pages;

            if (isset($statistics) && !empty($statistics)) {
                $result['status'] = ERROR_NONE;
                $result['statistics'] = $statistics;
            } else {
                $result['status'] = ERROR_NO_DATA;
            }

        } catch (Exception $e) {
            $result['status'] = isset($result['status']) ? $result['status'] : ERROR_EXCEPTION;
            $result['errMsg'] = $e->getMessage();
        }

        return $result;
    }

    /**
     * 获取商户的活动券
     */
    public function getExchangList($merchant_id)
    {
        try {
            $cmd = Yii::app()->db->createCommand();
            $cmd->select = 'c.id, c.title as ctitle';
            $cmd->from = 'wq_point_activity pa';
            $cmd->where = 'pa.merchant_id = ' . $merchant_id . ' and pa.flag = ' . FLAG_NO;
            $cmd->join = 'LEFT JOIN wq_coupons c ON pa.coupon_id = c.id';

            $list = $cmd->queryAll();
            $result['status'] = ERROR_NONE;
            $result['list'] = $list;
        } catch (Exception $e) {
            $result['status'] = isset($result['status']) ? $result['status'] : ERROR_EXCEPTION;
            $result['errMsg'] = $e->getMessage();
        }

        return $result;
    }
    
}