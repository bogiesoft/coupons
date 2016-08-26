<?php

/**
 * 营销管理控制器
 * 姚小磊
 * 2016/7/15
 */
class MarketingController extends marketingSController
{
    /*
    * 营销活动列表
    */
    public function actionMarketingList()
    {
        $list = array();
        $marketingClass = new MarketingClass();
        $merchant_id = Yii::app()->session['merchant_id'];

        //名称搜索
        $activity_name = null;
        if (isset($_GET['name']) && !empty($_GET['name'])) {
            $activity_name = $_GET['name'];
        }
        //活动类型搜索
        $type = null;
        if (isset($_GET['type']) && !empty($_GET['type'])) {
            $type = $_GET['type'];
        }
        //活动状态搜索
        $status = null;
        if (isset($_GET['status']) && !empty($_GET['status'])) {
            $status = $_GET['status'];
        }
        $res = $marketingClass->getMarketingActivityList($merchant_id, $activity_name, $type, $status);
        $res = json_decode($res, true);
        if ($res ['status'] == ERROR_NONE) {
            $list = $res ['data']['list'] ;
        }
        foreach($list as $k=>$v){
            //一次性活动   伪进行中（未开始状态） 开始时间为当天  状态显示为进行中   开始时间小于当天时间  状态改为停止
            if($v['status'] == MARKETING_ACTIVITY_STATUS_NOSTART && $v['time_type'] == MARKETING_ACTIVITY_TIME_TYPE_ONETIME){
                if(date('Ymd',$v['start_time']) == date('Ymd',time())){
                    $list[$k]['status'] = MARKETING_ACTIVITY_STATUS_START;
                }}
            //周期性活动   伪进行中（未开始状态） 当天时间在开始时间和结束时间之间（包含开始和结束的那两天） 状态显示为进行中
            //                                当天时间大于结束时间                                  状态显示为已结束
            elseif($v['status'] == MARKETING_ACTIVITY_STATUS_NOSTART && $v['time_type'] == MARKETING_ACTIVITY_TIME_TYPE_PERIODICITY){
                if(date('Ymd',time()) >= date('Ymd',$v['start_time']) && date('Ymd',time()) <= date('Ymd',$v['end_time'])){
                    $list[$k]['status'] = MARKETING_ACTIVITY_STATUS_START;
                }
            }
        }
        $this->render('marketingList', array(
            'list' => $list,
            'pages' => $marketingClass->page
        ));
    }

    /**
     * 删除活动操作
     */
    public function actionDelMarketingActivity()
    {
        $id = isset($_GET['id']) && !empty($_GET['id']) ? $_GET['id'] : '';
        $marketingClass = new MarketingClass();

        $res = $marketingClass->delMarketingActivity($id);
        $res = json_decode($res, true);
        if ($res ['status'] == ERROR_NONE) {
            $this->redirect(array('marketingList'));
        }
    }

    /**
     * 改变活动状态操作
     * $id                                     活动id
     * $type                                 活动类型
     * $status                                 活动状态
     */
    public function actionEditMarketingActivityStatus($ma_id, $status)
    {
        $marketingClass = new MarketingClass();
        $res = $marketingClass->editMarketingActivityStatus($ma_id, $status);
        echo $res;
    }

    /**
     * 判断该活动类型是否已存在
     * $ma_id                             活动id
     */
    public function actionIfMarketingActivity($ma_id)
    {
        $marketingClass = new MarketingClass();
        $res = $marketingClass->ifMarketingActivity($ma_id);
        echo $res;
    }
    //所有基础营销活动的编辑
    public function actionEditMarketingActivity()
    {
        $marketing_id = $_GET['id'];
        $marketing_type = $_GET['type'];

        //精准营销
        if ($marketing_type == MARKETING_ACTIVITY_TYPE_BASICS_JZMA) {
            $this->redirect(array('MarketingPrecisionAdd', 'marketing_id' => $marketing_id));
        }
        //首次关注
        if ($marketing_type == MARKETING_ACTIVITY_TYPE_BASICS_SCGZMA) {
            $this->redirect(array('MarketingNewCustomer/FirstConcern', 'marketing_id' => $marketing_id));
        }
        //新会员
        if ($marketing_type == MARKETING_ACTIVITY_TYPE_BASICS_XHYMA) {
            $this->redirect(array('MarketingNewCustomer/NewMember', 'marketing_id' => $marketing_id));
        }
        //首次支付
        if ($marketing_type == MARKETING_ACTIVITY_TYPE_BASICS_SCZFMA) {
            $this->redirect(array('MarketingNewCustomer/FirstPay', 'marketing_id' => $marketing_id));
        }
        //流失客户
        if ($marketing_type == MARKETING_ACTIVITY_TYPE_BASICS_LSKHMA) {
            $this->redirect(array('MarketingOldCustomer/LossingCustomer', 'marketing_id' => $marketing_id));
        }
        //老会员
        if ($marketing_type == MARKETING_ACTIVITY_TYPE_BASICS_LHYMA) {
            $this->redirect(array('MarketingOldCustomer/OldMember', 'marketing_id' => $marketing_id));
        }
        //活跃客户
        if ($marketing_type == MARKETING_ACTIVITY_TYPE_BASICS_HYKHMA) {
            $this->redirect(array('MarketingOldCustomer/ActiveMember', 'marketing_id' => $marketing_id));
        }
    }

