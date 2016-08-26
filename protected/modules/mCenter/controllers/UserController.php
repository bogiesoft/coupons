<?php
/**
 * 会员管理
 */
// error_reporting(E_ALL);
class UserController extends mCenterController
{
    /**
     * 添加会员等级
     */
    public function actionAddUserGrade()
    {
        $msg = '';
        $merchant_id = Yii::app()->session['merchant_id'];
        if(isset($_POST['UserGrade']) && $_POST['UserGrade']){
            $post = $_POST['UserGrade'];
            $flag = false;
            //验证会员等级名称
            if(empty($post['name'])){
                $flag = true;
                Yii::app()->user->setFlash('name','会员等级名称必填');
            }
                        if(!empty($post['name']))
                        {
                            if(preg_match('/^[a-zA-Z_\x{4e00}-\x{9fa5}]+$/u',trim($post['name'])))
                            {
                            } else {
                                $flag = true;
                                Yii::app()->user->setFlash('name','不允许含有数字,符合,空格');
                            }
                        }
            //验证会员特权
            if(empty($post['discount'])){                            
                            
            }
            if($post['discount']) 
            {
                if(preg_match('/^[a-zA-Z_\x{4e00}-\x{9fa5}]+$/u',trim($post['discount']))){
                        $flag = true;
                        Yii::app()->user->setFlash('discount','会员特权格式不正确');
                } else {
                    if($post['discount'] < 0)
                    {
                        $flag = true;
                        Yii::app()->user->setFlash('discount','会员特权不为负数');
                    }
                }
                if($post['discount'] == 0.00)
                {
                    $flag = true;
                    Yii::app()->user->setFlash('discount','请填写正确的折扣格式');
                }
                if($post['discount'] > 10)
                {
                    $flag = true;
                    Yii::app()->user->setFlash('discount','会员特权只能输入（1-10）之间');
                }
            }
            if($post['discount']==='0')
            {
                $flag = 1;
                Yii::app()->user->setFlash('discount','会员特权只能输入（1-10）之间');
            }
                        
            //验证积分要求
            if($post['points_rule'] == '0')
            {
                $flag = true;
                Yii::app()->user->setFlash('points_rule','积分要求不为0');
            } else {
                if(empty($post['points_rule'])){
                        $flag = true;
                        Yii::app()->user->setFlash('points_rule','积分要求必填');
                } else
                {
                    $userc=new UserC();
                    $rs=json_decode($userc->checkUserPointRule($merchant_id,$post['points_rule']),true);
                    if($rs['status']==ERROR_DUPLICATE_DATA)
                    {
                        //积分要求重复
                        $flag = 1;
                        Yii::app()->user->setFlash('points_rule','积分不能重复');
                    }
                }
            }
            if($post['points_rule'])
            {
                if(preg_match('/^[a-zA-Z_\x{4e00}-\x{9fa5}]+$/u',trim($post['points_rule']))){
                        $flag = true;
                        Yii::app()->user->setFlash('points_rule','积分要求格式不正确');
                } else {
                    $flags = 0;
                    if($post['points_rule'] < 0)
                    {
                        $flag = true;
                        $flags = 1;
                        Yii::app()->user->setFlash('points_rule','积分要求不为负数');
                    }
                    if($flags == 0)
                    {
                        if(preg_match('/^[1-9]\d*|0$/', trim($post['points_rule'])))
                        {

                        } else {
                            $flag = true;
                            Yii::app()->user->setFlash('points_rule','积分要求为整数');
                        }
                    }
                }
            }
            
            //验证积分规则
            if($post['points_ratio'] == '0')
            {
                $flag = true;
                Yii::app()->user->setFlash('points_ratio','积分规则不为0');
            } else {
                if(empty($post['points_ratio'])){
                        
                }
            }
            if(!empty($post['points_ratio'])) 
            {
                if(preg_match('/^[a-zA-Z_\x{4e00}-\x{9fa5}]+$/u',trim($post['points_ratio']))){
                        $flag = true;
                        Yii::app()->user->setFlash('points_ratio','积分规则格式不正确');
                } else {
                    if($post['points_ratio'] <= 0)
                    {
                        $flag = true;
                        Yii::app()->user->setFlash('points_ratio','积分规则不为负数');
                    }
                    $ratio = explode('.', $post['points_ratio']);
                    $points = count($ratio);
                    if($points > 1)
                    {
                        $flag = 1;
                        Yii::app()->user->setFlash('points_ratio','积分规则不能有小数点');
                    }
                }
            }

            //验证生日积分倍率
            if($post['birthday_rate'] == '0')
            {
                $flag = true;
                Yii::app()->user->setFlash('birthday_rate','积分倍率不能为0');
            } else {
                if(empty($post['birthday_rate'])){
            
                }
            }
            if(!empty($post['birthday_rate']))
            {
                if(preg_match('/^[a-zA-Z_\x{4e00}-\x{9fa5}]+$/u',trim($post['points_ratio']))){
                    $flag = true;
                    Yii::app()->user->setFlash('birthday_rate','积分倍率格式不正确');
                } else {
                    if($post['birthday_rate'] <= 0)
                    {
                        $flag = true;
                        Yii::app()->user->setFlash('birthday_rate','倍率不能为负数');
                    }

                }
            }            
            
            //验证会员卡名称
            if(empty($_POST['card_name']))
            {
                $flag = true;
                Yii::app()->user->setFlash('card_name','会员卡名称必填');
            }   
            if(!empty($_POST['card_name']))
            {
                if(preg_match('/^[a-zA-Z_\x{4e00}-\x{9fa5}]+$/u',trim($_POST['card_name'])))
                {
                } else {
                    $flag = true;
                    Yii::app()->user->setFlash('card_name','会员卡名称格式不允许有数字,符合,空格');
                }
            }
            //验证会员卡图片样式
            if(empty($_POST['cover']))
            {
                $flag = true;
                Yii::app()->user->setFlash('cover','请选择样式或者上传样式');
            }
                        
            //验证会员等级名称是否存在
            if(!empty($post['name'])){
                $userc = new UserC();
                $id = '';
                $merchant_id = Yii::app() -> session['merchant_id'];                               
                $is_exit = $userc -> isUserGradeNameExit($post['name'],$id,$merchant_id);
                if($is_exit){
                  $flag = true;
                  Yii::app()->user->setFlash('name','会员等级名称已存在');
                }
            }
            
            //判断规则积分类型是否存在
            if(empty($post['rule_type'])){
                    $flag = true;
                    Yii::app()->user->setFlash('rule_type','请选择规则积分类型');
            }

            if(!$flag){                           
               $userc = new UserC();
                           $name = trim($post['name']);
                           $discount = !empty($post['discount']) ? $post['discount'] : '';
                           $points_rule = $post['points_rule'];
                           $points_ratio = !empty($post['points_ratio']) ? $post['points_ratio'] : '1';
                           $discount_illustrate = $post['discount_illustrate'];
                           $cover = isset($_POST['cover']) ? $_POST['cover'] : '';
                           $cardName = isset($_POST['card_name']) ? trim($_POST['card_name']) : '';
                           $if_hideword = isset($_POST['if_hideword'])&& $_POST['if_hideword'] == IF_HIDEWORD_YES ? IF_HIDEWORD_YES:IF_HIDEWORD_NO;
                           $rule_type = $post['rule_type'];
                           $birthday_rate = $post['birthday_rate'];
                           

                           $result = $userc -> addUserGrade($merchant_id,$name,$points_rule,$discount,$discount_illustrate,$points_ratio,$cover,$cardName,$if_hideword,$rule_type,$birthday_rate);
                           $result = json_decode($result,true);
                           if($result['status'] == ERROR_NONE){              
                                             $url = Yii::app()->createUrl('mCenter/user/setUserGrade');
                                            echo "<script>alert('添加成功');window.location.href='$url'</script>";
                           }else {
                             $status = $result['status'];
                             $msg = $result['errMsg'];
                             echo "<script>alert('$msg');</script>";
                }
            }
            
        }
        $userc = new UserC();
        $merchant_id = Yii::app()->session['merchant_id'];
        $Grade = json_decode($userc -> getSetUserGrade($merchant_id),true);
        $list = $Grade['datas']['list'];
        if(!empty($list)){
            $type = $list['rule_type'];
        }
        $this->render('addUserGrade',array(
                'type'=>$type
                
        ));
    }
    
    /**
     * 会员等级设置
     */
    public function actionSetUserGrade()
    {
        $list = array();
        $lists = array();
        $userc = new UserC();
        $merchant_id = Yii::app()->session['merchant_id'];
        $Grade = json_decode($userc -> getSetUserGrade($merchant_id),true);
        $GradeDraft = json_decode($userc -> getSetUserGradeDraft($merchant_id),true);
        $res = $userc -> contrastGrade($Grade,$GradeDraft);

        $num = 0;
        if (!empty($res['num'])){
            $num = $res['num'];
        }

        $change = $res['change'];       
        $result = $res['grade'];
        //var_dump($result['data']['list']);
        if($result['status'] == ERROR_NONE){
            if(isset($result['data']['list'])){
                $list = $result['data']['list'];
            }
            if(isset($result['datas']['list'])){
                $lists = $result['datas']['list'];
            }
        }
        
        $this->render('setUserGrade',array(
                'list'=>$list,
                'lists'=>$lists,
                'countusergrade'=>$result['countusergrade'],
                'change'=>$change,
                'num'=>$num
        ));
    }
    
