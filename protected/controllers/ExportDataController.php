<?php

class ExportDataController extends Controller{
    /*
     * 导出代理商excel
     */
    public function actionExportExcelForAgent() {
        include_once 'PHPExcel.php';
        include_once 'PHPExcel/Reader/Excel2007.php';
        include_once 'PHPExcel/Reader/Excel5.php';
        include_once 'PHPExcel/IOFactory.php';
         
        $sub_agent = Agent::model()->findAll('ppid is not null and ppid != "" and flag = :flag',array(':flag' => FLAG_NO));
    
        $slave_agent = Agent::model()->findAll('pid is not null and pid != "" and (ppid is null or ppid = "") and flag = :flag',array(':flag' => FLAG_NO));
         
        $slave_id_arr = array();
        foreach ($slave_agent as $k => $v) {
            $slave_id_arr[$v['id']] = array($v['id']);
        }
        foreach ($sub_agent as $k => $v) {
            if (isset($slave_id_arr[$v['pid']])) {
                $slave_id_arr[$v['pid']][] = $v['id'];
            }
        }
    
        $master_agent = Agent::model()->findAll('(pid is null or pid = "") and flag = :flag',array(':flag' => FLAG_NO));
        $master_id_arr = array();
        foreach ($master_agent as $k => $v) {
            $master_id_arr[$v['id']] = array($v['id']);
        }
    
        foreach ($slave_agent as $k => $v) {
            if (isset($master_id_arr[$v['pid']])) {
                $master_id = $v['pid'];
                $tmp = $slave_id_arr[$v['id']];
                array_push($tmp, $master_id);
                $master_id_arr[$v['pid']] = array_merge($master_id_arr[$v['pid']], $tmp);
            }
        }
         
        $data = array();
         
        foreach ($master_id_arr as $k => $v) {
            $master_id = $k;
            $agent_arr = $v;
    
            $agent = Agent::model()->findByPk($master_id);
            if (empty($agent)) {
                continue;
            }
            $data[$master_id]['name'] = $agent['name'];
            $data[$master_id]['merchant'] = array();
    
            $cmd = Yii::app()->db->createCommand();
            $cmd->andWhere('flag = :flag');
            $cmd->params[':flag'] = FLAG_NO;
            $cmd->andWhere(array('IN', 'agent_id', $agent_arr));
            	
            $select = 'id, wq_m_name, create_time, wq_m_industry';
            $cmd->select = $select;
    
            $cmd->from = 'wq_merchant';
    
            $list = $cmd->queryAll();
            foreach ($list as $k1 => $v1) {
                $merchant_id = $v1['id'];
                //统计门店数
                $store_count = Store::model()->count('merchant_id = :merchant_id and flag = :flag',
                    array(':merchant_id' => $merchant_id, ':flag' => FLAG_NO));
                $industry = $v1['wq_m_industry'];
                if (!empty($industry)) {
                    $arr = explode(',', $industry);
                    $category = $GLOBALS['WECHAT_MERCHANT_JYLM'][$arr[0]]['text'].$GLOBALS['WECHAT_MERCHANT_JYLM'][$arr[0]]['sub'][$arr[1]]['text'].$GLOBALS['WECHAT_MERCHANT_JYLM'][$arr[0]]['sub'][$arr[1]]['sub'][$arr[2]]['text'];
                }
                 
                $alipay_trade_count = 0;
                $alipay_trade_money = 0;
                $wxpay_trade_count = 0;
                $wxpay_trade_money = 0;
                //统计订单明细
                $cmd = Yii::app()->db->createCommand();
                //查询条件
                $cmd->andWhere('flag = :flag');
                $cmd->params[':flag'] = FLAG_NO;
                $cmd->andWhere('pay_status = :custom_pay_status');
                $cmd->params[':custom_pay_status'] = ORDER_STATUS_PAID; //已支付订单
                $cmd->andWhere('order_type = :custom_order_type');
                $cmd->params[':custom_order_type'] = ORDER_TYPE_CASHIER; //收银订单
                $cmd->andWhere('merchant_id = :merchant_id');
                $cmd->params[':merchant_id'] = $merchant_id;
                $cmd->andWhere('pay_time >= :start_time');
                $cmd->params[':start_time'] = '2016-05-01 00:00:00';
                //查询计算
                $select = 'pay_channel';
                $select .= ', SUM(order_paymoney) AS order_sum';
                $select .= ', SUM(coupons_money) AS coupons_sum';
                $select .= ', SUM(discount_money) AS discount_sum';
                $select .= ', SUM(merchant_discount_money) AS m_discount_sum';
                $select .= ', COUNT(*) AS trade_sum';
                $cmd->select = $select;
                //分组
                $cmd->group = 'pay_channel';
                //指定查询表
                $cmd->from = 'wq_order';
                 
                $list = $cmd->queryAll();
                 
                foreach ($list as $k2 => $trade) {
                    $pay_channel = $trade['pay_channel'];
                    if ($pay_channel != ORDER_PAY_CHANNEL_ALIPAY_SM &&
                        $pay_channel != ORDER_PAY_CHANNEL_ALIPAY_TM &&
                        $pay_channel != ORDER_PAY_CHANNEL_WXPAY_SM &&
                        $pay_channel != ORDER_PAY_CHANNEL_WXPAY_TM) {
                            continue;
                        }
    
                        $receipt_money = $trade['coupons_sum'] + $trade['discount_sum'] + $trade['m_discount_sum'];
    
                        if ($pay_channel == ORDER_PAY_CHANNEL_ALIPAY_SM || $pay_channel == ORDER_PAY_CHANNEL_ALIPAY_TM) {
                            $alipay_trade_count += $trade['trade_sum'];
                            $alipay_trade_money += $trade['order_sum'] - $receipt_money;
                        }
                        if ($pay_channel == ORDER_PAY_CHANNEL_WXPAY_SM || $pay_channel == ORDER_PAY_CHANNEL_WXPAY_TM) {
                            $wxpay_trade_count += $trade['trade_sum'];
                            $wxpay_trade_money += $trade['order_sum'] - $receipt_money;
                        }
                }
                 
                //退款查询
                $cmd = Yii::app()->db->createCommand();
                //查询条件
                $cmd->andWhere('flag = :flag');
                $cmd->params[':flag'] = FLAG_NO;
                $cmd->andWhere('type = :type');
                $cmd->params[':type'] = REFUND_TYPE_REFUND;
                $cmd->andWhere('status != :status');
                $cmd->params[':status'] = REFUND_STATUS_FAIL;
                $cmd->andWhere('merchant_id = :merchant_id');
                $cmd->params[':merchant_id'] = $merchant_id;
                $cmd->andWhere('refund_time >= :start_time');
                $cmd->params[':start_time'] = '2016-05-01 00:00:00';
                //查询计算
                $select = 'refund_channel';
                $select .= ', SUM(refund_money) AS refund_sum';
                $select .= ', COUNT(*) AS record_sum';
                $cmd->select = $select;
                //分组
                $cmd->group = 'refund_channel';
                //指定查询表
                $cmd->from = 'wq_refund_record';
                 
                $list = $cmd->queryAll();
                foreach ($list as $k2 => $trade) {
                    $pay_channel = $trade['refund_channel'];
                    if ($pay_channel != ORDER_PAY_CHANNEL_ALIPAY_SM &&
                        $pay_channel != ORDER_PAY_CHANNEL_ALIPAY_TM &&
                        $pay_channel != ORDER_PAY_CHANNEL_WXPAY_SM &&
                        $pay_channel != ORDER_PAY_CHANNEL_WXPAY_TM) {
                            continue;
                        }
                         
                        $refund_money = $trade['refund_sum'];
    
                        if ($pay_channel == ORDER_PAY_CHANNEL_ALIPAY_SM || $pay_channel == ORDER_PAY_CHANNEL_ALIPAY_TM) {
                            $alipay_trade_money -= $refund_money;
                        }
                        if ($pay_channel == ORDER_PAY_CHANNEL_WXPAY_SM || $pay_channel == ORDER_PAY_CHANNEL_WXPAY_TM) {
                            $wxpay_trade_money -= $refund_money;
                        }
                }
    
                $info = array(
                    'name' => $v1['wq_m_name'],
                    'store_count' => $store_count,
                    'open_time' => $v1['create_time'],
                    'industry' => $category,
                    'alipay_trade_money' => ''.$alipay_trade_money,
                    'alipay_trade_count' => ''.$alipay_trade_count,
                    'wxpay_trade_money' => ''.$wxpay_trade_money,
                    'wxpay_trade_count' => ''.$wxpay_trade_count,
                );
                $data[$master_id]['merchant'][] = $info;
            }
        }
         
        $objPHPExcel = new PHPExcel();
        $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('A1','代理商')
        ->setCellValue('B1','商户')
        ->setCellValue('C1','门店数')
        ->setCellValue('D1','开通时间')
        ->setCellValue('E1','行业')
        ->setCellValue('F1','支付宝金额')
        ->setCellValue('G1','支付宝笔数')
        ->setCellValue('H1','微信金额')
        ->setCellValue('I1','微信笔数');
         
         
        //设置列宽
        $objActSheet = $objPHPExcel->getActiveSheet();
        $objActSheet->getColumnDimension('A')->setWidth(20);
        $objActSheet->getColumnDimension('B')->setWidth(20);
        $objActSheet->getColumnDimension('C')->setWidth(20);
        $objActSheet->getColumnDimension('D')->setWidth(20);
        $objActSheet->getColumnDimension('E')->setWidth(20);
        $objActSheet->getColumnDimension('F')->setWidth(20);
        $objActSheet->getColumnDimension('G')->setWidth(20);
        $objActSheet->getColumnDimension('H')->setWidth(20);
        $objActSheet->getColumnDimension('I')->setWidth(20);
         
        //设置sheet名称
        $objActSheet -> setTitle('总代理商商户统计表');
         
        $i=2;
        foreach ($data as $k => $v) {
            $name = $v['name'];
            $info = $v['merchant'];
            if (empty($info)) {
                continue;
            }
    
            foreach ($info as $k1 => $v1) {
                $merchant_name = $v1['name'];
                $store_count = $v1['store_count'];
                $open_time = $v1['open_time'];
                $industry = $v1['industry'];
                 
                $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A'.$i,$name)
                ->setCellValue('B'.$i,$merchant_name)
                ->setCellValue('C'.$i,$store_count)
                ->setCellValue('D'.$i,$open_time)
                ->setCellValue('E'.$i,$industry)
                ->setCellValue('F'.$i,$alipay_trade_money)
                ->setCellValue('G'.$i,$alipay_trade_count)
                ->setCellValue('H'.$i,$wxpay_trade_money)
                ->setCellValue('I'.$i,$wxpay_trade_count);
                 
                $i ++;
            }
        }
         
        $filename = "商户统计".date("YmdHis");//定义文件名
        $objWriter = new PHPExcel_Writer_Excel5($objPHPExcel);
        $this->outPut($filename);
        $objWriter->save("php://output");
    }

