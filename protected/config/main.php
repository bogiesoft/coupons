<?php
$dbconfig = include(dirname(__FILE__).'/dbConfig.php');

return array(
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'name'=>'快快付',
    'defaultController'=>'home',
    'theme' => 'classic',
    'language' => 'zh_cn',
	'preload'=>array('log'),

	'import'=>array(
		'application.models.*',
		'application.components.*',
        'application.extensions.*',
        'application.class.basic.*',
		'application.class.oto.*',
		'application.class.pay.*',
        'application.class.crm.*',
		'application.class.index.*',
        'application.class.api.*',
        'application.extensions.excel.*',
		'application.class.mgr.*',
		'application.class.fx.*',
		'application.extensions.wxjssdk.*',
		'application.class.mall.*',
	    'application.class.dmall.*',
	    'application.class.config.message.*',
	    'application.class.wechat.*',
	    'application.class.oa.*',
		'application.class.mobile.*',
		'application.class.wy.*',
		'application.class.wq.*',
		'application.class.aliServiceWindow.*'
	),
	'modules'=>array(
		'gii'=>array(
			'class'=>'system.gii.GiiModule',
			'password'=>'gii',
			'ipFilters'=>array('127.0.0.1','::1'),
		),
        'mCenter',
        'finance',
		'marketing',
		'crm',
		'statistics',
		'index'
	),
        

	'components'=>array(
		'memcache'=>array(
			'class'=>'CMemCache',
			'servers'=>array(
				array(
						'host'=>'127.0.0.1',
						'port'=>11211,
				),
			),
		),
        'session'=>array(
            'timeout'=>86400,
        ),
        'dbcache'=>array(
            'class'=>' system.caching.CDbCache',
        ),
		'user'=>array(
			'allowAutoLogin'=>true,
		),
		'urlManager'=>array(
		   'urlFormat'=>'path',
 		   'showScriptName' => false,
            'urlSuffix' => '.html',
		   'rules'=>array(
// 				'http://wqmgr.51wanquan.com'=>array('/mgr/index/index', 'urlSuffix'=>'', 'caseSensitive'=>false),
// 				'http://gj.51wanquan.com'=>array('/mCenter/index/index', 'urlSuffix'=>'', 'caseSensitive'=>false),
// 				'http://syt.51wanquan.com'=>array('/syt/index/index', 'urlSuffix'=>'', 'caseSensitive'=>false),				
				
                '<controller:\w+>/<id:\d+>'=>'<controller>/view',
				'<controller:\w+>/<action:\w+>/<id:\d+>'=>'<controller>/<action>',
				'<controller:\w+>/<action:\w+>'=>'<controller>/<action>',
                '<module:\w+>/<controller:\w+>/<action:\w+>' => '<module>/<controller>/<action>',
			),
		),

        'db' => array(
            'class' => 'CDbConnection',
            'connectionString' => sprintf('mysql:host=%s; port=%s; dbname=%s', $dbconfig['dbHost'], $dbconfig['dbPort'], $dbconfig['dbName']),
            'username' => $dbconfig['dbUser'],
            'password' => $dbconfig['dbPassword'],
            'charset' => 'utf8',
            'persistent' => false,
            'tablePrefix' => $dbconfig['tablePrefix'],
            'attributes' => array(
                PDO::ATTR_EMULATE_PREPARES => true,
            ),
            'schemaCacheID' => 'cache',
            'schemaCachingDuration' => 3600 * 24,    // metadata 缓存超时时间(s)
        ),
//        'cache' => array(
//            'class' => 'CFileCache',
//            'directoryLevel' => 2,
//        ),
		'errorHandler'=>array(
			// use 'site/error' action to display errors
			//'errorAction'=>'index/error',
		),
		'log'=>array(
			'class'=>'CLogRouter',
			'routes'=>array(
				array(
					'class'=>'CFileLogRoute',
					'levels'=>'error, warning, info',
				),
				// uncomment the following to show log messages on web pages
				/*
				array(
					'class'=>'CWebLogRoute',
				),
				*/
//                array(
//                    'class' => 'CWebLogRoute',
//                    'levels' => 'trace', //级别为trace
//                    'categories' => 'system.db.*' //只显示关于数据库信息,包括数据库连�?,数据库执行语�?
//                ),
			),
		),
	),
	'params'=>include(dirname(__FILE__).'/params.php')
);