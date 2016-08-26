<?php
include_once(dirname(__FILE__) . '/../mainClass.php');
include_once $_SERVER['DOCUMENT_ROOT'] . '/protected/components/Component.php';
/*
 * 微信群发功能类
 */
class WechatMassSend extends WechatBase{


    /**
     * 微信群发广播-发送(根据openID（订阅号不可用，服务号认证后可用）)
     * @param $message 群发信息json字段（type类型+info数据内容）
     * @param $openid_list 群发对象openid列表
     * @param $access_token 商户的access_token
     */
    public function sendMessage($message, $openid_list, $access_token)
    {
        $wechatmaterial = new WechatMaterial();//用到素材类中的上传方法
        $message = json_decode($message,true);
        $type = $message['type'];
        $info = $message['info'];

        if($type == 'wxcard'){//群发原生券时，只需要传入card_id就行了
            $data = $info['card_id'];
        }elseif ($type == 'text'){//群发文本
            $data = $info['content'];//已经经过urlencode处理过的
        }elseif ($type == 'image'){//群发图片
            //上传图片
            $img = $info['img_path'];//原图路径
            $img_upload_res = $wechatmaterial->uploadImg($img, $access_token);
            $img_upload_result = json_decode($img_upload_res, true);

            if (empty($img_upload_result['media_id'])){
                $arr_result['errcode'] = 1;
                $arr_result['errmsg'] = "图片上传失败";
            }else {
                $data = $img_upload_result['media_id'];//上传微信后的图片素材id
            }
        }elseif ($type == 'wqcard'){//群发玩券卡券，需要将链接嵌入图文并上传微信，获取微信素材id

            $material = $info['material'];//素材信息
            //上传图片
            $thumb_media_id = '';
            $img_flag = true;
            $img = $material['img_path'];//图片url
            $img_upload_res = $wechatmaterial->uploadImg($img, $access_token);
            $img_upload_result = json_decode($img_upload_res, true);

            if (empty($img_upload_result['media_id'])){
                $img_flag = false;//上传失败
            }else {
                $thumb_media_id = $img_upload_result['media_id'];//上传微信后的图片素材id
            }

            if ( $img_flag ) {
                //上传图文信息
                //组装图文信息数组
                //处理乱码
                $news[] = array(
                    "thumb_media_id" => $thumb_media_id,
                    //"author" => "nnkou",
                    "title" => urlencode($material['title']),//群发信息标题
                    "content_source_url" => urlencode($material['content_source_url']),//原文链接（卡券链接）
                    "content" => urlencode(htmlspecialchars(str_replace("\"", "'", $material['content']))),
                    //"digest" => $material['abstract'],
                );

                //调用上传素材接口
                $news_upload_res = $wechatmaterial->uploadNews($news, $access_token);
                $news_upload_result = json_decode($news_upload_res, true);
                if (!empty($news_upload_result['media_id'])) {
                    $news_media_id = $news_upload_result['media_id'];
                    $type = 'mpnews';
                    $data = $news_media_id;//获取到上传后的图文素材id

                }else {
                    $arr_result['errcode'] = 1;
                    $arr_result['errmsg'] = "图文信息上传失败";

                }
            }else {
                $arr_result['errcode'] = 1;
                $arr_result['errmsg'] = "图片上传失败";
            }
        }

        //执行群发
        $msg = array('touser'=>$openid_list);//open_id列表
        $msg['msgtype'] = $type;
        //根据类型组装信息内容
        switch ($type)
        {
            case 'text' :
                $msg[$type] = array('content'=>$data);
                break;
            case 'image' :
                $msg[$type] = array('media_id'=>$data);
                break;
            case 'voice' :
            case 'mpvideo' :
            case 'mpnews' :
                $msg[$type] = array('media_id'=>$data);
                break;
            case 'wxcard' :
                $msg[$type] = array('card_id'=>$data);
                break;
        }

        $result = $this->massSendGroud($access_token, json_encode($msg));
        $arr_result = json_decode($result, true);

        return $arr_result;
    }

