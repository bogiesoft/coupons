<?php

/**
 *用户分组管理
 */
class UserGroupController extends CrmController
{
    /**
     * 会员分组列表
     */
    public function actionUserGroupList() {
        $group_types = $GLOBALS['__GROUP_TYPES'];

        $merchant_id = Yii::app()->session['merchant_id'];
        //获取用户分组列表
        $list = json_decode($this->getUserGroupList($merchant_id), true);

        $this->render('userGroupList', array(
            'list' => $list,
            'pages' => $this->page,
            'group_types' => $group_types,
        ));
    }

    /**
     * 新建会员管理分组 (根据条件筛选分组)
     */
    public function actionAddFilterUserGroup() {
        $group_types = $GLOBALS['__GROUP_TYPES'];
        $tag_category_customer_values = $GLOBALS['__TAG_CATEGORY_CUSTOMER_VALUE'];

        $merchant_id = Yii::app()->session['merchant_id'];
        //获取该商户下的用户等级
        $user_grades = $this->getUserGrade($merchant_id);

        $this->render('addFilterUserGroup', array(
            'user_grades' => $user_grades,
            'group_types' => $group_types,
            'tag_category_customer_values' => $tag_category_customer_values
        ));
    }

    /**
     * 编辑会员管理分组(根据条件筛选分组)
     */
    public function actionEditFilterUserGroup() {
        if (isset($_GET['userGroupId']) && $_GET['userGroupId']) {
            $group_id = $_GET['userGroupId'];
            $merchant_id = Yii::app()->session['merchant_id'];

            //获取用户分组信息
            $user_group = $this->getUserGroupInfo($group_id);
            //获取用户等级
            $user_grades = $this->getUserGrade($merchant_id);

            $group_types = $GLOBALS['__GROUP_TYPES'];
            $tag_category_customer_values = $GLOBALS['__TAG_CATEGORY_CUSTOMER_VALUE'];

            $this->render('editFilterUserGroup', array(
                'user_group' => $user_group,
                'user_grades' => $user_grades,
                'group_types' => $group_types,
                'tag_category_customer_values' => $tag_category_customer_values,
            ));
        }
    }

    /** 筛选会员人数
     * @return array
     */
    public function actionJSGroup() {
        //筛选
        $user_ids = array();
        $user_ids = $this->JiSuan();

        $ret_html_array = $this->generateHtmlJson($user_ids, '');
        echo json_encode($ret_html_array);
    }

    /**
     * 条件筛选分组保存
     */
    public function actionSaveGroup() {
        set_time_limit(0);
        $merchant_id = Yii::app()->session['merchant_id'];
        //获取ajax 提交过来的数据
        $jsonStr = file_get_contents('php://input');
        //解析json数据
        $jsonObj = json_decode($jsonStr);
        //判断分组名称是否存在
        $userGroups = $this->checkGroupName($merchant_id, $jsonObj->group_name);
        if ($userGroups) {
            die('分组名已存在');
        } else {
            //缓存中读表达式json
            $key = 'merchant_' . $merchant_id . '_group_exp_json';
            $expJson = Yii::app()->memcache->get($key);

            if (!empty($merchant_id) && !empty($expJson)) {
                $uids = $this->JiSuan('', $expJson);
                //保存分组
                $save_res = $this->saveGroup($uids, $merchant_id, $jsonObj, $expJson);
                if ($save_res) {
                    echo 'OK';
                } else {
                    echo 'NO';
                }
            }
        }
    }

    /**
     * 编辑条件筛选分组保存
     */
    public function actionEditGroup() {
        set_time_limit(0);
        $merchant_id = Yii::app()->session['merchant_id'];
        //获取ajax 提交过来的数据
        $jsonStr = $_POST['json_str'];
        $group_id = $_POST['id'];
        if (!empty($jsonStr) && $group_id) {
            //解析json数据
            $jsonObj = json_decode($jsonStr);
            //判断分组名称是否存在
            $userGroups = $this->checkGroupName($merchant_id, $jsonObj->group_name, $group_id);
            if ($userGroups) {
                die('分组名已存在');
            } else {
                //缓存中读表达式json
                $key = 'merchant_' . $merchant_id . '_group_exp_json';
                $expJson = Yii::app()->memcache->get($key);

                if (!empty($merchant_id) && !empty($expJson)) {
                    if ($this->saveEditGroup($group_id, $jsonObj, $expJson)) {
                        echo 'OK';
                    } else {
                        echo 'NO';
                    }
                }
            }
        }
    }

    /**
     * 选则门店弹出框
     */
    public function actionFilterStoreDialog() {
        $search = '';
        if (isset($_GET['key_word'])) {
            $search['key_word'] = $_GET['key_word'];
        }

        if (isset ($_GET ['store_id_str'])) {
            $store_id_str = trim($_GET ['store_id_str']);
            $store_id_arr = array_filter(explode(',', $store_id_str));
        }

        $merchant_id = Yii::app()->session['merchant_id'];
        //获取门店列表
        $store_lists = $this->getStoreList($merchant_id, $search);

        $this->render('filterStoreDialog', array(
            'store_lists' => $store_lists,
            'pages' => $this->page,
            'store_id_arr' => $store_id_arr
        ));
    }

    /**
     * 获取门店列表
     */
    public function actionGetStoreList() {
        $merchant_id = Yii::app()->session['merchant_id'];
        $store_lists = $this->getStoreList($merchant_id, '', true);
        echo json_encode($store_lists);
    }


    /**
     * 删除已经选中的门店(去掉复选框勾选)
     */
    public function actionDelChooseStore() {
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

    /**
     * 删除会员分组管理
     */
    public function actionDelUserGroup() {
        $userGroupId = intval($_GET['userGroupId']);
        $result = json_decode($this->delUserGroup($userGroupId), true);
        if ($result['status'] == ERROR_NONE) {
            $this->redirect(array('userGroupList'));
        }
    }

    /**
     * 新建会员管理分组  (自定义分组)
     */
    public function actionAddUserGroupSelf() {
        $merchant_id = Yii::app()->session['merchant_id'];
        if (isset($_POST['UserGroup']) && !empty($_POST['UserGroup'])) {
            $name = $_POST['UserGroup']['name'];
            //extract($_POST['UserGroup']);
            $res = json_decode($this->addUserGroupSelf($merchant_id, $name), true);
            if ($res['status'] == ERROR_NONE) {
                $this->redirect(array('userGroupList'));
            }
        }
        $this->render('addUserGroupSelf');
    }

    /**
     * 修改会员管理分组  (自定义分组)
     */
    public function actionEditUserGroupSelf() {
        $merchant_id = Yii::app()->session['merchant_id'];
        $userGroupId = intval($_GET['userGroupId']);
        //获取分组信息
        $model = $this->getUserGroupDetail($userGroupId);

        if (isset($_POST['UserGroup']) && !empty($_POST['UserGroup'])) { //修改分组信息提交数据
            $name = $_POST['UserGroup']['name'];
            //extract($_POST['UserGroup']);
            $res = json_decode($this->editUserGroupSelf($merchant_id, $name, $userGroupId), true);
            if ($res['status'] == ERROR_NONE) {
                $this->redirect(array('userGroupList'));
            }
        }

        $this->render('editUserGroupSelf', array('model' => $model, 'userGroupId' => $userGroupId));
    }
}