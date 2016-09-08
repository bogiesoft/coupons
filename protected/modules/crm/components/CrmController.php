<?php
class CrmController extends Controller
{
    public $layout = 'main';

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
}