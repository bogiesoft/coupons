<?php
/*	create by gulei
 * 商城常量表
 * 分为东钱湖商城和通用商城
* 规则：
* 1、常量必须加注释，并且需要标明添加者和添加日期还有用处
* 2、命名规范带上同意前缀以作区分
* 3、需要和管理员报备自己添加的常量
* */

/*************************东钱湖商城*********************************/
//短信发送状态 东钱湖下单买票后会通过第三方接口调用下单并发送短信
define('DMALL_ORDER_MESSAGE_SEND_STATUS_SEND', '1');//已发送
define('DMALL_ORDER_MESSAGE_SEND_STATUS_NUSEND', '2');//未发送



//接口调用常量
$GLOBALS['__TIANSHI_PID_AUTHCODE'] = array(
    '125490' => '0e57b361c9caf790c57748c8158d5984',
    '125491' => '3c04c532e2511cb9c89393cee51add70'
);

/******************************通用商城*************************************/