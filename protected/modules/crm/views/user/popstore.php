<script type="text/javascript">
    $(document).ready(main_obj.list_init);
</script>
<body class="store">
    <div class="store_pop" id="store_pop">
        <div class="pop_con popStoreSel recent_consume">
            <div class="title">
                <span class="name">最近消费门店</span>
            </div>
            <div class="pop_content">
                <?php echo CHtml::beginForm(Yii::app()->createUrl($this->route), 'get', array('id' => 'search_form'))?>
                    <div class="sh-search clearfix">
                        <span>
                        <?php
                        $this->widget('application.modules.mCenter.components.widgets.SelectorWidget', array(
                            'model' => $pca,
                            'attributeProvince' => 'liveplaceProvince',
                            'attributeCity' => 'liveplaceCity',
                            'attributeArea' => 'liveplaceArea',
                            'htmlOptions' => array('class' => 'operate', 'style' => 'height:30px')
                        ));
                        ?>
                        </span>
                        <input class="txt" placeholder="请输入门店名称" style="width:180px" type="text" name="store_name">
                        <input type="submit" class="search" value="">
                    </div>
                <?php echo CHtml::endForm()?>
                <table width="100%" border="0" cellspacing="0" cellpadding="0" class="tableH">
                    <tbody>
                        <tr class="orderHead">
                            <td width="15">
                                <input type="checkbox" id="all_check" name="chkAll">
                            </td>
                            <td align="left" width="30%">门店名称</td>
                            <td align="right">地址</td>
                        </tr>
                        <?php 
                        if(!empty($stores)){
                            foreach($stores as $store)
                            {
                        ?>
                        <tr>
                            <td>
                                <input type="checkbox" class="store_id" name="store[]" value="<?php echo $store['id']?>,<?php echo $store['name'] ?>" <?php if(isset($_GET['store_id']) && in_array($store['id'], $_GET['store_id'])) echo 'checked'?>>
                            </td>
                            <td align="left">
                                <?php echo empty($store['branch_name']) ? $store['name'] : $store['name']."-".$store['branch_name']?>
                            </td>
                            <td align="right"><?php echo $store['address']?></td>
                        </tr>
                        <?php 
                            }
                        }
                        else {
                            ?>
                            <tr>
                                <td colspan="3" class="empty_td">没有门店信息，<a href="/mCenter/store/addStore">请前往设置</a></td>
                            </tr>
                            <?php
                        }
                        ?>
                    </tbody>
                </table>
                <div class="page2">
                <?php $this -> widget('CLinkPager',array(
                        'pages'=>$pages,
                        'header'=>'共&nbsp;<strong class="orange">'.$pages -> getItemCount().'</strong>&nbsp;条&nbsp;',
                        'prevPageLabel' => '上一页',
                        'nextPageLabel'=>'下一页',
                        'maxButtonCount'=>8
                    ));?>
                </div>
                <div class="btnWrap">
                    <div class="total">已选<span id="check_num"><?php echo count($_GET['store_id'])-1?></span>家，共<?php echo $count?>家</div>
                    <input type="button" value="确认" class="btn_com_blue" id="store_pop_sure_btn">
                    <input type="button" value="取消" class="btn_com_gray" id="store_pop_close_btn">
                </div>
        </div>
    </div>
</body>
<script type="text/javascript">
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
/*
$("#check_all_btn").click(function() {
    $("input.store_id").prop("checked", !!$(this).is(":checked"));
    $("#check_num").html($("input.store_id").length);
});

$("#btn_yes").click(function(){
	var store_name = $("input.store_name");
	var store_names = new Array();
	var store_ids = new Array();
	var i = 0;
	$("input.store_id").each(function(){
		if($(this).is(":checked")) {
			store_ids[i] = $(this).val();
			store_names[i] = store_name[i].value;
			i++;
		}
	});

	var sarea = $("#store_area", window.parent.document);
	$.each(store_ids, function(i,val){
		var str = sarea.html();
	      var appendHtml = '<button type="button" class="tag_btn_default tag_btn_primary btn_area"'
		      +' onclick="this.parentNode.removeChild(this)"><em>'+store_names[i]+'</em> '
		      +'<input type="hidden" name="store_id['+i+']" value="'+val+'">'
		      +'<input type="hidden" name="store_name['+i+']" value="'+store_names[i]+'"></button>';
		  //判重
		  if(str.indexOf('value="'+val+'">') == -1)
		  {
			  sarea.append(appendHtml);
		  }
	  });
});

*/