    /**
     * 创建活动总界面
     */
    public function actionCreateActivity()
    {
        $this->render('createActivity');
    }

    /**
     * 基础营销详情（非精准营销）
     */
    public function actionMarketingDetailElse()
    {
        $marketing_id = $_GET['marketing_id'];

        $marketingClass = new MarketingClass();
        $marketing_info = json_decode($marketingClass->getMarketingInfoById($marketing_id),true);
        $type =  $marketing_info['type'];
        if($type == MARKETING_ACTIVITY_TYPE_BASICS_XHYMA ||
        $type == MARKETING_ACTIVITY_TYPE_BASICS_LSKHMA ||
		$type == MARKETING_ACTIVITY_TYPE_BASICS_LHYMA ||
		$type == MARKETING_ACTIVITY_TYPE_BASICS_HYKHMA
        ){
            $merchant_id =  $marketing_info['merchant_id'];
            $user_info = json_decode($marketingClass->getUserInfo($type,$merchant_id),true);
        }

        $refresh_time = time();
        if(isset($user_info) && !empty($user_info)){
            $this->render('marketingDetailActive', array(
                'user_info'=>$user_info,
                'refresh_time'=>$refresh_time,
                'marketing_info' => $marketing_info
            ));
        }else{
            $this->render('marketingDetailTrigger', array(
                'refresh_time'=>$refresh_time,
                'marketing_info' => $marketing_info
            ));
        }

    }

    /**
     * 精准营销
     */
    public function actionMarketingPrecision()
    {
        //获取分组类型
        $group_types = $GLOBALS['__GROUP_TYPES'];
        //获取用户标签
        $tag_category_customer_values = $GLOBALS['__TAG_CATEGORY_CUSTOMER_VALUE'];
        $edit_status = false;
        $merchant_id = Yii::app()->session['merchant_id'];

        //查询会员等级
        $UserClass = new UserGradeClass();
        $user_grades = $UserClass->getUserGradeList($merchant_id);

        //查询门店列表
        $adminC = new AdminC();
        $manage_arr = $adminC->getManageArr($merchant_id);

        $cardCouponsC = new CardCouponsC();
        $count_store = $cardCouponsC->getCountStore($merchant_id); //商户对应的总门店数
        //获取未分组的门店
        $re = json_decode($adminC->getMerchantStoreWithoutMamagement($merchant_id));
        if ($re->status == ERROR_NONE) {
            $store_lists = $re->data;
        }

        $this->render('marketingPrecision', array(
            'edit_status' => $edit_status,
            'store_lists' => $store_lists,
            'count_store' => $count_store,
            'manage_arr' => $manage_arr,
            'user_grades' => $user_grades,
            'group_types' => $group_types,
            'tag_category_customer_values' => $tag_category_customer_values
        ));
    }

    /**
     * 选择分组弹出框
     */
    public function actionGroupDialog()
    {
        if (isset($_GET['type']) && !empty($_GET['type'])) {
            $search['type'] = $_GET['type'];
        }

        if (isset($_GET['status']) && !empty($_GET['status'])) {
            $search['status'] = $_GET['status'];
        }

        if (isset($_GET['name']) && !empty($_GET['name'])) {
            $search['name'] = $_GET ['name'];
        }

        $merchant_id = Yii::app()->session['merchant_id'];
        $groupClass = new GroupClass();
        $group_lists = json_decode($groupClass->getGroupLists($merchant_id, $search), true);
        $group_type = $GLOBALS['__GROUP_TYPES'];

        $this->render('groupDialog', array(
            'group_lists' => $group_lists,
            'pages' => $groupClass->page,
            'group_type' => $group_type
        ));
    }

