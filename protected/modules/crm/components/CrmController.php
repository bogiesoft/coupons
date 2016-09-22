<?php
class CrmController extends Controller
{
    public $layout = 'main';
    public $page = null;
    public $page1 = null;
    public $page2 = null;

    /** 获取商户下分组列表
     * @param $merchant_id 商户id
     * @return string 返回分组列表json数据
     */
    public function getUserGroupList($merchant_id) {
        $cmd = Yii::app()->db->createCommand();
        $cmd->select('a.id, a.name, a.type, a.num, IFNULL(b.user_num, 0) as user_num');
        $cmd->from('wq_user_group a');
        $cmd->leftJoin('(
            SELECT
                a.group_id, COUNT(a.user_id) as user_num
            FROM wq_group a
            WHERE a.flag = ' . FLAG_NO . '
            GROUP BY a.group_id
        ) b', 'a.id = b.group_id');
        $cmd->andWhere('a.merchant_id = :merchant_id');
        $cmd->params[':merchant_id'] = $merchant_id;

        $cmd->andWhere('a.flag = :flag');
        $cmd->params[':flag'] = FLAG_NO;
        $cmd->order('a.create_time DESC');

        //分页
        $cmd1 = clone $cmd;
        $cmd1->select = 'count(*)';
        $count = $cmd1->queryColumn();
        if ($count[0] > 0) {
            $pages = new CPagination($count[0]);
            $pages->pageSize = Yii::app()->params['perPage'];
            $this->page = $pages;
        }
        $cmd->offset = (isset($_GET['page']) ? ($_GET['page'] - 1) : 0) * $pages->pageSize;
        $cmd->limit = $pages->pageSize;
        $userGroups = $cmd->queryAll();

        //查询商户下所有用户的数量（去除已绑定的）
        $user_num = User::model()->count('merchant_id = :merchant_id AND flag = :flag AND bind_status = :bind_status', array(
            ':merchant_id' => $merchant_id,
            ':flag' => FLAG_NO,
            ':bind_status' => USER_BIND_STATUS_UNBIND
        ));

        $list = array();
        foreach ($userGroups as $k => $v) {
            $list[$k]['id'] = $v['id'];
            $list[$k]['name'] = $v['name'];
            $list[$k]['type'] = isset($v['type']) ? $v['type'] : '3';
            $list[$k]['num'] = $v['user_num'];
            $list[$k]['num_bfb'] = $user_num > 0 ? round(($v['user_num'] / $user_num) * 100, 2) : 0;
        }
        return json_encode($list);
    }

    /** 获取该商户下的用户等级
     * @param $merchant_id 商户id
     * @return string 返回用户等级json数据
     */
    public function getUserGrade($merchant_id) {
        $user_grades = UserGrade::model()->findAll(array(
            'select' => 'id, name, points_rule',
            'condition' => 'merchant_id = :merchant_id AND flag = :flag',
            'order' => 'points_rule asc',
            'params' => array(
                ':merchant_id' => $merchant_id,
                ':flag' => FLAG_NO
            )
        ));
        return $user_grades;
    }

    /** 获取该商户下的门店列表
     * @param $merchant_id 商户id
     * @param string $search 搜索条件
     * @param bool $ajax_flag 是否是ajax请求数据
     * @return string 返回门店列表json数据
     */
    public function getStoreList($merchant_id, $search = '', $ajax_flag = false) {
        if ($ajax_flag) {
            $criteria = new CDbCriteria();
            $criteria->addCondition('merchant_id=:merchant_id and flag=:flag');
            $criteria->params = array(':merchant_id' => $merchant_id, ':flag' => FLAG_NO);

            $criteria->order = 'create_time desc';
            $stores = Store::model()->findAll($criteria);
            $store_lists = array();
            foreach ($stores as $k => $v) {
                $store_lists[$k]['id'] = $v->id;
                $store_lists[$k]['name'] = $v->name;
                $store_lists[$k]['address'] = $v->address;
            }

            return $store_lists;
        } else {
            if (!empty($search)) {
                $key_word = $search['key_word'];
            }

            $criteria = new CDbCriteria();
            $criteria->addCondition('merchant_id=:merchant_id and flag=:flag');
            $criteria->params = array(':merchant_id' => $merchant_id, ':flag' => FLAG_NO);

            if (!empty($key_word)) {
                $criteria->addCondition("`name` like '%{$key_word}%'");
            }

            $criteria->order = 'create_time desc';

            //分页
            $pages = new CPagination(Store::model()->count($criteria));
            $pages->pageSize = 5;
            $pages->applyLimit($criteria);
            $this->page = $pages;

            $store_lists = Store::model()->findAll($criteria);

            return $store_lists;
        }
    }

    /** 删除会员管理分组
     * @param $user_group_id 用户分组id
     * @return string 返回删除结果json数据
     */
    public function delUserGroup($user_group_id) {
        $result = array();
        $transaction = Yii::app()->db->beginTransaction();
        try {
            $model = UserGroup::model()->findByPk($user_group_id);
            $model->flag = FLAG_YES;
            if (!$model->update()) {
                $result['status'] = ERROR_SAVE_FAIL;
                $result['errMsg'] = '数据删除失败';
                throw new Exception('会员管理分组删除失败');
            }
            //删除支付宝标签
            if (!empty($model->label_id)) {
                $AliServiceWindowLabel = new AliServiceWindowLabel();
                $ali = $AliServiceWindowLabel->delLable($model->label_id);
                if ($ali->alipay_mobile_public_label_delete_response->code == 200) {}
            }

            $group = Group::model()->findAll('flag=:flag and group_id=:group_id', array(
                ':flag' => FLAG_NO,
                ':group_id' => $user_group_id
            ));
            foreach ($group as $k => $v) {
                $v['flag'] = FLAG_YES;
                if (!$v->update()) {
                    $result['status'] = ERROR_SAVE_FAIL;
                    $result['errMsg'] = '数据删除失败';
                    throw new Exception('会员管理分组删除失败');
                }
            }
            $result['status'] = ERROR_NONE; //状态码
            $result['errMsg'] = ''; //错误信息
            $result['data'] = '';
            $transaction->commit(); //数据提交
        } catch (Exception $e) {
            $transaction->rollback();
            $result['status'] = isset($result['status']) ? $result['status'] : ERROR_EXCEPTION;
            $result['errMsg'] = $e->getMessage();
        }
        return json_encode($result);
    }

    /** 新建会员管理分组  (自定义分组)
     * @param $merchant_id 商户id
     * @param $name 分组名称
     * @return string 返回添加结果json数据
     */
    public function addUserGroupSelf($merchant_id, $name) {
        $result = array();
        $flag = 1;
        $errMsg = '';
        try {
            $userGroup = new UserGroup();
            if (empty($name)) {
                $flag = 2;
                $result ['status'] = ERROR_PARAMETER_MISS;
                $errMsg = $errMsg . ' 分组名必填';
                Yii::app()->user->setFlash('name_error', '分组名必填');
            } else {
                $model_num = UserGroup::model()->count('flag = :flag and merchant_id = :merchant_id and name = :name', array(
                    ':flag' => FLAG_NO,
                    ':merchant_id' => $merchant_id,
                    ':name' => trim($name)
                ));
                if ($model_num > 0) {
                    $flag = 2;
                    $result ['status'] = ERROR_PARAMETER_MISS;
                    $errMsg = $errMsg . ' 分组名已存在';
                    Yii::app()->user->setFlash('name_error', '分组名已存在');
                }
            }

            if ($flag == 2) {
                $result ['errMsg'] = $errMsg;
                return json_encode($result);
            }
            //支付宝服务窗生成标签
            $AliServiceWindowLabel = new AliServiceWindowLabel();
            $ali = $AliServiceWindowLabel->addLable($name);

            $userGroup->merchant_id = $merchant_id;
            $userGroup->name = $name;
            $userGroup->type = GROUP_TYPE_CUSTOM;
            $userGroup->create_time = date('Y-m-d H:i:s');
            if ($ali['response']->alipay_mobile_public_label_query_response->code == 200) {
                $userGroup->label_id = $ali['label_id'];
            }
            if ($userGroup->save()) {
                $result ['status'] = ERROR_NONE; // 状态码
            } else {
                $result ['status'] = ERROR_SAVE_FAIL; // 状态码
            }

        } catch (Exception $e) {
            $result['status'] = isset($result['status']) ? $result['status'] : ERROR_EXCEPTION;
            $result['errMsg'] = $e->getMessage();
        }
        return json_encode($result);
    }

    /** 修改会员管理分组  (自定义分组)
     * @param $merchant_id 商户id
     * @param $name 分组名称
     * @param $user_group_id 分组id
     * @return string 返回修改结果json数据
     */
    public function editUserGroupSelf($merchant_id, $name, $user_group_id) {
        $result = array();
        $flag = 1;
        $errMsg = '';
        try {
            $userGroup = UserGroup::model()->findByPk($user_group_id);
            if (empty($name)) {
                $flag = 2;
                $result ['status'] = ERROR_PARAMETER_MISS;
                $errMsg = $errMsg . ' 分组名必填';
                Yii::app()->user->setFlash('name_error', '分组名必填');
            } else {
                $model_num = UserGroup::model()->count('flag=:flag and merchant_id=:merchant_id and name=:name and id!=:id', array(
                    ':flag' => FLAG_NO,
                    ':merchant_id' => $merchant_id,
                    ':name' => trim($name),
                    ':id' => $user_group_id
                ));
                if ($model_num > 0) {
                    $flag = 2;
                    $result ['status'] = ERROR_PARAMETER_MISS;
                    $errMsg = $errMsg . ' 分组名已存在';
                    Yii::app()->user->setFlash('name_error', '分组名已存在');
                }
            }

            if ($flag == 2) {
                $result['errMsg'] = $errMsg;
                return json_encode($result);
            }

            $userGroup->name = $name;
            $userGroup->last_time = date('Y-m-d H:i:s');
            if ($userGroup->update()) {
                $result['status'] = ERROR_NONE;
            } else {
                $result ['status'] = ERROR_SAVE_FAIL; // 状态码
            }
        } catch (Exception $e) {
            $result['status'] = isset($result['status']) ? $result['status'] : ERROR_EXCEPTION;
            $result['errMsg'] = $e->getMessage();
        }
        return json_encode($result);
    }

    /** 会员管理分组详情
     * @param $user_group_id 分组id
     * @return array|mixed|null 返回分组信息对象
     */
    public function getUserGroupDetail($user_group_id) {
        $model = UserGroup::model()->findByPk($user_group_id);
        return $model;
    }

    /** 获取用户分组信息
     * @param $group_id 分组id
     * @return static 返回分组信息对象
     */
    public function getUserGroupInfo($group_id) {
        $user_group_info = UserGroup::model()->findByPk($group_id);
        return $user_group_info;
    }

    /** 查询分组名称是否存在
     * @param $merchant_id 商户id
     * @param $group_name 分组名称
     * @param string $group_id 分组id（编辑时用到）
     * @return bool 返回bool类型的查询结果
     */
    public function checkGroupName($merchant_id, $group_name, $group_id = '') {
        if (empty($group_id)) {
            $check_res = UserGroup::model()->exists('merchant_id = :merchant_id AND flag = :flag and name = :name', array(
                ':merchant_id' => $merchant_id,
                ':flag' => FLAG_NO,
                ':name' => $group_name
            ));
        } else {
            $check_res = UserGroup::model()->exists('merchant_id = :merchant_id AND flag = :flag and name = :name and id != :id', array(
                ':merchant_id' => $merchant_id,
                ':flag' => FLAG_NO,
                ':name' => $group_name,
                ':id' => $group_id
            ));
        }
        return $check_res;
    }

