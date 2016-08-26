
<body>
    <div class="kkfm_r_inner">
        <div class="storeManage">
            <div class="top">
                <div class="name">
                    添加管理员
                </div>
            </div>
            <?php echo CHtml::beginForm(); ?>
            <div class="groupInfo">
                <h3>管理员信息</h3>
                <div class="groupItem">
                    <span class="groupName">手机号</span>
                    <?php echo CHtml::textField('account',isset($_POST['account'])?$_POST['account']:'',array('placeholder'=>'用于登录管理','class'=>'Grouptext')); ?>
                    <?php if(Yii::app()->user->hasFlash('account_error')){ ?>
                     <span class="error1"><?php echo Yii::app()->user->getFlash('account_error');?></span>
                     <?php }?>
                </div>
                <div class="groupItem">
                    <span class="groupName">姓名</span>
                    <?php echo CHtml::textField('name',isset($_POST['name'])?$_POST['name']:'',array('class'=>'Grouptext')); ?>
                     <?php if(Yii::app()->user->hasFlash('name_error')){ ?>
                     <span class="error1"><?php echo Yii::app()->user->getFlash('name_error');?></span>
                     <?php }?>
                </div>
                <div class="groupItem">
                    <span class="groupName">初始密码</span>
                    <?php echo CHtml::passwordField('pwd',isset($_POST['pwd'])?$_POST['pwd']:'',array('class'=>'Grouptext')); ?>
                     <?php if(Yii::app()->user->hasFlash('pwd_error')){ ?>
                     <span class="error1"><?php echo Yii::app()->user->getFlash('pwd_error');?></span>
                     <?php }?>
                </div>
            </div>
            <div class="limits">
                <h3>权限分配</h3>
                <?php if(Yii::app()->user->hasFlash('limit_error')){ ?>
                     <span class="error1"><?php echo Yii::app()->user->getFlash('limit_error');?></span>
                     <?php }?>
                <div class="limitsWrap">
                    <div class="tree1">
                        <i class="rbArrow"  id="allLimit"></i>
                        <input type="checkbox" class="parentsBox" id="all_limit_check"><i class="iconFolder"></i>全部
                        <ul class="manage_limt">
                        <?php foreach ($GLOBALS['MANAGER_LIMIT'] as $a => $b){ ?>
                            <li class="child1"><i class="rbArrow"></i>
                                <input type="checkbox" name="parent_limit[]" class="parentBox" value="<?php echo $a; ?>"><i class="iconFolder"></i><?php echo $b['text']; ?>
                            </li>
                        <?php }?>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="limits" id="storeBody" style="display: none">
                <h3>门店分配</h3>
                <div class="limitsWrap">
                    <div class="tree1">
                        <i class="rbArrow" id="allManage"></i>
                        <input type="checkbox" class="parentsBox" id="all_check"><i class="iconFolder"></i>全部
                        <ul>
                        <?php if(!empty($store_list)){?>
                        <?php foreach ($store_list as $k => $v){?>
                            <li class="child3">
                            <input type="checkbox" name="store_id[]" id="store<?php echo $v -> id ?>"  class="grandchildBox" value="<?php echo $v -> id; ?>">
                            <i class="iconFile"></i><?php echo $v -> name; ?>
                            </li>
                        <?php }?>
                        <?php }?>
                        </ul>
                        <?php if(!empty($manage_arr)){ ?>
                        <?php foreach ($manage_arr as $k=>$v){ ?>
                        <ul class="manage_list">
                            <li class="child1"><i class="rbArrow"></i>
                                <input type="checkbox" class="parentBox" id="childone<?php echo $k; ?>"><i class="iconFolder"></i><?php echo $v['name']; ?>
                                 <ul class="firstManage<?php echo $k; ?>">
                                 <?php if(!empty($v['firstStore'])){ ?>
                                 <?php foreach ($v['firstStore'] as $ke=>$val){ ?>
                                            <li class="child3">
                                                <input type="checkbox" name="store_id[]" id="store<?php echo $val['id'] ?>"  class="childBox" value="<?php echo $val['id']; ?>"><i class="iconFile"></i><?php echo empty($val['branch_name']) ? $val['name'] : $val['name']."-".$val['branch_name']; ?></li>
                                           
                                 <?php }?>
                                 <?php }?>
                                 </ul>
                                <ul class="firstManage<?php echo $k; ?>">
                                <?php if(!empty($v['sub'])){ ?>
                                <?php foreach ($v['sub'] as $key => $value){ ?>
                                    <li class="child2"><i class="rbArrow" onclick="sub(<?php echo $value['id']; ?>)"></i>
                                        <input type="checkbox" class="childBox" id="childbox<?php echo $value['id']; ?>" ><i class="iconFolder"></i><?php echo $value['name']; ?>
                                        <ul id="sub<?php echo $value['id']; ?>">
                                        <?php if(!empty($value['store'])){ ?>
                                        <?php foreach ($value['store'] as $ked=>$va){ ?>
                                            <li class="child3">
                                                <input type="checkbox" name="store_id[]" id="store<?php echo $va['id'] ?>" class="grandchildBox" value="<?php echo $va['id']; ?>"><i class="iconFile"></i><?php echo $va['name']; ?></li>
                                        <?php }?> 
                                        <?php }?> 
                                        </ul>
                                    </li>
                                <?php }?>
                                <?php }?>
                                </ul>
                               
                            </li>
                        </ul>
                        <?php }?>
                        <?php }?>
                    </div>
                </div>
            </div>
            <div class="save_btn">
                <input type="submit" value="保存" class="btn_com_blue">
            </div>
            <?php echo CHtml::endForm();?>
        </div>
    </div>
