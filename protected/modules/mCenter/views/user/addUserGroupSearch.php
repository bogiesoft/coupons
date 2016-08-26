<script type="text/javascript">
	$(document).ready(main_obj.list_init);
        //设置日期控件
	$(document).ready(function() {
		$('input[name=Time]').daterangepicker({
	        format: 'YYYY/MM/DD',
	        dateLimit: true,
	        maxDate: true
	    });
	});
</script>
<body>
	<div class="kkfm_r_inner">
		<div class="storeManage">
			<div class="top">
				<div class="name">新建分组</div>
			</div>
			<div class="bus-contant new_group_wrap">
				<div class="btn_l level_r">
					<a href="<?php echo Yii::app()->createUrl('mCenter/user/addUserGroupSearch'); ?>" class="btn_com_blue btn_blue_group">根据条件筛选</a>
					<a href="<?php echo Yii::app()->createUrl('mCenter/user/addUserGroupSelf'); ?>" class="btn_com_gray btn_gray_group">自定义分组</a>
				</div>
                <?php echo CHtml::beginForm('','post',array('id'=>'addForm')); ?>
                <div class="filtrate_group">
					<div class="new_group">
						<div class="new_group_item">
							<h3 class="first_title">分组名称</h3>
							<div class="new_group_item_c">
                                <?php echo CHtml::textField('name',isset($_POST['name'])?$_POST['name']:'',array('class'=>'text_group','placeholder'=>'请输入分组名称，最多9个汉字')); ?>
                            </div>
						</div>
						<div class="new_group_item">
							<h3>用户来源</h3>
							<div class="new_group_item_c">
                            <?php echo CHtml::checkBoxList('from', '', $from,array('separator'=>'')); ?>
<!--                                 <input type="checkbox" id="from_0" value="1" name="from[]"> -->
								<!--                                 <label for="from_0">微信</label> -->
								<!--                                 <input type="checkbox" id="from_1" value="2" name="from[]"> -->
								<!--                                 <label for="from_1">支付宝</label> -->
								<!--                                 <input type="checkbox" id="from_2" value="3" name="from[]"> -->
								<!--                                 <label for="from_2">未知</label> -->
							</div>
						</div>
						<div class="new_group_item">
							<h3>基本信息</h3>
							<div class="new_group_item_c">
								<div class="basic_info_item">
									<div class="es_text">
										<span>性别</span>
                                       <?php echo CHtml::checkBoxList('sex', '', $sex,array('separator'=>'')); ?>
<!--                                         <input type="checkbox" id="sex_0" value="1" name="sex[]"> -->
										<!--                                         <label for="sex_0">男</label> -->
										<!--                                         <input type="checkbox" id="sex_1" value="2" name="sex[]"> -->
										<!--                                         <label for="sex_1">女</label> -->
										<!--                                         <input type="checkbox" id="sex_2" value="3" name="sex[]"> -->
										<!--                                         <label for="sex_2">未知</label> -->
									</div>
