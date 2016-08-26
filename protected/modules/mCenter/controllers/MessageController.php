<?php
include_once(getcwd() . '/protected/extensions/alifuwu/function.inc.php');

class MessageController extends mCenterController
{
    public function init()
    {

    }

    /*
     * ation:处理收到的信息
     * author：li-junyu
     * date:2015-3-12
     */
    public function Message($encrypt_id, $biz_content)
    {
    	$fuwu = new FuwuC();
    	
        header("Content-Type: text/xml;charset=GBK");
        
        //writeLog("+++".$biz_content);
        $UserInfo = $this->getNode($biz_content, "UserInfo");
//         $FromUserId = $this->getNode($biz_content, "FromUserId");
        //由于业务原因计划于2016年9月份废弃，FromAlipayUserId将替代FromUserId作为用户唯一性标示
        $FromUserId = $this->getNode($biz_content, "FromAlipayUserId");
        
        $AppId = $this->getNode($biz_content, "AppId");
        $CreateTime = $this->getNode($biz_content, "CreateTime");
        $MsgType = $this->getNode($biz_content, "MsgType");
        $EventType = $this->getNode($biz_content, "EventType");
        $AgreementId = $this->getNode($biz_content, "AgreementId");
        $ActionParam = $this->getNode($biz_content, "ActionParam");
        $AccountNo = $this->getNode($biz_content, "AccountNo");
        writeLog("type:".$EventType);
        
        $admin = $fuwu->getMerchant($encrypt_id);
        $appid = $admin['appid'];
        $merchant_id = $admin['id'];
        
        // 收到用户发送的对话消息
        if ($MsgType == "text") {
            //收到的文本
            $text = $this->getNode($biz_content, "Text");
            writeLog("收到的文本：" . $text);

            //查询数据库寻找关键词
            $word_obj = $fuwu->getReplyKeyWord($admin['id'], $text);
             //判断是否存在该关键词
            if (!empty($word_obj)) {
                if ( !empty($word_obj['content']) ) { //判断回复类型：文字信息
                    $content = $word_obj['content']; //回复内容
                    $this->transmitText($content, $FromUserId, $appid);
                } else { //回复类型：图文信息
                	//根据id找到图文素材
                    $obj_material = $fuwu->getMaterial($word_obj['material_id']);
                    $this->transmitImageText($obj_material, $FromUserId, $appid);
                }
            } else {
            	$word_obj = Reply::model()->find('merchant_id = :merchant_id and type = :type and from_platform = :from_platform and flag = :flag', array(':merchant_id'=>$merchant_id, ':type'=>REPLY_TYPE_MSG, ':from_platform'=>FROM_PLATFORM_ALI, ':flag'=>FLAG_NO));
            	//判断是否存在该关键词
            	if (!empty($word_obj)) {
            		if ( !empty($word_obj['content']) ) { //判断回复类型：文字信息
            			$content =$word_obj['content']; //回复内容
            			$this->transmitText($content, $FromUserId, $appid);
            		} elseif (!empty($word_obj['material_id'])) { //回复类型：图文信息
            			//             		$obj_material = Material::model()->findByPk($word_obj->reply_content_material_id);//根据id找到图文素材
            			$obj_material = $fuwu->getMaterial($word_obj['material_id']);
            			$this->transmitImageText($obj_material, $FromUserId, $appid);
            		}
            	} else {
            	}
            }
        }

        // 收到用户发送的关注消息
        if ($EventType == "follow") {
        	writeLog("用户关注");
        	$info = $this->getNode($biz_content, "UserInfo");
        	//存储用户信息
        	Yii::log('alipay_follow','warning');
        	$user = new UserC();
        	$result = json_decode($user -> saveAlipayFansInfo($merchant_id,$FromUserId,$info));
        	if($result -> status == ERROR_NONE){
                //首次关注
                if($result['userExist'] == false){

                }
                //再次关注
        		elseif($result['userExist'] == true){
                    $type = MARKETING_ACTIVITY_TYPE_BASICS_SCGZMA;
                    $marketingC = new MarketingClass();
                    $marketing = json_decode($marketingC->getMarketingCouponShowInfo($merchant_id,$type),true);

                    $coupon_id = $marketing['coupon_id'];

                    //查询优惠券信息
                    $coupon_info = Coupons::model()->findByPk($coupon_id);

                    //图文消息内容
                    $name = $marketing['name'];
                    $abstract = $marketing['image_text_title'];
                    $imageUrl = $marketing['image_text_imageurl'];
                    $link_content = WAP_DOMAIN . '/coupon_' . $coupon_info['code'] . '.html';
                    $obj_material = array(
                        '0'=>array(
                            "abstract"=>$abstract,
                            "cover_img"=>$imageUrl,
                            "title"=>$name,
                            "link_content"=>$link_content
                    ));
                    $this->transmitImageText($obj_material,$FromUserId,$appid);
                }
        	}else{
        		Yii::log('alipay_follow'.$result -> status.$result -> errMsg,'warning');
        	}
            // 处理关注消息
            // 一般情况下，可推送一条欢迎消息或使用指导的消息。
            // 如：
            //收到的文本
            //查询数据库寻找关键词
            //$word_obj = KeyWord::model()->find('user_id = :user_id and match_type=:match_type', array(':user_id' => $encrypt_account, ':match_type' => WN_MATCH_TYPE_FIRST));
            $word_obj = Reply::model()->find('merchant_id = :merchant_id and type = :type and from_platform = :from_platform and flag = :flag', array(':merchant_id'=>$merchant_id, ':type'=>REPLY_TYPE_MSG, ':from_platform'=>FROM_PLATFORM_ALI, ':flag'=>FLAG_NO));
            //判断是否存在该关键词
            if (!empty($word_obj)) {
            	if ( !empty($word_obj['content']) ) { //判断回复类型：文字信息
            		$content =$word_obj['content']; //回复内容
            		$this->transmitText($content, $FromUserId, $appid);
            	} elseif (!empty($word_obj['material_id'])) { //回复类型：图文信息
//             		$obj_material = Material::model()->findByPk($word_obj->reply_content_material_id);//根据id找到图文素材
                    $obj_material = $fuwu->getMaterial($word_obj['material_id']);
            		$this->transmitImageText($obj_material, $FromUserId, $appid);
            	}
            } else {
            }
        } elseif ($EventType == "unfollow") {
        	//存储用户信息
        	$user = new UserC();
        	$result = json_decode($user -> cancelAlipaySubscribe($merchant_id,$FromUserId));
        	if($result -> status == ERROR_NONE){
        	
        	}
            
        } elseif ($EventType == "enter") {

            // 处理进入消息，扫描二维码进入,获取二维码扫描传过来的参数

            $arr = json_decode($ActionParam);
            if ($arr != null) {
                writeLog("二维码传来的参数：" . var_export($arr, true));

                $sceneId = $arr->scene->sceneId;
                writeLog("二维码传来的参数,场景ID：" . $sceneId);
                // 这里可以根据定义场景ID时指定的规则，来处理对应事件。
                // 如：跳转到某个页面，或记录从什么来源(哪种宣传方式)来关注的本服务窗
            }
            // 处理关注消息
            // 一般情况下，可推送一条欢迎消息或使用指导的消息。

            // 日志记录
        } elseif ($EventType == "click") {
        	// 处理菜单点击的消息
        	writeLog("点击菜单,收到的参数：" . $ActionParam);
        	writeLog("encrypt_account：" . $encrypt_id);
        	
        	//数据统计
//         	$statistics = new StatisticsForm();
//         	$statistics->updateMenu($ActionParam,$encrypt_account);
            
        	$obj_menu = Menu::model()->findByPk($ActionParam);
        	
        	if (!empty($obj_menu)) {
        		writeLog('菜单内容'.$obj_menu->content);
        		
        		if ($obj_menu->type == WQ_MENU_TYPE_WORD) {  //文字信息
        			$this->transmitText($obj_menu->content, $FromUserId, $appid);
        		}
        		if ($obj_menu->type == WQ_MENU_TYPE_PHOTO) {  //图文信息
//         			$obj_material = Material::model()->findByPk($obj_menu->content);//根据id找到图文素材
                    $obj_material = $fuwu->getMaterial($obj_menu['content']);
        			$this->transmitImageText($obj_material, $FromUserId, $appid);
        		}
        	}
        }

        // 给支付宝返回ACK回应消息，不然支付宝会再次重试发送消息,再调用此方法之前，不要打印输出任何内容
        echo self::mkAckMsg($FromUserId, $appid);
        exit ();
    }

