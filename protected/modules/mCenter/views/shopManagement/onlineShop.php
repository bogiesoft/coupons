<script type="text/javascript">
	$(document).ready(main_obj.list_init);
</script>
<script src="<?php echo GJ_STATIC_JS."gj.js"?>"></script>
<?php echo CHtml::beginForm('','post',array('id'=>'sform'))?>
<div class="kkfm_r_inner">
       <div class="top">
           <div class="name"><?php echo empty($show['name'])?Yii::app() -> session['merchant_name']:$show['name']?></div>
        </div>
        <div class="shopWrap">
	   		<div class="shop-left">
	            <div class="shop-con">
	            <div class="title1">封面设置</div>
	              <div class="shopName"><input type="text" name="shopname" class="txt" placeholder="商家名称" value="<?php echo empty($show['name'])?Yii::app() -> session['merchant_name']:$show['name']?>"></div>
	              <span class="banner">
	                	<ul>
	                    	<li><img src="<?php echo empty($show['img'])?'':IMG_GJ_LIST.$show['img']?>" id="cover_img"></li>
	                    	<input name="cover" value="" style="display: none">
	                    </ul>
	                </span>
	              <span class="niu">
	              <input type="file" class="upload" id="upload" value="上传照片">
	              <em>图片大小不超过3M，建议尺寸640px × 280px</em>
	              </span>
	             <!--  <span class="xc"><a href="<?php //echo Yii::app() -> createUrl('mCenter/Album/PhotoManagement')?>" class="btn_border">设置相册</a></span> -->
	              <div class="clear"></div>
	              
	              <!--上传logo start-->
	            <div class="logoSet">
	            	<div class="logoSet_l">
	            	   <img src="<?php echo empty($show['logo_img'])?'':IMG_GJ_LIST.$show['logo_img']?>" id="coverlogo_img"></li>
	                    	<input name="coverlogo" value="" style="display: none">
	            	</div>
	                <div class="logoSet_r">
	                	<a href="javascript:void(0)" class="btn_com_gray" id="uploadLogo">上传logo</a>							 					
	                    <div class="remark">图片大小不超过3M，建议尺寸120 × 120px</div>
	                </div>
	            </div>
	            <!--上传logo end-->
	            
	              	<div class="title1">
	                  功能设置
	                 </div>
	                <div class="title1" style="border-bottom:none;margin:0">
	                	<div class="con"><input type="checkbox" id="if_book" name="if_book" onclick="if_book(<?php echo MERCHANT_BOOK_OPEN;?>,<?php echo MERCHANT_BOOK_CLOSE;?>)" value="<?php echo $show['if_book'];?>" <?php echo isset($show['if_book']) && $show['if_book'] == MERCHANT_BOOK_OPEN?'checked':''?>>开启预定功能</div>
	            	</div>
	                <div class="child">
	                	 <div class="classifys">
	                     <div class="con">接受预定的门店<input type="checkbox" id="selectAll">全选</div>
	                    <?php for ($i=0;$i<count($choose)/3;$i++){?>
	                	<span class="one">
	                    	<ul>
	                    	<?php for ($j=0;$j<3;$j++) {?>
	                    	<?php if(isset($choose[$j+$i*3]) && !empty($choose[$j+$i*3])){?>
	                        	<li><input type="checkbox" name="store[<?php echo $j+$i*3?>]" id="store<?php echo $j+$i*3;?>" value="<?php echo $choose[$j+$i*3]['id'] ?>" <?php echo strstr($storeId,','.$choose[$j+$i*3]['id'].',') != false?'checked':'';?>><?php echo empty($choose[$j+$i*3]['branch_name']) ? $choose[$j+$i*3]['name'] : $choose[$j+$i*3]['name']."-".$choose[$j+$i*3]['branch_name']; ?></li>
	                            <?php }?>
	                            <?php }?>
	                        </ul>
	                        <input type="text" name="checkbox_sum" id="checkbox_sum" style="display: none" value="<?php echo count($choose);?>">
	                    </span>
	                    <?php }?>
	                </div>
	              </div>
<!-- 	                 <div class="title"> -->
<!--	                 <div class="con"><input type="checkbox" name="if_hongbao" value="<?php //echo MERCHANT_HONGBAO_OPEN?>" <?php //if(!empty($show['if_hongbao']) && $show['if_hongbao'] == MERCHANT_HONGBAO_OPEN){?>checked<?php //}?>>开启红包领取</div> -->
<!--	                 <div class="cz"><a href="<?php //echo Yii::app()->createUrl('mCenter/coupons/redEnvelopeList') ?>" class="btn_border">管理红包</a></div> -->
<!-- 	            	</div> -->
	                 <div class="title">
	                	<div class="con"><input type="checkbox" name="if_coupons" value="<?php echo MERCHANT_COUPONS_OPEN?>" <?php if(!empty($show['if_coupons']) && $show['if_coupons'] == MERCHANT_COUPONS_OPEN){?>checked<?php }?>>开启优惠券领取</div>
<!--	                <div class="cz"><a href="<?php //echo Yii::app()->createUrl('mCenter/coupons/yhqList') ?>" class="btn_border">管理优惠券</a></div> -->
	            	</div>
	                <div class="sh-name">
	                	<div class="title1" style="border-bottom:none">商家介绍</div>
	                    <span><textarea class="text" name="introduction"><?php if(!empty($show['introduction'])){echo $show['introduction'];}?></textarea></span>
	                </div>
	                <div class="bottom">
	                	<input type="button" onclick="sub()" class="btn_com_blue" value="提交保存">
	                </div>
	          </div>
          </div>
		</div>
</div>
<?php echo CHtml::endForm();?>

<script type="text/javascript">

    $(function(){
        onlineshop_uploadImg('upload','cover','cover_img','<?php echo UPLOAD_TO_PATH?>','<?php echo GJ_STATIC_JS?>','<?php echo UPLOAD_IMG_TYPE;?>','<?php echo IMG_GJ_FOLDER?>','<?php echo IMG_GJ_LIST?>');
        onlineshop_uploadlogo('uploadLogo','coverlogo','coverlogo_img','<?php echo UPLOAD_TO_PATH?>','<?php echo GJ_STATIC_JS?>','<?php echo UPLOAD_IMG_TYPE;?>','<?php echo IMG_GJ_FOLDER?>','<?php echo IMG_GJ_LIST?>');
    });
    $(document).ready(function(e){
        var if_book=$('#if_book').val();
//        alert(if_book);
        if(if_book==<?php echo MERCHANT_BOOK_OPEN;?>)
        {
            //已经开启预订功能，可以选择门店

        }else if(if_book==<?php echo MERCHANT_BOOK_CLOSE;?>)
        {
            //没有开启预订功能，不能选择门店
            //将所有checkbox设置成不可点击 disable=true
            $('#selectAll').attr('disabled','true');
            var checkbox_sum=$('#checkbox_sum').val();
            for(var i=0;i<checkbox_sum;i++)
            {
                $('#store'+i).attr('disabled','true');
            }
        }
    });
</script>

