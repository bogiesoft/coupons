<?php
include_once(dirname(__FILE__) . '/../mainClass.php');
/**
 * 用户粉丝可用type=1 or ( type!=1 and bind_status=2 );检索
 *
 */

/**
 * CRM条件分组,标签 筛选用户
 *
 * @author Jelly
 * @version 1.0
 * @since 2016-6-6
 */
class FilterUserClass 
{
    /**
     * @param $expObjs
     * @param $merchant_id
     * @return array
     * 根据用户条件筛选
     */
    private function condition_user($expObjs, $merchant_id)
    {
        $criteria = new CDbCriteria();
        foreach ($expObjs as $exp) {
            switch ($exp->name) {
                case 'condition_sex':
                    $sex = array_filter(explode(',', $exp->value));
                    if (! empty($sex))
                        $criteria->addInCondition('IFNULL(sex, 3)', $sex);
                    break;
                case 'condition_age':
                    $agemin = $exp->value->valuemin;
                    $agemax = $exp->value->valuemax;
                    if ($agemin == 0)
                        $agemin = 1;
                    if ($agemax == 0)
                        $agemax = 1;
                    if (! empty($agemin) && ! empty($agemax)) {
                        $agestart = date('Y') - $agemax;
                        $ageend = date('Y') - $agemin;
                        $criteria->addCondition($agestart . ' <= year(birthday) AND year(birthday) <= ' . $ageend);
                    }
                    break;
                case 'condition_birthday':
                    if (! empty($exp->value)) {
                        $birth = explode('-', $exp->value);
                        $birthdatemin = date('Y-m-d 00:00:00', strtotime('1970/' . trim($birth[0])));
                        $birthdatemax = date('Y-m-d 23:59:59', strtotime('1970/' . trim($birth[1])));
                        if (! empty($birthdatemin) && ! empty($birthdatemax))
                            $criteria->addBetweenCondition("DATE_FORMAT(birthday,'%m-%d')", date('m-d', strtotime($birthdatemin)), date('m-d', strtotime($birthdatemax)));
                    }
                    break;
                case 'condition_source':
                    $froms = array_filter(explode(',', $exp->value));
                    // 组合封装检索条件
                    $sql = array();
                    foreach ($froms as $v) {
                        $sql[] = "`from` like '%,{$v},%'";
                    }
                    $sqlStr = implode(' OR ', $sql);
                    $criteria->addCondition($sqlStr);
                    break;
                case 'condition_grade':
                    $membershipgrade_ids = array_filter(explode(',', $exp->value));
                    if (! empty($membershipgrade_ids))
                        $criteria->addInCondition('membershipgrade_id', $membershipgrade_ids);
                    break;
                case 'condition_value':
                    $system_value_tag = Tag::model()->find('merchant_id=:merchant_id AND `type`=:type AND category=:category AND flag=:flag', array(
                        ':merchant_id'=>$merchant_id,
                        ':type'=>TAG_TYPE_SYSTEM,
                        ':category'=>TAG_CATEGORY_CUSTOMER_VALUE,
                        ':flag'=>FLAG_NO
                    ));

                    $ids = array();
                    //不存在客户价值标签，则直接返回空数组
                    if(!isset($system_value_tag))
                        return $ids;
                    $tag_values = array_filter(explode(',', $exp->value));
                    $criteria->addInCondition('tag_value', $tag_values);
                    break;
            }
        }


        $criteria->select = 'id';
        $criteria->addCondition('merchant_id=:merchant_id AND flag=:flag AND bind_status=:bind_status');
        $criteria->params[':merchant_id'] = $merchant_id;
        $criteria->params[':flag'] = FLAG_NO;
        $criteria->params[':bind_status'] = USER_BIND_STATUS_UNBIND;



        $users = User::model()->findAll($criteria);
        $ids = array();
        foreach ($users as $user) {
            $ids[] = $user->id;
        }
        return $ids;
    }

