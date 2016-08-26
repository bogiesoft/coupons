
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
                    <?php echo CHtml::textField('account',$model['account'],array('placeholder'=>'用于登录管理','class'=>'Grouptext')); ?>
                    <?php if(Yii::app()->user->hasFlash('account_error')){ ?>
                     <span class="error1"><?php echo Yii::app()->user->getFlash('account_error');?></span>
                     <?php }?>
                </div>
                <div class="groupItem">
                    <span class="groupName">姓名</span>
                    <?php echo CHtml::textField('name',$model['name'],array('class'=>'Grouptext')); ?>
                     <?php if(Yii::app()->user->hasFlash('name_error')){ ?>
                     <span class="error1"><?php echo Yii::app()->user->getFlash('name_error');?></span>
                     <?php }?>
                </div>
                <div class="groupItem">
                    <span class="groupName">初始密码</span>
                    <?php echo CHtml::passwordField('pwd',$model['pwd'],array('class'=>'Grouptext')); ?>
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
                            <?php if(in_array($a,$model['right'])){ ?>
                                <input type="checkbox" name="parent_limit[]" checked="checked" class="parentBox" value="<?php echo $a; ?>"><i class="iconFolder"></i><?php echo $b['text']; ?>
                                <?php }else{?>
                                <input type="checkbox" name="parent_limit[]" class="parentBox" value="<?php echo $a; ?>"><i class="iconFolder"></i><?php echo $b['text']; ?>
                                <?php }?>
                            </li>
                        <?php }?>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="limits" id="storeBody">
                <h3>门店分配</h3>
                <div class="limitsWrap">
                    <div class="tree1">
                        <i class="rbArrow" id="allManage"></i>
                        <input type="checkbox" class="parentsBox" id="all_check"><i class="iconFolder"></i>全部
                        
                        <ul>
                        <?php if(!empty($store_list)){?>
                        <?php foreach ($store_list as $k => $v){?>
                            <li class="child3">
                            <input type="checkbox" name="store_id[]" id="store<?php echo $v -> id ?>" <?php if(in_array($v -> id,$model['store_id'])){ ?>checked<?php }?>  class="grandchildBox" value="<?php echo $v -> id; ?>">
                            <i class="iconFile"></i><?php echo $v -> name; ?>
                            </li>
                        <?php }?>
                        <?php }?>
                        </ul>
                        
                        
                        <?php if(!empty($manage_arr)){ ?>
                        <?php foreach ($manage_arr as $k=>$v){ ?>
                        <ul class="manage_list">
                            <li class="child1"><i class="rbArrow" onclick="firstManage(<?php echo $k; ?>)"></i>
                                <input type="checkbox" class="parentBox" id="childone<?php echo $k; ?>" onclick="childone(<?php echo $k; ?>)"><i class="iconFolder"></i><?php echo $v['name']; ?>
                                
                                 <ul class="firstManage<?php echo $k; ?>">
                                 <?php if(!empty($v['firstStore'])){ ?>
                                 <?php foreach ($v['firstStore'] as $ke=>$val){ ?>
                                            <li class="child3">
                                            <?php if(in_array($val['id'],$model['store_id'])){ ?>
                                                <input type="checkbox" name="store_id[]" checked="checked" class="grandchildBox" value="<?php echo $val['id']; ?>"><i class="iconFile">
                                                <?php }else{?>
                                                <input type="checkbox" name="store_id[]" class="grandchildBox" value="<?php echo $val['id']; ?>"><i class="iconFile">
                                                <?php }?>
                                                </i><?php echo $val['name']; ?></li>
                                           
                                 <?php }?>
                                 <?php }?>
                                 </ul>
                                
                                <ul class="firstManage<?php echo $k; ?>">
                                <?php if(!empty($v['sub'])){ ?>
                                <?php foreach ($v['sub'] as $key => $value){ ?>
                                    <li class="child2"><i class="rbArrow" onclick="sub(<?php echo $value['id']; ?>)"></i>
                                        <input type="checkbox" class="childBox" id="childbox<?php echo $value['id']; ?>" onclick="childbox(<?php echo $value['id']; ?>)"><i class="iconFolder"></i><?php echo $value['name']; ?>
                                        <ul id="sub<?php echo $value['id']; ?>">
                                        <?php if(!empty($value['store'])){ ?>
                                        <?php foreach ($value['store'] as $ked=>$va){ ?>
                                            <li class="child3">
                                             <?php if(in_array($va['id'],$model['store_id'])){ ?>
                                                <input type="checkbox" name="store_id[]" checked="checked" class="grandchildBox" value="<?php echo $va['id']; ?>"><i class="iconFile">
                                                <?php }else{?>
                                                <input type="checkbox" name="store_id[]" class="grandchildBox" value="<?php echo $va['id']; ?>"><i class="iconFile">
                                                <?php }?>
                                                </i><?php echo $va['name']; ?></li>
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

$(function(){
	if('<?php echo count($model['store_id']) ?>' == 0){ //门店数为0
		$("#storeBody").hide(); //门店分配区域隐藏
		window.parent.callParAutoResize("main",$("body").height());
	}else{
		$("#storeBody").show(); //门店分配区域显示
		window.parent.callParAutoResize("main",$("body").height());
	}
	if(<?php echo $allpick ?>){
		$('#all_check').prop("checked",true);
		$('.manage_list').each(function(){
			$(this).find(".parentBox").prop("checked",false);
			$(this).find(".childBox").prop("checked",false);
			$(this).find(".grandchild   Box").prop("checked",false);
		});
	}

	
});

/**********权限分配*************/

// $('#allLimit').click(function(){
// 	$('.manage_limt').slideToggle(1000);
// });

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
			$(this).find(".parentBox").prop("checked",false);
			$(this).find(".childBox").prop("checked",false);
			$(this).find(".grandchildBox").prop("checked",false);
		});
		window.parent.callParAutoResize("main",$("body").height());
		
// 		$('.manage_limt').find('.child1').each(function(){
// 			$(this).find('.parentBox').prop('checked',false);
// 		});
	}
});

//权限复选框勾选判断
$("input[name='parent_limit\\[\\]']").click(function(){
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
	});
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


</script>