<!-- 									<div class="es_span"> -->
<!-- 										<span>年龄</span> -->
<!-- 										<div class="age_select"> -->
<!-- 											<input type="text" id="text_age" class="text_group text_time"> -->
<!-- 											<ul class="age_select_dropdown"> -->
<!-- 												<li>18岁以下</li> -->
<!-- 												<li>18~24岁</li> -->
<!-- 												<li>25~29岁</li> -->
<!-- 												<li>30~34岁</li> -->
<!-- 												<li>35~39岁</li> -->
<!-- 												<li>40~49岁</li> -->
<!-- 												<li>50~59岁</li> -->
<!-- 												<li>60岁以上</li> -->
<!-- 												<li>未知</li> -->
<!-- 											</ul> -->
<!-- 										</div> -->
<!-- 									</div> -->
								</div>
								<div class="basic_info_item">
									<span>注册时间</span> <input type="text"
										class="text_group text_time" name="Time">
									<!--                                     <div class="es_span"> -->
									<!--                                         <span>职业</span> -->
									<!--                                         <select class="iselect"> -->
									<!--                                             <option>全部</option> -->
									<!--                                             <option>设计师</option> -->
									<!--                                             <option>码农</option> -->
									<!--                                             <option>码农</option> -->
									<!--                                         </select> -->
									<!--                                     </div> -->
								</div>
								<div class="basic_info_item">
                                    <em>地区</em>
                                    <select class="iselect user_select" id="province" name="province">
                                       <option value="">-请选择省份-</option>
                    	               <?php foreach ($province as $k => $v){?>
                    	               <option value="<?php echo $v -> code?>"><?php echo $v-> name?></option>
                    	               <?php }?>
                                    </select>
                                    <select class="iselect user_select" id="city" name="city">
                                       <option value="">-请选择城市-</option>
                		               <?php foreach ($city as $k => $v){?>
                    	               <option value="<?php echo $v -> code?>"><?php echo $v-> name?></option>
                    	               <?php }?>
                                    </select>
                                    <button class="btn_com_lightblue btn_addcity" id="addBt">添加</button>
                                    <div class="selected_city">
<!--                                         <span class="city"><i>×</i>温州</span> -->
<!--                                         <span class="city"><i>×</i>温州</span> -->
                                    </div>
                                    <input type="hidden" value="" id="addressHide" name="addressHide">
                                </div>
							</div>
						</div>
						<div class="new_group_item">
							<h3>会员等级</h3>
							<!--                             <div class="new_group_item_c"> -->
                             <?php echo CHtml::checkBoxList('userGrade', '', $userGrade,array('separator'=>'')); ?>
<!--                             </div> -->
						</div>
<!-- 						<div class="new_group_item"> -->
<!-- 							<h3>消费情况</h3> -->
<!-- 							<div class="new_group_item_c"> -->
<!-- 								<div class="basic_info_item"> -->
<!-- 									<span>消费频次</span> <input type="checkbox" id="sk"> <label -->
<!-- 										for="sk">散客</label> <input type="checkbox" id="ck"> <label -->
<!-- 										for="ck">常客</label> <a href="" class="self_defined">自定义参数</a> -->
<!-- 								</div> -->
<!-- 							</div> -->
<!-- 							<div class="new_group_item_c"> -->
<!-- 								<div class="basic_info_item"> -->
<!-- 									<span>消费金额</span> <input type="checkbox" id="low"> <label -->
<!-- 										for="low">低消费</label> <input type="checkbox" id="high"> <label -->
<!-- 										for="high">高消费</label> <a href="" class="self_defined">自定义参数</a> -->
<!-- 								</div> -->
<!-- 							</div> -->
<!-- 							<div class="new_group_item_c"> -->
<!-- 								<div class="basic_info_item"> -->
<!-- 									<span>流失情况</span> <input type="checkbox" id="mild"> <label -->
<!-- 										for="mild">轻度流失</label> <input type="checkbox" id="serious"> <label -->
<!-- 										for="serious">重度流失</label> <a href="" class="self_defined">自定义参数</a> -->
<!-- 								</div> -->
<!-- 							</div> -->
<!-- 						</div> -->
						<div class="new_group_item">
							<h3>分组结果</h3>
							<p>
								共选择<em class="total"></em>位用户，占总体<em class="percent"></em>
							</p>
						</div>
					</div>
					<div class="btn level_r">
						<input id="submitBt" type="button" class="btn_com_blue" value="保存">
						<!--                         <input type="submit" class="btn_com_gray" value="取消"> -->
					</div>
				</div>
               <?php echo CHtml::endForm(); ?>
            </div>
		</div>
	</div>
</body>

