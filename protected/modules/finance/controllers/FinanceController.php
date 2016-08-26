<?php
/**
 * 财务管理
 * ronson  2016/07/13
 */
class FinanceController extends mFinanceController
{
    public $layout = 'newMain';
    
    /**
     * 交易明细列表
     */
    public function actionTradeList(){
        $list = array();//交易列表
        $operators = array();//操作员下拉列表
        $storeDropdownList = array();//门店下拉列表
        
        $merchant_id = Yii::app()->session['merchant_id'];
        //筛选条件
        $order_no = ''; //订单号
        if (isset($_GET['order_no']) && !empty($_GET['order_no'])){
            $order_no = $_GET['order_no'];
        }
        $start_time = ''; //搜索时间范围
        $end_time = '';
        if (isset($_GET['Time']) && !empty($_GET['Time'])){
            $time = $_GET['Time'];
            $time_arr = explode('-', $time);
            $start_time = trim($time_arr[0]);
            $end_time = trim($time_arr[1]);
        }
        $store_id = array(); //门店id数组
        if (isset($_GET['store_id']) && !empty($_GET['store_id'])){
            $store_id = explode(',', $_GET['store_id']);
        }
        $operator_id = ''; //操作员id
        if (isset($_GET['operator']) && !empty($_GET['operator'])){
            $operator_id = $_GET['operator'];
        }
        $trade_type = ''; //交易类型（收款，退款） 
        if (isset($_GET['trade_type']) && !empty($_GET['trade_type'])){
            $trade_type = $_GET['trade_type'];
        }
        $order_status = ''; //订单状态    
        if (isset($_GET['order_status']) && !empty($_GET['order_status'])){
            $order_status = $_GET['order_status'];
        }
        $pay_channel = array(); //支付渠道 
        if (isset($_GET['pay_channel']) && !empty($_GET['pay_channel'])){
            $pay_channel = explode(',', $_GET['pay_channel']);
        }
        //获取明细列表
        $tradeC = new TradeC();
        $result = json_decode($tradeC->getTradeList($merchant_id, $order_no, $start_time,
            $end_time, $store_id, $operator_id, $trade_type, $order_status, $pay_channel),true);
        if ($result['status'] == ERROR_NONE){
            $list = $result['data'];
        }
        
        //订单状态下拉列表
        $status = array();
        $status[ORDER_STATUS_NORMAL] = $GLOBALS['ORDER_STATUS_PAY'][ORDER_STATUS_PAID];
        $status[ORDER_STATUS_REFUND] = $GLOBALS['ORDER_STATUS'][ORDER_STATUS_REFUND];
        $status[ORDER_STATUS_PART_REFUND] = $GLOBALS['ORDER_STATUS'][ORDER_STATUS_PART_REFUND];
        $status[ORDER_STATUS_HANDLE_REFUND] = $GLOBALS['ORDER_STATUS'][ORDER_STATUS_HANDLE_REFUND];
        
        //获取操作员下拉列表
        $operator_res = json_decode($tradeC->getOperatorDropdownList($merchant_id,$store_id),true);
        if ($operator_res['status'] == ERROR_NONE){
            $operators = $operator_res['data'];
        }
        //获取门店下拉列表
        $storeDropdownList_res = json_decode($tradeC->getStoreDropdownList($merchant_id),true);
        if ($storeDropdownList_res['status'] == ERROR_NONE){
            $storeDropdownList = $storeDropdownList_res['data'];
        }
        $this->render('tradeList',array(
            'list' => $list,
            'status' => $status,
            'operators' => $operators,
            'storeDropdownList' => $storeDropdownList,
            'pages' => $tradeC->page
        ));
    }
    