    /**
     * 选择分组跳转
     */
    public function actionChooseGroup()
    {
        $group_id = $_GET['group_id'];

        $groupClass = new GroupClass();
        //获取分组信息
        $group_info = $groupClass->getGroupInfoById($group_id);

        if (!empty($group_info)) {
            //判断分组
            if ($group_info['type'] == GROUP_TYPE_STATIC || $group_info['type'] == GROUP_TYPE_CUSTOM) { //静态分组和自定义分组
                $this->redirect(array('StaticGroup',
                    'group_id' => $group_id
                ));
            } elseif ($group_info['type'] == GROUP_TYPE_DYNAMIC) {
                $this->redirect(array('DynamicGroup',
                    'group_id' => $group_id
                ));
            }
        }
    }

    /**
     * 静态分组
     */
    public function actionStaticGroup()
    {
        $group_id = $_GET['group_id'];

        $groupClass = new GroupClass();
        //查询分组信息
        $group_info = $groupClass->getGroupInfoById($group_id);

        $marketingClass = new MarketingClass();
        //查询分组正在参加的活动
        $group_marketing = $marketingClass->getGroupMarketing($group_id);

        //计算用户
        $filterUserClass = new FilterUserClass();

        //获取分组下所有用户的id
        $user_ids = $groupClass->getGroupAllUserId($group_id);

        $ret_html_array = $filterUserClass->generateHtmlJson($user_ids, '');

        $this->render('marketingStaticGroup', array(
            'group_info' => $group_info,
            'group_marketing' => $group_marketing,
            'ret_html_array' => $ret_html_array['html']
        ));
    }

    /**
     * 动态分组
     */
    public function actionDynamicGroup()
    {
        $group_id = $_GET['group_id'];
        $merchant_id = Yii::app()->session['merchant_id'];

        $groupClass = new GroupClass();
        //查询分组信息
        $group_info = $groupClass->getGroupInfoById($group_id);

        $marketingClass = new MarketingClass();
        //查询分组正在参加的活动
        $group_marketing = $marketingClass->getGroupMarketing($group_id);

        //查询会员等级
        $UserClass = new UserGradeClass();
        $user_grades = $UserClass->getUserGradeList($merchant_id);

        //获取用户标签
        $tag_category_customer_values = $GLOBALS['__TAG_CATEGORY_CUSTOMER_VALUE'];

        //计算用户
        $filterUserClass = new FilterUserClass();

        //获取分组下所有用户的id
        $user_ids = $groupClass->getGroupAllUserId($group_id);

        //生成画图
        $ret_html_array = $filterUserClass->generateHtmlJson($user_ids, '');

        $this->render('marketingDynamicGroup', array(
            'group_info' => $group_info,
            'user_grades' => $user_grades,
            'group_marketing' => $group_marketing,
            'ret_html_array' => $ret_html_array['html'],
            'filter_condition' => $group_info['filter_condition'],
            'tag_category_customer_values' => $tag_category_customer_values
        ));
    }