<script>
   var from_alipay = '';
   var from_wx = '';
   var from_other = '';
   var sex_male = '';
   var sex_femal = '';
   var sex_other = '';
   var userGradeArr = new Array();
   var age = '';
   var regist_time = '';

   //用户来源复选框点击事件
   $("input[name=from\\[\\]]").click(function(){

	   $("input[name=from\\[\\]]").each(function(){
		   if($(this).val() == 1){ //如果点击微信来源
			   if($(this).prop("checked")){ //如果选中微信来源
				   from_wx = ',1,';
			   }else{
				   from_wx = '';
			   }
		   }

		   if($(this).val() == 2){ //如果点击支付宝来源
			   if($(this).prop("checked")){ //如果选中支付宝来源
				   from_alipay = ',2,';
			   }else{
				   from_alipay = '';
			   }
		   }

		   if($(this).val() == 4){ //如果点击其他来源
			   if($(this).prop("checked")){ //如果选中其他来源
				   from_other = ',4,';
			   }else{
				   from_other = '';
			   }
		   }
	   });
	   sendAjax(from_alipay,from_wx,from_other,sex_male,sex_femal,sex_other,userGradeArr,regist_time,$('#addressHide').val());
	   //alert('男:'+sex_male+' 女:'+sex_femal+' 未知:'+sex_other+' 微信:'+from_wx+' 支付宝来源:'+from_alipay+' 其他来源:'+from_other);
   });

   //性别复选框点击事件
   $('input[name=sex\\[\\]]').click(function(){
	   $('input[name=sex\\[\\]]').each(function(){
		   if($(this).val() == 1){ //性别是男
			   if($(this).prop("checked")){ //勾选了男
				   sex_male = 1;
			   }else{
				   sex_male = '';
			   }
		   }

		   if($(this).val() == 2){ //性别是女
			   if($(this).prop("checked")){ //勾选了女
				   sex_femal = 2;
			   }else{
				   sex_femal = '';
			   }
		   }

		   if($(this).val() == 3){ //性别是未知
			   if($(this).prop("checked")){ //勾选了未知
				   sex_other = 3;
			   }else{
				   sex_other = '';
			   }
		   }
	   });
	   sendAjax(from_alipay,from_wx,from_other,sex_male,sex_femal,sex_other,userGradeArr,regist_time,$('#addressHide').val());
	   //alert('男:'+sex_male+' 女:'+sex_femal+' 未知:'+sex_other+' 微信:'+from_wx+' 支付宝来源:'+from_alipay+' 其他来源:'+from_other);
   });

   //会员等级复选框点击事件
   $('input[name=userGrade\\[\\]]').click(function(){
	   var j = 0; 
	   $('input[name=userGrade\\[\\]]').each(function(){
		   if($(this).prop("checked")){
			   userGradeArr[j] = ($(this).val()).toString();
			   j ++ ;			  
		   }else{
			   if(userGradeArr.length > 0){
			       removeVal(userGradeArr,$(this).val());
			   }
		   }
		}); 
	    //console.log(userGradeArr);
		userGradeArr =  unique(userGradeArr);
		sendAjax(from_alipay,from_wx,from_other,sex_male,sex_femal,sex_other,userGradeArr,regist_time,$('#addressHide').val());
// 		for(var k=0;k<userGradeArr.length;k++){alert(userGradeArr[k])
// 			// console.log(userGradeArr[k]);
// 			userGradeStr = userGradeStr + userGradeArr[k] +',';
// 		}console.log(userGradeStr);
	    //console.log(userGradeArr);
   });

      //注册时间
      //选中日期的触发事件
	  $('input[name="Time"]').on('apply.daterangepicker',function(ev,picker){
		  regist_time = $('input[name="Time"]').val();
		  sendAjax(from_alipay,from_wx,from_other,sex_male,sex_femal,sex_other,userGradeArr,regist_time,$('#addressHide').val());
      });
      //清空日期触发事件
	  $('input[name="Time"]').on('clean.daterangepicker',function(ev,picker){
		  regist_time = '';
		  sendAjax(from_alipay,from_wx,from_other,sex_male,sex_femal,sex_other,userGradeArr,regist_time,$('#addressHide').val());
      });

	  //年龄 