    /** 保存分组
     * @param $uids 用户id集合
     * @param $merchant_id 商户id
     * @param $jsonObj 分组信息对象
     * @param $expJson 筛选条件json
     * @return bool 返回结果
     */
    public function saveGroup($uids, $merchant_id, $jsonObj, $expJson) {
        //开启事务
        $transaction = Yii::app()->db->beginTransaction();
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
            foreach ($uids as $v) {
                $user = User::model()->findByPk($v, array(
                    'select' => 'alipay_fuwu_id'
                ));
                $group_record = new Group();
                $group_record->user_id = $v;
                $group_record->group_id = $group_id;
                $group_record->create_time = date('Y-m-d H:i:s');
                $group_record->save();
                //支付宝服务窗增加标签
                $AliServiceWindowLabel->lableUserAdd($userGroup->label_id, $user->alipay_fuwu_id);
            }

            $userGroup->num = Group::model()->count('group_id = :group_id', array(
                ':group_id' => $group_id
            ));
            if ($userGroup->update()) {
                $transaction->commit();
                return true;
            } else {
                $transaction->rollBack();
                return false;
            }
        } else {
            $transaction->rollBack();
            return false;
        }
    }

    /** 保存编辑分组
     * @param $group_id 分组id
     * @param $jsonObj 分组信息对象
     * @param $expJson 筛选条件json
     * @return bool 返回bool值
     * @throws Exception
     */
    public function saveEditGroup($group_id, $jsonObj, $expJson) {
        $userGroup = UserGroup::model()->findByPk($group_id);
        $userGroup->name = $jsonObj->group_name;
        $userGroup->type = $jsonObj->group_type;
        $userGroup->filter_condition = $expJson;
        $userGroup->last_time = date('Y-m-d H:i:s');
        if ($userGroup->update()) {
            //更新分组人数
            $this->updateGroup($userGroup->id);
            $userGroup->num = Group::model()->count('group_id = :group_id', array(
                ':group_id' => $group_id
            ));
            if ($userGroup->update()) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    /** 更新分组
     * @param $group_id 分组id
     * @return bool|string
     */
    public function updateGroup($group_id) {
        if (!empty($group_id)) { //重新计算更新分组
            //开启事务
            $transaction = Yii::app()->db->beginTransaction();
            $group = UserGroup::model()->findByPk($group_id);
            try {
                $now_time_user_ids = $this->JiSuan($group->merchant_id, $group->filter_condition);
                $oldGroups = Group::model()->findAll(array(
                    'select' => 'user_id',
                    'condition' => 'group_id = :group_id AND fla g= :flag',
                    'params' => array(
                        ':group_id' => $group->id,
                        ':flag' => FLAG_NO
                    )
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
                if ($group->save()) {
                    $transaction->commit();
                    return true;
                } else {
                    $transaction->rollBack();
                    return false;
                }
            } catch (Exception $e) {
                return $e->getMessage();
            }
        }
    }

    /** 删除分组用户
     * @param $del_user_ids 删除用户的id集合
     * @param $group_id 分组id
     * @param $label_id 标签id
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
                $user = User::model()->findByPk($v, array(
                    'select' => 'alipay_fuwu_id'
                ));
                $AliServiceWindowLabel->lableUserDel($label_id, $user->alipay_fuwu_id);
            }
        }
    }

    /** 添加分组用户
     * @param $in_users 要添加的用户id集合
     * @param $group_id 分组id
     * @param $label_id 标签id
     */
    private function addGroupUser($in_users, $group_id, $label_id) {
        $AliServiceWindowLabel = new AliServiceWindowLabel();
        foreach ($in_users as $v) {
            $new_group = new Group();
            $new_group->user_id = $v;
            $new_group->group_id = $group_id;
            $new_group->create_time = date('Y-m-d H:i:s');
            if ($new_group->save()) {
                $user = User::model()->findByPk($v, array(
                    'select' => 'alipay_fuwu_id'
                ));
                $AliServiceWindowLabel->lableUserAdd($label_id, $user->alipay_fuwu_id);
            }
        }
    }

    /** 添加分组点击计算
     * @param string $merchant_id 商户id
     * @param string $expJson 条件表达式
     * @return array 返回用户数据
     */
    public function JiSuan($merchant_id = '', $expJson = '') {
        if (empty($merchant_id)) {
            $merchant_id = Yii::app()->session['merchant_id'];
        }
        if (empty($expJson)) {
            $expJson = file_get_contents("php://input");
        }
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
                if ($exp->name == 'condition_consum_amount') {
                    $user_ids = $this->condition_consum_amount($exp, $merchant_id, $user_ids);
                }
                //客单价
                if ($exp->name == 'condition_consum_unitprice') {
                    if (!isset($conditionArr['condition_consum_amount'])) {
                        $user_ids = $this->condition_consum_unitprice($exp, $merchant_id, $user_ids);
                    }
                    if (isset($conditionArr['condition_consum_amount']) && !empty($user_ids)) {
                        $user_ids = $this->condition_consum_unitprice($exp, $merchant_id, $user_ids);
                    }
                }
                //最高消费
                if ($exp->name == 'condition_consum_highestprice') {
                    if (!isset($conditionArr['condition_consum_unitprice'])) {
                        $user_ids = $this->condition_consum_highestprice($exp, $merchant_id, $user_ids);
                    }
                    if (isset($conditionArr['condition_consum_unitprice']) && !empty($user_ids)) {
                        $user_ids = $this->condition_consum_highestprice($exp, $merchant_id, $user_ids);
                    }
                }
                //消费次数
                if ($exp->name == 'condition_consum_count') {
                    if (!isset($conditionArr['condition_consum_highestprice'])) {
                        $user_ids = $this->condition_consum_count($exp, $merchant_id, $user_ids);
                    }
                    if (isset($conditionArr['condition_consum_highestprice']) && !empty($user_ids)) {
                        $user_ids = $this->condition_consum_count($exp, $merchant_id, $user_ids);
                    }
                }
                //消费天数
                if ($exp->name == 'condition_consum_days') {
                    if (!isset($conditionArr['condition_consum_count'])) {
                        $user_ids = $this->condition_consum_days($exp, $merchant_id, $user_ids);
                    }
                    if (isset($conditionArr['condition_consum_count']) && !empty($user_ids)) {
                        $user_ids = $this->condition_consum_days($exp, $merchant_id, $user_ids);
                    }
                }
                //最近消费数据
                if ($exp->name == 'condition_not_consum_days') {
                    if (!isset($conditionArr['condition_consum_days'])) {
                        $user_ids = $this->condition_not_consum_days($exp, $merchant_id, $user_ids);
                    }
                    if (isset($conditionArr['condition_consum_days']) && !empty($user_ids)) {
                        $user_ids = $this->condition_not_consum_days($exp, $merchant_id, $user_ids);
                    }
                }
            }

        } catch (Exception $e) {
            die('{"status":"error","errcode":' . $e->getCode() . ',"errmsg":"' . $e->getMessage() . '"}');
        }

        // 计算后表达式缓存
        if (!empty($merchant_id) && !empty($expJson)) {
            $key = 'merchant_' . $merchant_id . '_group_exp_json';
            Yii::app()->memcache->set($key, $expJson);
        }

        return $user_ids;
    }

    /** 根据用户条件筛选
     * @param $expObjs 条件表达式
     * @param $merchant_id 商户id
     * @return array 返回用户数据
     */
    private function condition_user($expObjs, $merchant_id) {
        $criteria = new CDbCriteria();
        foreach ($expObjs as $exp) {
            switch ($exp->name) {
                case 'condition_sex':
                    $sex = array_filter(explode(',', $exp->value));
                    if (!empty($sex)) {
                        if (in_array('3', $sex)){
                            $sex[] = '0';
                        }
                        $criteria->addInCondition('IFNULL(sex, 3)', $sex);
                    }
                    break;
                case 'condition_age':
                    $agemin = $exp->value->valuemin;
                    $agemax = $exp->value->valuemax;
                    /*if ($agemin == 0) {
                        $agemin = 1;
                    }
                    if ($agemax == 0) {
                        $agemax = 1;
                    }*/
                    //if (!empty($agemin) && !empty($agemax)) {
                        $agestart = date('Y') - $agemax;
                        $ageend = date('Y') - $agemin;
                        $criteria->addCondition($agestart . ' <= year(birthday) AND year(birthday) <= ' . $ageend);
                    //}
                    break;
                case 'condition_birthday':
                    if (!empty($exp->value)) {
                        $birth = explode('-', $exp->value);
                        $birthdatemin = date('Y-m-d 00:00:00', strtotime('1970/' . trim($birth[0])));
                        $birthdatemax = date('Y-m-d 23:59:59', strtotime('1970/' . trim($birth[1])));
                        if (!empty($birthdatemin) && !empty($birthdatemax)) {
                            $criteria->addBetweenCondition("DATE_FORMAT(birthday,'%m-%d')", date('m-d', strtotime($birthdatemin)), date('m-d', strtotime($birthdatemax)));
                        }
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
					
                    $criteria_grade = new CDbCriteria();

                    $criteria_grade->addCondition('merchant_id = :merchant_id and flag = :flag');
                    $criteria_grade->params = array(
                        ':merchant_id' => $merchant_id,
                        ':flag' => FLAG_NO
                    );
                    $criteria_grade->order = 'points_rule asc';

                    $user_grade_model = UserGrade::model()->findAll($criteria_grade);

                    if (!empty($user_grade_model)) {
                        foreach ($user_grade_model as $k => $v) {
                            $all_grade[$v['id']]['min'] = !empty($v['points_rule']) ? $v['points_rule'] : 0;
                            $all_grade[$v['id']]['max'] = !empty($user_grade_model[$k + 1]['points_rule']) ? $user_grade_model[$k + 1]['points_rule'] : 0;
                        }
                        $sql = array();
                        foreach($membershipgrade_ids as $membershipgrade_id){
                            if ($all_grade[$membershipgrade_id]['min'] < $all_grade[$membershipgrade_id]['max']) {
                                $sql[] = ' (total_points >= ' . $all_grade[$membershipgrade_id]['min'] . ' and total_points < ' . $all_grade[$membershipgrade_id]['max'].')';
                            } else {
                                $sql[] = ' (total_points >= ' . $all_grade[$membershipgrade_id]['min'].')';
                            }
                        }
                        $sqlStr = implode(' OR ', $sql);
                        $criteria->addCondition($sqlStr);

                        $criteria->addCondition('type = :type');
                        $criteria->params[':type'] = USER_TYPE_WANQUAN_MEMBER;
                    }
                    break;
                case 'condition_value':
                    $system_value_tag_num = Tag::model()->count('merchant_id = :merchant_id AND type = :type AND category = :category AND flag = :flag', array(
                        ':merchant_id' => $merchant_id,
                        ':type' => TAG_TYPE_SYSTEM,
                        ':category' => TAG_CATEGORY_CUSTOMER_VALUE,
                        ':flag' => FLAG_NO
                    ));

                    $ids = array();
                    //不存在客户价值标签，则直接返回空数组
                    if ($system_value_tag_num == 0) {
                        return $ids;
                    }
                    $tag_values = array_filter(explode(',', $exp->value));
                    $criteria->addInCondition('tag_value', $tag_values);
                    break;
            }
        }

        $criteria->select = 'id';
        $criteria->addCondition('merchant_id = :merchant_id AND flag = :flag AND bind_status = :bind_status');
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

    /** 根据消费金额筛选
     * @param $obj 表达式
     * @param $merchant_id 商户id
     * @param $user_ids 用户id集合
     * @return array 返回用户数据
     */
    private function condition_consum_amount($obj, $merchant_id, $user_ids) {
        $ids = array();

        //已筛选出来的会员
        if (!empty($user_ids)) {
            $user_order = array();
            foreach ($user_ids as $user_id) {
                $user_order[$user_id]['order_paymoney'] = 0;
            }
            $user_ids = implode(',', $user_ids);
            //判断消费金额
            $valuemin = $obj->value->valuemin;
            $valuemax = $obj->value->valuemax;

            //查出用户的订单记录
            $user_sql = '
                SELECT
                    a.user_id, a.id AS order_id, a.order_paymoney, a.coupons_money, a.discount_money, a.merchant_discount_money
                FROM wq_order a
                WHERE a.pay_status = ' . ORDER_STATUS_PAID .'
                AND a.user_id IN (' . $user_ids . ')
                AND a.merchant_id = ' . $merchant_id . '
                ';

            //消费日期
            if (!empty($obj->date)) {
                $datetime = explode('-', $obj->date);
                $datemin = date('Y-m-d 00:00:00', strtotime($datetime[0]));
                $datemax = date('Y-m-d 23:59:59', strtotime($datetime[1]));
                $user_sql .= ' and a.pay_time > "' . $datemin . '" and a.pay_time < "' . $datemax . '"';
                $codition_refund_sql  = ' and a.refund_time > "' . $datemin . '" and a.refund_time < "' . $datemax . '"';
            }

            //消费门店
            $store_ids = array_filter($obj->store);
            if (!empty($store_ids)) {
                $store_ids = implode(',', $store_ids);
                $user_sql .= ' and a.store_id in (' . $store_ids . ')';
            }

            $connection = Yii::app()->db;
            $user_order_res = $connection->createCommand($user_sql)->queryAll();

            //计算用户所有订单金额
            foreach ($user_order_res as &$v) {
                $user_order[$v['user_id']]['order_paymoney'] += !empty($v['order_paymoney']) ? ($v['order_paymoney'] - $v['coupons_money'] - $v['discount_money'] - $v['merchant_discount_money']) : 0;
                if (!empty($v['order_id'])) {
                    $order_ids[] = $v['order_id'];
                }
            }

            if (!empty($order_ids)) {
                $order_ids = implode(',', $order_ids);

                //计算用户退款金额
                $refund_sql = '
                SELECT
                    a.order_id, b.user_id, sum(a.refund_money) AS total_refund_money
                FROM wq_refund_record a
                LEFT JOIN wq_order b ON a.order_id = b.id
                WHERE a.order_id IN (' . $order_ids . ')
                AND a.type = ' . REFUND_TYPE_REFUND . '
                AND a.status = ' . REFUND_STATUS_SUCCESS . '
                ' . $codition_refund_sql . '
                GROUP BY a.order_id
                ';
                $refund_order_res = $connection->createCommand($refund_sql)->queryAll();

                foreach ($refund_order_res as $v) {
                    if (isset($user_order[$v['user_id']])) {
                        $user_order[$v['user_id']]['order_paymoney'] -= !empty($v['total_refund_money']) ? $v['total_refund_money'] : 0;
                    }
                }
            }

            //计算用户实际消费金额
            foreach ($user_order as $key => $v) {
                if ($v['order_paymoney'] <= $valuemax && $v['order_paymoney'] >= $valuemin) {
                    $ids[] = $key;
                }
            }
        }

        return $ids;
    }

    /** 根据客单价
     * @param $obj 表达式
     * @param $merchant_id 商户id
     * @param $user_ids 用户id集合
     * @return array 返回用户数据
     */
    private function condition_consum_unitprice($obj, $merchant_id, $user_ids) {
        $ids = array();

        if (!empty($user_ids)) {
            $user_order = array();
            foreach ($user_ids as $user_id) {
                $user_order[$user_id]['order_paymoney'] = 0;
                $user_order_num[$user_id] = 0;
            }

            $user_ids = implode(',', $user_ids);

            //客单价
            $valuemin = $obj->value->valuemin;
            $valuemax = $obj->value->valuemax;

            //查出用户的订单记录
            $user_sql = '
                SELECT
                    a.user_id, a.id AS order_id, a.order_paymoney, a.coupons_money, a.discount_money, a.merchant_discount_money
                FROM wq_order a
                WHERE a.pay_status = ' . ORDER_STATUS_PAID .'
                AND a.user_id IN (' . $user_ids . ')
                AND a.merchant_id = ' . $merchant_id . '
                ';

            //消费日期
            if (!empty($obj->date)) {
                $datetime = explode('-', $obj->date);
                $datemin = date('Y-m-d 00:00:00', strtotime($datetime[0]));
                $datemax = date('Y-m-d 23:59:59', strtotime($datetime[1]));
                $user_sql .= ' and a.pay_time > "' . $datemin . '" and a.pay_time < "' . $datemax . '"';
                $codition_refund_sql  = ' and a.refund_time > "' . $datemin . '" and a.refund_time < "' . $datemax . '"';
                $codition_order_sql  = ' and a.pay_time > "' . $datemin . '" and a.pay_time < "' . $datemax . '"';
            }

            //消费门店
            $store_ids = array_filter($obj->store);
            if (!empty($store_ids)) {
                $store_ids = implode(',', $store_ids);
                $user_sql .= ' and a.store_id in (' . $store_ids . ')';
                $codition_order_sql .= ' and a.store_id in (' . $store_ids . ')';
            }

            $connection = Yii::app()->db;
            $user_order_res = $connection->createCommand($user_sql)->queryAll();

            //计算用户所有订单金额
            foreach ($user_order_res as $v) {
                $user_order[$v['user_id']]['order_paymoney'] += !empty($v['order_paymoney']) ? ($v['order_paymoney'] - $v['coupons_money'] - $v['discount_money'] - $v['merchant_discount_money']) : 0;
                if (!empty($v['order_id'])) {
                    $order_ids_arr[] = $v['order_id'];
                }
            }

            if (!empty($order_ids_arr)) {
                $order_ids = implode(',', $order_ids_arr);

                //计算用户退款金额
                $refund_sql = '
                SELECT
                    a.order_id, b.user_id, sum(a.refund_money) AS total_refund_money
                FROM wq_refund_record a
                LEFT JOIN wq_order b ON a.order_id = b.id
                WHERE a.order_id IN (' . $order_ids . ')
                AND a.type = ' . REFUND_TYPE_REFUND . '
                AND a.status = ' . REFUND_STATUS_SUCCESS . '
                ' . $codition_refund_sql . '
                GROUP BY a.order_id
                ';
                $refund_order_res = $connection->createCommand($refund_sql)->queryAll();

                foreach ($refund_order_res as $v) {
                    if (isset($user_order[$v['user_id']])) {
                        $user_order[$v['user_id']]['order_paymoney'] -= !empty($v['total_refund_money']) ? $v['total_refund_money'] : 0;
                        $refund_order[$v['order_id']] = $v['total_refund_money'];
                    }
                }

                foreach ($user_order_res as $key => $v) {
                    if (!empty($refund_order[$v['order_id']])) {
                        if ($v['order_paymoney'] > $refund_order[$v['order_id']]) {
                            $user_order_num[$v['user_id']]++;
                        }
                    } else {
                        if (key_exists($v['user_id'], $user_order_num)) {
                            $user_order_num[$v['user_id']]++;
                        }
                    }
                }
            }

            //计算用户平均消费金额
            foreach ($user_order as $key => $v) {
                $average = !empty($user_order_num[$key]) ? round($v['order_paymoney'] / $user_order_num[$key], 2) : 0;
                if ($average <= $valuemax && $average >= $valuemin) {
                    $ids[] = $key;
                }
            }
        }
        return $ids;
    }

    /** 根据最高消费
     * @param $obj 表达式
     * @param $merchant_id 商户id
     * @param $user_ids 用户id集合
     * @return array 返回用户数据
     */
    private function condition_consum_highestprice($obj, $merchant_id, $user_ids) {
        if (!empty($user_ids)) {
            $user_order = array();
            foreach ($user_ids as $user_id) {
                $user_order[$user_id] = 0;
            }
            $user_ids = implode(',', $user_ids);

            //查出用户的订单记录
            $user_sql = '
                SELECT
                    a.user_id, a.id AS order_id, a.order_paymoney, a.coupons_money, a.discount_money, a.merchant_discount_money
                FROM wq_order a
                WHERE a.pay_status = ' . ORDER_STATUS_PAID .'
                AND a.user_id IN (' . $user_ids . ')
                AND a.merchant_id = ' . $merchant_id . '
                ';

            //消费日期
            if (!empty($obj->date)) {
                $datetime = explode('-', $obj->date);
                $datemin = date('Y-m-d 00:00:00', strtotime($datetime[0]));
                $datemax = date('Y-m-d 23:59:59', strtotime($datetime[1]));
                $user_sql .= ' and a.pay_time > "' . $datemin . '" and a.pay_time < "' . $datemax . '"';
                $codition_refund_sql  = ' and a.refund_time > "' . $datemin . '" and a.refund_time < "' . $datemax . '"';
            }

            //消费门店
            $store_ids = array_filter($obj->store);
            if (!empty($store_ids)) {
                $store_ids = implode(',', $store_ids);
                $user_sql .= ' and a.store_id in (' . $store_ids . ')';
            }

            $connection = Yii::app()->db;
            $user_order_res = $connection->createCommand($user_sql)->queryAll();

            foreach ($user_order_res as &$v) {
                if (!empty($v['order_id'])) {
                    $order_ids[] = $v['order_id'];
                }
            }

            if (!empty($order_ids)) {
                $order_ids = implode(',', $order_ids);

                //计算用户退款金额
                $refund_sql = '
                SELECT
                    a.order_id, b.user_id, sum(a.refund_money) AS total_refund_money
                FROM wq_refund_record a
                LEFT JOIN wq_order b ON a.order_id = b.id
                WHERE a.order_id IN (' . $order_ids . ')
                AND a.type = ' . REFUND_TYPE_REFUND . '
                AND a.status = ' . REFUND_STATUS_SUCCESS . '
                ' . $codition_refund_sql . '
                GROUP BY a.order_id
                ';
                $refund_order_res = $connection->createCommand($refund_sql)->queryAll();

                foreach ($refund_order_res as &$v) {
                    $refund_order[$v['order_id']] = $v['total_refund_money'];
                }

                foreach ($user_order_res as $key => $v) {
                    $v['order_paymoney'] = $v['order_paymoney'] - $v['coupons_money'] - $v['discount_money'] - $v['merchant_discount_money'] - $refund_order[$v['order_id']];

                    if (empty($user_order[$v['user_id']]) || $user_order[$v['user_id']] < $v['order_paymoney']) {
                        $user_order[$v['user_id']] = $v['order_paymoney'];
                    }
                }
            }

            //最高消费
            $valuemin = $obj->value->valuemin;
            $valuemax = $obj->value->valuemax;

            $ids = array();
            //计算用户最高消费金额
            foreach ($user_order as $key => $v) {
                if ($v <= $valuemax && $v >= $valuemin) {
                    $ids[] = $key;
                }
            }
        }

        return $ids;
    }

    /** 根据消费次数
     * @param $obj 表达式
     * @param $merchant_id 商户id
     * @param $user_ids 用户id集合
     * @return array 返回用户数据
     */
    private function condition_consum_count($obj, $merchant_id, $user_ids) {
        $ids = array();
        if (!empty($user_ids)) {
            $user_order = array();
            foreach ($user_ids as $user_id) {
                $user_order[$user_id] = 0;
            }
            $user_ids = implode(',', $user_ids);

            //查出用户的订单记录
            $user_sql = '
                SELECT
                    a.user_id, a.id AS order_id, a.order_paymoney, a.coupons_money, a.discount_money, a.merchant_discount_money
                FROM wq_order a
                WHERE a.pay_status = ' . ORDER_STATUS_PAID .'
                AND a.user_id IN (' . $user_ids . ')
                AND a.merchant_id = ' . $merchant_id . '
                ';

            //消费日期
            if (!empty($obj->date)) {
                $datetime = explode('-', $obj->date);
                $datemin = date('Y-m-d 00:00:00', strtotime($datetime[0]));
                $datemax = date('Y-m-d 23:59:59', strtotime($datetime[1]));
                $user_sql .= ' and a.pay_time > "' . $datemin . '" and a.pay_time < "' . $datemax . '"';
                $codition_refund_sql  = ' and a.refund_time > "' . $datemin . '" and a.refund_time < "' . $datemax . '"';
            }

            //消费门店
            $store_ids = array_filter($obj->store);
            if (!empty($store_ids)) {
                $store_ids = implode(',', $store_ids);
                $user_sql .= ' and a.store_id in (' . $store_ids . ')';
            }

            $connection = Yii::app()->db;
            $user_order_res = $connection->createCommand($user_sql)->queryAll();

            foreach ($user_order_res as &$v) {
                if (!empty($v['order_id'])) {
                    $order_ids[] = $v['order_id'];
                }
            }

            if (!empty($order_ids)) {
                $order_ids = implode(',', $order_ids);

                //计算用户退款金额
                $refund_sql = '
                SELECT
                    a.order_id, b.user_id, sum(a.refund_money) AS total_refund_money
                FROM wq_refund_record a
                LEFT JOIN wq_order b ON a.order_id = b.id
                WHERE a.order_id IN (' . $order_ids . ')
                AND a.type = ' . REFUND_TYPE_REFUND . '
                AND a.status = ' . REFUND_STATUS_SUCCESS . '
                ' . $codition_refund_sql . '
                GROUP BY a.order_id
                ';
                $refund_order_res = $connection->createCommand($refund_sql)->queryAll();

                foreach ($refund_order_res as $v) {
                    $refund_order[$v['order_id']] = $v['total_refund_money'];
                }

                foreach ($user_order_res as $v) {
                    if (!empty($refund_order[$v['order_id']])) {
                        if ($v['order_paymoney'] - $v['coupons_money'] - $v['discount_money'] - $v['merchant_discount_money'] > $refund_order[$v['order_id']]) {
                            $user_order[$v['user_id']]++;
                        }
                    } else {
                        if (key_exists($v['user_id'], $user_order)) {
                            $user_order[$v['user_id']]++;
                        }
                    }
                }
            }

            //消费次数
            $valuemin = $obj->value->valuemin;
            $valuemax = $obj->value->valuemax;

            foreach ($user_order as $key => $v) {
                if ($v <= $valuemax && $v >= $valuemin) {
                    $ids[] = $key;
                }
            }
        }
        return $ids;
    }

    /** 根据消费天数
     * @param $obj 表达式
     * @param $merchant_id 商户id
     * @param $user_ids 用户id集合
     * @return array 返回用户数据
     */
    private function condition_consum_days($obj, $merchant_id, $user_ids) {
        $ids = array();
        $codition_sql = '
            where wq_order.merchant_id = ' . $merchant_id . ' 
            and (wq_order.order_status = ' . ORDER_STATUS_NORMAL . ' 
            or wq_order.order_status = ' . ORDER_STATUS_PART_REFUND . ' 
            or wq_order.order_status = ' . ORDER_STATUS_HANDLE_REFUND . ')';

        if (!empty($obj->date)) {
            $datetime = explode('-', $obj->date);
            $datemin = date('Y-m-d 00:00:00', strtotime($datetime[0]));
            $datemax = date('Y-m-d 23:59:59', strtotime($datetime[1]));
            $codition_sql .= ' and pay_time > "' . $datemin . '" and pay_time < "' . $datemax . '"';
        }

        //消费天数
        $valuemin = $obj->value->valuemin;
        $valuemax = $obj->value->valuemax;

        //消费门店
        $store_ids = array_filter($obj->store);
        if (!empty($store_ids)) {
            $store_ids = implode(',', $store_ids);
            $codition_sql .= ' and wq_order.store_id in (' . $store_ids . ')';
        }

        //已筛选出来的会员
        if (!empty($user_ids)) {
            $user_ids = implode(',', $user_ids);
            $codition_sql .= ' and wq_order.user_id in (' . $user_ids . ')';
        }

        $order_sql = 'SELECT user_id,wechat_user_id, alipay_user_id, left(pay_time, 10) from wq_order ' . $codition_sql;

        $user_sql = '
            SELECT 
                id, count(id) as num 
            FROM wq_user 
            LEFT JOIN (' . $order_sql . ') orders 
            ON (wq_user.id = orders.user_id 
                OR wq_user.wechat_id = orders.wechat_user_id 
                OR wq_user.alipay_fuwu_id = orders.alipay_user_id) 
            WHERE orders.user_id != "" 
            AND bind_status = ' . USER_BIND_STATUS_UNBIND . ' 
            GROUP BY id 
            HAVING num >= ' . $valuemin . ' 
            AND num <= ' . $valuemax;
        $connection = Yii::app()->db;
        $rs = $connection->createCommand($user_sql)->queryAll();
        if (!empty($rs)) {
            foreach ($rs as $v) {
                $ids[] = $v['id'];
            }
        }
        return $ids;
    }

    /** 为消费天数区间
     * @param $obj 表达式
     * @param $merchant_id 商户id
     * @param $user_ids 用户id集合
     * @return array 返回用户数据
     */
    private function condition_not_consum_days($obj, $merchant_id, $user_ids) {
        $ids = array();
        $codition_sql = '
            where wq_order.merchant_id = ' . $merchant_id . ' 
            and (wq_order.order_status = ' . ORDER_STATUS_NORMAL . ' 
            or wq_order.order_status = ' . ORDER_STATUS_PART_REFUND . ' 
            or wq_order.order_status = ' . ORDER_STATUS_HANDLE_REFUND . ')';

        //消费门店
        $store_ids = array_filter($obj->store);
        if (!empty($store_ids)) {
            $store_ids = implode(',', $store_ids);
            $codition_sql .= ' and wq_order.store_id in (' . $store_ids . ')';
        }

        //已筛选出来的会员
        if (!empty($user_ids)) {
            $user_ids = implode(',', $user_ids);
            $codition_sql .= ' and wq_order.user_id in (' . $user_ids . ')';
        }

        $valuemin = $obj->value->valuemin;
        $valuemax = $obj->value->valuemax;

        $order_sql = 'SELECT pay_time, wechat_user_id, user_id, alipay_user_id FROM wq_order ' . $codition_sql;

        $user_sql = '
            SELECT 
                id,timestampdiff(DAY, date_format(users.pay_time, "%y-%m-%d"), 
                date_format(now(), "%y-%m-%d")) as days 
            FROM (
                SELECT 
                    id, orders.pay_time, orders.wechat_user_id, orders.alipay_user_id 
                FROM wq_user 
                LEFT JOIN (' . $order_sql . ') orders 
                ON (wq_user.id = orders.user_id 
                    OR wq_user.wechat_id = orders.wechat_user_id 
                    OR wq_user.alipay_fuwu_id = orders.alipay_user_id) 
                ORDER BY orders.pay_time DESC) users 
            GROUP BY id 
            HAVING days >= ' . $valuemin . ' 
            AND days <= ' . $valuemax;

        $connection = Yii::app()->db;
        $rs = $connection->createCommand($user_sql)->queryAll();
        if (!empty($rs)) {
            foreach ($rs as $v) {
                $ids[] = $v['id'];
            }
        }
        return $ids;
    }

    /** 生成画图
     * @param $uids 用户id集合
     * @param string $merchant_id 商户id
     * @return array 返回html数据
     */
    public function generateHtmlJson($uids, $merchant_id = '') {
        if (empty($merchant_id)) {
            $merchant_id = Yii::app()->session['merchant_id'];
        }

        //获取用户等级
        $grades = $this->getUserGrade($merchant_id);

        $criteria = new CDbCriteria();
        $criteria->select = 'id, sex, birthday, membershipgrade_id, total_points, type';
        $criteria->addInCondition('id', $uids);
        $selected_users = User::model()->findAll($criteria);

        $all_users = User::model()->count('merchant_id = :merchant_id and flag = :flag and bind_status = :bind_status', array(
            ':merchant_id' => $merchant_id,
            ':flag' => FLAG_NO,
            ':bind_status' => USER_BIND_STATUS_UNBIND
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
        $grade_user_unkonuwn = array(
            'name' => '非会员',
            'user_num' => 0,
        );
        foreach ($grades as $grade) {
            $grade_user[$grade->id]['name'] = $grade->name;
            $grade_user[$grade->id]['points_rule'] = empty($grade->points_rule)? 0: $grade->points_rule;
            $grade_user[$grade->id]['user_num'] = 0;
        }
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
            $user_total_points = empty($user->total_points)? 0: $user->total_points;
            $grade_id = 0;
            if ($user->type == USER_TYPE_WANQUAN_MEMBER){
                foreach ($grade_user as $key => $val){
                    if ($user_total_points >= $val['points_rule']){
                        $grade_id = $key;
                    }
                }
            }
            if (!empty($grade_id) && isset($grade_user[$grade_id])) {
                $grade_user[$grade_id]['user_num']++;
            } else {
                $grade_user_unkonuwn['user_num']++;
            }
        }
        $grade_user['unkonuwn'] = $grade_user_unkonuwn;
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
                $v_bfb = number_format($v['user_num'] / $user_selected, 4) * 100;
                $user_grade_item_html = sprintf($user_grade_item_tpl, $v['name'], $v['user_num'], $v_bfb, $v_bfb);
                $user_grade_html .= $user_grade_item_html;
            }

            //客户价值标签
            $system_value_tag = Tag::model()->find(array(
                    'select' => 'id',
                    'condition' => 'merchant_id=:merchant_id AND type = :type AND category = :category AND flag = :flag',
                    'params' => array(
                        ':merchant_id' => $merchant_id,
                        ':type' => TAG_TYPE_SYSTEM,
                        ':category' => TAG_CATEGORY_CUSTOMER_VALUE,
                        ':flag' => FLAG_NO
                    )
                )
            );

            if (isset($system_value_tag) && !empty($system_value_tag)) {
                //获取客户价值的用户标签和值
                $criteria = new CDbCriteria();
                $criteria->select = 'tag_value';
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
                $user_grade_item_html = sprintf($user_grade_item_tpl, $v['name'], $v['user_num'], $v_bfb, $v_bfb);
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

    /**
     * 对比会员等级正式和草稿的数据
     * $Grade 当前使用的会员等级规则数据
     * $GradeDraft 修改会员等级的草稿
     */
    public function contrastGrade() {
        $merchant_id = Yii::app()->session['merchant_id'];
        $Grade = json_decode($this -> getSetUserGrade($merchant_id),true);
        $GradeDraft = json_decode($this -> getSetUserGradeDraft($merchant_id),true);
        $res = array();
        $user_grade = array();
        $user_grade_default = array();
        if (isset($Grade['data']) && !empty($Grade['data'])) {
            $user_grade = $this->data_transfer($Grade['data']); //整合数据，根据user_grade_code 作为键值
        }
        if (isset($GradeDraft['data']) && !empty($GradeDraft['data'])) {
            $user_grade_default = $this->data_transfer($GradeDraft['data']);
        }

        $change = FALSE;
        if(array_diff($user_grade_default, $user_grade) || array_diff($user_grade, $user_grade_default)){
            $change = TRUE;
        }
        $res['grade'] = $GradeDraft['data'];
        $res['change'] = $change;
        $res['countusergrade'] = $GradeDraft['countusergrade'];
        return json_encode($res);
    }

    /**
     * 获取会员等级设置
     * $merchant_id 商户id
     */
    public function getSetUserGrade($merchant_id) {
        $result = array();
        $criteria = new CDbCriteria();
        $criteria->order = 'points_rule asc';
        if (!empty($merchant_id)) {
            $criteria->addCondition('merchant_id=:merchant_id and flag=:flag');
            $criteria->params = array(
                ':merchant_id' => $merchant_id,
                ':flag' => FLAG_NO
            );
        } else {
            $criteria->addCondition('flag=:flag');
            $criteria->params = array(
                ':flag' => FLAG_NO
            );
        }
        $model = UserGrade::model()->findAll($criteria);
        $countusergrade = UserGrade::model()->count($criteria);
        $data = array();
        if (!empty($model)) {
        $points_rules = array();
            $i = 0;
            foreach ($model as $k => $v) {
                $points_rules[$i] = $v->points_rule;
                $data[$i]['id'] = $v->id;
                $data[$i]['merchant_id'] = $v->merchant_id;
                $data[$i]['name'] = $v->name;
                $data[$i]['points_rule'] = $v->points_rule;
                $data[$i]['discount'] = $v->discount * 10;
                $data[$i]['discount_illustrate'] = $v->discount_illustrate;
                $data[$i]['membercard_img'] = $v->membercard_img;
                $data[$i]['create_time'] = $v->create_time;
                $data[$i]['flag'] = $v->flag;
                $data[$i]['membership_card_name'] = $v->membership_card_name;
                $data[$i]['points_ratio'] = $v->points_ratio;
                $data[$i]['if_default'] = $v->if_default;
                $data[$i]['if_hideword'] = $v->if_hideword;
                $data[$i]['rule_type'] = $v->rule_type;
                $data[$i]['birthday_rate'] = $v->birthday_rate;
                $data[$i]['user_grade_code'] = $v->user_grade_code;
                $i ++;
            }
            foreach($data as $key => &$value){
                $value['count'] = $this->getUserCount($key, $merchant_id,$points_rules);
            }
            $result ['status'] = ERROR_NONE;
            $result ['data'] = $data;
            $result ['countusergrade'] = $countusergrade;
        } else {
            $result ['status'] = ERROR_NO_DATA;
            $result['errMsg'] = '无此数据'; //错误信息
        }
        return json_encode($result);
    }

    /**
     * 获取会员等级的会员人数
     * $membershipgrade_id  会员等级id
     * $merchant_id 商户id
     * 返回人数
     */
    public function getUserCount($key, $merchant_id,$points_rules) {
        $k = $key+1;
        if(empty($points_rules[$key]) || $points_rules[$key] == 0){
            $point_big = '';
            if (isset($points_rules[$k])){
                $point_big = ' AND total_points < '.$points_rules[$k];
            }
            $count = User::model()->count(array(
                'condition' => 'flag=:flag AND merchant_id=:merchant_id AND IFNULL(total_points,0) >=0 '.$point_big.' AND type=:type',
                'params' => array(
                    ':flag' => FLAG_NO,
                    ':merchant_id' => $merchant_id,
                    ':type' => USER_TYPE_WANQUAN_MEMBER
                )
            ));
        }elseif(isset($points_rules[$k])){
            $count = User::model()->count(array(
                'condition' => 'flag=:flag AND merchant_id=:merchant_id AND total_points >= :points_small AND total_points < :points_big AND type=:type',
                'params' => array(
                    ':flag' => FLAG_NO,
                    ':merchant_id' => $merchant_id,
                    ':points_small' => empty($points_rules[$key])?0:$points_rules[$key],
                    ':points_big' => empty($points_rules[$k])?0:$points_rules[$k],
                    ':type' => USER_TYPE_WANQUAN_MEMBER
                )
            ));
        }else{
            $count = User::model()->count(array(
                'condition' => 'flag=:flag AND merchant_id=:merchant_id AND total_points >= :points_small AND type=:type',
                'params' => array(
                    ':flag' => FLAG_NO,
                    ':merchant_id' => $merchant_id,
                    ':points_small' => empty($points_rules[$key])?0:$points_rules[$key],
                    ':type' => USER_TYPE_WANQUAN_MEMBER
                )
            ));
        }
        return $count;
    }

    //整合数据，根据user_grade_code 作为键值
    private function data_transfer($data) {
        if (empty($data)) {
            return array();
        }
        $new_data = array();
        foreach ($data as $value) {
            $arr = array();
            $arr['merchant_id'] = $value['merchant_id'];
            $arr['name'] = $value['name'];
            $arr['points_rule'] = $value['points_rule'];
            $arr['discount'] = $value['discount'];
            $arr['discount_illustrate'] = $value['discount_illustrate'];
            $arr['membership_card_name'] = $value['membership_card_name'];
            $arr['membercard_img'] = $value['membercard_img'];
            $arr['if_default'] = $value['if_default'];
            $arr['if_hideword'] = $value['if_hideword'];
            $arr['rule_type'] = $value['rule_type'];
            $arr['birthday_rate'] = $value['birthday_rate'];
            $arr['points_ratio'] = $value['points_ratio'];
            $arr['flag'] = $value['flag'];
            $new_data[$value['user_grade_code']] = md5(implode("", $arr));
        }
        return $new_data;
    }

    /**
     * 获取会员等级设置(草稿)
     * $merchant_id 商户id
     */
    public function getSetUserGradeDraft($merchant_id) {
        $result = array();
        $criteria = new CDbCriteria();
        $criteria->order = 'points_rule asc';
        if (!empty($merchant_id)) {
            $criteria->addCondition('merchant_id=:merchant_id and flag=:flag');
            $criteria->params = array(
                ':merchant_id' => $merchant_id,
                ':flag' => FLAG_NO
            );
        } else {
            $criteria->addCondition('flag=:flag');
            $criteria->params = array(
                ':flag' => FLAG_NO
            );
        }
        $model = UserGradeDraft::model()->findAll($criteria);
        $countusergrade = UserGradeDraft::model()->count($criteria);
        $data = array();
        if (!empty($model)) {
            $points_rules = array();
            $i = 0;
            foreach ($model as $k => $v) {
                $points_rules[$i] = empty($v->points_rule)?0:$v->points_rule;
                $data[$i]['id'] = $v->id;
                $data[$i]['merchant_id'] = $v->merchant_id;
                $data[$i]['name'] = $v->name;
                $data[$i]['points_rule'] = $v->points_rule;
                $data[$i]['discount'] = $v->discount * 10;
                $data[$i]['discount_illustrate'] = $v->discount_illustrate;
                $data[$i]['membercard_img'] = $v->membercard_img;
                $data[$i]['create_time'] = $v->create_time;
                $data[$i]['flag'] = $v->flag;
                $data[$i]['membership_card_name'] = $v->membership_card_name;
                $data[$i]['points_ratio'] = $v->points_ratio;
                $data[$i]['if_default'] = $v->if_default;
                $data[$i]['if_hideword'] = $v->if_hideword;
                $data[$i]['rule_type'] = $v->rule_type;
                $data[$i]['birthday_rate'] = $v->birthday_rate;
                $data[$i]['user_grade_code'] = $v->user_grade_code;
                $i ++;
            }
            foreach($data as $key => &$value){
                $value['count'] = $this->getUserCount($key, $merchant_id,$points_rules);
            }
            $result ['status'] = ERROR_NONE;
            $result ['data'] = $data;
            $result ['countusergrade'] = $countusergrade;
        } else {
            $result ['status'] = ERROR_NO_DATA;
            $result['errMsg'] = '无此数据'; //错误信息
        }
        return json_encode($result);
    }

    /**
     * 发布更新会员等级
     * $merchant_id 商户id
     * $post 添加属性的数组
     * cover  会员卡图片地址
     * cardName  会员卡名称
     */
    public function releaseUserGrade() {
        $merchant_id = Yii::app()->session['merchant_id'];
        $Grade = json_decode($this->getSetUserGrade($merchant_id), true);
        $GradeDraft = json_decode($this->getSetUserGradeDraft($merchant_id), true);
        $user_grade = array();
        $user_grade_default = array();
        if (isset($Grade['data']) && !empty($Grade['data'])) {
            $user_grade = $this->data_transfer($Grade['data']); //整合数据，根据user_grade_code 作为键值
        }
        if (isset($GradeDraft['data']) && !empty($GradeDraft['data'])) {
            $user_grade_default = $this->data_transfer($GradeDraft['data']);
        }
        $transaction = Yii::app()->db->beginTransaction(); //开启事务
        try {
            $ret['status'] = ERROR_SAVE_FAIL;
            foreach ($user_grade_default as $key => $grades) {
                if (array_key_exists($key, $user_grade)) {//update user grade
                    if ($user_grade[$key] != $grades) {
                        $ret = $this->update_user_grade_from_default($key, $GradeDraft);
                        if ($ret['status'] == ERROR_SAVE_FAIL) {
                            throw new Exception('update error:更改会员等级信息失败');
                            $ret['errMsg'] = ERROR_SAVE_FAIL;
                        }
                    }
                } else {// add new user grade
                    $ret = $this->insert_user_grade_from_default($key, $GradeDraft);
                    if ($ret['status'] == ERROR_SAVE_FAIL) {
                        throw new Exception('update error:更改会员等级信息失败');
                        $ret['errMsg'] = ERROR_SAVE_FAIL;
                    }
                }
            }
            foreach ($user_grade as $key => $grades) {
                if (!array_key_exists($key, $user_grade_default)) {//delete user grade
                    $model = UserGrade::model()->find('user_grade_code=:user_grade_code', array(':user_grade_code' => $key));
                    if(!empty($model)){
                        $model->flag = 2 ;
                        if ($model->save()) {
                            $ret['status'] = ERROR_NONE;
                        } else {
                            throw new Exception('update error:更改会员等级信息失败');
                            $ret['errMsg'] = '还原失败';
                        }
                    }
                }
            }

            $transaction->commit();
            return json_encode($ret);
        } catch (Exception $e) {
            $transaction->rollback();
            echo $e->getMessage();
        }
    }

    //根据user_grade_code更新user_grade 表
    private function update_user_grade_from_default($user_grade_code, $user_grade_default) {
        $result['status'] = ERROR_SAVE_FAIL;
        foreach ($user_grade_default['data'] as $default_grade) {
            if ($user_grade_code == $default_grade['user_grade_code']) {
                $model = UserGrade::model()->find('user_grade_code=:user_grade_code', array(':user_grade_code' => $user_grade_code));
                $model->name = $default_grade['name'];
                $model->points_rule = $default_grade['points_rule'];
                $model->discount = $default_grade['discount'] / 10;
                $model->discount_illustrate = $default_grade['discount_illustrate'];
                $model->membership_card_name = $default_grade['membership_card_name'];
                $model->if_hideword = $default_grade['if_hideword'];
                $model->rule_type = $default_grade['rule_type'];
                $model->birthday_rate = $default_grade['birthday_rate'];
                $model->points_ratio = $default_grade['points_ratio'];
                $model->membercard_img = $default_grade['membercard_img'];
                if ($model->save()) {
                    $result['status'] = ERROR_NONE;
                } else {
                    $result['errMsg'] = '修改失败';
                }
                break;
            }
        }
        return $result;
    }

    private function insert_user_grade_from_default($user_grade_code, $user_grade_default) {
        $result['status'] = ERROR_SAVE_FAIL;
        foreach ($user_grade_default['data'] as $default_grade) {
            if ($user_grade_code == $default_grade['user_grade_code']) {
                $model = new UserGrade();
                $model->merchant_id = Yii::app()->session['merchant_id'];
                $model->name = $default_grade['name'];;
                $model->points_rule = $default_grade['points_rule'];
                $model->discount = $default_grade['discount'] / 10;
                $model->discount_illustrate = $default_grade['discount_illustrate'];
                $model->points_ratio = $default_grade['points_ratio'];
                $model->create_time = date('Y-m-d H:i:s');
                $model->if_hideword = $default_grade['if_hideword'];
                $model->rule_type = $default_grade['rule_type'];
                $model->birthday_rate = $default_grade['birthday_rate'];
                $model->user_grade_code = $default_grade['user_grade_code'];
                $model->membercard_img = $default_grade['membercard_img'];
                $model->membership_card_name = $default_grade['membership_card_name'];
                if ($model->save()) {
                    $result ['status'] = ERROR_NONE;
                    $result ['errMsg'] = '';
                } else {
                    $result ['status'] = ERROR_SAVE_FAIL; // 状态码
                    $result ['errMsg'] = '数据保存失败'; // 错误信息
                }
                break;
            }
        }
        return $result;
    }

    /**
     * 还原上次的发布点
     */
    public function revertUserGrade() {
        $merchant_id = Yii::app()->session['merchant_id'];
        $Grade = json_decode($this->getSetUserGrade($merchant_id), true);
        $GradeDraft = json_decode($this->getSetUserGradeDraft($merchant_id), true);
        $user_grade = array();
        $user_grade_default = array();
        if (isset($Grade['data']) && !empty($Grade['data'])) {
            $user_grade = $this->data_transfer($Grade['data']); //整合数据，根据user_grade_code 作为键值
        }
        if (isset($GradeDraft['data']) && !empty($GradeDraft['data'])) {
            $user_grade_default = $this->data_transfer($GradeDraft['data']);
        }
        $transaction = Yii::app()->db->beginTransaction(); //开启事务
        try {
            $result['status'] = ERROR_SAVE_FAIL;
            $result['errMsg'] = '还原失败';
            foreach ($user_grade as $key => $grades) {
                if (array_key_exists($key, $user_grade_default)) {
                    if ($user_grade_default[$key] != $grades) {//update user grade draft
                        $result = $this->update_user_grade_default_from_grade($key, $Grade);
                        if ($result['status'] == ERROR_SAVE_FAIL) {
                            throw new Exception('update error:更改会员等级信息失败');
                        }
                    }
                } else {// restore the delete data from user grade draft
                    $model = UserGradeDraft::model()->find('user_grade_code=:user_grade_code', array(':user_grade_code' => $key));
                    if(!empty($model)){
                        $model->flag = $model->flag == 1 ? 2 :1;
                        if ($model->save()) {
                            $result['status'] = ERROR_NONE;
                        } else {
                            throw new Exception('update error:更改会员等级信息失败');
                            $result['errMsg'] = '还原失败';
                        }
                    }
                }
            }
            foreach ($user_grade_default as $key => $grades) {
                if (!array_key_exists($key, $user_grade)) {// delete the new record from user grade draft
                    $model = UserGradeDraft::model()->find('user_grade_code=:user_grade_code', array(':user_grade_code' => $key));
                    if(!empty($model)){
                        $model->flag = 2 ;
                        if ($model->save()) {
                            $result['status'] = ERROR_NONE;
                        } else {
                            throw new Exception('update error:更改会员等级信息失败');
                            $result['errMsg'] = '还原失败';
                        }
                    }
                }
            }

            $transaction->commit();
            return json_encode($result);
        } catch (Exception $e) {
            $transaction->rollback();
            echo $e->getMessage();
        }

        return json_encode($result);
    }

    //根据user_grade_code更新user_grade_draft 表
    private function update_user_grade_default_from_grade($user_grade_code, $user_grade) {
        $result['status'] = ERROR_SAVE_FAIL;
        foreach ($user_grade['data'] as $grade) {
            if ($user_grade_code == $grade['user_grade_code']) {
                $model = UserGradeDraft::model()->find('user_grade_code=:user_grade_code', array(':user_grade_code' => $user_grade_code));
                if(!empty($model)){
                    $model->name = $grade['name'];
                    $model->points_rule = $grade['points_rule'];
                    $model->discount = $grade['discount'] / 10;
                    $model->discount_illustrate = $grade['discount_illustrate'];
                    $model->membership_card_name = $grade['membership_card_name'];
                    $model->if_hideword = $grade['if_hideword'];
                    $model->membercard_img = $grade['membercard_img'];
                    $model->flag = $grade['flag'];
                    $model->rule_type = $grade['rule_type'];
                    $model->birthday_rate = $grade['birthday_rate'];
                    $model->points_ratio = $grade['points_ratio'];
                    if ($model->save()) {
                        $result['status'] = ERROR_NONE;
                    } else {
                        $result['errMsg'] = '修改失败';
                    }
                    break;
                }
            }
        }

        return $result;
    }

    public function createUserGradeCode() {
        $merchant_id = Yii::app()->session['merchant_id'];
        $criteri = new CDbCriteria();
        $criteri->order = 'id desc';
        $user = UserGradeDraft::model()->find($criteri);
        $id = $user->id;
        if ($id) {
            $code = $id + 1;
        } else {
            $code = 1;
        }
        $num = $this->add_zero($code);
        $jdh = "GR" . date("Ymd") .$merchant_id. $num;
        return $jdh;
    }

    private function add_zero($num, $count = 3) {
        if (!$num) {
            return "";
        }
        $add_str = "";
        $num_count = strlen($num);
        $add_zero_count = $count - $num_count;
        if ($add_zero_count <= 0) {
            return $num;
        }
        if ($add_zero_count) {
            for ($ii = 0; $ii < $add_zero_count; $ii++) {
                $add_str .= "0";
            }
        }
        return $add_str . $num;
    }

    /**
     * 添加会员等级
     * @param unknown $data
     * @return string
     */
    public function addUserGrade($data) {
        try{
            $ret = $this->validate_elements($data);
            if ($ret) {
                throw new Exception('验证失败');
            }
            $post = $data['UserGrade'];
            $name = trim($post['name']);
            $discount = !empty($post['discount']) ? $post['discount'] : '';
            $user_grade_code = !empty($post['user_grade_code']) ? $post['user_grade_code'] : '';
            $points_rule = $post['points_rule'];
            $points_ratio = !empty($post['points_ratio']) ? $post['points_ratio'] : '1';
            $discount_illustrate = $post['discount_illustrate'];
            $cover = isset($data['cover']) ? $data['cover'] : '';
            $cardName = isset($data['card_name']) ? trim($data['card_name']) : '';
            $if_hideword = isset($data['if_hideword']) && $data['if_hideword'] == IF_HIDEWORD_YES ? IF_HIDEWORD_YES : IF_HIDEWORD_NO;
            $rule_type = $post['rule_type'];
            $birthday_rate = $post['birthday_rate'];
            $merchant_id = Yii::app()->session['merchant_id'];
            $usergrade = UserGradeDraft::model()->count('merchant_id=:merchant_id and flag=:flag', array(':merchant_id' => $merchant_id, ':flag' => FLAG_NO));
            if ($usergrade < 5) {
                $model = new UserGradeDraft();
                $model->merchant_id = $merchant_id;
                $model->name = $name;
                $model->points_rule = $points_rule;
                $model->discount = $discount / 10;
                $model->discount_illustrate = $discount_illustrate;
                $model->points_ratio = $points_ratio;
                $model->create_time = date('Y-m-d H:i:s');
                $model->if_hideword = $if_hideword;
                $model->rule_type = $rule_type;
                $model->birthday_rate = $birthday_rate;
                $model->user_grade_code = $user_grade_code;
                if (!empty($cover)) {
                    $model->membercard_img = $cover;
                }
                $model->membership_card_name = $cardName;
                if ($model->save()) {
                    $result ['status'] = ERROR_NONE;
                } else {
                    $result ['status'] = ERROR_SAVE_FAIL;
                    throw new Exception('数据保存失败');
                }
            } else {
                $result ['status'] = ERROR_REQUEST_FAIL;
                throw new Exception('会员等级最多可添加5个等级');
            }
        } catch (Exception $e) {
            $result['status'] = isset($result['status']) ? $result['status'] : ERROR_EXCEPTION;
            $result['errMsg'] = $e->getMessage(); //错误信息
        }
        return json_encode($result);
    }

    /**
     * 添加会员等级(验证字段)
     * $merchant_id 商户id
     * $post 添加属性的数组
     * cover  会员卡图片地址
     * cardName  会员卡名称
     */
    private function validate_elements($data,$id='') {
        $merchant_id = Yii::app()->session['merchant_id'];
        if (isset($data['UserGrade']) && !empty($data['UserGrade'])) {
            $post = $data['UserGrade'];
            $flag = false;
            //验证会员等级名称
            if (empty($post['name'])) {
                $flag = true;
                Yii::app()->user->setFlash('name', '会员等级名称必填');
            }
            if (!empty($post['name'])) {
                if (preg_match('/^[a-zA-Z_\x{4e00}-\x{9fa5}]+$/u', trim($post['name']))) {

                } else {
                    $flag = true;
                    Yii::app()->user->setFlash('name', '不允许含有数字,符合,空格');
                }
            }
            //验证会员特权
            if ($post['discount']) {
                if (preg_match('/^[a-zA-Z_\x{4e00}-\x{9fa5}]+$/u', trim($post['discount']))) {
                    $flag = true;
                    Yii::app()->user->setFlash('discount', '会员特权格式不正确');
                } else {
                    if ($post['discount'] < 0) {
                        $flag = true;
                        Yii::app()->user->setFlash('discount', '会员特权不为负数');
                    }
                }
                if ($post['discount'] == 0.00) {
                    $flag = true;
                    Yii::app()->user->setFlash('discount', '请填写正确的折扣格式');
                }
                if ($post['discount'] > 10) {
                    $flag = true;
                    Yii::app()->user->setFlash('discount', '会员特权只能输入（1-10）之间');
                }
            }
            if ($post['discount'] === '0') {
                $flag = true;
                Yii::app()->user->setFlash('discount', '会员特权只能输入（1-10）之间');
            }
            //验证积分要求(默认等级修改不要求)
            if (isset($_GET['k']) && $_GET['k'] != USER_GRADE_DEFAULT_YES){
                if (isset($post['points_rule']) && $post['points_rule'] == '0') {
                    $flag = true;
                    Yii::app()->user->setFlash('points_rule', '积分要求不为0');
                } else {
                    if (empty($post['points_rule'])) {
                        $flag = true;
                        Yii::app()->user->setFlash('points_rule', '积分要求必填');
                    } else {
                        $rs = json_decode($this->checkUserPointRule($merchant_id, $post['points_rule'],$id), true);
                        if ($rs['status'] == ERROR_DUPLICATE_DATA) {
                            //积分要求重复
                            $flag = 1;
                            Yii::app()->user->setFlash('points_rule', '积分不能重复');
                        } 
                    }
                }
                if (isset($post['points_rule']) && $post['points_rule']) {
                    if (preg_match('/^[a-zA-Z_\x{4e00}-\x{9fa5}]+$/u', trim($post['points_rule']))) {
                        $flag = true;
                        Yii::app()->user->setFlash('points_rule', '积分要求格式不正确');
                    } else {
                        $flags = 0;
                        if ($post['points_rule'] < 0) {
                            $flag = true;
                            $flags = 1;
                            Yii::app()->user->setFlash('points_rule', '积分要求不为负数');
                        }
                        if ($flags == 0) {
                            if (preg_match('/^[1-9]\d*|0$/', trim($post['points_rule']))) {

                            } else {
                                $flag = true;
                                Yii::app()->user->setFlash('points_rule', '积分要求为整数');
                            }
                        }
                    }
                }
            }
            //验证积分规则
            if ($post['points_ratio'] == '0') {
                $flag = true;
                Yii::app()->user->setFlash('points_ratio', '积分规则不为0');
            } else {
                if (empty($post['points_ratio'])) {

                }
            }
            if (!empty($post['points_ratio'])) {
                if (preg_match('/^[a-zA-Z_\x{4e00}-\x{9fa5}]+$/u', trim($post['points_ratio']))) {
                    $flag = true;
                    Yii::app()->user->setFlash('points_ratio', '积分规则格式不正确');
                } else {
                    if ($post['points_ratio'] <= 0) {
                        $flag = true;
                        Yii::app()->user->setFlash('points_ratio', '积分规则不为负数');
                    }
                    $ratio = explode('.', $post['points_ratio']);
                    $points = count($ratio);
                    if ($points > 1) {
                        $flag = 1;
                        Yii::app()->user->setFlash('points_ratio', '积分规则不能有小数点');
                    }
                }
            }
            //验证生日积分倍率
            if ($post['birthday_rate'] == '0') {
                $flag = true;
                Yii::app()->user->setFlash('birthday_rate', '积分倍率不能为0');
            } else {
                if (empty($post['birthday_rate'])) {

                }
            }
            if (!empty($post['birthday_rate'])) {
                if (preg_match('/^[a-zA-Z_\x{4e00}-\x{9fa5}]+$/u', trim($post['points_ratio']))) {
                    $flag = true;
                    Yii::app()->user->setFlash('birthday_rate', '积分倍率格式不正确');
                } else {
                    if ($post['birthday_rate'] <= 0) {
                        $flag = true;
                        Yii::app()->user->setFlash('birthday_rate', '倍率不能为负数');
                    }
                }
            }
            //验证会员卡名称
            if (empty($data['card_name'])) {
                $flag = true;
                Yii::app()->user->setFlash('card_name', '会员卡名称必填');
            }
            if (!empty($data['card_name'])) {
                if (preg_match('/^[a-zA-Z_\x{4e00}-\x{9fa5}]+$/u', trim($_POST['card_name']))) {

                } else {
                    $flag = true;
                    Yii::app()->user->setFlash('card_name', '会员卡名称格式不允许有数字,符合,空格');
                }
            }
            //验证会员卡图片样式
            if (empty($data['cover']) && empty($id)) {
                $flag = true;
                Yii::app()->user->setFlash('cover', '请选择样式或者上传样式');
            }
            //验证会员等级名称是否存在
            if (!empty($post['name'])) {
                $merchant_id = Yii::app()->session['merchant_id'];
                $is_exit = $this->isUserGradeNameExit($post['name'], $id, $merchant_id);
                if ($is_exit) {
                    $flag = true;
                    Yii::app()->user->setFlash('name', '会员等级名称已存在');
                }
            }
            //判断规则积分类型是否存在
            if (empty($post['rule_type']) && isset($_GET['k']) && $_GET['k'] != USER_GRADE_DEFAULT_YES) {
                $flag = true;
                Yii::app()->user->setFlash('rule_type', '请选择规则积分类型');
            }
//             var_dump($post,$flag);exit;
            return $flag;
        }
    }

    /**
     * 获取会员等级详情(草稿)
     * $id  会员等级id
     */
    public function getUserGradeDraftDetails($id) {
        $model = UserGradeDraft::model()->findByPk($id);
        return $model;
    }

    /**
     * 编辑会员等级
     * $id  会员等级id
     * $post 添加属性的数组
     */
    public function editUserGrade($data, $id) {
        $result = array();
        try{
            //添加会员等级(验证字段)
            $ret = $this->validate_elements($data,$id);
            if (!$ret) {
                $post = $data['UserGrade'];
                $model = UserGradeDraft::model()->findByPk($id);
                if (empty($model)) {
                    $result['status'] = ERROR_NO_DATA;
                    throw new Exception('修改的会员等级不存在');
                } else {
                    $discount = !empty($post['discount']) ? $post['discount'] : '';
                    $points_rule = !empty($post['points_rule']) ? $post['points_rule'] : '';
                    $cover = isset($data['cover']) ? $data['cover'] : '';
                    $cardName = isset($data['card_name']) ? trim($data['card_name']) : '';
                    $if_hideword = isset($data['if_hideword']) && $data['if_hideword'] == IF_HIDEWORD_YES ? IF_HIDEWORD_YES : IF_HIDEWORD_NO;
                    $rule_type = !empty($post['rule_type']) ? $post['rule_type'] : 1;
                    $birthday_rate = $post['birthday_rate'];
                    $model->name = trim($post['name']);
                    if (!empty($points_rule) || (isset($_GET['k']) && $_GET['k'] == USER_GRADE_DEFAULT_YES)) {
                        if ($model->points_rule != $points_rule) {
                            $model->points_rule = $points_rule;
                        }
                        $model->points_ratio = !empty($post['points_ratio']) ? $post['points_ratio'] : '1';
                        ;
                        $model->discount_illustrate = $post['discount_illustrate'];
                        if ($model->discount != $discount) {
                            $model->discount = $discount / 10;
                        }
                        if (!empty($cover)) {
                            $model->membercard_img = $cover;
                        }
                        $model->membership_card_name = $cardName;
                        $model->last_time = date('Y-m-d H:i:s');
                        $model->if_hideword = $if_hideword;
                        $model->rule_type = $rule_type;
                        $model->birthday_rate = $birthday_rate;
                        if ($model->update()) {
                            $result ['status'] = ERROR_NONE;
                            $result['errMsg'] = '';
                        }else{
                            $result ['status'] = ERROR_SAVE_FAIL;
                            throw new Exception('数据保存失败');
                        }
                    }
                }
            } else {
                $result ['status'] = ERROR_SAVE_FAIL;
                throw new Exception('数据验证失败');
            }
        } catch (Exception $e) {
            $result['status'] = isset($result['status']) ? $result['status'] : ERROR_EXCEPTION;
            $result['errMsg'] = $e->getMessage(); //错误信息
        }
        return json_encode($result);
    }

    /**
     * 删除会员等级（会员人数为0的才能删）
     * $id  会员等级id
     */
    public function delUserGrade($id, $merchant_id) {
        $result = array();

        $is_exit = $this->isUserCount($id, $merchant_id);
        if ($is_exit) {//存在会员人数  不能删除
            $result ['status'] = ERROR_NONE;
            $result['errMsg'] = ''; //错误信息
            $url = Yii::app()->createUrl('crm/UserGrade/setUserGrade');
            echo "<script>alert('该会员等级存在会员，不能删除');window.location.href='$url'</script>";
        } else {//不存在  进行删除
            $model = UserGradeDraft::model()->findByPk($id);
            $model->flag = FLAG_YES;
            if ($model->save()) {
                $result ['status'] = ERROR_NONE;
                $result['errMsg'] = ''; //错误信息
            } else {
                $result ['status'] = ERROR_SAVE_FAIL;
                $result['errMsg'] = '数据保存失败'; //错误信息
            }
        }
        return json_encode($result);
    }

    /**
     * 判断会员等级是否存在会员
     * $membershipgrade_id  用户表的会员等级id
     * $merchant_id 商户id
     * return  true：存在       false：不存在
     */
    public function isUserCount($membershipgrade_id, $merchant_id) {
        $model = User::model()->findAll('membershipgrade_id=:membershipgrade_id and merchant_id=:merchant_id', array(':membershipgrade_id' => $membershipgrade_id, ':merchant_id' => $merchant_id));
        if (count($model) > 0) {
            return true;
        }
        return false;
    }

    /**
     * 清除草稿的会员等级条件
     */
    public function clearGradeType($GradeDraft, $rule_type) {
        $default = $GradeDraft['datas']['list'];
        $another = $GradeDraft['data']['list'];
        //清空默认等级的条件
        $model = UserGradeDraft::model()->findByPk($default['id']);
        $model->rule_type = $rule_type;
        $model->save();
        foreach ($another as $k => $v) {
            $model = UserGradeDraft::model()->findByPk($v['id']);
            $model->rule_type = $rule_type;
            $model->points_rule = null;
            $model->save();
        }
    }
    /**********************************  验证规则   *****************************************/
    /**
     * 根据id获取会员等级名称
     * @param  $userGrandId
     * @return string
     */
    public function getUserGrandName($userGrandId) {
        $model = UserGrade::model()->findByPk($userGrandId);
        if (!empty($model)) {
            return $model->name;
        } else {
            return '';
        }
    }

    /**
     * 判断会员等级名称是否存在
     * $name  会员等级名称
     * $merchant_id 商户id
     * return  true：存在       false：不存在
     */
    public function isUserGradeNameExit($name, $id = '', $merchant_id) {
        if (empty($id)) {
            $model = UserGradeDraft::model()->findAll('name=:name and flag=:flag and merchant_id=:merchant_id', array(
                ':name' => trim($name),
                ':flag' => FLAG_NO,
                ':merchant_id' => $merchant_id
            ));
            if (count($model) > 0) {
                return true;
            }
            return false;
        } else {
            $model = UserGradeDraft::model()->findAll('name=:name and id!=:id and flag=:flag and merchant_id=:merchant_id', array(
                ':name' => trim($name),
                ':id' => $id,
                ':flag' => FLAG_NO,
                ':merchant_id' => $merchant_id
            ));
            if (count($model) > 0) {
                return true;
            }
            return false;
        }
    }

    /**
     * 根据id获取积分要求
     * @param unknown $userGrandId
     * @return string
     */
    public function getUserGrandPointRule($userGrandId) {
        $model = UserGrade::model()->findByPk($userGrandId);
        if (!empty($model)) {
            return $model->points_rule;
        } else {
            return '';
        }
    }

    /**
     * 校验积分要求是否重复
     * id 编辑的ID
     * point_rule 积分要求
     */
    public function checkUserPointRule($merchant_id, $point_rule, $id = '') {
        $result = array();
        try {
            if (empty($merchant_id)) {
                $result['status'] = ERROR_PARAMETER_FORMAT;
                throw new Exception('参数merchant_id不能为空');
            }
            $criteria = new CDbCriteria();
            $criteria->addCondition('merchant_id = :id');
            $criteria->params[':id'] = $merchant_id;
            $criteria->addCondition('flag = :flag');
            $criteria->params[':flag'] = FLAG_NO;
            $model = UserGradeDraft::model()->findAll($criteria);
            if (isset($model)) {
                $flag = true;
                foreach ($model as $key => $value) {
                    if (isset($id)) {
                        //编辑会员等级
                        if ($value['points_rule'] == $point_rule && $value['id'] != $id) {
                            //有积分要求相同
                            $flag = false;
                            $result ['status'] = ERROR_DUPLICATE_DATA;
                            $result['errMsg'] = ''; //错误信息
                        }
                    } else {
                        //添加会员等级
                        if ($value['points_rule'] == $point_rule) {
                            //有积分要求相同
                            $flag = false;
                            $result ['status'] = ERROR_DUPLICATE_DATA;
                            $result['errMsg'] = ''; //错误信息
                        }
                    }
                }
                if ($flag) {
                    //没有相同数据
                    $result ['status'] = ERROR_NONE;
                    $result['errMsg'] = ''; //错误信息
                }
            } else {
                $result ['status'] = ERROR_EXCEPTION;
                $result['errMsg'] = ''; //错误信息
            }
        } catch (Exception $e) {
            $result['status'] = isset($result['status']) ? $result['status'] : ERROR_EXCEPTION;
            $result['errMsg'] = $e->getMessage(); //错误信息
        }
        return json_encode($result);
    }
    /************************************** 计划任务  ***********************************/
    /**
     * (计划任务) 初始化会员累计积分和消费金额的脚本
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
     * (计划任务)年底重置年累计积分和消费的脚本
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

    public function reduceDimension($arr, &$rt)
    {
        if (is_array($arr)) {
            foreach ($arr as $v) {
                if (is_array($v)) {
                    $this->reduceDimension($v, $rt);
                } else {
                    $rt[] = $v;
                }
            }
        }
        return $rt;
    }

    //用户详情
    /**
     * merchantId 商户id
     * account   账号
     * id       会员id
     */
    public function UserDetail($merchantId,$id)
    {
        //返回结果
        $result = array('status'=>1,'errMsg'=>'null','data'=>'null');
        $flag   = 0;
        if(isset($merchantId) && empty($merchantId))
        {
            $result['status'] = ERROR_PARAMETER_MISS;
            $result['errMsg'] = '参数merchantId缺失';
            $flag = 1;
        }
        if($flag == 0)
        {
            $data = array();
            if(!empty($id))
            {
                $user = User::model()->find('id=:id and merchant_id=:merchant_id and flag=:flag',array(':id'=>$id,':merchant_id'=>$merchantId,':flag'=>FLAG_NO));
                if($user){
                    $data['type']                   = $user->type;//用户类型 1会员 2微信粉丝 3支付宝粉丝
                    $data['account']                = $user->account;//账号
                    $data['avatar']                 = $user->avatar;//头像
                    $data['nickname']               = $user->nickname;//昵称
                    $data['name']                   = $user->name;//真实姓名
                    $data['sex']                    = $user->sex;//性别
                    $data['birthday']               = $user->birthday;//生日
                    $data['social_security_number'] = $user->social_security_number;//身份证
                    $data['email']                  = $user->email;//邮箱
                    $data['marital_status']         = $user->marital_status;//婚姻状况
                    $data['work']                   = $user->work;//工作
                    $data['free_secret']            = $user->free_secret;//小额免密金额
                    $data['money']                  = $user->money;//储值金额
                    $data['points']                 = $user->points;//会员积分
                    $data['membershipgrade_id']     = $user->membershipgrade_id;//会员等级id
                    //查询会员等级名称
                    $grade = UserGrade::model()->findByPk($user['membershipgrade_id']);
                    if (empty($grade)) {
                        $data['grade_name'] = '无'; //会员等级名称
                    }else {
                        $data['grade_name'] = $grade['name']; //会员等级名称
                    }
                    $data['membership_card_no']     = $user->membership_card_no;//会员卡号
                    $data['login_time']             = $user->login_time;//最后登录时间
                    $data['login_ip']               = $user->login_ip;//最后登录ip
                    $data['regist_time']            = $user->regist_time;//注册时间
                    $data['address']                = $user->address;//地址
                    $data['from']                   = $user->from;//来源(多个来源)
                    $data['alipay_fuwu_id']         = $user->alipay_fuwu_id;//服务窗账号id
                    $data['alipay_status']          = $user->alipay_status;//支付宝用户关注状态
                    $data['alipay_avatar']          = $user->alipay_avatar;//支付宝服务窗头像
                    $data['alipay_nickname']        = $user->alipay_nickname;//支付宝用户昵称
                    $data['alipay_province']        = $user->alipay_province;//支付宝用户注册所填省份
                    $data['alipay_city']            = $user->alipay_city;//支付宝用户注册所填城市
                    $data['alipay_gender']          = $user->alipay_gender;//支付宝用户性别 M男性 F女性
                    $data['alipay_user_type_value'] = $user->alipay_user_type_value;//支付宝用户类型 1公司账号 2个人账号
                    $data['alipay_is_licence_auth'] = $user->alipay_is_licence_auth;//支付宝用户是否经过营业执照认证 T通过 F没有通过
                    $data['alipay_is_certified']    = $user->alipay_is_certified;//支付宝用户是否通过实名认证 T通过 F没有实名认证
                    $data['alipay_certified_grade_a'] = $user->alipay_certified_grade_a;//支付宝用户是否A类认证 T是A类认证 F非A类认证
                    $data['alipay_is_student_certified'] = $user->alipay_is_student_certified;//支付宝用户是否是学生 T是学生 F不是学生
                    $data['alipay_is_bank_auth']    = $user->alipay_is_bank_auth;//支付宝用户是否经过银行卡认证 T经过银行卡认证 F未经过银行卡认证
                    $data['alipay_is_mobile_auth']  = $user->alipay_is_mobile_auth;//支付宝用户是否经过手机认证 T经过手机认证 F未经过手机认证
                    $data['alipay_user_status']     = $user->alipay_user_status;//支付宝用户状态 Q快速注册用户 T已认证用户 B被冻结账户 W已注册未激活账户
                    $data['alipay_is_id_auth']      = $user->alipay_is_id_auth;//支付宝用户是否身份证认证 T身份证认证 F非身份证认证
                    $data['alipay_subscribe_time']  = $user->alipay_subscribe_time;//支付宝用户关注时间
                    $data['alipay_cancel_subscribe_time'] = $user->alipay_cancel_subscribe_time;//支付宝用户取消关注时间
                    $data['alipay_subscribe_store_id'] = $user->alipay_subscribe_store_id;//支付宝用户关注入口门店
                    $data['register_address']       = $user->register_address;//注册地址（省,市）
                    $data['wechat_status']          = $user->wechat_status;//微信用户关注状态 1 未关注 2已关注 3取消关注
                    $data['wechat_id']              = $user->wechat_id;//微信用户openid
                    $data['wechat_nickname']        = $user->wechat_nickname;//微信用户昵称
                    $data['wechat_sex']             = $user->wechat_sex;//微信用户性别 1男性 2女性
                    $data['wechat_country']         = $user->wechat_country;//微信用户所在国家
                    $data['wechat_province']        = $user->wechat_province;//微信用户所在省份
                    $data['wechat_city']            = $user->wechat_city;//微信用户所在城市
                    $data['wechat_language']        = $user->wechat_language;//微信用户的语言
                    $data['wechat_headimgurl']      = $user->wechat_headimgurl;//微信用户头像
                    $data['wechat_unionid']         = $user->wechat_unionid;//微信用户unionid
                    $data['wechat_remark']          = $user->wechat_remark;//微信用户备注
                    $data['wechat_groupid']         = $user->wechat_groupid;//微信用户所在分组id
                    $data['wechat_subscribe_time']  = $user->wechat_subscribe_time;//微信用户关注时间
                    $data['wechat_cancel_subscribe_time'] = $user->wechat_cancel_subscribe_time;//微信用户取消关注时间
                    $data['wechat_subscribe_store_id'] = $user->wechat_subscribe_store_id;//微信用户关注入口门店
                    $data['switch']                 = $user->switch;//会员等级是否受积分限制1受限制2不受限制
                    $data['create_time']            = $user->create_time;//创建时间
                    $data['last_time']              = $user->last_time;//最近更新时间
                    $data['login_client']           = $user->login_client;//最后登录客户端
                    $data['province']               = $user->province;//省
                    $data['city']                   = $user->city;//市
                    $criteri = new CDbCriteria();
                    $criteri -> order = 'create_time asc';
                    $criteri->addcondition('flag=:flag and user_id=:user_id');
                    $criteri->params[':flag'] = FLAG_NO;
                    $criteri->params[':user_id'] = $id;
                    $usergrowuprecord = UserGrowupRecord::model()->findall($criteri);
                    if($usergrowuprecord) {
                        foreach($usergrowuprecord as $a => $b){
                            $data['list'][$a]['user_grade_name'] = $b['user_grade_name'];
                            $data['list'][$a]['create_time'] = $b['create_time'];
                        }
                    } else {
                        $data['list'] = array();
                    }
                    $usertag = UserTag::model()->findall('flag=:flag and user_id=:user_id',array(
                        ':flag' => FLAG_NO,
                        ':user_id' => $id
                    ));
                    //标签
                    $tag_value = array();
                    if($usertag){
                        foreach($usertag as $key =>$val){
                            $tag_value[$key] = $val['tag_value'];
                        }
                    }
                    $data['tag_value'] = $tag_value;
                    $group = Group::model()->findall('flag=:flag and user_id=:user_id',array(
                        ':flag' => FLAG_NO,
                        ':user_id' => $id,
                    ));
                    //分组
                    $groupname = array();
                    if($group){
                        foreach($group as $k => $v) {
                            $userGroup = UserGroup::model()->find('flag=:flag and merchant_id=:merchant_id and id=:id',array(
                                ':flag' => FLAG_NO,
                                ':merchant_id' => $merchantId,
                                ':id' => $v -> group_id
                            ));
                            $groupname[] = $userGroup['name'];
                        }
                        if($userGroup){
                            $data['group'] = $groupname;
                        } else {
                            $data['group'] = '';
                        }
                    } else {
                        $data['group'] = '';
                    }

                    $data['order'] = array();
                    $data['order_count'] = '0';
                    $data['sum_order'] = '0';
                    $pay_status = ORDER_STATUS_PAID;
                    $store = Store::model()->findall('flag=:flag and merchant_id=:merchant_id',array(
                        ':flag' => FLAG_NO,
                        ':merchant_id' => $merchantId,
                    ));
                    $store_id = array();
                    if($store){
                        foreach($store as $v){
                            $store_id[] = $v['id'];
                        }
                    }
                    //消费记录
                    $criteria = new CDbCriteria();
                    $criteria -> order = 'pay_time desc';
                    $criteria->addCondition('flag=:flag and pay_status=:pay_status');
                    $criteria->params[':flag'] = FLAG_NO;
                    $criteria->addInCondition('store_id', $store_id);
                    $criteria->params[':pay_status'] = $pay_status;
                    /*
                    if ($id){
                        $criteria->addCondition('user_id=:user_id');
                        $criteria->params[':user_id'] = $id;
                    }
                    */
                    $wechat_user_id = $data['wechat_id'];
                    $alipay_user_id = $data['alipay_fuwu_id'];
                    if ($wechat_user_id){
                        $criteria->addCondition('wechat_user_id=:wechat_user_id');
                        $criteria->params[':wechat_user_id'] = $wechat_user_id;
                    }
                    if ($alipay_user_id){
                        $criteria->addCondition('alipay_user_id=:alipay_user_id');
                        $criteria->params[':alipay_user_id'] = $alipay_user_id;
                    }

                    //分页
                    $pages = new CPagination(Order::model()->count($criteria));
                    $pages->pageSize = Yii::app() -> params['perPage'];
                    $pages->applyLimit($criteria);
                    $this->page = $pages;
                    $order = Order::model()->findall($criteria);
                    if($order){
                        $order_paymoney = Yii::app()->db->createCommand("
                                    select sum(order_paymoney)
                                    from wq_order where merchant_id=$merchantId and pay_status=$pay_status and flag=1 and (user_id=$id or wechat_user_id='$wechat_user_id' or alipay_user_id='$alipay_user_id')                            
                                ")->queryScalar();
                        //累计消费金额
                        $data['sum_order'] = $order_paymoney;
                        $data['order_count'] = Order::model()->count($criteria);
                        foreach($order as $k => $v){
                            $data['order'][$k]['pay_time'] = $v['pay_time'];
                            $data['order'][$k]['store_name'] = $v->store->name;
                            $data['order'][$k]['pay_channel'] = $v['pay_channel'];
                            $data['order'][$k]['money'] = $v['order_paymoney'];
                        }
                    }
                }
            }
            $result['status'] = ERROR_NONE;
            $result['data']   = $data;
        } else {
            $result['status'] = ERROR_NO_DATA;
            $result['errMsg'] = '无此数据';
        }
        return json_encode($result);
    }
    /**
     * 为用户设置标签
     * @param type $user_id
     * @param type $tag_value
     * @return type
     * @throws Exception
     */
    public function UserTag($user_id, $tag_value)
    {
        $result = array();
        try {
            if (empty($user_id)) {
                $result['status'] = ERROR_PARAMETER_FORMAT;
                throw new Exception('参数user_id不能为空');
            }
            if (empty($tag_value)) {
                $result['status'] = ERROR_PARAMETER_FORMAT;
                throw new Exception('参数tag_value不能为空');
            }
            $usertags = UserTag::model()->findall('flag=:flag and user_id=:user_id',array(':flag'=>FLAG_NO,':user_id'=>$user_id));
            if(!empty($usertags)){
                foreach($usertags as $val){
                    $user_tag = UserTag::model()->find('flag=:flag and id=:id',array(':flag'=>FLAG_NO,':id'=>$val['id']));
                    $user_tag -> delete();
                }
            }
            foreach($tag_value as $v){
                $usertag = new UserTag();
                $usertag -> user_id = $user_id;
                $usertag -> tag_value = $v;
                $usertag -> create_time = new CDbExpression('now()');
                if(!$usertag -> save()){
                    $result['status'] = ERROR_NO_DATA;
                    $result['errMsg'] = '保存失败';
                }
            }
            $result['status'] = ERROR_NONE;
        } catch (Exception $e) {
            $result['status'] = isset($result['status']) ? $result['status'] : ERROR_EXCEPTION;
            $result['errMsg'] = $e->getMessage(); //错误信息
        }
        return json_encode($result);
    }
	
	/**
     * 数据库初始化: 会员等级code赋值
     * @throws Exception
     */
    public function actionSetUsersGradeCode(){
        set_time_limit(0);
        $cmd = Yii::app()->db->createCommand();
        $cmd->select = 'merchant_id';
        $cmd->from = 'wq_user_grade';
        $list = $cmd->queryColumn();
        foreach ($list as $v){
            $this->SetUserGradeCode($v);
        }
        echo 'end';
    }
    private function SetUserGradeCode($merchant_id){
        $Grade = json_decode($this -> getSetUserGrade($merchant_id),true);
        $GradeDraft = json_decode($this -> getSetUserGradeDraft($merchant_id),true);
        $new_grades = array();
        if ($Grade['status'] != ERROR_NONE || $GradeDraft['status'] != ERROR_NONE){
            return;
        }
        $transaction = Yii::app()->db->beginTransaction(); //开启事务
        try {
            foreach ($Grade['data'] as $value){
                $arr = array();
                $arr['merchant_id'] = $value['merchant_id'];
                $arr['name']        = $value['name'];
                $arr['points_rule'] = $value['points_rule'];
                $arr['discount']    = $value['discount'];
                $arr['discount_illustrate'] = $value['discount_illustrate'];
                $arr['membership_card_name'] = $value['membership_card_name'];
                $arr['if_default'] = $value['if_default'];
                $arr['if_hideword'] = $value['if_hideword'];
                $arr['rule_type'] = $value['rule_type'];
                $arr['birthday_rate'] = $value['birthday_rate'];
                $num = $this->add_zero($value['id']);
                $jdh = "GR" . date("Ymd") . $num;
                $new_grades[md5(implode("", $arr))] = $jdh;
                $model = UserGrade::model()->findByPk($value['id']);
                if (empty($model)) {
                    throw new Exception('save error:会员等级草稿表信息不存在');
                } else {
                    $model->user_grade_code = $jdh;
                    if (!$model->update()) {
                        throw new Exception('save error:'.CJSON::encode($model->getErrors()));
                    }
                }
            }
            foreach($GradeDraft['data'] as $value){
                $arr = array();
                $arr['merchant_id'] = $value['merchant_id'];
                $arr['name']        = $value['name'];
                $arr['points_rule'] = $value['points_rule'];
                $arr['discount']    = $value['discount'];
                $arr['discount_illustrate'] = $value['discount_illustrate'];
                $arr['membership_card_name'] = $value['membership_card_name'];
                $arr['if_default'] = $value['if_default'];
                $arr['if_hideword'] = $value['if_hideword'];
                $arr['rule_type'] = $value['rule_type'];
                $arr['birthday_rate'] = $value['birthday_rate'];
                $key = md5(implode("", $arr));
                if(array_key_exists($key, $new_grades)){
                    $model = UserGradeDraft::model()->findByPk($value['id']);
                    if (empty($model)) {
                        throw new Exception('save error:会员等级草稿表信息不存在');
                    } else {
                        $model->user_grade_code = $new_grades[$key];
                        if (!$model->update()) {
                            throw new Exception('save error:'.CJSON::encode($model->getErrors()));
                        }
                    }
                }
            }
            echo '更新会员等级成功';
            $transaction->commit();
        }catch (Exception $e){
            $transaction->rollback();
            echo $e -> getMessage();
        }
	}
}