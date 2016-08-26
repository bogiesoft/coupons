<?php
/**
 * 积分兑换
 */
class IntegralExchangeController extends CrmController
{
    public $layout='newMain';
    /**
     * 积分兑换列表
     */
    public function actionIntegralExchangeList ()
    {
        $merchant_id = Yii::app()->session['merchant_id'];

        $list = array();
        $pointActivityClass = new PointActivityClass();

        //优惠券名称
        $coupon_name = null;
        if (isset($_GET['coupon_name']) && !empty($_GET['coupon_name'])){
            $coupon_name = $_GET['coupon_name'];
        }

        //活动进行状态
        $time_status = null;
        if (isset($_GET['time_status']) && !empty($_GET['time_status'])){
            $time_status = $_GET['time_status'];
        }

        //活动上下架状态
        $activity_status = null;
        if (isset($_GET['activity_status']) && !empty($_GET['activity_status'])){
            $activity_status = $_GET['activity_status'];
        }
        
        
        //获取积分兑换列表
        $rs = $pointActivityClass -> getPointActivityList($merchant_id, $coupon_name, $time_status, $activity_status);
        $rs = json_decode($rs, true);

        if ($rs['status'] == ERROR_NONE){
            $list = $rs['data'];
        }

        $this->render("integralExchangeList",array(
            'list'=>$list,
            'pages' => $pointActivityClass->page,
            'time_status' => $time_status,
            'activity_status' => $activity_status,
            'coupon_name' => $coupon_name,
        ));
    }


    /**
     * 添加/编辑积分换券
     */
    public function actionIntegralExchangeAdd ()
    {
        $prev = '';//判断当前页面的上一级页面

        if (isset($_GET['diff']) && !empty($_GET['diff'])){
            $prev = 'integralExchangeInfo';
        }

        $flag = true; //添加/编辑标志
        $pointactivity = new PointActivityClass();
        if (isset($_POST) && !empty($_POST)){
            $info = array();
            $pa_id = null;

            //积分换券活动ID
            if (isset($_POST['pa_id']) && !empty($_POST['pa_id'])) {
                $pa_id = $_POST['pa_id'];
            }

            $merchant_id = Yii::app()->session['merchant_id'];
            $info['coupon_id'] = $_POST['coupon-id'];
            $rs = $pointactivity -> getPointActivityInfo($pa_id);
            $exchange_num = $rs['data']->exchange_num;
            $info['grade'] = $_POST['grade'];
            $info['limit'] = isset($_POST['limit']) && !empty($_POST['limit']) ? $_POST['limit'] : $_POST['coupon_limit'];

            if ($_POST['flag']) {
                $info['stock'] = isset($_POST['stock']) && !empty($_POST['stock']) ? $_POST['stock'] : $_POST['coupon_num'];
            } else {
                $info['stock'] = isset($_POST['stock']) && !empty($_POST['stock']) ? $_POST['stock'] + $exchange_num : $_POST['coupon_num'] + $exchange_num;
            }

            //判断活动时间类型
            if (isset($_POST['act-time']) && !empty($_POST['act-time']) && ($_POST['act-time'] == POINT_ACTIVITY_TIME_TYPE_FIXED)){
                $times = explode(' - ', $_POST['time1']);
                $info['start_time'] = date('Y-m-d 00:00:00', strtotime($times[0]));
                $info['end_time'] = date('Y-m-d 23:59:59', strtotime($times[1]));
                $info['time_type'] = POINT_ACTIVITY_TIME_TYPE_FIXED;
            }else{
                $info['time_type'] = POINT_ACTIVITY_TIME_TYPE_PERMANENT;
            }

            //判断是添加还是编辑
            if ($_POST['flag']){
                $result = $pointactivity->addPointActivity($merchant_id, $info);
            }else{
                $result = $pointactivity->editPointActivity($pa_id, $info);
            }



            if ($result['status'] == ERROR_NONE) {
                $this->redirect('IntegralExchangeList');
            }
        } elseif (isset($_GET) && !empty($_GET)) { //编辑获取信息
            $id = $_GET['id'];
            $flag = false;
            $rs = $pointactivity->getPointActivityInfo($id);
            if ($rs['status'] == ERROR_NONE) {
                $this->render("integralExchangeAdd",array(
                    'rs'=>$rs['data'],
                    'flag' => $flag,
                    'prev' => $prev
                ));
            }
        } else {
            $this->render("integralExchangeAdd",array(
                'flag' => $flag,
            ));
        }

    }