    /**
     * 根据消费金额
     * @param unknown $obj
     * @param string $merchant_id
     * @return Ambigous <multitype:, unknown, multitype:NULL >
     */
    private function condition_consum_amount($obj, $merchant_id, $user_ids)
    {
        $ids = array();
        $codition_sql = "where wq_order.merchant_id = $merchant_id and (wq_order.order_status = ".ORDER_STATUS_NORMAL." or wq_order.order_status = ".ORDER_STATUS_PART_REFUND." or wq_order.order_status = ".ORDER_STATUS_HANDLE_REFUND.")";

        $codition_refund_sql =  " HAVING o.merchant_id = $merchant_id and (order_status = ".ORDER_STATUS_NORMAL." or order_status = ".ORDER_STATUS_PART_REFUND." or order_status = ".ORDER_STATUS_HANDLE_REFUND.")";

        if (! empty($obj->date)) {
            $datetime = explode('-', $obj->date);
            $datemin = date('Y-m-d 00:00:00', strtotime($datetime[0]));
            $datemax = date('Y-m-d 23:59:59', strtotime($datetime[1]));
            $codition_sql .= " and pay_time > '$datemin' and pay_time < '$datemax'";
            $codition_refund_sql .= " and refund_time > '$datemin' and refund_time < '$datemax'";
        }

        //消费金额
        $valuemin = $obj->value->valuemin;
        $valuemax = $obj->value->valuemax;

        //消费门店
        $store_ids = array_filter($obj->store);
        if (!empty($store_ids)) {
            $store_ids = implode(',', $store_ids);
            $codition_sql .= " and wq_order.store_id in($store_ids)";
            $codition_refund_sql .= " and o.store_id in($store_ids)";
        }

        //已筛选出来的会员
        if (!empty($user_ids)) {
            $user_ids = implode(',', $user_ids);
            $codition_sql .= " and wq_order.user_id in ($user_ids)";
            $codition_refund_sql .= " and o.user_id in($user_ids)";
        }

        $refund_sql = 'SELECT SUM(refund_money) refund_money, order_id, o.merchant_id, o.store_id, o.order_status, o.user_id, refund_time  FROM wq_refund_record r, wq_order o WHERE o.id = r.order_id GROUP BY  order_id '.$codition_refund_sql;
        $order_sql = "SELECT order_paymoney - IFNULL(refund_money, 0) AS order_paymoney, wechat_user_id, wq_order.user_id, alipay_user_id FROM wq_order  LEFT JOIN ($refund_sql) refund ON wq_order.id = refund.order_id $codition_sql";

        $user_sql = "SELECT id, SUM(orders.order_paymoney) as order_paymoney FROM wq_user LEFT JOIN ($order_sql) orders ON (wq_user.id = orders.user_id OR wq_user.wechat_id = orders.wechat_user_id OR wq_user.alipay_fuwu_id = orders.alipay_user_id) GROUP BY id HAVING order_paymoney >= $valuemin and order_paymoney <=  $valuemax";
        $connection = Yii::app()->db;
        $rs = $connection->createCommand($user_sql)->queryAll();
        if (!empty($rs)) {
            foreach($rs as $v) {
                $ids[] = $v['id'];
            }
        }

        return $ids;
    }

    /**
     * 根据客单价
     * @param unknown $obj
     * @param string $merchant_id
     */
    private function condition_consum_unitprice($obj, $merchant_id, $user_ids)
    {
        $ids = array();
        //消费实际
        $codition_sql = "where wq_order.merchant_id = $merchant_id and (wq_order.order_status = ".ORDER_STATUS_NORMAL." or wq_order.order_status = ".ORDER_STATUS_PART_REFUND." or wq_order.order_status = ".ORDER_STATUS_HANDLE_REFUND.")";
        if (! empty($obj->date)) {
            $datetime = explode('-', $obj->date);
            $datemin = date('Y-m-d 00:00:00', strtotime($datetime[0]));
            $datemax = date('Y-m-d 23:59:59', strtotime($datetime[1]));
            $codition_sql .= " and pay_time > '$datemin' and pay_time < '$datemax'";
        }
        //客单价
        $valuemin = $obj->value->valuemin;
        $valuemax = $obj->value->valuemax;
        //消费门店
        $store_ids = array_filter($obj->store);
        if (!empty($store_ids)) {
            $store_ids = implode(',', $store_ids);
            $codition_sql .= " and wq_order.store_id in($store_ids)";
        }

        //已筛选出来的会员
        if (!empty($user_ids)) {
            $user_ids = implode(',', $user_ids);
            $codition_sql .= " and wq_order.user_id in ($user_ids)";
        }

        //消费金额
        $refund_sql = 'SELECT SUM(refund_money) refund_money, order_id FROM wq_refund_record r, wq_order o WHERE o.id = r.order_id GROUP BY  order_id';
        $order_sql = "SELECT order_paymoney - IFNULL(refund_money, 0) AS order_paymoney, wechat_user_id, user_id, alipay_user_id FROM wq_order  LEFT JOIN ($refund_sql) refund ON wq_order.id = refund.order_id $codition_sql";
        $user_sql = "SELECT id, SUM(orders.order_paymoney) as order_paymoney FROM wq_user LEFT JOIN ($order_sql) orders ON (wq_user.id = orders.user_id OR wq_user.wechat_id = orders.wechat_user_id) GROUP BY id HAVING order_paymoney > 0";

        //消费次数
        $user_num_order_sql = "SELECT id, user_id, wechat_user_id, alipay_user_id FROM wq_order $codition_sql";
        $user_num_user_sql = "SELECT wq_user.id, count(orders.id) as num FROM wq_user LEFT JOIN ($user_num_order_sql) orders ON (wq_user.id = orders.user_id OR wq_user.wechat_id = orders.wechat_user_id) GROUP BY wq_user.id HAVING num > 0";

        //算出客单价
        $sql  = "SELECT paymoney.id, order_paymoney, num, FORMAT(order_paymoney / num, 2) as avg_money FROM ($user_sql) paymoney, ($user_num_user_sql) num WHERE paymoney.id = num.id and FORMAT(order_paymoney / num, 2) >= $valuemin and FORMAT(order_paymoney / num, 2) <=  $valuemax";

        $connection = Yii::app()->db;
        $rs = $connection->createCommand($sql)->queryAll();
        if (!empty($rs)) {
            foreach($rs as $v) {
                $ids[] = $v['id'];
            }
        }
        return $ids;
    }