    /**
     * 初始化会员累计积分和消费金额的脚本
     */
    public function actionPointsRuleInit(){


            //所有会员
            $users = User::model()->findAll('type=:type and flag=:flag',array(
                    ':type'=>USER_TYPE_WANQUAN_MEMBER,
                    ':flag'=>FLAG_NO
            ));
    
            //给每个用户计算累计积分和消费金额
            foreach ($users as $k => $v){
                //计算累计积分(总收入积分-总退分)
                $total_points = 0;
                $points1 = UserPointsdetail::model()->findAll('user_id=:user_id and balance_of_payments=:balance_of_payments',array(
                        ':user_id'=>$v['id'],
                        ':balance_of_payments'=>BALANCE_OF_PAYMENTS_INCOME
                ));
                //累加所有收入的积分
                foreach ($points1 as $k1=>$v1){
                    $total_points += $v1['points'];
                }
                $reduce_points1 = UserPointsdetail::model()->findAll('user_id=:user_id and balance_of_payments=:balance_of_payments',array(
                        ':user_id'=>$v['id'],
                        ':balance_of_payments'=>BALANCE_OF_PAYMENTS_REFUND
                ));
                //累加所有退的积分
                $total_reduce_points = 0;
                foreach ($reduce_points1 as $k2=>$v2){
                    $total_reduce_points += $v2['points'];
                }
                $total_points = $total_points-$total_reduce_points;
                $v->total_points = $total_points;
    
                //计算年累计积分（年累计收入积分-年累计退分）
                $total_year_points = 0;
                $criteria = new CDbCriteria();
                $criteria -> addCondition('user_id = :user_id');
                $criteria -> params[':user_id'] = $v['id'];
                $criteria -> addCondition('DATE_FORMAT(create_time,\'%Y\') = DATE_FORMAT(NOW(),\'%Y\')-1');
                $criteria -> addCondition('balance_of_payments = :balance_of_payments');
                $criteria -> params[':balance_of_payments'] = BALANCE_OF_PAYMENTS_INCOME;
                $points2 = UserPointsdetail::model()->findAll($criteria);
                //累加去年一年中所有的积分
                $total_year_reduce_points = 0;
                foreach ($points2 as $k3=>$v3){
                    $total_year_points += $v3['points'];
                }
                $criteria = new CDbCriteria();
                $criteria -> addCondition('user_id = :user_id');
                $criteria -> params[':user_id'] = $v['id'];
                $criteria -> addCondition('DATE_FORMAT(create_time,\'%Y\') = DATE_FORMAT(NOW(),\'%Y\')-1');
                $criteria -> addCondition('balance_of_payments = :balance_of_payments');
                $criteria -> params[':balance_of_payments'] = BALANCE_OF_PAYMENTS_REFUND;
                $reduce_points2 = UserPointsdetail::model()->findAll($criteria);
                //累加去年一年中所有退的积分
                foreach ($reduce_points2 as $k3=>$v3){
                    $total_year_reduce_points += $v3['points'];
                }
                $total_year_points = $total_year_points - $total_year_reduce_points;
                $v->total_year_points = $total_year_points;
    
                //计算累计消费（已支付总消费额-已退款金额-已部分退款金额）
                $total_trade = 0;
                $criteria = new CDbCriteria();
                $criteria -> addCondition('user_id = :user_id');
                $criteria -> params[':user_id'] = $v['id'];
                //删除标志位：正常
                $criteria -> addCondition('flag = :flag');
                $criteria -> params[':flag'] = FLAG_NO;
    
                //支付状态为已支付
                $criteria -> addCondition('pay_status = :pay_status');
                $criteria -> params[':pay_status'] = ORDER_STATUS_PAID;
    
                //门店消费
                $criteria -> addCondition('order_type = :order_type');
                $criteria -> params[':order_type'] = ORDER_TYPE_CASHIER;
                
                //订单状态为正常
                $criteria -> addCondition('order_status = :order_status');
                $criteria -> params['order_status'] = ORDER_STATUS_NORMAL;
    
                $order1 = Order::model() -> findAll($criteria);
    
                //计算累计交易额
                foreach ($order1 as $k4 => $v4) {
                    $total_trade += $v4['order_paymoney'];
                }
                //计算累计的已退款
                $total_refund_trade = 0;
                $criteria = new CDbCriteria();
                $criteria -> addCondition('user_id = :user_id');
                $criteria -> params[':user_id'] = $v['id'];
                //删除标志位：正常
                $criteria -> addCondition('flag = :flag');
                $criteria -> params[':flag'] = FLAG_NO;
    
                $criteria -> addCondition('order_status = :order_status1 || order_status = :order_status2' );
                //订单状态为已退款
                $criteria -> params[':order_status1'] = ORDER_STATUS_REFUND;
                //订单状态为已部分退款
                $criteria -> params[':order_status2'] = ORDER_STATUS_PART_REFUND;
    
                $refund_order1 = Order::model() -> findAll($criteria);
    
                //通过所有已退款订单id去找到对应的退款金额并累加起来
                foreach ($refund_order1 as $k5 => $v5) {
                    $criteria = new CDbCriteria();
                    $criteria -> addCondition('order_id = :order_id');
                    $criteria -> params[':order_id'] = $v5['id'];
                    //删除标志位：正常
                    $criteria -> addCondition('flag = :flag');
                    $criteria -> params[':flag'] = FLAG_NO;
    
                    //退款状态为退款成功
                    $criteria -> addCondition('status = :status');
                    $criteria -> params['status'] = REFUND_STATUS_SUCCESS;
                    $refund_record = RefundRecord::model() -> findAll($criteria);
                    foreach ($refund_record as $k6 => $v6 ){
                        $total_refund_trade += $v6['refund_money'];
                    }
                }
                //总实收消费额减去退款金额
                $total_trade = $total_trade - $total_refund_trade;
                $v->total_trade = $total_trade;
    
                //计算年累计消费（年累计已支付消费金额-年已退款金额-年部分已退款金额）
                $total_year_trade = 0;
                $criteria = new CDbCriteria();
                //会员id
                $criteria -> addCondition('user_id = :user_id');
                $criteria -> params[':user_id'] = $v['id'];
                //删除标志位：正常
                $criteria -> addCondition('flag = :flag');
                $criteria -> params[':flag'] = FLAG_NO;
    
                //支付状态为已支付
                $criteria -> addCondition('pay_status = :pay_status');
                $criteria -> params[':pay_status'] = ORDER_STATUS_PAID;
    
                //门店消费
                $criteria -> addCondition('order_type = :order_type');
                $criteria -> params[':order_type'] = ORDER_TYPE_CASHIER;
                
                //订单状态为正常
                $criteria -> addCondition('order_status = :order_status');
                $criteria -> params[':order_status'] = ORDER_STATUS_NORMAL;
    
                //订单时间为去年
                $criteria -> addCondition('DATE_FORMAT(pay_time,\'%Y\') = DATE_FORMAT(NOW(),\'%Y\')-1');
    
                $order2 = Order::model() -> findAll($criteria);
                $total_trade = 0;
    
                //计算年累计交易额
                foreach ($order2 as $k7 => $v7) {
                    $total_year_trade += $v7['order_paymoney'];
                }
    
                //计算累计的已退款
                $total_year_refund_trade = 0;
                $criteria = new CDbCriteria();
                $criteria -> addCondition('user_id = :user_id');
                $criteria -> params[':user_id'] = $v['id'];
                //删除标志位：正常
                $criteria -> addCondition('flag = :flag');
                $criteria -> params[':flag'] = FLAG_NO;
    
                $criteria -> addCondition('order_status = :order_status1 || order_status = :order_status2' );
                //订单状态为已退款
                $criteria -> params[':order_status1'] = ORDER_STATUS_REFUND;
                //订单状态为已部分退款
                $criteria -> params[':order_status2'] = ORDER_STATUS_PART_REFUND;
    
                $refund_order2 = Order::model() -> findAll($criteria);
    
                //通过所有已退款订单id去找到对应的退款金额并累加起来
                foreach ($refund_order2 as $k8 => $v8) {
                    $criteria = new CDbCriteria();
                    $criteria -> addCondition('order_id = :order_id');
                    $criteria -> params[':order_id'] = $v8['id'];
                    //删除标志位：正常
                    $criteria -> addCondition('flag = :flag');
                    $criteria -> params[':flag'] = FLAG_NO;
    
                    //退款状态为退款成功
                    $criteria -> addCondition('status = :status');
                    $criteria -> params[':status'] = REFUND_STATUS_SUCCESS;
                    //订单时间为去年
                    $criteria -> addCondition('DATE_FORMAT(refund_time,\'%Y\') = DATE_FORMAT(NOW(),\'%Y\')-1');
                    $refund_record2 = RefundRecord::model() -> findAll($criteria);
                    foreach ($refund_record2 as $k9 => $v9 ){
                        $total_year_refund_trade += $v9['refund_money'];
                    }
                }
                //总实收消费额减去退款金额
                $total_year_trade = $total_year_trade - $total_year_refund_trade;
                $v->total_year_trade = $total_year_trade;
    
                //保存这个用户的信息
                $v->save();
                echo '执行完毕';
            }
        
    }
    
    /**
     * 年底重置年累计积分和消费的脚本
     */
    public function actionYearPointsRule(){
        $merchant_ids = Merchant::model()->findAll(array(
                'select'=>array('id'),
                'condition' => 'flag=:flag',
                'params' => array(':flag'=>FLAG_NO)
        ));
        foreach ($merchant_ids as $key=>$val){
            //找到商户下的所有会员
            $users = User::model()->findAll('merchant_id=:merchant_id and type=:type and flag=:flag',array(
                    ':merchant_id'=>$val['id'],
                    ':type'=>USER_TYPE_WANQUAN_MEMBER,
                    ':flag'=>FLAG_NO
            ));
            //给每个用户计算累计积分和消费金额
            foreach ($users as $k => $v){   
                //计算年累计积分
                $total_year_points = 0;
                $criteria = new CDbCriteria();
                $criteria -> addCondition('user_id = :user_id');
                $criteria -> params[':user_id'] = $v['id'];
                $criteria -> addCondition('DATE_FORMAT(create_time,\'%Y\') = DATE_FORMAT(NOW(),\'%Y\')-1');
                $criteria -> addCondition('balance_of_payments = :balance_of_payments');
                $criteria -> params[':balance_of_payments'] = BALANCE_OF_PAYMENTS_INCOME;
                $points2 = UserPointsdetail::model()->findAll($criteria);
                foreach ($points2 as $k2=>$v2){
                    $total_year_points += $v2['points'];
                }
                $v->total_year_points = $total_year_points;

                //计算年累计消费
                $total_year_trade = 0;
                $criteria = new CDbCriteria();
                //会员id
                $criteria -> addCondition('user_id = :user_id');
                $criteria -> params[':user_id'] = $v['id'];
                //删除标志位：正常
                $criteria -> addCondition('flag = :flag');
                $criteria -> params[':flag'] = FLAG_NO;
    
                //支付状态为已支付
                $criteria -> addCondition('pay_status = :pay_status');
                $criteria -> params[':pay_status'] = ORDER_STATUS_PAID;
    
                //订单状态为正常
                $criteria -> addCondition('order_status = :order_status');
                $criteria -> params['order_status'] = ORDER_STATUS_NORMAL;
    
                //订单时间为去年
                $criteria -> addCondition('DATE_FORMAT(pay_time,\'%Y\') = DATE_FORMAT(NOW(),\'%Y\')-1');
    
                $order2 = Order::model() -> findAll($criteria);
                $total_trade = 0;
    
                //计算年累计交易额
                foreach ($order2 as $k4 => $v4) {
                    $total_year_trade += $v4['order_paymoney'];
                }
                $v->total_year_trade = $total_year_trade;
    
                //保存这个用户的信息
                $v->save();
            }
        }
    }
    
    /**
     * 发布会员等级
     */
    public function actionReleaseUserGrade(){
        $userc = new UserC();
        $merchant_id = Yii::app()->session['merchant_id'];
        $num = 0;
        if (!empty($_GET['num'])){
            $num = $_GET['num'];
        }
        if ($num != 0){
            $url = Yii::app()->createUrl('mCenter/user/setUserGrade',array('showleft'=>1));
            echo "<script>window.location.href='$url'</script>";
        }else {
            $result = $userc->releaseUserGrade($merchant_id);
            $result = json_decode($result,true);
            if($result['status'] == ERROR_NONE){
                $url = Yii::app()->createUrl('mCenter/user/setUserGrade');
                echo "<script>alert('发布成功');window.location.href='$url'</script>";
            }else {
                $status = $result['status'];
                $msg = $result['errMsg'];
                echo "<script>alert('$msg');</script>";
            }    
        }
    }
    
    /**
     * 还原会员等级
     */
    public function actionRevertUserGrade(){
        $userc = new UserC();
        $merchant_id = Yii::app()->session['merchant_id'];
    
    
        $result = $userc->RevertUserGrade($merchant_id);
        $result = json_decode($result,true);
        if($result['status'] == ERROR_NONE){
            $url = Yii::app()->createUrl('mCenter/user/setUserGrade');
            echo "<script>alert('还原成功');window.location.href='$url'</script>";
        }else {
            $status = $result['status'];
            $msg = $result['errMsg'];
            echo "<script>alert('$msg');</script>";
        }
    }
    
    /**
     * 清空会员条件设置
     */
    public function actionClearType(){
        $userc = new UserC();
        $merchant_id = Yii::app()->session['merchant_id'];
        $type = $_GET['type'] + 1;
        $GradeDraft = json_decode($userc -> getSetUserGradeDraft($merchant_id),true);
        $userc->clearGradeType($GradeDraft,$type);
        $arr=array('v'=>'所有条件已清除，请重新设置');
        echo json_encode($arr);
        
    }
    
    /**
     * 删除会员等级（会员人数为0的才能删）
     */
    public function actionDelUserGrade($id)
    {       
        $userc = new UserC();
        $merchant_id = Yii::app()->session['merchant_id'];
        $result = $userc -> delUserGrade($id,$merchant_id);
        $result = json_decode($result,true);
        
        if($result['status'] == ERROR_NONE){
            $this -> actionSetUserGrade();
        }
    }
    