    /**
     * 微信预览群发信息(根据openID（订阅号不可用，服务号认证后可用）)
     * @param $message 群发信息json字段（type类型+info数据内容）
     * @param $openid 预览对象openid
     * @param $access_token 商户的access_token
     */
    public function previewMessage($message, $openid, $access_token)
    {
        $wechatmaterial = new WechatMaterial();//用到素材类中的上传方法
        $message = json_decode($message,true);
        $type = $message['type'];
        $info = $message['info'];

        if($type == 'wxcard'){//预览原生券时，只需要传入card_id就行了
            $data = $info['card_id'];
        }elseif ($type == 'wqcard'){//预览玩券卡券，需要将链接嵌入图文并上传微信，获取微信素材id

            $material = $info['material'];//素材信息
            //上传图片
            $thumb_media_id = '';
            $img_flag = true;
            $img = $material['img_path'];//图片url
            $img_upload_res = $wechatmaterial->uploadImg($img, $access_token);
            $img_upload_result = json_decode($img_upload_res, true);

            if (empty($img_upload_result['media_id'])){
                $img_flag = false;//上传失败
            }else {
                $thumb_media_id = $img_upload_result['media_id'];//上传微信后的图片素材id
            }

            if ( $img_flag ) {
                //上传图文信息
                //组装图文信息数组
                //处理乱码
                $news[] = array(
                    "thumb_media_id" => $thumb_media_id,//上一步获得的图片id
                    //"author" => "nnkou",//作者
                    "title" => urlencode($material['title']),//群发信息标题
                    "content_source_url" => urlencode($material['content_source_url']),//原文链接（卡券链接）
                    "content" => urlencode(htmlspecialchars(str_replace("\"", "'", $material['content']))),//文本消息
                    //"digest" => $material['abstract'],//摘要
                );

                //调用上传素材接口
                $news_upload_res = $wechatmaterial->uploadNews($news, $access_token);
                $news_upload_result = json_decode($news_upload_res, true);
                if (!empty($news_upload_result['media_id'])) {
                    $news_media_id = $news_upload_result['media_id'];
                    $type = 'mpnews';
                    $data = $news_media_id;//获取到上传后的图文素材id

                }else {
                    $arr_result['errcode'] = 1;
                    $arr_result['errmsg'] = "图文信息上传失败";

                }
            }else {
                $arr_result['errcode'] = 1;
                $arr_result['errmsg'] = "图片上传失败";
            }
        }elseif ($type == 'text'){//群发文本
            $data = urlencode($info['content']);
        }elseif ($type == 'image'){//群发图片
            //上传图片
            $img = $info['img_path'];//原图路径
            $img_upload_res = $wechatmaterial->uploadImg($img, $access_token);
            $img_upload_result = json_decode($img_upload_res, true);

            if (empty($img_upload_result['media_id'])){
                $arr_result['errcode'] = 1;
                $arr_result['errmsg'] = "图片上传失败";
            }else {
                $data = $img_upload_result['media_id'];//上传微信后的图片素材id
            }
        }

        //组装预览数组
        $msg = array('touser'=>$openid);//需要预览的open_id
        $msg['msgtype'] = $type;
        //根据类型组装信息内容
        switch ($type)
        {
            case 'text' :
                $msg[$type] = array('content'=>$data);
                break;
            case 'image' :
                $msg[$type] = array('media_id'=>$data);
                break;
            case 'voice' :
            case 'mpvideo' :
            case 'mpnews' :
                $msg[$type] = array('media_id'=>$data);
                break;
            case 'wxcard' :
                $msg[$type] = array('card_id'=>$data);
                break;
        }
        //执行预览
        $result = $this->massSendPreview($access_token, json_encode($msg));
        $arr_result = json_decode($result, true);

        return $arr_result;
    }