    //导出5月20至6月20商户微信交易量
    public function actionExport1() {
        set_time_limit(0);
        ini_set('memory_limit', '5000M');
        include_once 'PHPExcel.php';
        include_once 'PHPExcel/Reader/Excel2007.php';
        include_once 'PHPExcel/Reader/Excel5.php';
        include_once 'PHPExcel/IOFactory.php';
        $list = Merchant::model()->findAll('flag = :flag', array(':flag' => 1));
        $data = array();
        foreach ($list as $merchant) {
            $name = $merchant['wq_m_name'];
            $industry = $merchant['wq_m_industry'];
            if ($merchant['wxpay_merchant_type'] == 2) {
                $mchid = $merchant['t_wx_mchid'];
            }else {
                $mchid = $merchant['wechat_mchid'];
            }
            $arr = explode(',', $industry);
            $ins1 = $arr[0];
            $ins2 = $arr[1];
            $ins3 = $arr[2];
            $text1 = $GLOBALS['WECHAT_MERCHANT_JYLM'][$ins1]['text'];
            $text2 = $GLOBALS['WECHAT_MERCHANT_JYLM'][$ins1]['sub'][$ins2]['text'];
            $text3 = $GLOBALS['WECHAT_MERCHANT_JYLM'][$ins1]['sub'][$ins2]['sub'][$ins3]['text'];
            $industry = $text1.' '.$text2.' '.$text3;
            $address = $merchant['wq_m_address'];
             
            $store_count = Store::model()->count('merchant_id = :merchant_id and flag = :flag',
                array(':merchant_id' => $merchant['id'], ':flag' => 1));
            $start_time = '2016-05-20 00:00:00';
            $end_time = '2016-06-20 23:59:59';
            $channel_qrcode = 9;
            $channel_barcode = 10;
            $criteria = new CDbCriteria();
            $criteria->addCondition('merchant_id = :merchant_id');
            $criteria->params[':merchant_id'] = $merchant['id'];
            $criteria->addCondition('pay_status = :pay_status');
            $criteria->params[':pay_status'] = 2;
            $criteria->addCondition('flag = :flag');
            $criteria->params[':flag'] = 1;
            $criteria->addCondition('pay_time >= :start_time');
            $criteria->params[':start_time'] = $start_time;
            $criteria->addCondition('pay_time <= :end_time');
            $criteria->params[':end_time'] = $end_time;
            $criteria->addCondition('pay_channel = :pay_channel');
             
            $criteria->params[':pay_channel'] = 9;
            $qr_count = Order::model()->count($criteria);
             
            $criteria->params[':pay_channel'] = 10;
            $bar_count = Order::model()->count($criteria);
             
            $total_count = $qr_count + $bar_count;
             
            $data[] = array(
                'name' => $name,
                'industry' => $industry,
                'address' => $address,
                'stores' => $store_count,
                'mchid' => $mchid,
                'total' => $total_count,
                'qr' => $qr_count,
                'bar' => $bar_count
            );
        }
         
        $objPHPExcel = new PHPExcel();
        $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('A1','商户名称')
        ->setCellValue('B1','行业')
        ->setCellValue('C1','区域')
        ->setCellValue('D1','门店数')
        ->setCellValue('E1','微信总交易笔数')
        ->setCellValue('F1','微信扫码支付笔数')
        ->setCellValue('G1','微信条码支付笔数')
        ->setCellValue('H1','微信商户号');
    
        //设置列宽
        $objActSheet = $objPHPExcel->getActiveSheet();
        $objActSheet->getColumnDimension('A')->setWidth(20);
        $objActSheet->getColumnDimension('B')->setWidth(20);
        $objActSheet->getColumnDimension('C')->setWidth(20);
        $objActSheet->getColumnDimension('D')->setWidth(20);
        $objActSheet->getColumnDimension('E')->setWidth(20);
        $objActSheet->getColumnDimension('F')->setWidth(20);
        $objActSheet->getColumnDimension('G')->setWidth(20);
        $objActSheet->getColumnDimension('H')->setWidth(20);
    
        //设置sheet名称
        $objActSheet -> setTitle('统计表');
    
        $i=2;
        foreach ($data as $obj) {
            $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A'.$i,$obj['name'])
            ->setCellValue('B'.$i,$obj['industry'])
            ->setCellValue('C'.$i,$obj['address'])
            ->setCellValue('D'.$i,$obj['stores'])
            ->setCellValue('E'.$i,$obj['total'])
            ->setCellValue('F'.$i,$obj['qr'])
            ->setCellValue('G'.$i,$obj['bar'])
            ->setCellValue('H'.$i,$obj['mchid']);
             
            $i ++;
        }
    
        $filename = "商户统计".date("YmdHis");//定义文件名
        $objWriter = new PHPExcel_Writer_Excel5($objPHPExcel);
        $this->outPut($filename);
        $objWriter->save("php://output");
    }
    