    /**
     * 根据最高消费
     * @param unknown $obj
     * @param string $merchant_id
     */
    private function condition_consum_highestprice($obj, $merchant_id, $user_ids)
    {
        $codition_refund_sql =  " HAVING o.merchant_id = $merchant_id and (o.order_status = ".ORDER_STATUS_NORMAL." or o.order_status = ".ORDER_STATUS_PART_REFUND." or o.order_status = ".ORDER_STATUS_HANDLE_REFUND.")";
        $codition_order_sql = '';
        $codition_user_sql = '';
        if (! empty($obj->date)) {
            $datetime = explode('-', $obj->date);
            $datemin = date('Y-m-d 00:00:00', strtotime($datetime[0]));
            $datemax = date('Y-m-d 23:59:59', strtotime($datetime[1]));
            $codition_refund_sql .= " and pay_time > '$datemin' and pay_time < '$datemax'";
        }

        //消费门店
        $store_ids = array_filter($obj->store);
        if (!empty($store_ids)) {
            $store_ids = implode(',', $store_ids);
            $codition_refund_sql .= " and o.store_id in($store_ids)";
        }

        //已筛选出来的会员
        if (!empty($user_ids)) {
            $user_ids = implode(',', $user_ids);
            $codition_refund_sql .= " and o.user_id in ($user_ids)";
            $codition_order_sql  = " where wq_order.user_id in($user_ids)";
            $codition_user_sql   = " and wq_user.id in ($user_ids)";
        }

        $valuemin = $obj->value->valuemin;
        $valuemax = $obj->value->valuemax;

        $refund_sql = 'SELECT SUM(refund_money) refund_money, order_id, o.merchant_id, o.store_id, o.order_status, o.pay_time, o.user_id FROM wq_refund_record r, wq_order o WHERE o.id = r.order_id GROUP BY order_id  '. $codition_refund_sql;

        $order_sql = "SELECT id, orders.order_paymoney, orders.wechat_user_id, orders.alipay_user_id
FROM wq_user LEFT JOIN (SELECT order_paymoney - IFNULL(refund_money, 0) AS order_paymoney, wechat_user_id, wq_order.user_id, alipay_user_id FROM wq_order LEFT JOIN ($refund_sql) refund ON wq_order.id = refund.order_id  $codition_order_sql) orders ON (wq_user.id = orders.user_id OR wq_user.wechat_id = orders.wechat_user_id OR wq_user.alipay_fuwu_id = orders.alipay_user_id) WHERE bind_status = 1 $codition_user_sql ORDER BY orders.order_paymoney DESC";

        $user_sql =  "SELECT id, users.order_paymoney from ($order_sql) users   GROUP BY id  HAVING order_paymoney >= $valuemin and order_paymoney <=  $valuemax";

        $connection = Yii::app()->db;
        $rs = $connection->createCommand($user_sql)->queryAll();
        if (!empty($rs)) {
            foreach($rs as $v) {
                $ids[] = $v['id'];
            }
        }

        return $ids;
    }

    /**
     * 根据消费次数
     * @param unknown $obj
     * @param string $merchant_id
     */
    private function condition_consum_count($obj, $merchant_id, $user_ids)
    {
        $ids = array();
        $codition_sql = "where wq_order.merchant_id = $merchant_id and (wq_order.order_status = ".ORDER_STATUS_NORMAL." or wq_order.order_status = ".ORDER_STATUS_PART_REFUND." or wq_order.order_status = ".ORDER_STATUS_HANDLE_REFUND.")";

        if (! empty($obj->date)) {
            $datetime = explode('-', $obj->date);
            $datemin = date('Y-m-d 00:00:00', strtotime($datetime[0]));
            $datemax = date('Y-m-d 23:59:59', strtotime($datetime[1]));
            $codition_sql .= " and pay_time > '$datemin' and pay_time < '$datemax'";
        }

        //消费次数
        $valuemin = $obj->value->valuemin;
        $valuemax = $obj->value->valuemax;

        //消费门店
        $store_ids = array_filter($obj->store);
        if (!empty($store_ids)) {
            $store_ids = implode(',', $store_ids);
            $codition_sql .= " and wq_order.store_id in($store_ids)";
        }

        //已筛选出来的会员
        if (!empty($user_ids)) {
            $user_ids = implode(',', $user_ids);
            $codition_sql .= " and wq_order.user_id in ($user_ids)";
        }

        $order_sql = "SELECT wechat_user_id, user_id, alipay_user_id FROM wq_order  $codition_sql";

        $user_sql = "SELECT id, count(id) as num FROM wq_user LEFT JOIN ($order_sql) orders ON (wq_user.id = orders.user_id OR wq_user.wechat_id = orders.wechat_user_id OR wq_user.alipay_fuwu_id = orders.alipay_user_id) WHERE (orders.wechat_user_id IS NOT NULL OR orders.alipay_user_id IS NOT NULL OR orders.user_id IS NOT NULL) GROUP BY id HAVING num >= $valuemin and num <= $valuemax";
        $connection = Yii::app()->db;
        $rs = $connection->createCommand($user_sql)->queryAll();
        if (!empty($rs)) {
            foreach($rs as $v) {
                $ids[] = $v['id'];
            }
        }
        return $ids;
    }