    /**
     * 导出Excel - 交易明细表
     */
    public function actionExportTradeListExcel(){
        $list = array();//交易列表
        $merchant_id = Yii::app()->session['merchant_id'];
        //获取玩券商户名称
        $tradeC = new TradeC();
        $merchcantInfo_res = json_decode($tradeC->getMerchantInfo($merchant_id),true);
        $merchant_name = '';
        if ($merchcantInfo_res['status'] == ERROR_NONE){
            $merchant_name = $merchcantInfo_res['data']['wq_m_name'];
        }
        //获取get
        $get = array();
        if (isset($_GET['get']) && !empty($_GET['get'])){
            $get = $_GET['get'];
        }
        //筛选条件
        $order_no = ''; //订单号
        if (isset($get['order_no']) && !empty($get['order_no'])){
            $order_no = $get['order_no'];
        }
        $start_time = ''; //搜索时间范围
        $end_time = '';
        if (isset($get['Time']) && !empty($get['Time'])){
            $time = $get['Time'];
            $time_arr = explode('-', $time);
            $start_time = trim($time_arr[0]);
            $end_time = trim($time_arr[1]);
        }
        $store_id = array(); //门店id数组
        if (isset($get['store_id']) && !empty($get['store_id'])){
            $store_id = explode(',', $get['store_id']);
        }
        $operator_id = ''; //操作员id
        if (isset($get['operator']) && !empty($get['operator'])){
            $operator_id = $get['operator'];
        }
        $trade_type = ''; //交易类型（收款，退款） 
        if (isset($get['trade_type']) && !empty($get['trade_type'])){
            $trade_type = $get['trade_type'];
        }
        $order_status = ''; //订单状态    
        if (isset($get['order_status']) && !empty($get['order_status'])){
            $order_status = $get['order_status'];
        }
        $pay_channel = array(); //支付渠道 
        if (isset($get['pay_channel']) && !empty($get['pay_channel'])){
            $pay_channel = explode(',', $get['pay_channel']);
        }
        //获取明细列表
        $result = json_decode($tradeC->getTradeList($merchant_id, $order_no, $start_time,
            $end_time, $store_id, $operator_id, $trade_type, $order_status, $pay_channel,1),true);
        if ($result['status'] == ERROR_NONE && !empty($result['data']['list'])){
            $list = $result['data']['list'];
        }
        //建导出表
        if (empty($start_time)){
            $start_time_1 = date('Y年m月d日 00:00:00');
            $end_time_1 = date('Y年m月d日 H:i:s');
            $start_time_2 = date('Y年m月d日');
            $end_time_2 = date('Y年m月d日');
        }else{
            $start_time_1 = date('Y年m月d日 00:00:00',strtotime($start_time));
            $start_time_2 = date('Y年m月d日',strtotime($start_time));
            $end_time_2 = date('Y年m月d日',strtotime($end_time));
            if (date('Y-m-d',strtotime($end_time)) == date('Y-m-d')){
                $end_time_1 = date('Y年m月d日 H:i:s');
            }else{
                $end_time_1 = date('Y年m月d日 23:59:59',strtotime($end_time));
                
            }
        }
        $str = "\xEF\xBB\xBF"."商户名称: ".$merchant_name."\n";
        $str .= "起始日期：[".$start_time_1."]   终止日期：[".$end_time_1."]\n\n";
        $str .= "订单号,交易类型,订单金额,会员折扣,商家优惠,收入/支出,支付宝优惠,微信优惠,订单状态,交易渠道,支付账号,下单时间,交易时间,操作员,所属门店,门店编号\n";
        foreach ($list as $v){
            $order_flag = ($v['order_flag'] == FLAG_NO ? '收款':'退款'); //交易类型
            $discount_money = $v['discount_money'] > 0 ? '-'.$v['discount_money']:''; //会员折扣
            $merchant_discount_money = $v['merchant_discount_money'] > 0 ? '-'.$v['merchant_discount_money']:''; //商家优惠
            $alipay_discount_money = ($v['alipay_discount_money'] > 0 && $v['order_flag'] == FLAG_NO ? $v['alipay_discount_money']:''); //支付宝优惠
            $wxpay_discount_money = ($v['wxpay_discount_money'] > 0 && $v['order_flag'] == FLAG_NO ? $v['wxpay_discount_money']:''); //支付宝优惠
            if ($v['order_flag'] == FLAG_NO){//收支
                if ($v['order_status'] == ORDER_STATUS_REVOKE || $v['pay_status']==ORDER_STATUS_UNPAID){
                    $in_out = 0;
                }else{
                    $in_out = sprintf('%.2f',$v['order_paymoney']-$v['coupons_money']-$v['discount_money']-$v['merchant_discount_money']);
                }
            }else{
                $in_out = '-'.sprintf('%.2f',$v['refund_money']);
            }
            $order_status = $v['order_status'] == ORDER_STATUS_NORMAL ? ($v['pay_status']==ORDER_STATUS_PAID?'已付款':'待支付'):$GLOBALS['ORDER_STATUS'][$v['order_status']];//订单状态
            $pay_channel = $GLOBALS['ORDER_PAY_CHANNEL'][$v['pay_channel']];//交易渠道
            $operator_name = empty($v['operator_number']) ? $v['operator_name'] : $v['operator_name'].'('.$v['operator_number'].')';//操作员
            $store_name = empty($v['branch_name'])? $v['store_name']: $v['store_name'].'('.$v['branch_name'].')';//所属门店
            
            $str .= $v['order_no']." 	,".$order_flag.",".$v['order_paymoney'].",".$discount_money.",".$merchant_discount_money;
            $str .= ",".$in_out.",".$alipay_discount_money.",".$wxpay_discount_money.",".$order_status.",".$pay_channel.",".$v['alipay_account'];
            $str .= ",".$v['create_time'].",".(empty($v['pay_time'])?$v['create_time']:$v['pay_time']).",".$operator_name.",".$store_name.",".$v['store_number']." 	\n";
            
        }
        $filename = '交易明细'.$start_time_2.'-'.$end_time_2.'.csv'; //设置文件名
        $this->export_csv($filename,$str); //导出
    }
    
    /**
     * 获取操作员下拉框
     * @param unknown $filename
     * @param unknown $data
     */
    public function actionAjaxGetOperatorList() {
        
        $result = array();
        if(isset($_GET['stores']) && !empty($_GET['stores'])){
            $store_id = explode(',', $_GET['stores']);
            $merchant_id = Yii::app()->session['merchant_id'];
            $tradeC = new TradeC();
            //获取操作员下拉列表
            $operator_res = json_decode($tradeC->getOperatorDropdownList($merchant_id,$store_id),true);
            if ($operator_res['status'] == ERROR_NONE){
                $str = "<option value>全部</option>";
                foreach ($operator_res['data'] as $k=>$v){
                    $str = $str."<option value='".$k."'>".$v."</option>";
                }
                $result['status']= true;
                $result['data']= $str;
            }else{
                $result['status']= false;
            }
        }else{
            $result['status']= false;
        }
        echo json_encode($result,JSON_UNESCAPED_UNICODE);
    }
    
    /**
     * 导出Excel
     * @param unknown $filename
     * @param unknown $data
     */
    private function export_csv($filename,$data) {
        $data = mb_convert_encoding($data, "GBK","utf-8");
        $filename=iconv("utf-8", "GBK", $filename);
        header("Content-type:text/csv");
        header("Content-Disposition:attachment;filename=".$filename);
        header('Cache-Control:must-revalidate,post-check=0,pre-check=0');
        header('Expires:0');
        header('Pragma:public');
        echo $data;
        exit();
    }
    
    /**
     * 交易明细列表 - 交易详情
     */
    public function actionTradeDetail(){
        $list = array();
        $order_no = '';//订单号
        if (isset($_GET['order_no']) && !empty($_GET['order_no'])){
            $order_no = $_GET['order_no'];
        }
        //获取明细列表
        $tradeC = new TradeC();
        $result = json_decode($tradeC->getTradeDetail($order_no),true);
        if ($result['status'] == ERROR_NONE){
            $list = $result['data'];
        }
        $this->render('tradeDetail',array('list'=>$list));
    }
    
