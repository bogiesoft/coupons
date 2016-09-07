<?php
class CrmController extends Controller
{
    public $layout='main';
    /**
     * 获取会员等级设置
     * @param int $merchant_id 商户id
     */
    public function getSetUserGrade($merchant_id)
    {
        $result = array();
        $criteria = new CDbCriteria();
        $criteria -> order = 'points_rule asc';
        if (! empty ( $merchant_id )) {
            $criteria->addCondition ( 'merchant_id=:merchant_id and flag=:flag' );
            $criteria->params = array (
                ':merchant_id' => $merchant_id,
                ':flag' => FLAG_NO
            );
        }else{
            $criteria->addCondition ( 'flag=:flag' );
            $criteria->params = array (
                ':flag' => FLAG_NO
            );
        }
        $model = UserGrade::model()->findAll($criteria);
        $countusergrade = UserGrade::model()->count($criteria);
        $data = array ();
        $dataS = array();
        if(!empty($model)){
            foreach ( $model as $k => $v ) {
                if($v['if_default'] == USER_GRADE_DEFAULT_NO)
                {
                    $data ['list'] [$k] ['id'] = $v->id;
                    $data ['list'] [$k] ['name'] = $v->name;
                    $data ['list'] [$k] ['points_rule'] = $v->points_rule;
                    $data ['list'] [$k] ['points_ratio'] = $v->points_ratio;
                    $data ['list'] [$k] ['discount'] = $v->discount*10;
                    $data ['list'] [$k] ['create_time'] = $v->create_time;
                    $data ['list'] [$k] ['discount_illustrate'] = $v->discount_illustrate;
                    $data ['list'] [$k] ['membercard_img'] = $v->membercard_img;
                    $data ['list'] [$k] ['membership_card_name'] = $v->membership_card_name;
                    $data ['list'] [$k] ['if_hideword'] = $v->if_hideword;
                    $count = $this -> getUserCount($v->id,$merchant_id);
                    $data ['list'] [$k] ['count'] = $count;
                    $data ['list'] [$k] ['if_default'] = $v -> if_default;
                    $data ['list'] [$k] ['rule_type'] = $v->rule_type;
                    $data ['list'] [$k] ['birthday_rate'] = $v->birthday_rate;
                }
            }
            $grade = UserGrade::model()->find('merchant_id=:merchant_id and if_default=:if_default and flag=:flag',array(':merchant_id'=>$merchant_id,':if_default'=>USER_GRADE_DEFAULT_YES,':flag'=>FLAG_NO));
            if(!empty($grade)) {
                $dataS ['list'] ['id'] = $grade->id;
                $dataS ['list'] ['name'] = $grade->name;
                $dataS ['list'] ['points_rule'] = $grade->points_rule;
                $dataS ['list'] ['points_ratio'] = $grade->points_ratio;
                $dataS ['list'] ['discount'] = $grade->discount*10;
                $dataS ['list'] ['create_time'] = $grade->create_time;
                $dataS ['list'] ['discount_illustrate'] = $grade->discount_illustrate;
                $dataS ['list'] ['membercard_img'] = $grade->membercard_img;
                $dataS ['list'] ['membership_card_name'] = $grade->membership_card_name;
                $dataS ['list'] ['if_hideword'] = $grade->if_hideword;
                $count = $this -> getUserCount($grade->id,$merchant_id);
                $dataS ['list'] ['count'] = $count;
                $dataS ['list'] ['if_default'] = $grade -> if_default;
                $dataS ['list'] ['rule_type'] = $grade->rule_type;
                $dataS ['list'] ['birthday_rate'] = $grade->birthday_rate;
            }
            $result ['status'] = ERROR_NONE;
            $result ['data']  = $data;
            $result ['datas'] = $dataS;
            $result ['countusergrade'] = $countusergrade;
        }else{
            $result ['status'] = ERROR_NO_DATA;
            $result['errMsg'] = '无此数据'; //错误信息
        }

        return json_encode ( $result );
    }
    /**
     * 获取会员等级设置(草稿)
     * $merchant_id 商户id
     */
    public function getSetUserGradeDraft($merchant_id)
    {
        $result = array();
        $criteria = new CDbCriteria();
        $criteria -> order = 'points_rule asc';
        if (! empty ( $merchant_id )) {
            $criteria->addCondition ( 'merchant_id=:merchant_id and flag=:flag' );
            $criteria->params = array (
                ':merchant_id' => $merchant_id,
                ':flag' => FLAG_NO
            );
        }else{
            $criteria->addCondition ( 'flag=:flag' );
            $criteria->params = array (
                ':flag' => FLAG_NO
            );
        }
        $model = UserGradeDraft::model()->findAll($criteria);
        $countusergrade = UserGradeDraft::model()->count($criteria);
        $data = array ();
        $dataS = array();
        if(!empty($model)){
            foreach ( $model as $k => $v ) {
                if($v['if_default'] == USER_GRADE_DEFAULT_NO)
                {
                    $data ['list'] [$k] ['id'] = $v->id;
                    $data ['list'] [$k] ['name'] = $v->name;
                    $data ['list'] [$k] ['points_rule'] = $v->points_rule;
                    $data ['list'] [$k] ['discount'] = $v->discount*10;
                    $data ['list'] [$k] ['points_ratio'] = $v->points_ratio;
                    $data ['list'] [$k] ['create_time'] = $v->create_time;
                    $data ['list'] [$k] ['discount_illustrate'] = $v->discount_illustrate;
                    $data ['list'] [$k] ['membercard_img'] = $v->membercard_img;
                    $data ['list'] [$k] ['membership_card_name'] = $v->membership_card_name;
                    $data ['list'] [$k] ['if_hideword'] = $v->if_hideword;
                    $count = $this -> getUserCount($v->id,$merchant_id);
                    $data ['list'] [$k] ['count'] = $count;
                    $data ['list'] [$k] ['if_default'] = $v -> if_default;
                    $data ['list'] [$k] ['rule_type'] = $v->rule_type;
                    $data ['list'] [$k] ['birthday_rate'] = $v->birthday_rate;
                }
            }
            $grade = UserGradeDraft::model()->find('merchant_id=:merchant_id and if_default=:if_default and flag=:flag',array(':merchant_id'=>$merchant_id,':if_default'=>USER_GRADE_DEFAULT_YES,':flag'=>FLAG_NO));
            if(!empty($grade)) {
                $dataS ['list'] ['id'] = $grade->id;
                $dataS ['list'] ['name'] = $grade->name;
                $dataS ['list'] ['points_rule'] = $grade->points_rule;
                $dataS ['list'] ['points_ratio'] = $grade->points_ratio;
                $dataS ['list'] ['discount'] = $grade->discount*10;
                $dataS ['list'] ['create_time'] = $grade->create_time;
                $dataS ['list'] ['discount_illustrate'] = $grade->discount_illustrate;
                $dataS ['list'] ['membercard_img'] = $grade->membercard_img;
                $dataS ['list'] ['membership_card_name'] = $grade->membership_card_name;
                $dataS ['list'] ['if_hideword'] = $grade->if_hideword;
                $count = $this -> getUserCount($grade->id,$merchant_id);
                $dataS ['list'] ['count'] = $count;
                $dataS ['list'] ['if_default'] = $grade -> if_default;
                $dataS ['list'] ['rule_type'] = $grade->rule_type;
                $dataS ['list'] ['birthday_rate'] = $grade->birthday_rate;
            }
            $result ['status'] = ERROR_NONE;
            $result ['data']  = $data;
            $result ['datas'] = $dataS;
            $result ['countusergrade'] = $countusergrade;
        }else{
            $result ['status'] = ERROR_NO_DATA;
            $result['errMsg'] = '无此数据'; //错误信息
        }

        return json_encode ( $result );
    }
    /**
     * 对比会员等级正式和草稿的数据
     * $Grade 当前使用的会员等级规则数据
     * $GradeDraft 修改会员等级的草稿
     */
    public function contrastGrade($Grade,$GradeDraft){
        $res = array();
        //会员等级数量
        $count1 = $Grade['countusergrade'];
        $count2 = $GradeDraft['countusergrade'];
        //其他等级的二维数组
        $now = array();
        $draft = array();
        if (isset($Grade['data']['list']) && !empty($Grade['data']['list'])){
            $now = $Grade['data']['list'];
        }
        $now = $Grade['data']['list'];
        if (isset($GradeDraft['data']['list']) && !empty($GradeDraft['data']['list'])){
            $draft = $GradeDraft['data']['list'];
        }
        //默认等级的数组
        $now1 = array();
        $draft1 = array();
        if (isset($Grade['datas']['list']) && !empty($Grade['datas']['list'])){
            $now1 = $Grade['datas']['list'];
        }
        $now = $Grade['data']['list'];
        if (isset($GradeDraft['datas']['list']) && !empty($GradeDraft['datas']['list'])){
            $draft1 = $GradeDraft['datas']['list'];
        }
        //其他会员等级的id数组
        $arr1 = array();
        $arr2 = array();
        if(!empty($now)){
            foreach ($now as $k=>$v){
                $arr1[] = $v['id'];
            }
        }
        if (!empty($draft)){
            foreach ($draft as $k=>$v){
                $arr2[] = $v['id'];
            }
        }
        //给数组将维
        $rtnow =array();
        $rtdraft = array();
        $this->reduceDimension($now,$rtnow);
        $this->reduceDimension($draft,$rtdraft);

        //对比数组
        $now1['if_hideword']=$now1['if_hideword']*10;
        $now1['rule_type']=$now1['rule_type']*10;
        $draft1['if_hideword']=$draft1['if_hideword']*10;
        $draft1['rule_type']=$draft1['rule_type']*10;
        $diff = array_diff($rtnow, $rtdraft);
        $diffr = array_diff($rtdraft, $rtnow);
        $diff1 = array_diff($draft1, $now1);
        $now1['if_hideword']=$now1['if_hideword']/10;
        $now1['rule_type']=$now1['rule_type']/10;
        $draft1['if_hideword']=$draft1['if_hideword']/10;
        $draft1['rule_type']=$draft1['rule_type']/10;
        $addid = array();
        foreach ($arr2 as $k => $v){
            if(!in_array($v, $arr1)){
                $addid[] = $v;
            }
        }
        $revertid = array();
        foreach ($arr1 as $k => $v){
            if(!in_array($v, $arr2)){
                $revertid[] = $v;
            }
        }

        //找出剩余需修改的类型的等级
        $num = 0;
        $change_type = 5;
        foreach ($draft as $k => $v){
            if ($v['rule_type'] == 5){
                $num++;
            }else{
                $change_type = $v['rule_type'];
            }
        }
        if ($draft1['rule_type'] == 5){
            $num++;
        }else{
            $change_type = $draft1['rule_type'];
        }
        //在第一次设置类型的情况下
        foreach ($draft as $k => $v){
            if ($v['rule_type'] == null){
                $num++;
            }else{
                $change_type = $v['rule_type'];
            }
        }
        if ($draft1['rule_type'] == null){
            $num++;
        }else{
            $change_type = $draft1['rule_type'];
        }


        if ( empty($diff) && empty($diff1) &&empty($diffr)){
            $res['grade'] = $Grade;
            $res['change'] = 0;
            $res['change_type'] = $change_type;
        }else {
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
    public function reduceDimension($arr, &$rt) {
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
}