    //商户门店表
    public function actionExport2() {
        set_time_limit(0);
        ini_set('memory_limit', '5000M');
        include_once 'PHPExcel.php';
        include_once 'PHPExcel/Reader/Excel2007.php';
        include_once 'PHPExcel/Reader/Excel5.php';
        include_once 'PHPExcel/IOFactory.php';
        $list = Merchant::model()->findAll('flag = :flag', array(':flag' => 1));
        $data = array();
        $isv = '2088701036418655';
        foreach ($list as $merchant) {
            $name = $merchant['wq_m_name'];
            $agent = Agent::model() -> findByPk($merchant['agent_id']);
            if(!empty($agent -> pid)){
                $pagent = Agent::model() -> findByPk($agent -> pid);
                $agent_name = $pagent -> name;
            }else{
                $agent_name = $agent -> name;
            }
             
            $account = $merchant['account'];
            $industry = $merchant['wq_m_industry'];
            $pid = $merchant['alipay_auth_pid'];
            $arr = explode(',', $industry);
            $ins1 = $arr[0];
            $ins2 = $arr[1];
            $ins3 = $arr[2];
            $text1 = $GLOBALS['WECHAT_MERCHANT_JYLM'][$ins1]['text'];
            $text2 = $GLOBALS['WECHAT_MERCHANT_JYLM'][$ins1]['sub'][$ins2]['text'];
            $text3 = $GLOBALS['WECHAT_MERCHANT_JYLM'][$ins1]['sub'][$ins2]['sub'][$ins3]['text'];
            $industry = $text1.' '.$text2.' '.$text3;
             
            $store = Store::model()->findAll('merchant_id = :merchant_id and flag = :flag',
                array(':merchant_id' => $merchant['id'], ':flag' => 1));
            foreach ($store as $model) {
                $data[] = array(
                    'name' => $name,
                    'account' => $account,
                    'industry' => $industry,
                    'pid' => $pid,
                    'store_name' => $model['name'],
                    'shop_id' => $model['koubei_store_id'],
                    'status' => $GLOBALS['__STORE_ALIPAY_SYNC_STATUS'][$model['alipay_sync_verify_status']],
                    'agent_name' => $agent_name
                );
            }
        }
    
        $objPHPExcel = new PHPExcel();
        $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('A1','商户名称')
        ->setCellValue('B1','玩券管家账号')
        ->setCellValue('C1','行业')
        ->setCellValue('D1','ISV_PID')
        ->setCellValue('E1','门店')
        ->setCellValue('F1','shop_id')
        ->setCellValue('G1','审核状态')
        ->setCellValue('H1','总代名称');
         
        //设置列宽
        $objActSheet = $objPHPExcel->getActiveSheet();
        $objActSheet->getColumnDimension('A')->setWidth(20);
        $objActSheet->getColumnDimension('B')->setWidth(20);
        $objActSheet->getColumnDimension('C')->setWidth(20);
        $objActSheet->getColumnDimension('D')->setWidth(20);
        $objActSheet->getColumnDimension('E')->setWidth(20);
        $objActSheet->getColumnDimension('F')->setWidth(20);
        $objActSheet->getColumnDimension('G')->setWidth(20);
         
        //设置sheet名称
        $objActSheet -> setTitle('统计表');
         
        $i=2;
        foreach ($data as $obj) {
            $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A'.$i,$obj['name'])
            ->setCellValueExplicit('B'.$i,$obj['account'], PHPExcel_Cell_DataType::TYPE_STRING)
            ->setCellValue('C'.$i,$obj['industry'])
            ->setCellValueExplicit('D'.$i,$obj['pid'], PHPExcel_Cell_DataType::TYPE_STRING)
            ->setCellValue('E'.$i,$obj['store_name'])
            ->setCellValueExplicit('F'.$i,$obj['shop_id'], PHPExcel_Cell_DataType::TYPE_STRING)
            ->setCellValue('G'.$i,$obj['status'])
            ->setCellValue('H'.$i,$obj['agent_name']);
             
            $i ++;
        }
         
        $filename = "商户统计".date("YmdHis");//定义文件名
        $objWriter = new PHPExcel_Writer_Excel5($objPHPExcel);
        $this->outPut($filename);
        $objWriter->save("php://output");
    }
    
