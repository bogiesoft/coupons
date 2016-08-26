<div class="kkfm_r_inner">
        <div class="storeManage">
            <div class="top">
                <div class="name">
                    添加标签
                </div>
            </div>
            <div class="bus-contant new_group_wrap new_label_wrap">
                <div class="btn_l level_r">
                    <input type="submit" value="属性标签" class="btn_com_blue btn_attr_tag">
                    <!--<input type="submit" value="条件标签" class="btn_com_gray btn_term_tag">-->
                </div>
                <div class="new_group new_group_attr">
                    <div class="new_group_item">
                        <?php echo CHtml::beginForm(Yii::app()->createUrl($this->route), 'post', array('id' => 'label_form'))?>
                        <h3 class="first_title">属性标签</h3>
                        <div class="new_group_item_c">
                            <div class="basic_info_item">
                                <span>标签名</span>
                                <input type="text" class="text_group" name="label_name">
                                <span class="red" id="label_name"></span>
                            </div>
                        </div>
                        <div class="new_group_item_c">
                            <div class="basic_info_item">
                                <span>属性值</span>
                                <input type="text" class="text_group" id="attribut">                                
                                <input type="button" class="btn_com_lightblue btn_com_attr" onclick="att()" value="添加">
                                <span class="red" id="error"></span>
                                <div class="attr_name" id="attr_name"></div>
                            </div>
                        </div>
                        <?php echo CHtml::endForm()?>
                    </div>
                </div>
                <div class="new_group_term">
                    <?php echo CHtml::beginForm(Yii::app()->createUrl('mCenter/label/addCondition'), 'post', array('id' => 'condition_form'))?>
                    <div class="new_group">
                        <div class="new_group_item">
                            <h3 class="first_title">条件标签</h3>
                            <div class="new_group_item_c">
                                <div class="basic_info_item">
                                    <span>标签名</span>
                                    <input type="text" class="text_group">
                                </div>
                            </div>
                            <div class="new_group_item_c">
                                <span>条件</span>
                                <button class="btn_com_lightblue">添加</button>
                            </div>
                        </div>
                    </div>
                    <table cellspacing="0" cellpadding="0" width="100%" class="term_table">
                        <tr class="thead">
                            <td width="15%">属性值</td>
                            <td width="70%">条件</td>
                            <td align="center" style="padding-left: 0;">操作</td>
                        </tr>
                        <tr class="tbody">
                            <td valign="top" rowspan="2" class="first_td">
                                <dl class="list">
                                    <dt>
                                        <input type="text" class="text_group text_attr">
                                    </dt>
                                </dl>
                            </td>
                            <td valign="top">
                                <dl class="list">
                                    <dt>消费频次：在时间
                                        <select class="iselect user_select">
                                            <option>30天</option>
                                            <option>30天</option>
                                            <option>30天</option>
                                            <option>30天</option>
                                        </select> 内，
                                        <select class="iselect user_select">
                                            <option>1次</option>
                                            <option>1次</option>
                                            <option>1次</option>
                                            <option>1次</option>
                                            <option>1次</option>
                                        </select> 消费次数
                                        <select class="iselect user_select">
                                            <option>不限</option>
                                            <option>不限</option>
                                            <option>不限</option>
                                            <option>不限</option>
                                        </select>
                                    </dt>
                                </dl>
                            </td>
                            <td align="center" valign="top">
                                <dl class="operate">
                                    <dt>删除</dt>
                                </dl>
                            </td>
                        </tr>
                        <tr class="tbody">
                            <td valign="top">
                                <dl class="list">
                                    <dt>消费频次：在时间
                                        <select class="iselect user_select">
                                            <option>30天</option>
                                            <option>30天</option>
                                            <option>30天</option>
                                            <option>30天</option>
                                        </select> 内，
                                        <select class="iselect user_select">
                                            <option>1次</option>
                                            <option>1次</option>
                                            <option>1次</option>
                                            <option>1次</option>
                                            <option>1次</option>
                                        </select> 消费次数
                                        <select class="iselect user_select">
                                            <option>不限</option>
                                            <option>不限</option>
                                            <option>不限</option>
                                            <option>不限</option>
                                        </select>
                                    </dt>
                                </dl>
                            </td>
                            <td align="center" valign="top">
                                <dl class="operate">
                                    <dt>删除</dt>
                                </dl>
                            </td>
                        </tr>
                        <tr class="tbody">
                            <td valign="top" rowspan="2" class="first_td">
                                <dl class="list">
                                    <dt>
                                        <input type="text" class="text_group text_attr">
                                    </dt>
                                </dl>
                            </td>
                            <td valign="top">
                                <dl class="list">
                                    <dt>消费频次：在时间
                                        <select class="iselect user_select">
                                            <option>30天</option>
                                            <option>30天</option>
                                            <option>30天</option>
                                            <option>30天</option>
                                        </select> 内，
                                        <select class="iselect user_select">
                                            <option>1次</option>
                                            <option>1次</option>
                                            <option>1次</option>
                                            <option>1次</option>
                                            <option>1次</option>
                                        </select> 消费次数
                                        <select class="iselect user_select">
                                            <option>不限</option>
                                            <option>不限</option>
                                            <option>不限</option>
                                            <option>不限</option>
                                        </select>
                                    </dt>
                                </dl>
                            </td>
                            <td align="center" valign="top">
                                <dl class="operate">
                                    <dt>删除</dt>
                                </dl>
                            </td>
                        </tr>
                        <tr class="tbody">
                            <td valign="top">
                                <dl class="list">
                                    <dt>消费频次：在时间
                                        <select class="iselect user_select">
                                            <option>30天</option>
                                            <option>30天</option>
                                            <option>30天</option>
                                            <option>30天</option>
                                        </select> 内，
                                        <select class="iselect user_select">
                                            <option>1次</option>
                                            <option>1次</option>
                                            <option>1次</option>
                                            <option>1次</option>
                                            <option>1次</option>
                                        </select> 消费次数
                                        <select class="iselect user_select">
                                            <option>不限</option>
                                            <option>不限</option>
                                            <option>不限</option>
                                            <option>不限</option>
                                        </select>
                                    </dt>
                                </dl>
                            </td>
                            <td align="center" valign="top">
                                <dl class="operate">
                                    <dt>删除</dt>
                                </dl>
                            </td>
                        </tr>
                    </table>
                    <div class="btn btn_com_attr_add">
                        <button class="btn_com_lightblue">添加属性</button>
                    </div>
                </div>
                <div class="new_group_term">
                    <div class="new_group">
                        <div class="new_group_item">
                            <h3 class="first_title">条件标签</h3>
                            <div class="new_group_item_c">
                                <div class="basic_info_item">
                                    <span>标签名</span>
                                    <input type="text" class="text_group">
                                </div>
                            </div>
                            <div class="new_group_item_c">
                                <span>条件</span>
                                <span>消费能力</span>
                            </div>
                        </div>
                    </div>
                    <table cellspacing="0" cellpadding="0" width="100%" class="term_table">
                        <tr class="thead">
                            <td width="15%">属性值</td>
                            <td width="85%">条件</td>
                        </tr>
                        <tr class="tbody">
                            <td valign="top" class="first_td">
                                <dl class="list">
                                    <dt>高消费</dt>
                                </dl>
                            </td>
                            <td valign="top">
                                <dl class="list">
                                    <dt>消费金额：在时间
                                        <select class="iselect user_select">
                                            <option>30天</option>
                                            <option>30天</option>
                                            <option>30天</option>
                                            <option>30天</option>
                                        </select> 内，
                                        <select class="iselect user_select">
                                            <option>100元</option>
                                            <option>100元</option>
                                            <option>100元</option>
                                            <option>100元</option>
                                            <option>100元</option>
                                        </select> 消费次数
                                        <select class="iselect user_select">
                                            <option>不限</option>
                                            <option>不限</option>
                                            <option>不限</option>
                                            <option>不限</option>
                                        </select>
                                    </dt>
                                </dl>
                            </td>
                        </tr>
                        <tr class="tbody">
                            <td valign="top" class="first_td">
                                <dl class="list">
                                    <dt>低消费</dt>
                                </dl>
                            </td>
                            <td valign="top">
                                <dl class="list">
                                    <dt>消费金额：在时间
                                        <select class="iselect user_select">
                                            <option>30天</option>
                                            <option>30天</option>
                                            <option>30天</option>
                                            <option>30天</option>
                                        </select> 内，
                                        <select class="iselect user_select">
                                            <option>100元</option>
                                            <option>100元</option>
                                            <option>100元</option>
                                            <option>100元</option>
                                            <option>100元</option>
                                        </select> 消费次数
                                        <select class="iselect user_select">
                                            <option>不限</option>
                                            <option>不限</option>
                                            <option>不限</option>
                                            <option>不限</option>
                                        </select>
                                    </dt>
                                </dl>
                            </td>
                        </tr>
                    </table>
                </div>
                <?php echo CHtml::endForm()?>
                <div class="btn level_r" id="level_r">
                    <input type="button" class="btn_com_blue" onclick="label_form()" value="保存">
                </div>
            </div>
        </div>
    </div>