    /**
     * 获取群发广播历史
     * @param $merchant_id
     */
    public function getBroadcastRecord($merchant_id)
    {
        $result = array();
        $list = array();
        try {
            //数据库查询
            $model = Reply::model()->findAll(array(
                'condition' => 'merchant_id = :merchant_id and type = :type and flag = :flag and from_platform = :from_platform',
                'order' => 'create_time desc',
                'params' => array(':merchant_id' => $merchant_id, ':type' => REPLY_TYPE_BROADCAST, ':flag' => FLAG_NO, ':from_platform' => FROM_PLATFORM_WECHAT),
            ));
            if (!empty($model)) {
                foreach ($model as $key => $value) {
                    if (!empty($value['material_id'])) {
                        $material = Material::model()->find('material_id = :material_id and rate = :rate', array(':material_id' => $value['material_id'], ':rate' => 0));
                        $list[$value['id']]['title'] = '[图文消息]' . $material['title'];
                        $list[$value['id']]['content'] = $material['abstract'];
                        $list[$value['id']]['img'] = $material['cover_img'];
                    } else {
                        $list[$value['id']]['title'] = '[文字]' . $value['content'];
                    }

                    if (!$value['group_id']) {
                        $list[$value['id']]['group'] = '全部';
                    } else {
                        $group = UserGroup::model()->findByPk($value['group_id']);
                        $list[$value['id']]['group'] = $group['name'];
                    }
                    $list[$value['id']]['time'] = $value['create_time'];
                }
            }

            $result['data'] = $list;
            $result['status'] = ERROR_NONE; //状态码
            $result['errMsg'] = ''; //错误信息
        } catch (Exception $e) {
            $result['status'] = isset($result['status']) ? $result['status'] : ERROR_EXCEPTION;
            $result['errMsg'] = $e->getMessage(); //错误信息
        }

        return $result;
    }

    /**
     * 客服发送消息接口
     * @param $msg_id     群发信息的id
     * @param $access_token
     */
    public function customSend($access_token, $msg)
    {
        $url = "https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token=".$access_token;
        $result = $this->https_request($url, $msg);

        return $result;
    }



    /**
     * 保存群发消息
     * @param $merchant_id 商户号
     * @param $content json字段（类型加内容）
     * @param $material_id 素材id（可为空）
     * @param $group_id 分组id
     */
    public function saveBroadcastReply($merchant_id, $content, $material_id, $group_id)
    {
        $result = array();
        $model = new Reply();
        try {
            $model['merchant_id'] = $merchant_id;
            $model['from_platform'] = FROM_PLATFORM_WECHAT;
            if (!empty($content)) {
                $model['content'] = $content;
                $model['material_id'] = 0;
            } elseif (!empty($material_id)) {
                $model['material_id'] = $material_id;
                $model['content'] = '';
            }
            $model['create_time'] = date('Y-m-d H:i:s');
            $model['type'] = REPLY_TYPE_BROADCAST;
            $model['group_id'] = $group_id;

            if ($model->save()) {
                $result['data'] = $model;
                $result['status'] = ERROR_NONE; //状态码
                $result['errMsg'] = ''; //错误信息
            }

        } catch (Exception $e) {
            $result['data'] = $model;
            $result['status'] = isset($result['status']) ? $result['status'] : ERROR_EXCEPTION;
            $result['errMsg'] = $e->getMessage(); //错误信息
        }

        return $result;
    }

    /**
     * action: 微信群发接口
     * post: assess_token,msg
     * author: qian_zhaohui
     * date: 2016-7-15
    */
    public function massSendGroud($access_token, $msg)
    {
        $url = "https://api.weixin.qq.com/cgi-bin/message/mass/send?access_token=" . $access_token;
        $result = $this->https_request($url, $msg);

        return $result;
    }

    /**
     * 群发预览接口
     * @param $msg     群发信息
     * @param $access_token
     */
    public function massSendPreview($access_token, $msg)
    {
        $url = "https://api.weixin.qq.com/cgi-bin/message/mass/preview?access_token=" . $access_token;
        $result = $this->https_request($url, $msg);

        return $result;
    }