    /**
     * 导出商户表(对账)
     */
    public function actionExportMerchant()
    {
        set_time_limit(0);
        ini_set('memory_limit', '5000M');
        //创建导出表
        $str = "\xEF\xBB\xBF"."商户名称,开通时间,所属服务运营商,最近交易时间,行业";
        $str .= ",开通的支付渠道,玩券管家版本,所属总服务运营商,PID,商户号,门店数\n";
		//获取商户下门店数量
		$select_1 = 'merchant_id,COUNT(*) AS num';
		$from_1 = 'wq_store';
        $where_1 = 'flag='.FLAG_NO;
		$group_1 = 'merchant_id';
        $sql_1 = 'SELECT '.$select_1.' FROM '.$from_1.' WHERE '.$where_1." GROUP BY ".$group_1;
        //获取最后交易时间
		$select_2 = 'merchant_id,MAX(pay_time) AS pay_time';
		$from_2 = 'wq_order';
        $where_2 = 'pay_status='.ORDER_STATUS_PAID;//已付款
		$where_2 .= ' AND flag='.FLAG_NO;
		$group_2 = 'merchant_id';
        $sql_2 = 'SELECT '.$select_2.' FROM '.$from_2.' WHERE '.$where_2.' GROUP BY '.$group_2;
		//获取开通支付渠道: 支付宝/微信(支付成功的订单)
		$select_3 = 'merchant_id,flag';
		$from_3 = 'wq_order';
        $where_3 = 'pay_status='.ORDER_STATUS_PAID;
		$where_3 .= ' AND flag='.FLAG_NO;
		$where_3 .= ' AND (pay_channel='.ORDER_PAY_CHANNEL_ALIPAY_SM.' OR pay_channel='.ORDER_PAY_CHANNEL_ALIPAY_TM.')';
		$group_3 = 'merchant_id';
        $sql_3 = 'SELECT '.$select_3.' FROM '.$from_3.' WHERE '.$where_3.' GROUP BY '.$group_3;
        $select_4 = 'merchant_id,flag';
        $from_4 = 'wq_order';
        $where_4 = 'pay_status='.ORDER_STATUS_PAID;
        $where_4 .= ' AND flag='.FLAG_NO;
        $where_4 .= ' AND (pay_channel='.ORDER_PAY_CHANNEL_WXPAY_SM.' OR pay_channel='.ORDER_PAY_CHANNEL_WXPAY_TM.')';
        $group_4 = 'merchant_id';
        $sql_4 = 'SELECT '.$select_4.' FROM '.$from_4.' WHERE '.$where_4.' GROUP BY '.$group_4;
        //获取商户表数据:
        $select = 'm.id,m.wq_mchid,m.wq_m_name,m.gj_start_time,m.wq_m_industry,m.alipay_api_version,
            m.alipay_auth_pid,m.partner,gj.name AS gj_product_name,a1.name AS agent_name,
            a2.name AS p_agent_name,s.num AS store_num,o1.pay_time,o2.flag AS alipay_flag,o3.flag AS wechat_flag';
        $from = 'wq_merchant AS m';
        $join = ' LEFT JOIN wq_agent AS a1 ON a1.id = m.agent_id';
        $join .= ' LEFT JOIN wq_agent AS a2 ON a2.id = a1.pid';
        $join .= ' LEFT JOIN wq_gj_product AS gj ON gj.id = m.gj_product_id';
        $join .= ' LEFT JOIN ('.$sql_1.') AS s ON m.id = s.merchant_id';
		$join .= ' LEFT JOIN ('.$sql_2.') AS o1 ON m.id = o1.merchant_id';
		$join .= ' LEFT JOIN ('.$sql_3.') AS o2 ON m.id = o2.merchant_id';
        $join .= ' LEFT JOIN ('.$sql_4.') AS o3 ON m.id = o3.merchant_id';
        $where = 'm.flag = '.FLAG_NO;
        $sql = 'SELECT '.$select.' FROM '.$from.$join.' WHERE '.$where;
        $list = Yii::app()->db->createCommand($sql)->queryAll();
        foreach ($list as $v){
            
            $wq_m_industry = '';//行业
            if(!empty($v['wq_m_industry'])){
                $arr = explode(',',$v['wq_m_industry']);
                $wq_m_industry = $GLOBALS['WECHAT_MERCHANT_JYLM'][$arr[0]]['text'].$GLOBALS['WECHAT_MERCHANT_JYLM'][$arr[0]]['sub'][$arr[1]]['text'].$GLOBALS['WECHAT_MERCHANT_JYLM'][$arr[0]]['sub'][$arr[1]]['sub'][$arr[2]]['text'];
            }
            $channel = '';//开通支付渠道
            if (!empty($v['alipay_flag']) && !empty($v['wechat_flag'])){
                $channel = "支付宝/微信";
            }else if (!empty($v['alipay_flag']) && empty($v['wechat_flag'])){
                $channel = "支付宝";
            }else if (empty($v['alipay_flag']) && !empty($v['wechat_flag'])){
                $channel = "微信";
            }
            $gj_product_name = empty($v['gj_product_name'])?'未开通':$v['gj_product_name'];
            $pid = '';//支付宝版本PID
            if(!empty($v['alipay_api_version'])){
                if($v['alipay_api_version'] == 3){
                    $pid = $v['alipay_auth_pid'];
                }elseif ($v['alipay_api_version'] == 1){
                    $pid = $v['partner'];
                }elseif ($v['alipay_api_version'] == 2){
                    $pid = '2.0未设置pid';
                }
            }
            $store_num = empty($v['store_num'])?0:$v['store_num'];//门店数
            $str .= $v['wq_m_name']." 	,".$v['gj_start_time'].",".$v['agent_name'].",".$v['pay_time'].",".$wq_m_industry;
            $str .= ",".$channel.",".$gj_product_name.",".$v['p_agent_name'].",".$pid." 	,".$v['wq_mchid']." 	,".$store_num."\n";
        }
        //导出
        $filename = "商户表".date("Y年m月d日").'.csv'; //设置文件名
        $this->export_csv($filename,$str); //导出
    }
    
