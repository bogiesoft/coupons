<script type="text/javascript">
	$(document).ready(main_obj.list_init);
</script>

<div class="kkfm_r_inner">
   <div class="top">
        <div class="name">会员等级管理</div>
    </div>
    <?php if($change){?>
        <div class="policy">
            <a href="<?php echo $this->createUrl('ReleaseUserGrade',array('num'=>$num))?>" class="post_policy">发布会员制度</a>
            <a href="<?php echo $this->createUrl('RevertUserGrade')?>" class="undo_policy">还原上次会员制度</a>
            <p class="tips">
                会员制度已经发生变化，选择<em>“发布会员制度”</em>，使新的会员制度生效；选择还原<em>“还原上次会员制度”</em>，取消对会员制度的修改。
            </p>
        </div>
    <?php }?>
    <?php if($countusergrade < 5) { ?>
        <div class="btn_com_grade_wrap">
            <a href="<?php echo $this->createUrl('AddUserGrade')?>">
                <input type="button" value="添加会员等级" class="btn_com_blue btn_com_grade">
            </a>
        </div>
    <?php } ?>
    <div class="vipCard"> 
        <?php if (!empty($lists)) { ?>
        <div class="item clearfix">
        	<div class="card">
            	<div class="card-box">
                    <?php $images = explode('/', $lists['membercard_img']);
                          $count = count($images);?>
                    <?php if($count == 2) { ?>
                        <img src="<?php echo IMG_GJ_LIST.$lists['membercard_img']?>">
                    <?php } ?>
                    <?php if($count == 1) { ?>
                        <img src="<?php echo GJ_STATIC_IMAGES.'card/'.$lists['membercard_img']?>">
                    <?php } ?>
                    <?php if($lists['if_hideword'] == IF_HIDEWORD_NO){?>
                      <!--<div class="name"><?php echo $lists['membership_card_name']?></div>-->
                      <div class="card-dj"><?php echo $lists['name']?></div>
                      <?php }?>
                </div>
            </div>
            <div class="con">
            	<span class="title">会员人数：<?php echo $lists['count']; ?>人<em>(默认等级)</em></span>
                <span class="tq"><em>积分要求：<?php echo $lists['points_rule'];?></em><em>会员特权：<?php echo isset($lists['discount']) ? number_format($lists['discount'],1).'折' : '无'; ?></em></span>
                <div class="filed">
                    <span class="label">特权说明：</span>                    
                    <input type="hidden" id="discount_illustrate" value="<?php echo $lists['discount_illustrate']?>">
                    <span class="text" id="illustrate"></span>
                    <div id="details" class="details">
                    	<span class="ico"></span>
                        <m id="detail"></m>
                    </div>
                </div>
            </div>
            <div class="shade">
                <a href="<?php echo $this->createUrl('editUserGrade',array('id'=>$lists['id'],'k'=>$lists['if_default']))?>" class="edit"></a>
            </div>
        </div>
         <?php } ?>
        <!--end item-->
        <?php if (!empty($list)) {
        foreach ($list as $k => $v) { ?>        
        <div class="item item01 clearfix">
        	<div class="card">
            	<div class="card-box">
                    <?php $images = explode('/', $v['membercard_img']);
                          $count = count($images);?>
                    <?php if($count == 2) { ?>
                        <img src="<?php echo IMG_GJ_LIST.$v['membercard_img']?>">
                    <?php } ?>
                    <?php if($count == 1) { ?>
                        <img src="<?php echo GJ_STATIC_IMAGES.'card/'.$v['membercard_img']?>">
                    <?php } ?>
                    <?php if($v['if_hideword'] == IF_HIDEWORD_NO){?>
                      <!--<div class="name"><?php echo $v['membership_card_name']?></div>-->
                      <div class="card-dj"><?php echo $v['name']?></div>
                      <?php }?>
                </div>
            </div>
            <div class="con">
            	<span class="title">会员人数：<?php echo $v['count']; ?>人</span>
                <span class="tq"><em>积分要求：<?php echo $v['points_rule'];?></em><em>会员特权：<?php echo isset($v['discount']) ? substr($v['discount'],0,3).'折' : '无'; ?></em></span>
                <div class="filed">
                    <span class="label">特权说明：</span>                    
                    <input type="hidden" id="discount_illustrate<?php echo $k?>" value="<?php echo $v['discount_illustrate']?>">
                    <span class="text" id="illustrate<?php echo $k?>"></span>                    
                    <div id="details_<?php echo $k?>" class="details">
                    	<span class="ico"></span>
                        <m id="detail_<?php echo $k?>"></m>
                    </div>
                </div>
            </div>
            <div class="shade">
                <a href="<?php echo $this->createUrl('editUserGrade',array('id'=>$v['id'],'k'=>$v['if_default']))?>" class="edit"></a>
                <a href="<?php echo $this->createUrl('delUserGrade',array('id'=>$v['id']))?>" class="del" onclick="return confirm('确定此操作吗？');"></a>
            </div>
        </div>
            <?php } } ?>
       
        <!--end item-->
       <script>
	   	$(".con .filed").hover(
                    function(){
                        $(this).addClass("filedHover")
                    },
                    function(){
                        $(this).removeClass("filedHover")
                    }	
                )
       		$(".vipCard .item").hover(
                    function(){
                        $(this).addClass("menuHover")
                    },
                    function(){
                        $(this).removeClass("menuHover")
                    }
                )

                var s=$('#discount_illustrate').val().replace(/\n/g,'<br>');
                $('#illustrate').html(s);
                var length = (s.split('<br>')).length-1;
                if(length >= 3){
                    $('#detail').html(s);
                }else{
                    $('#details').hide();
                }
                <?php if (!empty($list)) {
                foreach ($list as $k => $v) { ?> 
                    strate=$('#discount_illustrate<?php echo $k?>').val().replace(/\n/g,'<br>');
                    $('#illustrate<?php echo $k?>').html(strate);
                    var len = (strate.split('<br>')).length-1;
                    if(len >= 3){
                        $('#detail_<?php echo $k?>').html(strate);
                    }else{
                        $('#details_<?php echo $k?>').hide();
                    }
                <?php }}?>

                <?php if (isset($_GET['showleft']) && ($_GET['showleft'] == 1)){?>
                	layer.msg('会员等级条件未设置。会员等级条件已修改，原有会员等级条件失效，需要重新设置并发布',{offset: '200px'});
                <?php }?>
       </script>
    </div>
</div> 