    /**
     * 直接获取xml中某个结点的内容
     *
     * @param unknown $xml
     * @param unknown $node
     */
    public function getNode($xml, $node)
    {
        $xml = "<?xml version=\"1.0\" encoding=\"GBK\"?>" . $xml;
        $dom = new DOMDocument ("1.0", "GBK");
        $dom->loadXML($xml);
        $event_type = $dom->getElementsByTagName($node);
        return isset($event_type->item(0)->nodeValue) ? $event_type->item(0)->nodeValue : '';
    }

    /*
     *action:回复文字信息
    *author:li-junyu
    *date:2015-3-13
    */
    public function transmitText($content, $FromUserId, $appid)
    {
        include(getcwd() . '/protected/extensions/alifuwu/PushMsg.php');
        $push = new PushMsg ();
        
        $text_msg = $push->mkTextMsg($content);
        // 发给这个关注的用户
        $biz_content = $push->mkTextBizContent($FromUserId, $text_msg);
        //$biz_content = iconv("UTF-8", "GBK//IGNORE", $biz_content);
        //writeLog(iconv("GB2312", "UTF-8//IGNORE", "\r\n发送的biz_content：" . $biz_content));

        // $return_msg = $push->sendMsgRequest ( $biz_content );
        $return_msg = $push->sendRequest($biz_content, $appid);
        // 日志记录
        //writeLog("发送对话消息返回：" . getcwd());
        writeLog("发送对话消息返回：20150317");
    }

