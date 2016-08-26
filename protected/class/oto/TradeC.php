<?php
include_once(dirname(__FILE__).'/../mainClass.php');

class TradeC extends mainClass{
	public $page = null;
	
	/**
	 * 获取交易列表       ( 可用于导出excel表 )
	 * @param 必选   $merchant_id    商户id
	 * @param 可选   $order_no       订单号
	 * @param 可选   $start_time     交易时间-开始                                   默认今日
	 * @param 可选   $end_time       交易时间-结束                                   默认今日
	 * @param 可选   $store_id       门店id（数组）                               默认全部门店
	 * @param 可选   $operator_id    操作员id             默认全部操作员
	 * @param 可选   $trade_type     交易类型（收款，退款）                  默认全部交易类型
	 * @param 可选   $order_status   订单状态                                              默认全部状态
	 * @param 可选   $pay_channel    支付渠道                                              默认全渠道
	 * @param 可选   $page_no        是否分页,用于导出excel   默认分页
	 */
	public function getTradeList($merchant_id,$order_no,$start_time,$end_time,
	    $store_id,$operator_id,$trade_type,$order_status,$pay_channel,$page_no=NULL)
	{
	    $result = array();
	    try {
	        //验证参数
	        if (empty($merchant_id)){
	            $result['status'] = ERROR_PARAMETER_MISS;
	            throw new Exception('参数merchant_id必填');
	        }
	        //查询1条件
	        $select1 = 'o.merchant_id, o.id AS order_id, o.order_no, o.create_time, o.pay_status,
	            o.store_id, s.name AS store_name, s.branch_name,s.number AS store_number,
	            o.operator_id, op.name AS operator_name, op.number AS operator_number,
	            o.pay_time, o.pay_channel, o.order_paymoney, o.alipay_account, o.wxpay_discount_money,
	            o.coupons_money, o.discount_money, o.merchant_discount_money, o.alipay_discount_money,
	            o.flag AS refund_money, o.order_status,o.flag AS order_flag';
	        $from1 = 'wq_order AS o';
	        $join1 = ' INNER JOIN wq_store AS s ON s.id = o.store_id';
	        $join1 .= ' LEFT JOIN wq_operator AS op ON op.id = o.operator_id';
	        $where1 = 'o.flag = '.FLAG_NO;
	        //查询2条件
	        $select2 = 'o.merchant_id, o.id AS order_id, o.order_no, r.create_time, o.pay_status,
	            o.store_id, s.name AS store_name, s.branch_name,s.number AS store_number,
	            r.operator_id, op.name AS operator_name, op.number AS operator_number,
	            r.refund_time, r.refund_channel, o.order_paymoney, o.alipay_account, o.wxpay_discount_money,
	            o.coupons_money, o.discount_money, o.merchant_discount_money, o.alipay_discount_money,
	            r.refund_money, o.order_status, o.order_type AS order_flag';
	        $from2 = 'wq_refund_record AS r';
	        $join2 = ' INNER JOIN wq_order AS o ON r.order_id = o.id';
	        $join2 .= ' INNER JOIN wq_store AS s ON s.id = o.store_id';
	        $join2 .= ' LEFT JOIN wq_operator AS op ON op.id = r.operator_id'; 

	        $where2 = 'r.flag = '.FLAG_NO;
	        $where2 .= ' AND o.flag = '.FLAG_NO;
	        //查询3条件
	        $orderBy3 = 't.pay_time DESC';
	        //搜索条件
            if (!empty($order_no)){
                //订单号搜索
                $where1 .= ' AND o.order_no = '.$order_no;
	            $where2 .= ' AND o.order_no = '.$order_no;
	        }else{
	            $where1 .= ' AND o.pay_status = '.ORDER_STATUS_PAID;//已支付订单
	            $where1 .= ' AND o.order_type = '.ORDER_TYPE_CASHIER;//收银订单
	            $where1 .= ' AND o.order_status != '.ORDER_STATUS_REVOKE;//已撤销
	            
	            $where2 .= ' AND r.status != '.REFUND_STATUS_FAIL;//退款失败
	            $where2 .= ' AND r.type = '.REFUND_TYPE_REFUND;//退款
	            $where2 .= ' AND o.order_status != '.ORDER_STATUS_REVOKE;//已撤销
                //时间搜索
	            if (!empty($start_time) && !empty($end_time)){
	                $start_time1 = date("Y-m-d 00:00:00",strtotime($start_time));
	                $end_time1 = date("Y-m-d 23:59:59",strtotime($end_time));
	                $where1 .= " AND o.pay_time >= '".$start_time1."'";
	                $where2 .= " AND r.refund_time >= '".$start_time1."'";
	                $where1 .= " AND o.pay_time <= '".$end_time1."'";
	                $where2 .= " AND r.refund_time <= '".$end_time1."'";
	            }else{//默认今日
	                $start_time1 = date("Y-m-d 00:00:00");
	                $end_time1 = date("Y-m-d 23:59:59");
	                $where1 .= " AND o.pay_time >= '".$start_time1."'";
	                $where2 .= " AND r.refund_time >= '".$start_time1."'";
	                $where1 .= " AND o.pay_time <= '".$end_time1."'";
	                $where2 .= " AND r.refund_time <= '".$end_time1."'";
	            }
	            //门店搜索
	            $storeId_arr = array();
	            if (!empty($store_id)){
	                $storeId_arr = $store_id;
	            }else{
	                if(Yii::app ()->session ['role'] == WQ_ROLE_MANAGER){ //管理员只能操作分配的门店
	                    $storeId = Yii::app ()->session ['store_id'];
	                    if (!empty($storeId)){
	                        $storeId = substr ( $storeId, 1, strlen ( $storeId ) - 2 );
	                        $storeId_arr = explode ( ',', $storeId );
	                    }
	                }else{
	                    $storeId_arr = $this -> getMerchantLowerStoreId($merchant_id);
	                }
	            }
	            //获取关联门店id,得到id并集
	            $storeId_arr = $this->getRelationStoresId($storeId_arr);
	            $store_str = implode(',', $storeId_arr);
	            $where1 .= " AND o.store_id IN (".$store_str.")";
	            $where2 .= " AND o.store_id IN (".$store_str.")";
	            //操作员搜索
	            if (!empty($operator_id)){
	                $where1 .= ' AND o.operator_id = '.$operator_id;
	                $where2 .= ' AND r.operator_id = '.$operator_id;
	            }
	            //交易类型搜索(收/退款)
	            if (!empty($trade_type)){
	                if ($trade_type == 1){
	                    $where2 .= ' AND o.flag = 999';
	                }else if ($trade_type == 2){
	                    $where1 .= ' AND o.flag = 999';
	                }
	            }
	            //订单状态搜索
	            if (!empty($order_status)){
	                $where1 .= ' AND o.order_status = '.$order_status;
	                $where2 .= ' AND o.order_status = '.$order_status;
	            }
	            //支付渠道搜索
	            if (!empty($pay_channel)){
	                $pay_channel_str = implode(',', $pay_channel);
	                $where1 .= " AND o.pay_channel IN (".$pay_channel_str.")";
	                $where2 .= " AND r.refund_channel IN (".$pay_channel_str.")";
	            }
	        }
	        $sql1 = 'SELECT '.$select1.' FROM '.$from1.$join1.' WHERE '.$where1;
	        $sql2 = 'SELECT '.$select2.' FROM '.$from2.$join2.' WHERE '.$where2;
	        $sql3 = 'SELECT * FROM ( '.$sql1.' UNION ALL '.$sql2.') AS t ORDER BY '.$orderBy3;
    		$list = Yii::app()->db->createCommand($sql3)->queryAll();
    		//数据是否为空
    		if (empty($list)){
    		    $result['status'] = ERROR_NO_DATA;
    		    throw new Exception('数据为空');
    		}
    		
    		//分页
    		if (empty($page_no)){//导出表时不分页
    		    $count = count($list);
    		    $pages = new CPagination($count);
    		    $pages -> pageSize = 20;
    		    $this -> page = $pages;
    		    $page = 1;
    		    if (isset($_GET['page']) && !empty($_GET['page'])){
    		        $page = $_GET['page'];
    		    }
    		    $offset = $page*20-20;
    		    $data['list'] = array_slice($list, $offset, 20);
    		}else{
    		    $data['list'] = $list;
    		}
    		//初始化数据
    		$data['statistics'] = array(
    		    'total_trade_money' =>0,          //总订单金额
    		    'total_trade_actual_money' =>0,   //总实际订单金额
    		    'total_trade_num' =>0,            //总订单数量
    		    'total_discount_money' =>0,       //总优惠金额
    		    'total_refund_money' =>0,         //总退款金额
    		    'total_refund_num' =>0,           //总退款笔数
    		    
    		    'alipay_trade_money' =>0,
    		    'alipay_trade_actual_money' =>0,
    		    'alipay_trade_num' =>0,
    		    'alipay_discount_money' =>0,
    		    'alipay_refund_money' =>0,
    		    'alipay_refund_num' =>0,
    		    
    		    'wechat_trade_money' =>0,
    		    'wechat_trade_actual_money' =>0,
    		    'wechat_trade_num' =>0,
    		    'wechat_discount_money' =>0,
    		    'wechat_refund_money' =>0,
    		    'wechat_refund_num' =>0,
    		    
    		    'unionpay_trade_money' =>0,
    		    'unionpay_trade_actual_money' =>0,
    		    'unionpay_trade_num' =>0,
    		    'unionpay_discount_money' =>0,
    		    'unionpay_refund_money' =>0,
    		    'unionpay_refund_num' =>0,
    		    
    		    'stored_trade_money' =>0,
    		    'stored_trade_actual_money' =>0,
    		    'stored_trade_num' =>0,
    		    'stored_discount_money' =>0,
    		    'stored_refund_money' =>0,
    		    'stored_refund_num' =>0,
    		    
    		    'cash_trade_money' =>0,
    		    'cash_trade_actual_money' =>0,
    		    'cash_trade_num' =>0,
    		    'cash_discount_money' =>0,
    		    'cash_refund_money' =>0,
    		    'cash_refund_num' =>0,
    		    
    		    'nopay_trade_money' =>0,
    		    'nopay_trade_actual_money' =>0,
    		    'nopay_trade_num' =>0,
    		    'nopay_discount_money' =>0,
    		    'nopay_refund_money' =>0,
    		    'nopay_refund_num' =>0,
    		    
    		    'group_trade_money' =>0,
    		    'group_trade_actual_money' =>0,
    		    'group_trade_num' =>0,
    		    'group_discount_money' =>0,
    		    'group_refund_money' =>0,
    		    'group_refund_num' =>0,
    		);
    		foreach ($list as $v){
    		    $pay_channel = $v['pay_channel']; //支付方式
    		    $order_paymoney = $v['order_paymoney']; //订单总金额
    		    $coupons_money = $v['coupons_money']; //优惠券优惠金额
    		    $discount_money = $v['discount_money']; //会员优惠
    		    $m_discount_money = $v['merchant_discount_money']; //商家优惠
    		    $refund_money = $v['refund_money']; //退款金额
    		    $order_flag = $v['order_flag']; //交易类型: 收/退款
    		    
    		    if ($pay_channel != ORDER_PAY_CHANNEL_ALIPAY_SM &&
    		        $pay_channel != ORDER_PAY_CHANNEL_ALIPAY_TM &&
    		        $pay_channel != ORDER_PAY_CHANNEL_WXPAY_SM &&
    		        $pay_channel != ORDER_PAY_CHANNEL_WXPAY_TM &&
    		        $pay_channel != ORDER_PAY_CHANNEL_STORED &&
    		        $pay_channel != ORDER_PAY_CHANNEL_CASH &&
    		        $pay_channel != ORDER_PAY_CHANNEL_UNIONPAY &&
    		        $pay_channel != ORDER_PAY_CHANNEL_NO_MONEY &&
    		        $pay_channel != ORDER_PAY_CHANNEL_GROUP ) {
    				//非统计的支付数据
    				continue;
		        }
    		    if ($order_flag == FLAG_NO){//收款记录
    		        //优惠金额
    		        $discount_money_total = $coupons_money + $discount_money + $m_discount_money;
    		        //实收金额
    		        $receipt_money = $order_paymoney - $discount_money_total;
    		        if ($v['order_status'] == ORDER_STATUS_REVOKE || $v['pay_status']==ORDER_STATUS_UNPAID){
    		            $receipt_money = 0;
    		        }
    		        //计算交易金额和实收金额和交易笔数
    		        switch ($pay_channel) {
    		            case ORDER_PAY_CHANNEL_ALIPAY_SM: //支付宝扫码
    		                $data['statistics']['alipay_trade_money'] += $order_paymoney;
    		                $data['statistics']['alipay_trade_actual_money'] += $receipt_money;
    		                $data['statistics']['alipay_trade_num'] += 1;
    		                $data['statistics']['alipay_discount_money'] += $discount_money_total;
    		                break;
    		            case ORDER_PAY_CHANNEL_ALIPAY_TM: //支付宝条码
    		                $data['statistics']['alipay_trade_money'] += $order_paymoney;
    		                $data['statistics']['alipay_trade_actual_money'] += $receipt_money;
    		                $data['statistics']['alipay_trade_num'] += 1;
    		                $data['statistics']['alipay_discount_money'] += $discount_money_total;
    		                break;
    		            case ORDER_PAY_CHANNEL_WXPAY_SM: //微信扫码
    		                $data['statistics']['wechat_trade_money'] += $order_paymoney;
    		                $data['statistics']['wechat_trade_actual_money'] += $receipt_money;
    		                $data['statistics']['wechat_trade_num'] += 1;
    		                $data['statistics']['wechat_discount_money'] += $discount_money_total;
    		                break;
    		            case ORDER_PAY_CHANNEL_WXPAY_TM: //微信条码
    		                $data['statistics']['wechat_trade_money'] += $order_paymoney;
    		                $data['statistics']['wechat_trade_actual_money'] += $receipt_money;
    		                $data['statistics']['wechat_trade_num'] += 1;
    		                $data['statistics']['wechat_discount_money'] += $discount_money_total;
    		                break;
    		            case ORDER_PAY_CHANNEL_CASH: //现金支付
    		                $data['statistics']['cash_trade_money'] += $order_paymoney;
    		                $data['statistics']['cash_trade_actual_money'] += $receipt_money;
    		                $data['statistics']['cash_trade_num'] += 1;
    		                $data['statistics']['cash_discount_money'] += $discount_money_total;
    		                break;
    		            case ORDER_PAY_CHANNEL_UNIONPAY: //银联支付
    		                $data['statistics']['unionpay_trade_money'] += $order_paymoney;
    		                $data['statistics']['unionpay_trade_actual_money'] += $receipt_money;
    		                $data['statistics']['unionpay_trade_num'] += 1;
    		                $data['statistics']['unionpay_discount_money'] += $discount_money_total;
    		                break;
    		            case ORDER_PAY_CHANNEL_STORED: //储值支付
    		                $data['statistics']['stored_trade_money'] += $order_paymoney;
    		                $data['statistics']['stored_trade_actual_money'] += $receipt_money;
    		                $data['statistics']['stored_trade_num'] += 1;
    		                $data['statistics']['stored_discount_money'] += $discount_money_total;
    		                break;
		                case ORDER_PAY_CHANNEL_NO_MONEY: //无需支付
		                    $data['statistics']['nopay_trade_money'] += $order_paymoney;
		                    $data['statistics']['nopay_trade_actual_money'] += $receipt_money;
		                    $data['statistics']['nopay_trade_num'] += 1;
		                    $data['statistics']['nopay_discount_money'] += $discount_money_total;
		                    break;
		                case ORDER_PAY_CHANNEL_GROUP: //集中支付
		                    $data['statistics']['group_trade_money'] += $order_paymoney;
		                    $data['statistics']['group_trade_actual_money'] += $receipt_money;
		                    $data['statistics']['group_trade_num'] += 1;
		                    $data['statistics']['group_discount_money'] += $discount_money_total;
		                    break;
    		            default:
    		                break;
    		        }
    		        //总交易统计:累计交易额/累计实际交易额/交易笔数
    		        $data['statistics']['total_trade_money'] += $order_paymoney;
    		        $data['statistics']['total_trade_actual_money'] += $receipt_money;
    		        $data['statistics']['total_trade_num'] += 1;
    		        $data['statistics']['total_discount_money'] += $discount_money_total;
    		    }else if ($order_flag == ORDER_TYPE_CASHIER){//退款记录
    		        //计算交易金额和实收金额和交易笔数
    		        switch ($pay_channel) {
    		            case ORDER_PAY_CHANNEL_ALIPAY_SM: //支付宝扫码
    		                $data['statistics']['alipay_trade_actual_money'] -= $refund_money;
            		        $data['statistics']['alipay_refund_money'] += $refund_money;
            		        $data['statistics']['alipay_refund_num'] += 1;
    		                break;
    		            case ORDER_PAY_CHANNEL_ALIPAY_TM: //支付宝条码
    		                $data['statistics']['alipay_trade_actual_money'] -= $refund_money;
            		        $data['statistics']['alipay_refund_money'] += $refund_money;
            		        $data['statistics']['alipay_refund_num'] += 1;
    		                break;
    		            case ORDER_PAY_CHANNEL_WXPAY_SM: //微信扫码
    		                $data['statistics']['wechat_trade_actual_money'] -= $refund_money;
            		        $data['statistics']['wechat_refund_money'] += $refund_money;
            		        $data['statistics']['wechat_refund_num'] += 1;
    		                break;
    		            case ORDER_PAY_CHANNEL_WXPAY_TM: //微信条码
    		                $data['statistics']['wechat_trade_actual_money'] -= $refund_money;
            		        $data['statistics']['wechat_refund_money'] += $refund_money;
            		        $data['statistics']['wechat_refund_num'] += 1;
    		                break;
    		            case ORDER_PAY_CHANNEL_CASH: //现金支付
    		                $data['statistics']['cash_trade_actual_money'] -= $refund_money;
            		        $data['statistics']['cash_refund_money'] += $refund_money;
            		        $data['statistics']['cash_refund_num'] += 1;
    		                break;
    		            case ORDER_PAY_CHANNEL_UNIONPAY: //银联支付
    		                $data['statistics']['unionpay_trade_actual_money'] -= $refund_money;
            		        $data['statistics']['unionpay_refund_money'] += $refund_money;
            		        $data['statistics']['unionpay_refund_num'] += 1;
    		                break;
    		            case ORDER_PAY_CHANNEL_STORED: //储值支付
    		                $data['statistics']['stored_trade_actual_money'] -= $refund_money;
            		        $data['statistics']['stored_refund_money'] += $refund_money;
            		        $data['statistics']['stored_refund_num'] += 1;
    		                break;
		                case ORDER_PAY_CHANNEL_NO_MONEY: //无需支付
		                    $data['statistics']['nopay_trade_actual_money'] -= $refund_money;
		                    $data['statistics']['nopay_refund_money'] += $refund_money;
		                    $data['statistics']['nopay_refund_num'] += 1;
		                    break;
		                case ORDER_PAY_CHANNEL_GROUP: //集中支付
		                    $data['statistics']['group_trade_actual_money'] -= $refund_money;
		                    $data['statistics']['group_refund_money'] += $refund_money;
		                    $data['statistics']['group_refund_num'] += 1;
		                    break;
    		            default:
    		                break;
    		        }
    		        //总交易统计:累计交易额/累计实际交易额/交易笔数
    		        $data['statistics']['total_trade_actual_money'] -= $refund_money;
    		        $data['statistics']['total_refund_money'] += $refund_money;
    		        $data['statistics']['total_refund_num'] += 1;
    		    }
    		}
    		$result['data'] = $data;
	        $result['status'] = ERROR_NONE;
	    }catch (Exception $e) {
    		$result['status'] = isset($result['status'])?$result['status']:ERROR_EXCEPTION;
    		$result['errMsg'] = $e -> getMessage();
    	}
    	return json_encode($result);
	}
	