    /**
     * 日汇总
     */
    public function actionStoreStatisticsDay(){
        $list = array();
        $storeDropdownList = array();//门店下拉列表
        //商户id
        $merchant_id = Yii::app()->session['merchant_id'];
        //搜索时间范围
        $start_time = '';
        $end_time = '';
        if (isset($_GET['Time']) && !empty($_GET['Time'])){
            $time = $_GET['Time'];
            $time_arr = explode('-', $time);
            $start_time = trim($time_arr[0]);
            $end_time = trim($time_arr[1]);
        }
        //门店搜索
        $store_id = array();
        if (isset($_GET['store_id']) && !empty($_GET['store_id'])){
            $store_id = explode(',', $_GET['store_id']);
        }
        //支付渠道搜索
        $pay_channel = array();
        if (isset($_GET['pay_channel']) && !empty($_GET['pay_channel'])){
            $pay_channel = explode(',', $_GET['pay_channel']);
        }
        //获取列表数据
        $tradeC = new TradeC();
        $result = json_decode($tradeC->getSStatisticsDay($merchant_id,$start_time,$end_time,
            $store_id, $pay_channel),true);
        if ($result['status'] == ERROR_NONE){
            $list = $result['data'];
        }
        //获取门店下拉列表
        $storeDropdownList_res = json_decode($tradeC->getStoreDropdownList($merchant_id),true);
        if ($storeDropdownList_res['status'] == ERROR_NONE){
            $storeDropdownList = $storeDropdownList_res['data'];
        }
        
        $this->render('storeStatisticsDay',array(
            'list'=>$list,
            'storeDropdownList'=>$storeDropdownList,
            'pages' => $tradeC->page
        ));
    }
    
    /**
     * 导出Excel - 日汇总(单导)
     */
    public function actionExportDayExcel(){
        $list = array();
        
        $merchant_id = Yii::app()->session['merchant_id'];//商户id
        //获取玩券商户名称
        $tradeC = new TradeC();
        $merchcantInfo_res = json_decode($tradeC->getMerchantInfo($merchant_id),true);
        $merchant_name = '';
        if ($merchcantInfo_res['status'] == ERROR_NONE){
            $merchant_name = $merchcantInfo_res['data']['wq_m_name'];
        }
        //获取get
        $get = array();
        if (isset($_GET['get']) && !empty($_GET['get'])){
            $get = $_GET['get'];
        }
        //时间段搜索
        $start_time = '';
        $end_time = '';
        if (isset($get['today_time']) && !empty($get['today_time'])){
            $start_time = $end_time = $get['today_time'];
        }else{
            exit;
        }
        //门店搜索
        $store_id = array();
        if (isset($get['store_id']) && !empty($get['store_id'])){
            $store_id = explode(',', $get['store_id']);
        }
        //支付渠道搜索
        $pay_channel = array();
        if (isset($get['pay_channel']) && !empty($get['pay_channel'])){
            $pay_channel = explode(',', $get['pay_channel']);
        }
        
        //获取列表数据
        $result = json_decode($tradeC->getTradeList($merchant_id, '', $start_time, $end_time, $store_id, '', '', '', $pay_channel,1),true);
        if ($result['status'] == ERROR_NONE && !empty($result['data']['list'])){
            $list = $result['data']['list'];
        }        
        //建导出表
        $start_time_1 = date('Y年m月d日 00:00:00',strtotime($start_time));
        $end_time_1 = date('Y年m月d日 23:59:59',strtotime($end_time));
        $start_time_2 = date('Y年m月d日',strtotime($start_time));
        $end_time_2 = date('Y年m月d日',strtotime($end_time));
        
        $str = "\xEF\xBB\xBF"."商户名称: ".$merchant_name."\n";
        $str .= "起始日期：[".$start_time_1."]   终止日期：[".$end_time_1."]\n\n";
        $str .= "订单号,交易类型,订单金额,会员折扣,商家优惠,收入/支出,支付宝优惠,微信优惠,订单状态,交易渠道,支付账号,下单时间,交易时间,操作员,所属门店,门店编号\n";
        foreach ($list as $v){
            $order_flag = ($v['order_flag'] == FLAG_NO ? '收款':'退款'); //交易类型
            $discount_money = $v['discount_money'] > 0 ? '-'.$v['discount_money']:''; //会员折扣
            $merchant_discount_money = $v['merchant_discount_money'] > 0 ? '-'.$v['merchant_discount_money']:''; //商家优惠
            $alipay_discount_money = ($v['alipay_discount_money'] > 0 && $v['order_flag'] == FLAG_NO ? $v['alipay_discount_money']:''); //支付宝优惠
            $wxpay_discount_money = ($v['wxpay_discount_money'] > 0 && $v['order_flag'] == FLAG_NO ? $v['wxpay_discount_money']:''); //支付宝优惠
            if ($v['order_flag'] == FLAG_NO){//收支
                $in_out = sprintf('%.2f',$v['order_paymoney']-$v['coupons_money']-$v['discount_money']-$v['merchant_discount_money']);
            }else{
                $in_out = '-'.sprintf('%.2f',$v['refund_money']);
            }
            $order_status = $v['order_status'] == ORDER_STATUS_NORMAL ? '已付款':$GLOBALS['ORDER_STATUS'][$v['order_status']];//订单状态
            $pay_channel = $GLOBALS['ORDER_PAY_CHANNEL'][$v['pay_channel']];//交易渠道
            $operator_name = empty($v['operator_number']) ? $v['operator_name'] : $v['operator_name'].'('.$v['operator_number'].')';//操作员
            $store_name = empty($v['branch_name'])? $v['store_name']: $v['store_name'].'('.$v['branch_name'].')';//所属门店
        
            $str .= $v['order_no']." 	,".$order_flag.",".$v['order_paymoney'].",".$discount_money.",".$merchant_discount_money;
            $str .= ",".$in_out.",".$alipay_discount_money.",".$wxpay_discount_money.",".$order_status.",".$pay_channel.",".$v['alipay_account'];
            $str .= ",".$v['create_time'].",".$v['pay_time'].",".$operator_name.",".$store_name.",".$v['store_number']." 	\n";
        
        }
        $filename = '交易明细'.$start_time_2.'-'.$end_time_2.'.csv'; //设置文件名
        $this->export_csv($filename,$str); //导出
    }
    