    /**
     * 查看积分详情
     */
    public function actionIntegralExchangeInfo()
    {
        $id = $_GET['id'];
        $flag = 1;
        if (isset($_GET['flag'])){
            $flag = $_GET['flag'];
        }
        $pointActivityClass = new PointActivityClass();
        $result = $pointActivityClass -> getPointActivityInfo($id);
        $this->render("IntegralExchangeInfo",array(
            'rs' => $result['data'],
            'flag' => $flag
        ));
    }

    /**
     * 上/下架活动优惠券
     */
    public function actionOnOffShelf()
    {
        $id = $_GET['id'];
        $status = $_GET['status'];
        $pointActivityClass = new PointActivityClass();
        $result = $pointActivityClass -> editPointActivityStatus($id, $status);
        echo json_encode($result);
    }

    /**
     * 删除活动优惠券
     */
    public function actionIntegralExchangeDel()
    {
        $id = $_POST['id'];
        $pointActivityClass = new PointActivityClass();
        $rs = $pointActivityClass -> delPointActivity($id);
        if ($rs['status'] == ERROR_NONE) {
            echo json_encode($rs);
        }
    }


    /**
     * 选择优惠券弹出窗
     */
    public function actionIntegralExchangeDialog ()
    {
        $couponClass = new CouponClass();
        $merchant_id = Yii::app()->session['merchant_id'];
        //查询可用的优惠券
        $coupon_lists = $couponClass->getCouponList($merchant_id);

        $this->render('integralExchangeDialog', array(
            'coupon_lists' => $coupon_lists,
            'pages' => $couponClass->page
        ));
    }

    /**
     * 积分兑换统计
     */
    public function actionIntegralExchangeStatistics ()
    {
        $merchant_id = Yii::app() -> session['merchant_id'];
        $pointActivityClass = new PointActivityClass();

        //获取积分活动券列表
        $coupon_list = $pointActivityClass -> getExchangList($merchant_id);


        $coupon_id = null; //优惠券ID
        if (isset($_GET['coupon_id']) && !empty($_GET['coupon_id'])){
            $coupon_id = $_GET['coupon_id'];
        }


        $type = null;

        $start_time = mktime(0,0,0,date('m'),date('d')-7,date('Y')); //开始时间
        $end_time = mktime(0,0,0,date('m'),date('d'),date('Y'))-1; //结束时间

        if (isset($_GET['data_time']) && !empty($_GET['data_time'])){
            $data_time = $_GET['data_time'];
            $times = explode(' - ', $data_time);
            $start_time = strtotime($times[0]);
            $end_time = strtotime($times[1]);
        }

        $rs = $pointActivityClass -> getPointActivityData($merchant_id);
        $rs_trend = $pointActivityClass -> getPointActivityStatistics($merchant_id, $coupon_id, $type, $start_time, $end_time);

        $coupon_id1 = null;
        if (isset($_GET['coupon_id1']) && !empty($_GET['coupon_id1'])){
            $coupon_id1 = $_GET['coupon_id1'];
        }

        if (isset($_GET['data_time1']) && !empty($_GET['data_time1'])){
            $data_time = $_GET['data_time1'];
            $times = explode(' - ', $data_time);
            $start_time = strtotime($times[0]);
            $end_time = strtotime($times[1]);
        }

        $rs_statistic = $pointActivityClass -> getPointActivityAllStatistics($merchant_id, $coupon_id1, $start_time, $end_time);

        $trend = array();
        if ($rs_trend['status'] == ERROR_NONE) {
            if (!empty($rs_trend['trend'])) {
                foreach ($rs_trend['trend'] as $v) {
                    $date = date('Y-m-d', strtotime($v['date']));
                    $trend[$date] = $v;
                }
            }
        }



        $this->render("integralExchangeStatistics",array(
            'list' => $coupon_list['list'],
            'trend' => $trend,
            'rs' => $rs['data'],
            'pages' => $pointActivityClass->page,
            'rs_statistic' => $rs_statistic['statistics'],
        ));
    }

    /**
     * 获取积分兑换活动下架原因
     */
    public function actionGetExpiredReason ()
    {
        $id = $_GET['id'];
        $pointActivityClass = new PointActivityClass();
        $rs = $pointActivityClass->getExpiredReason($id);
        echo json_encode($rs);
    }

}