    /**
     * 发起精准营销
     */
    public function actionMarketingPrecisionAdd()
    {
        $marketingClass = new MarketingClass();
        $groupClass = new GroupClass();
        $couponClass = new CouponClass();

        if (isset($_GET['marketing_id']) && !empty($_GET['marketing_id'])) { //编辑
            $marketing_id = $_GET['marketing_id'];
            $marketing_info = json_decode($marketingClass->getMarketingInfoById($marketing_id),true);
            $page_flag = true; //编辑模式
        } else { //添加
            $group_id = $_GET['group_id'];

            //查询分组信息
            $group_info = $groupClass->getGroupInfoById($group_id);
            $page_flag = false; //添加模式
        }

        //查询商户是否配置了支付宝参数
        $merchant_id = Yii::app()->session['merchant_id'];
        $merchantC = new MobileMerchantC();
        $merchant = $merchantC->getMerchantInfo($merchant_id);

        if ($merchant['alipay_auth_appid'] != '' || $merchant['appid'] != '') {
            //支付宝参数已配置
            $alipay_flag = true;
        } else {
            //支付宝参数未配置
            $alipay_flag = false;
        }

        if ($_POST) {
            $post = $_POST['Marketing'];
            $edit_flag = $post['page_flag'];
            $marketing_id = $post['id'];

            $marketing_name = $post['name'];
            $marketing_group = $post['group'];
            $marketing_coupon = $post['coupon'];
            $marketing_send_channel = $post['send_channel'];
            $marketing_cover_img = $post['cover_img'];
            $marketing_img_text = $post['img_text'];
            $marketing_time_type = $post['time_type'];
            $marketing_time_send_type1 = $post['time_send_type1']; //一次性活动发送类型
            $marketing_time_send_type2 = $post['time_send_type2']; //周期活动发送类型
            $marketing_send_one_time = $post['send_one_time']; //一次性活动的定时发送
            $marketing_cyc_time = $post['cyc_time']; //周期活动的定义周期

            if (empty($marketing_name)) {
                echo "<script>alert('请输入活动名称');history.go(-1);</script>";
                exit();
            }

            if (empty($marketing_group)) {
                echo "<script>alert('请选择分组');history.go(-1);</script>";
                exit();
            }

            if (empty($marketing_coupon)) {
                echo "<script>alert('请选择活动内容');history.go(-1);</script>";
                exit();
            }

            if (empty($marketing_send_channel)) {
                echo "<script>alert('请选择赠券渠道');history.go(-1);</script>";
                exit();
            }

            if (in_array(MARKETING_ACTIVITY_SEND_CHANNEL_ALIPAY, $marketing_send_channel)) { //勾选了支付宝
                if (empty($marketing_cover_img)) {
                    echo "<script>alert('请上传封面图片');history.go(-1);</script>";
                    exit();
                }

                if (empty($marketing_img_text)) {
                    echo "<script>alert('请填写图文简介');history.go(-1);</script>";
                    exit();
                }
            }

            //获取优惠券信息
            $coupon_info = $couponClass->getCouponInfo($marketing_coupon);
            if ($coupon_info['status'] != WX_CHECK_PASS) { //优惠券在微信没有通过审核，则要判断活动说明是否填写
                if (empty($marketing_img_text)) {
                    echo "<script>alert('请填写图文简介');history.go(-1);</script>";
                    exit();
                }
            }

            $data['name'] = $marketing_name;
            $data['type'] = MARKETING_ACTIVITY_TYPE_BASICS_JZMA;
            if (!$edit_flag) { //不是编辑
                $data['group_id'] = $marketing_group;
            }
            $data['image_text_imageurl'] = $marketing_cover_img;
            $data['image_text_title'] = $marketing_img_text;
            $data['coupon_id'] = $marketing_coupon;

            if (in_array(MARKETING_ACTIVITY_SEND_CHANNEL_WECHAT, $marketing_send_channel) && !in_array(MARKETING_ACTIVITY_SEND_CHANNEL_ALIPAY, $marketing_send_channel)) { //仅微信渠道
                $data['send_channel'] = MARKETING_ACTIVITY_SEND_CHANNEL_WECHAT;
                $data['image_text_imageurl'] = '';
                if ($coupon_info['status'] == WX_CHECK_PASS) {
                    $data['image_text_title'] = '';
                }
            } elseif (!in_array(MARKETING_ACTIVITY_SEND_CHANNEL_WECHAT, $marketing_send_channel) && in_array(MARKETING_ACTIVITY_SEND_CHANNEL_ALIPAY, $marketing_send_channel)) { //仅支付宝渠道
                $data['send_channel'] = MARKETING_ACTIVITY_SEND_CHANNEL_ALIPAY;
            } elseif (in_array(MARKETING_ACTIVITY_SEND_CHANNEL_WECHAT, $marketing_send_channel) && in_array(MARKETING_ACTIVITY_SEND_CHANNEL_ALIPAY, $marketing_send_channel)) { //全部渠道
                $data['send_channel'] = MARKETING_ACTIVITY_SEND_CHANNEL_BOTH;
            }

            if ($marketing_time_type == MARKETING_ACTIVITY_TIME_TYPE_ONETIME) { //一次性活动
                $data['time_type'] = MARKETING_ACTIVITY_TIME_TYPE_ONETIME;
                if ($marketing_time_send_type1 == MARKETING_ACTIVITY_SEND_TYPE_TIMING) { //定时发送
                    if (date('Y-m-d 16:00:00', strtotime($marketing_send_one_time)) <= date('Y-m-d 16:00:00', time())) { //判断定时时间和计划任务的发送时间
                        $data['status'] = MARKETING_ACTIVITY_STATUS_START;
                    } else {
                        $data['status'] = MARKETING_ACTIVITY_STATUS_NOSTART;
                    }
                    $data['time_send_type'] = MARKETING_ACTIVITY_SEND_TYPE_TIMING;
                    $data['start_time'] = date('Y-m-d 16:00:00', strtotime($marketing_send_one_time));
                } elseif ($marketing_time_send_type1 == MARKETING_ACTIVITY_SEND_TYPE_IMMEDIATELY) { //即时发送
                    $data['time_send_type'] = MARKETING_ACTIVITY_SEND_TYPE_IMMEDIATELY;
                    $data['start_time'] = date('Y-m-d H:i:s', time());
                    $data['status'] = MARKETING_ACTIVITY_STATUS_START;
                }

                $data['end_time'] = null;
            } elseif ($marketing_time_type == MARKETING_ACTIVITY_TIME_TYPE_PERIODICITY) { //周期活动
                $data['time_type'] = MARKETING_ACTIVITY_TIME_TYPE_PERIODICITY;
                if ($marketing_time_send_type2 == MARKETING_ACTIVITY_SEND_TYPE_CRONTAB) { //定义周期
                    $times = explode(' - ', $marketing_cyc_time);
                    $start_time = $times[0];
                    $end_time = $times[1];

                    if ($start_time > date('Y-m-d', time())) { //开始时间大于当前时间
                        $data['status'] = MARKETING_ACTIVITY_STATUS_NOSTART;
                    } else {
                        $data['status'] = MARKETING_ACTIVITY_STATUS_START;
                    }

                    $data['time_send_type'] = MARKETING_ACTIVITY_SEND_TYPE_CRONTAB;
                    $data['start_time'] = date('Y-m-d 00:00:00', strtotime($start_time));
                    $data['end_time'] = date('Y-m-d 23:59:59', strtotime($end_time));
                } elseif ($marketing_time_send_type2 == MARKETING_ACTIVITY_SEND_TYPE_LONG) {

                    $data['time_send_type'] = MARKETING_ACTIVITY_SEND_TYPE_LONG;
                    $data['start_time'] = date('Y-m-d H:i:s', time());
                    $data['end_time'] = null;
                    $data['status'] = MARKETING_ACTIVITY_STATUS_START;
                }
            }

            if (empty($edit_flag) && !$edit_flag) { //添加营销活动
                $res_add = json_decode($marketingClass->addMarketingActivityPrecision($merchant_id, $data), true);
            } else { //编辑营销活动
                $res_add = json_decode($marketingClass->editMarketingActivityPrecision($marketing_id, $data), true);
            }

            if ($res_add['status'] == ERROR_NONE && $data['time_type'] == MARKETING_ACTIVITY_TIME_TYPE_ONETIME && $data['time_send_type'] == MARKETING_ACTIVITY_SEND_TYPE_IMMEDIATELY) { //当活动为一次性即时活动，立即发送图文消息

                $send_data['merchant_id'] = $merchant_id;
                $send_data['group_id'] = $marketing_group;
                $send_data['coupon_id'] = $data['coupon_id'];
                $send_data['send_channel'] = $data['send_channel'];
                $send_data['image_text_title'] = $data['image_text_title'];
                $send_data['name'] = $data['name'];
                $send_data['image_text_imageurl'] = $data['image_text_imageurl'];
                $send_data['time_type'] = $data['time_type'];
                $send_data['time_send_type'] = $data['time_send_type'];
                $send_data['marketing_id'] = $res_add['data'];

                $marketingClass->sendChannelMsg($send_data);

                //修改活动状态为已结束
                $marketingClass->setMarketingActivityPrecisionStatus($res_add['data'], MARKETING_ACTIVITY_STATUS_END);
            }

            if ($res_add['status'] == ERROR_NONE && $edit_flag) { //编辑
                echo "<script>parent.window.delayHide('m-succeed-icon', '活动编辑成功', 1500);window.location.href='" . Yii::app()->createUrl('marketing/marketing/MarketingList') . "'</script>";
            } elseif ($res_add['status'] == ERROR_NONE && !$edit_flag) { //创建
                echo "<script>parent.window.delayHide('m-succeed-icon', '活动创建成功', 1500);window.location.href='" . Yii::app()->createUrl('marketing/marketing/MarketingList') . "'</script>";
            }
        }

        $this->render('marketingPrecisionAdd', array(
            'page_flag' => $page_flag,
            'group_info' => $group_info,
            'alipay_flag' => $alipay_flag,
            'marketing_info' => $marketing_info
        ));
    }

