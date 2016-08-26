<?php
include_once(dirname(__FILE__) . '/../mainClass.php');
include_once $_SERVER['DOCUMENT_ROOT'] . '/protected/components/Component.php';
/*
 * 微信素材管理类
 */
class WechatMaterial extends WechatBase{

    /**
     * 获取素材url
     * @param $merchant_id
     */
    public function getMaterialUrl($merchant_id)
    {
        $result = array();
        $list = array();
        try {
            $model = Merchant::model()->findByPk($merchant_id);
            $coupons = Coupons::model()->findAll('merchant_id = :merchant_id and flag = :flag', array(':merchant_id' => $merchant_id, ':flag' => FLAG_NO));
            if (!empty($model)) {
                $list[0] = "--请选择--";
                $list[USER_DOMAIN . '/memberCenter?encrypt_id=' . $model['encrypt_id'] . '&source=wechat'] = '会员中心';
                $list[USER_DOMAIN . '/shop?encrypt_id=' . $model['encrypt_id'] . '&source=wechat'] = '在线商铺';
                $list[USER_DOMAIN . '/bookOperate?encrypt_id=' . $model['encrypt_id'] . '&source=wechat'] = '预定';
                $list[USER_DOMAIN . '/orderList?encrypt_id=' . $model['encrypt_id'] . '&source=wechat' . '&stored_confirm_status=' . ORDER_PAY_WAITFORCONFIRM] = '我的订单';

                if (!empty($coupons)) {
                    foreach ($coupons as $key => $value) {
                        $url = USER_DOMAIN . '/lookCoupons?encrypt_id=' . $model['encrypt_id'] . '&coupons_id=' . $value['id'] . '&coupons_type=' . $value['type'] . '&source=wechat';
                        $list[$url] = $value['title'];
                    }
                }

                $now = date("Y-m-d h:i:s");
                $promotions = Activity::model()->findAll('merchant_id = :merchant_id and flag = :flag and end_time > :now', array(':merchant_id' => $merchant_id, ':flag' => FLAG_NO, ':now' => $now));
                if (!empty($promotions)) {
                    foreach ($promotions as $k => $v) {
                        $activity_url = PROMOTIONS_DOMAIN . '/promotionsActivity?encrypt_id=' . $model['encrypt_id'] . '&promotions_id=' . $v['id'] . '&source=wechat';
                        if ($v['type'] == PROMOTIONS_TYPE_TURNTABLE) {  //大转盘活动
                            $list[$activity_url] = '大转盘——' . $v['name'];
                        } elseif ($v['type'] == PROMOTIONS_TYPE_SEARCH) {  //刮刮卡
                            $list[$activity_url] = '刮刮卡——' . $v['name'];
                        }
                    }
                }

                $result['data'] = $list;
                $result['status'] = ERROR_NONE; //状态码
                $result['errMsg'] = ''; //错误信息
            } else {
                $result['status'] = ERROR_NO_DATA; //状态码
                $result['errMsg'] = '未找到该商户信息'; //错误信息
            }
        } catch (Exception $e) {
            $result['status'] = isset($result['status']) ? $result['status'] : ERROR_EXCEPTION;
            $result['errMsg'] = $e->getMessage(); //错误信息
        }

        return $result;
    }

