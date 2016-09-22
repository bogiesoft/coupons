<?php

/**
 * 会员等级管理
 */
class UserGradeController extends CrmController{
    public function actionIndex(){
        
    }
    /**
     * 数据库初始化: 会员等级code赋值
     * @throws Exception
     */
    public function actionSetUsersGradeCode(){
        set_time_limit(0);
        $transaction = Yii::app()->db->beginTransaction(); //开启事务
        try {
            //删除草稿表数据
            $cmd1 = UserGradeDraft::model()->deleteAll();
            //会员等级主表多有商户id(遍历)
            $cmd = Yii::app()->db->createCommand();
            $cmd->select = 'merchant_id';
            $cmd->from = 'wq_user_grade';
            $cmd->andWhere('flag = 1');
            $cmd->group = 'merchant_id';
            $list = $cmd->queryColumn();
            foreach ($list as $v){
                $res = $this->SetUserGradeCode($v);
                if ($res['status'] != ERROR_NONE){
                    throw new Exception($res['errMsg']);
                }
            }
            $transaction->commit();
            $result['status'] = ERROR_NONE;
        }catch (Exception $e) {
            $transaction->rollback();
            $result['status'] = isset($result['status'])?$result['status']:ERROR_EXCEPTION;
            $result['errMsg'] = $e -> getMessage();
        }
        var_dump($result);
    }
    /**
     * 设置code
     * @param unknown $merchant_id
     * @throws Exception
     */
    private function SetUserGradeCode($merchant_id){
        try {
            //查询会员等级主表所有数据(有效数据)
            $Grade = json_decode($this -> getSetUserGrade($merchant_id),true);
            if ($Grade['status'] == ERROR_NONE){
                foreach ($Grade['data'] as $k => $v){
                    //设置会员等级code
                    $grade_one = UserGrade::model()->findByPk($v['id']);
                    if (empty($grade_one)) {
                        throw new Exception('save error:会员等级草稿表信息不存在');
                    } else {
                        $num = $this->add_zero($v['id']);
                        $jdh = "GR" . date("Ymd") .$merchant_id. $num;
                        $grade_one->user_grade_code = $jdh;
                        if (!$grade_one->update()) {
                            throw new Exception('save error:'.CJSON::encode($grade_one->getErrors()));
                        }
                    }
                    //同步到草稿表
                    $model = new UserGradeDraft();
                    $model->merchant_id = $v['merchant_id'];
                    $model->name = $v['name'];
                    $model->points_rule = $v['points_rule'];
                    $model->discount = $v['discount'] / 10;
                    $model->discount_illustrate = $v['discount_illustrate'];
                    $model->points_ratio = $v['points_ratio'];
                    $model->create_time = date('Y-m-d H:i:s');
                    $model->if_hideword = $v['if_hideword'];
                    $model->rule_type = $v['rule_type'];
                    $model->birthday_rate = $v['birthday_rate'];
                    $model->user_grade_code = $jdh;
                    $model->membercard_img = $v['membercard_img'];
                    $model->membership_card_name = $v['membership_card_name'];
                    $model->if_default = $v['if_default'];
                    if (!$model->save()) {
                        throw new Exception('save error:数据保存失败');
                    }
                }
            }
            $result['status'] = ERROR_NONE;
        }catch (Exception $e) {
            $result['status'] = isset($result['status'])?$result['status']:ERROR_EXCEPTION;
            $result['errMsg'] = $e -> getMessage();
        }
        return $result;
    }
    /**
     * 生成code尾数
     * @param unknown $num
     * @param number $count
     */
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
     * 会员等级设置
     */
    public function actionSetUserGrade(){
        $res = $this -> contrastGrade();
        $result = json_decode($res, true);
        $this->render('setUserGrade',array(
            'list'=>$result['grade'],
            'change'=>$result['change'],
            'countusergrade' => $result['countusergrade']
        ));
    }
    
    /**
     * 发布会员等级
     */
    public function actionReleaseUserGrade(){
        $result = json_decode($this->releaseUserGrade(),true);
        if($result['status'] == ERROR_NONE){
            $url = $this->createUrl('setUserGrade');
            echo "<script>alert('发布成功');window.location.href='$url'</script>";
        }else {
            $msg = $result['errMsg'];
            $url = $this->createUrl('setUserGrade');
            echo "<script>alert('$msg');window.location.href='$url'</script>";
        }
    }

    /**
     * 还原会员等级
     */
    public function actionRevertUserGrade(){
        $result = json_decode($this->revertUserGrade(),true);
        if($result['status'] == ERROR_NONE){
            $url = $this->createUrl('setUserGrade');
            echo "<script>alert('还原成功');window.location.href='$url'</script>";
        }else {
            $msg = $result['errMsg'];
            $url = $this->createUrl('setUserGrade');
            echo "<script>alert('$msg');window.location.href='$url'</script>";
        }
    }

