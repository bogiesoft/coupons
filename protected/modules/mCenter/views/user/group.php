<script src="<?php echo GJ_STATIC_JS."gj.js"?>"></script>
<div class="kkfm_r_inner userManagement">
	<div class="top">
    	<div class="name">会员管理</div>
    </div>
     
	<div class="contant">
		<?php echo CHtml::beginForm(Yii::app()->createUrl($this->route), 'get', array('id' => 'search_form'))?>
    	<div class="sh-search clearfix">
        	<input type="text" placeholder="请输入会员手机号" class="txt" name="keyword" value="<?php echo isset($_GET['keyword'])?$_GET['keyword']:'';?>">
        	<input type="submit" class="search" value=""> 
        </div>
        <?php echo CHtml::endForm()?>
        <dl class="crumb">
        	<dt>当前分组：</dt>
            <dd><?php echo $group_name?></dd>
        </dl>
    	<div class="card-left">
    		<?php echo CHtml::beginForm(Yii::app()->createUrl($this->route), 'get', array('id' => 'list_form'))?>
    			<input type="hidden" name="group" value="<?php echo isset($_GET['group'])?$_GET['group']:'all-'.USER_GROUP_ALL;?>">
    			<input type="hidden" name="client" value="<?php echo isset($_GET['client'])?$_GET['client']:'';?>" id="client">
    			<input type="hidden" name="user" value="" id="user">
    			<input type="hidden" name="operation" value="" id="operation">
    			<input type="hidden" name="target" value="" id="target">
    		<?php echo CHtml::endForm()?>
        	<div class="userList">
            	<div class="userListHD clearfix">
                	<div class="userListHDL">
                    	<span class="check">
                    		<input type="checkbox" id="checkall">全选
                        </span>
                        <span class="check">
                            <?php echo CHtml::dropDownList('add', '', $add, array('prompt' => '添加到'))?>
                        </span>
                        <span class="check">
                            <?php echo CHtml::dropDownList('move', '', $move, array('prompt' => '移动到'))?>
                        </span>
                    </div>
                </div>
                	<div class="userListHD01 clearfix">
	                	<div class="userListHDL">
	                    	<span class="check">来源筛选</span>
	                        <span class="check">
	                            <input type="checkbox" id="alipay" value="<?php echo USER_FROM_ALIPAY?>" <?php if(isset($_GET['client']))echo strstr($_GET['client'], ','.USER_FROM_ALIPAY.',')? 'checked':''?>> 服务窗
	                        </span>
	                        <span class="check">
	                            <input type="checkbox" id="wechat" value="<?php echo USER_FROM_WECHAT?>" <?php if(isset($_GET['client']))echo strstr($_GET['client'], ','.USER_FROM_WECHAT.',')? 'checked':''?>> 公众号
	                        </span>
	                    </div>
	                </div>
                
                <?php if (Yii::app()->user->hasFlash('error')) { ?>
                	<script>alert('<?php echo Yii::app()->user->getFlash('error')?>')</script>
                <?php }?>
                <div class="userListBD clearfix">
                	<div class="classify">
                		<?php if (!empty($list)) {
                			foreach ($list as $k => $v) { ?>
	                		<div class="list clearfix">
	                            <span class="check"><input type="checkbox" value="<?php echo $v['id'] ?>"></span>
	                            <span class="img"><img src="<?php echo empty($v['avatar'])?GJ_STATIC_IMAGES.'headIcon.jpg':$v['avatar']?>"></span>
	                            <span class="name">
	                            	<p class="p01"><?php echo $v['account']?></p>
	                                <p><?php echo $v['name']?><em>(<?php echo $v['grade_name']?>)</em></p>
	                            </span>
	                            <span class="info">
	                                <a href="<?php echo Yii::app()->createUrl('mCenter/user/userDetails', array('id' => $v['id']))?>">详情</a>
	                            </span>
	                        </div>
                		<?php }
                		}else {?>
                			<div class="list clearfix" style="text-align: center">未找到相关会员信息</div>
                		<?php }?>
                    </div>
                </div>
            </div>
            <div class="page">
	            <?php $this -> widget('CLinkPager',array(
					'pages'=>$pages,
					'header'=>'共&nbsp;<span class="yellow">'.$pages -> getItemCount().'</span>&nbsp;条&nbsp;',
					'prevPageLabel' => '上一页',
					'nextPageLabel'=>'下一页',
					'maxButtonCount'=>8
				));?>
            </div>
		</div>
        <div class="card-right">
        	<div class="setion">
            	<div class="title"><a href="javascript:;" class="createGroup">新建分组</a></div>
                <ul>
                	<?php foreach ($group_list as $k => $v) {
                		$arr = explode("-", $v['id']);
                		if ($arr[0] == 'all' || $arr[0] == 'default' || $arr[0] == 'grade') { ?>
		                	<li style="cursor:pointer" value="<?php echo $v['id']?>">
		                        <span><strong><?php echo $v['name']?></strong><em>(<?php echo $v['num']?>)</em></span>
		                    </li>
                		<?php }else { ?>
		                	<li style="cursor:pointer" value="<?php echo $v['id']?>">
		                    	<div class="op">
		                        	<a href="javascript:;" class="modify"><img src="<?php echo GJ_STATIC_IMAGES?>mod.png"></a>
		                            <a href="javascript:;" class="del"><img src="<?php echo GJ_STATIC_IMAGES?>del.png"></a>
		                        </div>
		                        <span><strong><?php echo $v['name']?></strong><em>(<?php echo $v['num']?>)</em></span>
		                        <input type="text" class="txt editText" value="<?php echo $v['name']?>" style="display:none">
		                    </li>
                		<?php }
                	}?>
                </ul>
            </div>
            <script>
            	//设置选中效果
            	$(function() {
                	//设置选中的分组
                	var group = $("#list_form input[name=group]").val();
                	var from = $("#list_form input[name=from]").val();
                	$(".card-right .setion li").each(function() {
                    	if($(this).attr("value") == group) {
                    		$(this).css('background-color', '#f5f5f5');
                    		return false;
                    	}
                	});
//                 	$(this).siblings().css('background-color', '');
// 					$(this).css('background-color', '#f5f5f5');
            	});
            	//点击新建分组，创建li的内容并加入到ul的最后一个元素里
				var createGroupName = '<li style="cursor:pointer;display:none" value="0"> <div class="op"> <a href="javascript:;" class="modify"><img src="<?php echo GJ_STATIC_IMAGES?>mod.png"></a> <a href="javascript:;" class="del"><img src="<?php echo GJ_STATIC_IMAGES?>del.png"></a> </div> <span style="display:none"><strong></strong><em></em></span> <input type="text" class="txt editText" value="新建分组"></li>';
				$(".createGroup").click(function(e) {
					//把要创建的东西放入到父级的下一个兄弟节点里
                    $(this).parent().next().append(createGroupName);
                    $(this).parent().next().find("li:eq(-1)").slideDown(function() {
                    	//input获得焦点值选中
    					$(this).find("input").select();	
    					parent.callParAutoResize('main', 0);//高度适配
                    });
                    window.parent.callParAutoResize("main",$("body").height());
                });
				//点击del 则删除父级的li
				$(document).on('click','.del', function(e){
					e.stopPropagation(); //停止分派，为了保证不触发父节点的选中事件
					if(!confirm('删除分组将会把该组已有成员全部移动至未分组里。是否确定删除？')) {
						return false;
					}
					var obj = $(this);
					var id = obj.closest("li").attr("value");
					deleteCustomGroup(id, function(result) {
						//删除结果
						if(result.error == 'success') { //删除成功
							obj.parents("li").slideUp(function() {
								$(this).remove();
								parent.callParAutoResize('main', 0);//高度适配
							});
							//更新未分组的人数
							if(!!result.count) {
								obj.closest("ul").find("li:eq(1) em").html("("+result.count+")");
							}
						}else { //删除失败
							alert(result.errMsg);
						}
					});
				})
				//点击modify则把父级的兄弟节点span隐藏掉,并取得span的文字如“普通会员”，把input显示出来然后把span里的文字放入到input的value里
				$(document).on('click','.modify', function(e){
					e.stopPropagation(); //停止分派，为了保证不触发父节点的选中事件
					var spanText = $(this).parent().next("span").children("strong").text();
					$(this).parent().next("span").hide();
					$(this).parent().siblings("input").val(spanText).show();
					$(this).parent().siblings("input").select();
				})
				//修改好文字后，当失去焦点则input隐藏并把修改后的文字赋给兄弟节点span里的strong并显示span
				$(document).on('blur','.editText', function(){
					var obj = $(this);
					var id = obj.closest("li").attr("value");
					var newName = obj.val();
					//修改前的名称
					var oldName = obj.prev().children("strong").html();
					if(newName == oldName&&newName!='') { //未修改
						obj.hide(); //隐藏输入框
						obj.prev().children("strong").html(newName).end().show();
					}else {
//						if(oldName == '') {
//							oldName = newName;
//						}
						//调用编辑方法
						editCustomGroup(id, newName, function(result) {
							//修改结果
							if(result.error == 'success') { //修改成功
								//更新分组id
								obj.closest("li").attr("value", 'custom-'+result.group_id);
								obj.hide(); //隐藏输入框
								obj.prev().children("strong").html(newName).end().show(); //更新名称并显示
							}else { //修改失败
								alert(result.errMsg);
								//删除新建分组
								if(id == '0') {
									obj.parents("li").slideUp(function() {
										$(this).remove();
										parent.callParAutoResize('main', 0);//高度适配
									});
								}else {
									//修改失败则恢复为原来的名称
									obj.hide(); //隐藏输入框
									obj.prev().children("strong").html(oldName).end().show();
								}
							}
						});
					}
				});
				//回车保存
				$(document).on('keypress','.editText', function(e){
					var event = e || window.event;  
					var currentKey = event.charCode || event.keyCode;  //ie、FF下获取键盘码
					if(currentKey == 13) { //回车
						$(this).blur();
					}
				});
				//选择分组获取分组数据
				$(document).on('click', '.card-right .setion li', function() {
					if(!$(".editText").is(":focus")) {
						$("#list_form input[name=group]").val($(this).attr("value"));
						//获取分组的数据
						$("#list_form").submit();
					}
				});

				//ajax调用编辑自定义分组的接口
				function editCustomGroup(id, name, callback) {
					$.ajax({
			            url: '<?php echo(Yii::app()->createUrl('mCenter/user/editCustom'));?>',
			            data: {id: id, name: name},
			            type: 'post',
			            dataType: 'json',
			            success: function (data) {
			            	if(callback) {
								callback(data); //执行回调函数
							}
			            },
			            error: function () {
			            	if(callback) {
								callback({error: 'failure', errMsg: '请求失败'});
							}
			            }
			        });
				}
				//ajax调用删除自定义分组的接口
				function deleteCustomGroup(id, callback) {
					$.ajax({
			            url: '<?php echo(Yii::app()->createUrl('mCenter/user/deleteCustom'));?>',
			            data: {id: id},
			            type: 'post',
			            dataType: 'json',
			            success: function (data) {
			            	if(callback) {
								callback(data); //执行回调函数
							}
			            },
			            error: function () {
			            	if(callback) {
								callback({error: 'failure', errMsg: '请求失败'});
							}
			            }
			        });
				}

				//全选/全不选
				$("#checkall").click(function() {
					$(".classify div .check input").prop("checked", !!$(this).is(":checked"));
				});
				//用户选择
				$(".classify .check input").click(function() {
					var hasChecked = false;
					var hasUnchecked = false;
					//遍历页面用户
					$(".userList .classify div").each(function() {
						var obj = $(this).find(".check input");
						if(obj.is(":checked")) {
							hasChecked = true;
						}else {
							hasUnchecked = true;
						}
					});
					if(hasChecked && !hasUnchecked) { //全部选中
						$("#checkall").prop("checked", true);
						$("#checkall").prop("indeterminate", false);
					}
					if(!hasChecked && hasUnchecked) { //全未选中
						$("#checkall").prop("checked", false);
						$("#checkall").prop("indeterminate", false);
					}
					if(hasChecked && hasUnchecked) { //部分选中
						$("#checkall").prop("indeterminate", true);
					}
				});
				//下拉框触发事件
				$(document).on("change", "select", function(e) {
					if($(this).val() == '') {
						return true;
					}
					//遍历选中会员
					var user = '';
					$(".classify .check input").each(function() {
						if($(this).is(":checked")) {
							if(user == '') {
								user = $(this).val();
							}else {
								user += ',' + $(this).val();
							}
						}
					});
					//未选中任何会员
					if(user == '') {
						alert('请选择会员后再操作');
						$(this).val("");
						return true;
					}
					var type = $(this).attr("id");
					//确认提示
					var tmp = type == 'add' ? '添加' : '移动';
					var msg = '确定'+tmp+'到该分组下?';
					if(!confirm(msg)) {
						$(this).val("");
						return true;
					}
					//保存表单信息
					$("#user").val(user);
					$("#operation").val(type);
					$("#target").val($(this).val());
					//提交表单
					$("#list_form").submit();
				});
				//来源筛选
				$(document).on("click", ".userListHD01 input", function() {
					var client = $("#client").val();
					var arr = client.split(",");
					var tmp = '';
					if($(this).is(":checked")) {
						for(var i in arr) {
							if(arr[i] == '') continue;
							tmp += ","+arr[i] + ",";
						}
						tmp += ","+$(this).val() + ",";
					}else {
						for(var i in arr) {
							if(arr[i] == $(this).val() || arr[i] == '') continue;
							tmp += ","+arr[i] + ",";
						}
					}
					//保存来源信息
					$("#client").val(tmp);
					//提交表单
					$("#list_form").submit();
				});
            </script>
        </div>
   	</div>
	
</div>