    /**
     * 删除群发接口
     * @param $msg_id     群发信息的id
     * @param $access_token
     */
    public function deleteSend($access_token, $msg_id)
    {
        $url = "https://api.weixin.qq.com/cgi-bin/message/mass/delete?access_token=" . $access_token;
        $result = $this->https_request($url, $msg_id);

        return $result;
    }

    /**
     * 获取会员分组
     * @param $merchant_id
     */
    public function getGroupList($merchant_id)
    {
        $result = array();
        try {
            //数据库查询
            $cmd = Yii::app()->db->createCommand();
            $cmd->select('*');
            $cmd->from(array('wq_user_group'));
            $cmd->where(array(
                'AND',
                'merchant_id = :merchant_id',
                'flag = :flag',
            ));
            $cmd->params = array(
                ':merchant_id' => $merchant_id,
                ':flag' => FLAG_NO,
            );
            $model = $cmd->queryAll();

            $list = array();
            $list[0] = "所有用户";
            foreach ($model as $key => $value) {
                $list[$value['id']] = $value['name'];
            }

            $result['data'] = $list;
            $result['status'] = ERROR_NONE; //状态码
            $result['errMsg'] = ''; //错误信息
        } catch (Exception $e) {
            $result['status'] = isset($result['status']) ? $result['status'] : ERROR_EXCEPTION;
            $result['errMsg'] = $e->getMessage(); //错误信息
        }

        return $result;
    }

    /**
     * 获取会员openid列表
     * @param $merchant_id
     * @param $group_id
     */
    public function getUserOpenId($merchant_id, $group_id)
    {
        $result = array();
        try {
            //数据库查询
            $cmd = Yii::app()->db->createCommand();
            $cmd->select('*');

            if ($group_id !=0) {
                $cmd->from('wq_group a');
                $cmd->leftJoin('wq_user_group b', 'a.group_id = b.id');

                $cmd->andWhere('b.merchant_id = :merchant_id');
                $cmd->params[':merchant_id'] = $merchant_id;

                $cmd->andWhere('a.group_id = :group_id');
                $cmd->params[':group_id'] = $group_id;
            }else{
                $cmd->from('wq_user a');
                $cmd->andWhere('a.merchant_id = :merchant_id');
                $cmd->params[':merchant_id'] = $merchant_id;
            }


            $cmd->andWhere('a.flag = :flag');
            $cmd->params[':flag'] = FLAG_NO;

            $cmd->andWhere('a.wechat_id is not null');

            $model = $cmd->queryAll();
            $list = array();
            foreach ($model as $key => $value) {
                if (!empty($value['wechat_id'])) {
                    $list[$key] = $value['wechat_id'];
                }
            }

            $result['data'] = $list;
            $result['status'] = ERROR_NONE; //状态码
            $result['errMsg'] = ''; //错误信息
        } catch (Exception $e) {
            $result['status'] = isset($result['status']) ? $result['status'] : ERROR_EXCEPTION;
            $result['errMsg'] = $e->getMessage(); //错误信息
        }

        return $result;
    }

    /**
     * 上传微信卡券（图文发券）
     */
    public function uploadCard($access_token, $card_id)
    {
        $url = "https://api.weixin.qq.com/card/mpnews/gethtml?access_token=" . $access_token;
        $result = $this->https_request($url, $card_id);

        return $result;
    }

    /**
     * 统计会员数量
     */
    public function getUserNum($merchant_id)
    {
        $count_arr = array();
        $wechat_count = User::model()->count('merchant_id = :merchant_id and wechat_id is not null', array(':merchant_id' => $merchant_id));
        $ali_count = User::model()->count('merchant_id = :merchant_id and alipay_fuwu_id is not null', array(':merchant_id' => $merchant_id));
        $togther_count = User::model()->count('merchant_id = :merchant_id and wechat_id is not null and alipay_fuwu_id is not null', array(':merchant_id' => $merchant_id));
        $count_arr['wechat'] = $wechat_count;
        $count_arr['ali'] = $ali_count;
        $count_arr['togher'] = $togther_count;

        return $count_arr;
    }

}