    /**
     * 精准营销详情
     */
    public function actionMarketingPrecisionDetail()
    {
        $marketing_id = $_GET['marketing_id'];

        $marketingClass = new MarketingClass();
        $marketing_info = json_decode($marketingClass->getMarketingInfoById($marketing_id),true);

        $this->render('marketingPrecisionDetail', array(
            'marketing_info' => $marketing_info
        ));
    }

    /**
     * 选择优惠券弹出框
     */
    public function actionCouponDialog()
    {
        $couponClass = new CouponClass();
        $merchant_id = Yii::app()->session['merchant_id'];
        //查询可用的优惠券
        $coupon_lists = $couponClass->getCouponUsable($merchant_id);

        $this->render('couponDialog', array(
            'coupon_lists' => $coupon_lists,
            'pages' => $couponClass->page
        ));
    }

    /**
     * 选则门店弹出框
     */
    public function actionFilterStoreDialog()
    {
        $store_lists = array();
        $key_word = '';
        if (isset($_GET['key_word'])) {
            $key_word = $_GET['key_word'];
        }

        $type = '';
        if (isset($_GET['type'])) {
            $type = $_GET['type'];
        }

        $arr_store = array(); // 已经选中的门店
        if (isset ($_GET ['store_id_str'])) {
            $store_id_str = trim($_GET ['store_id_str']);
            $store_id_arr = array_filter(explode(',', $store_id_str));
        }

        $merchant_id = Yii::app()->session['merchant_id'];
        $criteria = new CDbCriteria();
        $criteria->addCondition('merchant_id=:merchant_id and flag=:flag');
        $criteria->params = array(
            ':merchant_id' => $merchant_id,
            ':flag' => FLAG_NO
        );

        if (!empty($key_word)) {
            $criteria->addCondition("`name` like '%{$key_word}%'");
        }

        $criteria->order = 'create_time desc';

        //分页
        $pages = new CPagination(Store::model()->count($criteria));
        $pages->pageSize = 5;
        $pages->applyLimit($criteria);

        $store = Store::model()->findAll($criteria);

        $store_lists = Store::model()->findAll($criteria);

        $this->render('filterStoreDialog', array(
            'store_lists' => $store_lists,
            'pages' => $pages,
            'store_id_arr' => $store_id_arr,
            'type' => $type
        ));
    }

