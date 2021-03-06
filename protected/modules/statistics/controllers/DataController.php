<?php

/**
 * 统计管理
 */
class DataController extends StatisiticsController
{
    /**
     * 用户统计
     */
    public function actionUser(){
        set_time_limit(0);
        ini_set('memory_limit', '1500M');
        $merchant_id = Yii::app() -> session['merchant_id'];
        $userC = new UserC();
        //获取累计用户，今日新增用户，今日到店消费用户
        $result = json_decode($userC -> getUserNumAndNewuserNumAndPurchaseUser($merchant_id));
        if($result -> status == ERROR_NONE){
            $head = $result -> data;
        }
        //获取用户来源和性别数量
        $result_from_sex = json_decode($userC -> getUserFromNumAndUserSexNum($merchant_id));
        if($result_from_sex -> status == ERROR_NONE){
            $user_from_sex = $result_from_sex -> data;
        }
        //获取用户年龄段
        $result_age = json_decode($userC -> getUserAgeNum($merchant_id));
        if($result_age -> status == ERROR_NONE){
            $user_age = $result_age -> data;
        }
        $this->render('user',array(
            'head' => $head,
            'user_from_sex' => $user_from_sex,
            'user_age' => $user_age
        ));
    }

    /**
     * 财务统计
     */
    public function actionFinance(){
        $finance = new FinanceC();
        $merchant_id = Yii::app() -> session['merchant_id'];
        $storeC = new StoreC();
        $manager_id = '';
        if(Yii::app() -> session['role'] == WQ_ROLE_MANAGER){
            $manager_id = Yii::app() -> session['manager_id'];
        }

        //获取门店和门店分组
        $result = json_decode($storeC -> getStoreCountAndManagement($merchant_id,$manager_id));
        if($result -> status == ERROR_NONE){
            $store_count = $result -> data -> store_count;
            $management = $result -> data -> management;
            $store = $result -> data -> store;
        }

        //计算今日金额
        $orderC = new OrderC();
        $result_order = json_decode($orderC -> countTotalAndAlipayAndWechatToday($merchant_id,$manager_id));
        if($result_order -> status == ERROR_NONE){
            $total_money_count = $result_order -> data -> total_money_count;
            $alipay_money_count = $result_order -> data -> alipay_money_count;
            $alipay_num_count = $result_order -> data -> alipay_num_count;
            $wechat_money_count = $result_order -> data -> wechat_money_count;
            $wechat_num_count = $result_order -> data -> wechat_num_count;
        }
        //交易趋势分析
        $result_trend = json_decode($orderC -> getReportFormDay('', '', '',$merchant_id,$manager_id));
        if($result_trend -> status == ERROR_NONE){
            $trade_trend = $result_trend -> data;
        }

        $result_achievement = json_decode($orderC -> getStoreTrade('', '', $merchant_id,$manager_id));
        if($result_achievement -> status == ERROR_NONE){
            $trade_achievement = $result_achievement -> data;
        }

        $this->render('finance',array(
            'store_count' => $store_count,
            'management' => $management,
            'store' => $store,
            'total_money_count' => $total_money_count,
            'alipay_money_count' => $alipay_money_count,
            'alipay_num_count' => $alipay_num_count,
            'wechat_money_count' => $wechat_money_count,
            'wechat_num_count' => $wechat_num_count,
            'trade_trend' => $trade_trend,
            'trade_achievement' => $trade_achievement,

        ));
    }


    /**
     * ajax
     * 财务统计----获取下级门店分组和门店
     * */
    public function actionGetSubManagementAndStore(){
        if(isset($_POST['id']) && !empty($_POST['id'])){
            $management_id = $_POST['id'];
            //如果角色是管理员，则获取管理员id
            $manager_id = '';
            if(Yii::app()->session ['role'] == WQ_ROLE_MANAGER){
                $manager_id = Yii::app()->session ['manager_id'];
            }
            $storeC = new StoreC();
            echo $storeC -> getSubmanagementAndStore($management_id,$manager_id);
        }
    }

    /**
     * ajax
     * 财务统计----获取二级门店分组的门店
     */
    public function actionGetStoreByManagement(){
        if(isset($_POST['id']) && !empty($_POST['id'])){
            $management_id = $_POST['id'];
            $manager_id = '';
            if(Yii::app() -> session['role'] == WQ_ROLE_MANAGER){
                $manager_id = Yii::app() -> session['manager_id'];
            }
            $storeC = new StoreC();
            echo $storeC -> getStoreByManagement($management_id,$manager_id);
        }
    }

    /*
     * ajax
     * 财务统计----获取交易汇总
     */
    public function actionGetTradeTrend(){
        if(isset($_POST['time']) && !empty($_POST['time'])){
            $merchant_id = Yii::app() -> session['merchant_id'];

            $manager_id = '';
            if(Yii::app() -> session['role'] == WQ_ROLE_MANAGER){
                $manager_id = Yii::app() -> session['manager_id'];
            }

            $time = $_POST['time'];
            $arr_time = explode('-',str_replace(' ','',$time));
            $store_id = $_POST['store_id'];
            $orderC = new OrderC();
            echo $orderC -> getReportFormDay($arr_time[0], $arr_time[1], $store_id,$merchant_id,$manager_id);
        }
    }

    /**
     * ajax
     * 财务统计----获取门店交易
     */
    public function actionGetStoreTrade(){
        if(isset($_POST['time']) && !empty($_POST['time'])){
            $merchant_id = Yii::app() -> session['merchant_id'];

            $manager_id = '';
            if(Yii::app() -> session['role'] == WQ_ROLE_MANAGER){
                $manager_id = Yii::app() -> session['manager_id'];
            }

            $time = $_POST['time'];
            $arr_time = explode('-',str_replace(' ','',$time));
            $orderC = new OrderC();
            echo $orderC -> getStoreTrade($arr_time[0], $arr_time[1],$merchant_id,$manager_id);
        }
    }

}

