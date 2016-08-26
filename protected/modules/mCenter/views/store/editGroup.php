<script type="text/javascript"
	src="<?php echo SYT_STATIC_JS?>artDialog/jquery.artDialog.js?skin=simple"></script>
<script type="text/javascript"
	src="<?php echo SYT_STATIC_JS?>artDialog/plugins/iframeTools.js"></script>
<body>
	<div class="kkfm_r_inner">
		<div class="storeManage">
			<div class="top">
				<div class="name">添加分组</div>
			</div>
            <?php echo CHtml::beginForm(); ?>
            <div class="groupInfo">
				<h3>分组信息</h3>
				<div class="groupItem">
					<span class="groupName">分组名称</span>
                    <?php echo CHtml::textField('name',$model['name'],array('class'=>'Grouptext','placeholder'=>'请填写分组名称')); ?>
                    <?php if(Yii::app()->user->hasFlash('manageMent_name_error')){ ?>
                     <span class="error1"><?php echo Yii::app()->user->getFlash('manageMent_name_error');?></span>
                     <?php }?>
				</div>
				<div class="groupItem clearfix newGroup" style="display:none">
					<span class="groupName">所属分组</span>
					<div class="text">
						<input type="radio" 
							<?php if(empty($model['p_mid'])){echo 'checked';}else{echo '';} ?>
							name="groupType" value="1"> <label>新建分组</label>
                        <?php if(!empty($groupList)){ ?>
                        <div class="addTo">
							<input type="radio" 
								<?php if(!empty($model['p_mid'])){echo 'checked';}else{echo '';} ?>
								name="groupType" value="2"> <label>添加到</label>
                            <?php echo CHtml::dropDownList('gruop',$model['p_mid'],$groupList); ?>
                        </div>
                        <?php }?>
                    </div>
				</div>
			</div>
			<input type="hidden" id="store_str" value=""><!-- 定义父窗口隐藏域   存放弹出框选中的门店 -->
			
			<input type="hidden" id="str_id" name="containStore"
				value="<?php echo $store_id_str; ?>" />
			<div class="searchWrap">
				<h3>管理门店</h3>
				<div class="cz">
					<a href="javascript:void(0);" class="btn_com_blue"
						onclick="addStore()">选择门店</a>
				</div>
			</div>
			<div class="bus-contant">
				<table cellspacing="0" cellpadding="0" width="100%" id="addtable">
					<tr class="thead">
						<td width="20%">编号</td>
						<td width="20%">门店名称</td>
						<td align="45%">门店地址</td>
						<td align="center">操作</td>
					</tr>
                    
                    <?php if(!empty($model['store_name'])) {?>
                         <?php foreach ($model['store_name'] as $k=>$v){ ?>
                             <tr class="tbody" id="<?php echo $k; ?>">
						      <td><?php echo $v['number']; ?></td>
						      <td class="storeName"><?php echo $v['name']; ?></td>
						      <td><?php echo $v['address']; ?></td>
						      <td align="center"><a href="#" onclick="removeStore(<?php echo $k; ?>)">移除</a></td>
					         </tr>
                         <?php }?>
                    <?php }?>
                  
                </table>
                 <div class="save_btn">
				    <input type="submit" value="保存" class="btn_com_blue save_btn">
				</div>
                <?php echo CHtml::endForm();?>
            </div>
		</div>
	</div>
</body>

<script type="text/javascript">
$(function(){
	//给隐藏域赋值（数据库存在的门店id）    
    var str = '<?php echo $store_id_str; ?>';
    var str = str+",";
    str = str.substr(1); //去掉字符串第一个逗号
    $('#store_str').val(str); //给隐藏域赋值
});
function addStore()
{
	var data = [];
	var row = {};
	$('table tbody').find('.tbody').each(function(){
		row = {'id':$(this).attr('id'),'name':$(this).children('.storeName').html()};
		data.push(row);
	});
   // console.log(data);
	//JSON.stringify(data)  转化为php识别的数据   $type是添加操作的标示  与 编辑区分开来
	art.dialog.open(
        	'<?php echo Yii::app()->createUrl('mCenter/store/addStoreDialog');?>'+'?arr_store='+JSON.stringify(data),
        	 {
              	 title: '',
              	 lock: true,
              	 drag:true, 
                 width: '655px',
              	 id: 'dialog_notice',
              	 background: '#fff',
             }
    );
    //return false;
}

function addStoreInfo(store_arr)
{	
	$('table').find('.tbody').remove(); //删除所有节点
	var str_id = ''; //定义所有选中门店id的字符串形式
	for(var i = 0;i<store_arr.length;i++){
		str_id = str_id + ',' + store_arr[i];
		
		$.ajax({
			url : '<?php echo Yii::app()->createUrl('mCenter/store/loadStoreOnPage'); ?>',
			data : {store_id : store_arr[i]},
			type : 'post',
			success : function(data){
				var data = jQuery.parseJSON(data);
				$('#addtable').append('<tr class="tbody" id="'+data.id+'">'+
						          '<td>'+data.number+'</td>'+
						          '<td class="storeName">'+data.name+'</td>'+
						          '<td>'+data.address+'</td>'+
						          '<td align="center">'+'<a href="#" onclick="removeStore('+data.id+')">移除'+'</a>'+'</td>'+
						          '</tr>'
						);
				window.parent.callParAutoResize("main",$("body").height());	
			}
		});
	}
	$("#str_id").val(str_id); //给隐藏域赋值
	art.dialog.get("dialog_notice").close(); //关闭弹出框
	 
}

function removeStore(id)
{//alert($("#str_id").val())
	var store_str = $('#store_str').val();
	var str = '';
	var strId = '';

	
	$('#'+id).remove();
	var store_arr = new Array();
	store_arr = store_str.split(","); 
	store_arr.pop();
	for(var j=0;j<store_arr.length;j++){
		if(store_arr[j] != id){
			str = str + store_arr[j] + ",";
		}
	}

	var str_id = $("#str_id").val();
	var str_id_arr = new Array();
	str_id_arr = str_id.split(",");
	for(var i=1;i<str_id_arr.length;i++){
		if(str_id_arr[i] != id){
			strId = strId + str_id_arr[i] + ",";
		}
	}
	strId = strId.substring(0,strId.length-1);
	strId = ","+strId;

	$("#str_id").val(strId); //给隐藏域赋值
	$('#store_str').val(str);//alert($("#str_id").val())
}

//关闭弹出框
function closeWinDialog()
{
	art.dialog.get("dialog_notice").close();
	location.reload() 
}
</script>