    /**
     * 添加会员等级
     */
    public function actionAddUserGrade() {
        //create user_grade_code
        $user_grade_code = $this -> createUserGradeCode();
        $request = $_POST;
        if(isset($request['UserGrade']) && $request['UserGrade']){
            $result = $this -> addUserGrade($request);
            $res = json_decode($result,true);
            if($res['status'] == ERROR_NONE){
                $url = $this->createUrl('setUserGrade');
                echo "<script>alert('添加成功');window.location.href='$url'</script>";
            }
        }
        $this->render('addUserGrade',array(
            'user_grade_code' => $user_grade_code
        ));
    }

    /**
     * 编辑会员等级
     */
    public function actionEditUserGrade($id){
        $model = $this -> getUserGradeDraftDetails($id);
        $request = $_POST;
        if(isset($request['UserGrade']) && $request['UserGrade']){
            $ret = $this -> editUserGrade($request,$id);
            $result = json_decode($ret,true);
            if($result['status'] == ERROR_NONE){
                $url = $this->createUrl('setUserGrade');
                echo "<script>alert('修改成功');window.location.href='$url'</script>";
            }
        }
        $type = 1;
        $this -> render('editUserGrade',array(
            'model'=>$model,
            'id'=>$id,
            'type'=>$type,
        ));
    }

    /**
     * 删除会员等级（会员人数为0的才能删）
     */
    public function actionDelUserGrade($id){
        $merchant_id = Yii::app()->session['merchant_id'];
        $result = $this -> delUserGrade($id,$merchant_id);
        $result = json_decode($result,true);
        if($result['status'] == ERROR_NONE){
            $this -> actionSetUserGrade();
        }
    }

    /**
     * 清空会员条件设置
     */
    public function actionClearType() {
        $merchant_id = Yii::app()->session['merchant_id'];
        $type = $_GET['type'] + 1;
        $GradeDraft = json_decode($this -> getSetUserGradeDraft($merchant_id),true);
        $this->clearGradeType($GradeDraft,$type);
        $arr=array('v'=>'所有条件已清除，请重新设置');
        echo json_encode($arr);
    }

