<div class="kkfm_r_inner freightAdd">
    <!--搜索框 count-top包含的部分-->
    <div class="top">
        <div class="name">运费模板</div>
    </div>
    <div class="shop_menu">
    	<ul>
        	<li><a href="<?php echo Yii::app() -> createUrl('mCenter/shopMallHome/setShopMall');?>">商城首页</a></li>
        	<li class="cur"><a href="#">运费模板</a></li>
        </ul>
    </div>
    <div class="contant freightMode">
    	<div class="cz"><a href="<?php echo Yii::app()->createUrl('mCenter/ShopFreight/AddFreight');?>" class="btn_com_blue">添加运费模板</a></div>
        <?php if(!empty($data)&&isset($data)){?>
        <?php foreach($data as $key=>$value){?>
        <div class="recharge" id="datasum<?php echo $key;?>">
            <div  id="stretch" class="title clearfix">
                <div class="l"><?php echo $value['name'];?><span>（已有<?php echo $value['products'];?>个商品使用该模板）</span></div>
                <div class="r">
                    <span>最后编辑时间：<?php echo $value['last_time'];?></span>
                    <a href="<?php echo Yii::app()->createUrl('mCenter/ShopFreight/CopyFreight',array('id'=>$value['id']));?>" id="copy" class="link1">复制</a>
                    <a href="<?php echo Yii::app()->createUrl('mCenter/ShopFreight/UpdateFreight',array('id'=>$value['id']));?>" id="edit" class="link2">编辑</a>
                    <a href="<?php echo Yii::app()->createUrl('mCenter/ShopFreight/DeleteFreight',array('id'=>$value['id']));?>" id="delete" class="link3">删除</a>
                    <input type="text" style="display: none" id="freight_id" value="<?php echo $value['id']?>">
                    <a href="javascript:;" id="draw" class="link4"><em></em></a>
                </div>
            </div>
            <input id="subfreightsum" style="display: none" type="text" value="<?php echo count($value['subfreight']);?>" />
            <span class="text">
            <table width="100%" cellspacing="0" cellpadding="0" id="tab<?php echo $key;?>" class="tab">
                <tr id='show' class="order-title" >
                    <td width="40%">可配送至</td>
                    <td>首件（个）</td>
                    <td>运费（元）</td>
                    <td>续件（个）</td>
                    <td>续费（元）</td>
                </tr>
                <?php if(isset($value['subfreight'])&&!empty($value['subfreight'])){?>
                <?php foreach($value['subfreight'] as $k=>$v){?>

                <tr  id='show' class="tr<?php echo $k;?>">
                    <!--存放读取的地区-->
                    <td style="display: none">
                        <input id="readarea<?php echo $k;?>" value="<?php echo $v['area'];?>" style="display: none">
                    </td>
                    <!--存放读取的地区-->
                    <td class="area"></td>

                    <td><?php echo $v['first_num']?></td>
                    <td><?php echo $v['first_freight']?></td>
                    <td><?php echo $v['second_num']?></td>
                    <td><?php echo $v['second_freight']?></td>
                </tr>
                <?php }?>
            <?php }?>

            </table>
                </span>
        </div>

        <?php }?>
                <table  width="100%" cellspacing="0" style="margin-bottom: 10px;" cellpadding="0">
                    <tr style="border:none">
                   	<td style="border:none;text-align:right" colspan="5">
            		<div id="page">
                <?php $this -> widget('CLinkPager',array(
                        'pages'=>$pages,
                        'header'=>'共&nbsp;<span class="yellow">'.$pages -> getItemCount().'</span>&nbsp;条&nbsp;',
                        'prevPageLabel' => '上一页',
                        'nextPageLabel'=>'下一页',
                        'firstPageCssClass' => '',  
                        'lastPageCssClass' => '', 
                        'firstPageLabel' => '首页',    
                        'lastPageLabel' => '末页',
                        'maxButtonCount'=>8
                ));?>
            		</div>
            		</td>
                    </tr>
                </table>
        <?php }?>
    </div>