    /**
     * 导出Excel - 日汇总(总导)
     */
    public function actionExportDaysExcel(){
        $list = array();
        $merchant_id = Yii::app()->session['merchant_id'];//商户id
        //获取玩券商户名称
        $tradeC = new TradeC();
        $merchcantInfo_res = json_decode($tradeC->getMerchantInfo($merchant_id),true);
        $merchant_name = '';
        if ($merchcantInfo_res['status'] == ERROR_NONE){
            $merchant_name = $merchcantInfo_res['data']['wq_m_name'];
        }
        //获取get
        $get = array();
        if (isset($_GET['get']) && !empty($_GET['get'])){
            $get = $_GET['get'];
        }
        //搜索时间范围
        $start_time = '';
        $end_time = '';
        if (isset($get['Time']) && !empty($get['Time'])){
            $time = $get['Time'];
            $time_arr = explode('-', $time);
            $start_time = trim($time_arr[0]);
            $end_time = trim($time_arr[1]);
        }
        //门店搜索
        $store_id = array();
        if (isset($get['store_id']) && !empty($get['store_id'])){
            $store_id = explode(',', $get['store_id']);
        }
        //支付渠道搜索
        $pay_channel = array();
        if (isset($get['pay_channel']) && !empty($get['pay_channel'])){
            $pay_channel = explode(',', $get['pay_channel']);
        }

        //建导出表
        if (empty($start_time)){
            $start_time_1 = date('Y年m月d日',strtotime('-30 day'));
            $end_time_1 = date('Y年m月d日',strtotime('-1 day'));
        }else{
            $start_time_1 = date('Y年m月d日',strtotime($start_time));
            if (date('Y-m-d H:i:s',strtotime($end_time)) >= date('Y-m-d 00:00:00')){
                $end_time_1 = date('Y年m月d日',strtotime('-1 day'));
            }else{
                $end_time_1 = date('Y年m月d日',strtotime($end_time));
            }
        }
        $str = "\xEF\xBB\xBF"."商户名称: ".$merchant_name."\n";
        $str .= "起始日期：[".$start_time_1."]   终止日期：[".$end_time_1."]\n";
        $str .= ",,,,,,,,,支付宝,,,,,,微信,,,,,,银联,,,,,,储值,,,,,,现金,,,,,,无需支付,,,,,,集中收银\n";
        $str .= "门店名称,门店编号,时间,订单金额,订单笔数,优惠金额,退款金额,退款笔数,实收金额";
        $str .= ",支付宝订单金额,支付宝订单笔数,支付宝优惠金额,支付宝退款金额,支付宝退款笔数,支付宝实收金额";
        $str .= ",微信订单金额,微信订单笔数,微信优惠金额,微信退款金额,微信退款笔数,微信实收金额";
        $str .= ",银联订单金额,银联订单笔数,银联优惠金额,银联退款金额,银联退款笔数,银联实收金额";
        $str .= ",储值订单金额,储值订单笔数,储值优惠金额,储值退款金额,储值退款笔数,储值实收金额";
        $str .= ",现金订单金额,现金订单笔数,现金优惠金额,现金退款金额,现金退款笔数,现金实收金额";
        $str .= ",无需支付订单金额,无需支付订单笔数,无需支付优惠金额,无需支付退款金额,无需支付退款笔数,无需支付实收金额";
        $str .= ",集中收银订单金额,集中收银订单笔数,集中收银优惠金额,集中收银退款金额,集中收银退款笔数,集中收银实收金额\n";
        
        //获取门店信息集合
        $store_arr_info = $tradeC->getMerchantLowerStore($merchant_id, $store_id,1);
        $store_arr_id= array_keys($store_arr_info);
        for ($i=0;$i<count($store_arr_id);$i+=10){
            $store_id_arr = array_slice($store_arr_id, $i,10);
            //获取统计数据 (每次查询)
            $result = json_decode($tradeC->getExportDaysExcel($merchant_id,$start_time,$end_time,
                $store_id_arr, $pay_channel),true);
            if ($result['status'] == ERROR_NONE){
                $list = $result['data'];
                //拼接导出表
                foreach ($list as $k => $v){
                    if (is_array($v)){
                        foreach ($v as $k2 => $v2){
                            $store_name = empty($store_arr_info[$k]['branch_name']) ? $store_arr_info[$k]['name'] :$store_arr_info[$k]['name'].'('.$store_arr_info[$k]['branch_name'].')';
                            $str .= $store_name.",".$store_arr_info[$k]['number']." 	,".date('Y年m月d日',strtotime($k2));
                            $str .= ",".sprintf('%.2f',$v2['total_trade_money']).",".$v2['total_trade_num'].",-".sprintf('%.2f',$v2['total_discount_money']).",-".sprintf('%.2f',$v2['total_refund_money']).",".$v2['total_refund_num'].",".sprintf('%.2f',$v2['total_trade_actual_money']);
                            $str .= ",".sprintf('%.2f',$v2['alipay_trade_money']).",".$v2['alipay_trade_num'].",-".sprintf('%.2f',$v2['alipay_discount_money']).",-".sprintf('%.2f',$v2['alipay_refund_money']).",".$v2['alipay_refund_num'].",".sprintf('%.2f',$v2['alipay_trade_actual_money']);
                            $str .= ",".sprintf('%.2f',$v2['wechat_trade_money']).",".$v2['wechat_trade_num'].",-".sprintf('%.2f',$v2['wechat_discount_money']).",-".sprintf('%.2f',$v2['wechat_refund_money']).",".$v2['wechat_refund_num'].",".sprintf('%.2f',$v2['wechat_trade_actual_money']);
                            $str .= ",".sprintf('%.2f',$v2['unionpay_trade_money']).",".$v2['unionpay_trade_num'].",-".sprintf('%.2f',$v2['unionpay_discount_money']).",-".sprintf('%.2f',$v2['unionpay_refund_money']).",".$v2['unionpay_refund_num'].",".sprintf('%.2f',$v2['unionpay_trade_actual_money']);
                            $str .= ",".sprintf('%.2f',$v2['stored_trade_money']).",".$v2['stored_trade_num'].",-".sprintf('%.2f',$v2['stored_discount_money']).",-".sprintf('%.2f',$v2['stored_refund_money']).",".$v2['stored_refund_num'].",".sprintf('%.2f',$v2['stored_trade_actual_money']);
                            $str .= ",".sprintf('%.2f',$v2['cash_trade_money']).",".$v2['cash_trade_num'].",-".sprintf('%.2f',$v2['cash_discount_money']).",-".sprintf('%.2f',$v2['cash_refund_money']).",".$v2['cash_refund_num'].",".sprintf('%.2f',$v2['cash_trade_actual_money']);
                            $str .= ",".sprintf('%.2f',$v2['nopay_trade_money']).",".$v2['nopay_trade_num'].",-".sprintf('%.2f',$v2['nopay_discount_money']).",-".sprintf('%.2f',$v2['nopay_refund_money']).",".$v2['nopay_refund_num'].",".sprintf('%.2f',$v2['nopay_trade_actual_money']);
                            $str .= ",".sprintf('%.2f',$v2['group_trade_money']).",".$v2['group_trade_num'].",-".sprintf('%.2f',$v2['group_discount_money']).",-".sprintf('%.2f',$v2['group_refund_money']).",".$v2['group_refund_num'].",".sprintf('%.2f',$v2['group_trade_actual_money'])."\n";
                        }
                    }
                }
            }
        }
        $filename = '日汇总'.$start_time_1.'-'.$end_time_1.'.csv'; //设置文件名
        $this->export_csv($filename,$str); //导出
    }
    