    /**
     * 根据消费天数
     * @param unknown $obj
     * @param string $merchant_id
     */
    private function condition_consum_days($obj, $merchant_id, $user_ids)
    {
        $ids = array();
        $codition_sql = "where wq_order.merchant_id = $merchant_id and (wq_order.order_status = ".ORDER_STATUS_NORMAL." or wq_order.order_status = ".ORDER_STATUS_PART_REFUND." or wq_order.order_status = ".ORDER_STATUS_HANDLE_REFUND.")";

        if (! empty($obj->date)) {
            $datetime = explode('-', $obj->date);
            $datemin = date('Y-m-d 00:00:00', strtotime($datetime[0]));
            $datemax = date('Y-m-d 23:59:59', strtotime($datetime[1]));
            $codition_sql .= " and pay_time > '$datemin' and pay_time < '$datemax'";
        }

        //消费天数
        $valuemin = $obj->value->valuemin;
        $valuemax = $obj->value->valuemax;

        //消费门店
        $store_ids = array_filter($obj->store);
        if (!empty($store_ids)) {
            $store_ids = implode(',', $store_ids);
            $codition_sql .= " and wq_order.store_id in($store_ids)";
        }

        //已筛选出来的会员
        if (!empty($user_ids)) {
            $user_ids = implode(',', $user_ids);
            $codition_sql .= " and wq_order.user_id in ($user_ids)";
        }

        $order_sql = "SELECT 	user_id,wechat_user_id, alipay_user_id, left(pay_time, 10) from wq_order $codition_sql";

        $user_sql = "SELECT id, count(id) as num FROM wq_user LEFT JOIN ($order_sql) orders ON (wq_user.id = orders.user_id OR wq_user.wechat_id = orders.wechat_user_id OR wq_user.alipay_fuwu_id = orders.alipay_user_id) WHERE orders.user_id != '' and bind_status = 1 GROUP BY id HAVING num >= $valuemin and num <= $valuemax";
        $connection = Yii::app()->db;
        $rs = $connection->createCommand($user_sql)->queryAll();
        if (!empty($rs)) {
            foreach($rs as $v) {
                $ids[] = $v['id'];
            }
        }
        return $ids;
    }

    /**
     * 为消费天数区间
     * @param unknown $obj
     * @param string $merchant_id
     */
    private function condition_not_consum_days($obj, $merchant_id, $user_ids)
    {
        $ids = array();
        $codition_sql = "where wq_order.merchant_id = $merchant_id and (wq_order.order_status = ".ORDER_STATUS_NORMAL." or wq_order.order_status = ".ORDER_STATUS_PART_REFUND." or wq_order.order_status = ".ORDER_STATUS_HANDLE_REFUND.")";

        //消费门店
        $store_ids = array_filter($obj->store);
        if (!empty($store_ids)) {
            $store_ids = implode(',', $store_ids);
            $codition_sql .= " and wq_order.store_id in($store_ids)";
        }

        //已筛选出来的会员
        if (!empty($user_ids)) {
            $user_ids = implode(',', $user_ids);
            $codition_sql .= " and wq_order.user_id in ($user_ids)";
        }

        $valuemin = $obj->value->valuemin;
        $valuemax = $obj->value->valuemax;

        $order_sql = "SELECT pay_time, wechat_user_id, user_id, alipay_user_id FROM wq_order  $codition_sql";

        $user_sql = "SELECT id,timestampdiff(DAY, date_format(users.pay_time,'%y-%m-%d'), date_format(now(),'%y-%m-%d')) as days FROM (SELECT id, orders.pay_time, orders.wechat_user_id, orders.alipay_user_id FROM wq_user LEFT JOIN ($order_sql) orders ON (wq_user.id = orders.user_id OR wq_user.wechat_id = orders.wechat_user_id OR wq_user.alipay_fuwu_id = orders.alipay_user_id) ORDER BY orders.pay_time DESC) users GROUP BY id HAVING days >= $valuemin and days <= $valuemax";

        $connection = Yii::app()->db;
        $rs = $connection->createCommand($user_sql)->queryAll();
        if (!empty($rs)) {
            foreach($rs as $v) {
                $ids[] = $v['id'];
            }
        }
        return $ids;
    }