    /**
     * 编辑会员等级
     */
    public function actionEditUserGrade($id)
    {
        $model = array();
        $userc = new UserC();
                $merchant_id = Yii::app()->session['merchant_id'];
        $model = $userc -> getUserGradeDraftDetails($id);

        //var_dump($model);
        $flag = 0;
                
        if(isset($_POST['UserGrade']) && $_POST['UserGrade']){
            $post = $_POST['UserGrade'];
            
            //验证会员等级名称
            if(empty($post['name'])){
                $flag = 1;
                Yii::app()->user->setFlash('name','会员等级名称必填');
            }
                        if(!empty($post['name']))
                        {
                            if(preg_match('/^[a-zA-Z_\x{4e00}-\x{9fa5}]+$/u',trim($post['name'])))
                            {
                            } else {
                                $flag = 1;
                                Yii::app()->user->setFlash('name','会员等级名称格式不允许有数字,符合,空格');
                            }
                        }
            //验证会员特权
            if($post['discount']) 
            {
                if(preg_match('/^[a-zA-Z_\x{4e00}-\x{9fa5}]+$/u',trim($post['discount']))){
                        $flag = 1;
                        Yii::app()->user->setFlash('discount','会员特权格式不正确');
                } else {
                    if($post['discount'] < 0)
                    {
                        $flag = 1;
                        Yii::app()->user->setFlash('discount','会员特权不为负数');
                    }
                }
                if($post['discount'] == 0.00)
                {
                    $flag = 1;
                    Yii::app()->user->setFlash('discount','请填写正确的折扣格式');
                }
                if($post['discount'] > 10)
                {
                    $flag = 1;
                    Yii::app()->user->setFlash('discount','会员特权只能输入（1-10）之间');
                }
            }
            if($post['discount']==='0')
            {
                $flag = 1;
                Yii::app()->user->setFlash('discount','会员特权只能输入（1-10）之间');
            }
                        
            //验证积分要求
            if($_GET['k'] != USER_GRADE_DEFAULT_YES)
            {
                if($post['points_rule'] == '0')
                {
                    $flag = 1;
                    Yii::app()->user->setFlash('points_rule','积分要求不为0');
                } else {
                    //积分要求不为0
                    if(empty($post['points_rule'])){
                        //积分要求为空
                            $flag = 1;
                            Yii::app()->user->setFlash('points_rule','积分要求必填');
                    } else{
                        //积分要求不为空且不为0,判断是否与设置过的有效会员等级一致
                        //不能与设置过的有效会员等级一致
                        $rs=json_decode($userc->checkUserPointRule($merchant_id,$post['points_rule'],$id),true);
                        if($rs['status']==ERROR_DUPLICATE_DATA)
                        {
                            //积分要求重复
                            $flag = 1;
                            Yii::app()->user->setFlash('points_rule','积分不能重复');
                        }
                    }
                }                          
                if($post['points_rule'])
                {
                    if(preg_match('/^[a-zA-Z_\x{4e00}-\x{9fa5}]+$/u',trim($post['points_rule']))){
                            $flag = 1;
                            Yii::app()->user->setFlash('points_rule','积分要求格式不正确');
                    } else {
                        $flags = 0;
                        if($post['points_rule'] < 0)
                        {
                            $flag = 1;
                            $flags = 1;
                            Yii::app()->user->setFlash('points_rule','积分要求不为负数');
                        }
                        if($flags == 0)
                        {
                            if(preg_match('/^[1-9]\d*|0$/', trim($post['points_rule'])))
                            {

                            } else {
                                $flag = 1;
                                Yii::app()->user->setFlash('points_rule','积分要求为整数');
                            }
                        }
                    }
                }
            }
            //验证积分规则
            if($post['points_ratio'] == '0')
            {
                $flag = 1;
                Yii::app()->user->setFlash('points_ratio','积分规则不为0');
            } else {
                if(empty($post['points_ratio']) && $flag == 0){
                        
                }
            }
            if(!empty($post['points_ratio'])) 
            {
                if(preg_match('/^[a-zA-Z_\x{4e00}-\x{9fa5}]+$/u',trim($post['points_ratio']))){
                        $flag = 1;
                        Yii::app()->user->setFlash('points_ratio','积分规则格式不正确');
                } else {
                    if($post['points_ratio'] <= 0)
                    {
                        $flag = 1;
                        Yii::app()->user->setFlash('points_ratio','积分规则不为负数');
                    }
                    $ratio = explode('.', $post['points_ratio']);
                    $points = count($ratio);
                    if($points > 1)
                    {
                        $flag = 1;
                        Yii::app()->user->setFlash('points_ratio','积分规则不能有小数点');
                    }
                }
            }
            
            if(!empty($post['birthday_rate']))
            {
                if(preg_match('/^[a-zA-Z_\x{4e00}-\x{9fa5}]+$/u',trim($post['birthday_rate']))){
                    $flag = 1;
                    Yii::app()->user->setFlash('birthday_rate','积分规则格式不正确');
                } else {
                    if($post['birthday_rate'] <= 0)
                    {
                        $flag = 1;
                        Yii::app()->user->setFlash('birthday_rate','积分规则不为负数');
                    }
                    $ratio = explode('.', $post['birthday_rate']);
                    $points = count($ratio);
                    if($points > 1)
                    {
                        $flag = 1;
                        Yii::app()->user->setFlash('birthday_rate','积分规则不能有小数点');
                    }
                }
            }
            
            //验证会员卡名称
            if(empty($_POST['card_name']))
            {
                    $flag = 1;
                    Yii::app()->user->setFlash('card_name','会员卡名称必填');
            }
                if(!empty($_POST['card_name']))
                {
                    if(preg_match('/^[a-zA-Z_\x{4e00}-\x{9fa5}]+$/u',trim($_POST['card_name'])))
                    {
                    } else {
                        $flag = 1;
                        Yii::app()->user->setFlash('card_name','会员卡名称格式不允许有数字,符合,空格');
                    }
                }
                     
                    //验证会员等级名称是否存在
                    if(!empty($post['name'])){
                            $userc = new UserC();
                            $is_exit = $userc -> isUserGradeNameExit($post['name'],$id,$merchant_id);
                            if($is_exit){
                                    $flag = 1;
                                    Yii::app()->user->setFlash('name','会员等级名称已存在');
                            }
                    }
                    $cover = isset($_POST['cover']) ? $_POST['cover'] : '';      
                    if($flag == 0)
                    {   
                        $name = trim($post['name']);
                        $discount = !empty($post['discount']) ? $post['discount'] : '';
                        $points_rule = isset($post['points_rule']) ? $post['points_rule'] : '';
                        $points_ratio = !empty($post['points_ratio']) ? $post['points_ratio'] : '1';
                        $discount_illustrate = $post['discount_illustrate'];
                        $cardName = isset($_POST['card_name']) ? trim($_POST['card_name']) : '';
                        $if_hideword = isset($_POST['if_hideword'])&& $_POST['if_hideword'] == IF_HIDEWORD_YES ? IF_HIDEWORD_YES:IF_HIDEWORD_NO;
                        $rule_type = $post['rule_type'];
                        $birthday_rate = $post['birthday_rate'];
                        
                        $result = $userc -> editUserGrade($name,$points_rule,$points_ratio,$discount_illustrate,$discount,$id,$cover,$cardName,$if_hideword,$rule_type,$birthday_rate);
                        $result = json_decode($result,true);                        
                        if($result['status'] == ERROR_NONE)
                        {
                            
                                $url = Yii::app()->createUrl('mCenter/user/setUserGrade');
                                echo "<script>alert('修改成功');window.location.href='$url'</script>";
                        }
                    }
            
        }
        $userc = new UserC();
        $merchant_id = Yii::app()->session['merchant_id'];
        $Grade = json_decode($userc -> getSetUserGrade($merchant_id),true);
        $GradeDraft = json_decode($userc -> getSetUserGradeDraft($merchant_id),true);
        $res = $userc -> contrastGrade($Grade,$GradeDraft);
        
        $list = array();
        $list2 = array();
        $type = 0;
        if (isset($Grade['datas']['list']) && !empty($Grade['datas']['list'])){
            $list = $Grade['datas']['list'];
        }
        if (isset( $GradeDraft['datas']['list']) && !empty( $GradeDraft['datas']['list'])){
            $list = $GradeDraft['datas']['list'];
        }
        
        if($list['rule_type'] != null ){
            $type = $list['rule_type'];
        }else if ($list2['rule_type'] != null){
            $type = $list2['rule_type'];
        }

        $change_type = $res['change_type'];

        $this -> render('editUserGrade',array(
                'model'=>$model,
                'id'=>$id,
                'type'=>$type,
                'change_type'=>$change_type
        ));
    }
    
    /**
     * 基本设置
     */
    public function actionBasicSet()
    {       
        $userc = new UserC();
        $merchant_id = Yii::app()->session['merchant_id'];
                $flag = 0;
                $ret = $userc ->getStored($merchant_id);
                $ret = json_decode($ret,true);
                if(empty($_POST['Merchant']['if_stored'][0]))
                {
                    if($ret['status'] == ERROR_NONE)
                    {
                        $flag = 1;
                        $url = Yii::app()->createUrl('mCenter/stored/storedList');
                        echo "<script>alert('已存在储值数据，不能关闭');window.location.href='$url'</script>";
                    }
                }
                if($flag == 0)
                {
                    $post = isset($_POST['Merchant']['if_stored'][0]) ? $_POST['Merchant']['if_stored'][0] : '';
                    $result = $userc -> basicSet($merchant_id,$post);
                    $result = json_decode($result,true);

                    if($result['status'] == ERROR_NONE){
                        $url = Yii::app()->createUrl('mCenter/stored/storedList');
                        echo "<script>alert('修改成功');window.location.href='$url'</script>";                  
                    }
                    if($result['status'] == ERROR_NO_DATA){
                        $url = Yii::app()->createUrl('mCenter/stored/storedList');
                        echo "<script>alert('商户不存在');window.location.href='$url'</script>";                 
                    }
                    if($result['status'] == ERROR_SAVE_FAIL){
                        $url = Yii::app()->createUrl('mCenter/stored/storedList');
                        echo "<script>alert('数据保存失败');window.location.href='$url'</script>";                    
                    }
                }
    }
        
        /**
         * 设置会员认证所需资料填写
         */
        public function actionAuthSettings(){
            $userc = new UserC();
            $merchant_id = Yii::app()->session['merchant_id'];
            $result = $userc->getAuthSet($merchant_id);
            $this->render('authSettings',array('authset'=>$result));
        }
        
        /**
         * 保存设置会员认证所需资料填写
         */
        public function actionSaveAuthSettings(){
            $info = $_GET;
            $arr = array();
            if(isset($info['cert'])){
                foreach ($info['cert'] as $k => $v){
                    if($v == 'on'){
                        $arr[] = $k;
                    }
                }
            }
            $authset = implode(',', $arr);
            $userc = new UserC();
            $merchant_id = Yii::app()->session['merchant_id'];
            $result = $userc->saveAuthSet($merchant_id, $authset);
            $result = json_decode($result,true);
            if($result['status'] == ERROR_NONE){
                $url = Yii::app()->createUrl('mCenter/user/authsettings');
                echo "<script>alert('保存成功');window.location.href='$url'</script>";
            }
        }
    
        /**
         * 查询会员列表
         */
        public function actionUserList()
        {
            $list       = '';            
            $merchantId = Yii::app()->session['merchant_id'];
            $account    = isset($_GET['account']) ? $_GET['account'] : '';
            $groups    = isset($_GET['groups']) ? $_GET['groups'] : '';
            $usergroups    = isset($_GET['usergroups']) ? $_GET['usergroups'] : '';
            $ret        = new UserC();
            $rat        = $ret->UserList($merchantId,$id='',$account,$groups,$usergroups);
            $list       = json_decode($rat,true);
            if($list['status'] == ERROR_NONE)
            {
                $list = $list['data'];
            } else {
                $list = '';
            }
            //会员分组
            $res   = $ret->Group($merchantId);
            $group = json_decode($res,true);
            if($group['status'] == ERROR_NONE)
            {
                $group = $group['data'];                
            } else {
                $group = '';
            }
            //自定义分组            
            $rot  = $ret->UserGroup($merchantId);
            $usergroup = json_decode($rot,true);
            
            if($usergroup['status'] == ERROR_NONE)
            {
                $usergroup = $usergroup['data'];
            } else {
                $usergroup = '';
            }
            $this->render('userList',array('list'=>$list,'group'=>$group,'usergroup'=>$usergroup));
        }
        
        /**
         * 查询会员详情
         */
        public function actionUserDetails()
        {
            $list = '';
            $id = isset($_GET['id']) ? $_GET['id'] : '';
            $merchantId = Yii::app()->session['merchant_id'];
            $ret = new UserC();
            $rat = $ret->UserList($merchantId,$id);
            $list = json_decode($rat,true);            
            if($list['status'] == ERROR_NONE)
            {
                $list = $list['data'];
            }
            $this->render('userDetails',array('list'=>$list));
        }
        
        /**
         * 添加分组
         */
        public function actionAddGroup()
        {
            $merchantId = Yii::app()->session['merchant_id'];
            $flag = 0;
            if(isset($_POST) && $_POST)
            {
                if(empty($_POST['group_name'])){
                    $flag = 1;
                    Yii::app()->user->setFlash('group_name','请填写分组名称');
                }
                if($flag == 0)
                {
                    $groupname = $_POST['group_name'];
                    $ret   = new UserC();
                    $add   = $ret->AddGroup($merchantId,$groupname);
                    $group = json_decode($add,true);
                    if($group['status'] == ERROR_NONE)
                    {
                        $url = Yii::app()->createUrl('mCenter/user/userList');
                        echo "<script>alert('添加成功');parent.location.href='$url'</script>";
                    } else {
                        $url = Yii::app()->createUrl('mCenter/user/addGroup');
                        echo "<script>alert('添加失败');window.location.href='$url'</script>";
                    }
                }
            }
            $this->render('addGroup');
        }
        
        /**
         * 修改分组
         */
        public function actionEditGroup($id)
        {
            $merchantId = Yii::app()->session['merchant_id'];
            $flag  = 0;
            $ret   = new UserC();
            if(isset($_POST) && $_POST)
            {
                if(empty($_POST['group_name'])){
                    $flag = 1;
                    Yii::app()->user->setFlash('group_name','请填写分组名称');
                }
                if($flag == 0)
                {
                    $groupname = $_POST['group_name'];                    
                    $add   = $ret->EditGroup($merchantId,$groupname,$id);
                    $group = json_decode($add,true);
                    if($group['status'] == ERROR_NONE)
                    {
                        $url = Yii::app()->createUrl('mCenter/user/userList',array('usergroups'=>$id));
                        echo "<script>alert('修改成功');parent.location.href='$url'</script>";
                    } else {
                        $url = Yii::app()->createUrl('mCenter/user/editGroup',array('usergroups'=>$id));
                        echo "<script>alert('修改失败');window.location.href='$url'</script>";
                    }
                }
            }
            $see = $ret->SeeGroup($id);
            $seegroup = json_decode($see,true);
            if($seegroup['status'] == ERROR_NONE)
            {
                $seegroup = $seegroup['data'];
            } else {
                $seegroup = '';
            }            
            $this->render('editGroup',array('seegroup'=>$seegroup));
        }
        
        /**
         * 删除分组
         */
        public function actionDel($id)
        {
            $ret = new UserC();
            $del = $ret->Del($id);
            $delete = json_decode($del,true);
            if($delete['status'] == ERROR_NONE)
            {
                $url = Yii::app()->createUrl('mCenter/user/userList');
                echo "<script>alert('删除成功');window.location.href='$url'</script>";
            } else {
                $url = Yii::app()->createUrl('mCenter/user/userList');
                echo "<script>alert('删除失败');window.location.href='$url'</script>";
            }
        }
        
        /**
         * 下拉框保存数据
         */
        public function actionGrade()
        {
            $grade = isset($_POST['grade_id']) ? $_POST['grade_id'] : '';
            $id    = isset($_POST['userid']) ? $_POST['userid'] : '';            
            $ret   = new UserC();
            $rat   = $ret->Grade($grade,$id);
            $usergrade = json_decode($rat,true);
            if($usergrade['status'] == ERROR_NONE)
            {
               $url = Yii::app()->createUrl('mCenter/user/userList');
                echo "<script>alert('修改成功');window.location.href='$url'</script>";
            } else {
                $url = Yii::app()->createUrl('mCenter/user/userList');
                echo "<script>alert('修改失败');window.location.href='$url'</script>";
            }
        }
    