    /**
     * 月汇总
     */
    public function actionStoreStatisticsMonth(){
        $list = array();
        $storeDropdownList = array();//门店下拉列表
        //商户id
        $merchant_id = Yii::app()->session['merchant_id'];
        
        //时间段搜索
        $start_time = '';
        $end_time = '';
        if (isset($_GET['Time']) && !empty($_GET['Time'])){
            $time = $_GET['Time'];
            $time_arr = explode('-', $time);
            $start_time = trim($time_arr[0]);
            $end_time = trim($time_arr[1]);
        }
        //门店搜索
        $store_id = array();
        if (isset($_GET['store_id']) && !empty($_GET['store_id'])){
            $store_id = explode(',', $_GET['store_id']);
        }
        //支付渠道搜索
        $pay_channel = array();
        if (isset($_GET['pay_channel']) && !empty($_GET['pay_channel'])){
            $pay_channel = explode(',', $_GET['pay_channel']);
        }
        //获取列表数据
        $tradeC = new TradeC();
        $result = json_decode($tradeC->getSStatisticsMonth($merchant_id,$start_time,$end_time,
            $store_id, $pay_channel),true);
        if ($result['status'] == ERROR_NONE){
            $list = $result['data'];
        }
        //获取门店下拉列表
        $storeDropdownList_res = json_decode($tradeC->getStoreDropdownList($merchant_id),true);
        if ($storeDropdownList_res['status'] == ERROR_NONE){
            $storeDropdownList = $storeDropdownList_res['data'];
        }
        $this->render('storeStatisticsMonth',array(
            'list'=>$list,
            'storeDropdownList'=>$storeDropdownList,
            'pages' => $tradeC->page
        ));
    }
    
    /**
     * 导出Excel - 月汇总
     */
    public function actionExportMonthExcel(){
        $list = array();
        
        $merchant_id = Yii::app()->session['merchant_id'];//商户id
        //获取玩券商户名称
        $tradeC = new TradeC();
        $merchcantInfo_res = json_decode($tradeC->getMerchantInfo($merchant_id),true);
        $merchant_name = '';
        if ($merchcantInfo_res['status'] == ERROR_NONE){
            $merchant_name = $merchcantInfo_res['data']['wq_m_name'];
        }
        //获取get
        $get = array();
        if (isset($_GET['get']) && !empty($_GET['get'])){
            $get = $_GET['get'];
        }
        //时间段搜索
        $start_time = '';
        $end_time = '';
        if (isset($get['today_time']) && !empty($get['today_time'])){
            $start_time = date('Y-m-01',strtotime($get['today_time']));
            $end_time = date('Y-m-t',strtotime($get['today_time']));
        }else{
            exit;
        }
        //门店搜索
        $store_id = array();
        if (isset($get['store_id']) && !empty($get['store_id'])){
            $store_id = explode(',', $get['store_id']);
        }
        //支付渠道搜索
        $pay_channel = array();
        if (isset($get['pay_channel']) && !empty($get['pay_channel'])){
            $pay_channel = explode(',', $get['pay_channel']);
        }
        
        //获取列表数据
        $result = json_decode($tradeC->getTradeList($merchant_id, '', $start_time, $end_time, $store_id, '', '', '', $pay_channel,1),true);
        if ($result['status'] == ERROR_NONE && !empty($result['data']['list'])){
            $list = $result['data']['list'];
        }
        //建导出表
        $start_time_1 = date('Y年m月d日 00:00:00',strtotime($start_time));
        $end_time_1 = date('Y年m月d日 23:59:59',strtotime($end_time));
        $start_time_2 = date('Y年m月d日',strtotime($start_time));
        $end_time_2 = date('Y年m月d日',strtotime($end_time));
        
        $str = "\xEF\xBB\xBF"."商户名称: ".$merchant_name."\n";
        $str .= "起始日期：[".$start_time_1."]   终止日期：[".$end_time_1."]\n\n";
        $str .= "订单号,交易类型,订单金额,会员折扣,商家优惠,收入/支出,支付宝优惠,微信优惠,订单状态,交易渠道,支付账号,下单时间,交易时间,操作员,所属门店,门店编号\n";
        foreach ($list as $v){
            $order_flag = ($v['order_flag'] == FLAG_NO ? '收款':'退款'); //交易类型
            $discount_money = $v['discount_money'] > 0 ? '-'.$v['discount_money']:''; //会员折扣
            $merchant_discount_money = $v['merchant_discount_money'] > 0 ? '-'.$v['merchant_discount_money']:''; //商家优惠
            $alipay_discount_money = ($v['alipay_discount_money'] > 0 && $v['order_flag'] == FLAG_NO ? $v['alipay_discount_money']:''); //支付宝优惠
            $wxpay_discount_money = ($v['wxpay_discount_money'] > 0 && $v['order_flag'] == FLAG_NO ? $v['wxpay_discount_money']:''); //支付宝优惠
            if ($v['order_flag'] == FLAG_NO){//收支
                $in_out = sprintf('%.2f',$v['order_paymoney']-$v['coupons_money']-$v['discount_money']-$v['merchant_discount_money']);
            }else{
                $in_out = '-'.sprintf('%.2f',$v['refund_money']);
            }
            $order_status = $v['order_status'] == ORDER_STATUS_NORMAL ? '已付款':$GLOBALS['ORDER_STATUS'][$v['order_status']];//订单状态
            $pay_channel = $GLOBALS['ORDER_PAY_CHANNEL'][$v['pay_channel']];//交易渠道
            $operator_name = empty($v['operator_number']) ? $v['operator_name'] : $v['operator_name'].'('.$v['operator_number'].')';//操作员
            $store_name = empty($v['branch_name'])? $v['store_name']: $v['store_name'].'('.$v['branch_name'].')';//所属门店
        
            $str .= $v['order_no']." 	,".$order_flag.",".$v['order_paymoney'].",".$discount_money.",".$merchant_discount_money;
            $str .= ",".$in_out.",".$alipay_discount_money.",".$wxpay_discount_money.",".$order_status.",".$pay_channel.",".$v['alipay_account'];
            $str .= ",".$v['create_time'].",".$v['pay_time'].",".$operator_name.",".$store_name.",".$v['store_number']." 	\n";
        
        }
        $filename = '交易明细'.$start_time_2.'-'.$end_time_2.'.csv'; //设置文件名
        $this->export_csv($filename,$str); //导出
    }
    