<script>
    $(".btn_attr_tag").click(function() {
		$('.new_group_attr').show(autoResize);
		$('.new_group_term').hide();
                $('#level_r').html('<input type="submit" class="btn_com_blue" onclick="label_form()" value="保存">');
		$(this).addClass("btn_com_blue").removeClass("btn_com_gray").siblings().addClass("btn_com_gray").removeClass("btn_com_blue");
    })
    $(".btn_term_tag").click(function() {
            $('.new_group_term').show(autoResize);
            $('.new_group_attr').hide();
            $('#level_r').html('<input type="submit" class="btn_com_blue" onclick="condition_form()" value="保存">');
            $(this).addClass("btn_com_blue").removeClass("btn_com_gray").siblings().addClass("btn_com_gray").removeClass("btn_com_blue");
    })
    //重设高度
    function autoResize(){
            window.parent.callParAutoResize("main",$("body").height());
    }
    //添加属性值
    function att()
    {        
        if($('#attribut').val() == ''){
            $('#error').html('请填写属性值');
        } else {
            var attr = $('#attribut').val();
            $('#error').html('');
            $('.attr_name').append('<span onclick="del(this)">'+attr+'<i>×</i><input type="hidden" name=values[] value="'+attr+'"></span>');
            $('#attribut').val('');
        }        
    }
    //删除标签
    function del(obj)
    {
        obj.remove();
    }
    $('input[name=label_name]').bind("blur keyup", function(){ 
        if($('input[name=label_name]').val() == ''){
            $('#label_name').html('请填写标签名');
        } else {
            $('#label_name').html('');
        }
    });
    //属性标签表单提交
    function label_form()
    {
        if($('input[name=label_name]').val() == ''){
            $('#label_name').html('请填写标签名');
        }else if($('.attr_name').find('span').length == '0'){
            $('#error').html('属性值至少为一个');
        }else {            
            document.getElementById("label_form").submit();
        }
    }
</script>