    /**
     * 添加分组点击计算
     * @param string $merchant_id
     * @param string $expJson
     */
    public function JiSuan($merchant_id = '', $expJson = '')
    {
        if (empty($merchant_id))
            $merchant_id = Yii::app()->session['merchant_id'];
        if (empty($expJson))
            $expJson = file_get_contents("php://input");


        $user_ids = array();
        try {
            $expObjs = json_decode($expJson);

            $conditionArr = array();
            foreach ($expObjs as $exp) {
                $conditionArr[$exp->name] = $exp->name;
            }

            $user_ids = $this->condition_user($expObjs, $merchant_id);
            foreach ($expObjs as $exp) {
                //消费金额
                if($exp->name == 'condition_consum_amount') {
                    $user_ids = $this->condition_consum_amount($exp, $merchant_id, $user_ids);
                }
                //客单价
                if($exp->name == 'condition_consum_unitprice') {
                    if(!isset($conditionArr['condition_consum_amount'])) {
                        $user_ids = $this->condition_consum_unitprice($exp, $merchant_id, $user_ids);
                    }
                    if(isset($conditionArr['condition_consum_amount']) && !empty($user_ids)) {
                        $user_ids = $this->condition_consum_unitprice($exp, $merchant_id, $user_ids);
                    }
                }
                //最高消费
                if($exp->name == 'condition_consum_highestprice') {
                    if(!isset($conditionArr['condition_consum_unitprice'])) {
                        $user_ids = $this->condition_consum_highestprice($exp, $merchant_id, $user_ids);
                    }
                    if(isset($conditionArr['condition_consum_unitprice']) && !empty($user_ids)) {
                        $user_ids = $this->condition_consum_highestprice($exp, $merchant_id, $user_ids);
                    }
                }
                //消费次数
                if($exp->name == 'condition_consum_count') {
                    if(!isset($conditionArr['condition_consum_highestprice'])) {
                        $user_ids = $this->condition_consum_count($exp, $merchant_id, $user_ids);
                    }
                    if(isset($conditionArr['condition_consum_highestprice']) && !empty($user_ids)) {
                        $user_ids = $this->condition_consum_count($exp, $merchant_id, $user_ids);
                    }
                }
                //消费天数
                if($exp->name == 'condition_consum_days') {
                    if(!isset($conditionArr['condition_consum_count'])) {
                        $user_ids = $this->condition_consum_days($exp, $merchant_id, $user_ids);
                    }
                    if(isset($conditionArr['condition_consum_count']) && !empty($user_ids)) {
                        $user_ids = $this->condition_consum_days($exp, $merchant_id, $user_ids);
                    }
                }
                //最近消费数据
                if($exp->name == 'condition_not_consum_days') {
                    if(!isset($conditionArr['condition_consum_days'])) {
                        $user_ids = $this->condition_not_consum_days($exp, $merchant_id, $user_ids);
                    }
                    if(isset($conditionArr['condition_consum_days']) && !empty($user_ids)) {
                        $user_ids = $this->condition_not_consum_days($exp, $merchant_id, $user_ids);
                    }
                }
            }

        } catch (Exception $e) {
            die('{"status":"error","errcode":' . $e->getCode() . ',"errmsg":"' . $e->getMessage() . '"}');
        }
//        $tmp_user_ids = array();
//        if (! empty($user_ids)) {
//            $tmp_user_ids = $all_user_ids;
//            foreach ($user_ids as $v) {
//                $tmp_user_ids = array_intersect($tmp_user_ids, $v);
//            }
//        }
//
//        $tmp_user_ids = array_values($tmp_user_ids);

        // 计算后表达式缓存
        if (! empty($merchant_id) && ! empty($expJson)) {
            $key = 'merchant_' . $merchant_id . '_group_exp_json';
            Yii::app()->memcache->set($key, $expJson);
        }

        return $user_ids;
    }

