
<h4 class="g-title">查看详情</h4>
<div class="g-container">
    <div class="m-estate-box">
        <div class="form-group">
            <div class="estate-title">
                <span>姓名：</span>
            </div>
            <div class="estate-con">
                <span><?php echo !empty($proprietor_detail['name']) ? $proprietor_detail['name'] : ''?></span>
            </div>
        </div>
        <div class="form-group">
            <div class="estate-title">
                <span>业主手机号：</span>
            </div>
            <div class="estate-con">
                <span><?php echo !empty($proprietor_detail['tel']) ? $proprietor_detail['tel'] : ''?></span>
            </div>
        </div>
        <div class="form-group">
            <div class="estate-title">
                <span>门禁卡号：</span>
            </div>
            <div class="estate-con">
                <span><?php echo !empty($proprietor_detail['access_control_card_no']) ? $proprietor_detail['access_control_card_no'] : ''?></span>
            </div>
        </div>
        <div class="form-group">
            <div class="estate-title mt6">
                <span>类型：</span>
            </div>
            <div class="estate-con">
                <span><?php echo !empty($proprietor_detail['type']) ? $GLOBALS['__PROPRIETOR_TYPE'][$proprietor_detail['type']] : ''?></span>
            </div>
        </div>
        <div class="form-group">
            <div class="estate-title mt6">
                <span>地址：</span>
            </div>
            <div class="estate-con">
                <span><?php echo !empty($proprietor_detail['community_id']) ? $proprietor_detail['community_id'] : ''?>-<?php echo !empty($proprietor_detail['building_number']) ? $proprietor_detail['building_number'] : ''?>-<?php echo !empty($proprietor_detail['room_number']) ? $proprietor_detail['room_number'] : ''?></span>
            </div>
        </div>
        <?php $family_members = json_decode($proprietor_detail['family_members'], true)?>
        <div class="form-group">
            <div class="estate-title mt6">
                <span>家庭联系人：</span>
            </div>
            <div class="estate-con">
                <span><?php echo isset($family_members[0]) ? $family_members[0] : ''?></span>
            </div>
        </div>
        <div class="form-group">
            <div class="estate-title">
                <span>与业主关系：</span>
            </div>
            <div class="estate-con">
                <span><?php echo isset($family_members[3]) ? $family_members[3] : ''?></span>
            </div>
        </div>
        <div class="form-group">
            <div class="estate-title">
                <span>手机号：</span>
            </div>
            <div class="estate-con">
                <span><?php echo isset($family_members[1]) ? $family_members[1] : ''?></span>
            </div>
        </div>
        <div class="form-group">
            <div class="estate-title">
                <span>门禁卡号：</span>
            </div>
            <div class="estate-con">
                <span><?php echo isset($family_members[2]) ? $family_members[2] : ''?></span>
            </div>
        </div>
        <div class="form-inline m-form-group text-center mt45">
            <a href="<?php echo $this->createUrl('ProprietorList')?>" class="btn btn-default m-btn" >返回</a>
        </div>
    </div>
</div>
