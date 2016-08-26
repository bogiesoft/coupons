<?php
/**错误码**/

//接口调用状态
define('APPLY_CLASS_SUCCESS', '10000');//接口调用成功
define('APPLY_CLASS_FAIL', '10001');//接口调用失败

//参数错误
define('APPLY_ERROR_PARAMETER_MISS', '20100');//参数缺失
define('APPLY_ERROR_PARAMETER_FORMAT', '20101');//参数格式不正确
define('APPLY_ERROR_PARAMETER_TOO_LONG', '20102');//参数长度过长
define('APPLY_ERROR_PARAMETER_TOO_SHORT', '20103');//参数格式过短

//数据库操作
define('APPLY_ERROR_DATA_BASE_ADD', '20200');//数据库添加失败
define('APPLY_ERROR_DATA_BASE_EDIT', '20201');//数据库修改失败
define('APPLY_ERROR_DATA_BASE_SELECT', '20202');//数据库查询失败
define('APPLY_ERROR_DATA_BASE_DELETE', '20203');//数据库删除失败