    /** 生成画图
     * @param $uids
     * @param string $merchant_id
     * @return array
     */
    public function generateHtmlJson($uids, $merchant_id = '')
    {
        if (empty($merchant_id)) {
            $merchant_id = Yii::app()->session['merchant_id'];
        }

        $grades = UserGrade::model()->findAll('merchant_id=:merchant_id and flag=:flag', array(
            ':merchant_id' => $merchant_id,
            ':flag' => FLAG_NO
        ));

        $selected_users = User::model()->findAllByPk($uids);
        $all_users = User::model()->count('merchant_id=:merchant_id and flag=:flag and bind_status=:bind_status',  array(
            ':merchant_id' => $merchant_id,
            ':flag'=>FLAG_NO,
            ':bind_status'=>USER_BIND_STATUS_UNBIND
        ));
        
        //人数
        $user_all = $all_users;
        $user_selected = count($selected_users);

        $sex_user_male = 0;
        $sex_user_female = 0;
        $sex_unknown = 0;

        $age_20_30 = 0;
        $age_30_40 = 0;
        $age_40_50 = 0;
        $age_other = 0;

        //初始化grade区间数据
        $grade_user = array();
        $grade_user_bfb = array();
        $grade_name = array();
        foreach ($grades as $grade) {
            $grade_user[$grade->id] = 0;
            $grade_user_bfb[$grade->id] = 0.00;
            $grade_name[$grade->id] = $grade->name;
        }
        $grade_user['unknown'] = 0;
        $grade_user_bfb['unknown'] = 0.00;
        $grade_name['unknown'] = '非会员';

        foreach ($selected_users as $user) {
            if ($user->sex == SEX_MALE) {
                $sex_user_male++;
            }
            if ($user->sex == SEX_FEMALE) {
                $sex_user_female++;
            }
            if (!in_array($user->sex, array(SEX_MALE, SEX_FEMALE))) {
                $sex_unknown++;
            }
            if (isset($user->birthday) && !empty($user->birthday)) {
                $age = date('Y', time()) - date('Y', strtotime($user->birthday)) + 1;
            } else {
                $age = 0;
            }
            if ($age <= 20 || $age >= 51) {
                $age_other++;
            }
            if (21 <= $age && $age <= 30) {
                $age_20_30++;
            }
            if (31 <= $age && $age <= 40) {
                $age_30_40++;
            }
            if (41 <= $age && $age <= 50) {
                $age_40_50++;
            }
            if (isset($user->membershipgrade_id) && !empty($user->membershipgrade_id)) {
                $grade_user[$user->membershipgrade_id]++;
            } else {
                $grade_user['unknown']++;
            }
        }

        $customer_value_tag_values = $GLOBALS['__TAG_CATEGORY_CUSTOMER_VALUE'];
        //初始化
        $value_tag_count = array();
        foreach ($customer_value_tag_values as $k => $v) {
            $value_tag_count[$k] = array();
            $value_tag_count[$k]['name'] = $v;
            $value_tag_count[$k]['count'] = 0;
            $value_tag_count[$k]['bfb'] = 0.00;
        }
        //初始化显示模板
        $total_user_tpl = '<span>总体<em class="num">%u</em>位客户，共选中<em class="num">%u</em>位客户，占总体<em class="num">%g%%</em></span>';

        $user_age_tpl = '<div><span class="calc_label">20~30岁：<em class="num">%u</em>人</span> <span class="per per_user"><span class="per per_user per_user_choosed" style="width:%g%%"></span></span> <span>%g%%</span></div><div><span class="calc_label">30~40岁：<em class="num">%u</em>人</span> <span class="per_user per"><span class="per per_user per_user_choosed" style="width:%g%%"></span></span> <span>%g%%</span></div><div><span class="calc_label">40~50岁：<em class="num">%u</em>人</span> <span class="per_user per"><span class="per per_user per_user_choosed" style="width:%g%%"></span></span> <span>%g%%</span></div><div><span class="calc_label">其他：<em class="num">%u</em>人</span> <span class="per_user per"><span class="per per_user per_user_choosed" style="width:%g%%"></span></span> <span>%g%%</span></div>';

        $user_sex_tpl = '<div class="mb10"><span class="calc_label">男性：<em class="num">%u</em>人</span> <span class="per per_sex"><span class="male per per_sex" style="width:%g%%"></span></span> <span>%g%%</span></div><div><span class="calc_label">女性：<em class="num">%u</em>人</span> <span class="per_sex per"><span class="female per per_sex" style="width:%g%%"></span></span> <span>%g%%</span></div><span class="unknow">未知性别占总体<em class="num">%g%%</em>，共<em class="num">%u</em>人</span>';

        $user_grade_item_tpl = '<div><span class="calc_label">%s：<em class="num">%u</em>人</span> <span class="per per_user"><span class="per per_user per_user_choosed" style="width:%g%%"></span></span> <span>%g%%</span></div>';
        $user_value_tpl = '<ul class="value">%s</ul>';
        $user_value_item_tpl = '<li><i class="icon_value icon_value_%u"><em class="icon_value_num">%g%%</em></i><p>%s</p><p><em class="num">%u</em>人</p></li>';

        if ($user_selected != 0) {
            //总人数
            $total_user_bfb = number_format($user_selected / $user_all, 4) * 100;
            $total_user_html = sprintf($total_user_tpl, $user_all, $user_selected, $total_user_bfb);

            //年龄
            $age_20_30_bfb = number_format($age_20_30 / $user_selected, 4) * 100;
            $age_30_40_bfb = number_format($age_30_40 / $user_selected, 4) * 100;
            $age_40_50_bfb = number_format($age_40_50 / $user_selected, 4) * 100;
            $age_other_bfb = number_format($age_other / $user_selected, 4) * 100;
            $user_age_html = sprintf($user_age_tpl, $age_20_30, $age_20_30_bfb, $age_20_30_bfb, $age_30_40, $age_30_40_bfb, $age_30_40_bfb, $age_40_50, $age_40_50_bfb, $age_40_50_bfb, $age_other, $age_other_bfb, $age_other_bfb);

            //性别
            $sex_user_female_bfb = number_format($sex_user_female / $user_selected, 4) * 100;
            $sex_user_male_bfb = number_format($sex_user_male / $user_selected, 4) * 100;
            $sex_unknown_bfb = number_format($sex_unknown / $user_selected, 4) * 100;
            $user_sex_html = sprintf($user_sex_tpl, $sex_user_male, $sex_user_male_bfb, $sex_user_male_bfb, $sex_user_female, $sex_user_female_bfb, $sex_user_female_bfb, $sex_unknown_bfb, $sex_unknown);

            $user_grade_html = '';

            foreach ($grade_user as $k => $v) {
                $v_bfb = number_format($v / $user_selected, 4) * 100;
                $user_grade_item_html = sprintf($user_grade_item_tpl, $grade_name[$k], $v, $v_bfb, $v_bfb);
                $user_grade_html .= $user_grade_item_html;
            }

            //客户价值标签
            $system_value_tag = Tag::model()->find('merchant_id=:merchant_id AND `type`=:type AND `category`=:category AND flag=:flag', array(
                ':merchant_id' => $merchant_id,
                ':type' => TAG_TYPE_SYSTEM,
                ':category' => TAG_CATEGORY_CUSTOMER_VALUE,
                ':flag' => FLAG_NO,
            ));

            if (isset($system_value_tag) && !empty($system_value_tag)) {
                //获取客户价值的用户标签和值
                $criteria = new CDbCriteria();
                $criteria->addCondition('tag_id=:tag_id AND flag=:flag');
                $criteria->params[':tag_id'] = $system_value_tag->id;
                $criteria->params[':flag'] = FLAG_NO;
                $criteria->addInCondition('user_id', $uids);
                $userTags = UserTag::model()->findAll($criteria);

                foreach ($userTags as $k => $v) {
                    $cur_count = $value_tag_count[$v->tag_value]['count'] + 1;
                    $value_tag_count[$v->tag_value]['count'] = $cur_count;
                    $value_tag_count[$v->tag_value]['bfb'] = number_format($cur_count / $user_selected, 4) * 100;
                }
            }
            $user_value_item_html = '';
            foreach ($value_tag_count as $k => $v) {
                $user_value_item_html .= sprintf($user_value_item_tpl, $k, $v['bfb'], $v['name'], $v['count']);
            }
            $user_value_html = sprintf($user_value_tpl, $user_value_item_html);
        } else {
            $total_user_html = sprintf($total_user_tpl, $user_all, 0, 0);
            $user_age_html = sprintf($user_age_tpl, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);
            $user_sex_html = sprintf($user_sex_tpl, 0, 0, 0, 0, 0, 0, 0, 0);
            $user_grade_html = '';
            $user_value_html = '';
            foreach ($grade_user as $k => $v) {
                $v_bfb = 0;
                $user_grade_item_html = sprintf($user_grade_item_tpl, $grade_name[$k], $v, $v_bfb, $v_bfb);
                $user_grade_html .= $user_grade_item_html;
            }
            $user_value_item_html = '';
            foreach ($value_tag_count as $k => $v) {
                $user_value_item_html .= sprintf($user_value_item_tpl, $k, $v['bfb'], $v['name'], $v['count']);
            }
            $user_value_html = sprintf($user_value_tpl, $user_value_item_html);
        }

        return array(
            'status' => 'ok',
            'html' => array(
                'total_user' => $total_user_html,
                'user_age' => $user_age_html,
                'user_sex' => $user_sex_html,
                'user_grade' => $user_grade_html,
                'user_value' => $user_value_html
            )
        );
    }