	/**
	 * 获取关联门店id, 返回id并集
	 * @param array $storeId_arr 门店id数组
	 */
	private function getRelationStoresId($storeId_arr){
	    $result = array();
	    if (!empty($storeId_arr)){
	        //获取关联门店id
	        $criteria = new CDbCriteria();
	        $criteria->select = "id,relation_store_id";
	        $criteria->addInCondition('id',$storeId_arr);
	        $criteria->addCondition('flag = :flag');
	        $criteria->params[':flag'] = FLAG_NO;
	        $model = Store::model()->findAll($criteria);
	        $relation_id = array();
	        if (!empty($model)){
	            foreach ($model as $k=>$v){
	                if (!empty($v['relation_store_id'])){
	                    $relation_id[] = $v['relation_store_id'];
	                }
	            }
	        }
	        $result = array_merge($storeId_arr,$relation_id);
	    }
	    return $result;
	}
	
	/**
	 * 获取交易详情
	 * @param 必选   $order_no 订单号     
	 */
	public function getTradeDetail($order_no)
	{
	    $result = array();
	    try {
	        //验证参数
	        if (empty($order_no)){
	            $result['status'] = ERROR_PARAMETER_MISS;
	            throw new Exception('参数order_no必填');
	        }
	        //查询订单详情
	        $select1 = 'o.id AS order_id,
	            m.wq_m_name AS merchant_name, 
	            o.store_id,
	            s.name AS store_name,
	            s.branch_name,
	            o.operator_id,
	            op.name AS operator_name,
	            op.number AS operator_number,
	            o.user_id,
	            u.name AS user_name,
	            u.account AS user_account,
	            o.create_time,
	            o.pay_time,
	            o.order_no,
	            o.trade_no,
	            o.pay_channel,
	            o.stored_paymoney,
	            o.online_paymoney,
	            o.unionpay_paymoney,
	            o.cash_paymoney,
	            o.alipay_account,
	            o.order_paymoney,
	            o.coupons_money,
	            o.discount_money,
	            o.merchant_discount_money,
	            o.undiscount_paymoney,
	            o.alipay_discount_money,
	            o.wxpay_discount_money,
	            o.order_status,
	            o.pay_status,
	            po.points';
	        $from1 = 'wq_order AS o';
	        $join1 = ' LEFT JOIN wq_merchant AS m ON m.id = o.merchant_id';
	        $join1 .= ' LEFT JOIN wq_store AS s ON s.id = o.store_id';
	        $join1 .= ' LEFT JOIN wq_operator AS op ON op.id = o.operator_id';
	        $join1 .= ' LEFT JOIN wq_user AS u ON u.id = o.user_id';
	        $join1 .= ' LEFT JOIN wq_user_pointsdetail AS po ON o.id = po.order_id';
	        $where1 = 'o.flag = '.FLAG_NO;
	        $where1 .= ' AND o.order_no = '.$order_no;
	        $group1 = 'o.order_no';
	        $sql1 = 'SELECT '.$select1.' FROM '.$from1.$join1.' WHERE '.$where1.' GROUP BY '.$group1;
	        $list1 = Yii::app()->db->createCommand($sql1)->queryRow();
	        if (!empty($list1)){
	            $data['detail'] = $list1;
	            $data['detail']['refund_money'] = 0;
	        }
	        
	        //查询退款列表
	        $select2 = 'r.refund_time, r.operator_id, r.operator_admin_id, r.refund_money,
	            op.name AS operator_name, op.number AS operator_number,
	            op2.name AS operator_admin_name, op2.number AS operator_admin_number';
	        $from2 = 'wq_refund_record AS r';
	        $join2 = ' LEFT JOIN wq_operator AS op ON op.id = r.operator_id';
	        $join2 .= ' LEFT JOIN wq_operator AS op2 ON op2.id = r.operator_admin_id';
	        $join2 .= ' LEFT JOIN wq_order AS o ON o.id = r.order_id';
	        $where2 = 'r.flag = '.FLAG_NO;
	        $where2 .= ' AND r.type = '.REFUND_TYPE_REFUND;
	        $where2 .= ' AND o.order_no = '.$order_no;
	        $sql2 = 'SELECT '.$select2.' FROM '.$from2.$join2.' WHERE '.$where2;
	        $list2 = Yii::app()->db->createCommand($sql2)->queryAll();
	        if (!empty($data['detail']) && !empty($list2)){
	            foreach ($list2 as $v){
	                $data['detail']['refund_money'] += $v['refund_money'];
	            }  
	        }

	        //分页
            $count = count($list2);
            $pages = new CPagination($count);
            $pages -> pageSize = Yii::app() -> params['perPage'];
            $this -> page = $pages;
            $page = 1;
            if (isset($_GET['page']) && !empty($_GET['page'])){
                $page = $_GET['page'];
            }
            $offset = $page*20-20;
            $data['list'] = array_slice($list2, $offset, 20);

    	    $result['data'] = $data;
    	    $result['status'] = ERROR_NONE;
	    }catch (Exception $e) {
	        $result['status'] = isset($result['status'])?$result['status']:ERROR_EXCEPTION;
	        $result['errMsg'] = $e -> getMessage();
	    }
	    return json_encode($result);
	}
	
