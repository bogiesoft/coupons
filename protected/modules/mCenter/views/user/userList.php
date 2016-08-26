<script type="text/javascript">
	$(document).ready(main_obj.list_init);
</script>

<div class="kkfm_r_inner">
	<div class="top">
        	<div class="name">会员管理</div>
    	</div>
	<div class="contant">
    	<div class="card-left">
            <?php $form = $this->beginWidget('CActiveForm',array('method'=>'get'))?>
            <div class="shop-con">
            	<span>
                    <input type="text" name="account" value="<?php echo isset($_GET['account']) ? $_GET['account'] : ''?>" class="txt" style="width:250px;margin-right:30px" placeholder="请输入会员手机号">
                    <input type="submit" value="搜索" class="btn_com_blue">
                </span>
                <span class="banner">
                    <ul>
                        <?php $usergroups = isset($_GET['usergroups']) ? $_GET['usergroups'] : '';?>
                        <?php if(!empty($usergroups)) { ?>
                    	<li>分组名称</li>                        
                            <?php if(!empty($usergroup)) { ?>
                                <?php foreach ($usergroup as $key => $value) { ?> 
                                    <?php if($value['group_id'] == $usergroups) { ?>
                                        <li class="blue"><?php echo $value['group_name']?>&nbsp;&nbsp;<a  href="javascript:void(0);" id="outside1" >修改</a>&nbsp;&nbsp;<a  href="<?php echo Yii::app()->createUrl('mCenter/user/del',array('id'=>$usergroups))?>" id="outside1" onclick="return confirm('确定删除吗？');">删除</a></li>  
                                    <?php } ?>
                                <?php } ?>
                            <?php } ?>
                        <?php } ?>
                    </ul>
                </span>
                <div class="clear"></div>
            <?php $form = $this->endWidget()?>
                <div class="top">
                <!--
                    <div class="check"><input type="checkbox">全选</div>
                    <div class="check">
                    	<select>
                        	<option>添加到</option>
                        </select>
                    </div>
                    <div class="check">
                    	<select>
                        	<option>移动到</option>
                        </select>
                    </div>
                -->
                </div>
                <div class="classify">
                <?php if(!empty($list)) { ?>
                <?php foreach ($list as $key => $value) { ?>
                    <div <?php echo $key%2==1 ? 'class="list clearfix listbg"' : 'class="list clearfix"'?>>
                        <span class="check"><input type="checkbox"></span>
                        <span class="img"><img src="<?php echo isset($value['avatar']) ? $value['avatar'] : ''?>"></span>
                        <span class="name"><?php echo isset($value['name']) ? $value['name'] : $value['nickname']?><br><?php echo $value['account']?></span>
                        <span class="hydj">
                            <?php if(!empty($group)) { ?>
                            <form action="<?php echo Yii::app()->createUrl('mCenter/user/Grade')?>" method="post" id="formedit">                                
                                <select name="grade_id" id="grade_id">                                    
                                    <?php foreach ($group as $k => $v) { ?>                                                                           
                                    <option value="<?php echo $v['id']?>" <?php echo $value['membershipgrade_id'] == $v['id'] ? 'selected="selected"' : ''?>><?php echo $v['name']?></option>                                     
                                    <?php } ?>                                    
                        	</select>
                                <input type="hidden" value="<?php echo $value['id']?>" name="userid">
                                <button>修改</button>                                
                            </form>
                            <?php } ?>
                        </span>
                        <span class="info">
                        	<a href="<?php echo $this->createUrl('userDetails',array('id'=>$value['id']))?>">详情</a>
                        </span>
                    </div>
                <?php } ?>
                <?php } else {?>
                    <div class="list clearfix"><span>没有记录</span></div>
                <?php } ?>
                </div>
                <div class="btn"></div>
             </div>
		</div>
        <div class="card-right">
            <span><a  href="javascript:void(0);" id="outside" class="btn_com_blue" >添加分组</a></span>
            <div class="fz">
            	<dl>
                    <dt><a href="<?php echo $this->createUrl('userList')?>">全部会员</a></dt>
                    <?php if(!empty($group)) { ?>
                    <?php foreach ($group as $k => $v) { ?>                    
                    <dd <?php echo $k%2==1 ? 'class="fz-bg"' : ''?>><a href="<?php echo $this->createUrl('userList',array('groups'=>$v['id']))?>"><?php echo $v['name']?>(<?php echo $v['num']?>)</a></dd></dd>                   
                    <?php } ?>
                    <?php } ?>
                    <?php if(!empty($usergroup)) { ?>
                    <?php foreach ($usergroup as $key => $value) { ?>                    
                    <dd <?php echo $key%2==1 ? '' : 'class="fz-bg"'?>><a href="<?php echo $this->createUrl('userList',array('usergroups'=>$value['group_id']))?>"><?php echo $value['group_name']?>(<?php echo $value['num']?>)</a></dd></dd>                   
                    <?php } ?>
                    <?php } ?>
                </dl>
            </div>
        </div>
   	</div>
</div> 
<script>
    //弹出窗口
    $(document).ready(function() {
        $('#outside').click(function(){
                popWin.showWin("500","200","添加分组","<?php echo Yii::app()->createUrl('mCenter/user/addGroup')?>");
        });
        $('#outside1').click(function(){
                popWin.showWin("500","200","修改分组","<?php echo Yii::app()->createUrl('mCenter/user/editGroup',array('id'=>$usergroups))?>");
        });
    });
    //下拉框表单提交
    function fun(){
        if(confirm('确定修改吗？'))
        {
            document.getElementById("formedit").submit();
        }
    }
    
</script>