</body>

<script type="text/javascript">

/**********权限分配*************/

// $('#allLimit').click(function(){
// 	$('.manage_limt').slideToggle(1000);
// });

//全部复选框勾选状态
$('#all_limit_check').click(function(){
	if($('#all_limit_check').prop("checked")){
		
		$("#storeBody").show(); //门店分配区域显示
		window.parent.callParAutoResize("main",$("body").height());
		
// 		$('.manage_limt').find('.child1').each(function(){
// 			$(this).find('.parentBox').prop('checked',true); //
// 		});
	}else{
		
		$("#storeBody").hide(); //门店分配区域隐藏
		//门店分配区域隐藏后   之前勾选的门店要取消勾选
		$('.manage_list').each(function(){
			$(this).find(".parentBox").prop("checked",true);
			$(this).find(".childBox").prop("checked",true);
			$(this).find(".grandchildBox").prop("checked",true);
		});
		window.parent.callParAutoResize("main",$("body").height());
		
// 		$('.manage_limt').find('.child1').each(function(){
// 			$(this).find('.parentBox').prop('checked',false);
// 		});
	}
});

//权限复选框勾选判断
var LimitNum = 0;
$("input[name='parent_limit\\[\\]']").click(function(){
	
	//当某个多选框没选中 及 全选框选中，则把全选框设置成未选中状态
// 	if(!$(this).prop("checked") && $("#all_limit_check").prop("checked")){
// 		$("#all_limit_check").prop("checked",false);
// 	}
	
	var flag = 1; //定义标志变量
	$("input[name='parent_limit\\[\\]']").each(function(){
		if($(this).val() == '100' && $(this).prop("checked")){
			flag = 2;
		}
		if($(this).val() == '101' && $(this).prop("checked")){
			flag = 2;
		}
        if($(this).val() == '105' && $(this).prop("checked")){
            flag = 2;
        }

// 		if($(this).prop("checked")){
// 			LimitNum ++;
// 		}
	});
	
// 	if(LimitNum == $("input[name='parent_limit\\[\\]']").length){
// 		$("#all_limit_check").prop("checked",true);
// 	}
//	LimitNum = 0; //把值重新初始为0，不然下次计算就会一直叠加上去
	
	if(flag == 2){
		$("#storeBody").show(); //门店分配区域显示
		window.parent.callParAutoResize("main",$("body").height());
	}else{
		$("#storeBody").hide(); //门店分配区域隐藏
		//门店分配区域隐藏后   之前勾选的门店要取消勾选
		$('.manage_list').each(function(){
			$(this).find(".parentBox").prop("checked",false);
			$(this).find(".childBox").prop("checked",false);
			$(this).find(".grandchildBox").prop("checked",false);
		});
		
		window.parent.callParAutoResize("main",$("body").height());
	}
});


