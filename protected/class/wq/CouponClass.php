<?php
include_once(dirname(__FILE__) . '/../mainClass.php');

/**
 * 优惠券类
 * 姚小磊
 * 2016/7/15
 */
class CouponClass extends mainClass
{
    public $page = null;

    /** 查询商户的所有可用的优惠券
     * @param $merchant_id 商户id
     * @return mixed
     */
    public function getCouponUsable($merchant_id)
    {
        $cmd = Yii::app()->db->createCommand();
        $cmd->select('a.*');
        $cmd->from('wq_coupons a');

        $cmd->andWhere('a.merchant_id = :merchant_id');
        $cmd->params[':merchant_id'] = $merchant_id;

        $cmd->andWhere('a.flag = :flag');
        $cmd->params[':flag'] = FLAG_NO;

        $cmd->andWhere('a.if_invalid = :if_invalid');
        $cmd->params[':if_invalid'] = IF_INVALID_NO;

        $cmd->andWhere('((a.time_type = :time_type1 AND NOW() < a.end_time) OR (a.time_type = :time_type2))');
        $cmd->params[':time_type1'] = VALID_TIME_TYPE_FIXED;
        $cmd->params[':time_type2'] = VALID_TIME_TYPE_RELATIVE;


        $cmd->order('a.create_time DESC');

        //分页
        $cmd1 = clone $cmd;
        $result1 = $cmd1->queryAll();

        $pages = new CPagination(count($result1));
        $pages->pageSize = 7;
        $this->page = $pages;

        $cmd->offset = (isset($_GET['page']) ? ($_GET['page'] - 1) : 0) * $pages->pageSize;
        $cmd->limit = $pages->pageSize;

        $all_coupon = $cmd->queryAll();

        return $all_coupon;
    }

    /** 根据id获取优惠券信息
     * @param $coupon_id 优惠券id
     * @return mixed
     */
    public function getCouponInfo($coupon_id)
    {
        $res_coupon = Coupons::model()->findByPk($coupon_id);
        return $res_coupon;
    }

    /**
     * 获取优惠券列表
     * @param int $merchant_id 商户id
     */
    public function getCouponList ($merchant_id)
    {
        $cmd = Yii::app()->db->createCommand();
        $cmd->select('a.*');
        $cmd->from('wq_coupons a');

        $cmd->andWhere('a.merchant_id = :merchant_id');
        $cmd->params[':merchant_id'] = $merchant_id;

        $cmd->andWhere('a.flag = :flag');
        $cmd->params[':flag'] = FLAG_NO;

        $cmd->andWhere('a.if_invalid = :if_invalid');
        $cmd->params[':if_invalid'] = IF_INVALID_NO;

        $cmd->andWhere('((a.time_type = :time_type1 AND NOW() < a.end_time) OR (a.time_type = :time_type2))');
        $cmd->params[':time_type1'] = VALID_TIME_TYPE_FIXED;
        $cmd->params[':time_type2'] = VALID_TIME_TYPE_RELATIVE;


        $cmd->andWhere('a.num - a.get_num > 0');

        $cmd->andWhere('a.id NOT IN ( select pa.coupon_id from wq_point_activity pa where pa.merchant_id = a.merchant_id and flag = :flag )');
        $cmd->params[':flag'] = FLAG_NO;
        

        $cmd->order('a.create_time DESC');

        //分页
        $cmd1 = clone $cmd;
        $result1 = $cmd1->queryAll();

        $pages = new CPagination(count($result1));
        $pages->pageSize = 9;
        $this->page = $pages;

        $cmd->offset = (isset($_GET['page']) ? ($_GET['page'] - 1) : 0) * $pages->pageSize;
        $cmd->limit = $pages->pageSize;

        $all_coupon = $cmd->queryAll();

        return $all_coupon;
    }
}