    /**
     * 获取门店列表
     */
    public function actionGetStoreList()
    {
        $store_lists = array();
        $merchant_id = Yii::app()->session['merchant_id'];
        $criteria = new CDbCriteria();
        $criteria->addCondition('merchant_id=:merchant_id and flag=:flag');
        $criteria->params = array(
            ':merchant_id' => $merchant_id,
            ':flag' => FLAG_NO
        );

        $criteria->order = 'create_time desc';
        $stores = Store::model()->findAll($criteria);
        $store_lists = array();
        foreach ($stores as $k => $v) {
            $store_lists[$k]['id'] = $v->id;
            $store_lists[$k]['name'] = $v->name;
            $store_lists[$k]['address'] = $v->address;
        }

        echo json_encode($store_lists);
    }


    /**
     * 删除已经选中的门店(去掉复选框勾选)
     */
    public function actionDelChooseStore()
    {
        $store_str = ''; //获取已经选中的门店id集
        $delData = ''; //要删除的门店id
        if (isset($_POST['store_str'])) {
            $store_str = $_POST['store_str'];
        }
        if (isset($_POST['delData'])) {
            $delData = $_POST['delData'];
        }

        $store_str_arr = explode(',', $store_str);
        array_splice($store_str_arr, array_search($delData, $store_str_arr), 1); //删除指定元素  并重新排序索引
        echo json_encode($store_str_arr);
    }