    /**
     * 导出Excel - 月汇总(总导) 2016.07.28
     */
    public function actionExportMonthsExcel(){
        $list = array();
        $merchant_id = Yii::app()->session['merchant_id'];//商户id
    
        //获取玩券商户名称
        $tradeC = new TradeC();
        $merchcantInfo_res = json_decode($tradeC->getMerchantInfo($merchant_id),true);
        $merchant_name = '';
        if ($merchcantInfo_res['status'] == ERROR_NONE){
            $merchant_name = $merchcantInfo_res['data']['wq_m_name'];
        }
        //获取get
        $get = array();
        if (isset($_GET['get']) && !empty($_GET['get'])){
            $get = $_GET['get'];
        }
        //搜索时间范围
        $start_time = '';
        $end_time = '';
        if (isset($get['Time']) && !empty($get['Time'])){
            $time = $get['Time'];
            $time_arr = explode('-', $time);
            $start_time = trim($time_arr[0]);
            $end_time = trim($time_arr[1]);
        }
        //门店搜索
        $store_id = array();
        if (isset($get['store_id']) && !empty($get['store_id'])){
            $store_id = explode(',', $get['store_id']);
        }
        //支付渠道搜索
        $pay_channel = array();
        if (isset($get['pay_channel']) && !empty($get['pay_channel'])){
            $pay_channel = explode(',', $get['pay_channel']);
        }
    
        //获取列表数据
        $result = json_decode($tradeC->getExportMonthsExcel($merchant_id,$start_time,$end_time,
            $store_id, $pay_channel),true);
        if ($result['status'] == ERROR_NONE){
            $list = $result['data'];
        }
        //建导出表
        if (empty($start_time)){
            $start_time_1 = date('Y年m月',strtotime('-12 month'));
            $end_time_1 = date('Y年m月',strtotime('-1 month'));
        }else{
            $start_time = str_replace('/', '-', $start_time);
            $end_time = str_replace('/', '-', $end_time);
            $start_time_1 = date('Y年m月',strtotime($start_time));
            if (date('Y-m',strtotime($end_time)) >= date('Y-m')){
                $end_time_1 = date('Y年m月',strtotime('-1 month'));
            }else{
                $end_time_1 = date('Y年m月',strtotime($end_time));
            }
        }
        $str = "\xEF\xBB\xBF"."商户名称: ".$merchant_name."\n";
        $str .= "起始日期：[".$start_time_1."]   终止日期：[".$end_time_1."]\n";
        $str .= ",,,,,,,,,支付宝,,,,,,微信,,,,,,银联,,,,,,储值,,,,,,现金,,,,,,无需支付,,,,,,集中收银\n";
        $str .= "门店名称,门店编号,时间,订单金额,订单笔数,优惠金额,退款金额,退款笔数,实收金额";
        $str .= ",支付宝订单金额,支付宝订单笔数,支付宝优惠金额,支付宝退款金额,支付宝退款笔数,支付宝实收金额";
        $str .= ",微信订单金额,微信订单笔数,微信优惠金额,微信退款金额,微信退款笔数,微信实收金额";
        $str .= ",银联订单金额,银联订单笔数,银联优惠金额,银联退款金额,银联退款笔数,银联实收金额";
        $str .= ",储值订单金额,储值订单笔数,储值优惠金额,储值退款金额,储值退款笔数,储值实收金额";
        $str .= ",现金订单金额,现金订单笔数,现金优惠金额,现金退款金额,现金退款笔数,现金实收金额";
        $str .= ",无需支付订单金额,无需支付订单笔数,无需支付优惠金额,无需支付退款金额,无需支付退款笔数,无需支付实收金额";
        $str .= ",集中收银订单金额,集中收银订单笔数,集中收银优惠金额,集中收银退款金额,集中收银退款笔数,集中收银实收金额\n";
        foreach ($list as $k => $v){
            if (is_array($v)){
                foreach ($v as $k2 => $v2){
                    $store_name = empty($v2['branch_name']) ? $v2['name'] :$v2['name'].'('.$v2['branch_name'].')';
                    $str .= $store_name.",".$v2['number']." 	,".date('Y年m月',strtotime($k2));
                    $str .= ",".sprintf('%.2f',$v2['total_trade_money']).",".$v2['total_trade_num'].",-".sprintf('%.2f',$v2['total_discount_money']).",-".sprintf('%.2f',$v2['total_refund_money']).",".$v2['total_refund_num'].",".sprintf('%.2f',$v2['total_trade_actual_money']);
                    $str .= ",".sprintf('%.2f',$v2['alipay_trade_money']).",".$v2['alipay_trade_num'].",-".sprintf('%.2f',$v2['alipay_discount_money']).",-".sprintf('%.2f',$v2['alipay_refund_money']).",".$v2['alipay_refund_num'].",".sprintf('%.2f',$v2['alipay_trade_actual_money']);
                    $str .= ",".sprintf('%.2f',$v2['wechat_trade_money']).",".$v2['wechat_trade_num'].",-".sprintf('%.2f',$v2['wechat_discount_money']).",-".sprintf('%.2f',$v2['wechat_refund_money']).",".$v2['wechat_refund_num'].",".sprintf('%.2f',$v2['wechat_trade_actual_money']);
                    $str .= ",".sprintf('%.2f',$v2['unionpay_trade_money']).",".$v2['unionpay_trade_num'].",-".sprintf('%.2f',$v2['unionpay_discount_money']).",-".sprintf('%.2f',$v2['unionpay_refund_money']).",".$v2['unionpay_refund_num'].",".sprintf('%.2f',$v2['unionpay_trade_actual_money']);
                    $str .= ",".sprintf('%.2f',$v2['stored_trade_money']).",".$v2['stored_trade_num'].",-".sprintf('%.2f',$v2['stored_discount_money']).",-".sprintf('%.2f',$v2['stored_refund_money']).",".$v2['stored_refund_num'].",".sprintf('%.2f',$v2['stored_trade_actual_money']);
                    $str .= ",".sprintf('%.2f',$v2['cash_trade_money']).",".$v2['cash_trade_num'].",-".sprintf('%.2f',$v2['cash_discount_money']).",-".sprintf('%.2f',$v2['cash_refund_money']).",".$v2['cash_refund_num'].",".sprintf('%.2f',$v2['cash_trade_actual_money']);
                    $str .= ",".sprintf('%.2f',$v2['nopay_trade_money']).",".$v2['nopay_trade_num'].",-".sprintf('%.2f',$v2['nopay_discount_money']).",-".sprintf('%.2f',$v2['nopay_refund_money']).",".$v2['nopay_refund_num'].",".sprintf('%.2f',$v2['nopay_trade_actual_money']);
                    $str .= ",".sprintf('%.2f',$v2['group_trade_money']).",".$v2['group_trade_num'].",-".sprintf('%.2f',$v2['group_discount_money']).",-".sprintf('%.2f',$v2['group_refund_money']).",".$v2['group_refund_num'].",".sprintf('%.2f',$v2['group_trade_actual_money'])."\n";
                }
            }
        }
        $filename = '月汇总'.$start_time_1.'-'.$end_time_1.'.csv'; //设置文件名
        $this->export_csv($filename,$str); //导出
    }
    