    /** 分组计划任务
     * @param string $group_id 分组id
     * @return array
     */
    public function GroupTask($group_id = '')
    {
        $start_time = microtime(true);

        if (!empty($group_id)) { //重新计算更新分组
            $group = UserGroup::model()->findByPk($group_id);
            try {
                $now_time_user_ids = $this->JiSuan($group->merchant_id, $group->filter_condition);
                $oldGroups = Group::model()->findAll('group_id=:group_id AND flag=:flag', array(
                    ':group_id' => $group->id,
                    ':flag' => FLAG_NO
                ));
                $last_time_user_ids = array();
                foreach ($oldGroups as $v) {
                    $last_time_user_ids[] = $v->user_id;
                }
                //出组的
                $del_user_ids = array_diff($last_time_user_ids, $now_time_user_ids);
                //进组的
                $in_user_ids = array_diff($now_time_user_ids, $last_time_user_ids);
                $this->delGroupUser($del_user_ids, $group->id, $group->label_id);
                $this->addGroupUser($in_user_ids, $group->id, $group->label_id);

                //刷新分组人数
                $group->num = Group::model()->count('group_id = :group_id', array(':group_id' => $group->id));
                $group->last_time = date('Y-m-d H:i:s');
                $group->save();

                $data['now_time_user_ids'] = $now_time_user_ids;
                $data['last_time'] = $group->last_time;
                $data['group_num'] = $group->num;
                return $data;
            } catch (Exception $e) {
                return $e->getMessage();
                exit;
            }
        } else { //计划任务更新分组
            $groups = UserGroup::model()->findAll('type = :type and flag = :flag', array(
                ':type' => GROUP_TYPE_DYNAMIC,
                ':flag' => FLAG_NO
            ));

            try {
                foreach ($groups as $group) {
                    $now_time_user_ids = $this->JiSuan($group->merchant_id, $group->filter_condition);
                    $oldGroups = Group::model()->findAll('group_id=:group_id AND flag=:flag', array(':group_id' => $group->id, ':flag' => FLAG_NO));
                    $last_time_user_ids = array();
                    foreach ($oldGroups as $v) {
                        $last_time_user_ids[] = $v->user_id;
                    }
                    //出组的
                    $del_user_ids = array_diff($last_time_user_ids, $now_time_user_ids);
                    //进组的
                    $in_user_ids = array_diff($now_time_user_ids, $last_time_user_ids);

                    $this->delGroupUser($del_user_ids, $group->id, $group->label_id);
                    $this->addGroupUser($in_user_ids, $group->id, $group->label_id);
                    //刷新分组人数
                    $group->num = Group::model()->count('group_id = :group_id', array(':group_id' => $group->id));
                    $group->last_time = date('Y-m-d H:i:s');
                    $group->save();
                }
            } catch (Exception $e) {
                echo '出错了';
                exit;
            } 
        }
    }

