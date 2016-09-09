<?php
class CrmController extends Controller
{
    public $layout = 'main';
    public $page = null;
    public $page1 = null;
    public $page2 = null;

    /**
     * 获取会员等级设置
     * @param int $merchant_id 商户id
     */
    public function getSetUserGrade($merchant_id)
    {
        $result = array();
        $criteria = new CDbCriteria();
        $criteria->order = 'points_rule asc';
        if (!empty ($merchant_id)) {
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
        $dataS = array();
        if (!empty($model)) {
            foreach ($model as $k => $v) {
                if ($v['if_default'] == USER_GRADE_DEFAULT_NO) {
                    $data ['list'] [$k] ['id'] = $v->id;
                    $data ['list'] [$k] ['name'] = $v->name;
                    $data ['list'] [$k] ['points_rule'] = $v->points_rule;
                    $data ['list'] [$k] ['points_ratio'] = $v->points_ratio;
                    $data ['list'] [$k] ['discount'] = $v->discount * 10;
                    $data ['list'] [$k] ['create_time'] = $v->create_time;
                    $data ['list'] [$k] ['discount_illustrate'] = $v->discount_illustrate;
                    $data ['list'] [$k] ['membercard_img'] = $v->membercard_img;
                    $data ['list'] [$k] ['membership_card_name'] = $v->membership_card_name;
                    $data ['list'] [$k] ['if_hideword'] = $v->if_hideword;
                    $count = $this->getUserCount($v->id, $merchant_id);
                    $data ['list'] [$k] ['count'] = $count;
                    $data ['list'] [$k] ['if_default'] = $v->if_default;
                    $data ['list'] [$k] ['rule_type'] = $v->rule_type;
                    $data ['list'] [$k] ['birthday_rate'] = $v->birthday_rate;
                }
            }
            $grade = UserGrade::model()->find('merchant_id=:merchant_id and if_default=:if_default and flag=:flag', array(':merchant_id' => $merchant_id, ':if_default' => USER_GRADE_DEFAULT_YES, ':flag' => FLAG_NO));
            if (!empty($grade)) {
                $dataS ['list'] ['id'] = $grade->id;
                $dataS ['list'] ['name'] = $grade->name;
                $dataS ['list'] ['points_rule'] = $grade->points_rule;
                $dataS ['list'] ['points_ratio'] = $grade->points_ratio;
                $dataS ['list'] ['discount'] = $grade->discount * 10;
                $dataS ['list'] ['create_time'] = $grade->create_time;
                $dataS ['list'] ['discount_illustrate'] = $grade->discount_illustrate;
                $dataS ['list'] ['membercard_img'] = $grade->membercard_img;
                $dataS ['list'] ['membership_card_name'] = $grade->membership_card_name;
                $dataS ['list'] ['if_hideword'] = $grade->if_hideword;
                $count = $this->getUserCount($grade->id, $merchant_id);
                $dataS ['list'] ['count'] = $count;
                $dataS ['list'] ['if_default'] = $grade->if_default;
                $dataS ['list'] ['rule_type'] = $grade->rule_type;
                $dataS ['list'] ['birthday_rate'] = $grade->birthday_rate;
            }
            $result ['status'] = ERROR_NONE;
            $result ['data'] = $data;
            $result ['datas'] = $dataS;
            $result ['countusergrade'] = $countusergrade;
        } else {
            $result ['status'] = ERROR_NO_DATA;
            $result['errMsg'] = '无此数据'; //错误信息
        }

        return json_encode($result);
    }

    /**
     * 获取会员等级设置(草稿)
     * $merchant_id 商户id
     */
    public function getSetUserGradeDraft($merchant_id)
    {
        $result = array();
        $criteria = new CDbCriteria();
        $criteria->order = 'points_rule asc';
        if (!empty ($merchant_id)) {
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
        $dataS = array();
        if (!empty($model)) {
            foreach ($model as $k => $v) {
                if ($v['if_default'] == USER_GRADE_DEFAULT_NO) {
                    $data ['list'] [$k] ['id'] = $v->id;
                    $data ['list'] [$k] ['name'] = $v->name;
                    $data ['list'] [$k] ['points_rule'] = $v->points_rule;
                    $data ['list'] [$k] ['discount'] = $v->discount * 10;
                    $data ['list'] [$k] ['points_ratio'] = $v->points_ratio;
                    $data ['list'] [$k] ['create_time'] = $v->create_time;
                    $data ['list'] [$k] ['discount_illustrate'] = $v->discount_illustrate;
                    $data ['list'] [$k] ['membercard_img'] = $v->membercard_img;
                    $data ['list'] [$k] ['membership_card_name'] = $v->membership_card_name;
                    $data ['list'] [$k] ['if_hideword'] = $v->if_hideword;
                    $count = $this->getUserCount($v->id, $merchant_id);
                    $data ['list'] [$k] ['count'] = $count;
                    $data ['list'] [$k] ['if_default'] = $v->if_default;
                    $data ['list'] [$k] ['rule_type'] = $v->rule_type;
                    $data ['list'] [$k] ['birthday_rate'] = $v->birthday_rate;
                }
            }
            $grade = UserGradeDraft::model()->find('merchant_id=:merchant_id and if_default=:if_default and flag=:flag', array(':merchant_id' => $merchant_id, ':if_default' => USER_GRADE_DEFAULT_YES, ':flag' => FLAG_NO));
            if (!empty($grade)) {
                $dataS ['list'] ['id'] = $grade->id;
                $dataS ['list'] ['name'] = $grade->name;
                $dataS ['list'] ['points_rule'] = $grade->points_rule;
                $dataS ['list'] ['points_ratio'] = $grade->points_ratio;
                $dataS ['list'] ['discount'] = $grade->discount * 10;
                $dataS ['list'] ['create_time'] = $grade->create_time;
                $dataS ['list'] ['discount_illustrate'] = $grade->discount_illustrate;
                $dataS ['list'] ['membercard_img'] = $grade->membercard_img;
                $dataS ['list'] ['membership_card_name'] = $grade->membership_card_name;
                $dataS ['list'] ['if_hideword'] = $grade->if_hideword;
                $count = $this->getUserCount($grade->id, $merchant_id);
                $dataS ['list'] ['count'] = $count;
                $dataS ['list'] ['if_default'] = $grade->if_default;
                $dataS ['list'] ['rule_type'] = $grade->rule_type;
                $dataS ['list'] ['birthday_rate'] = $grade->birthday_rate;
            }
            $result ['status'] = ERROR_NONE;
            $result ['data'] = $data;
            $result ['datas'] = $dataS;
            $result ['countusergrade'] = $countusergrade;
        } else {
            $result ['status'] = ERROR_NO_DATA;
            $result['errMsg'] = '无此数据'; //错误信息
        }

        return json_encode($result);
    }

    /**
     * 对比会员等级正式和草稿的数据
     * $Grade 当前使用的会员等级规则数据
     * $GradeDraft 修改会员等级的草稿
     */
    public function contrastGrade($Grade, $GradeDraft)
    {
        $res = array();
        //会员等级数量
        $count1 = $Grade['countusergrade'];
        $count2 = $GradeDraft['countusergrade'];
        //其他等级的二维数组
        $now = array();
        $draft = array();
        if (isset($Grade['data']['list']) && !empty($Grade['data']['list'])) {
            $now = $Grade['data']['list'];
        }
        $now = $Grade['data']['list'];
        if (isset($GradeDraft['data']['list']) && !empty($GradeDraft['data']['list'])) {
            $draft = $GradeDraft['data']['list'];
        }
        //默认等级的数组
        $now1 = array();
        $draft1 = array();
        if (isset($Grade['datas']['list']) && !empty($Grade['datas']['list'])) {
            $now1 = $Grade['datas']['list'];
        }
        $now = $Grade['data']['list'];
        if (isset($GradeDraft['datas']['list']) && !empty($GradeDraft['datas']['list'])) {
            $draft1 = $GradeDraft['datas']['list'];
        }
        //其他会员等级的id数组
        $arr1 = array();
        $arr2 = array();
        if (!empty($now)) {
            foreach ($now as $k => $v) {
                $arr1[] = $v['id'];
            }
        }
        if (!empty($draft)) {
            foreach ($draft as $k => $v) {
                $arr2[] = $v['id'];
            }
        }
        //给数组将维
        $rtnow = array();
        $rtdraft = array();
        $this->reduceDimension($now, $rtnow);
        $this->reduceDimension($draft, $rtdraft);

        //对比数组
        $now1['if_hideword'] = $now1['if_hideword'] * 10;
        $now1['rule_type'] = $now1['rule_type'] * 10;
        $draft1['if_hideword'] = $draft1['if_hideword'] * 10;
        $draft1['rule_type'] = $draft1['rule_type'] * 10;
        $diff = array_diff($rtnow, $rtdraft);
        $diffr = array_diff($rtdraft, $rtnow);
        $diff1 = array_diff($draft1, $now1);
        $now1['if_hideword'] = $now1['if_hideword'] / 10;
        $now1['rule_type'] = $now1['rule_type'] / 10;
        $draft1['if_hideword'] = $draft1['if_hideword'] / 10;
        $draft1['rule_type'] = $draft1['rule_type'] / 10;
        $addid = array();
        foreach ($arr2 as $k => $v) {
            if (!in_array($v, $arr1)) {
                $addid[] = $v;
            }
        }
        $revertid = array();
        foreach ($arr1 as $k => $v) {
            if (!in_array($v, $arr2)) {
                $revertid[] = $v;
            }
        }

        //找出剩余需修改的类型的等级
        $num = 0;
        $change_type = 5;
        foreach ($draft as $k => $v) {
            if ($v['rule_type'] == 5) {
                $num++;
            } else {
                $change_type = $v['rule_type'];
            }
        }
        if ($draft1['rule_type'] == 5) {
            $num++;
        } else {
            $change_type = $draft1['rule_type'];
        }
        //在第一次设置类型的情况下
        foreach ($draft as $k => $v) {
            if ($v['rule_type'] == null) {
                $num++;
            } else {
                $change_type = $v['rule_type'];
            }
        }
        if ($draft1['rule_type'] == null) {
            $num++;
        } else {
            $change_type = $draft1['rule_type'];
        }


        if (empty($diff) && empty($diff1) && empty($diffr)) {
            $res['grade'] = $Grade;
            $res['change'] = 0;
            $res['change_type'] = $change_type;
        } else {
            $res['grade'] = $GradeDraft;
            $res['change'] = 1;
            $res['diff'] = $diff;
            $res['diff1'] = $diff1;
            $res['addid'] = $addid;
            $res['revertid'] = $revertid;
            $res['num'] = $num;
            $res['change_type'] = $change_type;
        }
        return $res;
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

    /**
     * 获取会员等级详情(草稿)
     * $id  会员等级id
     */
    public function getUserGradeDraftDetails($id)
    {
        $model = UserGradeDraft::model()->findByPk($id);
        return $model;
    }

    /**
     * 校验积分要求是否重复
     * id 编辑的ID
     * point_rule 积分要求
     */
    public function checkUserPointRule($merchant_id, $point_rule, $id = '')
    {
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
            $model = UserGrade::model()->findAll($criteria);
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

    /**
     * 添加会员等级
     * $merchant_id 商户id
     * $post 添加属性的数组
     * cover  会员卡图片地址
     * cardName  会员卡名称
     */
    public function addUserGrade($merchant_id, $name, $points_rule, $discount, $discount_illustrate, $points_ratio, $cover, $cardName, $if_hideword, $rule_type, $birthday_rate)
    {

        $result = array();
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

            if (!empty($cover)) {
                $model->membercard_img = $cover;
            }
            $model->membership_card_name = $cardName;
            if ($model->save()) {
                $result ['status'] = ERROR_NONE;
                $result ['errMsg'] = '';
            } else {
                $result ['status'] = ERROR_SAVE_FAIL; // 状态码
                $result ['errMsg'] = '数据保存失败'; // 错误信息
            }
        } else {
            $result ['status'] = ERROR_REQUEST_FAIL; // 状态码
            $result ['errMsg'] = '会员等级最多可添加5个等级'; // 错误信息
        }

        return json_encode($result);
    }

    /**
     * 判断会员等级名称是否存在
     * $name  会员等级名称
     * $merchant_id 商户id
     * return  true：存在       false：不存在
     */
    public function isUserGradeNameExit($name, $id = '', $merchant_id)
    {
        if (empty($id)) {

            $model = UserGrade::model()->findAll('name=:name and flag=:flag and merchant_id=:merchant_id', array(
                ':name' => trim($name),
                ':flag' => FLAG_NO,
                ':merchant_id' => $merchant_id
            ));

            if (count($model) > 0) {
                return true;
            }
            return false;
        } else {
            $model = UserGrade::model()->findAll('name=:name and id!=:id and flag=:flag and merchant_id=:merchant_id', array(
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
     * 还原上次的发布点
     */
    public function RevertUserGrade($merchant_id)
    {
        $error = 0;
        $result = array();
        $list = array();
        $default = array();
        $Grade = json_decode($this->getSetUserGrade($merchant_id), true);
        if (isset($Grade['data']['list']) && !empty($Grade['data']['list'])) {
            $list = $Grade['data']['list'];
        }
        if (isset($Grade['datas']['list']) && !empty($Grade['datas']['list'])) {
            $default = $Grade['datas']['list'];
        }
        //更新默认等级
        $id = $default['id'];
        $model = UserGradeDraft::model()->findByPk($id);
        if (empty($model)) {
            $result['status'] = ERROR_NO_DATA;
            $result['errMsg'] = '修改的会员等级不存在'; //错误信息
        } else {
            $model->name = $default['name'];
            if ($model->points_rule != $default['points_rule']) {
                $model->points_rule = $default['points_rule'];
            }
            $model->points_ratio = $default['points_ratio'];
            $model->discount_illustrate = $default['discount_illustrate'];
            if ($model->discount != $default['discount']) {
                $model->discount = $default['discount'] / 10;
            }
            if (!empty($default['membercard_img'])) {
                $model->membercard_img = $default['membercard_img'];
            }
            $model->membership_card_name = $default['membership_card_name'];
            $model->last_time = date('Y-m-d H:i:s');
            $model->if_hideword = $default['if_hideword'];
            $model->rule_type = $default['rule_type'];
            $model->birthday_rate = $default['birthday_rate'];
        }

        if ($model->update()) {
        } else {
            $error++;
        }

        //更新其他等级
        foreach ($list as $k => $v) {
            $id = $v['id'];
            $model = UserGradeDraft::model()->findByPk($id);
            if (empty($model)) {

            } else {
                $model->name = $v['name'];
                if ($model->points_rule != $v['points_rule']) {
                    $model->points_rule = $v['points_rule'];
                }
                $model->points_ratio = $v['points_ratio'];
                $model->discount_illustrate = $v['discount_illustrate'];
                if ($model->discount != $v['discount']) {
                    $model->discount = $v['discount'] / 10;
                }
                if (!empty($v['membercard_img'])) {
                    $model->membercard_img = $v['membercard_img'];
                }
                $model->membership_card_name = $v['membership_card_name'];
                $model->last_time = date('Y-m-d H:i:s');
                $model->if_hideword = $v['if_hideword'];
                $model->rule_type = $v['rule_type'];
                $model->birthday_rate = $v['birthday_rate'];


                if ($model->update()) {
                } else {
                    $error++;
                }
            }
        }
        //当草稿有新增等级时，把多余的记录删掉
        $Grade = json_decode($this->getSetUserGrade($merchant_id), true);
        $GradeDraft = json_decode($this->getSetUserGradeDraft($merchant_id), true);
        $res = $this->contrastGrade($Grade, $GradeDraft);

        $addid = array();
        if (!empty($res['addid'])) {
            $addid = $res['addid'];
            foreach ($addid as $k => $v) {
                $model = UserGradeDraft::model()->findByPk($v);
                $model->flag = FLAG_YES;
                $model->save();
            }
        }
        //当草稿删掉原来的等级时
        $revertid = array();
        if (!empty($res['revertid'])) {
            $revertid = $res['revertid'];
            foreach ($revertid as $k => $v) {
                $model = UserGradeDraft::model()->findByPk($v);
                $model->flag = FLAG_NO;
                $model->save();
            }
        }


        if ($error == 0) {
            $result ['status'] = ERROR_NONE;
            $result['errMsg'] = ''; //错误信息
        } else {
            $result ['status'] = ERROR_SAVE_FAIL;
            $result['errMsg'] = '数据更新失败'; //错误信息
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
    public function releaseUserGrade($merchant_id)
    {
        $error = 0;
        $result = array();
        $list = array();
        $default = array();
        $GradeDraft = json_decode($this->getSetUserGradeDraft($merchant_id), true);
        if (isset($GradeDraft['data']['list']) && !empty($GradeDraft['data']['list'])) {
            $list = $GradeDraft['data']['list'];
        }
        if (isset($GradeDraft['datas']['list']) && !empty($GradeDraft['datas']['list'])) {
            $default = $GradeDraft['datas']['list'];
        }
        //更新默认等级
        $id = $default['id'];
        $model = UserGrade::model()->findByPk($id);
        if (empty($model)) {
            $result['status'] = ERROR_NO_DATA;
            $result['errMsg'] = '修改的会员等级不存在'; //错误信息
        } else {
            $model->name = $default['name'];
            if ($model->points_ratio != $default['points_ratio']) {
                $model->points_ratio = $default['points_ratio'];
            }
            if ($model->points_rule != $default['points_rule']) {
                $model->points_rule = $default['points_rule'];
            }
            $model->discount_illustrate = $default['discount_illustrate'];
            if ($model->discount != $default['discount']) {
                $model->discount = $default['discount'] / 10;
            }
            if (!empty($default['membercard_img'])) {
                $model->membercard_img = $default['membercard_img'];
            }
            $model->membership_card_name = $default['membership_card_name'];
            $model->last_time = date('Y-m-d H:i:s');
            $model->if_hideword = $default['if_hideword'];
            $model->rule_type = $default['rule_type'];
            $model->birthday_rate = $default['birthday_rate'];
        }

        if ($model->update()) {
        } else {
            $error++;
        }
    }
    /**
     * 编辑会员等级
     * $id  会员等级id
     * $post 添加属性的数组
     */
    public function editUserGrade($name,$points_rule='',$points_ratio,$discount_illustrate,$discount,$id,$cover='',$cardName,$if_hideword,$rule_type,$birthday_rate)
    {
        $result = array();
        $model = UserGradeDraft::model()->findByPk($id);
        if (empty($model)) {
            $result['status'] = ERROR_NO_DATA;
            $result['errMsg'] = '修改的会员等级不存在'; //错误信息
        }else{
            $model -> name = $name;
            if(!empty($points_rule))
            {
                if($model -> points_rule != $points_rule) {
                    $model->points_rule = $points_rule;
                }
            }
            $model -> points_ratio = $points_ratio;
            $model -> discount_illustrate = $discount_illustrate;
            if($model->discount != $discount){
                $model->discount = $discount/10;
            }
            if(!empty($cover))
            {
                $model -> membercard_img = $cover;
            }
            $model -> membership_card_name = $cardName;
            $model -> last_time = date('Y-m-d H:i:s');
            $model -> if_hideword = $if_hideword;
            $model -> rule_type = $rule_type;
            $model -> birthday_rate = $birthday_rate;

            if($model -> update()){
                $result ['status'] = ERROR_NONE;
                $result['errMsg'] = ''; //错误信息
            }else{
                $result ['status'] = ERROR_SAVE_FAIL;
                $result['errMsg'] = '数据保存失败'; //错误信息
            }
        }

        return json_encode($result);
    }
    /**
     * 删除会员等级（会员人数为0的才能删）
     * $id  会员等级id
     */
    public function delUserGrade($id,$merchant_id)
    {
        $result = array();

        $is_exit = $this->isUserCount($id,$merchant_id);
        if($is_exit){//存在会员人数  不能删除
            $result ['status'] = ERROR_NONE;
            $result['errMsg'] = ''; //错误信息
            $url = Yii::app()->createUrl('mCenter/user/setUserGrade');
            echo "<script>alert('该会员等级存在会员，不能删除');window.location.href='$url'</script>";
        }else{//不存在  进行删除
            $model = UserGradeDraft::model()->findByPk($id);
            $model -> flag = FLAG_YES;
            if($model -> save()){
                $result ['status'] = ERROR_NONE;
                $result['errMsg'] = ''; //错误信息
            }else{
                $result ['status'] = ERROR_SAVE_FAIL;
                $result['errMsg'] = '数据保存失败'; //错误信息
            }
        }
        return json_encode($result);
    }
    /**
     * 清除草稿的会员等级条件
     */
    public function clearGradeType($GradeDraft,$rule_type){
        $default = $GradeDraft['datas']['list'];
        $another = $GradeDraft['data']['list'];
        //清空默认等级的条件
        $model = UserGradeDraft::model()->findByPk($default['id']);
        $model->rule_type = $rule_type;
        $model->save();
        foreach ($another as $k => $v){
            $model = UserGradeDraft::model()->findByPk($v['id']);
            $model->rule_type = $rule_type;
            $model->points_rule = null;
            $model->save();
        }
    }
    /**
     * 根据id获取会员等级名称
     * @param  $userGrandId
     * @return string
     */
    public function getUserGrandName($userGrandId)
    {
        $model = UserGrade::model()->findByPk($userGrandId);
        if(!empty($model)){
            return $model -> name;
        }else{
            return '';
        }
    }
    /**
     * 根据id获取积分要求
     * @param unknown $userGrandId
     * @return string
     */
    public function getUserGrandPointRule($userGrandId)
    {
        $model = UserGrade::model()->findByPk($userGrandId);
        if(!empty($model)){
            return $model -> points_rule;
        }else{
            return '';
        }
    }
    /**
     * 删除会员管理分组
     */
    public function delUserGroup($userGroupId)
    {
        $result = array();
        $transaction = Yii::app()->db->beginTransaction();
        try {
            $model = UserGroup::model()->findByPk($userGroupId);
            $model -> flag = FLAG_YES;
            if(!$model -> update()){
                $result['status'] = ERROR_SAVE_FAIL;
                $result['errMsg'] = '数据删除失败';
                throw new Exception('会员管理分组删除失败');
            }
            //删除支付宝标签
            if(!empty($model->label_id)) {
                $AliServiceWindowLabel = new AliServiceWindowLabel();
                $ali = $AliServiceWindowLabel->delLable($model->label_id);
                if ($ali->alipay_mobile_public_label_delete_response->code == 200) {

                }
            }

            $group = Group::model()->findAll('flag=:flag and group_id=:group_id',array(':flag'=>FLAG_NO,':group_id'=>$userGroupId));
            foreach ($group as $k=>$v){
                $v['flag'] = FLAG_YES;
                if(!$v->update()){
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
            $result['status'] = isset($result['status'])?$result['status']:ERROR_EXCEPTION;
            $result['errMsg'] = $e->getMessage();
        }
        return json_encode($result);
    }
    /**
     *  新建会员管理分组  (自定义分组)
     */
    public function addUserGroupSelf($merchant_id,$name)
    {
        $result = array();
        $flag = 1;
        $errMsg = '';
        try {
            $userGroup = new UserGroup();
            if(empty($name)){
                $flag = 2;
                $result ['status'] = ERROR_PARAMETER_MISS;
                $errMsg =  $errMsg .' 分组名必填';
                Yii::app()->user->setFlash('name_error','分组名必填');
            }else {
                $model = UserGroup::model ()->find ( 'flag=:flag and merchant_id=:merchant_id and name=:name', array (
                    ':flag' => FLAG_NO ,
                    ':merchant_id' => $merchant_id,
                    ':name' => trim($name)
                ) );
                if(count($model)>0){
                    $flag = 2;
                    $result ['status'] = ERROR_PARAMETER_MISS;
                    $errMsg =  $errMsg .' 分组名已存在';
                    Yii::app()->user->setFlash('name_error','分组名已存在');
                }
            }

            if ($flag == 2) {
                $result ['errMsg'] = $errMsg;
                return json_encode ( $result );
            }
            //支付宝服务窗生成标签
            $AliServiceWindowLabel = new AliServiceWindowLabel();
            $ali = $AliServiceWindowLabel->addLable($name);

            $userGroup -> merchant_id = $merchant_id;
            $userGroup -> name = $name;
            $userGroup -> type = GROUP_TYPE_CUSTOM;
            $userGroup -> create_time = date('Y-m-d H:i:s');
            if ($ali['response']->alipay_mobile_public_label_query_response->code == 200) {
                $userGroup->label_id = $ali['label_id'];
            }
            if($userGroup->save()){
                $result ['status'] = ERROR_NONE; // 状态码
            }else{
                $result ['status'] = ERROR_SAVE_FAIL; // 状态码
            }

        } catch (Exception $e) {
            $result['status'] = isset($result['status'])?$result['status']:ERROR_EXCEPTION;
            $result['errMsg'] = $e->getMessage();
        }
        return json_encode($result);
    }
    /**
     * 会员管理分组详情
     */
    public function getUserGroupDetail($userGroupId)
    {
        $model = UserGroup::model()->findByPk($userGroupId);
        return $model;
    }
    /**
     * 修改会员管理分组  (自定义分组)
     */
    public function editUserGroupSelf($merchant_id,$name,$userGroupId)
    {
        $result = array();
        $flag = 1;
        $errMsg = '';
        try {
            $userGroup = UserGroup::model()->findByPk($userGroupId);
            if(empty($name)){
                $flag = 2;
                $result ['status'] = ERROR_PARAMETER_MISS;
                $errMsg =  $errMsg .' 分组名必填';
                Yii::app()->user->setFlash('name_error','分组名必填');
            }else {
                $model = UserGroup::model ()->find ( 'flag=:flag and merchant_id=:merchant_id and name=:name and id!=:id', array (
                    ':flag' => FLAG_NO ,
                    ':merchant_id' => $merchant_id,
                    ':name' => trim($name),
                    ':id'=>$userGroupId
                ) );
                if(count($model)>0){
                    $flag = 2;
                    $result ['status'] = ERROR_PARAMETER_MISS;
                    $errMsg =  $errMsg .' 分组名已存在';
                    Yii::app()->user->setFlash('name_error','分组名已存在');
                }
            }

            if($flag == 2){
                $result['errMsg'] = $errMsg;
                return json_encode($result);
            }

            $userGroup -> name = $name;
            $userGroup -> last_time = date('Y-m-d H:i:s');
            if($userGroup->update()){
                $result['status'] = ERROR_NONE;
            }else{
                $result ['status'] = ERROR_SAVE_FAIL; // 状态码
            }

        } catch (Exception $e) {
            $result['status'] = isset($result['status'])?$result['status']:ERROR_EXCEPTION;
            $result['errMsg'] = $e->getMessage();
        }
        return json_encode($result);
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
}