$(function(){

	var idcontainer		= window.parent.$("#store_ids");
	var namecontainer	= window.parent.$("#store_names");
	var storearea 		= window.parent.$("#store_area");
	var allStore		= $('input.store_id');
	
    function layerClose(){
        var index = parent.layer.getFrameIndex(window.name);
        parent.layer.close(index);
    };

    $("#store_pop_close_btn").click(function(){
       layerClose();
    });

    $("#store_pop_sure_btn").click(function(){
    	storearea.html('');
        var store_ids_str = idcontainer.html();
        var store_names_str = namecontainer.html();
        
        var store_ids = store_ids_str.split(',');
        var store_names = store_names_str.split(',');
        
        store_ids.pop();
        store_names.pop();

		for(var i=0; i<store_ids.length; i++) 
		{
			var appTpl = '<button type="button" class="tag_btn_default tag_btn_primary btn_area"'
            	+' onclick="this.parentNode.removeChild(this)"><em>'+store_names[i]+'</em><input type="hidden"'
            	+' name="store_id[]" value="'+store_ids[i]+'"><input type="hidden" name="store_name[]" value="'
            	+store_names[i]+'"></button>';
        
        	storearea.append(appTpl);
		}
        layerClose();
    });

    $("#all_check").click(function(){
// 		alert('全选');
    	//取消全选
    	if($(this).prop('checked') == false)
			emptySome();
    	else
    	{
    		emptySome();
    		$('input.store_id').prop('checked', true);
//     		$('input.store_id').click();
    		for(var i=0; i<allStore.length; i++)
    		{
        		var idname = allStore[i].value;
        		var arr = idname.split(',');
        		var store_id = arr[0];
        		var store_name = arr[1];
//         		alert('id:'+store_id+',name:'+store_name);
        		idcontainer.append(store_id+',');
        		namecontainer.append(store_name+',');
        		$('#check_num').html(allStore.length);
        	}
    	}
    });

	$("input.store_id").click(function(){

		var cur_arr = $(this).val().split(',');
		var cur_id	= cur_arr[0];
		var cur_name= cur_arr[1];
		
		var store_ids_str = idcontainer.html();
        var store_names_str = namecontainer.html();

        var store_id_arr = store_ids_str.split(',');
		var store_name_arr = store_names_str.split(',');
		
        store_id_arr.pop();
        store_name_arr.pop();
		
		//单个取消
		if($(this).prop('checked') == false)
		{
	        //位置
	        var pos = $.inArray(cur_id, store_id_arr);
			store_id_arr.splice(pos,1);
			store_name_arr.splice(pos,1);

			store_ids_str = store_id_arr.join(',')+',';
			store_names_str = store_name_arr.join(',')+',';
			idcontainer.html(store_ids_str);
			namecontainer.html(store_names_str);
			if($("#all_check").prop("checked")==true)
			{
				$("#all_check").prop("checked", false);
			}
			$('#check_num').html(store_id_arr.length);
		}
		else	
		//单个确定
		{
	        if($.inArray(cur_id, store_id_arr) == -1)
	        {
	        	store_ids_str += cur_id+',';
	        	store_names_str += cur_name+',';
	        	idcontainer.html(store_ids_str);
				namecontainer.html(store_names_str);
				$('#check_num').html(store_id_arr.length+1);
	        }
		}
	});
	
    //置空
	function emptySome()
	{
		idcontainer.html('');
		namecontainer.html('');
		storearea.html('');
		$('input.store_id').prop('checked', false);
		$('#check_num').html('');
	}
})



</script>