</div>
<div class="popWrap" id="pop" style="width:650px; top: 3%; left: 180px; display:none">
    <div class="pop_con popFeight">
        <div class="title">选择可配送区域</div>
        <div class="pfArea">
            <dl class="dl01">
                <dt>可选省、市、区</dt>
                <dd id="area1">

                </dd>
            </dl>
            <div class="add"><a href="#">添加</a></div>
            <dl class="dl02">
                <dt>已选省、市、区</dt>
                <dd id="area2">

                </dd>
            </dl>

            <div class="btn">
                <a href="#" class="btn_com_blue" id="submit">确认</a>
                <a href="#" class="btn_com_gray" onclick="dismiss()">取消</a>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    var datanum=<?php echo count($data);?>;//模板数
        //var area;
    //读取城市xml
    $(document).ready(function(){
        $.ajax({
            url: "<?php echo GJ_STATIC_JS;?>city.xml",
            type: "GET",
            async:false,
            dataType: "xml",
            success: function (data) {
                $(data).find('province').each(function () {
                    //读取省份
                    var province = $(this);
                    //province.attr("name");省份名称
                    // province.attr("postcode");省份代码
                    var ul1 = "<ul id='ula' class='"+province.attr("postcode")+"'><li id='lia' class='"+province.attr("postcode")+"'><a href='#' id='a'><span class='icon'>+</span><span id='province'>" + province.attr("name") + "</span></a><ul id='ulaa' style='display: none'>";
                    var ul2 = "<ul id='ula'  class='"+province.attr("postcode")+"'><li id='lia' class='"+province.attr("postcode")+"'><a href='#'  style='display: none' id='a'><span class='icon'>+</span><span class='del' id='delprovince'>×</span><span id='province'>" + province.attr("name") + "</span></a><ul id='ulaa' style='display: none'>";
                    $(this).find('city').each(function () {
                        //读取城市
                        var city = $(this);
                        //city.attr("name"));城市名称
                        // city.attr("postcode");城市代码
                        var cityul1 = "<li id='liaa' class='"+city.attr("postcode")+"'><a href='#' id='aa'><span class='icon'>+</span><span id='city'>" + city.attr("name") + "</span></a><ul id='ulaaa' style='display:none'>";
                        var cityul2 = "<li id='liaa' class='"+city.attr("postcode")+"'><a href='#'  style='display: none' id='aa'><span class='icon'>+</span><span class='del' id='delcity'>×</span><span id='city'>" + city.attr("name") + "</span></a><ul id='ulaaa' style='display:none'>";
                        $(this).find('area').each(function () {
                            //读取地区
                            var area = $(this);
                            var areaul1 = "<li id='liaaa' class='"+area.attr("postcode")+"'><a href='#' id='aaa'><span id='area'>" + area.attr('name') + "</span></a></li>";
                            var areaul2 = "<li id='liaaa' class='"+area.attr("postcode")+"'><a href='#'  style='display: none' id='aaa'><span class='del' id='delarea'>×</span><span id='area'>" + area.attr('name') + "</span></a></li>";
                            cityul1 += areaul1;
                            cityul2 += areaul2;
                        });
                        var cityend1 = "</ul></li>";
                        cityul1+=cityend1;
                        cityul2+=cityend1;
                        ul1 += cityul1;
                        ul2 += cityul2;
                    });
                    var end1 = "</ul></li></ul>";
                    ul1 += end1;
                    ul2+=end1;
                    $('#area1').append(ul1);
                    $('#area2').append(ul2);
                });

            }

        });
        //将数据库读出的地区存放到数组里面
        for(var data=0;data<datanum;data++) {
            var provinceStr=new Array();
            var cityStr=new Array();
            var areaStr=new Array();
            var num=$(document).find('#datasum'+data).find('#subfreightsum').val();
            for (var i = 0; i < num; i++) {

                //省#市$区
                var aProvince = new Array();
                aProvince[0] = new Array();//省
                aProvince[1] = new Array();//市
                aProvince[2] = new Array();//区
                var areaCode =$(document).find('#tab'+data).find('.tr'+i).find('#readarea'+i).val();
                var provinceS = areaCode.split('#');
                provinceStr[i] = provinceS[0];
                var cityS = provinceS[1].split('$');
                cityStr[i] = cityS[0];
                areaStr[i] = cityS[1];
                var length = 0;
                //地区代码前两位是省，3.4位是市，后两位是地区，省后4位为0，市后两位为0
                var areaProvince = provinceStr[i].split("|");//存储的省
                var areaCity = cityStr[i].split("|");//存储的市
                var areaArea = areaStr[i].split("|");//存储的区
                var area1 = $(document).find('#area1');
                if (areaProvince[0] != "undefined") {
                    for (var jp = 0; jp < areaProvince.length; jp++) {
                        //把同一个省的都划分到aProvince【】里面
                        aProvince[0][length] = areaProvince[jp];
                        aProvince[1][length] = "undefined";
                        aProvince[2][length] = "undefined";
                        length++;
                    }
                }

                if (areaCity[0] != "undefined") {

                    for (var cp = 0; cp < areaCity.length; cp++) {
                        var flagcity = true;
                        //判断这个市是否在已经存在的省里面
                        for (var cpp = 0; cpp < length; cpp++) {
                            if (aProvince[0][cpp] != "undefined" && areaCity[cp] != "undefined") {
                                //如果省相同
                                if (aProvince[0][cpp].substring(0, 2) == areaCity[cp].substring(0, 2)) {
                                    aProvince[1][cpp] = aProvince[1][cpp] + "|" + areaCity[cp];
                                    aProvince[2][cpp] = "undefined";
                                    flagcity = false;
                                }
                            }
                        }
                        if (flagcity && areaCity[cp] != "undefined") {
                            //省不同,添加省和市
                            aProvince[0][length] = areaCity[cp].substring(0, 2) + "0000";
                            aProvince[1][length] = areaCity[cp];
                            aProvince[2][length] = "undefined";
                            length++;
                        }
                    }
                }
                if (areaArea[0] != "undefined") {
                    for (var ap = 0; ap < areaArea.length; ap++) {
                        //判断地区是不是在已存在的省里面
                        var flagarea = true;
                        for (var ttt = 0; ttt < length; ttt++) {
                            var provinceHead = aProvince[0][ttt].substring(0, 2);
                            var areaHead = areaArea[ap].substring(0, 2);
                            //在已存在的省里面
                            if (provinceHead == areaHead&&areaArea[ap]!="undefined") {
                                //这个区在已存在的省里面
                                //市
                                if (aProvince[1][ttt] == "undefined") {
                                    //这个省的市没有,直接添加市
                                    aProvince[1][ttt] = areaArea[ap].substring(0, 4) + "00";
                                    aProvince[2][ttt] = areaArea[ap];
                                }
                                else {
                                    //如果已经这个省已经有市区存在，需要检验，当前区的市是否已经存在，
                                    //如果市不存在，添加市，如果存在，不添加，再判断当前市里面区是否重复
                                    var areafour=areaArea[ap].substring(0, 4);//区前四位
                                    var areafourflag=false;//判断市是否已经存在，存在为true
                                    var arrayCi=aProvince[1][ttt].split("|");
                                    for(var areafournum=0;areafournum<arrayCi.length;areafournum++)
                                    {
                                        if(areafour==arrayCi[areafournum].substring(0, 4))
                                        {
                                            //市区已经存在
                                            areafourflag=true;
                                        }
                                    }


                                    if(areafourflag)
                                    {
                                        //市区已经存在，只添加区
                                        //判断区是否重复

                                        if(aProvince[2][ttt]!="undefined")
                                        {
                                            //区不为空
                                            aProvince[2][ttt] = aProvince[2][ttt]+"|"+areaArea[ap];
                                        }
                                        else
                                            aProvince[2][ttt] = areaArea[ap];
                                    }
                                    else
                                    {
                                        //不存在
                                        aProvince[1][ttt] =aProvince[1][ttt]+"|"+areaArea[ap].substring(0, 4) + "00";
                                        if(aProvince[2][ttt]!="undefined")
                                        {
                                            //区不为空
                                            aProvince[2][ttt] = aProvince[2][ttt]+"|"+areaArea[ap];
                                        }
                                        else
                                            aProvince[2][ttt] = areaArea[ap];
                                    }
                                }
                                flagarea = false;
                            }
                        }
                        if (flagarea && areaArea[ap] != "undefined") {
                            //不在已存在的省里面，添加省市
                            aProvince[0][length] = areaArea[ap].substring(0, 2) + "0000";
                            aProvince[1][length] = areaArea[ap].substring(0, 4) + "00";
                            aProvince[2][length] = areaArea[ap];
                            length++;
                        }
                    }
                }
                //将具体省市区显示在页面上
                var h = "<ul>";
                for (var pp = 0; pp < aProvince[0].length; pp++) {
                    if (aProvince[0][pp] != "undefined") {
                        var html = "<li>" + area1.find('li.' + aProvince[0][pp]).find('#province').text();
                        //市
                        if (aProvince[1][pp] != "undefined") {
                            var cct = aProvince[1][pp].split("|");

                            for (var yy = 0; yy < cct.length; yy++) {
                                //获取这个城市的前四位
                                var city4=cct[yy].substring(0,4);
                                html = html + "(";
                                html = html + area1.find('li.' + cct[yy]).find('#city').text();
                                //区
                                if (aProvince[2][pp] != "undefined") {
                                    //判断这个区是否属于上面这个城市
                                    html = html + "<span>";
                                    var aat = aProvince[2][pp].split("|");
                                    for (var uu = 0; uu < aat.length; uu++) {
                                        //获取地区前四位与城市匹配
                                        var area4=aat[uu].substring(0,4);
                                        if(city4==area4)
                                            html = html + area1.find('li.' + aat[uu]).find('#area').text() + " ";
                                    }
                                    html = html + "</span>";
                                }
                                html = html + ")";
                            }
                        }
                        html = html + "</li>";
                        h = h + html;
                    }
                }
                h = h + "</ul>";
                $('#tab'+data).find('.tr'+i).find('td').eq(1).html(h);
            }
        }
    });

    $(document).on('click','#stretch',function(e){
            $(this).parent().find('.tab').find('tr').each(function (e) {
                var id=$(this).attr('id');
                if(id=='show')
                {
                    $(this).attr('style','display:none');
                    $(this).attr('id','hide');
                }
                else if(id=='hide')
                {
                    $(this).attr('style','');
                    $(this).attr('id','show');
                }
            });
    });
    $(document).on('click','#copy',function(e){
        e.stopPropagation();
      if(!confirm('确认复制'))
      {
          return false;
      }

    });
    $(document).on('click','#delete',function(e){
        e.stopPropagation();
        if(!confirm('确认删除'))
        {
            return false;
        }else
        {
            //ajax判断该运费模板是否正在被使用
            var id=$(this).next().val();
            var flag=false;
            $.ajax({
                url:'<?php echo Yii::app()->createUrl('mCenter/ShopFreight/FreightIsUsing')?>',
                type:'GET',
                data:{id:id},
                dataType:'json',
                async:false,
                success:function(data)
                {
                    if(data=='success')
                    {
                        flag=true;
                    }else
                    {
                        flag=false;
                    }
                }
            });
            if(flag)
                return true;
            else {
                alert('该运费模板正在被使用,不能被删除!');
                return false;
            }

        }

    });
    $(document).on('click','#edit',function(e){
       e.stopPropagation();
    });
</script>