// 	  $("#text_age").click(function(){
// 			$(".age_select_dropdown").toggle();
// 	  });

// 		var _total="";
// 		$(".age_select_dropdown li").click(function(){
// 			$(".age_select_dropdown").hide();
// 			$(this).each(function(){
// 				var _value=$(this).html()+',';
// 				_total+=_value;
// 				$("#text_age").val(_total);
// 			})
// 			//alert($('#text_age').val());
// 			age = $('#text_age').val();
// 		});

   function sendAjax(from_alipay,from_wx,from_other,sex_male,sex_femal,sex_other,userGradeArr,regist_time,addressHideval)
   {	//JSON.stringify(data)  
	   $.ajax({
		   url : '<?php echo Yii::app()->createUrl('mCenter/user/searchUser'); ?>',
		   data : {from_alipay:from_alipay,from_wx:from_wx,from_other:from_other,sex_male:sex_male,sex_femal:sex_femal,sex_other:sex_other,userGradeArr: userGradeArr,regist_time:regist_time,addressHideval:addressHideval},
		   type : 'post',
		   success:function(data){
			  // alert(data)
				  var data = jQuery.parseJSON(data);
				  $('.total').html(data.count);
				  $('.percent').html(data.percentage+'%');
		   }
		   
	   });
   }
   
   //去掉数组重复的元素
   function unique(arr) {
       var result = [], hash = {};
       for (var i = 0, elem; (elem = arr[i]) != null; i++) {
           if (!hash[elem]) {
               result.push(elem);
               hash[elem] = true;
           }
       }
       return result;
   }
   
   //删除数组元素
   function removeVal(arr,val)
   {
	   for(var i=0;i<arr.length;i++){
		   if(arr[i] == val){
			   arr.remove(i);
		   }
	   }
	   //console.log(arr);
	   return arr;
   }
   /** 
    *删除数组指定下标或指定对象 
    */ 
    Array.prototype.remove=function(obj){ 
    for(var i =0;i <this.length;i++){ 
      var temp = this[i]; 
      if(!isNaN(obj)){ 
       temp=i; 
      } 
      if(temp == obj){ 
       for(var j = i;j <this.length;j++){ 
        this[j]=this[j+1]; 
       } 
        this.length = this.length-1;
     } 
     } 
    } 

    /***********************************选择地址**************************************/
	//选择省 
   	$('#province').change(function(){
		var code = $(this).val();
		$('#city').empty();
		$('#city').append('<option value="">-请选择城市-</option>');
		$('#area').empty();
		$('#area').append('<option value="">-请选择-</option>');
		$.ajax({
            url: '<?php echo(Yii::app()->createUrl('mCenter/Store/GetCity'));?>',
            data: {code:code},
            type: 'post',
            dataType: 'json',
            success: function (data) {
              	for(var i=0 ; i<data.length;i++){
                  	var code = data[i].split(",")[1];
              		var name = data[i].split(",")[0];
                  	var text = '<option value="'+code+'">'+name+'</option>';
                	$('#city').append(text);
             	}
            }
        });
        return false;
 	});

	  //选择市
   	$('#city').change(function(){
		var code = $(this).val();
		$('#area').empty();
		$('#area').append('<option value="">-请选择城市-</option>');
		$.ajax({
            url: '<?php echo(Yii::app()->createUrl('mCenter/Store/GetArea'));?>',
            data: {code:code},
            type: 'post',
            dataType: 'json',
            success: function (data) {
              	for(var i=0 ; i<data.length;i++){
                  	var code = data[i].split(",")[1];
              		var name = data[i].split(",")[0];
                  	var text = '<option value="'+code+'">'+name+'</option>';
                	$('#area').append(text);
             	}
            }
        });
		return false;
 	});

 	$("#area").change(function() {
 		var pca = $("#province").find("option:selected").text() +','+ $("#city").find("option:selected").text() +','+ $("#area").find("option:selected").text();
      	$("#Store_detail_address").val(pca);
 	});

 	//添加省市
 	$('#addBt').click(function(){
 	 	var addressArr = new Array();
 	 	var provinceCode = '';
 	 	var cityCode = '';
 	 	var addressHideStr = $('#addressHide').val();
 	 	if($('#province').val() == ''){
 	 	 	alert('请选择省份');
 	 	 	return false;
 	 	}else{
 	 		provinceCode = $('#province').val();
 	 		cityCode = $('#city').val();
 	 		$.ajax({
 	 	 		url : '<?php echo Yii::app()->createUrl('mCenter/user/addAddress'); ?>',
 	 	 		data : {provinceCode : provinceCode,cityCode : cityCode},
 	 	 		type : 'post',
 	 	 		success : function(data){
 	 	 	 		var data = jQuery.parseJSON(data);
 	 	 	 		//alert(data.address);
 	 	 	 		$('.selected_city').append('<span class="city">'+'<i onclick="delItem(this,'+data.code+')">×</i>'+data.address+'</span>');
 	 	 	 		//重新初始化
 	 	 	 	 	$('#province').val('');
 	 	 	 	 	$('#city').val('');
 	 	 	 	 	
 	 	 	 	 	addressHideStr = addressHideStr + data.code + '-';
 	 	 	 	 	$('#addressHide').val(addressHideStr);
 	 	 	 	 	
 	 	 	 	 	//console.log($('#addressHide').val());
 	 	 	 	 	sendAjax(from_alipay,from_wx,from_other,sex_male,sex_femal,sex_other,userGradeArr,regist_time,$('#addressHide').val());
 	 	 	 	}
 	 	 	});
 	 	 	return false;
 	 	}
 	});
 	
 	//删除所选地址