    /**
     * 会员管理
     */
    public function actionGroup() {
        $client = '';
        $group = '';
        $keyword = '';
        $users = '';
        $operation = '';
        $target = '';
        if (isset($_GET['client']) && !empty($_GET['client'])) {
            $client = $_GET['client'];
        }
        if (isset($_GET['group']) && !empty($_GET['group'])) {
            $group = $_GET['group'];
        }else {
            $group = 'all-'.USER_GROUP_ALL; //分组默认全部会员
        }
        if (isset($_GET['keyword']) && !empty($_GET['keyword'])) {
            $keyword = $_GET['keyword'];
        }
        if (isset($_GET['user']) && !empty($_GET['user'])) {
            $users = $_GET['user'];
        }
        if (isset($_GET['operation']) && !empty($_GET['operation'])) {
            $operation = $_GET['operation'];
        }
        if (isset($_GET['target']) && !empty($_GET['target'])) {
            $target = $_GET['target'];
        }
        
        $merchant_id = Yii::app()->session['merchant_id']; //商户id
        $user = new UserC();
        
        //是否进行会员操作（添加/移动）
        if ($user && $operation && $target) {
            $ret = $user->regroupUser($merchant_id, $operation, $users, $group, $target);
            $result = json_decode($ret, true);
            if ($result['status'] != ERROR_NONE) {
                Yii::app()->user->setFlash('error', $result['errMsg']);
            }
        }
        
        //获取分组的会员列表
        $ret = $user->getGroupUserList($merchant_id, $client, $group, $keyword);
        $result = json_decode($ret, true);
        if ($result['status'] == ERROR_NONE) {
            $user_list = $result['data']['list'];
        }else {
            $user_list = array();
        }
        
        //获取所有分组及人数
        $ret1 = $user->getGroupList($merchant_id);
        $result1 = json_decode($ret1, true);
        if ($result1['status'] == ERROR_NONE) {
            $group_list = $result1['data']['list'];
        }else {
            $group_list = array();
        }
        
        //获取当前分组名称
        $group_name = '';
        $ret2 = $user->getGroupName($merchant_id, $group);
        $result2 = json_decode($ret2, true);
        if ($result2['status'] == ERROR_NONE) {
            $group_name = $result2['data']['name'];
        }
        //获取添加分组列表和移动分组列表
        $ret3 = $user->getOperationGroupList($merchant_id, $group);
        $result3 = json_decode($ret3, true);
        if ($result3['status'] == ERROR_NONE) {
            $add = $result3['data']['add'];
            $move = $result3['data']['move'];
        }else {
            $add = array();
            $move = array();
        }
        //var_dump($result3['data']);
        
        $this->render ('group', array('list' => $user_list, 'add' => $add, 'move' => $move, 'group_name' => $group_name, 'group_list' => $group_list, 'pages' => $user->page));
    }
    
    /**
     * 自定义分组编辑（新建/修改）
     */
    public function actionEditCustom() {
        $data = array();
        $data['error'] = 'failure';
        if (isset($_POST['id']) && isset($_POST['name'])) {
            $id = $_POST['id'];
            $name = $_POST['name'];
            if (empty($name)) {
                $data['errMsg'] = '分组名不能为空';
                echo json_encode($data);
                return;
            }else if(preg_match("/\s/",$name)) {
                $data['errMsg'] = '分组名不能含有空格';
                echo json_encode($data);
                return;
            }
            $merchant_id = Yii::app()->session['merchant_id'];
            //分组id判断，0创建，非0修改
            $user = new UserC();
            if ($id == '0') {
                //创建新分组
                $ret = $user->createCustomGroup($merchant_id, $name);
                $result = json_decode($ret, true);
                if ($result['status'] == ERROR_NONE) {
                    $data['error'] = 'success';
                    $data['group_id'] = $result['data']['group_id'];
                }else {
                    $data['errMsg'] = $result['errMsg'];
                }
            }else {
                $arr = explode("-", $id);
                $group_id = isset($arr[1]) ? $arr[1] : '0';
                //修改分组
                $ret = $user->modifyCustomGroup($merchant_id, $group_id, $name);
                $result = json_decode($ret, true);
                if ($result['status'] == ERROR_NONE) {
                    $data['error'] = 'success';
                    $data['group_id'] = $result['data']['group_id'];
                }else {
                    $data['errMsg'] = $result['errMsg'];
                }
            }
        }else {
            $data['errMsg'] = '错误的请求';
        }
        echo json_encode($data);
    }

    /**
     * 删除自定义分组
     */
    public function actionDeleteCustom() {
        $data = array();
        $data['error'] = 'failure';
        if (isset($_POST['id'])) {
            $id = $_POST['id'];
        
            $merchant_id = Yii::app()->session['merchant_id'];
            //分组id判断，0删除成功，非0修改数据库
            $user = new UserC();
            if ($id == '0') {
                //删除成功
                $data['error'] = 'success';
            }else {
                $arr = explode("-", $id);
                $group_id = isset($arr[1]) ? $arr[1] : '0';
                //修改分组
                $ret = $user->deleteCustomGroup($merchant_id, $group_id);
                $result = json_decode($ret, true);
                if ($result['status'] == ERROR_NONE) {
                    $data['error'] = 'success';
                    $data['count'] = $result['data']['count'];
                }else {
                    $data['errMsg'] = $result['errMsg'];
                }
            }
        }else {
            $data['errMsg'] = '错误的请求';
        }
        echo json_encode($data);
    }

    /**
     * 检测会员等级管理输入
     */
    public function actionCheckUserGrade()
    {
        $merchant_id = Yii::app()->session['merchant_id'];
        if(isset($_GET['data'])&&isset($_GET['type']))
        {
            $data=$_GET['data'];
            $type=$_GET['type'];
            $flag=true;
            if($type=='name')
            {
                //会员等级名称
                if(empty($data))
                {
                    $flag=false;
                    $arr=array('type'=>'name','v'=>'会员等级名称必填');
                    echo json_encode($arr);
                }
                else
                {
                    if(preg_match('/^[a-zA-Z_\x{4e00}-\x{9fa5}]+$/u',trim($data)))
                    {
                    } else {
                        $flag = false;
                        $arr=array('type'=>'name','v'=>'不允许有数字,符合,空格');
                        echo json_encode($arr);
                    }

                    $userc = new UserC();
                    $id = '';
                    $userGrandId = isset($_GET['userGrandId'])?$_GET['userGrandId']:'';
                    $userGrandName = $userc -> getUserGrandName($userGrandId);
                    $merchant_id = Yii::app() -> session['merchant_id'];
                    if($userGrandName != trim($data)){ //编辑界面     如果原输入框的会员等级名称和userGrandId对应的数据库的会员等级名称不一样  说明有改动  要判断是否存在
                    $is_exit = $userc -> isUserGradeNameExit($data,$id,$merchant_id);
                    if($is_exit){
                        $flag = false;
                        $arr=array('type'=>'name','v'=>'会员等级名称已存在');
                        echo json_encode($arr);
                    }
                    }
                }
            }
            else if($type=='discount')
            {
                //会员特权
                if(empty($data))
                {
                    if($data === '0' || $data === 0){
                        $flag = false;
                        $arr=array('type'=>'discount','v'=>'会员特权只能输入（1-10）之间');
                        echo json_encode($arr);
                    } else {
//                        $flag=false;
//                        $arr=array('type'=>'discount','v'=>'请输入会员折扣,1到9.9之间，保留一位小数，不填默认为无会员特权');
//                        echo json_encode($arr);
                    }
                }
                if($data)
                {
                    if(preg_match('/^[a-zA-Z_\x{4e00}-\x{9fa5}]+$/u',trim($data))){
                        $flag = false;
                        $arr=array('type'=>'discount','v'=>'会员特权格式不正确');
                        echo json_encode($arr);
                    } else {
                        if($data <= 0)
                        {
                            $flag = false;
                            $arr=array('type'=>'discount','v'=>'会员特权不为负数');
                            echo json_encode($arr);
                        }
                    }
                    if($data == 0.00)
                    {
                        $flag = false;
                        $arr=array('type'=>'discount','v'=>'请填写正确的折扣格式');
                        echo json_encode($arr);
                    }
                    if($data > 10)
                    {
                        $flag = false;
                        $arr=array('type'=>'discount','v'=>'会员特权只能输入（1-10）之间');
                        echo json_encode($arr);
                    }
                }
            }else if($type=='points_rule')
            {
                //积分要求
                if($data == '0')
                {
                    $flag = false;
                    $arr=array('type'=>'points_rule','v'=>'积分要求不为0');
                    echo json_encode($arr);
                } else {
                    if(empty($data)){
                        $flag = false;
                        $arr=array('type'=>'points_rule','v'=>'积分要求必填');
                        echo json_encode($arr);
                    } else
                    {
                        $userc=new UserC();
                        $userGrandId = isset($_GET['userGrandId'])?$_GET['userGrandId']:'';
                        $userGrandPointRule = $userc -> getUserGrandPointRule($userGrandId);
                        $rs=json_decode($userc->checkUserPointRule($merchant_id,$data),true);
                        if($userGrandPointRule != trim($data)){
                        if($rs['status']==ERROR_DUPLICATE_DATA)
                        {
                            //积分要求重复
                            $flag = false;
                            $arr=array('type'=>'points_rule','v'=>'积分不能重复');
                            echo json_encode($arr);
                        }
                        }
                    }
                }
                if($data)
                {
                    if(preg_match('/^[a-zA-Z_\x{4e00}-\x{9fa5}]+$/u',trim($data))){
                        $flag = true;
                        $flag = false;
                        $arr=array('type'=>'points_rule','v'=>'积分要求格式不正确');
                        echo json_encode($arr);
                    } else {
                        $flags=0;
                        if($data < 0)
                        {
                            $flags=1;
                            $flag = false;
                            $arr=array('type'=>'points_rule','v'=>'积分要求不为负数');
                            echo json_encode($arr);
                        }
                        if($flags == 0)
                        {
                            if(preg_match('/^[1-9]\d*|0$/', trim($data)))
                            {

                            } else {
                                $flag = false;
                                $arr=array('type'=>'points_rule','v'=>'积分要求为整数');
                                echo json_encode($arr);
                            }
                        }
                    }
                }
            }else if($type=='birthday_rate')
                {
                    //生日积分倍率
                    if($data == '0')
                    {
                        $flag = false;
                        $arr=array('type'=>'birthday_rate','v'=>'积分倍率不为0');
                        echo json_encode($arr);
                    }
                    if($data)
                    {
                        if(preg_match('/^[a-zA-Z_\x{4e00}-\x{9fa5}]+$/u',trim($data))){
                            $flag = true;
                            $flag = false;
                            $arr=array('type'=>'birthday_rate','v'=>'积分倍率格式不正确');
                            echo json_encode($arr);
                        } else {
                            $flags=0;
                            if($data < 0)
                            {
                                $flags=1;
                                $flag = false;
                                $arr=array('type'=>'birthday_rate','v'=>'积分倍率不为负数');
                                echo json_encode($arr);
                            }
                            if($flags == 0)
                            {
                                if(preg_match('/^[1-9]\d*|0$/', trim($data)))
                                {
                
                                } else {
                                    $flag = false;
                                    $arr=array('type'=>'birthday_rate','v'=>'积分倍率为整数');
                                    echo json_encode($arr);
                                }
                            }
                        }
                    }
            }else if($type=='points_ratio')
            {
                //验证积分规则
                if($data == '0')
                {
                    $flag = false;
                    $arr=array('type'=>'points_ratio','v'=>'积分规则不为0');
                    echo json_encode($arr);
                } else {
                    if(empty($data)){
//                        $flag = false;
//                        $arr=array('type'=>'points_ratio','v'=>'积分规则必填');
//                        echo json_encode($arr);
                    }
                }
                if(!empty($data))
                {
                    if(preg_match('/^[a-zA-Z_\x{4e00}-\x{9fa5}]+$/u',trim($data))){
                        $flag = false;
                        $arr=array('type'=>'points_ratio','v'=>'积分规则格式不正确');
                        echo json_encode($arr);
                    } else {
                        if($data <= 0)
                        {
                            $flag = false;
                            $arr=array('type'=>'points_ratio','v'=>'积分规则不为负数');
                            echo json_encode($arr);
                        }
                        $ratio = explode('.', $data);
                        $points = count($ratio);
                        if($points > 1)
                        {
                            $flag = false;
                            $arr=array('type'=>'points_ratio','v'=>'积分规则不能有小数点');
                            echo json_encode($arr);
                        }
                    }
                }
            }else if($type=='span_cardname')
            {
                //验证会员卡名称
                if(empty($data))
                {
                    $flag = false;
                    $arr=array('type'=>'span_cardname','v'=>'会员卡名称必填');
                    echo json_encode($arr);
                }
                if(!empty($data))
                {
                    if(preg_match('/^[a-zA-Z_\x{4e00}-\x{9fa5}]+$/u',trim($data)))
                    {
                    } else {
                        $flag = false;
                        $arr=array('type'=>'span_cardname','v'=>'会员卡名称格式不允许有数字,符合,空格');
                        echo json_encode($arr);
                    }
                }
            }

            if($flag)
            {
                //没有错误
                $arr=array('type'=>$type,'v'=>'');
                echo json_encode($arr);
            }
        }

    }
    