    //导出交易流水
    public function actionExportOrder(){
        set_time_limit(0);
        ini_set('memory_limit', '6000M');
        if (empty($_GET['start_time']) || empty($_GET['start_time'])){
            echo '时间参数缺失';exit;
        }
        $start_time = date('Y-m-d 00:00:00',strtotime($_GET['start_time']));
        $end_time = date('Y-m-d 23:59:59',strtotime($_GET['end_time']));
        //创建导出表
        $str = "\xEF\xBB\xBF"."订单号,商户名称,商户所在区域,用户年龄,用户性别,门店名称,操作员名称,交易号,支付渠道,储值支付,";
        $str .= "线下支付,银联支付,现金支付,支付状态,订单状态,储值确认状态,支付时间,创建时间,订单金额,支付宝账号,支付宝条码,代金券金额,";
        $str .= "折扣券金额,不打折金额,银联卡号,终端类型,商家折扣,支付宝折扣,支付通道,返佣费率,微信用户openid,支付宝用户openid,";
        $str .= "微信唯一标示openid,所属服务运营商,行业,玩券管家版本,商户号,退款金额,交易类型,总服务运营商,PID\n";
        //分组获取流水表数据
        $start_timeStamp = strtotime($start_time);
        $end_timeStamp = strtotime($end_time);
        $today_timeStamp = time();
        while ($start_timeStamp < $end_timeStamp && $start_timeStamp < $today_timeStamp)
        {
            $start_time_1 = date('Y-m-d 00:00:00',$start_timeStamp);
            $end_time_1 = date('Y-m-d 23:59:59',$start_timeStamp);
            $result = $this->getExportOrderDate($start_time_1,$end_time_1);
            if ($result['status'] == ERROR_NONE){
                foreach ($result['data'] as $v){
                    $wq_m_address = empty($v['wq_m_address'])?'':str_replace(',', '', $v['wq_m_address']);//商户所在区域
                    $age = empty($v['user_birthday'])?'':(date('Y')-date('Y',strtotime($v['user_birthday'])));//用户年龄
                    $sex = empty($v['user_sex'])?'':$GLOBALS['__SEX'][$v['user_sex']];//用户性别
                    $pay_channel = empty($v['pay_channel'])?'':$GLOBALS['ORDER_PAY_CHANNEL'][$v['pay_channel']];//支付渠道
                    $pay_status = empty($v['pay_status'])?'':$GLOBALS['ORDER_STATUS_PAY'][$v['pay_status']];//支付状态
                    $order_status = empty($v['order_status'])?'':$GLOBALS['ORDER_STATUS'][$v['order_status']];//订单状态
                    $terminal_type = empty($v['terminal_type'])?'':$GLOBALS['__TERMINAL_TYPE_POS'][$v['terminal_type']];//终端类型
                    $pay_passageway = empty($v['pay_passageway'])?'':$GLOBALS['__ORDER_PAY_PASSAGEWAY'][$v['pay_passageway']];//支付通道
                    $wq_m_industry = '';//行业
                    if(!empty($v['wq_m_industry'])){
                        $arr = explode(',', $v['wq_m_industry']);
                        $wq_m_industry = $GLOBALS['WECHAT_MERCHANT_JYLM'][$arr[0]]['text'].$GLOBALS['WECHAT_MERCHANT_JYLM'][$arr[0]]['sub'][$arr[1]]['text'].$GLOBALS['WECHAT_MERCHANT_JYLM'][$arr[0]]['sub'][$arr[1]]['sub'][$arr[2]]['text'];
                    }
                    $order_flag = $v['order_flag']==FLAG_NO?'收款':'退款';//交易类型
                    $pid = '';//PID
                    if(!empty($v['alipay_api_version'])){
                        if($v['alipay_api_version'] == 3){
                            $pid = $v['alipay_auth_pid'];
                        }elseif ($v['alipay_api_version'] == 1){
                            $pid = $v['partner'];
                        }elseif ($v['alipay_api_version'] == 2){
                            $pid = '2.0未设置pid';
                        }
                    }
                    $refund_money = $v['order_flag']==FLAG_NO?0:$v['refund_money'];//交易退款金额
                    $str .= $v['order_no']." 	,".$v['wq_m_name'].",".$wq_m_address.",".$age.",".$sex.",".$v['store_name'].",".$v['operator_name'].",".$v['trade_no']." 	,".$pay_channel.",".$v['stored_paymoney'];
                    $str .= ",".$v['online_paymoney'].",".$v['unionpay_paymoney'].",".$v['cash_paymoney'].",".$pay_status.",".$order_status.",".$v['stored_confirm_status'].",".$v['pay_time'].",".$v['create_time'].",".$v['order_paymoney'].",".$v['alipay_account'].",".$v['user_code']." 	,".$v['coupons_money'];
                    $str .= ",".$v['discount_money'].",".$v['undiscount_paymoney'].",".$v['ums_card_no'].",".$terminal_type.",".$v['merchant_discount_money'].",".$v['alipay_discount_money'].",".$pay_passageway.",".$v['commission_ratio'].",".$v['wechat_user_id'].",".$v['alipay_user_id'];
                    $str .= " 	,".$v['wechat_user_p_id'].",".$v['agent_name'].",".$wq_m_industry.",".$v['gj_product_name'].",".$v['wq_mchid']." 	,".$refund_money.",".$order_flag.",".$v['p_agent_name'].",".$pid." 	\n";
                }
            }
            $start_timeStamp = strtotime('+1 day',$start_timeStamp);
        }
        $start_time_2 = date('Y年m月d日',strtotime($start_time));
        $end_time_2 = date('Y年m月d日',strtotime($end_time));
        $filename = '交易流水表'.$start_time_2.'-'.$end_time_2.'.csv'; //设置文件名
        $this->export_csv($filename,$str); //导出
    }
    
    
    /**
     * 获取交易流水比表数据
     * @param unknown $start_time
     * @param unknown $end_time
     * @throws Exception
     */
    private function getExportOrderDate($start_time,$end_time){
        $result = array();
        try {
            //验证参数
            if (empty($start_time) || empty($end_time)){
                $result['status'] = ERROR_PARAMETER_MISS;
                throw new Exception('参数time必填');
            }
            //查询1条件
            $select1 = 'o.id AS order_id,o.merchant_id,o.user_id,o.store_id,o.operator_id,
	            o.order_no,o.trade_no,o.pay_channel,o.stored_paymoney,o.online_paymoney,
	            o.unionpay_paymoney,o.cash_paymoney,o.pay_status,o.order_status,
	            o.stored_confirm_status,o.pay_time,o.create_time,o.order_paymoney,
	            o.alipay_account,o.user_code,o.coupons_money,o.discount_money,
	            o.undiscount_paymoney,o.ums_card_no,o.terminal_type,o.merchant_discount_money,
	            o.alipay_discount_money,o.pay_passageway,o.commission_ratio,o.wechat_user_id,
	            o.alipay_user_id,o.wechat_user_p_id,m.wq_m_name,m.wq_m_address,m.wq_m_industry,
	            m.gj_product_id,m.wq_mchid,m.alipay_api_version,m.alipay_auth_pid,m.partner,
	            u.birthday AS user_birthday,u.sex AS user_sex,s.name AS store_name,
	            op.name AS operator_name,a.name AS agent_name,o.flag AS refund_money,
	            a1.name AS p_agent_name,o.flag AS order_flag,gj.name AS gj_product_name';
            $from1 = 'wq_order AS o';
            $join1 = ' INNER JOIN wq_store AS s ON s.id = o.store_id';
            $join1 .= ' LEFT JOIN wq_operator AS op ON op.id = o.operator_id';
            $join1 .= ' LEFT JOIN wq_merchant AS m ON m.id = o.merchant_id';
            $join1 .= ' LEFT JOIN wq_user AS u ON u.id = o.user_id';
            $join1 .= ' LEFT JOIN wq_agent AS a ON a.id = m.agent_id';
            $join1 .= ' LEFT JOIN wq_agent AS a1 ON a1.id = a.pid';
            $join1 .= ' LEFT JOIN wq_gj_product AS gj ON gj.id = m.gj_product_id';
            $where1 = 'o.flag = '.FLAG_NO;
            $where1 .= ' AND o.pay_status = '.ORDER_STATUS_PAID;//已支付订单
            $where1 .= ' AND o.order_type = '.ORDER_TYPE_CASHIER;//收银订单
            $where1 .= ' AND o.order_status != '.ORDER_STATUS_REVOKE;//已撤销
//             $where1 .= ' AND o.order_status != '.ORDER_STATUS_REFUND;//已退款
            $where1 .= " AND o.create_time >= '".$start_time."'";
            $where1 .= " AND o.create_time <= '".$end_time."'";
            //查询2条件
            $select2 = 'o.id AS order_id,o.merchant_id,o.user_id,o.store_id,o.operator_id,
	            o.order_no,o.trade_no,o.pay_channel,o.stored_paymoney,o.online_paymoney,
	            o.unionpay_paymoney,o.cash_paymoney,o.pay_status,o.order_status,
	            o.stored_confirm_status,o.pay_time,o.create_time,o.order_paymoney,
	            o.alipay_account,o.user_code,o.coupons_money,o.discount_money,
	            o.undiscount_paymoney,o.ums_card_no,o.terminal_type,o.merchant_discount_money,
	            o.alipay_discount_money,o.pay_passageway,o.commission_ratio,o.wechat_user_id,
	            o.alipay_user_id,o.wechat_user_p_id,m.wq_m_name,m.wq_m_address,m.wq_m_industry,
	            m.gj_product_id,m.wq_mchid,m.alipay_api_version,m.alipay_auth_pid,m.partner,
	            u.birthday AS user_birthday,u.sex AS user_sex,s.name AS store_name,
	            op.name AS operator_name,a.name AS agent_name,r.refund_money,
	            a1.name AS p_agent_name,o.order_type AS order_flag,gj.name AS gj_product_name';
            $from2 = 'wq_refund_record AS r';
            $join2 = ' INNER JOIN wq_order AS o ON o.id = r.order_id';
            $join2 .= ' INNER JOIN wq_store AS s ON s.id = o.store_id';
            $join2 .= ' LEFT JOIN wq_operator AS op ON op.id = r.operator_id';
            $join2 .= ' LEFT JOIN wq_merchant AS m ON m.id = o.merchant_id';
            $join2 .= ' LEFT JOIN wq_user AS u ON u.id = o.user_id';
            $join2 .= ' LEFT JOIN wq_agent AS a ON a.id = m.agent_id';
            $join2 .= ' LEFT JOIN wq_agent AS a1 ON a1.id = a.pid';
            $join2 .= ' LEFT JOIN wq_gj_product AS gj ON gj.id = m.gj_product_id';
            $where2 = 'r.flag = '.FLAG_NO;
            $where2 .= ' AND r.status != '.REFUND_STATUS_FAIL;//退款失败
            $where2 .= ' AND r.type = '.REFUND_TYPE_REFUND;//退款
            $where2 .= " AND r.create_time >= '".$start_time."'";
            $where2 .= " AND r.create_time <= '".$end_time."'";
            $where2 .= ' AND o.flag = '.FLAG_NO;
            $where2 .= ' AND o.order_type = '.ORDER_TYPE_CASHIER;//收银订单
            $where2 .= ' AND o.order_status != '.ORDER_STATUS_REVOKE;//已撤销
//             $where2 .= ' AND o.order_status != '.ORDER_STATUS_REFUND;//已退款
            //查询3条件
            $orderBy3 = 't.pay_time DESC';
             
            $sql1 = 'SELECT '.$select1.' FROM '.$from1.$join1.' WHERE '.$where1;
            $sql2 = 'SELECT '.$select2.' FROM '.$from2.$join2.' WHERE '.$where2;
            $sql3 = 'SELECT * FROM ( '.$sql1.' UNION ALL '.$sql2.') AS t ORDER BY '.$orderBy3;
            $list = Yii::app()->db->createCommand($sql3)->queryAll();
            $result['data'] = $list;
            $result['status'] = ERROR_NONE;
        }catch (Exception $e) {
            $result['status'] = isset($result['status'])?$result['status']:ERROR_EXCEPTION;
            $result['errMsg'] = $e -> getMessage();
        }
        return $result;
    }