	/**
	 * 获取日汇总
	 * @param 必选   $merchant_id    商户id
	 * @param 可选   $start_time     开始时间                                                  默认30日
	 * @param 可选   $end_time       结束时间                                                  默认30日
	 * @param 可选   $store_id       门店id（数组）                                      默认全部门店
	 * @param 可选   $pay_channel    支付渠道                                                    默认全渠道
	 * @param 可选   $page_no        是否分页,用于导出excel     默认分页
	 */
	public function getSStatisticsDay($merchant_id,$start_time,$end_time,$store_id,
	    $pay_channel,$page_no=NULL)
	{
	    $result = array();
	    try {
	        //验证参数
	        if (empty($merchant_id)){
	            $result['status'] = ERROR_PARAMETER_MISS;
	            throw new Exception('参数merchant_id必填');
	        }
	        //日期搜索 (默认30日)
	        if (empty($start_time)){
	            $start_time = date('Y-m-d 00:00:00',strtotime('-30 day'));
	            $end_time = date('Y-m-d 23:59:59',strtotime('-1 day'));
	        }else{
	            $start_time = date('Y-m-d 00:00:00',strtotime($start_time));
	            $end_time = date('Y-m-d 23:59:59',strtotime($end_time));
	        }
	        //初始化数据
	        $data = array();
	        $start_timeStamp = strtotime($start_time);
	        $end_timeStamp = strtotime($end_time);
	        $today_timeStamp = strtotime(date('Y-m-d 00:00:00'));
	        while ($start_timeStamp < $end_timeStamp && $start_timeStamp < $today_timeStamp)
	        {
	            $date = date('Y-m-d',$start_timeStamp);
	            $data[$date] = array(
	                'total_trade_money' =>0,
	                'total_trade_actual_money' =>0,
	                'total_trade_num' =>0,
	                'total_discount_money' =>0,
	                'total_refund_money' =>0,
	                'total_refund_num' =>0,
	            
	                'alipay_trade_money' =>0,
	                'alipay_trade_actual_money' =>0,
	                'alipay_trade_num' =>0,
	                'alipay_discount_money' =>0,
	                'alipay_refund_money' =>0,
	                'alipay_refund_num' =>0,
	            
	                'wechat_trade_money' =>0,
	                'wechat_trade_actual_money' =>0,
	                'wechat_trade_num' =>0,
	                'wechat_discount_money' =>0,
	                'wechat_refund_money' =>0,
	                'wechat_refund_num' =>0,
	            
	                'unionpay_trade_money' =>0,
	                'unionpay_trade_actual_money' =>0,
	                'unionpay_trade_num' =>0,
	                'unionpay_discount_money' =>0,
	                'unionpay_refund_money' =>0,
	                'unionpay_refund_num' =>0,
	            
	                'stored_trade_money' =>0,
	                'stored_trade_actual_money' =>0,
	                'stored_trade_num' =>0,
	                'stored_discount_money' =>0,
	                'stored_refund_money' =>0,
	                'stored_refund_num' =>0,
	            
	                'cash_trade_money' =>0,
	                'cash_trade_actual_money' =>0,
	                'cash_trade_num' =>0,
	                'cash_discount_money' =>0,
	                'cash_refund_money' =>0,
	                'cash_refund_num' =>0,
	                
	                'nopay_trade_money' =>0,
	                'nopay_trade_actual_money' =>0,
	                'nopay_trade_num' =>0,
	                'nopay_discount_money' =>0,
	                'nopay_refund_money' =>0,
	                'nopay_refund_num' =>0,
	                
	                'group_trade_money' =>0,
	                'group_trade_actual_money' =>0,
	                'group_trade_num' =>0,
	                'group_discount_money' =>0,
	                'group_refund_money' =>0,
	                'group_refund_num' =>0,
	            );
	            $start_timeStamp = strtotime('+1 day',$start_timeStamp);
	        }
	        $data = array_reverse($data);
	        //分页 (导出表时不分页)
	        if (empty($page_no)){
	            $count = count($data);
	            $pages = new CPagination($count);
	            $pages -> pageSize = 30;
	            $this -> page = $pages;
	            $page = 1;
	            if (isset($_GET['page']) && !empty($_GET['page'])){
	                $page = $_GET['page'];
	            }
	            $offset = $page*30-30;
	            $data = array_slice($data, $offset, 30);
	        }
	        //查询1条件
	        $select1 = 'o.pay_time,
            			o.pay_channel,
            			o.order_paymoney,
            			o.coupons_money,
            			o.discount_money,
            			o.merchant_discount_money,
            			o.flag AS refund_money,
            			o.flag AS order_flag';
	        $from1 = 'wq_order AS o';
	        $where1 = 'o.pay_status = '.ORDER_STATUS_PAID;//已支付订单
	        $where1 .= ' AND o.order_type = '.ORDER_TYPE_CASHIER;//收银订单
	        $where1 .= ' AND o.order_status != '.ORDER_STATUS_REVOKE;//已撤销
	        $where1 .= ' AND o.flag = '.FLAG_NO;
	        //查询2条件
	        $select2 = 'r.refund_time,
				o.pay_channel,
				o.order_paymoney,
				o.coupons_money,
				o.discount_money,
				o.merchant_discount_money,
				r.refund_money,
				o.pay_status AS order_flag';
	        $from2 = 'wq_refund_record AS r';
	        $join2 = ' INNER JOIN wq_order AS o ON r.order_id = o.id';
	        $where2 = 'r.status != '.REFUND_STATUS_FAIL;//退款失败
	        $where2 .= ' AND r.type = '.REFUND_TYPE_REFUND;//退款
	        $where2 .= ' AND r.flag = '.FLAG_NO;
	        //查询3条件
	        $orderBy3 = 't.pay_time ASC';
	         
	        //时间搜索
            $where1 .= " AND o.pay_time >= '".$start_time."'";
            $where2 .= " AND r.refund_time >= '".$start_time."'";
            $where1 .= " AND o.pay_time <= '".$end_time."'";
            $where2 .= " AND r.refund_time <= '".$end_time."'";

	        //门店搜索
            $storeId_arr = array();
	        if (!empty($store_id)){
	            $storeId_arr = $store_id;
	        }else{
	            if(Yii::app ()->session ['role'] == WQ_ROLE_MANAGER){
	                //管理员只能操作分配的门店
	                $storeId = Yii::app ()->session ['store_id'];
	                if (!empty($storeId)){
	                    $storeId = substr ( $storeId, 1, strlen ( $storeId ) - 2 );
	                    $storeId_arr = explode ( ',', $storeId );
	                }
	            }else{
	                //获取商户下门店
	                $storeId_arr = $this->getMerchantLowerStoreId($merchant_id);
	            }
	        }
	        //获取关联门店id,得到id并集
	        $storeId_arr = $this->getRelationStoresId($storeId_arr);
	        $store_str = implode(',', $storeId_arr);
	        $where1 .= " AND o.store_id IN (".$store_str.")";
	        $where2 .= " AND o.store_id IN (".$store_str.")";
	        //支付渠道搜索
	        if (!empty($pay_channel)){
	            $pay_channel_str = implode(',', $pay_channel);
	            $where1 .= " AND o.pay_channel IN (".$pay_channel_str.")";
	            $where2 .= " AND r.refund_channel IN (".$pay_channel_str.")";
	        }
	        $sql1 = 'SELECT '.$select1.' FROM '.$from1.' WHERE '.$where1;
	        $sql2 = 'SELECT '.$select2.' FROM '.$from2.$join2.' WHERE '.$where2;
	        $sql3 = 'SELECT * FROM ( '.$sql1.' UNION ALL '.$sql2.') AS t ORDER BY '.$orderBy3;
	        $list = Yii::app()->db->createCommand($sql3)->queryAll();
	        
	        foreach ($list as $v){
	            $pay_time = date('Y-m-d',strtotime($v['pay_time']));
	            if (!isset($data[$pay_time])){
	                continue;
	            }
	            $pay_channel = $v['pay_channel']; //支付方式
	            $order_paymoney = $v['order_paymoney']; //订单总金额
	            $coupons_money = $v['coupons_money']; //优惠券优惠金额
	            $discount_money = $v['discount_money']; //会员优惠
	            $m_discount_money = $v['merchant_discount_money']; //商家优惠
	            $refund_money = $v['refund_money']; //退款金额
	            $order_flag = $v['order_flag']; //交易类型: 收/退款
	    
	           if ($pay_channel != ORDER_PAY_CHANNEL_ALIPAY_SM &&
	                $pay_channel != ORDER_PAY_CHANNEL_ALIPAY_TM &&
	                $pay_channel != ORDER_PAY_CHANNEL_WXPAY_SM &&
	                $pay_channel != ORDER_PAY_CHANNEL_WXPAY_TM &&
	                $pay_channel != ORDER_PAY_CHANNEL_STORED &&
	                $pay_channel != ORDER_PAY_CHANNEL_CASH &&
	                $pay_channel != ORDER_PAY_CHANNEL_UNIONPAY &&
    		        $pay_channel != ORDER_PAY_CHANNEL_NO_MONEY &&
    		        $pay_channel != ORDER_PAY_CHANNEL_GROUP ) {
	                    //非统计的支付数据
	                    continue;
	                }
	                if ($order_flag == 1){//收款记录
	                    //优惠金额
	                    $discount_money_total = $coupons_money + $discount_money + $m_discount_money;
	                    //实收金额
	                    $receipt_money = $order_paymoney - $discount_money_total;
	    
	                    //计算交易金额和实收金额和交易笔数
	                    switch ($pay_channel) {
	                        case ORDER_PAY_CHANNEL_ALIPAY_SM: //支付宝扫码
	                            $data[$pay_time]['alipay_trade_money'] += $order_paymoney;
	                            $data[$pay_time]['alipay_trade_actual_money'] += $receipt_money;
	                            $data[$pay_time]['alipay_trade_num'] += 1;
	                            $data[$pay_time]['alipay_discount_money'] += $discount_money_total;
	                            break;
	                        case ORDER_PAY_CHANNEL_ALIPAY_TM: //支付宝条码
	                            $data[$pay_time]['alipay_trade_money'] += $order_paymoney;
	                            $data[$pay_time]['alipay_trade_actual_money'] += $receipt_money;
	                            $data[$pay_time]['alipay_trade_num'] += 1;
	                            $data[$pay_time]['alipay_discount_money'] += $discount_money_total;
	                            break;
	                        case ORDER_PAY_CHANNEL_WXPAY_SM: //微信扫码
	                            $data[$pay_time]['wechat_trade_money'] += $order_paymoney;
	                            $data[$pay_time]['wechat_trade_actual_money'] += $receipt_money;
	                            $data[$pay_time]['wechat_trade_num'] += 1;
	                            $data[$pay_time]['wechat_discount_money'] += $discount_money_total;
	                            break;
	                        case ORDER_PAY_CHANNEL_WXPAY_TM: //微信条码
	                            $data[$pay_time]['wechat_trade_money'] += $order_paymoney;
	                            $data[$pay_time]['wechat_trade_actual_money'] += $receipt_money;
	                            $data[$pay_time]['wechat_trade_num'] += 1;
	                            $data[$pay_time]['wechat_discount_money'] += $discount_money_total;
	                            break;
	                        case ORDER_PAY_CHANNEL_CASH: //现金支付
	                            $data[$pay_time]['cash_trade_money'] += $order_paymoney;
	                            $data[$pay_time]['cash_trade_actual_money'] += $receipt_money;
	                            $data[$pay_time]['cash_trade_num'] += 1;
	                            $data[$pay_time]['cash_discount_money'] += $discount_money_total;
	                            break;
	                        case ORDER_PAY_CHANNEL_UNIONPAY: //银联支付
	                            $data[$pay_time]['unionpay_trade_money'] += $order_paymoney;
	                            $data[$pay_time]['unionpay_trade_actual_money'] += $receipt_money;
	                            $data[$pay_time]['unionpay_trade_num'] += 1;
	                            $data[$pay_time]['unionpay_discount_money'] += $discount_money_total;
	                            break;
	                        case ORDER_PAY_CHANNEL_STORED: //储值支付
	                            $data[$pay_time]['stored_trade_money'] += $order_paymoney;
	                            $data[$pay_time]['stored_trade_actual_money'] += $receipt_money;
	                            $data[$pay_time]['stored_trade_num'] += 1;
	                            $data[$pay_time]['stored_discount_money'] += $discount_money_total;
	                            break;
                            case ORDER_PAY_CHANNEL_NO_MONEY: //无需支付
                                $data[$pay_time]['nopay_trade_money'] += $order_paymoney;
                                $data[$pay_time]['nopay_trade_actual_money'] += $receipt_money;
                                $data[$pay_time]['nopay_trade_num'] += 1;
                                $data[$pay_time]['nopay_discount_money'] += $discount_money_total;
                                break;
                            case ORDER_PAY_CHANNEL_GROUP: //集中支付
                                $data[$pay_time]['group_trade_money'] += $order_paymoney;
                                $data[$pay_time]['group_trade_actual_money'] += $receipt_money;
                                $data[$pay_time]['group_trade_num'] += 1;
                                $data[$pay_time]['group_discount_money'] += $discount_money_total;
                                break;
	                        default:
	                            break;
	                    }
	                    //总交易统计:累计交易额/累计实际交易额/交易笔数
	                    $data[$pay_time]['total_trade_money'] += $order_paymoney;
	                    $data[$pay_time]['total_trade_actual_money'] += $receipt_money;
	                    $data[$pay_time]['total_trade_num'] += 1;
	                    $data[$pay_time]['total_discount_money'] += $discount_money_total;
	                }else if ($order_flag == 2){//退款记录
	                    //计算交易金额和实收金额和交易笔数
	                    switch ($pay_channel) {
	                        case ORDER_PAY_CHANNEL_ALIPAY_SM: //支付宝扫码
	                            $data[$pay_time]['alipay_trade_actual_money'] -= $refund_money;
	                            $data[$pay_time]['alipay_refund_money'] += $refund_money;
	                            $data[$pay_time]['alipay_refund_num'] += 1;
	                            break;
	                        case ORDER_PAY_CHANNEL_ALIPAY_TM: //支付宝条码
	                            $data[$pay_time]['alipay_trade_actual_money'] -= $refund_money;
	                            $data[$pay_time]['alipay_refund_money'] += $refund_money;
	                            $data[$pay_time]['alipay_refund_num'] += 1;
	                            break;
	                        case ORDER_PAY_CHANNEL_WXPAY_SM: //微信扫码
	                            $data[$pay_time]['wechat_trade_actual_money'] -= $refund_money;
	                            $data[$pay_time]['wechat_refund_money'] += $refund_money;
	                            $data[$pay_time]['wechat_refund_num'] += 1;
	                            break;
	                        case ORDER_PAY_CHANNEL_WXPAY_TM: //微信条码
	                            $data[$pay_time]['wechat_trade_actual_money'] -= $refund_money;
	                            $data[$pay_time]['wechat_refund_money'] += $refund_money;
	                            $data[$pay_time]['wechat_refund_num'] += 1;
	                            break;
	                        case ORDER_PAY_CHANNEL_CASH: //现金支付
	                            $data[$pay_time]['cash_trade_actual_money'] -= $refund_money;
	                            $data[$pay_time]['cash_refund_money'] += $refund_money;
	                            $data[$pay_time]['cash_refund_num'] += 1;
	                            break;
	                        case ORDER_PAY_CHANNEL_UNIONPAY: //银联支付
	                            $data[$pay_time]['unionpay_trade_actual_money'] -= $refund_money;
	                            $data[$pay_time]['unionpay_refund_money'] += $refund_money;
	                            $data[$pay_time]['unionpay_refund_num'] += 1;
	                            break;
	                        case ORDER_PAY_CHANNEL_STORED: //储值支付
	                            $data[$pay_time]['stored_trade_actual_money'] -= $refund_money;
	                            $data[$pay_time]['stored_refund_money'] += $refund_money;
	                            $data[$pay_time]['stored_refund_num'] += 1;
	                            break;
                            case ORDER_PAY_CHANNEL_NO_MONEY: //无需支付
                                $data[$pay_time]['nopay_trade_actual_money'] -= $refund_money;
                                $data[$pay_time]['nopay_refund_money'] += $refund_money;
                                $data[$pay_time]['nopay_refund_num'] += 1;
                                break;
                            case ORDER_PAY_CHANNEL_GROUP: //集中支付
                                $data[$pay_time]['group_trade_actual_money'] -= $refund_money;
                                $data[$pay_time]['group_refund_money'] += $refund_money;
                                $data[$pay_time]['group_refund_num'] += 1;
                                break;
	                        default:
	                            break;
	                    }
	                    //总交易统计:累计交易额/累计实际交易额/交易笔数
	                    $data[$pay_time]['total_trade_actual_money'] -= $refund_money;
	                    $data[$pay_time]['total_refund_money'] += $refund_money;
	                    $data[$pay_time]['total_refund_num'] += 1;
	                }
	        }

	        $result['data'] = $data;
	        $result['status'] = ERROR_NONE;
	    }catch (Exception $e) {
	        $result['status'] = isset($result['status'])?$result['status']:ERROR_EXCEPTION;
	        $result['errMsg'] = $e -> getMessage();
	    }
	    return json_encode($result);
	}