    /**
     * 会员分组管理列表
     * xuyf
     * 2016/1/25
     */
    public function actionUserGroupList()
    {
        $list = array();
        $merchant_id = Yii::app()->session['merchant_id'];
        $u = new U();
        $result = $u -> getUserGroupList($merchant_id);
        $result = json_decode($result,true);
        if ($result['status'] == ERROR_NONE) {
            $list = $result['data']['list'];
        }
        $this->render('userGroupList',array('list'=>$list));
    }
    
    /**
     * 删除会员分组管理
     *  xuyf
     * 2016/1/25
     */
    public function actionDelUserGroup($userGroupId)
    {
        $u = new U();
        $result = $u -> delUserGroup($userGroupId);
        $result = json_decode($result,true);
        if ($result['status'] == ERROR_NONE) {
            $this->redirect(array('userGroupList'));
        }
    }
    
    public function actionAddUserGroupSearch_1()
    {
        // error_reporting(E_ALL);
        var_dump($this->get());
        $action = $this->get('btn_save')?$this->get('btn_save'):$this->get('btn_jisuan');
        
//         dd
        if($action == '计算人数')
        {
            echo '执行计算操作！';
        }
        elseif($action == '保存')
        {
            echo '执行保存操作！';
        }
        else 
        {
            echo '不做任何处理，第一次加载或点击了取消按钮！';
        }
        
        $this->render('addUserGroupSearch_1');
    }
    /**
     * 新建会员管理分组  (根据条件筛选)
     */
    public function actionAddUserGroupSearch()
    {
        $merchant_id = Yii::app()->session['merchant_id'];
        $u = new U();
        $store = new StoreC();
        //会员来源
        $from = array();
        $from[1] = '微信';
        $from[2] = '支付宝';
        $from[4] = '其他';
        //性别
        $sex = array();
        $sex[1] = '男';
        $sex[2] = '女';
        $sex[3] = '未知';
        //会员等级
        $userGrade = array();
        //设置省市区数据源
        $province = json_decode($store -> getProvince());
        $province = $province->data;
        $city = array();
        $area = array();
        
        $res = json_decode($u -> getUserGrade($merchant_id),true);
        if($res['status'] == ERROR_NONE){
            $userGrade = $res['data']['list'];
        }
        if (isset($_POST) && !empty($_POST)){
            $name = '';
            if(isset($_POST['name']) && !empty($_POST['name'])){
                $name = $_POST['name'];
            }
            
            $from = array();
            if(isset($_POST['from']) && !empty($_POST['from'])){
                $from = $_POST['from'];
            }
             //print_r($from);exit;
            $sex = array();
            if(isset($_POST['sex']) && !empty($_POST['sex'])){
                $sex = $_POST['sex'];
            }
            
            $regist_time = '';
            if(isset($_POST['Time']) && !empty($_POST['Time'])){
                $regist_time = $_POST['Time'];
            }
            
            $addressHide = '';
            if(isset($_POST['addressHide']) && !empty($_POST['addressHide'])){
                $addressHide = $_POST['addressHide'];
            }
            
            $userGrade = array();
            if(isset($_POST['userGrade']) && !empty($_POST['userGrade'])){
                $userGrade = $_POST['userGrade'];
            }
            $result = $u -> addUserGroupSearch($merchant_id,$name,$from,$sex,$regist_time,$addressHide,$userGrade);
            $result = json_decode($result,true);
            if($result['status'] == ERROR_NONE){
                $this->redirect(array('userGroupList'));
            }
        }
        $this->render ( 'addUserGroupSearch', array (
                'from' => $from,
                'sex' => $sex ,
                'userGrade' => $userGrade,
                'province' => $province,
                'city' => $city,
                'area' => $area
        ) );
    }
    
    /**
     * 根据条件筛选符合条件的会员
     */
    public function actionSearchUser()
    {
        $from_alipay = '';
        if(isset($_POST['from_alipay']) && !empty($_POST['from_alipay'])){
            $from_alipay = $_POST['from_alipay'];
        }
        
        $from_wx = '';
        if(isset($_POST['from_wx']) && !empty($_POST['from_wx'])){
            $from_wx = $_POST['from_wx'];
        }
        
        $from_other = '';
        if(isset($_POST['from_other']) && !empty($_POST['from_other'])){
            $from_other = $_POST['from_other'];
        }
        
        $sex_male = '';
        if(isset($_POST['sex_male']) && !empty($_POST['sex_male'])){
            $sex_male = $_POST['sex_male'];
        }
        
        $sex_femal = '';
        if(isset($_POST['sex_femal']) && !empty($_POST['sex_femal'])){
            $sex_femal = $_POST['sex_femal'];
        }
        
        $sex_other = '';
        if(isset($_POST['sex_other']) && !empty($_POST['sex_other'])){
            $sex_other = $_POST['sex_other'];
        }
        
        $userGradeArr = '';
        if(isset($_POST['userGradeArr']) && !empty($_POST['userGradeArr'])){
            $userGradeArr = $_POST['userGradeArr'];
        }
        
        $regist_time = '';
        if(isset($_POST['regist_time']) && !empty($_POST['regist_time'])){
            $regist_time = $_POST['regist_time'];
        }
        
//      $age = '';
//      if(isset($_POST['age']) && !empty($_POST['age'])){
//          $age = $_POST['age'];
//      }
        $addressHideval = '';
        if(isset($_POST['addressHideval']) && !empty($_POST['addressHideval'])){
            $addressHideval = $_POST['addressHideval'];
        }

        $merchant_id = Yii::app()->session['merchant_id'];
        $u = new U();
        $res = $u->searchUser($merchant_id,$from_alipay,$from_wx,$from_other,$sex_male,$sex_femal,$sex_other,$userGradeArr,$regist_time,$addressHideval);
        $res = json_decode($res,true);
        if($res['status'] == ERROR_NONE){
            echo json_encode($res['data']);
        }
        //echo json_encode($userGradeArr);
    }
    
    /**
     * 添加地址加载到页面
     */
    public function actionAddAddress()
    {
        $merchant_id = Yii::app()->session['merchant_id'];
        $u = new U();
        
        $provinceCode = '';
        $cityCode = '';
        if(isset($_POST['provinceCode'])){
            $provinceCode = $_POST['provinceCode'];
        }
        if(isset($_POST['cityCode'])){
            $cityCode = $_POST['cityCode'];
        }
        
        $res = $u -> addAddress($merchant_id,$provinceCode,$cityCode);
        //$res = json_decode($res,true);
        echo $res;
    }
    
    public function actionTest(){
        $merchant_id = Yii::app()->session['merchant_id'];
        $u = new U();
        $u->searchUser($merchant_id,'','','','','','','','','350400-');
    }
    
    /**
     * ajax判断分组名是否存在
     */
    public function actionCheckUserGroupName()
    {
        $merchant_id = Yii::app()->session['merchant_id'];
        $u = new U();
        $name = '';
        if(isset($_POST['name']) && !empty($_POST['name'])){
            $name = $_POST['name'];
        }
        $res = $u -> checkUserGroupName($merchant_id,$name);
        echo $res;
    }
    
    /**
     * 新建会员管理分组  (自定义分组)
     */
    public function actionAddUserGroupSelf()
    {
        $merchant_id = Yii::app()->session['merchant_id'];
        $u = new U();
        if(isset($_POST['UserGroup']) && !empty($_POST['UserGroup'])){
            extract($_POST['UserGroup']);
            $res = $u -> addUserGroupSelf($merchant_id,$name);
            $res = json_decode($res,true);
            if($res['status'] == ERROR_NONE){
                $this->redirect(array('userGroupList'));
            }
        }
        $this->render('addUserGroupSelf');
    }
    
    /**
     * 修改会员管理分组  (自定义分组)
     */
    public function actionEditUserGroupSelf($userGroupId)
    {
        $merchant_id = Yii::app()->session['merchant_id'];
        $u = new U();
        $model = $u->getUserGroupDetail($userGroupId);
        if(isset($_POST['UserGroup']) && !empty($_POST['UserGroup'])){
            extract($_POST['UserGroup']);
            $res = $u -> editUserGroupSelf($merchant_id,$name,$userGroupId);
            $res = json_decode($res,true);
            if($res['status'] == ERROR_NONE){
                $this->redirect(array('userGroupList'));
            }
        }
        
        $this->render('editUserGroupSelf',array('model'=>$model,'userGroupId'=>$userGroupId));
    }

    
    /**
     * 积分规则
     */
    public function actionIntegrationRule()
    {
        $merchant_id = Yii::app()->session['merchant_id'];
        $user = new UserC();
        $rule = $user ->IntegrationRule($merchant_id);
        $res = json_decode($rule,true);
        if($res['status'] == ERROR_NONE){
            $model = $res['data'];
        }
        $this -> render('integrationRule',array('model' => $model));
    }
    
    /**
     * 编辑积分规则
     */
    public function actionEditIntegrationRule()
    {
        $merchant_id = Yii::app()->session['merchant_id'];
        if($_POST){
            $id = $_POST['id'];
            $period = $_POST['period'];
            $num = $_POST['num'];
            $condition = $_POST['condition'];
            $points = $_POST['points'];
            $if_storedpay_get_points = !empty($_POST['if_storedpay_get_points']) ? $_POST['if_storedpay_get_points'] : '1';
            $user = new UserC();
            $re = $user -> EditIntegrationRule($merchant_id, $id, $period, $num, $condition, $points, $if_storedpay_get_points);
            $result = json_decode($re,true);
            if($result['status'] == ERROR_NONE){
                $this ->redirect(array('integrationRule'));
            }
        }
    }
    
    public function actionPopStore()
    {
//         echo '弹窗';
        $merchant_id = Yii::app()->session['merchant_id'];
        $storeC = new StoreC();
        $pca = new Selector();
        
        $list = array();
        
        //搜索：省市区
        $address = '';
        if (!empty($_GET['Selector']['liveplaceProvince'])) {
            $address = $_GET['Selector']['liveplaceProvince'];
            if (!empty($_GET['Selector']['liveplaceCity'])) {
                $address = $address . ',' . $_GET['Selector']['liveplaceCity'] . ',' . $_GET['Selector']['liveplaceArea'];
            }
        }
        
        $criteria = new CDbCriteria();
        $criteria->addCondition('merchant_id='.$merchant_id);
//         $criteria->addCondition('flag='.FLAG_NO);
        $province = json_decode($storeC -> getProvince());
        $province = $province->data;
        
        $store_name = isset($_GET['store_name'])?$_GET['store_name']:'';
        
        if(!empty($store_name))
            $criteria->addCondition("name like '%{$store_name}%'");
        
        if(!empty($address))
            $criteria->addCondition("address like '%{$address}%'");
        
        $count = Store::model()->count($criteria);
        $pages = new CPagination($count);
        $pages->pageSize = Yii::app() -> params['perPage'];
        $pages->applyLimit($criteria);

        $stores = Store::model()->findAll($criteria);
        
        $this->render('popstore',array(
            'province'=>$province,
            'pages'=>$pages,
            'pca' => $pca,
            'stores'=>$stores,
            'count'=>$count,
        ));
    }
    
