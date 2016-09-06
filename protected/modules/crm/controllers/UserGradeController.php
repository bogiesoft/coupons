<?php

/**
 * 会员等级管理
 */
class UserGradeController extends CrmController{
    /**
     * 会员等级设置
     */
    public function actionSetUserGrade()
    {
        $list = array();
        $lists = array();
        $userC = new UserC();
        $merchant_id = Yii::app()->session['merchant_id'];
        $Grade = json_decode($userC -> getSetUserGrade($merchant_id),true);
        $GradeDraft = json_decode($userC -> getSetUserGradeDraft($merchant_id),true);
        $res = $userC -> contrastGrade($Grade,$GradeDraft);

        $num = 0;
        if (!empty($res['num'])){
            $num = $res['num'];
        }
        $change = $res['change'];
        $result = $res['grade'];

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
     * 发布会员等级
     */
    public function actionReleaseUserGrade(){
        $userC = new UserC();
        $merchant_id = Yii::app()->session['merchant_id'];
        $num = 0;
        if (!empty($_GET['num'])){
            $num = $_GET['num'];
        }
        if ($num != 0){
            $url = $this->createUrl('setUserGrade',array('showleft'=>1));
            echo "<script>window.location.href='$url'</script>";
        }else {
            $result = $userC->releaseUserGrade($merchant_id);
            $result = json_decode($result,true);
            if($result['status'] == ERROR_NONE){
                $url = $this->createUrl('setUserGrade');
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
        $userC = new UserC();
        $merchant_id = Yii::app()->session['merchant_id'];


        $result = $userC->RevertUserGrade($merchant_id);
        $result = json_decode($result,true);
        if($result['status'] == ERROR_NONE){
            $url = $this->createUrl('setUserGrade');
            echo "<script>alert('还原成功');window.location.href='$url'</script>";
        }else {
            $status = $result['status'];
            $msg = $result['errMsg'];
            echo "<script>alert('$msg');</script>";
        }
    }

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
                    $userC=new UserC();
                    $rs=json_decode($userC->checkUserPointRule($merchant_id,$post['points_rule']),true);
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
                $userC = new UserC();
                $id = '';
                $merchant_id = Yii::app() -> session['merchant_id'];
                $is_exit = $userC -> isUserGradeNameExit($post['name'],$id,$merchant_id);
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
                $userC = new UserC();
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


                $result = $userC -> addUserGrade($merchant_id,$name,$points_rule,$discount,$discount_illustrate,$points_ratio,$cover,$cardName,$if_hideword,$rule_type,$birthday_rate);
                $result = json_decode($result,true);
                if($result['status'] == ERROR_NONE){
                    $url = $this->createUrl('setUserGrade');
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
     * 编辑会员等级
     */
    public function actionEditUserGrade($id)
    {
        $model = array();
        $userC = new UserC();
        $merchant_id = Yii::app()->session['merchant_id'];
        $model = $userC -> getUserGradeDraftDetails($id);

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
                        $rs=json_decode($userC->checkUserPointRule($merchant_id,$post['points_rule'],$id),true);
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
                $userC = new UserC();
                $is_exit = $userC -> isUserGradeNameExit($post['name'],$id,$merchant_id);
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

                $result = $userC -> editUserGrade($name,$points_rule,$points_ratio,$discount_illustrate,$discount,$id,$cover,$cardName,$if_hideword,$rule_type,$birthday_rate);
                $result = json_decode($result,true);
                if($result['status'] == ERROR_NONE)
                {

                    $url = $this->createUrl('setUserGrade');
                    echo "<script>alert('修改成功');window.location.href='$url'</script>";
                }
            }

        }
        $userC = new UserC();
        $merchant_id = Yii::app()->session['merchant_id'];
        $Grade = json_decode($userC -> getSetUserGrade($merchant_id),true);
        $GradeDraft = json_decode($userC -> getSetUserGradeDraft($merchant_id),true);
        $res = $userC -> contrastGrade($Grade,$GradeDraft);

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
     * 删除会员等级（会员人数为0的才能删）
     */
    public function actionDelUserGrade($id)
    {
        $userC = new UserC();
        $merchant_id = Yii::app()->session['merchant_id'];
        $result = $userC -> delUserGrade($id,$merchant_id);
        $result = json_decode($result,true);

        if($result['status'] == ERROR_NONE){
            $this -> actionSetUserGrade();
        }
    }

    /**
     * 清空会员条件设置
     */
    public function actionClearType() {
        $userC = new UserC();
        $merchant_id = Yii::app()->session['merchant_id'];
        $type = $_GET['type'] + 1;
        $GradeDraft = json_decode($userC -> getSetUserGradeDraft($merchant_id),true);
        $userC->clearGradeType($GradeDraft,$type);
        $arr=array('v'=>'所有条件已清除，请重新设置');
        echo json_encode($arr);
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

                    $userC = new UserC();
                    $id = '';
                    $userGrandId = isset($_GET['userGrandId'])?$_GET['userGrandId']:'';
                    $userGrandName = $userC -> getUserGrandName($userGrandId);
                    $merchant_id = Yii::app() -> session['merchant_id'];
                    if($userGrandName != trim($data)){ //编辑界面     如果原输入框的会员等级名称和userGrandId对应的数据库的会员等级名称不一样  说明有改动  要判断是否存在
                        $is_exit = $userC -> isUserGradeNameExit($data,$id,$merchant_id);
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
                        $userC=new UserC();
                        $userGrandId = isset($_GET['userGrandId'])?$_GET['userGrandId']:'';
                        $userGrandPointRule = $userC -> getUserGrandPointRule($userGrandId);
                        $rs=json_decode($userC->checkUserPointRule($merchant_id,$data),true);
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


}