	/**
	 * 获取日汇总 导出表数据      ( 总导 )
	 * @param 必选   $merchant_id    商户id
	 * @param 可选   $start_time     开始时间                                                  默认本月
	 * @param 可选   $end_time       结束时间                                                  默认本月
	 * @param 可选   $store_id       门店id（数组）                                      默认全部门店
	 * @param 可选   $pay_channel    支付渠道                                                    默认全渠道
	 * @param 可选   $page_no        是否分页,用于导出excel     默认分页
	 */
	public function getExportDaysExcel($merchant_id,$start_time,$end_time,$store_id,
	    $pay_channel,$page_no=NULL)
	{
	    $result = array();
	    try {
	        //验证参数
	        if (empty($merchant_id)){
	            $result['status'] = ERROR_PARAMETER_MISS;
	            throw new Exception('参数merchant_id必填');
	        }
	        if (empty($store_id)){
	            $result['status'] = ERROR_PARAMETER_MISS;
	            throw new Exception('参数store_id必填');
	        }
	        //日期搜索 (默认30日)
	        if (empty($start_time)){
	            $start_time = date('Y-m-d 00:00:00',strtotime('-30 day'));
	            $end_time = date('Y-m-d 23:59:59',strtotime('-1 day'));
	        }else{
	            $start_time = date('Y-m-d 00:00:00',strtotime($start_time));
	            $end_time = date('Y-m-d 23:59:59',strtotime($end_time));
	        }
	        //获取关联门店id,得到id并集
	        $storeId_relation = $this->getRelationStoresId($store_id);
	        //初始化数据
	        $data = array();
	        $start_timeStamp = strtotime($start_time);
	        $end_timeStamp = strtotime($end_time);
	        $today_timeStamp = strtotime(date('Y-m-d 00:00:00'));
	        while ($start_timeStamp < $end_timeStamp && $start_timeStamp < $today_timeStamp){
	            $date = date('Y-m-d',$start_timeStamp);
	            foreach ($storeId_relation as $v){
	                $data[$v][$date] = array(
	                    'total_trade_money' =>0,
	                    'total_trade_actual_money' =>0,
	                    'total_trade_num' =>0,
	                    'total_discount_money' =>0,
	                    'total_refund_money' =>0,
	                    'total_refund_num' =>0,
	                
	                    'alipay_trade_money' =>0,
	                    'alipay_trade_actual_money' =>0,
	                    'alipay_trade_num' =>0,
	                    'alipay_discount_money' =>0,
	                    'alipay_refund_money' =>0,
	                    'alipay_refund_num' =>0,
	                
	                    'wechat_trade_money' =>0,
	                    'wechat_trade_actual_money' =>0,
	                    'wechat_trade_num' =>0,
	                    'wechat_discount_money' =>0,
	                    'wechat_refund_money' =>0,
	                    'wechat_refund_num' =>0,
	                
	                    'unionpay_trade_money' =>0,
	                    'unionpay_trade_actual_money' =>0,
	                    'unionpay_trade_num' =>0,
	                    'unionpay_discount_money' =>0,
	                    'unionpay_refund_money' =>0,
	                    'unionpay_refund_num' =>0,
	                
	                    'stored_trade_money' =>0,
	                    'stored_trade_actual_money' =>0,
	                    'stored_trade_num' =>0,
	                    'stored_discount_money' =>0,
	                    'stored_refund_money' =>0,
	                    'stored_refund_num' =>0,
	                
	                    'cash_trade_money' =>0,
	                    'cash_trade_actual_money' =>0,
	                    'cash_trade_num' =>0,
	                    'cash_discount_money' =>0,
	                    'cash_refund_money' =>0,
	                    'cash_refund_num' =>0,
	                
	                    'nopay_trade_money' =>0,
	                    'nopay_trade_actual_money' =>0,
	                    'nopay_trade_num' =>0,
	                    'nopay_discount_money' =>0,
	                    'nopay_refund_money' =>0,
	                    'nopay_refund_num' =>0,
	                
	                    'group_trade_money' =>0,
	                    'group_trade_actual_money' =>0,
	                    'group_trade_num' =>0,
	                    'group_discount_money' =>0,
	                    'group_refund_money' =>0,
	                    'group_refund_num' =>0,
	                );
	            }
	            $start_timeStamp = strtotime('+1 day',$start_timeStamp);
	        }
	        
	        //查询1条件
	        $select1 = 'o.pay_time,
            			o.pay_channel,
            			o.order_paymoney,
            			o.coupons_money,
            			o.discount_money,
            			o.merchant_discount_money,
            			o.flag AS refund_money,
            			o.flag AS order_flag,
	                    o.store_id';
	        $from1 = 'wq_order AS o';
	        $where1 = 'o.pay_status = '.ORDER_STATUS_PAID;//已支付订单
	        $where1 .= ' AND o.order_type = '.ORDER_TYPE_CASHIER;//收银订单
	        $where1 .= ' AND o.order_status != '.ORDER_STATUS_REVOKE;//已撤销
	        $where1 .= ' AND o.flag = '.FLAG_NO;
	        //查询2条件
	        $select2 = 'r.refund_time,
				o.pay_channel,
				o.order_paymoney,
				o.coupons_money,
				o.discount_money,
				o.merchant_discount_money,
				r.refund_money,
				o.pay_status AS order_flag,
	            o.store_id';
	        $from2 = 'wq_refund_record AS r';
	        $join2 = ' INNER JOIN wq_order AS o ON r.order_id = o.id';
	        $where2 = 'r.status != '.REFUND_STATUS_FAIL;//退款失败
	        $where2 .= ' AND r.type = '.REFUND_TYPE_REFUND;//退款
	        $where2 .= ' AND r.flag = '.FLAG_NO;
	        //查询3条件
	        $orderBy3 = 't.pay_time ASC';
	
	        //时间搜索
	        $where1 .= " AND o.pay_time >= '".$start_time."'";
	        $where2 .= " AND r.refund_time >= '".$start_time."'";
	        $where1 .= " AND o.pay_time <= '".$end_time."'";
	        $where2 .= " AND r.refund_time <= '".$end_time."'";
	
	        //门店搜索
	        $store_str = implode(',', $storeId_relation);
	        $where1 .= " AND o.store_id IN (".$store_str.")";
	        $where2 .= " AND o.store_id IN (".$store_str.")";
	         
	        //支付渠道搜索
	        if (!empty($pay_channel)){
	            $pay_channel_str = implode(',', $pay_channel);
	            $where1 .= " AND o.pay_channel IN (".$pay_channel_str.")";
	            $where2 .= " AND r.refund_channel IN (".$pay_channel_str.")";
	        }
	        $sql1 = 'SELECT '.$select1.' FROM '.$from1.' WHERE '.$where1;
	        $sql2 = 'SELECT '.$select2.' FROM '.$from2.$join2.' WHERE '.$where2;
	        $sql3 = 'SELECT * FROM ( '.$sql1.' UNION ALL '.$sql2.') AS t ORDER BY '.$orderBy3;
	        $list = Yii::app()->db->createCommand($sql3)->queryAll();

	        foreach ($list as $v){
	            $pay_time = date('Y-m-d',strtotime($v['pay_time']));
	            $v_store_id = $v['store_id'];
	            if (!isset($data[$v_store_id][$pay_time])){
	                continue;
	            }
	            $pay_channel = $v['pay_channel']; //支付方式
	            $order_paymoney = $v['order_paymoney']; //订单总金额
	            $coupons_money = $v['coupons_money']; //优惠券优惠金额
	            $discount_money = $v['discount_money']; //会员优惠
	            $m_discount_money = $v['merchant_discount_money']; //商家优惠
	            $refund_money = $v['refund_money']; //退款金额
	            $order_flag = $v['order_flag']; //交易类型: 收/退款
	             
	        if ($pay_channel != ORDER_PAY_CHANNEL_ALIPAY_SM &&
	                $pay_channel != ORDER_PAY_CHANNEL_ALIPAY_TM &&
	                $pay_channel != ORDER_PAY_CHANNEL_WXPAY_SM &&
	                $pay_channel != ORDER_PAY_CHANNEL_WXPAY_TM &&
	                $pay_channel != ORDER_PAY_CHANNEL_STORED &&
	                $pay_channel != ORDER_PAY_CHANNEL_CASH &&
	                $pay_channel != ORDER_PAY_CHANNEL_UNIONPAY &&
    		        $pay_channel != ORDER_PAY_CHANNEL_NO_MONEY &&
    		        $pay_channel != ORDER_PAY_CHANNEL_GROUP ) {
	                    //非统计的支付数据
	                    continue;
	                }
	                if ($order_flag == 1){//收款记录
	                    //优惠金额
	                    $discount_money_total = $coupons_money + $discount_money + $m_discount_money;
	                    //实收金额
	                    $receipt_money = $order_paymoney - $discount_money_total;
	                     
	                    //计算交易金额和实收金额和交易笔数
	                    switch ($pay_channel) {
	                        case ORDER_PAY_CHANNEL_ALIPAY_SM: //支付宝扫码
	                            $data[$v_store_id][$pay_time]['alipay_trade_money'] += $order_paymoney;
	                            $data[$v_store_id][$pay_time]['alipay_trade_actual_money'] += $receipt_money;
	                            $data[$v_store_id][$pay_time]['alipay_trade_num'] += 1;
	                            $data[$v_store_id][$pay_time]['alipay_discount_money'] += $discount_money_total;
	                            break;
	                        case ORDER_PAY_CHANNEL_ALIPAY_TM: //支付宝条码
	                            $data[$v_store_id][$pay_time]['alipay_trade_money'] += $order_paymoney;
	                            $data[$v_store_id][$pay_time]['alipay_trade_actual_money'] += $receipt_money;
	                            $data[$v_store_id][$pay_time]['alipay_trade_num'] += 1;
	                            $data[$v_store_id][$pay_time]['alipay_discount_money'] += $discount_money_total;
	                            break;
	                        case ORDER_PAY_CHANNEL_WXPAY_SM: //微信扫码
	                            $data[$v_store_id][$pay_time]['wechat_trade_money'] += $order_paymoney;
	                            $data[$v_store_id][$pay_time]['wechat_trade_actual_money'] += $receipt_money;
	                            $data[$v_store_id][$pay_time]['wechat_trade_num'] += 1;
	                            $data[$v_store_id][$pay_time]['wechat_discount_money'] += $discount_money_total;
	                            break;
	                        case ORDER_PAY_CHANNEL_WXPAY_TM: //微信条码
	                            $data[$v_store_id][$pay_time]['wechat_trade_money'] += $order_paymoney;
	                            $data[$v_store_id][$pay_time]['wechat_trade_actual_money'] += $receipt_money;
	                            $data[$v_store_id][$pay_time]['wechat_trade_num'] += 1;
	                            $data[$v_store_id][$pay_time]['wechat_discount_money'] += $discount_money_total;
	                            break;
	                        case ORDER_PAY_CHANNEL_CASH: //现金支付
	                            $data[$v_store_id][$pay_time]['cash_trade_money'] += $order_paymoney;
	                            $data[$v_store_id][$pay_time]['cash_trade_actual_money'] += $receipt_money;
	                            $data[$v_store_id][$pay_time]['cash_trade_num'] += 1;
	                            $data[$v_store_id][$pay_time]['cash_discount_money'] += $discount_money_total;
	                            break;
	                        case ORDER_PAY_CHANNEL_UNIONPAY: //银联支付
	                            $data[$v_store_id][$pay_time]['unionpay_trade_money'] += $order_paymoney;
	                            $data[$v_store_id][$pay_time]['unionpay_trade_actual_money'] += $receipt_money;
	                            $data[$v_store_id][$pay_time]['unionpay_trade_num'] += 1;
	                            $data[$v_store_id][$pay_time]['unionpay_discount_money'] += $discount_money_total;
	                            break;
	                        case ORDER_PAY_CHANNEL_STORED: //储值支付
	                            $data[$v_store_id][$pay_time]['stored_trade_money'] += $order_paymoney;
	                            $data[$v_store_id][$pay_time]['stored_trade_actual_money'] += $receipt_money;
	                            $data[$v_store_id][$pay_time]['stored_trade_num'] += 1;
	                            $data[$v_store_id][$pay_time]['stored_discount_money'] += $discount_money_total;
	                            break;
                            case ORDER_PAY_CHANNEL_NO_MONEY: //无需支付
                                $data[$v_store_id][$pay_time]['nopay_trade_money'] += $order_paymoney;
                                $data[$v_store_id][$pay_time]['nopay_trade_actual_money'] += $receipt_money;
                                $data[$v_store_id][$pay_time]['nopay_trade_num'] += 1;
                                $data[$v_store_id][$pay_time]['nopay_discount_money'] += $discount_money_total;
                                break;
                            case ORDER_PAY_CHANNEL_GROUP: //集中支付
                                $data[$v_store_id][$pay_time]['group_trade_money'] += $order_paymoney;
                                $data[$v_store_id][$pay_time]['group_trade_actual_money'] += $receipt_money;
                                $data[$v_store_id][$pay_time]['group_trade_num'] += 1;
                                $data[$v_store_id][$pay_time]['group_discount_money'] += $discount_money_total;
                                break;
	                        default:
	                            break;
	                    }
	                    //总交易统计:累计交易额/累计实际交易额/交易笔数
	                    $data[$v_store_id][$pay_time]['total_trade_money'] += $order_paymoney;
	                    $data[$v_store_id][$pay_time]['total_trade_actual_money'] += $receipt_money;
	                    $data[$v_store_id][$pay_time]['total_trade_num'] += 1;
	                    $data[$v_store_id][$pay_time]['total_discount_money'] += $discount_money_total;
	                }else if ($order_flag == 2){//退款记录
	                    //计算交易金额和实收金额和交易笔数
	                    switch ($pay_channel) {
	                        case ORDER_PAY_CHANNEL_ALIPAY_SM: //支付宝扫码
	                            $data[$v_store_id][$pay_time]['alipay_trade_actual_money'] -= $refund_money;
	                            $data[$v_store_id][$pay_time]['alipay_refund_money'] += $refund_money;
	                            $data[$v_store_id][$pay_time]['alipay_refund_num'] += 1;
	                            break;
	                        case ORDER_PAY_CHANNEL_ALIPAY_TM: //支付宝条码
	                            $data[$v_store_id][$pay_time]['alipay_trade_actual_money'] -= $refund_money;
	                            $data[$v_store_id][$pay_time]['alipay_refund_money'] += $refund_money;
	                            $data[$v_store_id][$pay_time]['alipay_refund_num'] += 1;
	                            break;
	                        case ORDER_PAY_CHANNEL_WXPAY_SM: //微信扫码
	                            $data[$v_store_id][$pay_time]['wechat_trade_actual_money'] -= $refund_money;
	                            $data[$v_store_id][$pay_time]['wechat_refund_money'] += $refund_money;
	                            $data[$v_store_id][$pay_time]['wechat_refund_num'] += 1;
	                            break;
	                        case ORDER_PAY_CHANNEL_WXPAY_TM: //微信条码
	                            $data[$v_store_id][$pay_time]['wechat_trade_actual_money'] -= $refund_money;
	                            $data[$v_store_id][$pay_time]['wechat_refund_money'] += $refund_money;
	                            $data[$v_store_id][$pay_time]['wechat_refund_num'] += 1;
	                            break;
	                        case ORDER_PAY_CHANNEL_CASH: //现金支付
	                            $data[$v_store_id][$pay_time]['cash_trade_actual_money'] -= $refund_money;
	                            $data[$v_store_id][$pay_time]['cash_refund_money'] += $refund_money;
	                            $data[$v_store_id][$pay_time]['cash_refund_num'] += 1;
	                            break;
	                        case ORDER_PAY_CHANNEL_UNIONPAY: //银联支付
	                            $data[$v_store_id][$pay_time]['unionpay_trade_actual_money'] -= $refund_money;
	                            $data[$v_store_id][$pay_time]['unionpay_refund_money'] += $refund_money;
	                            $data[$v_store_id][$pay_time]['unionpay_refund_num'] += 1;
	                            break;
	                        case ORDER_PAY_CHANNEL_STORED: //储值支付
	                            $data[$v_store_id][$pay_time]['stored_trade_actual_money'] -= $refund_money;
	                            $data[$v_store_id][$pay_time]['stored_refund_money'] += $refund_money;
	                            $data[$v_store_id][$pay_time]['stored_refund_num'] += 1;
	                            break;
                            case ORDER_PAY_CHANNEL_NO_MONEY: //无需支付
                                $data[$v_store_id][$pay_time]['nopay_trade_actual_money'] -= $refund_money;
                                $data[$v_store_id][$pay_time]['nopay_refund_money'] += $refund_money;
                                $data[$v_store_id][$pay_time]['nopay_refund_num'] += 1;
                                break;
                            case ORDER_PAY_CHANNEL_GROUP: //集中支付
                                $data[$v_store_id][$pay_time]['group_trade_actual_money'] -= $refund_money;
                                $data[$v_store_id][$pay_time]['group_refund_money'] += $refund_money;
                                $data[$v_store_id][$pay_time]['group_refund_num'] += 1;
                                break;
	                        default:
	                            break;
	                    }
	                    //总交易统计:累计交易额/累计实际交易额/交易笔数
	                    $data[$v_store_id][$pay_time]['total_trade_actual_money'] -= $refund_money;
	                    $data[$v_store_id][$pay_time]['total_refund_money'] += $refund_money;
	                    $data[$v_store_id][$pay_time]['total_refund_num'] += 1;
	                }
	        }
	        //合并关联门店数据
	        $relative_storeId = $this->getRelationStoreIdRelative($store_id);
	        foreach ($relative_storeId as $k => $v){
	            if (!empty($data[$k]) && !empty($data[$v])){
	                foreach ($data[$k] as $k2 => $v2){
	                    foreach ($v2 as $k3 => $v3){
	                        $data[$k][$k2][$k3] += $data[$v][$k2][$k3];
	                    }
	                }
	            }
	            unset($data[$v]);
	        }
	        $result['data'] = $data;
	        $result['status'] = ERROR_NONE;
	    }catch (Exception $e) {
	        $result['status'] = isset($result['status'])?$result['status']:ERROR_EXCEPTION;
	        $result['errMsg'] = $e -> getMessage();
	    }
	    return json_encode($result);
	}
	