    /**
     * 门店汇总
     */
    public function actionStoreStatistics(){
        $list = array();
        
        //商户id
        $merchant_id = Yii::app()->session['merchant_id'];
        //时间段搜索
        $start_time = ''; 
        $end_time = '';
        if (isset($_GET['Time']) && !empty($_GET['Time'])){
            $time = $_GET['Time'];
            $time_arr = explode('-', $time);
            $start_time = trim($time_arr[0]);
            $end_time = trim($time_arr[1]);
        }
        //门店搜索
        $store_id = array();//门店id数组
        if (isset($_GET['store_id']) && !empty($_GET['store_id'])){
            $store_id = explode(',', $_GET['store_id']);
        }
        //支付渠道搜索
        $pay_channel = array(); //支付渠道 
        if (isset($_GET['pay_channel']) && !empty($_GET['pay_channel'])){
            $pay_channel = explode(',', $_GET['pay_channel']);
        }
        //排序字段
        $orderby_key = '';
        if (isset($_GET['orderby_key']) && !empty($_GET['orderby_key'])){
            $orderby_key = $_GET['orderby_key'];
        }
        switch ($orderby_key){
            case 1:
                $orderby_key = 'number';
                break;
            case 2:
                $orderby_key = 'total_trade_money';
                break;
            case 3:
                $orderby_key = 'total_discount_money';
                break;
            case 4:
                $orderby_key = 'total_refund_money';
                break;
            case 5:
                $orderby_key = 'total_trade_actual_money';
                break;
            default:
                $orderby_key = 'number';
                break;   
        }
        //排序方式: 升序/降序
        $orderby_value = ''; //交易类型（收款，退款）
        if (isset($_GET['orderby_value']) && !empty($_GET['orderby_value'])){
            $orderby_value = $_GET['orderby_value'];
        }

        //获取列表数据
        $tradeC = new TradeC();
        $result = json_decode($tradeC->getSStatistics($merchant_id, $start_time, $end_time,
            $store_id, $pay_channel, $orderby_key, $orderby_value),true);
        if ($result['status'] == ERROR_NONE){
            $list = $result['data'];
        }
        //获取门店下拉列表
        $storeDropdownList_res = json_decode($tradeC->getStoreDropdownList($merchant_id),true);
        if ($storeDropdownList_res['status'] == ERROR_NONE){
            $storeDropdownList = $storeDropdownList_res['data'];
        }
        $this->render('storeStatistics',array(
            'list'=>$list,
            'pages' => $tradeC->page,
            'storeDropdownList'=>$storeDropdownList
        ));
    }
    