/**********门店分配*************/

// $('#allManage').click(function(){
// 	$(".manage_list").slideToggle(1000);
// });

// //二级分组滑入滑出效果
// function sub(manage_id){
// 	$('#sub'+manage_id).slideToggle(1000);
// }

// //一级分组滑入滑出效果
// function firstManage(manage_id){
// 	$('.firstManage'+manage_id).slideToggle(1000);
// }

//全部复选框勾选
// $('#all_check').click(function(){
// 	if($('#all_check').prop("checked")){
// 		$('.manage_list').each(function(){
// 			$(this).find(".parentBox").prop("checked",true); //一级复选框勾选
// 			$(this).find(".childBox").prop("checked",true);  //二级复选框勾选
// 			$(this).find(".grandchildBox").prop("checked",true); //门店复选框勾选
// 		});
// 	}else{
// 		$('.manage_list').each(function(){
// 			$(this).find(".parentBox").prop("checked",false);
// 			$(this).find(".childBox").prop("checked",false);
// 			$(this).find(".grandchildBox").prop("checked",false);
// 		});
// 	}
// });

// //一级分组复选框勾选
// function childone(manage_id)
// {
// 	if($('#childone'+manage_id).prop("checked")){
// 		$('.firstManage'+manage_id).find('.child3').each(function(){
// 			$(this).find('.grandchildBox').prop("checked",true); //一级下的门店复选框勾选
// 		});
// 		$('.firstManage'+manage_id).find('.child2').each(function(){
// 			$(this).find('.childBox').prop("checked",true); //一级下的二级分组勾选
// 		});
// 	}else{
// 		$('.firstManage'+manage_id).find('.child3').each(function(){
// 			$(this).find('.grandchildBox').prop("checked",false);
// 		});
// 		$('.firstManage'+manage_id).find('.child2').each(function(){
// 			$(this).find('.childBox').prop("checked",false);
// 		});
//     }
// }

// //二级分组复选框勾选
// function childbox(manage_id)
// {
// 	if($('#childbox'+manage_id).prop("checked")){
// 		$('#sub'+manage_id).find('.child3').each(function(){
// 			$(this).find('.grandchildBox').prop("checked",true); //二级下的门店勾选
// 		});
// 	}else{
// 		$('#sub'+manage_id).find('.child3').each(function(){
// 			$(this).find('.grandchildBox').prop("checked",false); //二级下的门店取消勾选
// 		});
// 	}
// }

// //门店勾选判断
// //store_id门店id   manage_id门店所属分组id  first_manage_id二级所属一级分组
// var storeNum = 0;
// function checkStore(store_id,manage_id,first_manage_id)
// {
// 	if(!$('#store'+store_id).prop("checked") && $("#childbox"+manage_id).prop("checked")){
// 		$("#childbox"+manage_id).prop("checked",false);
// 	}
// 	$('#sub'+manage_id).find('.child3').each(function(){
// 		if($(this).find('.grandchildBox').prop("checked")){
// 			storeNum ++;
// 		} 
		
// 	});
// 	if(storeNum == $('#sub'+manage_id).find('.child3').find('.grandchildBox').length){
// 		$("#childbox"+manage_id).prop("checked",true);
		
// 	}
// 	storeNum = 0;
// }

</script>