    public function actionUserLists()
    {
        $merchant_id = Yii::app()->session['merchant_id'];
        $storeC = new StoreC();
        
        $choseCities = array();
        
        if(isset($_GET['city']) && !empty($_GET['city']))
        {
            $cities = $_GET['city'];
            $provinces = $_GET['province'];
            
            foreach ($cities as $k=>$v)
            {
                $choseCities[$k]['pcode'] = $provinces[$k];
                $choseCities[$k]['pname'] = $storeC->getProvinceCityName($provinces[$k]);
                $choseCities[$k]['ccode'] = $cities[$k];
                $choseCities[$k]['cname'] = $storeC->getProvinceCityName($cities[$k]);
            }
            unset($cities);
            unset($provinces);
        }
        
        
        //设置省市区数据源
        $province = json_decode($storeC -> getProvince());
        $province = $province->data;
        
        $criteria = new CDbCriteria();
        $criteria->addCondition('merchant_id='.$merchant_id);
        $criteria->addCondition('flag='.FLAG_NO);
        
        $keyword    = isset($_GET['keyword'])?$_GET['keyword']:'';
        $grades     = isset($_GET['grade'])?$_GET['grade']:'';
        $groups     = isset($_GET['group'])?$_GET['group']:'';
        $sexes      = isset($_GET['sex'])?$_GET['sex']:'';
        $age_min    = isset($_GET['age_min'])?$_GET['age_min']:'';
        $age_max    = isset($_GET['age_max'])?$_GET['age_max']:'';
        
        $provinces  = isset($_GET['province'])?$_GET['province']:'';
        $cities     = isset($_GET['city'])?$_GET['city']:'';
       
        $alipay_status = isset($_GET['alipay_status'])?$_GET['alipay_status']:'';
        $wechat_status = isset($_GET['wechat_status'])?$_GET['wechat_status']:'';
        
        $reg_time   = isset($_GET['reg_time'])?$_GET['reg_time']:'';
        $fw_time    = isset($_GET['fw_time'])?$_GET['fw_time']:'';
        $wx_time    = isset($_GET['wx_time'])?$_GET['wx_time']:'';
        
        $score_min = isset($_GET['score_min'])?$_GET['score_min']:'';
        $score_max = isset($_GET['score_max'])?$_GET['score_max']:'';
        
        $birth = isset($_GET['birth'])?$_GET['birth']:'';
        
        $store_ids   = isset($_GET['store_id'])?$_GET['store_id']:'';
        $store_names = isset($_GET['store_name'])?$_GET['store_name']:'';
        
        //排序
        $ageOrder   = isset($_GET['sort'])?$_GET['sort']:'';
        $scoreOrder = isset($_GET['integ'])?$_GET['integ']:'';
        $focusOrder = isset($_GET['Focus'])?$_GET['Focus']:'';
        $registOrder= isset($_GET['regist'])?$_GET['regist']:'';
        $xf_tOrder  = isset($_GET['consumption'])?$_GET['consumption']:'';
        $xf_mOrder  = isset($_GET['xf_money'])?$_GET['xf_money']:'';
        
//         var_dump($keyword);
        if(!empty($keyword))
            $criteria->addCondition("nickname like '%$keyword%' or account like '%$keyword%' or name like '%$keyword%' or wechat_nickname like '%$keyword%' or alipay_nickname like '%$keyword%'");
        if(!empty($grades))
            $criteria->addInCondition('membershipgrade_id', $grades);
        if(!empty($groups))
        {
           $criteria2 = new CDbCriteria();
           $criteria2->addCondition('flag='.FLAG_NO);
           $criteria2->addInCondition('group_id', $groups);
           $getGroups = Group::model()->findAll($criteria2);
           $ids = array();
           foreach($getGroups as $v){
               $ids[] = $v['user_id'];
           }
           $criteria->addInCondition('id', $ids);
        }
        if(!empty($sexes))
            $criteria->addInCondition('IFNULL(sex, 3)', $sexes);
        
        if((!empty($age_min) || $age_min==='0') && (!empty($age_max) || $age_max==='0') && $age_max>=$age_min)
            $criteria->addBetweenCondition('year(birthday)', date('Y')-$age_max+1, date('Y')-$age_min+1);
        
        if($age_min>$age_max)
            Yii::app()->user->setFlash('age_err_tip', '前者不可大于后者！');
        
        if(!empty($alipay_status))
            $criteria->addInCondition('alipay_status', $alipay_status);
        if(!empty($wechat_status))
            $criteria->addInCondition('wechat_status', $wechat_status);
        if(!empty($reg_time))
        {
            $regist_time = explode('-', $reg_time);
            $start = date('Y-m-d'.' 00:00:00',strtotime($regist_time[0]));
            $end = date('Y-m-d'.' 23:59:59',strtotime($regist_time[1]));
            $reg_time_start = $start<$end?$start:$end;
            $reg_time_end   = $end>$start?$end:$start;
            $criteria->addBetweenCondition('regist_time', $reg_time_start, $reg_time_end);
        }
        
        if(!empty($wx_time))
        {
            $wechat_time = explode('-', $wx_time);
            $start = date('Y-m-d'.' 00:00:00',strtotime($wechat_time[0]));
            $end = date('Y-m-d'.' 23:59:59',strtotime($wechat_time[1]));
            $wechat_time_start = $start<$end?$start:$end;
            $wechat_time_end   = $end>$start?$end:$start;
            $criteria->addBetweenCondition('wechat_subscribe_time', $wechat_time_start, $wechat_time_end);
        }
        
        if(!empty($fw_time))
        {
            $alipay_time = explode('-', $fw_time);
            $start  = date('Y-m-d'.' 00:00:00',strtotime($alipay_time[0]));
            $end    = date('Y-m-d'.' 23:59:59',strtotime($alipay_time[1]));
            $alipay_time_start = $start<$end?$start:$end;
            $alipay_time_end   = $end>$start?$end:$start;
            $criteria->addBetweenCondition('alipay_subscribe_time', $alipay_time_start, $alipay_time_end);
        }
        
        //从user表获取
        if((!empty($score_min) || $score_min==='0') && (!empty($score_max) || $score_max==='0') && ($score_max>=$score_min))
            $criteria->addBetweenCondition('IFNULL(points, 0)', $score_min, $score_max);
        if($score_min>$score_max)
            Yii::app()->user->setFlash('score_err_tip', '前者不可大于后者！');
        if(!empty($birth))
        {
            $birth_time = explode('-', trim($birth));
            $birth_start = date('m-d', strtotime('1970/'.trim($birth_time[0])));
            $birth_end   = date('m-d', strtotime('1970/'.trim($birth_time[1])));
            $criteria->addBetweenCondition("DATE_FORMAT(birthday,'%m-%d')", $birth_start, $birth_end);
        }
        
        if(!empty($store_ids))
        {
            $criteria3 = new CDbCriteria();
            $criteria3->select = 'DISTINCT(user_id)';
            $criteria3->addInCondition('store_id', $store_ids);
            $orders = Order::model()->findAll($criteria3);
            $ids = array();
            foreach($orders as $order)
                $ids[] = $order['user_id'];
            $criteria->addInCondition('id', $ids);
        }
        
        //排序
        if(!empty($ageOrder))
        {
            if($ageOrder=='asc')
               $criteria->order = 'IFNULL(birthday, \'1970-01-01 00:00:00\') desc';
            if($ageOrder=='desc')
               $criteria->order = 'IFNULL(birthday, NOW()) asc';
        }
        
        if(!empty($scoreOrder))
        {
            if($scoreOrder=='asc')
                $criteria->order = 'IFNULL(points,0) asc';
            if($scoreOrder=='desc')
                $criteria->order = 'IFNULL(points,0) desc';
        }
        
        if(!empty($focusOrder))
        {
            if($focusOrder=='asc')
                $criteria->order = 'IFNULL(alipay_subscribe_time, NOW()) asc, IFNULL(wechat_subscribe_time, NOW()) asc';
            if($focusOrder=='desc')
                $criteria->order = 'alipay_subscribe_time desc , wechat_subscribe_time desc';
        }
        
        if(!empty($registOrder))
        {
            if($registOrder=='asc')
                $criteria->order = 'IFNULL(regist_time, NOW()) asc';
            if($registOrder=='desc')
                $criteria->order = 'IFNULL(regist_time, \'1970-01-01 00:00:00\') desc';
        }
        //最近一次消费时间排序
        if(!empty($xf_tOrder))
        {
            
        }
        //消费总金额排序
        if(!empty($xf_mOrder))
        {
            
        }
        //默认按照注册，关注时间降序
        if(empty($focusOrder) && empty($registOrder) && empty($scoreOrder) && empty($ageOrder))
            $criteria->order = 'create_time desc, alipay_subscribe_time desc , wechat_subscribe_time desc';
        
        $tmpOpenids  = array();
        $tmpAlipayIds= array();
        
        $criteria3 = new CDbCriteria();
        $criteria3->select = 'wechat_id, alipay_fuwu_id';
        $criteria3->addCondition("account is not null and account<>''");
        $criteria3->addCondition("(wechat_id is not null and wechat_id<>'') OR (alipay_fuwu_id is not null and alipay_fuwu_id<>'')");
        $tmpUser1 = User::model()->findAll($criteria3);
        unset($criteria3);
        
        foreach($tmpUser1 as $k=>$v)
        {
            if(!empty($v->wechat_id))
                $tmpOpenids[] = $v->wechat_id;
            if(!empty($v->alipay_fuwu_id))
                $tmpAlipayIds[] = $v->alipay_fuwu_id;
        }
        $tmpopStr = '(\''.implode('\',\'', $tmpOpenids).'\')';
        $tmpalStr = '(\''.implode('\',\'', $tmpAlipayIds).'\')';
        if(!empty($tmpAlipayIds) && !empty($tmpOpenids))
            $criteria->addCondition("((account is not null and account<>'') OR ((account is null or account ='') and wechat_id not in ".$tmpopStr.") OR ((account is null or account ='') and alipay_fuwu_id not in ".$tmpalStr."))");
        
        $count = User::model()->count($criteria);
        $pages = new CPagination(User::model()->count($criteria));
        $pages->pageSize = Yii::app() -> params['perPage'];
        $pages->applyLimit($criteria);
        
        $users = User::model()->findAll($criteria);
        
        //用户消费订单
        $tk_types = array(ORDER_STATUS_HANDLE_REFUND, ORDER_STATUS_REFUND, ORDER_STATUS_PART_REFUND);
        $xf_types = array(ORDER_STATUS_NORMAL);
        $allowed_types = array_merge($tk_types, $xf_types);
        $allowed_types_Str = "('".implode("','", $allowed_types)."')";
        $user_store_order = array();
        foreach($users as $k=>$v)
        {
            $user_id = $v->id;
            $wechat_user_id = $v->wechat_id?$v->wechat_id:-1;
            $alipay_user_id = $v->alipay_fuwu_id?$v->alipay_fuwu_id:-1;
            if($wechat_user_id || $alipay_user_id)
                $user_store_order[$v->id] = Order::model()->findAll('(user_id=:user_id or wechat_user_id=:wechat_user_id or alipay_user_id=:alipay_user_id) AND flag=:flag AND order_type=:order_type AND pay_status=:pay_status AND order_status IN '.$allowed_types_Str.' order by create_time desc', array(':user_id'=>$user_id, ':wechat_user_id'=>$wechat_user_id, ':alipay_user_id'=>$alipay_user_id, ':flag'=>FLAG_NO, ':order_type'=>ORDER_TYPE_CASHIER, ':pay_status'=>ORDER_STATUS_PAID));
        }
        
        $user_groups = UserGroup::model()->findAll('merchant_id='.$merchant_id.' and flag='.FLAG_NO);
        $user_grades = UserGrade::model()->findAll('merchant_id='.$merchant_id.' and flag='.FLAG_NO);
        $this -> render('userLists', array(
            'users'=>$users,
            'user_store_order'=>$user_store_order,
            'pages'=>$pages,
            'user_groups'=>$user_groups,
            'user_grades'=>$user_grades,
            'province'=>$province,
            'count'=>$count,
            'choseCities'=>$choseCities,
            'store_ids'=>$store_ids,
            'store_names'=>$store_names,
            
        ));
    }
    