//  	$('.selected_city').delegate('i','click',function(){
//  		//$(this).parent().remove();
 		
//  	});
 	function delItem(obj,code){
 		$(obj).parent().remove();
 		var addressHideStr = $('#addressHide').val();
 		//console.log($('#addressHide').val());
 		var codearr = addressHideStr.split("-");
 		codearr.pop();// 移除最後一元素(最后一个元素为-)
 		for(var i=0;i<codearr.length;i++){
 			if(codearr[i] == code){
 				codearr.remove(i);
 			}
 		}
 		var str = '';
 		for(var i=0;i<codearr.length;i++){
 			console.log('he:'+codearr[i]);
 			str = str + codearr[i]+'-';
 		}
 		$('#addressHide').val(str);
 		sendAjax(from_alipay,from_wx,from_other,sex_male,sex_femal,sex_other,userGradeArr,regist_time,$('#addressHide').val());
 		//console.log($('#addressHide').val());
 	}

 	//表单操作
 	$('#submitBt').click(function(){
 	 	if($('#name').val() == ''){
 	 	 	$('.error1').remove();
 	 	 	$('#name').after('<span class="error1">'+'分组名必填'+'</span>');
 	 	 	//alert('分组名必填');
 	 	 	return false;
 	 	}else{
 	 	 	$.ajax({
 	 	 	 	url : '<?php echo Yii::app()->createUrl('mCenter/user/checkUserGroupName'); ?>',
 	 	 	 	data : {name:$('#name').val()},
 	 	 	    type : 'post',
 	 	 	    success : function(data){
 	 	 	 	    if(data == 1){
 	 	 	 	 	    $('.error1').remove();
 	 	 	 	 	 	$('#name').after('<span class="error1">'+'分组名已存在'+'</span>');
 	 	 	 	 	    //alert('分组名已存在');
 	 	 	 	 	 	return false;
 	 	 	 	    }else{
 	 	 	 	 	    $('#addForm').submit();
 	 	 	 	    }
 	 	 	 	}
 	 	 	});
 	 	}
 	 	
 	});
</script>