    /**
     * 导出Excel
     * @param unknown $filename
     * @param unknown $data
     */
    private function export_csv($filename,$data) {
        $filename=iconv("utf-8", "gb2312", $filename);
        header("Content-type:text/csv");
        header("Content-Disposition:attachment;filename=".$filename);
        header('Cache-Control:must-revalidate,post-check=0,pre-check=0');
        header('Expires:0');
        header('Pragma:public');
        echo $data;
        exit();
    }

    /**
     * 拉取商户用户粉丝数量
     */
    public function actionExportUserNum(){
        set_time_limit(0);
        if (empty($_GET['start_time']) || empty($_GET['start_time'])){
            echo '时间参数缺失';exit;
        }
        $start_time = date('Y-m-d 00:00:00',strtotime($_GET['start_time']));
        $end_time = date('Y-m-d 23:59:59',strtotime($_GET['end_time']));
        //创建导出表
        $str = "\xEF\xBB\xBF"."玩券商户号,商户名称,日期,新增支付宝粉丝数,新增微信粉丝数,新增会员数";
        $str .= ",累计支付宝粉丝数,累计微信粉丝数,累计会员数,玩券版本,所属服务运营商,总服务运营商\n";
        //分组获取流水表数据
        $start_timeStamp = strtotime($start_time);
        $end_timeStamp = strtotime($end_time);
        $today_timeStamp = time();
        while ($start_timeStamp < $end_timeStamp && $start_timeStamp < $today_timeStamp)
        {
            $start_time_1 = date('Y-m-d 00:00:00',$start_timeStamp);
            $end_time_1 = date('Y-m-d 23:59:59',$start_timeStamp);
            $result = $this->getExportUserNumDate($start_time_1,$end_time_1);
            if ($result['status'] == ERROR_NONE){
                foreach ($result['data'] as $v){
                    $new_alipay_num = empty($v['new_alipay_num'])?0:$v['new_alipay_num'];//新增支付宝粉丝数
                    $new_wechat_num = empty($v['new_wechat_num'])?0:$v['new_wechat_num'];//新增微信粉丝数
                    $new_member_num = empty($v['new_member_num'])?0:$v['new_member_num'];//新增会员数
                    $total_alipay_num = empty($v['total_alipay_num'])?0:$v['total_alipay_num'];//累计支付宝粉丝数
                    $total_wechat_num = empty($v['total_wechat_num'])?0:$v['total_wechat_num'];//累计微信粉丝数
                    $total_member_num = empty($v['total_member_num'])?0:$v['total_member_num'];//累计会员数
                    $gj_product_name = empty($v['gj_product_name'])?'未开通':$v['gj_product_name'];//玩券版本名称
                    $p_agent_name = empty($v['p_agent_name'])?$v['agent_name']:$v['p_agent_name'];//总服务商名称
                    $str .= $v['wq_mchid']." 	,".$v['wq_m_name']." 	,".date('Y-m-d',$start_timeStamp).",".$new_alipay_num.",".$new_wechat_num.",".$new_member_num;
                    $str .= ",".$total_alipay_num.",".$total_wechat_num.",".$total_member_num.",".$gj_product_name.",".$v['agent_name'].",".$p_agent_name."\n";
                }
            }
            $start_timeStamp = strtotime('+1 day',$start_timeStamp);
        }
        $start_time_2 = date('Y年m月d日',strtotime($start_time));
        $end_time_2 = date('Y年m月d日',strtotime($end_time));
        $filename = '粉丝会员统计表'.$start_time_2.'-'.$end_time_2.'.csv'; //设置文件名
        $this->export_csv($filename,$str); //导出
    }

