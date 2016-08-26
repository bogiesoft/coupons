
    <div class="kkfm_r_inner">
        <div class="storeManage">
            <div class="top">
                <div class="name">
                    会员认证设置
                </div>
            </div>
            <div class="bus-contant">
            <?php echo CHtml::beginForm(Yii::app()->createUrl('mCenter/user/saveauthsettings'), 'get')?>
                <div class="cert">
                    <h3>选择用户注册会员时必须填写的信息：</h3>
                    <ul class="cert-con">
                        <li>
                            <label>
                                <input type="checkbox" name="cert[0]" id="cert_0" checked disabled="">手机号码</label>
                        </li>
                        <li>
                            <label>
                                <input type="checkbox" name="cert[1]" id="cert_1" <?php if (in_array('1', $authset)){echo 'checked';}?>>会员姓名</label>
                        </li>
                        <li>
                            <label>
                                <input type="checkbox" name="cert[2]" id="cert_2" <?php if (in_array('2', $authset)){echo 'checked';}?>>会员性别</label>
                        </li>
                        <li>
                            <label>
                                <input type="checkbox" name="cert[3]" id="cert_3" <?php if (in_array('3', $authset)){echo 'checked';}?>>会员生日</label>
                        </li>
                        <li>
                            <label>
                                <input type="checkbox" name="cert[4]" id="cert_4" <?php if (in_array('4', $authset)){echo 'checked';}?>>通讯地址</label>
                        </li>
                        <li>
                            <label>
                                <input type="checkbox" name="cert[5]" id="cert_5" <?php if (in_array('5', $authset)){echo 'checked';}?>>身份证号</label>
                        </li>
                        <li>
                            <label>
                                <input type="checkbox" name="cert[6]" id="cert_6" <?php if (in_array('6', $authset)){echo 'checked';}?>>邮&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;箱</label>
                        </li>
                        <li>
                            <label>
                                <input type="checkbox" name="cert[7]" id="cert_7" <?php if (in_array('7', $authset)){echo 'checked';}?>>婚姻情况</label>
                        </li>
                        <li>
                            <label>
                                <input type="checkbox" name="cert[8]" id="cert_8" <?php if (in_array('8', $authset)){echo 'checked';}?>>工&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;作</label>
                        </li>
                    </ul>
                </div>
                <div class="btn level_r cert-btn">
                    <input type="submit" class="btn_com_blue" value="提交保存">
                </div>
            </div>
            <?php echo CHtml::endForm();?>
            <!--<a href="<?php echo Yii::app()->createUrl('mCenter/user/NewInit') ?>">执行脚本</a>-->
        </div>
    </div>