    /**
     * 导出Excel - 门店汇总
     */
    public function actionExportSStatisticsExcel(){
        $list = array();
        $merchant_id = Yii::app()->session['merchant_id'];//商户id
        //获取玩券商户名称
        $tradeC = new TradeC();
        $merchcantInfo_res = json_decode($tradeC->getMerchantInfo($merchant_id),true);
        $merchant_name = '';
        if ($merchcantInfo_res['status'] == ERROR_NONE){
            $merchant_name = $merchcantInfo_res['data']['wq_m_name'];
        }
        $get = array();
        if (isset($_GET['get']) && !empty($_GET['get'])){
            $get = $_GET['get'];
        }
        //时间段搜索
        $start_time = '';
        $end_time = '';
        if (isset($get['Time']) && !empty($get['Time'])){
            $time = $get['Time'];
            $time_arr = explode('-', $time);
            $start_time = trim($time_arr[0]);
            $end_time = trim($time_arr[1]);
        }
        //门店搜索
        $store_id = array();//门店id数组
        if (isset($get['store_id']) && !empty($get['store_id'])){
            $store_id = explode(',', $get['store_id']);
        }
        //支付渠道搜索
        $pay_channel = array(); //支付渠道
        if (isset($get['pay_channel']) && !empty($get['pay_channel'])){
            $pay_channel = explode(',', $get['pay_channel']);
        }
        //排序字段
        $orderby_key = '';
        if (isset($get['orderby_key']) && !empty($get['orderby_key'])){
            $orderby_key = $get['orderby_key'];
        }
        switch ($orderby_key){
            case 1:
                $orderby_key = 'number';
                break;
            case 2:
                $orderby_key = 'total_trade_money';
                break;
            case 3:
                $orderby_key = 'total_discount_money';
                break;
            case 4:
                $orderby_key = 'total_refund_money';
                break;
            case 5:
                $orderby_key = 'total_trade_actual_money';
                break;
            default:
                $orderby_key = 'number';
                break;
        }
        //排序方式: 升序/降序
        $orderby_value = ''; //交易类型（收款，退款）
        if (isset($get['orderby_value']) && !empty($get['orderby_value'])){
            $orderby_value = $get['orderby_value'];
        }
        
        //获取列表数据
        $result = json_decode($tradeC->getSStatistics($merchant_id, $start_time, $end_time,
            $store_id, $pay_channel, $orderby_key, $orderby_value, 1),true);
        if ($result['status'] == ERROR_NONE){
            $list = $result['data'];
        }
        if (empty($start_time)){
            $start_time_1 = date('Y年m月d日 00:00:00');
            $end_time_1 = date('Y年m月d日 H:i:s');
            $start_time_2 = date('Y年m月d日');
            $end_time_2 = date('Y年m月d日');
        }else{
            $start_time_1 = date('Y年m月d日 00:00:00',strtotime($start_time));
            $start_time_2 = date('Y年m月d日',strtotime($start_time));
            $end_time_2 = date('Y年m月d日',strtotime($end_time));
            if (date('Y-m-d',strtotime($end_time)) == date('Y-m-d')){
                $end_time_1 = date('Y年m月d日 H:i:s');
            }else{
                $end_time_1 = date('Y年m月d日 23:59:59',strtotime($end_time));
        
            }
        }
        //建导出表
        $str = "\xEF\xBB\xBF"."商户名称: ".$merchant_name."\n";
        $str .= "起始日期：[".$start_time_1."]   终止日期：[".$end_time_1."]\n";
        $str .= ",,,,,,,,支付宝,,,,,,微信,,,,,,银联,,,,,,储值,,,,,,现金,,,,,,无需支付,,,,,,集中收银\n";
        $str .= "门店编号,门店名称,订单金额,订单笔数,优惠金额,退款金额,退款笔数,实收金额";
        $str .= ",支付宝订单金额,支付宝订单笔数,支付宝优惠金额,支付宝退款金额,支付宝退款笔数,支付宝实收金额";
        $str .= ",微信订单金额,微信订单笔数,微信优惠金额,微信退款金额,微信退款笔数,微信实收金额";
        $str .= ",银联订单金额,银联订单笔数,银联优惠金额,银联退款金额,银联退款笔数,银联实收金额";
        $str .= ",储值订单金额,储值订单笔数,储值优惠金额,储值退款金额,储值退款笔数,储值实收金额";
        $str .= ",现金订单金额,现金订单笔数,现金优惠金额,现金退款金额,现金退款笔数,现金实收金额";
        $str .= ",无需支付订单金额,无需支付订单笔数,无需支付优惠金额,无需支付退款金额,无需支付退款笔数,无需支付实收金额";
        $str .= ",集中收银订单金额,集中收银订单笔数,集中收银优惠金额,集中收银退款金额,集中收银退款笔数,集中收银实收金额\n";
        foreach ($list as $v){
            $store_name = empty($v['branch_name']) ? $v['name'] :$v['name'].'('.$v['branch_name'].')';
            $str .= $v['number']." 	,".$store_name;
            $str .= ",".sprintf('%.2f',$v['total_trade_money']).",".$v['total_trade_num'].",-".sprintf('%.2f',$v['total_discount_money']).",-".sprintf('%.2f',$v['total_refund_money']).",".$v['total_refund_num'].",".sprintf('%.2f',$v['total_trade_actual_money']);
            $str .= ",".sprintf('%.2f',$v['alipay_trade_money']).",".$v['alipay_trade_num'].",-".sprintf('%.2f',$v['alipay_discount_money']).",-".sprintf('%.2f',$v['alipay_refund_money']).",".$v['alipay_refund_num'].",".sprintf('%.2f',$v['alipay_trade_actual_money']);
            $str .= ",".sprintf('%.2f',$v['wechat_trade_money']).",".$v['wechat_trade_num'].",-".sprintf('%.2f',$v['wechat_discount_money']).",-".sprintf('%.2f',$v['wechat_refund_money']).",".$v['wechat_refund_num'].",".sprintf('%.2f',$v['wechat_trade_actual_money']);
            $str .= ",".sprintf('%.2f',$v['unionpay_trade_money']).",".$v['unionpay_trade_num'].",-".sprintf('%.2f',$v['unionpay_discount_money']).",-".sprintf('%.2f',$v['unionpay_refund_money']).",".$v['unionpay_refund_num'].",".sprintf('%.2f',$v['unionpay_trade_actual_money']);
            $str .= ",".sprintf('%.2f',$v['stored_trade_money']).",".$v['stored_trade_num'].",-".sprintf('%.2f',$v['stored_discount_money']).",-".sprintf('%.2f',$v['stored_refund_money']).",".$v['stored_refund_num'].",".sprintf('%.2f',$v['stored_trade_actual_money']);
            $str .= ",".sprintf('%.2f',$v['cash_trade_money']).",".$v['cash_trade_num'].",-".sprintf('%.2f',$v['cash_discount_money']).",-".sprintf('%.2f',$v['cash_refund_money']).",".$v['cash_refund_num'].",".sprintf('%.2f',$v['cash_trade_actual_money']);
            $str .= ",".sprintf('%.2f',$v['nopay_trade_money']).",".$v['nopay_trade_num'].",-".sprintf('%.2f',$v['nopay_discount_money']).",-".sprintf('%.2f',$v['nopay_refund_money']).",".$v['nopay_refund_num'].",".sprintf('%.2f',$v['nopay_trade_actual_money']);
            $str .= ",".sprintf('%.2f',$v['group_trade_money']).",".$v['group_trade_num'].",-".sprintf('%.2f',$v['group_discount_money']).",-".sprintf('%.2f',$v['group_refund_money']).",".$v['group_refund_num'].",".sprintf('%.2f',$v['group_trade_actual_money'])."\n";
        }
        $filename = '门店交易'.$start_time_2.'-'.$end_time_2.'.csv'; //设置文件名
        $this->export_csv($filename,$str); //导出
    }
}