    /**
     * 获取图文素材列表
     * @param $merchant_id
     */
    public function getMaterialDownList($merchant_id)
    {
        $result = array();
        try {
            //数据库查询
            $cmd = Yii::app()->db->createCommand();
            $cmd->select('*');
            $cmd->from(array('wq_material'));
            $cmd->where(array(
                'AND',
                'merchant_id = :merchant_id',
                'flag = :flag',
//                  'from_platform = :from_platform',
            ));
            $cmd->params = array(
                ':merchant_id' => $merchant_id,
                ':flag' => FLAG_NO,
//                  ':from_platform' => FROM_PLATFORM_WECHAT,
            );
            $model = $cmd->queryAll();

            $list = array();
            $list[0] = "---请选择---";
            foreach ($model as $key => $value) {
                if (0 == $value['rate']) {
                    $list[$value['material_id']] = $value['title'];
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
     * 获取素材信息
     */
    public function getMaterial($reply_id)
    {
        $model = Material::model()->findAll('material_id = :material_id and flag = :flag', array(':material_id' => $reply_id, ':flag' => FLAG_NO));

        return $model;
    }

    /**
     * 获取图文素材
     * @param $merchant_id          商户id
     */
    public function getMaterialList($merchant_id)
    {
        $result = array();
        try {
            //数据库查询
            $cmd = Yii::app()->db->createCommand();
            $cmd->select('*');
            $cmd->from(array('wq_material'));
            $cmd->where(array(
                'AND',
                'merchant_id = :merchant_id',
                'flag = :flag',
                'from_platform = :from_platform',
            ));
            $cmd->params = array(
                ':merchant_id' => $merchant_id,
                ':flag' => FLAG_NO,
                ':from_platform' => FROM_PLATFORM_WECHAT,
            );
            $cmd->order = 'last_time desc';
            $model = $cmd->queryAll();

            $list = array();
            foreach ($model as $key => $value) {
                if (empty($list[$value['material_id']])) {
                    $list[$value['material_id']][$value['rate']]['id'] = $value['id'];
                    $list[$value['material_id']][$value['rate']]['title'] = $value['title'];
                    $list[$value['material_id']][$value['rate']]['cover_img'] = $value['cover_img'];
                    $list[$value['material_id']][$value['rate']]['abstract'] = $value['abstract'];
                } else {
                    $list[$value['material_id']][$value['rate']]['id'] = $value['id'];
                    $list[$value['material_id']][$value['rate']]['title'] = $value['title'];
                    $list[$value['material_id']][$value['rate']]['cover_img'] = $value['cover_img'];
                    $list[$value['material_id']][$value['rate']]['abstract'] = $value['abstract'];
                }
                asort($list[$value['material_id']]);
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
     * 获取单图文信息
     * @param $material_id          素材id
     */
    public function getSingleMaterial($material_id)
    {
        $result = array();
        try {
            if (!empty($material_id)) {
                //数据库查询
                $model = Material::model()->find('material_id = :material_id', array(':material_id' => $material_id));
            } else {
                $model = new Material();
            }

            $result['data'] = $model;
            $result['status'] = ERROR_NONE; //状态码
            $result['errMsg'] = ''; //错误信息
        } catch (Exception $e) {
            $result['status'] = isset($result['status']) ? $result['status'] : ERROR_EXCEPTION;
            $result['errMsg'] = $e->getMessage(); //错误信息
        }

        return $result;
    }

    /**
     * 获取多图文信息
     * @param $material_id          素材id
     */
    public function getMoreMaterial($material_id)
    {
        $result = array();
        try {
            if (!empty($material_id)) {
                //数据库查询
                $model = Material::model()->findAll(array(
                    'condition' => 'material_id = :material_id and flag = :flag',
                    'order' => 'rate',
                    'params' => array(':material_id' => $material_id, ':flag' => FLAG_NO),
                ));
                $count = Material::model()->count('material_id = :material_id and flag = :flag', array(
                    ':material_id' => $material_id,
                    ':flag' => FLAG_NO
                ));
            }else{
                $model = new Material();
            }

            $result['data'] = $model;
            $result['num'] = $count;
            $result['status'] = ERROR_NONE; //状态码
            $result['errMsg'] = ''; //错误信息
        } catch (Exception $e) {
            $result['status'] = isset($result['status']) ? $result['status'] : ERROR_EXCEPTION;
            $result['errMsg'] = $e->getMessage(); //错误信息
        }

        return $result;
    }

    /**
     * 修改多图文
     * @param  $id
     * @param  $title
     * @param  $link_content
     * @param  $cover_img
     * @param  $img_path
     * @param  $jump_type
     * @param  $content
     * @param  $rate
     */
    public function editMaterial($id, $title, $link_content, $cover_img, $img_path, $jump_type, $content)
    {
        $result = array();
        try {
            $model = Material::model()->findByPk($id);

            $model['title'] = $title;
            $model['cover_img'] = $cover_img;
            $model['img_path'] = $img_path;
            $model['jump_type'] = $jump_type;
            if ($jump_type == MATERIAL_CONTENT_TEXT) {
                $model['content'] = $content;
            } else {
                $model['link_content'] = $link_content;
            }
            //参数验证

            if ($model->save()) {
                if ($model['jump_type'] == MATERIAL_CONTENT_TEXT) {
                    $model['link_content'] = USER_DOMAIN . '/material?material_id=' . $model['id'];
                }
                if ($model->save()) {
                    $result['data'] = $model;
                    $result['status'] = ERROR_NONE; //状态码
                    $result['errMsg'] = ''; //错误信息
                } else {
                    $result['data'] = $model;
                    $result['status'] = ERROR_SAVE_FAIL; //状态码
                    $result['errMsg'] = '数据保存失败'; //错误信息
                }
            } else {
                $result['data'] = $model;
                $result['status'] = ERROR_SAVE_FAIL; //状态码
                $result['errMsg'] = '数据保存失败'; //错误信息
            }
        } catch (Exception $e) {
            $result['data'] = $model;
            $result['status'] = isset($result['status']) ? $result['status'] : ERROR_EXCEPTION;
            $result['errMsg'] = $e->getMessage(); //错误信息
        }

        return $result;
    }

    /**
     * 删除单条图文
     * @param $id
     */
    public function delSingleMaterial($id)
    {
        $result = array();
        try {
            $model = Material::model()->findByPk($id);
            if (!empty($model)) {
                $model->flag = FLAG_YES;
                if ($model->save()) {
                    $result['status'] = ERROR_NONE; //状态码
                    $result['errMsg'] = ''; //错误信息
                } else {
                    $result['data'] = $model;
                    $result['status'] = ERROR_SAVE_FAIL; //状态码
                    $result['errMsg'] = '数据保存失败'; //错误信息
                }
            } else {
                $result['status'] = ERROR_NONE; //状态码
                $result['errMsg'] = ''; //错误信息
            }

        } catch (Exception $e) {
            $result['status'] = isset($result['status']) ? $result['status'] : ERROR_EXCEPTION;
            $result['errMsg'] = $e->getMessage(); //错误信息
        }

        return $result;
    }


    /**
     * 保存单图文素材
     * @param  $model
     * @param  $merchant_id          商户id
     * @param  $title                标题
     * @param  $cover_img            图片
     * @param  $img_path             图片路径
     * @param  $abstract             摘要
     * @param  $jump_type            跳转类型
     * @param  $content              正文内容
     * @param  $link_content         跳转链接
     * @param  $material_id          素材id
     * @param  $rate                 排序值
     */
    public function saveSingleMaterial($model, $merchant_id, $title, $cover_img, $img_path, $abstract, $jump_type, $content, $link_content, $material_id, $rate)
    {
        $result = array();
        try {

            $model['merchant_id'] = $merchant_id;
            $model['title'] = $title;
            $model['cover_img'] = $cover_img;
            $model['img_path'] = $img_path;
            $model['abstract'] = $abstract;
            $model['jump_type'] = $jump_type;
            $model['rate'] = $rate;
            $model['create_time'] = date('Y-m-d h:i:s');
            $model['from_platform'] = FROM_PLATFORM_WECHAT;
            $model['material_id'] = $material_id;
            if ($jump_type == MATERIAL_CONTENT_TEXT) {
                $model['content'] = $content;
            } else {
                $model['link_content'] = $link_content;
            }
            //参数验证
            if (!isset($title)) {
                throw new Exception('标题为空');
            }
            if (empty($cover_img)) {
                throw new Exception('图片为空');
            }

            if ($model->save()) {
                if ($model['jump_type'] == MATERIAL_CONTENT_TEXT) {
                    $model['link_content'] = USER_DOMAIN . '/material?material_id=' . $model['id'];
                }
                if ($model->save()) {
                    $result['data'] = $model;
                    $result['status'] = ERROR_NONE; //状态码
                    $result['errMsg'] = ''; //错误信息
                } else {
                    $result['data'] = $model;
                    $result['status'] = ERROR_SAVE_FAIL; //状态码
                    $result['errMsg'] = '数据保存失败'; //错误信息
                }
            } else {
                $result['data'] = $model;
                $result['status'] = ERROR_SAVE_FAIL; //状态码
                $result['errMsg'] = '数据保存失败'; //错误信息
            }
        } catch (Exception $e) {
            $result['data'] = $model;
            $result['status'] = isset($result['status']) ? $result['status'] : ERROR_EXCEPTION;
            $result['errMsg'] = $e->getMessage(); //错误信息
        }

        return $result;
    }

    /**
     * 删除图文素材
     * @param $material_id          素材id
     * @author gu
     */
    public function delMaterial($material_id)
    {
        $result = array();
        $flag = 0;
        try {
            $transaction = Yii::app()->db->beginTransaction();
            $material = Material::model()->findAll('material_id =:material_id and flag = :flag', array(
                ':material_id' => $material_id,
                ':flag' => FLAG_NO
            ));
            if ($material) {
                foreach ($material as $k => $v) {
                    $v->flag = FLAG_YES;
                    if ($v->update()) {

                    } else {
                        $flag = 1;
                        break;
                    }
                }
                if ($flag == 0) {
                    $transaction->commit();
                    $result['status'] = ERROR_NONE; //状态码
                } else {
                    $transaction->rollBack();
                    $result['status'] = ERROR_SAVE_FAIL; //状态码
                    $result['errMsg'] = '素材删除失败'; //错误信息
                }
            } else {
                $result['status'] = ERROR_NO_DATA; //状态码
                $result['errMsg'] = '该素材不存在'; //错误信息
            }
        } catch (Exception $e) {
            $transaction->rollBack();
            $result['status'] = isset($result['status']) ? $result['status'] : ERROR_EXCEPTION;
            $result['errMsg'] = $e->getMessage(); //错误信息
        }
        return json_encode($result);
    }

    /**
     * 上传图片
     * @param $img     图片地址
     * @param $access_token
     */
    public function uploadImg($img, $access_token)
    {
        $type = "image";
        $filepath = $img;
//      $filepath = 'D:/www/workplace/kuaiguanjia/upload/images/gj/source/'.$img;
        if (class_exists('\CURLFile')) {
            $filedata = array('media' => new \CURLFile(realpath($filepath)));
        } else {
            $filedata = array('media' => '@' . realpath($filepath));
        }
        $url = "http://file.api.weixin.qq.com/cgi-bin/media/upload?access_token=" . $access_token . "&type=" . $type;
        $result = $this->postData($url, $filedata);

        return $result;
    }

    /**
     * 上传图文信息
     * @param
     */
    public function uploadNews($news, $access_token)
    {

        $news_data = htmlspecialchars_decode(urldecode(json_encode(array("articles" => $news))));
        $url = "https://api.weixin.qq.com/cgi-bin/media/uploadnews?access_token=" . $access_token;
        $result = $this->postData($url, $news_data);

        return $result;

    }
    
}