	/**
	 * 获取月汇总 
	 * @param  必选       $merchant_id    商户id
	 * @param  可选   $start_time      开始时间                                                  默认12月
	 * @param  可选   $end_time        结束时间                                                  默认12月
	 * @param  可选     $store_id       门店id（数组）                                               默认全部门店
	 * @param  可选     $pay_channel    支付渠道                                                              默认全渠道
	 * @param  可选     $page_no        可选参数,是否分页,用于导出excel  默认分页
	 */
	public function getSStatisticsMonth($merchant_id,$start_time,$end_time,$store_id,$pay_channel)
	{
	    $result = array();
	    try {
	        //验证参数
	        if (empty($merchant_id)){
	            $result['status'] = ERROR_PARAMETER_MISS;
	            throw new Exception('参数merchant_id必填');
	        }
	        
	        //日期搜索 (默认12月)
	        if (empty($start_time)){
	            $start_time = date('Y-m-01 00:00:00',strtotime('-12 month'));
	            $end_time = date('Y-m-t 23:59:59',strtotime('-1 month'));
	        }else{
	           $start_time = str_replace('/', '-', $start_time);
	           $end_time = str_replace('/', '-', $end_time);
	            $start_time = date('Y-m-01 00:00:00',strtotime($start_time));
	            $end_time = date('Y-m-t 23:59:59',strtotime($end_time));
	        }
	        
	        //初始化数据
	        $data = array();
	        $start_timeStamp = strtotime($start_time);
	        $end_timeStamp = strtotime($end_time);
	        $today_timeStamp = strtotime(date('Y-m-01'));
	        while ($start_timeStamp < $end_timeStamp && $start_timeStamp < $today_timeStamp)
	        {
	            $date = date('Y-m',$start_timeStamp);
	            $data[$date] = array(
	                'total_trade_money' =>0,
	                'total_trade_actual_money' =>0,
	                'total_trade_num' =>0,
	                'total_discount_money' =>0,
	                'total_refund_money' =>0,
	                'total_refund_num' =>0,
	    
	                'alipay_trade_money' =>0,
	                'alipay_trade_actual_money' =>0,
	                'alipay_trade_num' =>0,
	                'alipay_discount_money' =>0,
	                'alipay_refund_money' =>0,
	                'alipay_refund_num' =>0,
	    
	                'wechat_trade_money' =>0,
	                'wechat_trade_actual_money' =>0,
	                'wechat_trade_num' =>0,
	                'wechat_discount_money' =>0,
	                'wechat_refund_money' =>0,
	                'wechat_refund_num' =>0,
	    
	                'unionpay_trade_money' =>0,
	                'unionpay_trade_actual_money' =>0,
	                'unionpay_trade_num' =>0,
	                'unionpay_discount_money' =>0,
	                'unionpay_refund_money' =>0,
	                'unionpay_refund_num' =>0,
	    
	                'stored_trade_money' =>0,
	                'stored_trade_actual_money' =>0,
	                'stored_trade_num' =>0,
	                'stored_discount_money' =>0,
	                'stored_refund_money' =>0,
	                'stored_refund_num' =>0,
	    
	                'cash_trade_money' =>0,
	                'cash_trade_actual_money' =>0,
	                'cash_trade_num' =>0,
	                'cash_discount_money' =>0,
	                'cash_refund_money' =>0,
	                'cash_refund_num' =>0,
	                
	                'nopay_trade_money' =>0,
	                'nopay_trade_actual_money' =>0,
	                'nopay_trade_num' =>0,
	                'nopay_discount_money' =>0,
	                'nopay_refund_money' =>0,
	                'nopay_refund_num' =>0,
	                
	                'group_trade_money' =>0,
	                'group_trade_actual_money' =>0,
	                'group_trade_num' =>0,
	                'group_discount_money' =>0,
	                'group_refund_money' =>0,
	                'group_refund_num' =>0,
	            );
	            $start_timeStamp = strtotime('+1 month',$start_timeStamp);
	             
	        }
	        $data = array_reverse($data);
	        //分页
            $count = count($data);
            $pages = new CPagination($count);
            $pages -> pageSize = 20;
            $this -> page = $pages;
            $page = 1;
            if (isset($_GET['page']) && !empty($_GET['page'])){
                $page = $_GET['page'];
            }
            $offset = $page*20-20;
            $data = array_slice($data, $offset, 20);
	        
	        //查询1条件
	        $select1 = 'o.pay_time,
            			o.pay_channel,
            			o.order_paymoney,
            			o.coupons_money,
            			o.discount_money,
            			o.merchant_discount_money,
            			o.flag AS refund_money,
            			o.flag AS order_flag';
	        $from1 = 'wq_order AS o';
	        $where1 = 'o.pay_status = '.ORDER_STATUS_PAID;//已支付订单
	        $where1 .= ' AND o.order_type = '.ORDER_TYPE_CASHIER;//收银订单
	        $where1 .= ' AND o.order_status != '.ORDER_STATUS_REVOKE;//已撤销
	        $where1 .= ' AND o.flag = '.FLAG_NO;
	        //查询2条件
	        $select2 = 'r.refund_time,
				o.pay_channel,
				o.order_paymoney,
				o.coupons_money,
				o.discount_money,
				o.merchant_discount_money,
				r.refund_money,
				o.pay_status AS order_flag';
	        $from2 = 'wq_refund_record AS r';
	        $join2 = ' INNER JOIN wq_order AS o ON r.order_id = o.id';
	        $where2 = 'r.status != '.REFUND_STATUS_FAIL;//退款失败
	        $where2 .= ' AND r.type = '.REFUND_TYPE_REFUND;//退款
	        $where2 .= ' AND r.flag = '.FLAG_NO;
	        //查询3条件
	        $orderBy3 = 't.pay_time ASC';
	    
	        //时间搜索
	        $where1 .= " AND o.pay_time >= '".$start_time."'";
	        $where2 .= " AND r.refund_time >= '".$start_time."'";
	        $where1 .= " AND o.pay_time <= '".$end_time."'";
	        $where2 .= " AND r.refund_time <= '".$end_time."'";
	         
	        //门店搜索
	        $storeId_arr = array();
	        if (!empty($store_id)){
	            $storeId_arr = $store_id;
	        }else{
	            if(Yii::app ()->session ['role'] == WQ_ROLE_MANAGER){
	                //管理员只能操作分配的门店
	                $storeId = Yii::app ()->session ['store_id'];
	                if (!empty($storeId)){
	                    $storeId = substr ( $storeId, 1, strlen ( $storeId ) - 2 );
	                    $storeId_arr = explode ( ',', $storeId );
	                }
	            }else{
	                //获取商户下门店
	                $storeId_arr = $this->getMerchantLowerStoreId($merchant_id);
	            }
	        }
	        //获取关联门店id,得到id并集
	        $storeId_arr = $this->getRelationStoresId($storeId_arr);
            $store_str = implode(',', $storeId_arr);
            $where1 .= " AND o.store_id IN (".$store_str.")";
            $where2 .= " AND o.store_id IN (".$store_str.")";
	        //支付渠道搜索
	        if (!empty($pay_channel)){
	            $pay_channel_str = implode(',', $pay_channel);
	            $where1 .= " AND o.pay_channel IN (".$pay_channel_str.")";
	            $where2 .= " AND r.refund_channel IN (".$pay_channel_str.")";
	        }
	        $sql1 = 'SELECT '.$select1.' FROM '.$from1.' WHERE '.$where1;
	        $sql2 = 'SELECT '.$select2.' FROM '.$from2.$join2.' WHERE '.$where2;
	        $sql3 = 'SELECT * FROM ( '.$sql1.' UNION ALL '.$sql2.') AS t ORDER BY '.$orderBy3;
	    
	        $list = Yii::app()->db->createCommand($sql3)->queryAll();
	        foreach ($list as $v){
	            $pay_time = date('Y-m',strtotime($v['pay_time']));
	            if (!isset($data[$pay_time])){
	                continue;
	            }
	            $pay_channel = $v['pay_channel']; //支付方式
	            $order_paymoney = $v['order_paymoney']; //订单总金额
	            $coupons_money = $v['coupons_money']; //优惠券优惠金额
	            $discount_money = $v['discount_money']; //会员优惠
	            $m_discount_money = $v['merchant_discount_money']; //商家优惠
	            $refund_money = $v['refund_money']; //退款金额
	            $order_flag = $v['order_flag']; //交易类型: 收/退款
	             
	            if ($pay_channel != ORDER_PAY_CHANNEL_ALIPAY_SM &&
	                $pay_channel != ORDER_PAY_CHANNEL_ALIPAY_TM &&
	                $pay_channel != ORDER_PAY_CHANNEL_WXPAY_SM &&
	                $pay_channel != ORDER_PAY_CHANNEL_WXPAY_TM &&
	                $pay_channel != ORDER_PAY_CHANNEL_STORED &&
	                $pay_channel != ORDER_PAY_CHANNEL_CASH &&
	                $pay_channel != ORDER_PAY_CHANNEL_UNIONPAY &&
    		        $pay_channel != ORDER_PAY_CHANNEL_NO_MONEY &&
    		        $pay_channel != ORDER_PAY_CHANNEL_GROUP ) {
	                    //非统计的支付数据
	                    continue;
	                }
	                if ($order_flag == 1){//收款记录
	                    //优惠金额
	                    $discount_money_total = $coupons_money + $discount_money + $m_discount_money;
	                    //实收金额
	                    $receipt_money = $order_paymoney - $discount_money_total;
	                     
	                    //计算交易金额和实收金额和交易笔数
	                    switch ($pay_channel) {
	                        case ORDER_PAY_CHANNEL_ALIPAY_SM: //支付宝扫码
	                            $data[$pay_time]['alipay_trade_money'] += $order_paymoney;
	                            $data[$pay_time]['alipay_trade_actual_money'] += $receipt_money;
	                            $data[$pay_time]['alipay_trade_num'] += 1;
	                            $data[$pay_time]['alipay_discount_money'] += $discount_money_total;
	                            break;
	                        case ORDER_PAY_CHANNEL_ALIPAY_TM: //支付宝条码
	                            $data[$pay_time]['alipay_trade_money'] += $order_paymoney;
	                            $data[$pay_time]['alipay_trade_actual_money'] += $receipt_money;
	                            $data[$pay_time]['alipay_trade_num'] += 1;
	                            $data[$pay_time]['alipay_discount_money'] += $discount_money_total;
	                            break;
	                        case ORDER_PAY_CHANNEL_WXPAY_SM: //微信扫码
	                            $data[$pay_time]['wechat_trade_money'] += $order_paymoney;
	                            $data[$pay_time]['wechat_trade_actual_money'] += $receipt_money;
	                            $data[$pay_time]['wechat_trade_num'] += 1;
	                            $data[$pay_time]['wechat_discount_money'] += $discount_money_total;
	                            break;
	                        case ORDER_PAY_CHANNEL_WXPAY_TM: //微信条码
	                            $data[$pay_time]['wechat_trade_money'] += $order_paymoney;
	                            $data[$pay_time]['wechat_trade_actual_money'] += $receipt_money;
	                            $data[$pay_time]['wechat_trade_num'] += 1;
	                            $data[$pay_time]['wechat_discount_money'] += $discount_money_total;
	                            break;
	                        case ORDER_PAY_CHANNEL_CASH: //现金支付
	                            $data[$pay_time]['cash_trade_money'] += $order_paymoney;
	                            $data[$pay_time]['cash_trade_actual_money'] += $receipt_money;
	                            $data[$pay_time]['cash_trade_num'] += 1;
	                            $data[$pay_time]['cash_discount_money'] += $discount_money_total;
	                            break;
	                        case ORDER_PAY_CHANNEL_UNIONPAY: //银联支付
	                            $data[$pay_time]['unionpay_trade_money'] += $order_paymoney;
	                            $data[$pay_time]['unionpay_trade_actual_money'] += $receipt_money;
	                            $data[$pay_time]['unionpay_trade_num'] += 1;
	                            $data[$pay_time]['unionpay_discount_money'] += $discount_money_total;
	                            break;
	                        case ORDER_PAY_CHANNEL_STORED: //储值支付
	                            $data[$pay_time]['stored_trade_money'] += $order_paymoney;
	                            $data[$pay_time]['stored_trade_actual_money'] += $receipt_money;
	                            $data[$pay_time]['stored_trade_num'] += 1;
	                            $data[$pay_time]['stored_discount_money'] += $discount_money_total;
	                            break;
                            case ORDER_PAY_CHANNEL_NO_MONEY: //无需支付
                                $data[$pay_time]['nopay_trade_money'] += $order_paymoney;
                                $data[$pay_time]['nopay_trade_actual_money'] += $receipt_money;
                                $data[$pay_time]['nopay_trade_num'] += 1;
                                $data[$pay_time]['nopay_discount_money'] += $discount_money_total;
                                break;
                            case ORDER_PAY_CHANNEL_GROUP: //集中支付
                                $data[$pay_time]['group_trade_money'] += $order_paymoney;
                                $data[$pay_time]['group_trade_actual_money'] += $receipt_money;
                                $data[$pay_time]['group_trade_num'] += 1;
                                $data[$pay_time]['group_discount_money'] += $discount_money_total;
                                break;
	                        default:
	                            break;
	                    }
	                    //总交易统计:累计交易额/累计实际交易额/交易笔数
	                    $data[$pay_time]['total_trade_money'] += $order_paymoney;
	                    $data[$pay_time]['total_trade_actual_money'] += $receipt_money;
	                    $data[$pay_time]['total_trade_num'] += 1;
	                    $data[$pay_time]['total_discount_money'] += $discount_money_total;
	                }else if ($order_flag == 2){//退款记录
	                    //计算交易金额和实收金额和交易笔数
	                    switch ($pay_channel) {
	                        case ORDER_PAY_CHANNEL_ALIPAY_SM: //支付宝扫码
	                            $data[$pay_time]['alipay_trade_actual_money'] -= $refund_money;
	                            $data[$pay_time]['alipay_refund_money'] += $refund_money;
	                            $data[$pay_time]['alipay_refund_num'] += 1;
	                            break;
	                        case ORDER_PAY_CHANNEL_ALIPAY_TM: //支付宝条码
	                            $data[$pay_time]['alipay_trade_actual_money'] -= $refund_money;
	                            $data[$pay_time]['alipay_refund_money'] += $refund_money;
	                            $data[$pay_time]['alipay_refund_num'] += 1;
	                            break;
	                        case ORDER_PAY_CHANNEL_WXPAY_SM: //微信扫码
	                            $data[$pay_time]['wechat_trade_actual_money'] -= $refund_money;
	                            $data[$pay_time]['wechat_refund_money'] += $refund_money;
	                            $data[$pay_time]['wechat_refund_num'] += 1;
	                            break;
	                        case ORDER_PAY_CHANNEL_WXPAY_TM: //微信条码
	                            $data[$pay_time]['wechat_trade_actual_money'] -= $refund_money;
	                            $data[$pay_time]['wechat_refund_money'] += $refund_money;
	                            $data[$pay_time]['wechat_refund_num'] += 1;
	                            break;
	                        case ORDER_PAY_CHANNEL_CASH: //现金支付
	                            $data[$pay_time]['cash_trade_actual_money'] -= $refund_money;
	                            $data[$pay_time]['cash_refund_money'] += $refund_money;
	                            $data[$pay_time]['cash_refund_num'] += 1;
	                            break;
	                        case ORDER_PAY_CHANNEL_UNIONPAY: //银联支付
	                            $data[$pay_time]['unionpay_trade_actual_money'] -= $refund_money;
	                            $data[$pay_time]['unionpay_refund_money'] += $refund_money;
	                            $data[$pay_time]['unionpay_refund_num'] += 1;
	                            break;
	                        case ORDER_PAY_CHANNEL_STORED: //储值支付
	                            $data[$pay_time]['stored_trade_actual_money'] -= $refund_money;
	                            $data[$pay_time]['stored_refund_money'] += $refund_money;
	                            $data[$pay_time]['stored_refund_num'] += 1;
	                            break;
                            case ORDER_PAY_CHANNEL_NO_MONEY: //无需支付
                                $data[$pay_time]['nopay_trade_actual_money'] -= $refund_money;
                                $data[$pay_time]['nopay_refund_money'] += $refund_money;
                                $data[$pay_time]['nopay_refund_num'] += 1;
                                break;
                            case ORDER_PAY_CHANNEL_GROUP: //集中支付
                                $data[$pay_time]['group_trade_actual_money'] -= $refund_money;
                                $data[$pay_time]['group_refund_money'] += $refund_money;
                                $data[$pay_time]['group_refund_num'] += 1;
                                break;
	                        default:
	                            break;
	                    }
	                    //总交易统计:累计交易额/累计实际交易额/交易笔数
	                    $data[$pay_time]['total_trade_actual_money'] -= $refund_money;
	                    $data[$pay_time]['total_refund_money'] += $refund_money;
	                    $data[$pay_time]['total_refund_num'] += 1;
	                }
	        }
	        
	        $result['data'] = $data;
	        $result['status'] = ERROR_NONE;
	    }catch (Exception $e) {
	        $result['status'] = isset($result['status'])?$result['status']:ERROR_EXCEPTION;
	        $result['errMsg'] = $e -> getMessage();
	    }
	    return json_encode($result);
	}
	