    /**
     * 获取粉丝会员统计数据
     * @param unknown $start_time
     * @param unknown $end_time
     * @throws Exception
     */
    private function getExportUserNumDate($start_time,$end_time){
        $result = array();
        try {
            //验证参数
            if (empty($start_time) || empty($end_time)){
                $result['status'] = ERROR_PARAMETER_MISS;
                throw new Exception('参数time必填');
            }
            //子查询:公共条件
            $select_0 = 'merchant_id,count(*) AS num';
            $from_0 = 'wq_user';
            $where_0 = 'flag = '.FLAG_NO;
            $where_0 .= " AND create_time <= '".$end_time."'";
            $Group_0 = "merchant_id";
            //子查询1:累计会员数
            $where_1 = $where_0;
            $where_1 .= " AND type = ".USER_TYPE_WANQUAN_MEMBER;//玩券会员
            $sql_1 = 'SELECT '.$select_0.' FROM '.$from_0.' WHERE '.$where_1." GROUP BY ".$Group_0;
            //子查询2:累计支付宝粉丝数
            $where_2 = $where_0;
            $where_2 .= " AND type = ".USER_TYPE_ALIPAY_FANS;//支付宝粉丝
            $where_2 .= ' AND alipay_status = '.ALIPAY_USER_SUBSCRIBE;//已关注
            $sql_2 = 'SELECT '.$select_0.' FROM '.$from_0.' WHERE '.$where_2." GROUP BY ".$Group_0;
            //子查询3:累计微信粉丝数
            $where_3 = $where_0;
            $where_3 .= " AND type = ".USER_TYPE_WECHAT_FANS;//微信粉丝
            $where_3 .= ' AND wechat_status = '.WECHAT_USER_SUBSCRIBE;//已关注
            $sql_3 = 'SELECT '.$select_0.' FROM '.$from_0.' WHERE '.$where_3." GROUP BY ".$Group_0;
            //子查询4:新增会员数
            $where_4 = $where_0;
            $where_4 .= " AND type = ".USER_TYPE_WANQUAN_MEMBER;//玩券会员
            $where_4 .= " AND create_time >= '".$start_time."'";
            $sql_4 = 'SELECT '.$select_0.' FROM '.$from_0.' WHERE '.$where_4." GROUP BY ".$Group_0;
            //子查询5:新增支付宝粉丝数
            $where_5 = $where_0;
            $where_5 .= " AND type = ".USER_TYPE_ALIPAY_FANS;//支付宝粉丝
            $where_5 .= " AND create_time >= '".$start_time."'";
            $where_5 .= ' AND alipay_status = '.ALIPAY_USER_SUBSCRIBE;//已关注
            $sql_5 = 'SELECT '.$select_0.' FROM '.$from_0.' WHERE '.$where_5." GROUP BY ".$Group_0;
            //子查询6:新增微信粉丝数
            $where_6 = $where_0;
            $where_6 .= " AND type = ".USER_TYPE_WECHAT_FANS;//微信粉丝
            $where_6 .= " AND create_time >= '".$start_time."'";
            $where_6 .= ' AND wechat_status = '.WECHAT_USER_SUBSCRIBE;//已关注
            $sql_6 = 'SELECT '.$select_0.' FROM '.$from_0.' WHERE '.$where_6." GROUP BY ".$Group_0;
            //查询条件
            $select = 'm.id,m.wq_mchid,m.wq_m_name,gj.name AS gj_product_name,
                a1.name AS agent_name,a2.name AS p_agent_name,u1.num AS total_member_num,
                u2.num AS total_alipay_num,u3.num AS total_wechat_num,
                u4.num AS new_member_num,u5.num AS new_alipay_num,u6.num AS new_wechat_num';
            $from = 'wq_merchant AS m';
            $join = ' LEFT JOIN wq_agent AS a1 ON a1.id = m.agent_id';
            $join .= ' LEFT JOIN wq_agent AS a2 ON a2.id = a1.pid';
            $join .= ' LEFT JOIN wq_gj_product AS gj ON gj.id = m.gj_product_id';
            $join .= ' LEFT JOIN ('.$sql_1.') AS u1 ON m.id = u1.merchant_id';
            $join .= ' LEFT JOIN ('.$sql_2.') AS u2 ON m.id = u2.merchant_id';
            $join .= ' LEFT JOIN ('.$sql_3.') AS u3 ON m.id = u3.merchant_id';
            $join .= ' LEFT JOIN ('.$sql_4.') AS u4 ON m.id = u4.merchant_id';
            $join .= ' LEFT JOIN ('.$sql_5.') AS u5 ON m.id = u5.merchant_id';
            $join .= ' LEFT JOIN ('.$sql_6.') AS u6 ON m.id = u6.merchant_id';
            $where = 'm.flag = '.FLAG_NO;
            $where .= " AND m.create_time <= '".$end_time."'";
            $sql = 'SELECT '.$select.' FROM '.$from.$join.' WHERE '.$where;
            $list = Yii::app()->db->createCommand($sql)->queryAll();
            $result['data'] = $list;
            $result['status'] = ERROR_NONE;
        }catch (Exception $e) {
            $result['status'] = isset($result['status'])?$result['status']:ERROR_EXCEPTION;
            $result['errMsg'] = $e -> getMessage();
        }
        return $result;
    }
    
    /**
     * 到浏览器  浏览器下载excel
     */
    public function outPut($filename)
    {
        header("Pragma: public");
        header("Expires: 0");
        header("Cache-Control:must-revalidate,post-check=0,pre-check=0");
        header("Content-Type:application/force-download");
        header("Content-Type:application/vnd.ms-execl");
        header("Content-Type:application/octet-stream");
        header("Content-Type:application/download");
        header("Content-Disposition:attachment;filename={$filename}.xls");
        header("Content-Transfer-Encoding:binary");
    }

}