    /**
     * 用户管理
     */
    public function actionManageList()
    {        
        $store = new StoreC();
        //设置省市区数据源
        $province = json_decode($store -> getProvince());
        $province = $province->data;
        $city = array();
        $area = array();
        //设置市列表
        if (!empty($_GET['province'])) {
                $city = json_decode($store -> getCity($_GET['province']));
                $city = $city->data;
        }
        $client = '';
        $group = '';
        $groups = '';
        $grade = '';
        $sex = '';
        $keyword = '';
        $users = '';
        $operation = '';
        $target = '';
        $time = '';
        $time1 = '';
        $time2 = '';
        $fans = '';
        $liveplaceProvince = '';
        $liveplaceCity = '';
        $sort = '';
        $Focus = '';
        $integral = '';
        $consumption = '';
        $Ability = '';
        $age_min = '';
        $age_max = '';
        $unknown = '';
        if(isset($_GET['age_min']) && !empty($_GET['age_min']) && isset($_GET['age_max']) && !empty($_GET['age_max']) || ($_GET['age_min']==0 && isset($_GET['age_max']) && !empty($_GET['age_max']))){            
            $birth_min = date('Y') - $_GET['age_max']+1;
            $birth_max = date('Y') - $_GET['age_min']+1;
        } else { }
        if(isset($_GET['Ability']) && !empty($_GET['Ability'])){
            $Ability = $_GET['Ability'];
        }
        if(isset($_GET['consumption']) && !empty($_GET['consumption'])){
            $consumption = $_GET['consumption'];
        }
        if(isset($_GET['integ']) && !empty($_GET['integ'])){
            $integral = $_GET['integ'];
        }
        if(isset($_GET['Focus']) && !empty($_GET['Focus'])){
            $Focus = $_GET['Focus'];
        }
        if(isset($_GET['sort']) && !empty($_GET['sort'])){
            $sort = $_GET['sort'];
        }
        if(isset($_GET['province']) && !empty($_GET['province'])){
            $liveplaceProvince = $_GET['province'];            
        }
        if(isset($_GET['city']) && !empty($_GET['city'])){
            $liveplaceCity = $_GET['city'];
        }
//         if(isset($_GET['fans']) && !empty($_GET['fans'])){
//             $fans = $_GET['fans'];
//         }
        if(isset($_GET['Time']) && !empty($_GET['Time'])){
            $time= $_GET['Time'];
        }
        if(isset($_GET['Time1']) && !empty($_GET['Time1'])){
            $time1= $_GET['Time1'];
        }
        if(isset($_GET['Time2']) && !empty($_GET['Time2'])){
            $time2= $_GET['Time2'];
        }
        if(isset($_GET['sex']) && !empty($_GET['sex'])){
            $sex = array();
            foreach($_GET['sex'] as $v){
                $sex[] = $v;
            }            
        }
        if(isset($_GET['unknown']) && !empty($_GET['unknown'])){
            $unknown = $_GET['unknown'];
        }
        if (isset($_GET['clients']) && !empty($_GET['clients'])) {
            $client = array();
            foreach($_GET['clients'] as $v) { 
                $client[] = $v;
            }                
        }
        if (isset($_GET['group']) && !empty($_GET['group'])) {
                $group = $_GET['group'];
        }else {
                $group = 'all-'.USER_GROUP_ALL; //分组默认全部会员
        }
        if (isset($_GET['groups']) && !empty($_GET['groups'])) {
            $groups = array();
            foreach($_GET['groups'] as $v){
                $groups[] = $v;
            }            
        }
        if (isset($_GET['grade']) && !empty($_GET['grade'])) {
            $grade = array();
            foreach($_GET['grade'] as $v){
                $grade[] = $v;
            }            
        }
        if (isset($_GET['keyword']) && !empty($_GET['keyword'])) {
                $keyword = $_GET['keyword'];
        }
        if (isset($_GET['user']) && !empty($_GET['user'])) {
                $users = $_GET['user'];
        }
        if (isset($_GET['operation']) && !empty($_GET['operation'])) {
                $operation = $_GET['operation'];
        }
        if (isset($_GET['target']) && !empty($_GET['target'])) {
                $target = $_GET['target'];
        }
        if (isset($_GET['wechat_status']) && !empty($_GET['wechat_status'])) {
                $wechat_status = $_GET['wechat_status'];
        }else{
            $wechat_status = array();
        }
        if (isset($_GET['alipay_status']) && !empty($_GET['alipay_status'])) {
                $alipay_status = $_GET['alipay_status'];
        }else{
            $alipay_status = array();
        }
        
//                var_dump($wechat_status);
//                var_dump($alipay_status);
        
        $merchant_id = Yii::app()->session['merchant_id']; //商户id
        $user = new UserC();

        //是否进行会员操作（添加/移动）
        if ($user && $operation && $target) {
//             var_dump($target);exit;
                $ret = $user->regroupUser($merchant_id, $operation, $users, $group, $target);
                
                $result = json_decode($ret, true);
                if ($result['status'] != ERROR_NONE) {
                        Yii::app()->user->setFlash('error', $result['errMsg']);
                }
        }

        //获取分组的会员列表
        
        $ret = $user->getGroupManageList($merchant_id, $client, $groups, $keyword, $sex, $grade, $time, $time1, $time2, $fans, $liveplaceProvince, $liveplaceCity, $sort, $Focus, $integral, $consumption, $Ability, $birth_min, $birth_max, $unknown, $wechat_status, $alipay_status);

        $result = json_decode($ret, true);
        if ($result['status'] == ERROR_NONE) {
                $user_list = $result['data']['list'];   
                $count = $result['count'];
        }else {
                $user_list = array();
                $count = 0;
        }

        //获取所有分组及人数
        $ret1 = $user->getManageList($merchant_id);
        $result1 = json_decode($ret1, true);
        if ($result1['status'] == ERROR_NONE) {
            $group_list = $result1['data']['grade'];
            $group_pu = $result1['data']['group'];
            if(empty($group_list)){
                $group_list = array();
            }
            if(empty($group_pu)){
                $group_pu = array();
            }
        }else {
            $group_list = array();
            $group_pu = array();
        }

        //获取当前分组名称
        $group_name = '';
//        $ret2 = $user->getGroupName($merchant_id, $group);
//        $result2 = json_decode($ret2, true);
//        if ($result2['status'] == ERROR_NONE) {
//                $group_name = $result2['data']['name'];
//        }
        //获取添加分组列表和移动分组列表
        $ret3 = $user->getOperationGroupList($merchant_id, $group);
        $result3 = json_decode($ret3, true);
        if ($result3['status'] == ERROR_NONE) {
                $add = $result3['data']['add'];
//                $move = $result3['data']['move'];
        }else {
                $add = array();
//                $move = array();
        }
        //移动分组列表
        $ret4 = $user ->getOperationGradeList($merchant_id);
        $result4 = json_decode($ret4, true);
        if ($result4['status'] == ERROR_NONE) {
            $move = $result4['data']['move'];
        } else {
            $move = array();
        }
        
        //获取标签
        $ret5 = $user -> getUserTag($merchant_id);
        $result5 = json_decode($ret5,true);
        if($result5['status'] == ERROR_NONE){
            $tag = $result5['data']['list'];
        } else {
            $tag = array();
        }
        $this -> render('manageList', array(
                'list' => $user_list, 
                'add' => $add, 
                'move' => $move, 
                'group_name' => $group_name, 
                'group_list' => $group_list,
                'group_pu' => $group_pu, 
                'pages' => $user->page, 
                'province'=>$province,
                'city'=>$city,
                'area'=>$area,
                'tag'=>$tag,
                'count'=>$count
        ));
    }
    
    /**
     * 把用户添加到分组
     */
    public function actionAddtoGroup()
    {
         $userStr   = $_POST['users'];
         $groupStr = $_POST['groups'];
         $groups    = explode(',', $groupStr);
         
         $merchant_id = Yii::app()->session['merchant_id']; //商户id
         $user = new UserC();
         
         //如果是一个人则覆盖操作
         if(substr_count($userStr, ',')<1){
            $db = Yii::app()->db;
            $sql = "delete from wq_group where user_id=".$userStr;
            $command = $db->createCommand($sql);
            $res = $command->query();
         }
         
         foreach ($groups as $group){
             //重用 注意参数
             $ret = $user->regroupUser($merchant_id, "add", $userStr, 'all-ALL', 'custom-'.$group);
             $result = json_decode($ret, true);
             if ($result['status'] != ERROR_NONE) {
                 Yii::app()->user->setFlash('error', $result['errMsg']);
             }
         }
         echo "<script>alert('添加到分组成功');history.go(-1);</script>";
    }
    
    public function actionGetOneUserGroup()
    {
//         echo 'group of user:'.$_GET['uid'];
        $db = Yii::app()->db;
        $merchant_id = Yii::app()->session['merchant_id']; //商户id
        $sql = "select g.group_id as group_id from wq_group as g, wq_user_group as ug"." where g.group_id=ug.id and ug.merchant_id=".$merchant_id." and g.user_id=".$_GET['uid'];
        $command = $db->createCommand($sql);
        $res = $command->queryAll();
        $group = array();
        foreach ($res as $r){
            $group[] = $r['group_id'];
        }
        echo json_encode($group);
    }
    
    public function actionGetOneUserTag()
    {
//         echo 'tag of user:'.$_GET['uid'];
        $db = Yii::app()->db;
        $merchant_id = Yii::app()->session['merchant_id']; //商户id
        $sql = "SELECT tag_value FROM wq_user_tag WHERE user_id=".$_GET['uid'];
        $command = $db->createCommand($sql);
        $res = $command->queryAll();
        $tag = array();
        foreach ($res as $r){
            $tag[] = $r['tag_value'];
        }
        echo json_encode($tag);
    }
    
    /**
     * 给用户打标签
     */
    public function actionAddTag()
    {
        $userc = new UserC();
        $userStr    = $_POST['users'];
        $tagStr    = $_POST['tags'];
        $users      = explode(',', $userStr);
        $tags       = explode(',', $tagStr);
//         print_r($tags);print_r($users);
/* 
 * $user = new UserC();
        $tag_value = array();
        if($_POST){
            $user_id = $_GET['id'];
            if(!empty($_POST['tag']) && $_POST['tag']){
                foreach($_POST['tag'] as $v){
                    $tag_value[] = $v;
                }
            }
            $ret = $user->UserTag($user_id, $tag_value);
            $result = json_decode($ret,true);
            if($result['status'] == ERROR_NONE){
                $this->redirect($_GET['goUrl']);
            }
        }
 *  */
        if(substr_count($userStr, ',')<1){
            $db = Yii::app()->db;
            $sql = "delete from wq_user_tag where user_id=".$userStr;
            $command = $db->createCommand($sql);
            $res = $command->query();
        }
        foreach ($users as $v)
        {
            $ret = $userc->UserTag($v, $tags);
            $result = json_decode($ret,true);
            if($result['status'] == ERROR_NONE){
//                 $this->redirect($_GET['goUrl']);
                echo "<script>self.history.go(-1);</script>";
            }
        }
        echo "<script>alert('打标签成功');history.go(-1);</script>";      
    }
    /**
     * 用户详情
     */
    public function actionUserDetail()
    {
        $detail = '';
        $id = isset($_GET['id']) ? $_GET['id'] : '';
        $merchantId = Yii::app()->session['merchant_id'];
        $ret = new UserC();
        $rat = $ret->UserDetail($merchantId,$id);
        $detail = json_decode($rat,true);            
        if($detail['status'] == ERROR_NONE)
        {
            $detail = $detail['data'];
        }
        $assign['detail'] = $detail;
        $assign['pages']  = $ret -> page;
        $assign['page1'] = $ret -> page1;
        $assign['page2']  = $ret -> page2;
        $this -> render('userDetail',$assign);
    }
    
    public function actionUserInfo()
    {
        
        $user_login_clients = $GLOBALS['__USER_LOGIN_CLIENT'];
        $merchant_id = Yii::app()->session['merchant_id'];
        $user_id = isset($_GET['id'])?$_GET['id']:'';
        
        if(empty($user_id))
            exit('非法访问！');
        
        $criteria = new CDbCriteria();
                
        $user = User::model()->findByPk($user_id);
        if(empty($user))
            exit('该用户不存在！');
        
        $time_line = array();
        $tmpTimesArr  = array();
        $tmpThingsArr = array();
        
        if(!empty($user->growupRecord))
        {
            foreach($user->growupRecord as $record)
            {
                $tmpTimesArr[] = $record->create_time;
                $tmpThingsArr[]= $record->user_grade_name;
            }
        }
        if(!empty($user->regist_time))
        {
           $tmpTimesArr[] = $user->regist_time;
           $tmpThingsArr[]= '注册成为会员';
        } 
        
        if(!empty($user->alipay_subscribe_time))
        {
            $tmpTimesArr[] = $user->alipay_subscribe_time;
            $tmpThingsArr[]= '关注服务窗';
        }
        
        if(!empty($user->wechat_subscribe_time))
        {
            $tmpTimesArr[] = $user->wechat_subscribe_time;
            $tmpThingsArr[]= '关注微信公众号';
        }
        
        if(!empty($user->order))
        {
            $tmpTimesArr[] = $user->order[count($user->order)-1]['create_time'];
            $tmpThingsArr[]= '首次购物';
        }
        arsort($tmpTimesArr);
        $things = array();
        $times  = array();
        foreach($tmpTimesArr as $k=>$v)
        {
            $times[] = $v;
            $things[]= $tmpThingsArr[$k];
        }
        
        $i = 0; //计数器
        foreach($times as $k=>$v)
        {
            $key = date('Y', strtotime($v));
            $time_line[$key][$i]['time'] = $v;
            $time_line[$key][$i]['thing']= $things[$k];
            $i++;
        }
        
        //门店消费记录
        $tk_types = array(ORDER_STATUS_HANDLE_REFUND, ORDER_STATUS_REFUND, ORDER_STATUS_PART_REFUND);
        $xf_types = array(ORDER_STATUS_NORMAL);
        $allowed_types = array_merge($tk_types, $xf_types);
        $allowed_types_Str = "('".implode("','", $allowed_types)."')";
        $wechat_user_id = $user->wechat_id;
        $alipay_user_id = $user->alipay_fuwu_id;
        $user_store_orders = Order::model()->findAll('(user_id=:user_id or wechat_user_id=:wechat_user_id or alipay_user_id=:alipay_user_id) AND flag=:flag AND order_type=:order_type AND pay_status=:pay_status AND order_status IN '.$allowed_types_Str.' order by create_time asc', array(':user_id'=>$user_id, ':wechat_user_id'=>$wechat_user_id, ':alipay_user_id'=>$alipay_user_id, ':flag'=>FLAG_NO, ':order_type'=>ORDER_TYPE_CASHIER, ':pay_status'=>ORDER_STATUS_PAID));
        //累计门店消费金额
        $total_store_xf_money = 0;
        //消费次数
        $total_store_xf_num = 0;
        foreach($user_store_orders as $v)
        {
            if($v->order_status == ORDER_STATUS_NORMAL)
            {
                $shishou = $v->order_paymoney - $v->coupons_money - $v->discount_money - $v->merchant_discount_money;
                $total_store_xf_money += $shishou;
                $total_store_xf_num++;
            }
        }
        
        //累计获得积分
        $user_total_get_score = 0;
        if(!empty($user->score))
        {
            foreach($user->score as $v)
            {
                if($v->balance_of_payments == POINT_PAYMENT_PAY)
                    $user_total_get_score += $v->points;
                if($v->balance_of_payments == BALANCE_OF_PAYMENTS_REFUND)
                    $user_total_get_score -= $v->points;
            }
        }
        
        $this -> render('userInfo',array(
            'user'=>$user,
            'user_login_clients'=>$user_login_clients,
            'total_store_xf_money'=>$total_store_xf_money,
            'total_store_xf_num'=>$total_store_xf_num,
            'user_total_get_score'=>$user_total_get_score,
            'time_line'=>$time_line,
        ));
    }
    