	/**
	 * 获取月汇总导出表数据 (总导) 2016/07/28
	 * @param  必选       $merchant_id    商户id
	 * @param  可选   $start_time      开始时间                                                  默认12月
	 * @param  可选   $end_time        结束时间                                                  默认12月
	 * @param  可选     $store_id       门店id（数组）                                               默认全部门店
	 * @param  可选     $pay_channel    支付渠道                                                              默认全渠道
	 * @param  可选     $page_no        可选参数,是否分页,用于导出excel  默认分页
	 */
	public function getExportMonthsExcel($merchant_id,$start_time,$end_time,$store_id,$pay_channel)
	{
	    $result = array();
	    try {
	        //验证参数
	        if (empty($merchant_id)){
	            $result['status'] = ERROR_PARAMETER_MISS;
	            throw new Exception('参数merchant_id必填');
	        }
	         
	        //日期搜索 (默认12月)
	        if (empty($start_time)){
	            $start_time = date('Y-m-01 00:00:00',strtotime('-12 month'));
	            $end_time = date('Y-m-t 23:59:59',strtotime('-1 month'));
	        }else{
	            $start_time = str_replace('/', '-', $start_time);
	            $end_time = str_replace('/', '-', $end_time);
	            $start_time = date('Y-m-01 00:00:00',strtotime($start_time));
	            $end_time = date('Y-m-t 23:59:59',strtotime($end_time));
	        }
	        //获取门店信息集合
	        $store_arr_info = $this->getMerchantLowerStore($merchant_id, $store_id);
	        //初始化数据
	        $data = array();
	        $storeId_arr = array();    //门店id集合
    	    $start_timeStamp = strtotime($start_time);
	        $end_timeStamp = strtotime($end_time);
	        $today_timeStamp = strtotime(date('Y-m-01'));
	        while ($start_timeStamp < $end_timeStamp && $start_timeStamp < $today_timeStamp)
	        {
	            $date = date('Y-m',$start_timeStamp);
	            foreach ($store_arr_info as $v){
	                $storeId_arr[] = $v['id'];
	                $data[$v['id']][$date] = array(
	                    'name' =>$v['name'],
	                    'branch_name' =>$v['branch_name'],
	                    'number' =>$v['number'],
	                     
	                    'total_trade_money' =>0,
	                    'total_trade_actual_money' =>0,
	                    'total_trade_num' =>0,
	                    'total_discount_money' =>0,
	                    'total_refund_money' =>0,
	                    'total_refund_num' =>0,
	                
	                    'alipay_trade_money' =>0,
	                    'alipay_trade_actual_money' =>0,
	                    'alipay_trade_num' =>0,
	                    'alipay_discount_money' =>0,
	                    'alipay_refund_money' =>0,
	                    'alipay_refund_num' =>0,
	                
	                    'wechat_trade_money' =>0,
	                    'wechat_trade_actual_money' =>0,
	                    'wechat_trade_num' =>0,
	                    'wechat_discount_money' =>0,
	                    'wechat_refund_money' =>0,
	                    'wechat_refund_num' =>0,
	                
	                    'unionpay_trade_money' =>0,
	                    'unionpay_trade_actual_money' =>0,
	                    'unionpay_trade_num' =>0,
	                    'unionpay_discount_money' =>0,
	                    'unionpay_refund_money' =>0,
	                    'unionpay_refund_num' =>0,
	                
	                    'stored_trade_money' =>0,
	                    'stored_trade_actual_money' =>0,
	                    'stored_trade_num' =>0,
	                    'stored_discount_money' =>0,
	                    'stored_refund_money' =>0,
	                    'stored_refund_num' =>0,
	                
	                    'cash_trade_money' =>0,
	                    'cash_trade_actual_money' =>0,
	                    'cash_trade_num' =>0,
	                    'cash_discount_money' =>0,
	                    'cash_refund_money' =>0,
	                    'cash_refund_num' =>0,
	                    
	                    'nopay_trade_money' =>0,
	                    'nopay_trade_actual_money' =>0,
	                    'nopay_trade_num' =>0,
	                    'nopay_discount_money' =>0,
	                    'nopay_refund_money' =>0,
	                    'nopay_refund_num' =>0,
	                    
	                    'group_trade_money' =>0,
	                    'group_trade_actual_money' =>0,
	                    'group_trade_num' =>0,
	                    'group_discount_money' =>0,
	                    'group_refund_money' =>0,
	                    'group_refund_num' =>0,
	                );
	            }
	            $start_timeStamp = strtotime('+1 month',$start_timeStamp);
	        }
	        //查询1条件
	        $select1 = 'o.pay_time,
    			o.pay_channel,
    			o.order_paymoney,
    			o.coupons_money,
    			o.discount_money,
    			o.merchant_discount_money,
    			o.flag AS refund_money,
    			o.flag AS order_flag,
	            o.store_id';
	        $from1 = 'wq_order AS o';
	        $where1 = 'o.pay_status = '.ORDER_STATUS_PAID;//已支付订单
	        $where1 .= ' AND o.order_type = '.ORDER_TYPE_CASHIER;//收银订单
	        $where1 .= ' AND o.order_status != '.ORDER_STATUS_REVOKE;//已撤销
	        $where1 .= ' AND o.flag = '.FLAG_NO;
	        //查询2条件
	        $select2 = 'r.refund_time,
				o.pay_channel,
				o.order_paymoney,
				o.coupons_money,
				o.discount_money,
				o.merchant_discount_money,
				r.refund_money,
				o.pay_status AS order_flag,
	            o.store_id';
	        $from2 = 'wq_refund_record AS r';
	        $join2 = ' INNER JOIN wq_order AS o ON r.order_id = o.id';
	        $where2 = 'r.status != '.REFUND_STATUS_FAIL;//退款失败
	        $where2 .= ' AND r.type = '.REFUND_TYPE_REFUND;//退款
	        $where2 .= ' AND r.flag = '.FLAG_NO;
	        //查询3条件
	        $orderBy3 = 't.pay_time ASC';
	         
	        //时间搜索
	        $where1 .= " AND o.pay_time >= '".$start_time."'";
	        $where2 .= " AND r.refund_time >= '".$start_time."'";
	        $where1 .= " AND o.pay_time <= '".$end_time."'";
	        $where2 .= " AND r.refund_time <= '".$end_time."'";
	
	        //门店搜索
	        $store_str = implode(',', $storeId_arr);
	        $where1 .= " AND o.store_id IN (".$store_str.")";
	        $where2 .= " AND o.store_id IN (".$store_str.")";
	        //支付渠道搜索
	        if (!empty($pay_channel)){
	            $pay_channel_str = implode(',', $pay_channel);
	            $where1 .= " AND o.pay_channel IN (".$pay_channel_str.")";
	            $where2 .= " AND r.refund_channel IN (".$pay_channel_str.")";
	        }
	        $sql1 = 'SELECT '.$select1.' FROM '.$from1.' WHERE '.$where1;
	        $sql2 = 'SELECT '.$select2.' FROM '.$from2.$join2.' WHERE '.$where2;
	        $sql3 = 'SELECT * FROM ( '.$sql1.' UNION ALL '.$sql2.') AS t ORDER BY '.$orderBy3;
	         
	        $list = Yii::app()->db->createCommand($sql3)->queryAll();
	        foreach ($list as $v){
	            $pay_time = date('Y-m',strtotime($v['pay_time']));
	            $v_store_id = $v['store_id'];
	            if (!isset($data[$v_store_id][$pay_time])){
	                continue;
	            }
	            $pay_channel = $v['pay_channel']; //支付方式
	            $order_paymoney = $v['order_paymoney']; //订单总金额
	            $coupons_money = $v['coupons_money']; //优惠券优惠金额
	            $discount_money = $v['discount_money']; //会员优惠
	            $m_discount_money = $v['merchant_discount_money']; //商家优惠
	            $refund_money = $v['refund_money']; //退款金额
	            $order_flag = $v['order_flag']; //交易类型: 收/退款
	             
	        if ($pay_channel != ORDER_PAY_CHANNEL_ALIPAY_SM &&
	                $pay_channel != ORDER_PAY_CHANNEL_ALIPAY_TM &&
	                $pay_channel != ORDER_PAY_CHANNEL_WXPAY_SM &&
	                $pay_channel != ORDER_PAY_CHANNEL_WXPAY_TM &&
	                $pay_channel != ORDER_PAY_CHANNEL_STORED &&
	                $pay_channel != ORDER_PAY_CHANNEL_CASH &&
	                $pay_channel != ORDER_PAY_CHANNEL_UNIONPAY &&
    		        $pay_channel != ORDER_PAY_CHANNEL_NO_MONEY &&
    		        $pay_channel != ORDER_PAY_CHANNEL_GROUP ) {
	                    //非统计的支付数据
	                    continue;
	                }
	                if ($order_flag == 1){//收款记录
	                    //优惠金额
	                    $discount_money_total = $coupons_money + $discount_money + $m_discount_money;
	                    //实收金额
	                    $receipt_money = $order_paymoney - $discount_money_total;
	                     
	                    //计算交易金额和实收金额和交易笔数
	                    switch ($pay_channel) {
	                        case ORDER_PAY_CHANNEL_ALIPAY_SM: //支付宝扫码
	                            $data[$v_store_id][$pay_time]['alipay_trade_money'] += $order_paymoney;
	                            $data[$v_store_id][$pay_time]['alipay_trade_actual_money'] += $receipt_money;
	                            $data[$v_store_id][$pay_time]['alipay_trade_num'] += 1;
	                            $data[$v_store_id][$pay_time]['alipay_discount_money'] += $discount_money_total;
	                            break;
	                        case ORDER_PAY_CHANNEL_ALIPAY_TM: //支付宝条码
	                            $data[$v_store_id][$pay_time]['alipay_trade_money'] += $order_paymoney;
	                            $data[$v_store_id][$pay_time]['alipay_trade_actual_money'] += $receipt_money;
	                            $data[$v_store_id][$pay_time]['alipay_trade_num'] += 1;
	                            $data[$v_store_id][$pay_time]['alipay_discount_money'] += $discount_money_total;
	                            break;
	                        case ORDER_PAY_CHANNEL_WXPAY_SM: //微信扫码
	                            $data[$v_store_id][$pay_time]['wechat_trade_money'] += $order_paymoney;
	                            $data[$v_store_id][$pay_time]['wechat_trade_actual_money'] += $receipt_money;
	                            $data[$v_store_id][$pay_time]['wechat_trade_num'] += 1;
	                            $data[$v_store_id][$pay_time]['wechat_discount_money'] += $discount_money_total;
	                            break;
	                        case ORDER_PAY_CHANNEL_WXPAY_TM: //微信条码
	                            $data[$v_store_id][$pay_time]['wechat_trade_money'] += $order_paymoney;
	                            $data[$v_store_id][$pay_time]['wechat_trade_actual_money'] += $receipt_money;
	                            $data[$v_store_id][$pay_time]['wechat_trade_num'] += 1;
	                            $data[$v_store_id][$pay_time]['wechat_discount_money'] += $discount_money_total;
	                            break;
	                        case ORDER_PAY_CHANNEL_CASH: //现金支付
	                            $data[$v_store_id][$pay_time]['cash_trade_money'] += $order_paymoney;
	                            $data[$v_store_id][$pay_time]['cash_trade_actual_money'] += $receipt_money;
	                            $data[$v_store_id][$pay_time]['cash_trade_num'] += 1;
	                            $data[$v_store_id][$pay_time]['cash_discount_money'] += $discount_money_total;
	                            break;
	                        case ORDER_PAY_CHANNEL_UNIONPAY: //银联支付
	                            $data[$v_store_id][$pay_time]['unionpay_trade_money'] += $order_paymoney;
	                            $data[$v_store_id][$pay_time]['unionpay_trade_actual_money'] += $receipt_money;
	                            $data[$v_store_id][$pay_time]['unionpay_trade_num'] += 1;
	                            $data[$v_store_id][$pay_time]['unionpay_discount_money'] += $discount_money_total;
	                            break;
	                        case ORDER_PAY_CHANNEL_STORED: //储值支付
	                            $data[$v_store_id][$pay_time]['stored_trade_money'] += $order_paymoney;
	                            $data[$v_store_id][$pay_time]['stored_trade_actual_money'] += $receipt_money;
	                            $data[$v_store_id][$pay_time]['stored_trade_num'] += 1;
	                            $data[$v_store_id][$pay_time]['stored_discount_money'] += $discount_money_total;
	                            break;
                            case ORDER_PAY_CHANNEL_NO_MONEY: //无需支付
                                $data[$v_store_id][$pay_time]['nopay_trade_money'] += $order_paymoney;
                                $data[$v_store_id][$pay_time]['nopay_trade_actual_money'] += $receipt_money;
                                $data[$v_store_id][$pay_time]['nopay_trade_num'] += 1;
                                $data[$v_store_id][$pay_time]['nopay_discount_money'] += $discount_money_total;
                                break;
                            case ORDER_PAY_CHANNEL_GROUP: //集中支付
                                $data[$v_store_id][$pay_time]['group_trade_money'] += $order_paymoney;
                                $data[$v_store_id][$pay_time]['group_trade_actual_money'] += $receipt_money;
                                $data[$v_store_id][$pay_time]['group_trade_num'] += 1;
                                $data[$v_store_id][$pay_time]['group_discount_money'] += $discount_money_total;
                                break;
	                        default:
	                            break;
	                    }
	                    //总交易统计:累计交易额/累计实际交易额/交易笔数
	                    $data[$v_store_id][$pay_time]['total_trade_money'] += $order_paymoney;
	                    $data[$v_store_id][$pay_time]['total_trade_actual_money'] += $receipt_money;
	                    $data[$v_store_id][$pay_time]['total_trade_num'] += 1;
	                    $data[$v_store_id][$pay_time]['total_discount_money'] += $discount_money_total;
	                }else if ($order_flag == 2){//退款记录
	                    //计算交易金额和实收金额和交易笔数
	                    switch ($pay_channel) {
	                        case ORDER_PAY_CHANNEL_ALIPAY_SM: //支付宝扫码
	                            $data[$v_store_id][$pay_time]['alipay_trade_actual_money'] -= $refund_money;
	                            $data[$v_store_id][$pay_time]['alipay_refund_money'] += $refund_money;
	                            $data[$v_store_id][$pay_time]['alipay_refund_num'] += 1;
	                            break;
	                        case ORDER_PAY_CHANNEL_ALIPAY_TM: //支付宝条码
	                            $data[$v_store_id][$pay_time]['alipay_trade_actual_money'] -= $refund_money;
	                            $data[$v_store_id][$pay_time]['alipay_refund_money'] += $refund_money;
	                            $data[$v_store_id][$pay_time]['alipay_refund_num'] += 1;
	                            break;
	                        case ORDER_PAY_CHANNEL_WXPAY_SM: //微信扫码
	                            $data[$v_store_id][$pay_time]['wechat_trade_actual_money'] -= $refund_money;
	                            $data[$v_store_id][$pay_time]['wechat_refund_money'] += $refund_money;
	                            $data[$v_store_id][$pay_time]['wechat_refund_num'] += 1;
	                            break;
	                        case ORDER_PAY_CHANNEL_WXPAY_TM: //微信条码
	                            $data[$v_store_id][$pay_time]['wechat_trade_actual_money'] -= $refund_money;
	                            $data[$v_store_id][$pay_time]['wechat_refund_money'] += $refund_money;
	                            $data[$v_store_id][$pay_time]['wechat_refund_num'] += 1;
	                            break;
	                        case ORDER_PAY_CHANNEL_CASH: //现金支付
	                            $data[$v_store_id][$pay_time]['cash_trade_actual_money'] -= $refund_money;
	                            $data[$v_store_id][$pay_time]['cash_refund_money'] += $refund_money;
	                            $data[$v_store_id][$pay_time]['cash_refund_num'] += 1;
	                            break;
	                        case ORDER_PAY_CHANNEL_UNIONPAY: //银联支付
	                            $data[$v_store_id][$pay_time]['unionpay_trade_actual_money'] -= $refund_money;
	                            $data[$v_store_id][$pay_time]['unionpay_refund_money'] += $refund_money;
	                            $data[$v_store_id][$pay_time]['unionpay_refund_num'] += 1;
	                            break;
	                        case ORDER_PAY_CHANNEL_STORED: //储值支付
	                            $data[$v_store_id][$pay_time]['stored_trade_actual_money'] -= $refund_money;
	                            $data[$v_store_id][$pay_time]['stored_refund_money'] += $refund_money;
	                            $data[$v_store_id][$pay_time]['stored_refund_num'] += 1;
	                            break;
                            case ORDER_PAY_CHANNEL_NO_MONEY: //无需支付
                                $data[$v_store_id][$pay_time]['nopay_trade_actual_money'] -= $refund_money;
                                $data[$v_store_id][$pay_time]['nopay_refund_money'] += $refund_money;
                                $data[$v_store_id][$pay_time]['nopay_refund_num'] += 1;
                                break;
                            case ORDER_PAY_CHANNEL_GROUP: //集中支付
                                $data[$v_store_id][$pay_time]['group_trade_actual_money'] -= $refund_money;
                                $data[$v_store_id][$pay_time]['group_refund_money'] += $refund_money;
                                $data[$v_store_id][$pay_time]['group_refund_num'] += 1;
                                break;
	                        default:
	                            break;
	                    }
	                    //总交易统计:累计交易额/累计实际交易额/交易笔数
	                    $data[$v_store_id][$pay_time]['total_trade_actual_money'] -= $refund_money;
	                    $data[$v_store_id][$pay_time]['total_refund_money'] += $refund_money;
	                    $data[$v_store_id][$pay_time]['total_refund_num'] += 1;
	                }
	        }
	        
	        //合并关联门店数据
	        $store_arr2 = $this->getMerchantLowerStoreId($merchant_id);
	        $relative_storeId = $this->getRelationStoreIdRelative($store_arr2);
	        foreach ($relative_storeId as $k => $v){
	            if (!empty($data[$k]) && !empty($data[$v])){
	                foreach ($data[$k] as $k2 => $v2){
	                    foreach ($v2 as $k3 => $v3){
	                        if ($k3 == 'name' || $k3 == 'branch_name' || $k3 == 'number'){
	                            continue;
	                        }
	                        $data[$k][$k2][$k3] += $data[$v][$k2][$k3];
	                    }
	                }
	            }
	            unset($data[$v]);
	        }
	        $result['data'] = $data;
	        $result['status'] = ERROR_NONE;
	    }catch (Exception $e) {
	        $result['status'] = isset($result['status'])?$result['status']:ERROR_EXCEPTION;
	        $result['errMsg'] = $e -> getMessage();
	    }
	    return json_encode($result);
	}
	
