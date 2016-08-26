<script type="text/javascript">
	$(document).ready(main_obj.list_init);
</script>

<div class="kkfm_r_inner">
	<div class="contant">
	<?php if(!empty($memberCardList)){?>
	<?php foreach ($memberCardList as $key => $value) { ?> 
            <div class="card">             
                <div class="card-box" height="200px" width="285px" style="overflow: hidden">
                    <img src="<?php if(isset($value['membercard_img']) && !empty($value['membercard_img'])){?>
                    <?php echo IMG_GJ_LIST.$value['membercard_img']?>
                    <?php }else{?>
                    <?php echo GJ_STATIC_IMAGES?>mcard.png
                    <?php }?>">
                    <div class="name"><?php echo $value['membership_card_name']?></div>
                    <div class="card-dj"><?php echo $value['name']?></div>
                    <div class="card-id">会员编号：888888888888</div>
                    <span class="change"><a href="<?php echo $this->createUrl('editMembershipCard',array('id'=>$value['id']))?>">修改</a></span>
                </div>  
                <?php }?>
                <?php } else { ?>
                    <div class="card-box">
                        <div class="name">没有会员卡</div>
                    </div>  
                <?php } ?>
            </div>
        </div>
</div> 