    /**
     * (ajax验证方法)检测会员等级管理输入
     */
    public function actionCheckUserGrade(){
        $merchant_id = Yii::app()->session['merchant_id'];
        if(isset($_GET['data'])&&isset($_GET['type'])){
            $data=$_GET['data'];
            $type=$_GET['type'];
            $flag=true;
            if($type=='name'){
                //会员等级名称
                if(empty($data)){
                    $flag=false;
                    $arr=array('type'=>'name','v'=>'会员等级名称必填');
                    echo json_encode($arr);
                }else{
                    if(preg_match('/^[a-zA-Z_\x{4e00}-\x{9fa5}]+$/u',trim($data))){
                    } else {
                        $flag = false;
                        $arr=array('type'=>'name','v'=>'不允许有数字,符合,空格');
                        echo json_encode($arr);
                    }
                    $id = '';
                    if (!empty($_GET['grade_id'])){
                        $id = $_GET['grade_id'];
                    }
                    $userGrandId = isset($_GET['userGrandId'])?$_GET['userGrandId']:'';
                    $userGrandName = $this -> getUserGrandName($userGrandId);
                    $merchant_id = Yii::app() -> session['merchant_id'];
                    if($userGrandName != trim($data)){ //编辑界面     如果原输入框的会员等级名称和userGrandId对应的数据库的会员等级名称不一样  说明有改动  要判断是否存在
                        $is_exit = $this -> isUserGradeNameExit($data,$id,$merchant_id);
                        if($is_exit){
                            $flag = false;
                            $arr=array('type'=>'name','v'=>'会员等级名称已存在');
                            echo json_encode($arr);
                        }
                    }
                }
            }elseif($type=='discount'){
                //会员特权
                if(empty($data)){
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
                if($data){
                    if(preg_match('/^[a-zA-Z_\x{4e00}-\x{9fa5}]+$/u',trim($data))){
                        $flag = false;
                        $arr=array('type'=>'discount','v'=>'会员特权格式不正确');
                        echo json_encode($arr);
                    } else {
                        if($data <= 0){
                            $flag = false;
                            $arr=array('type'=>'discount','v'=>'会员特权不为负数');
                            echo json_encode($arr);
                        }
                    }
                    if($data == 0.00){
                        $flag = false;
                        $arr=array('type'=>'discount','v'=>'请填写正确的折扣格式');
                        echo json_encode($arr);
                    }
                    if($data > 10){
                        $flag = false;
                        $arr=array('type'=>'discount','v'=>'会员特权只能输入（1-10）之间');
                        echo json_encode($arr);
                    }
                }
            }else if($type=='points_rule'){
                //积分要求
                if($data == '0'){
                    $flag = false;
                    $arr=array('type'=>'points_rule','v'=>'积分要求不为0');
                    echo json_encode($arr);
                } else {
                    if(empty($data)){
                        $flag = false;
                        $arr=array('type'=>'points_rule','v'=>'积分要求必填');
                        echo json_encode($arr);
                    } else{
                        $userGrandId = isset($_GET['userGrandId'])?$_GET['userGrandId']:'';
                        $userGrandPointRule = $this -> getUserGrandPointRule($userGrandId);
                        $rs=json_decode($this->checkUserPointRule($merchant_id,$data),true);
                        if($userGrandPointRule != trim($data)){
                            if($rs['status']==ERROR_DUPLICATE_DATA){
                                //积分要求重复
                                $flag = false;
                                $arr=array('type'=>'points_rule','v'=>'积分不能重复');
                                echo json_encode($arr);
                            }
                        }
                    }
                }
                if($data){
                    if(preg_match('/^[a-zA-Z_\x{4e00}-\x{9fa5}]+$/u',trim($data))){
                        $flag = true;
                        $flag = false;
                        $arr=array('type'=>'points_rule','v'=>'积分要求格式不正确');
                        echo json_encode($arr);
                    } else {
                        $flags=0;
                        if($data < 0){
                            $flags=1;
                            $flag = false;
                            $arr=array('type'=>'points_rule','v'=>'积分要求不为负数');
                            echo json_encode($arr);
                        }
                        if($flags == 0){
                            if(preg_match('/^[1-9]\d*|0$/', trim($data))){

                            } else {
                                $flag = false;
                                $arr=array('type'=>'points_rule','v'=>'积分要求为整数');
                                echo json_encode($arr);
                            }
                        }
                    }
                }
            }else if($type=='birthday_rate'){
                //生日积分倍率
                if($data == '0'){
                    $flag = false;
                    $arr=array('type'=>'birthday_rate','v'=>'积分倍率不为0');
                    echo json_encode($arr);
                }
                if($data){
                    if(preg_match('/^[a-zA-Z_\x{4e00}-\x{9fa5}]+$/u',trim($data))){
                        $flag = true;
                        $flag = false;
                        $arr=array('type'=>'birthday_rate','v'=>'积分倍率格式不正确');
                        echo json_encode($arr);
                    } else {
                        $flags=0;
                        if($data < 0){
                            $flags=1;
                            $flag = false;
                            $arr=array('type'=>'birthday_rate','v'=>'积分倍率不为负数');
                            echo json_encode($arr);
                        }
                        if($flags == 0){
                            if(preg_match('/^[1-9]\d*|0$/', trim($data))){

                            } else {
                                $flag = false;
                                $arr=array('type'=>'birthday_rate','v'=>'积分倍率为整数');
                                echo json_encode($arr);
                            }
                        }
                    }
                }
            }else if($type=='points_ratio'){
                //验证积分规则
                if($data == '0'){
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
                if(!empty($data)){
                    if(preg_match('/^[a-zA-Z_\x{4e00}-\x{9fa5}]+$/u',trim($data))){
                        $flag = false;
                        $arr=array('type'=>'points_ratio','v'=>'积分规则格式不正确');
                        echo json_encode($arr);
                    } else {
                        if($data <= 0){
                            $flag = false;
                            $arr=array('type'=>'points_ratio','v'=>'积分规则不为负数');
                            echo json_encode($arr);
                        }
                        $ratio = explode('.', $data);
                        $points = count($ratio);
                        if($points > 1){
                            $flag = false;
                            $arr=array('type'=>'points_ratio','v'=>'积分规则不能有小数点');
                            echo json_encode($arr);
                        }
                    }
                }
            }else if($type=='span_cardname'){
                //验证会员卡名称
                if(empty($data)){
                    $flag = false;
                    $arr=array('type'=>'span_cardname','v'=>'会员卡名称必填');
                    echo json_encode($arr);
                }
                if(!empty($data)){
                    if(preg_match('/^[a-zA-Z_\x{4e00}-\x{9fa5}]+$/u',trim($data))){
                    } else {
                        $flag = false;
                        $arr=array('type'=>'span_cardname','v'=>'会员卡名称格式不允许有数字,符合,空格');
                        echo json_encode($arr);
                    }
                }
            }
            if($flag){
                //没有错误
                $arr=array('type'=>$type,'v'=>'');
                echo json_encode($arr);
            }
        }
    }
}