    public function actionUserExpenses()
    {
        //退款，消费（门店）
        $tk_types = array(ORDER_STATUS_HANDLE_REFUND, ORDER_STATUS_REFUND, ORDER_STATUS_PART_REFUND);
        $xf_types = array(ORDER_STATUS_NORMAL);
        $allowed_types = array_merge($tk_types, $xf_types);
        
        $merchant_id = Yii::app()->session['merchant_id'];
        $user_id = isset($_GET['id'])?$_GET['id']:'';
        if(empty($user_id))
           exit('非法访问！');
        
        $user = User::model()->findByPk($user_id);
        
        $pay_time = isset($_GET['pay_time'])?$_GET['pay_time']:'';
        $store_id = isset($_GET['store_id'])?$_GET['store_id']:'';
        $pay_channel  = isset($_GET['pay_channel'])?$_GET['pay_channel']:'';
        $order_status = isset($_GET['order_status'])?$_GET['order_status']:'';
        
        $criteria = new CDbCriteria();
        $criteria->addCondition('order_type=:order_type AND pay_status=:pay_status AND (user_id=:user_id or wechat_user_id=:wechat_user_id or alipay_user_id=:alipay_user_id)');
        $criteria->addInCondition('order_status', $allowed_types);
        $criteria->params[':order_type'] = ORDER_TYPE_CASHIER;
        $criteria->params[':user_id'] = $user_id;
        $wechat_user_id = $user->wechat_id?$user->wechat_id:-1;
        $alipay_user_id = $user->alipay_fuwu_id?$user->alipay_fuwu_id:-1;
        $criteria->params[':wechat_user_id'] = $wechat_user_id;
        $criteria->params[':alipay_user_id'] = $alipay_user_id;
        $criteria->params[':pay_status'] = ORDER_STATUS_PAID;
        
        if(!empty($pay_time))
        {
            $pay_time_arr = explode('-', $pay_time);
            $pay_time_start = date('Y-m-d'.' 00:00:00', strtotime($pay_time_arr[0]));
            $pay_time_end   = date('Y-m-d'.' 23:59:59', strtotime($pay_time_arr[1]));
            $criteria->addBetweenCondition('pay_time', $pay_time_start, $pay_time_end);
        }
        if(!empty($store_id))
        {
            $criteria->addCondition('store_id=:store_id');
            $criteria->params[':store_id'] = $store_id;
        }
        
        if(!empty($order_status))
        {
            if($order_status==1)
                $criteria->addInCondition('order_status', $tk_types);
            if($order_status==2)
                $criteria->addInCondition('order_status', $xf_types);
        }
        
        if(!empty($pay_channel))
        {
            $criteria->addCondition('pay_channel=:pay_channel');
            $criteria->params[':pay_channel'] = $pay_channel;
        }
        
        $criteria->order = 'create_time desc, last_time desc';
        
        $pages = new CPagination(Order::model()->count($criteria));
        $pages->pageSize = Yii::app()->params['perPage'];
        $pages->applyLimit($criteria);
        $orders = Order::model()->findAll($criteria);
        
        $criteria2 = new CDbCriteria();
        $criteria2->addCondition('merchant_id=:merchant_id and flag=:flag');
        $criteria2->params[':merchant_id'] = $merchant_id;
        $criteria2->params[':flag'] = FLAG_NO;
        $stores = Store::model()->findAll($criteria2);
        
        //门店消费记录
        $allowed_types_Str = "('".implode("','", $allowed_types)."')";
        $user_store_orders = Order::model()->findAll('(user_id=:user_id or wechat_user_id=:wechat_user_id or alipay_user_id=:alipay_user_id) AND flag=:flag AND order_type=:order_type AND pay_status=:pay_status AND order_status IN '.$allowed_types_Str.' order by create_time asc', array(':user_id'=>$user_id, ':wechat_user_id'=>$wechat_user_id, ':alipay_user_id'=>$alipay_user_id, ':flag'=>FLAG_NO, ':order_type'=>ORDER_TYPE_CASHIER, ':pay_status'=>ORDER_STATUS_PAID));
        $user_xf_total_money = 0;
        $user_xf_total_num = 0;
        $user_xf_first_time = '未消费';
        if(!empty($user_store_orders))
            $user_xf_first_time = $user_store_orders[0]['create_time'];
        
        if(!empty($user_store_orders))
        {
            foreach ($user_store_orders as $order)
            {
                if($order->order_status == ORDER_STATUS_NORMAL)
                {
                    $shishou = $order->order_paymoney - $order->coupons_money - $order->discount_money - $order->merchant_discount_money;
                    $user_xf_total_money += $shishou;
                    $user_xf_total_num++;
                }
            }
        }
        
        
        $order_statuses = $GLOBALS['ORDER_STATUS'];
        $pay_channels  = $GLOBALS['ORDER_PAY_CHANNEL'];
        
        $this->render('user_expenses', array(
            'user_store_orders'=>$user_store_orders,
            'user_xf_total_money'=>$user_xf_total_money,
            'user_xf_total_num'=>$user_xf_total_num,
            'user_xf_first_time'=>$user_xf_first_time,
            'orders'=>$orders,
            'stores'=>$stores,
            'order_statuses'=>$order_statuses,
            'tk_types'=>$tk_types,
            'xf_types'=>$xf_types,
            'pay_channels'=>$pay_channels,
            'pages'=>$pages,
        ));
    }
    
    public function actionUserPoints()
    {
        $merchant_id = Yii::app()->session['merchant_id'];
        $user_id = isset($_GET['id'])?$_GET['id']:'';
        $point_time = isset($_GET['point_time'])?$_GET['point_time']:'';
        $point_type = isset($_GET['point_type'])?$_GET['point_type']:'';
        $point_from = isset($_GET['point_from'])?$_GET['point_from']:'';
        
        $user = User::model()->findByPk($user_id);
        
        //数据合并
        $user_wechat_user_id = $user->wechat_id?$user->wechat_id:-1;
        $user_alipay_user_id = $user->alipay_fuwu_id?$user->alipay_fuwu_id:-1;
        $users = User::model()->findAll('id='.$user_id.' OR wechat_id=\''.$user_wechat_user_id.'\' OR alipay_fuwu_id=\''.$user_alipay_user_id.'\'');
//         var_dump($users);
        $uids = array();
        foreach ($users as $v)
        {
            $uids[] = $v->id;
        }
        $inStr = "('".implode("','", $uids)."')";
        
        if(empty($user_id))
           exit('非法访问！');
        
        
        $criteria = new CDbCriteria();
        $criteria->addInCondition('user_id', $uids);
        
        if(!empty($point_time))
        {
            $point_time_arr   = explode('-', $point_time);
            $point_time_start = date('Y-m-d 00:00:00', strtotime($point_time_arr[0]));
            $point_time_end   = date('Y-m-d 23:59:59', strtotime($point_time_arr[1]));
            $criteria->addBetweenCondition('last_time', $point_time_start, $point_time_end);
        }
        if(!empty($point_type))
        {
            $criteria->addCondition('balance_of_payments=:point_type');
            $criteria->params[':point_type'] = $point_type;    
        }
        if(!empty($point_from))
        {
            $criteria->addCondition('`from`=:from');
            $criteria->params[':from'] = $point_from;
        }
        
        $criteria->order = 'last_time desc, create_time desc';
        
        $pages = new CPagination(UserPointsdetail::model()->count($criteria));
        $pages->pageSize = Yii::app()->params['perPage'];
        $pages->applyLimit($criteria);
        
        $pointDetailes = UserPointsdetail::model()->findAll($criteria);
        
        $point_types = $GLOBALS['__BALANCE_OF_PAYMENTS'];
        $point_froms = $GLOBALS['__USER_POINTS_DETAIL_FROM'];
        
        $pointAllDetailes = UserPointsdetail::model()->findAll('user_id IN '.$inStr);
        
        $total_get_points = 0;
        foreach($pointAllDetailes as $k=>$v)
        {
            if($v->balance_of_payments == BALANCE_OF_PAYMENTS_INCOME)
                $total_get_points += $v->points;
            if($v->balance_of_payments == BALANCE_OF_PAYMENTS_REFUND)
                $total_get_points -= $v->points;
        }
        
        $this->render('user_points',array(
            'user'=>$user,
            'pointDetailes' => $pointDetailes,
            'total_get_points'=>$total_get_points,
            'point_types' => $point_types,
            'point_froms' => $point_froms,
            'pages' => $pages,
        ));
    }
    
    public function actionUserStored()
    {
        $merchant_id = Yii::app()->session['merchant_id'];
        $user_id = isset($_GET['id'])?$_GET['id']:'';
        if(empty($user_id))
            exit('非法访问！');
        
        $user = User::model()->findByPk($user_id);
        
        //数据合并
        $user_wechat_user_id = $user->wechat_id?$user->wechat_id:-1;
        $user_alipay_user_id = $user->alipay_fuwu_id?$user->alipay_fuwu_id:-1;
        $users = User::model()->findAll('id='.$user_id.' OR wechat_id=\''.$user_wechat_user_id.'\' OR alipay_fuwu_id=\''.$user_alipay_user_id.'\'');
//                 var_dump($users);
        $uids = array();
        foreach ($users as $v)
        {
            $uids[] = $v->id;
        }
        $inStr = "('".implode("','", $uids)."')";
        
        $stored_time = isset($_GET['stored_time'])?$_GET['stored_time']:'';
        $criteria = new CDbCriteria();
        if(!empty($stored_time))
        {
            $stored_time_arr = explode('-', $stored_time);
            $stored_time_start = date('Y-m-d 00:00:00', strtotime($stored_time_arr[0]));
            $stored_time_end = date('Y-m-d 23:59:59', strtotime($stored_time_arr[1]));
            $criteria->addBetweenCondition('pay_time', $stored_time_start, $stored_time_end);
        }
        
        $criteria->addInCondition('user_id', $uids);
        
        //获取当前商户储值活动
        $storeds = Stored::model()->findAll('merchant_id='.$merchant_id.' and flag='.FLAG_NO);
        $stored_ids = array();
        foreach($storeds as $v)
        {
            $stored_ids[] = $v->id;
        }
        $criteria->addInCondition('stored_id', $stored_ids);
        
        $criteria->addCondition('flag=:flag');
        $criteria->params[':flag'] = FLAG_NO;
        $criteria->addCondition('pay_status='.ORDER_STATUS_PAID);
        $criteria->order = 'create_time desc, last_time desc, pay_time desc';
        
        $pages = new CPagination(StoredOrder::model()->count($criteria));
        $pages->pageSize = Yii::app()->params['perPage'];
        $pages->applyLimit($criteria);
        
        $order_status = $GLOBALS['ORDER_STATUS'];
        
        $stored_lists = StoredOrder::model()->findAll($criteria);
        
        //当前用户所有储值记录，累计储值
        $all_stored_lists = StoredOrder::model()->findAll('user_id IN'.$inStr.' AND pay_status='.ORDER_STATUS_PAID.' AND flag='.FLAG_NO);
        
        $total_money = 0;
        $total_money_leiji = 0;
        $total_money_shishou = 0;
        if(!empty($all_stored_lists))
        {
            foreach($all_stored_lists as $item)
            {
                if($item->order_status == ORDER_STATUS_NORMAL)
                {
                    $total_money += $item->num*$item->stored->get_money;
                    $total_money_shishou += $item->num*$item->stored->stored_money;
                }
            }
        }
        $total_money_leiji = $total_money+$total_money_shishou;
        
        $this->render('user_stored', array(
            'stored_lists'=>$stored_lists,
            'all_stored_lists'=>$all_stored_lists,
            'total_money_shishou'=>$total_money_shishou,
            'total_money_leiji'=>$total_money_leiji,
            'user'=>$user,
//             'total_stored'=>$total_stored,
            'pages'=>$pages,
            'order_status'=>$order_status,
        ));
    }
    
    public function actionUserPurchase()
    {
        $user_id = isset($_GET['id'])?$_GET['id']:'';
        $merchant_id = Yii::app()->session['merchant_id'];
        if(empty($user_id))
            exit('非法访问');
        
        $user = User::model()->findByPk($user_id);
        $wechat_user_id = $user->wechat_id;
        $alipay_user_id = $user->alipay_fuwu_id;
        $criteria = new CDbCriteria();
        $criteria->addInCondition('order_type', array(ORDER_TYPE_OBJECT, ORDER_TYPE_VIRTUAL));
        $criteria->addCondition('flag='.FLAG_NO);
        if(!empty($wechat_user_id) || !empty($alipay_user_id))
        {
            $criteria->addCondition('user_id=:user_id or wechat_user_id=:wechat_user_id or alipay_user_id=:alipay_user_id');
            $criteria->params[':user_id'] = $user_id;
            $criteria->params[':wechat_user_id'] = $wechat_user_id;
            $criteria->params[':alipay_user_id'] = $alipay_user_id;
        }
            
        $user_shop_orders = Order::model()->findAll($criteria);
        $terminal_types = $GLOBALS['__TERMINAL_TYPE_POS'];
        $this->render('user_purchase', array(
            'user'=>$user,
            'user_shop_orders'=>$user_shop_orders,
            'terminal_types'=>$terminal_types,
        ));
    }
    
    /**
     * 添加标签
     */
    public function actionUserTag()
    {
        $user = new UserC();
        $tag_value = array();
        if($_POST){
            $user_id = $_GET['id'];
            if(!empty($_POST['tag']) && $_POST['tag']){
                foreach($_POST['tag'] as $v){
                    $tag_value[] = $v;
                }
            }
            $ret = $user->UserTag($user_id, $tag_value);
            $result = json_decode($ret,true);
            if($result['status'] == ERROR_NONE){
                $this->redirect($_GET['goUrl']);
            }
        }
    }
    public function actionSubmit() {
        $this->render('submit');
    }
    
    private function get($param='')
    {
        if($param)
            return isset($_GET[$param])?$_GET[$param]:'';
        else
            return $_GET;
    }
    
    private function post($param='') {
        if($param)
            return isset($_POST[$param])?$_POST[$param]:'';
        else 
            return $_POST;
    }
}