    /*
     *
     * 回复图文信息
     * li-junyu:20150412
     */
    public function transmitImageText($obj_material, $FromUserId, $appid)
    {
        include(getcwd() . '/protected/extensions/alifuwu/PushMsg.php');
        $push = new PushMsg ();
		$image_text_msg = array();

		foreach ($obj_material as $key => $value){
			$image_text_msg[] = array('actionName' => "立即查看",
			        					"desc" => $value->abstract,
			        					"imageUrl" => IMG_GJ_LIST.$value->cover_img,
			        					"title" => $value->title,
			        					"url" => $value->link_content,
			        					"authType" => "loginAuth");
		}
// 		$image_text_msg[] = array('actionName' => "立即查看",
// 				"desc" => "摘要",
// 				"imageUrl" => "http://upload.51wanquan.com/images/gj/source/20150720/150720100953379694.png",
// 				"title" => "标题",
// 				"url" => "https://www.baidu.com",
// 				"authType" => "loginAuth");
		// 发给这个关注的用户
		$biz_content = $push->mkImageTextBizContent ( $FromUserId, $image_text_msg );
		$return_msg = $push->sendRequest ( $biz_content, $appid );
		// 日志记录
        //file_put_contents ( "log.txt", $return_msg . "\r\n", FILE_APPEND );
    }

    public function mkAckMsg($toUserId, $appid)
    {
        header('Content-type:text/html;charset=GBK');
        Yii::import('application.extensions.alifuwu.*');
        require_once('AlipaySign.php');
        require_once('AopConfig.php');

        $as = new AlipaySign ();
        $private_key_file = getcwd() . "/protected/extensions/alifuwu/key/rsa_private_key.pem";

        $response_xml = " < XML><ToUserId ><![CDATA[" . $toUserId . "]]></ToUserId ><AppId ><![CDATA[" . $appid . "]]></AppId ><CreateTime > " . time() . "</CreateTime ><MsgType ><![CDATA[ack]]></MsgType ></XML > ";
        $return_xml = $as->sign_response($response_xml, $AopConfig ['charset'], $private_key_file);

        writeLog("response_xml: " . $return_xml);
        return $return_xml;
    }
}

