<?php

include_once(dirname(__FILE__) . '/../mainClass.php');
class TianShiApi extends mainClass
{
    const URL      = 'http://dqh.sjdzp.com/Api/Seller/api.json';//接口地址
    
//     const AUTOCODE = '0e57b361c9caf790c57748c8158d5984';//授权码（说明用，实际是32位字符串）
//     const _PID     = '125490';//合作伙伴ID
    

    /**
     * 门票列表
     * @param type $page
     * @param type $size
     * @param type $g_relate
     * @param type $cate_id
     * @param type $zone
     * @param type $item_id
     * @param type $key_word
     */
//     public function TicketList($page, $size, $g_relate, $cate_id, $zone, $item_id, $key_word) 
//     {
//         $url      = self::URL; //接口地址
//         $autoCode = self::AUTOCODE; //授权码（说明用，实际是32位字符串）
//         $_pid     = self::_PID; //合作伙伴ID
//         $post_data = array(            
//             'method'   => 'item_list',
//             'format'   => 'xml',
//             '_pid'     => $_pid,
//             'page'     => $page,//列表页码，缺省获取第1页
//             'size'     => $size,//每页获取数量，缺省每页获取发15条信息
//             'g_relate' => $g_relate,//是否返回关联产品：0不返回(默认)，1只返回关联产品，2返回搜索的联票产品和关联产品。与item_id一起使用，item_id为联票时才生效
//             'cate_id'  => $cate_id,//产品分类ID,缺省无，获取所有分类产品
//             'zone'     => $zone,//产品地区ID,缺省无，不作条件
//             'item_id'  => $item_id,//产品ID，用于获取确定的产品，多个用英文逗号分隔
//             'key_word' => $key_word,//产品标题搜索关键字
//         );
//         ksort($post_data);
//         //平台组合数据，进行签名计算
//         $query = $autoCode;
//         foreach ($post_data as $key => $value) {
//             $query .= "&" . $key . "=" . $value;
//         }
//         $query .= "&" . $autoCode;
//         $_sign = md5($query);
//         $post_data['_sig'] = $_sign; //把签名加入到数据中
//         echo $this -> http($url,$post_data);        
//     }
    
    /**
     * 订单列表
     * @param type $page
     * @param type $size
     * @param type $item_id
     * @param type $begin
     * @param type $end
     * @param type $g_relate
     * @param type $orders_id
     */
//     public function OrderList($page, $size, $item_id, $begin, $end, $g_relate, $orders_id)
//     {
//         $url      = self::URL; //接口地址
//         $autoCode = self::AUTOCODE; //授权码（说明用，实际是32位字符串）
//         $_pid     = self::_PID; //合作伙伴ID
//         $post_data = array(            
//             'method'    => 'orders_list',
//             'format'    => '',
//             '_pid'      => $_pid,
//             'page'      => $page,//列表页码，缺省获取第1页
//             'size'      => $size,//每页获取数量，缺省每页获取发15条信息
//             'item_id'   => $item_id,//产品ID，缺省不做条件
//             'begin'     => $begin,//开始时间戳，与end连用，缺省30天前时间戳
//             'end'       => $end,//结束时间戳，与begin连用，缺省当前时间戳
//             'g_relate'  => $g_relate,//是否返回关联订单：0不返回(默认)，1返回关联订单。与orders_id一起使用，orders_id为联票订单时才生效
//             'orders_id' => $orders_id,//订单ID，用于获取确定的订单，多个用英文逗号分隔        
//         );
//         ksort($post_data);
//         //平台组合数据，进行签名计算
//         $query = $autoCode;
//         foreach ($post_data as $key => $value) {
//             $query .= "&" . $key . "=" . $value;
//         }
//         $query .= "&" . $autoCode;
//         $_sign = md5($query);
//         $post_data['_sig'] = $_sign; //把签名加入到数据中
//         return $this -> http($url,$post_data); 
//     }
    
    
    
    
    /**
     * 退票接口(如果对应的订单退票许可状态为“管理员审核退票”，则接口为“申请退票接口”)
     * @param type $orders_id
     * @param type $size
     */
    public function Refund($item_id,$orders_id, $size)
    {
        $arr = explode('_', $item_id);
        $url      = self::URL; //接口地址
//         $autoCode = self::AUTOCODE; //授权码（说明用，实际是32位字符串）
        $_pid     = trim($arr[1]); //合作伙伴ID
        $post_data = array(            
            'method'          => 'item_refund',
            'format'          => 'json',
            '_pid'            => $_pid,            
            'orders_id'       => $orders_id,//必填 要退票的订单号            
            'size'            => $size,//退票数,缺省退票所有未使用票数            
        );
        ksort($post_data);
        //平台组合数据，进行签名计算
        $query = $GLOBALS['__TIANSHI_PID_AUTHCODE'][$arr[1]];
        foreach ($post_data as $key => $value) {
            $query .= "&" . $key . "=" . $value;
        }
        $query .= "&" . $GLOBALS['__TIANSHI_PID_AUTHCODE'][$arr[1]];
        $_sign = md5($query);
        $post_data['_sig'] = $_sign; //把签名加入到数据中
        return $this -> http($url,$post_data); 
    }
    
    
    /**
     * 码号查询接口
     * @param type $code
     */
//     public function OrdersQuery($code)
//     {
//         $url      = self::URL; //接口地址
//         $autoCode = self::AUTOCODE; //授权码（说明用，实际是32位字符串）
//         $_pid     = self::_PID; //合作伙伴ID
//         $post_data = array(            
//             'method'          => 'orders_query',
//             'format'          => 'json',
//             '_pid'            => $_pid,            
//             'code'            => $code,//必填  码号
//         );
//         ksort($post_data);
//         //平台组合数据，进行签名计算
//         $query = $autoCode;
//         foreach ($post_data as $key => $value) {
//             $query .= "&" . $key . "=" . $value;
//         }
//         $query .= "&" . $autoCode;
//         $_sign = md5($query);
//         $post_data['_sig'] = $_sign; //把签名加入到数据中
//         return $this -> http($url,$post_data); 
//     }
    

   

    /**
     * 自动提交表单方法
     */
    private function create_html($arrayData, $actionUrl){
        $html = '<html><head><meta http-equiv="Content-Type" content="text/html;charset=utf-8"/></head>'.
            '<body onload="javascript:document.pay_form.submit();">'.
            '<form id="pay_form" name="pay_form" action="'.$actionUrl.'" method="post">';
        foreach($arrayData as $k => $v){
            $html.='<input type="hidden" name="'.$k.'" id="'.$k.'" value="'.$v.'" /><br/>';
        }
        $html.= '</form></body></html>';
        return $html;
    }
    
    /**
     * 模拟提交数据函数
     * @param type $url
     * @param type $post_data
     */
    function Http($url,$post_data){ //       
        $ch = curl_init();  
        curl_setopt($ch, CURLOPT_POST, 1);  
        curl_setopt($ch, CURLOPT_URL,$url);  
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);  
        ob_start();  
        curl_exec($ch);  
        $result = ob_get_contents() ;  
        ob_end_clean();
        return $result;   
    }
}