    /** js计算分组
     * @return array
     */
    public function actionJSGroup()
    {
        //筛选
        $filterUserClass = new FilterUserClass();

        if (isset($_GET['group_id']) && !empty($_GET['group_id'])) { //重新计算分组，则更新分组信息
            $group_id = $_GET['group_id'];
            $data = $filterUserClass->GroupTask($group_id);
            $user_ids = $data['now_time_user_ids'];
            //生成画图
            $ret_html_array = $filterUserClass->generateHtmlJson($user_ids, '');
            $return_data['html'] = $ret_html_array;
            $return_data['last_time'] = $data['last_time'];
            $return_data['group_num'] = $data['group_num'];

            echo json_encode($return_data);
        } else { //计算分组
            $user_ids = $filterUserClass->JiSuan();

            //生成画图
            $ret_html_array = $filterUserClass->generateHtmlJson($user_ids, '');

            echo json_encode($ret_html_array);
            return $user_ids;
        }
    }

    /** 保存分组
     * @throws Exception
     */
    public function actionSaveGroup()
    {
        $merchant_id = Yii::app()->session['merchant_id'];
        //缓存中读表达式json
        $key = 'merchant_' . $merchant_id . '_group_exp_json';
        $expJson = Yii::app()->memcache->get($key);

        $jsonStr = file_get_contents('php://input');

        $filterUserClass = new FilterUserClass();
        $jsonObj = json_decode($jsonStr);
        $userGroups = UserGroup::model()->findAll('merchant_id=:merchant_id AND flag=:flag', array(
            ':merchant_id' => $merchant_id,
            ':flag' => FLAG_NO
        ));
        $user_group_names = array();
        $user_group_exps = array();
        foreach ($userGroups as $v) {
            $user_group_names[] = $v->name;
            $user_group_exps[] = $v->filter_condition;
        }

        if (!empty($merchant_id) && !empty($expJson)) {
            try {
                if (in_array($jsonObj->group_name, $user_group_names)) {
                    exit('分组名已存在！');
                }

                $uids = $filterUserClass->JiSuan('', $expJson);
                //print_r($uids);exit;
                $new_group_users = User::model()->findAllByPk($uids);

                $transaction = Yii::app()->db->beginTransaction(); //创建事务

            //创建支付宝服务窗标签
                $AliServiceWindowLabel = new AliServiceWindowLabel();
                $userGroup = new UserGroup();
                $userGroup->merchant_id = $merchant_id;
                $userGroup->name = $jsonObj->group_name;
                $userGroup->type = $jsonObj->group_type;
                $userGroup->filter_condition = $expJson;
                $userGroup->create_time = date('Y-m-d H:i:s');
                //生成支付宝服务窗标签
                $ali = $AliServiceWindowLabel->addLable($jsonObj->group_name);

                if ($ali['response']->alipay_mobile_public_label_query_response->code == 200) {
                    $userGroup->label_id = $ali['label_id'];
                }
                
                if ($userGroup->save()) {
                    $group_id = $userGroup->id;

                    if (!empty($new_group_users)) {
                        foreach($new_group_users as $v)
                        {
                            $user = User::model()->findByPk($v->id);
                            $group_record = new Group();
                            $group_record->user_id = $v->id;
                            $group_record->group_id = $group_id;
                            $group_record->wechat_id = $v->wechat_id;
                            $group_record->alipay_fuwu_id = $v->alipay_fuwu_id;
                            $group_record->create_time = date('Y-m-d H:i:s');
                            
                            //支付宝服务窗增加标签
                            $AliServiceWindowLabel->lableUserAdd($userGroup->label_id, $user->alipay_fuwu_id);
                            if ($group_record->save()) {
                                $flag = true;
                            } else {
                                $flag = false;
                                break;
                            }
                        }

                        //更新分组人数
                        $userGroup->num = Group::model()->count('group_id = :group_id', array(':group_id' => $group_id));
                        $userGroup->update();
                    } else {
                        $flag = true;
                    }

                    if ($flag) {
                        $transaction->commit(); //提交事务
                        $result['status'] = ERROR_NONE;
                        $result['data'] = $group_id;

                        echo json_encode($result);
                    }
                }
                
            } catch (Exception $e) {
                $transaction->rollback(); //回滚事务
                throw new Exception($e->getMessage(), $e->getCode(), $e->getPrevious());
            }
        }

    }
}