	/**
	 * 获取门店汇总       ( 可用于导出excel表 )
	 * @param  必选     $merchant_id     商户id
	 * @param  可选     $start_time      交易时间-开始:        默认今日
	 * @param  可选     $end_time        交易时间-结束:        默认今日
	 * @param  可选     $store_id        门店id（数组）:       默认全部门店
	 * @param  可选     $pay_channel     支付渠道 :           默认全渠道
	 * @param  可选     $orderby_key     排序键:             默认门店编号
	 * @param  可选     $orderby_value   排序值:             默认升序
	 * @param  可选     $page_no         是否分页,用于导出excel: 默认分页
	 */
	public function getSStatistics($merchant_id,$start_time,$end_time,$store_id,
	    $pay_channel,$orderby_key,$orderby_value,$page_no=NULL)
	{
	    $result = array();
	    try {
	        //验证参数
	        if (empty($merchant_id)){
	            $result['status'] = ERROR_PARAMETER_MISS;
	            throw new Exception('参数merchant_id必填');
	        }
	        
	        //获取商户下门店
	        $store_arr = $this->getMerchantLowerStore($merchant_id,$store_id);
	        //初始化数据
	        $storeId_arr = array();
	        foreach ($store_arr as $k => $v){
	            $storeId_arr[] = $v['id'];
	            $data[$v['id']] = array(
	                'name' =>$v['name'],
	                'branch_name' =>$v['branch_name'],
	                'number' =>$v['number'],
	        
	                'total_trade_money' =>0,
	                'total_trade_actual_money' =>0,
	                'total_trade_num' =>0,
	                'total_discount_money' =>0,
	                'total_refund_money' =>0,
	                'total_refund_num' =>0,
	        
	                'alipay_trade_money' =>0,
	                'alipay_trade_actual_money' =>0,
	                'alipay_trade_num' =>0,
	                'alipay_discount_money' =>0,
	                'alipay_refund_money' =>0,
	                'alipay_refund_num' =>0,
	        
	                'wechat_trade_money' =>0,
	                'wechat_trade_actual_money' =>0,
	                'wechat_trade_num' =>0,
	                'wechat_discount_money' =>0,
	                'wechat_refund_money' =>0,
	                'wechat_refund_num' =>0,
	        
	                'unionpay_trade_money' =>0,
	                'unionpay_trade_actual_money' =>0,
	                'unionpay_trade_num' =>0,
	                'unionpay_discount_money' =>0,
	                'unionpay_refund_money' =>0,
	                'unionpay_refund_num' =>0,
	        
	                'stored_trade_money' =>0,
	                'stored_trade_actual_money' =>0,
	                'stored_trade_num' =>0,
	                'stored_discount_money' =>0,
	                'stored_refund_money' =>0,
	                'stored_refund_num' =>0,
	        
	                'cash_trade_money' =>0,
	                'cash_trade_actual_money' =>0,
	                'cash_trade_num' =>0,
	                'cash_discount_money' =>0,
	                'cash_refund_money' =>0,
	                'cash_refund_num' =>0,
	                
	                'nopay_trade_money' =>0,
	                'nopay_trade_actual_money' =>0,
	                'nopay_trade_num' =>0,
	                'nopay_discount_money' =>0,
	                'nopay_refund_money' =>0,
	                'nopay_refund_num' =>0,
	                
	                'group_trade_money' =>0,
	                'group_trade_actual_money' =>0,
	                'group_trade_num' =>0,
	                'group_discount_money' =>0,
	                'group_refund_money' =>0,
	                'group_refund_num' =>0,
	            );
	        }
	        
	       //查询1条件
	        $select1 = 'o.store_id,
            			o.pay_channel,
            			o.order_paymoney,
            			o.coupons_money,
            			o.discount_money,
            			o.merchant_discount_money,
            			o.flag AS refund_money,
            			o.flag AS order_flag';
	        $from1 = 'wq_order AS o';
	        $where1 = 'o.pay_status = '.ORDER_STATUS_PAID;//已支付订单
	        $where1 .= ' AND o.order_type = '.ORDER_TYPE_CASHIER;//收银订单
	        $where1 .= ' AND o.order_status != '.ORDER_STATUS_REVOKE;//已撤销
	        $where1 .= ' AND o.flag = '.FLAG_NO;
	        //查询2条件
	        $select2 = 'o.store_id,
				o.pay_channel,
				o.order_paymoney,
				o.coupons_money,
				o.discount_money,
				o.merchant_discount_money,
				r.refund_money,
				o.pay_status AS order_flag';
	        $from2 = 'wq_refund_record AS r';
	        $join2 = ' INNER JOIN wq_order AS o ON r.order_id = o.id';
	        $where2 = 'r.status != '.REFUND_STATUS_FAIL;//退款失败
	        $where2 .= ' AND r.type = '.REFUND_TYPE_REFUND;//退款
	        $where2 .= ' AND r.flag = '.FLAG_NO;
	        //查询3条件
	        $orderBy3 = 't.store_id ASC';

            //时间搜索
            if (!empty($start_time) && !empty($end_time)){
                $start_time1 = date("Y-m-d 00:00:00",strtotime($start_time));
                $end_time1 = date("Y-m-d 23:59:59",strtotime($end_time));
                $where1 .= " AND o.pay_time >= '".$start_time1."'";
                $where2 .= " AND r.refund_time >= '".$start_time1."'";
                $where1 .= " AND o.pay_time <= '".$end_time1."'";
                $where2 .= " AND r.refund_time <= '".$end_time1."'";
            }else{//默认今日
                $start_time1 = date("Y-m-d 00:00:00");
                $end_time1 = date("Y-m-d 23:59:59");
                $where1 .= " AND o.pay_time >= '".$start_time1."'";
                $where2 .= " AND r.refund_time >= '".$start_time1."'";
                $where1 .= " AND o.pay_time <= '".$end_time1."'";
                $where2 .= " AND r.refund_time <= '".$end_time1."'";
            }            
            //门店搜索
            $store_str = implode(',', $storeId_arr);
            $where1 .= " AND o.store_id IN (".$store_str.")";
            $where2 .= " AND o.store_id IN (".$store_str.")";
            //支付渠道搜索
            if (!empty($pay_channel)){
                $pay_channel_str = implode(',', $pay_channel);
                $where1 .= " AND o.pay_channel IN (".$pay_channel_str.")";
                $where2 .= " AND r.refund_channel IN (".$pay_channel_str.")";
            }
	        $sql1 = 'SELECT '.$select1.' FROM '.$from1.' WHERE '.$where1;
	        $sql2 = 'SELECT '.$select2.' FROM '.$from2.$join2.' WHERE '.$where2;
	        $sql3 = 'SELECT * FROM ( '.$sql1.' UNION ALL '.$sql2.') AS t ORDER BY '.$orderBy3;
	        
    		$list = Yii::app()->db->createCommand($sql3)->queryAll();
    		foreach ($list as $v){
    		    $storeid = $v['store_id'];
    		    $pay_channel = $v['pay_channel']; //支付方式
    		    $order_paymoney = $v['order_paymoney']; //订单总金额
    		    $coupons_money = $v['coupons_money']; //优惠券优惠金额
    		    $discount_money = $v['discount_money']; //会员优惠
    		    $m_discount_money = $v['merchant_discount_money']; //商家优惠
    		    $refund_money = $v['refund_money']; //退款金额
    		    $order_flag = $v['order_flag']; //交易类型: 收/退款
    		    
    		    if ($pay_channel != ORDER_PAY_CHANNEL_ALIPAY_SM &&
    		        $pay_channel != ORDER_PAY_CHANNEL_ALIPAY_TM &&
    		        $pay_channel != ORDER_PAY_CHANNEL_WXPAY_SM &&
    		        $pay_channel != ORDER_PAY_CHANNEL_WXPAY_TM &&
    		        $pay_channel != ORDER_PAY_CHANNEL_STORED &&
    		        $pay_channel != ORDER_PAY_CHANNEL_CASH &&
    		        $pay_channel != ORDER_PAY_CHANNEL_UNIONPAY &&
    		        $pay_channel != ORDER_PAY_CHANNEL_NO_MONEY &&
    		        $pay_channel != ORDER_PAY_CHANNEL_GROUP ) {
    				    //非统计的支付数据
        				continue;
		        }
		        if ($order_flag == 1){//收款记录
		            //优惠金额
		            $discount_money_total = $coupons_money + $discount_money + $m_discount_money;
		            //实收金额
		            $receipt_money = $order_paymoney - $discount_money_total;
		    
		            //计算交易金额和实收金额和交易笔数
		            switch ($pay_channel) {
		                case ORDER_PAY_CHANNEL_ALIPAY_SM: //支付宝扫码
		                    $data[$storeid]['alipay_trade_money'] += $order_paymoney;
		                    $data[$storeid]['alipay_trade_actual_money'] += $receipt_money;
		                    $data[$storeid]['alipay_trade_num'] += 1;
		                    $data[$storeid]['alipay_discount_money'] += $discount_money_total;
		                    break;
		                case ORDER_PAY_CHANNEL_ALIPAY_TM: //支付宝条码
		                    $data[$storeid]['alipay_trade_money'] += $order_paymoney;
		                    $data[$storeid]['alipay_trade_actual_money'] += $receipt_money;
		                    $data[$storeid]['alipay_trade_num'] += 1;
		                    $data[$storeid]['alipay_discount_money'] += $discount_money_total;
		                    break;
		                case ORDER_PAY_CHANNEL_WXPAY_SM: //微信扫码
		                    $data[$storeid]['wechat_trade_money'] += $order_paymoney;
		                    $data[$storeid]['wechat_trade_actual_money'] += $receipt_money;
		                    $data[$storeid]['wechat_trade_num'] += 1;
		                    $data[$storeid]['wechat_discount_money'] += $discount_money_total;
		                    break;
		                case ORDER_PAY_CHANNEL_WXPAY_TM: //微信条码
		                    $data[$storeid]['wechat_trade_money'] += $order_paymoney;
		                    $data[$storeid]['wechat_trade_actual_money'] += $receipt_money;
		                    $data[$storeid]['wechat_trade_num'] += 1;
		                    $data[$storeid]['wechat_discount_money'] += $discount_money_total;
		                    break;
		                case ORDER_PAY_CHANNEL_CASH: //现金支付
		                    $data[$storeid]['cash_trade_money'] += $order_paymoney;
		                    $data[$storeid]['cash_trade_actual_money'] += $receipt_money;
		                    $data[$storeid]['cash_trade_num'] += 1;
		                    $data[$storeid]['cash_discount_money'] += $discount_money_total;
		                    break;
		                case ORDER_PAY_CHANNEL_UNIONPAY: //银联支付
		                    $data[$storeid]['unionpay_trade_money'] += $order_paymoney;
		                    $data[$storeid]['unionpay_trade_actual_money'] += $receipt_money;
		                    $data[$storeid]['unionpay_trade_num'] += 1;
		                    $data[$storeid]['unionpay_discount_money'] += $discount_money_total;
		                    break;
		                case ORDER_PAY_CHANNEL_STORED: //储值支付
		                    $data[$storeid]['stored_trade_money'] += $order_paymoney;
		                    $data[$storeid]['stored_trade_actual_money'] += $receipt_money;
		                    $data[$storeid]['stored_trade_num'] += 1;
		                    $data[$storeid]['stored_discount_money'] += $discount_money_total;
		                    break;
	                    case ORDER_PAY_CHANNEL_NO_MONEY: //无需支付
	                        $data[$storeid]['nopay_trade_money'] += $order_paymoney;
	                        $data[$storeid]['nopay_trade_actual_money'] += $receipt_money;
	                        $data[$storeid]['nopay_trade_num'] += 1;
	                        $data[$storeid]['nopay_discount_money'] += $discount_money_total;
	                        break;
	                    case ORDER_PAY_CHANNEL_GROUP: //集中支付
	                        $data[$storeid]['group_trade_money'] += $order_paymoney;
	                        $data[$storeid]['group_trade_actual_money'] += $receipt_money;
	                        $data[$storeid]['group_trade_num'] += 1;
	                        $data[$storeid]['group_discount_money'] += $discount_money_total;
	                        break;
		                default:
		                    break;
		            }
		            //总交易统计:累计交易额/累计实际交易额/交易笔数
		            $data[$storeid]['total_trade_money'] += $order_paymoney;
		            $data[$storeid]['total_trade_actual_money'] += $receipt_money;
		            $data[$storeid]['total_trade_num'] += 1;
		            $data[$storeid]['total_discount_money'] += $discount_money_total;
		        }else if ($order_flag == 2){//退款记录
		            //计算交易金额和实收金额和交易笔数
		            switch ($pay_channel) {
		                case ORDER_PAY_CHANNEL_ALIPAY_SM: //支付宝扫码
		                    $data[$storeid]['alipay_trade_actual_money'] -= $refund_money;
		                    $data[$storeid]['alipay_refund_money'] += $refund_money;
		                    $data[$storeid]['alipay_refund_num'] += 1;
		                    break;
		                case ORDER_PAY_CHANNEL_ALIPAY_TM: //支付宝条码
		                    $data[$storeid]['alipay_trade_actual_money'] -= $refund_money;
		                    $data[$storeid]['alipay_refund_money'] += $refund_money;
		                    $data[$storeid]['alipay_refund_num'] += 1;
		                    break;
		                case ORDER_PAY_CHANNEL_WXPAY_SM: //微信扫码
		                    $data[$storeid]['wechat_trade_actual_money'] -= $refund_money;
		                    $data[$storeid]['wechat_refund_money'] += $refund_money;
		                    $data[$storeid]['wechat_refund_num'] += 1;
		                    break;
		                case ORDER_PAY_CHANNEL_WXPAY_TM: //微信条码
		                    $data[$storeid]['wechat_trade_actual_money'] -= $refund_money;
		                    $data[$storeid]['wechat_refund_money'] += $refund_money;
		                    $data[$storeid]['wechat_refund_num'] += 1;
		                    break;
		                case ORDER_PAY_CHANNEL_CASH: //现金支付
		                    $data[$storeid]['cash_trade_actual_money'] -= $refund_money;
		                    $data[$storeid]['cash_refund_money'] += $refund_money;
		                    $data[$storeid]['cash_refund_num'] += 1;
		                    break;
		                case ORDER_PAY_CHANNEL_UNIONPAY: //银联支付
		                    $data[$storeid]['unionpay_trade_actual_money'] -= $refund_money;
		                    $data[$storeid]['unionpay_refund_money'] += $refund_money;
		                    $data[$storeid]['unionpay_refund_num'] += 1;
		                    break;
		                case ORDER_PAY_CHANNEL_STORED: //储值支付
		                    $data[$storeid]['stored_trade_actual_money'] -= $refund_money;
		                    $data[$storeid]['stored_refund_money'] += $refund_money;
		                    $data[$storeid]['stored_refund_num'] += 1;
		                    break;
	                    case ORDER_PAY_CHANNEL_NO_MONEY: //无需支付
	                        $data[$storeid]['nopay_trade_actual_money'] -= $refund_money;
	                        $data[$storeid]['nopay_refund_money'] += $refund_money;
	                        $data[$storeid]['nopay_refund_num'] += 1;
	                        break;
	                    case ORDER_PAY_CHANNEL_GROUP: //集中支付
	                        $data[$storeid]['group_trade_actual_money'] -= $refund_money;
	                        $data[$storeid]['group_refund_money'] += $refund_money;
	                        $data[$storeid]['group_refund_num'] += 1;
	                        break;
		                default:
		                    break;
		            }
		            //总交易统计:累计交易额/累计实际交易额/交易笔数
		            $data[$storeid]['total_trade_actual_money'] -= $refund_money;
		            $data[$storeid]['total_refund_money'] += $refund_money;
		            $data[$storeid]['total_refund_num'] += 1;
		        }
    		}
    		//合并关联门店数据
    		$store_arr2 = $this->getMerchantLowerStoreId($merchant_id);
    		$relative_storeId = $this->getRelationStoreIdRelative($storeId_arr);
    		foreach ($relative_storeId as $k => $v){
    		    if (!empty($data[$k]) && !empty($data[$v])){
    		        foreach ($data[$k] as $k2 => $v2){
    		            if ($k2 == 'name' || $k2 == 'branch_name' || $k2 == 'number'){
    		                continue;
    		            }
    		            $data[$k][$k2] += $data[$v][$k2];
    		        }
    		    }
    		    unset($data[$v]);
    		}
    		//排序字段
    		if (empty($orderby_key)){
    		    $orderby_key = 'number';
    		}
    		$orderby_arr = array();
    		foreach ($data as $k=>$v){
    		    $orderby_arr[$k] = $v[$orderby_key];
    		}
    		//排序方式: 1.升序; 2.降序
    		if (empty($orderby_value) || $orderby_value == 1){
    		    array_multisort($orderby_arr,SORT_ASC,$data);
    		}else{
    		    array_multisort($orderby_arr,SORT_DESC,$data);
    		}
    		//分页
    		if (empty($page_no)){
    		    $count = count($data);
    		    $pages = new CPagination($count);
    		    $pages -> pageSize = 20;
    		    $this -> page = $pages;
    		    $page = 1;
    		    if (isset($_GET['page']) && !empty($_GET['page'])){
    		        $page = $_GET['page'];
    		    }
    		    $offset = $page*20-20;
    		    $data = array_slice($data, $offset, 20);
    		}
    		
	        $result['data'] = $data;
	        $result['status'] = ERROR_NONE;
	    }catch (Exception $e) {
	        $result['status'] = isset($result['status'])?$result['status']:ERROR_EXCEPTION;
	        $result['errMsg'] = $e -> getMessage();
	    }
	    return json_encode($result);
	}
	
