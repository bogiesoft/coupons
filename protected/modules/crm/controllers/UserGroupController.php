<?php

/**
 *用户分组管理
 */
class UserGroupController extends CrmController{
    /**
     * 会员分组列表
     */
    public function actionUserGroupList()
    {
        $group_types = $GLOBALS['__GROUP_TYPES'];

        $list = array();
        $merchant_id = Yii::app()->session['merchant_id'];
        $criteria = new CDbCriteria();
        $criteria -> addCondition('flag=:flag');
        $criteria -> params[':flag'] = FLAG_NO;
        $criteria -> addCondition('merchant_id=:merchant_id');
        $criteria -> params[':merchant_id'] = $merchant_id;
        $criteria -> order = 'create_time DESC';

        //分页
        $pages = new CPagination(UserGroup::model()->count($criteria));
        $pages -> pageSize = Yii::app()->params['perPage'];

        $pages -> applyLimit($criteria);

        $userGroups = UserGroup::model()->findAll($criteria);
        $allusers = User::model()->findAll('merchant_id=:merchant_id AND flag=:flag AND bind_status=:bind_status', array(':merchant_id'=>$merchant_id, ':flag'=>FLAG_NO, ':bind_status'=>USER_BIND_STATUS_UNBIND));
        $all_user_ids = array();
        foreach($allusers as $k=>$v)
        {
            $all_user_ids[] = $v->id;
        }

        $alluser = count($all_user_ids);

        $list = array();
        foreach ($userGroups as $k=>$v)
        {
            $list[$k]['id'] = $v['id'];
            $list[$k]['name'] = $v['name'];
            $list[$k]['type'] = isset($v['type'])?$v['type']:'3';
            if(isset($v['num']) && !empty($v['num']))
            {
                $list[$k]['num'] = $v['num'];
                $list[$k]['num_bfb'] = round(($v['num']/$alluser)*100,2);
            }
            else
            {
                $vnum = count(Group::model()->findAll('group_id=:group_id AND flag=:flag', array(':group_id'=>$v['id'], ':flag'=>FLAG_NO)));
                $list[$k]['num'] = $vnum;
                $list[$k]['num_bfb'] = round(($vnum/$alluser)*100,2);
            }
        }

        $this->render('userGroupList', array(
            'list'=>$list,
            'pages'=>$pages,
            'group_types'=>$group_types,
        ));
    }

    /**
     * 新建会员管理分组 (根据条件筛选分组)
     */
    public function actionAddFilterUserGroup()
    {
        $group_types = $GLOBALS['__GROUP_TYPES'];
        $tag_category_customer_values = $GLOBALS['__TAG_CATEGORY_CUSTOMER_VALUE'];
        $edit_status = false;
        $group_id = $this->get('userGroupId');
        if($group_id)
            $user_group = UserGroup::model()->findByPk($group_id);
        //编辑状态
        if($group_id && isset($user_group) && !empty($user_group))
        {
            $edit_status = true;
        }


        $merchant_id = Yii::app()->session['merchant_id'];
        $user_grades = UserGrade::model()->findAll('merchant_id=:merchant_id AND flag=:flag', array(':merchant_id'=>$merchant_id, ':flag'=>FLAG_NO));
        $store_lists = Store::model()->findAll('merchant_id=:merchant_id AND flag=:flag', array(':merchant_id'=>$merchant_id, ':flag'=>FLAG_NO));

        $this->render('addFilterUserGroup', array(
            'edit_status'=>$edit_status,
            'store_lists'=>$store_lists,
            'user_grades'=>$user_grades,
            'group_types'=>$group_types,
            'tag_category_customer_values'=>$tag_category_customer_values,
        ));
    }

    /**
     * 编辑会员管理分组(根据条件筛选分组)
     */
    public function actionEditFilterUserGroup() {
        if (isset($_GET['userGroupId']) && $_GET['userGroupId']) {
            $group_id = $_GET['userGroupId'];
            $merchant_id = Yii::app()->session['merchant_id'];

            $user_group = UserGroup::model()->findByPk($group_id);
            $user_grades = UserGrade::model()->findAll('merchant_id=:merchant_id AND flag=:flag', array(':merchant_id'=>$merchant_id, ':flag'=>FLAG_NO));
            $store_lists = Store::model()->findAll('merchant_id=:merchant_id AND flag=:flag', array(':merchant_id'=>$merchant_id, ':flag'=>FLAG_NO));
            $group_types = $GLOBALS['__GROUP_TYPES'];
            $tag_category_customer_values = $GLOBALS['__TAG_CATEGORY_CUSTOMER_VALUE'];

            $this->render('editFilterUserGroup', array(
                'user_group' => $user_group,
                'store_lists'=>$store_lists,
                'user_grades'=>$user_grades,
                'group_types'=>$group_types,
                'tag_category_customer_values'=>$tag_category_customer_values,
            ));
        }
    }

    /**
     * @return array
     * 筛选会员人数
     */
    public function actionJSGroup()
    {
        //筛选
        $filterUserC = new FilterUserClass();
        $user_ids = array();
        $user_ids = $filterUserC->JiSuan();

        $ret_html_array = $filterUserC->generateHtmlJson($user_ids, '');
        echo json_encode($ret_html_array);
        return $user_ids;
    }