    /**
     * @param $del_user_ids 删除
     * @param $group_id
     * @param $label_id
     *
     * 删除分组用户
     */
    private function delGroupUser($del_user_ids, $group_id, $label_id) {
        if (!empty($del_user_ids)) {
            $criteria = new CDbCriteria();
            $criteria->addInCondition('user_id', $del_user_ids);
            $criteria->addCondition('group_id = :group_id');
            $criteria->params[':group_id'] = $group_id;
            $del_group = Group::model()->deleteAll($criteria);
            //删除支付宝服务窗标签组里的会员
            $AliServiceWindowLabel = new AliServiceWindowLabel();
            foreach($del_user_ids as $v) {
                $user = User::model()->findByPk($v);
                $AliServiceWindowLabel->lableUserDel($label_id, $user->alipay_fuwu_id);
            }
        }
    }

    /**
     * 添加分组用户
     */
    private function addGroupUser($in_users, $group_id, $label_id) {
        $AliServiceWindowLabel = new AliServiceWindowLabel();
        foreach ($in_users as $v) {
            $new_group = new Group();
            $new_group->user_id = $v;
            $new_group->group_id = $group_id;
            $new_group->create_time = date('Y-m-d H:i:s');
            if ($new_group->save()) {
                $user = User::model()->findByPk($v);
                $AliServiceWindowLabel->lableUserAdd($label_id, $user->alipay_fuwu_id);
            }
        }
    }

    ############################### 分隔线 以上为分组部分 ################################

    ############################### 分隔线 以下为助手方法部分 ################################


    /** 获取用户粉丝信息
     * @param string $wechat_id
     * @param string $alipay_fuwu_id
     * @param string $merchant_id
     * @return mixed
     */
    public static function getFsInfo($wechat_id = '', $alipay_fuwu_id = '', $merchant_id = '')
    {
        if (empty($merchant_id))
            $merchant_id = Yii::app()->session['merchant_id'];
        if (!empty($wechat_id))
            return User::model()->find('merchant_id=:merchant_id AND wechat_id=:wechat_id AND flag=:flag AND `type`=:type', array(
                ':merchant_id' => $merchant_id,
                ':wechat_id' => $wechat_id,
                ':flag' => FLAG_NO,
                ':type' => USER_TYPE_WECHAT_FANS
            ));
        if (!empty($alipay_fuwu_id))
            return User::model()->find('merchant_id=:merchant_id AND alipay_fuwu_id=:alipay_fuwu_id AND flag=:flag AND `type`=:type', array(
                ':merchant_id' => $merchant_id,
                ':alipay_fuwu_id' => $alipay_fuwu_id,
                ':flag' => FLAG_NO,
                ':type' => USER_TYPE_ALIPAY_FANS
            ));
    }
}