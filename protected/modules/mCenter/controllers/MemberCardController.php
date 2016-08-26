<?php

class MemberCardController extends mCenterController
{
    /**
     * 查询会员卡列表
     */
    public function actionGetMembershipCardList()
    {
        $ret = new UserRule();
        $merchantId = Yii::app()->session['merchant_id'];
        $model = $ret->GetMembershipCardList($merchantId);
        $memberCardList = json_decode($model,true);        
        if($memberCardList['status'] == ERROR_NONE)
        {
            $memberCardList = $memberCardList['data'];
        } else {
            $memberCardList = array();
        }
        $this->render('getMembershipCardList',array('memberCardList'=>$memberCardList));
    }
    
    /**
     * 修改会员卡
     */
    public function actionEditMembershipCard()
    {
        $id    = isset($_GET['id']) ? $_GET['id'] : '';
        $merchantId = Yii::app()->session['merchant_id'];
        $membershipCardName = isset($_POST['membership_card_name']) ? $_POST['membership_card_name'] : '';        
        $ret   = new UserRule();
        $membercard_img = isset($_POST['image']) ? $_POST['image'] : '';
        $card = '';
        if(isset($_POST) && $_POST)
        {
            $model = $ret->EditMembershipCard($id,$merchantId,$membershipCardName,$membercard_img);
            $editMembershipCard = json_decode($model,true);        
            if($editMembershipCard['status'] == ERROR_NONE)
            {                
                $url = Yii::app()->createUrl('mCenter/MemberCard/getMembershipCardList');
                echo "<script>alert('修改成功');window.location.href='$url'</script>";
            }
        }
        $member = $ret->Back($id, $merchantId);
        $shipCard = json_decode($member,true);
        if($shipCard['status'] == ERROR_NONE)
        {
            $card = $shipCard['data'];
        } else {
            $card = '';
        }
        $this->render('editMembershipCard',array('card'=>$card));
    }
}

