<?php

/**
 * Class AliServiceWindowMessageTotalSend
 * 用户群发消息类
 */
class AliServiceWindowMessageSend extends AliServiceWindowMain{

    /**
     * 群发广播-全部分组
     * @param null $content     文本消息内容
     * @param null $material_id 图文素材ID
     * @return bool|mixed|SimpleXMLElement
     */
    public function messageTotalSend($content = null, $material_id = null)
    {
        $biz_content = '';

        //包装参数成JSON
        if(!empty($content)){
            //如果文字消息不为空，那么发送文字消息
            $msg = array();
            $msg["msgType"] = "text";
            $msg["text"] = array("content" => $content);
            $biz_content = json_encode($msg);
        }elseif(!empty($material_id)){
            //如果图文素材ID不为空，那么发送图文消息
            $msg = array();
            $material = Material::model()->findAll('material_id = :material_id order by rate', array(':material_id' => $material_id));

            $msg["msgType"] = "image-text";
            $msg["createTime"] = time(date('Ymdhis'));
            $msg["articles"] = array();
            foreach ($material as $key => $value) {
                $msg["articles"][] = array("actionName" => "立即查看",
                    "desc" => $value['content'],
                    "imageUrl" => IMG_GJ_LIST . $value['cover_img'],
                    "title" => $value['title'],
                    "url" => $value['link_content'],
                );
            }
            $biz_content = json_encode($msg);
        }

        //获取APPID并发送数据至服务窗接口
        $merchant_id = Yii::app()->session ['merchant_id'];
        $merchant = Merchant::model()->findByPk($merchant_id);
        $custom_send = new AlipayMobilePublicMessageTotalSendRequest();
        $custom_send->setBizContent($biz_content);
        return without_request_execute($custom_send, $merchant['appid']);
    }


    /**
     * 群发广播-标签分组
     * @param null $content         文本消息内容
     * @param null $material_id     图文素材ID
     * @param $label_id             标签id
     * @return bool|mixed|SimpleXMLElement
     */
    public function messageLabelSend($content = null, $material_id = null, $label_id)
    {
        $biz_content = '';
        //包装参数成JSON
        if(!empty($content)){
            //如果文字消息不为空，那么发送文字消息
            $msg = array(
                "material" => array(
                    "msgType" => "text",
                    "text" => array("content" => $content),
                ),

                "filter" => array(
                    "template" => '${a}',
                    "context" => array(
                        "a" => array(
                            "columnName" => "label_id_list",
                            "op" => "=",
                            "values" => array($label_id),
                        ),
                    ),
                ),
            );
            $biz_content = json_encode($msg);

        }elseif(!empty($material_id)) {
            //如果图文素材ID不为空，那么发送图文消息
            $material = Material::model()->findAll('material_id = :material_id order by rate', array(':material_id' => $material_id));

            $msg = array();
            $msg['material'] = array();
            $msg['material']["msgType"] = "image-text";
            $msg['material']["createTime"] = time(date('Ymdhis'));
            foreach ($material as $key => $value) {
                $msg['material']['articles'][] = array("actionName" => "立即查看",
                    "desc" => $value['content'],
                    "imageUrl" => IMG_GJ_LIST . $value['cover_img'],
                    "title" => $value['title'],
                    "url" => $value['link_content'],
                );
            }

            $msg['filter'] = array(
                "template" => '${a}',
                "context" => array(
                    "a" => array(
                        "columnName" => "label_id_list",
                        "op" => "=",
                        "values" => array($label_id),
                    ),
                ),
            );
            $biz_content = json_encode($msg);
        }

        $merchant_id = Yii::app()->session ['merchant_id'];
        $merchant = Merchant::model()->findByPk($merchant_id);
        $custom_send = new AlipayMobilePublicMessageLabelSendRequest();
        $custom_send->setBizContent($biz_content);
        return without_request_execute($custom_send, $merchant['appid']);
    }

    /**
     * 保存单图文素材
     * @param  $title                标题
     * @param  $cover_img            图片
     * @param  $content              正文内容
     * @param  $link_content         跳转链接
     * @return array
     */
    public function saveMaterial($array)
    {
        $sort = 1;
        $material_id = date('Ymdhis');
        foreach($array as $v){
            $model = new Material();
            $model['title'] = $v['title'];
            $model['cover_img'] = $v['cover_img'];
            $model['content'] = $v['content'];
            $model['material_id'] = $material_id;
            $model['rate'] = $sort;
            $model['link_content'] = $v['link_content'];
            if (empty($v['title'])) {
                throw new Exception('标题为空');
            }
            if (empty($v['cover_img'])) {
                throw new Exception('图片为空');
            }
            if (empty($v['content'])) {
                throw new Exception('正文为空');
            }
            if (empty($v['link_content'])) {
                throw new Exception('链接为空');
            }
            if($model->save()){
                $sort++;
            }
        }
        return $material_id;
    }
}