    /**
     * @throws Exception
     * 新增条件筛选分组保存
     */
    public function actionSaveGroup()
    {
        set_time_limit(0);
        $merchant_id = Yii::app()->session['merchant_id'];
        //获取ajax 提交过来的数据
        $jsonStr = file_get_contents('php://input');
        //验证是否是json格式,解析json数据
        $filterUserC = new FilterUserClass();
        $jsonObj = json_decode($jsonStr);
        //判断分组名称是否存在
        $userGroups = UserGroup::model()->exists('merchant_id=:merchant_id AND flag=:flag and name = :name', array(':merchant_id'=>$merchant_id, ':flag'=>FLAG_NO, ':name' => $jsonObj->group_name));
        if ($userGroups) {
            die('分组名已存在');
        } else {
            //缓存中读表达式json
            $key = 'merchant_'.$merchant_id.'_group_exp_json';
            $expJson = Yii::app()->memcache->get($key);

            if(!empty($merchant_id) && !empty($expJson))
            {
                try {
                    $uids = $filterUserC->JiSuan('', $expJson);

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
                    $userGroup->save();
                    $group_id = $userGroup->id;
                    foreach($uids as $v)
                    {
                        $user = User::model()->findByPk($v);
                        $group_record = new Group();
                        $group_record->user_id = $v;
                        $group_record->group_id = $group_id;
                        $group_record->create_time = date('Y-m-d H:i:s');
                        $group_record->save();
                        //支付宝服务窗增加标签
                        $AliServiceWindowLabel->lableUserAdd($userGroup->label_id, $user->alipay_fuwu_id);
                    }

                    $userGroup->num = Group::model()->count('group_id = :group_id', array(':group_id' => $group_id));
                    $userGroup->update();
                } catch (Exception $e) {
                    throw new Exception($e->getMessage(), $e->getCode(), $e->getPrevious());
                }
                echo 'OK';
            }
        }
    }

    /**
     * @throws Exception
     * 编辑条件筛选分组保存
     */
    public function actionEditGroup()
    {
        set_time_limit(0);
        $merchant_id = Yii::app()->session['merchant_id'];
        //获取ajax 提交过来的数据
        $jsonStr = $_POST['json_str'];
        $group_id = $_POST['id'];
        if (!empty($jsonStr) &&  $group_id) {
            //验证是否是json格式,解析json数据
            $filterUserC = new FilterUserClass();
            $jsonObj = json_decode($jsonStr);
            //判断分组名称是否存在
            $userGroups = UserGroup::model()->exists('merchant_id=:merchant_id AND flag=:flag and name = :name and id != :id', array(':merchant_id'=>$merchant_id, ':flag'=>FLAG_NO, ':name' => $jsonObj->group_name, ':id' => $group_id));
            if ($userGroups) {
                die('分组名已存在');
            } else {
                //缓存中读表达式json
                $key = 'merchant_'.$merchant_id.'_group_exp_json';
                $expJson = Yii::app()->memcache->get($key);

                if(!empty($merchant_id) && !empty($expJson))
                {
                    try {
                        $userGroup = UserGroup::model()->findByPk($group_id);
                        $userGroup->name = $jsonObj->group_name;
                        $userGroup->type = $jsonObj->group_type;
                        $userGroup->filter_condition = $expJson;
                        $userGroup->last_time = date('Y-m-d H:i:s');
                        $userGroup->update();
                        //更新分组人数
                        $filterUserC->GroupTask($userGroup->id);
                        $userGroup->num = Group::model()->count('group_id = :group_id', array(':group_id' => $group_id));
                        $userGroup->update();
                    } catch (Exception $e) {
                        throw new Exception($e->getMessage(), $e->getCode(), $e->getPrevious());
                    }
                    echo 'OK';
                }
            }
        }
    }

    /**
     * 选则门店弹出框
     */
    public function actionFilterStoreDialog()
    {
//         var_dump($_GET['store_id_str']);exit;
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
        $criteria -> addCondition('merchant_id=:merchant_id and flag=:flag');
        $criteria -> params = array(':merchant_id'=>$merchant_id,':flag'=>FLAG_NO);

        if(!empty($key_word)){
            $criteria -> addCondition("`name` like '%{$key_word}%'");
        }

        $criteria -> order = 'create_time desc';

        //分页
        $pages = new CPagination(Store::model()->count($criteria));
        $pages -> pageSize = 5;
        $pages -> applyLimit($criteria);

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
        $criteria -> addCondition('merchant_id=:merchant_id and flag=:flag');
        $criteria -> params = array(':merchant_id'=>$merchant_id,':flag'=>FLAG_NO);

        $criteria -> order = 'create_time desc';
        $stores = Store::model()->findAll($criteria);
        $store_lists = array();
        foreach($stores as $k=>$v)
        {
            $store_lists[$k]['id']  = $v->id;
            $store_lists[$k]['name']= $v->name;
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
        //array_pop($store_str_arr);
        array_splice($store_str_arr, array_search($delData, $store_str_arr), 1); //删除指定元素  并重新排序索引
        echo json_encode($store_str_arr);
    }

    /**
     * @param $userGroupId
     * 删除会员分组管理
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
     * @param string $param
     * @return string
     * 获取get值
     */
    private function get($param='')
    {
        if($param)
            return isset($_GET[$param])?$_GET[$param]:'';
        else
            return $_GET;
    }

}