	/**
	 * 获取门店id和关联id的对应关系
	 * @param unknown $storeId_arr
	 */
	private function getRelationStoreIdRelative($storeId_arr){
	    $result = array();
	    if (!empty($storeId_arr)){
	        //获取关联门店id
	        $criteria = new CDbCriteria();
	        $criteria->select = "id,relation_store_id";
	        $criteria->addInCondition('id',$storeId_arr);
	        $criteria->addCondition('flag = :flag');
	        $criteria->params[':flag'] = FLAG_NO;
	        $model = Store::model()->findAll($criteria);
	        if (!empty($model)){
	            foreach ($model as $k=>$v){
	                if (!empty($v['relation_store_id'])){
	                    $result[$v['id']] = $v['relation_store_id'];
	                }
	            }
	        }
	    }
	    return $result;
	}
	
	/**
	 * 获取商户下门店信息
	 */
	public function getMerchantLowerStore($merchant_id,$store_id,$no_relation=NULL){
	    $stores = array();
	    //获取商户下门店
	    $store_arr = array();
	    if (!empty($store_id)){
	        $store_arr = $store_id;
	    }else{
	        //是否管理员
	        if(Yii::app() -> session['role'] == WQ_ROLE_MANAGER){
	            $storeId = Yii::app ()->session ['store_id'];
	            if (!empty($storeId)){
	                $storeId = substr ( $storeId, 1, strlen ( $storeId ) - 2 );
	                $store_arr = explode ( ',', $storeId );
	            }
	        }else{
	            $store_arr = $this->getMerchantLowerStoreId($merchant_id);
	        }
	    }
	    //获取关联门店id,得到id并集
	    if (empty($no_relation)){
	        $store_arr = $this->getRelationStoresId($store_arr);
	    }
	    $criteria = new CDbCriteria();
	    $criteria->select = "id,name,branch_name,number";
	    $criteria->addInCondition('id',$store_arr);
	    $criteria->addCondition('flag = :flag');
	    $criteria->params[':flag'] = FLAG_NO;
	    $model = Store::model()->findAll($criteria);
	    if (!empty($model)){
	        foreach ($model as $k=>$v){
	            $stores[$v['id']]['id'] = $v['id'];
	            $stores[$v['id']]['name'] = $v['name'];
	            $stores[$v['id']]['branch_name'] = $v['branch_name'];
	            $stores[$v['id']]['number'] = $v['number'];
	        }
	    }
	    return $stores;
	}
	
	/**
	 * 获取商户下门店id
	 */
	private function getMerchantLowerStoreId($merchant_id){
	    $stores = array();
	    $model = Store::model()->findAll(array(
	        'select' => 'id',
	        'condition' => 'merchant_id = :merchant_id AND flag = :flag',
	        'params' => array(':merchant_id'=> $merchant_id,':flag'=>FLAG_NO)
	    ));
	    if (!empty($model)){
	        foreach ($model as $k=>$v){
	            $stores[] = $v['id'];
	        }
	    }
	    return $stores;
	}
	
	/**
	 * 获取商户下操作员列表
	 */
	public function getOperatorDropdownList($merchant_id,$store_id = NULL){
	    $result = array();
	    try {
	        //验证参数
	        if (empty($merchant_id)){
	            $result['status'] = ERROR_PARAMETER_MISS;
	            throw new Exception('参数merchant_id必填');
	        }
	        $store_arr = array();
	        if (!empty($store_id)){
	            $store_arr = $store_id;
	        }else{
	            //是否管理员
	            if(Yii::app() -> session['role'] == WQ_ROLE_MANAGER){
    	            $storeId = Yii::app ()->session ['store_id'];
    	            if (!empty($storeId)){
    	                $storeId = substr ( $storeId, 1, strlen ( $storeId ) - 2 );
    	                $store_arr = explode ( ',', $storeId );
    	            }
	            }else{
	                //获取商户下门店
	                $store_arr = $this->getMerchantLowerStoreId($merchant_id);
	            }
	        }
	        //获取关联门店id,得到id并集
	        $store_arr = $this->getRelationStoresId($store_arr);
	        $criteria = new CDbCriteria();
	        $criteria->select = "id,name,number";
	        $criteria->addInCondition('store_id',$store_arr);
    		$criteria->addCondition('flag = :flag');
    		$criteria->params[':flag'] = FLAG_NO;
    		$model = Operator::model()->findAll($criteria);
    	    if (empty($model)){
    	        $result['status'] = ERROR_NO_DATA;
    	        throw new Exception('数据为空');
    	    }
	        $data = array();
	        foreach ($model as $v){
	            $data[$v['id']] = $v['name'].' ('.$v['number'].')';
	        }
	        $result['data'] = $data;
	        $result['status'] = ERROR_NONE;
        }catch (Exception $e) {
            $result['status'] = isset($result['status'])?$result['status']:ERROR_EXCEPTION;
            $result['errMsg'] = $e -> getMessage();
        }
        return json_encode($result);
	}
	
	/**
	 * 获取门店下拉列表
	 */
	public function getStoreDropdownList($merchant_id){
	    $result = array();
	    try {
	        //验证参数
	        if (empty($merchant_id)){
	            $result['status'] = ERROR_PARAMETER_MISS;
	            throw new Exception('参数merchant_id必填');
	        }
	        $store_arr = array();
            //是否管理员
            if(Yii::app() -> session['role'] == WQ_ROLE_MANAGER){
                $storeId = Yii::app ()->session ['store_id'];
                if (!empty($storeId)){
                    $storeId = substr ( $storeId, 1, strlen ( $storeId ) - 2 );
                    $store_arr = explode ( ',', $storeId );
                }
            }else{
                //获取商户下门店
                $store_arr = $this->getMerchantLowerStoreId($merchant_id);
            }
	        //获取所有门店
	        $criteria = new CDbCriteria();
	        $criteria->select = "id,name,branch_name,management_id,address";
	        $criteria->addInCondition('id',$store_arr);
	        $criteria->addCondition('flag = :flag');
	        $criteria->params[':flag'] = FLAG_NO;
	        $list1 = Store::model()->findAll($criteria);
	        if (empty($list1)){
	            $result['status'] = ERROR_NO_DATA;
	            throw new Exception('数据为空');
	        }
	        $data['store_count'] = count($list1);
	        //遍历获取门店分组id
	        $managementId_arr = array(); //分组id集合
	        $group_store = array(); //分组门店集合
	        foreach ($list1 as $k=>$v){
	            if (!empty($v['management_id'])){
	                $managementId_arr[] = $v['management_id'];
	                $group_store[$v['management_id']][$k]['id'] = $v['id'];
	                $group_store[$v['management_id']][$k]['name'] = empty($v['branch_name'])?$v['name']:$v['name'].' -'.$v['branch_name'];
	                $group_store[$v['management_id']][$k]['address'] = str_replace(',', '', $v['address']);
	            }else{
	                //加入无分组门店
	                $data['store'][$v['id']]['store_id'] = $v['id'];
	                $data['store'][$v['id']]['name'] = empty($v['branch_name'])?$v['name']:$v['name'].' ('.$v['branch_name'].')';
	                $data['store'][$v['id']]['address'] = str_replace(',', '', $v['address']);
	            }
	        }
	        //获取门店分组
	        $criteria = new CDbCriteria();
	        $criteria->select = "id,p_mid,name";
	        $criteria -> addCondition('merchant_id=:merchant_id');
	        $criteria -> params[':merchant_id'] = $merchant_id;
	        $criteria -> addCondition('flag=:flag');
	        $criteria -> params[':flag'] = FLAG_NO;
	        $criteria -> order = 'create_time desc';
	        $manageMent = Management::model ()->findAll($criteria);
	        
	        //加入一级分组名
	        foreach ($manageMent as $k=>$v){
	            if (empty($v['p_mid'])){
	                $data['group'][$v['id']]['group_id'] = $v['id'];
	                $data['group'][$v['id']]['name'] = $v['name'];
	            }
	        }
	        //加入二级分组名及门店
	        foreach ($manageMent as $k=>$v){
	            if (!empty($v['p_mid']) && !empty($data['group'][$v['p_mid']])){
	                $data['group'][$v['p_mid']]['group'][$v['id']]['group_id'] = $v['id'];
	                $data['group'][$v['p_mid']]['group'][$v['id']]['name'] = $v['name'];
	            }
	        }
	        //加入分组门店
	        if (!empty($data['group'])){
	            foreach ($data['group'] as $k => $v){
    	            if (!empty($group_store[$k])){
    	                foreach ($group_store[$k] as $k2 => $v2){
    	                    $data['group'][$k]['store'][$v2['id']]['store_id'] = $v2['id'];
    	                    $data['group'][$k]['store'][$v2['id']]['name'] = empty($v2['branch_name'])?$v2['name']:$v2['name'].' ('.$v2['branch_name'].')';
    	                    $data['group'][$k]['store'][$v2['id']]['address'] = str_replace(',', '', $v2['address']);
    	                }
    	            }
    	            if (!empty($v['group'])){
    	                foreach ($v['group'] as $k3 => $v3){
    	                    if (!empty($group_store[$k3])){
    	                        foreach ($group_store[$k3] as $k4 => $v4){
    	                            $data['group'][$k]['group'][$k3]['store'][$v4['id']]['store_id'] = $v4['id'];
    	                            $data['group'][$k]['group'][$k3]['store'][$v4['id']]['name'] = empty($v4['branch_name'])?$v4['name']:$v4['name'].' ('.$v4['branch_name'].')';
    	                            $data['group'][$k]['group'][$k3]['store'][$v4['id']]['address'] = str_replace(',', '', $v4['address']);
    	                        }  
    	                    }
    	                }
    	            }
	            }  
	        }
	        $result['data'] = $data;
	        $result['status'] = ERROR_NONE;
	    }catch (Exception $e) {
	        $result['status'] = isset($result['status'])?$result['status']:ERROR_EXCEPTION;
	        $result['errMsg'] = $e -> getMessage();
	    }
	    return json_encode($result);
	}
	
	/**
	 * 获取商户信息      2016/07/28
	 * @param unknown $merchant_id
	 */
	public function getMerchantInfo($merchant_id){
	    $result = array();
	    try {
	        //验证参数
	        if (empty($merchant_id)){
	            $result['status'] = ERROR_PARAMETER_MISS;
	            throw new Exception('参数merchant_id必填');
	        }
	        //获取商户信息
	        $model = Merchant::model()->find(array(
	            'select' => 'id,wq_m_name',
	            'condition' => 'flag=:flag AND id=:id',
	            'params' => array(
	                ':flag' => FLAG_NO,
	                ':id' => $merchant_id
	            )
	        ));
	        if (empty($model)){
	            $result['status'] = ERROR_NO_DATA;
	            throw new Exception('无此数据');
	        }
	        $data['id'] = $model->id;
	        $data['wq_m_name'] = $model->wq_m_name;
	        $result['data'] = $data;
	        $result['status'] = ERROR_NONE;
	    }catch (Exception $e) {
	        $result['status'] = isset($result['status'])?$result['status']:ERROR_EXCEPTION;
	        $result['errMsg'] = $e -> getMessage();
	    }
	    return json_encode($result);
	}
}