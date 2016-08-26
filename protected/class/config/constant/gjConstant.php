<?php
/*	create by gulei
 * 管家常量表
* 规则：
* 1、常量必须加注释，并且需要标明添加者和添加日期还有用处
* 2、命名规范带上同意前缀以作区分
* 3、需要和管理员报备自己添加的常量
* */

/**************门店*****************/
//门店同步支付宝口碑门店类型  by gulei 2016-1-14
define('STORE_ALIPAY_SYNC_TYPE_SYNC', '1');//同步创建口碑门店
define('STORE_ALIPAY_SYNC_TYPE_RELATION', '2');//关联已有口碑门店
define('STORE_ALIPAY_SYNC_TYPE_NOSYNC_NO_RELATION', '3');//不同步创建也不关联已有

//门店同步口碑门店审核状态 by gulei 2016-1-14
define('STORE_ALIPAY_SYNC_STATUS_NONE', '1');//无审核
define('STORE_ALIPAY_SYNC_STATUS_AUDITING', '2');//审核中
define('STORE_ALIPAY_SYNC_STATUS_REJECT', '3');//审核驳回
define('STORE_ALIPAY_SYNC_STATUS_PASS', '4');//审核通过
$GLOBALS['__STORE_ALIPAY_SYNC_STATUS'] = array(
		STORE_ALIPAY_SYNC_STATUS_NONE => '无审核',
		STORE_ALIPAY_SYNC_STATUS_AUDITING => '审核中',
		STORE_ALIPAY_SYNC_STATUS_REJECT => '审核驳回',
		STORE_ALIPAY_SYNC_STATUS_PASS => '审核通过'
);

//支付宝口碑门店全类目 by gulei 2016-1-16
$GLOBALS['__ALIPAY_KOUBEI_STORE_ALL_CATEGORY'] = array(
		array(
				"id" => "2015050700000000",
				"name" => "美食(口碑)",
				"parentId" => null,
				"rootId" => "2015050700000000",
				"categoryList" => array(
						array(
								"id" => "2015050700000001",
								"name" => "中餐",
								"parentId" => "2015050700000000",
								"rootId" => "2015050700000000",
								"categoryList" => array(
										array(
												"id" => "2015050700000010",
												"name" => "川菜",
												"parentId" => "2015050700000001",
												"rootId" => "2015050700000000",
												"categoryList" => null
										),
										array(
												"id" => "2015050700000022",
												"name" => "其它地方菜",
												"parentId" => "2015050700000001",
												"rootId" => "2015050700000000",
												"categoryList" => null
										),
										array(
												"id" => "2015050700000012",
												"name" => "湖北菜",
												"parentId" => "2015050700000001",
												"rootId" => "2015050700000000",
												"categoryList" => null
										),
										array(
												"id" => "2015050700000021",
												"name" => "海鲜",
												"parentId" => "2015050700000001",
												"rootId" => "2015050700000000",
												"categoryList" => null
										),
										array(
												"id" => "2015050700000011",
												"name" => "湘菜",
												"parentId" => "2015050700000001",
												"rootId" => "2015050700000000",
												"categoryList" => null
										),
										array(
												"id" => "2015050700000020",
												"name" => "香锅/烤鱼",
												"parentId" => "2015050700000001",
												"rootId" => "2015050700000000",
												"categoryList" => null
										),
										array(
												"id" => "2015052200000062",
												"name" => "粤菜",
												"parentId" => "2015050700000001",
												"rootId" => "2015050700000000",
												"categoryList" => null
										),
										array(
												"id" => "2016010600120962",
												"name" => "海南菜",
												"parentId" => "2015050700000001",
												"rootId" => "2015050700000000",
												"categoryList" => null
										),
										array(
												"id" => "2015050700000017",
												"name" => "贵州菜",
												"parentId" => "2015050700000001",
												"rootId" => "2015050700000000",
												"categoryList" => null
										),
										array(
												"id" => "2015050700000018",
												"name" => "西北菜",
												"parentId" => "2015050700000001",
												"rootId" => "2015050700000000",
												"categoryList" => null
										),
										array(
												"id" => "2015050700000019",
												"name" => "东北菜",
												"parentId" => "2015050700000001",
												"rootId" => "2015050700000000",
												"categoryList" => null
										),
										array(
												"id" => "2015050700000013",
												"name" => "台湾菜",
												"parentId" => "2015050700000001",
												"rootId" => "2015050700000000",
												"categoryList" => null
										),
										array(
												"id" => "2015050700000014",
												"name" => "新疆菜",
												"parentId" => "2015050700000001",
												"rootId" => "2015050700000000",
												"categoryList" => null
										),
										array(
												"id" => "2015050700000015",
												"name" => "江浙菜",
												"parentId" => "2015050700000001",
												"rootId" => "2015050700000000",
												"categoryList" => null
										),
										array(
												"id" => "2015050700000016",
												"name" => "云南菜",
												"parentId" => "2015050700000001",
												"rootId" => "2015050700000000",
												"categoryList" => null
										),
										array(
												"id" => "2016031800154367",
												"name" => "徽菜",
												"parentId" => "2015050700000001",
												"rootId" => "2015050700000000",
												"categoryList" => null
										),
                                        array(
                                                "id" => "2016031800152626",
                                                "name" => "鲁菜",
                                                "parentId" => "2015050700000001",
                                                "rootId" => "2015050700000000",
                                                "categoryList" => null
                                        ),
                                        array(
                                                "id" => "2016031800154368",
                                                "name" => "晋菜",
                                                "parentId" => "2015050700000001",
                                                "rootId" => "2015050700000000",
                                                "categoryList" => null
                                        ),
                                        array(
                                                "id" => "2016031800155597",
                                                "name" => "豫菜",
                                                "parentId" => "2015050700000001",
                                                "rootId" => "2015050700000000",
                                                "categoryList" => null
                                        ),
                                        array(
                                                "id" => "2016031800156722",
                                                "name" => "闽菜",
                                                "parentId" => "2015050700000001",
                                                "rootId" => "2015050700000000",
                                                "categoryList" => null
                                        ),
                                        array(
                                                "id" => "2016031800158042",
                                                "name" => "上海本帮菜",
                                                "parentId" => "2015050700000001",
                                                "rootId" => "2015050700000000",
                                                "categoryList" => null
                                        ),
                                        array(
                                                "id" => "2016031800159500",
                                                "name" => "淮扬菜",
                                                "parentId" => "2015050700000001",
                                                "rootId" => "2015050700000000",
                                                "categoryList" => null
                                        ),
								)
						),
						array(
								"id" => "2015050700000004",
								"name" => "快餐",
								"parentId" => "2015050700000000",
								"rootId" => "2015050700000000",
								"categoryList" => array(
										array(
												"id" => "2015050700000038",
												"name" => "西式快餐",
												"parentId" => "2015050700000004",
												"rootId" => "2015050700000000",
												"categoryList" => null
										),
										array(
												"id" => "2015050700000039",
												"name" => "中式快餐",
												"parentId" => "2015050700000004",
												"rootId" => "2015050700000000",
												"categoryList" => null
										),
										array(
												"id" => "2015061690000030",
												"name" => "其它快餐",
												"parentId" => "2015050700000004",
												"rootId" => "2015050700000000",
												"categoryList" => null
										)
								)
						),
						array(
								"id" => "2015050700000005",
								"name" => "休闲食品",
								"parentId" => "2015050700000000",
								"rootId" => "2015050700000000",
								"categoryList" => array(
										array(
												"id" => "2015050700000040",
												"name" => "零食",
												"parentId" => "2015050700000005",
												"rootId" => "2015050700000000",
												"categoryList" => null
										),
										array(
												"id" => "2015050700000041",
												"name" => "生鲜水果",
												"parentId" => "2015050700000005",
												"rootId" => "2015050700000000",
												"categoryList" => null
										),
										array(
												"id" => "2015061690000029",
												"name" => "其它休闲食品",
												"parentId" => "2015050700000005",
												"rootId" => "2015050700000000",
												"categoryList" => null
										),
										array(
												"id" => "2016062900190066",
												"name" => "美食特产",
												"parentId" => "2015050700000005",
												"rootId" => "2015050700000000",
												"categoryList" => null
										)
								)
						),
						array(
								"id" => "2015050700000002",
								"name" => "火锅",
								"parentId" => "2015050700000000",
								"rootId" => "2015050700000000",
								"categoryList" => array(
										array(
												"id" => "2015050700000023",
												"name" => "麻辣烫/串串香",
												"parentId" => "2015050700000002",
												"rootId" => "2015050700000000",
												"categoryList" => null
										),
										array(
												"id" => "2015050700000030",
												"name" => "炭火锅",
												"parentId" => "2015050700000002",
												"rootId" => "2015050700000000",
												"categoryList" => null
										),
										array(
												"id" => "2015050700000028",
												"name" => "鱼火锅",
												"parentId" => "2015050700000002",
												"rootId" => "2015050700000000",
												"categoryList" => null
										),
										array(
												"id" => "2015050700000029",
												"name" => "羊蝎子",
												"parentId" => "2015050700000002",
												"rootId" => "2015050700000000",
												"categoryList" => null
										),
										array(
												"id" => "2015050700000033",
												"name" => "其它火锅",
												"parentId" => "2015050700000002",
												"rootId" => "2015050700000000",
												"categoryList" => null
										),
										array(
												"id" => "2015050700000026",
												"name" => "老北京涮羊肉",
												"parentId" => "2015050700000002",
												"rootId" => "2015050700000000",
												"categoryList" => null
										),
										array(
												"id" => "2015050700000027",
												"name" => "港式火锅",
												"parentId" => "2015050700000002",
												"rootId" => "2015050700000000",
												"categoryList" => null
										),
										array(
												"id" => "2015050700000031",
												"name" => "韩式火锅",
												"parentId" => "2015050700000002",
												"rootId" => "2015050700000000",
												"categoryList" => null
										),
										array(
												"id" => "2015050700000024",
												"name" => "川味/重庆火锅",
												"parentId" => "2015050700000002",
												"rootId" => "2015050700000000",
												"categoryList" => null
										),
										array(
												"id" => "2015050700000032",
												"name" => "豆捞",
												"parentId" => "2015050700000002",
												"rootId" => "2015050700000000",
												"categoryList" => null
										),
										array(
												"id" => "2015050700000025",
												"name" => "云南火锅",
												"parentId" => "2015050700000002",
												"rootId" => "2015050700000000",
												"categoryList" => null
										)
								)
						),
						array(
								"id" => "2015050700000003",
								"name" => "小吃",
								"parentId" => "2015050700000000",
								"rootId" => "2015050700000000",
								"categoryList" => array(
										array(
												"id" => "2015050700000037",
												"name" => "其它小吃",
												"parentId" => "2015050700000003",
												"rootId" => "2015050700000000",
												"categoryList" => null
										),
										array(
												"id" => "2015050700000036",
												"name" => "米粉/米线",
												"parentId" => "2015050700000003",
												"rootId" => "2015050700000000",
												"categoryList" => null
										),
										array(
												"id" => "2015050700000035",
												"name" => "面点",
												"parentId" => "2015050700000003",
												"rootId" => "2015050700000000",
												"categoryList" => null
										),
										array(
												"id" => "2015050700000034",
												"name" => "熟食",
												"parentId" => "2015050700000003",
												"rootId" => "2015050700000000",
												"categoryList" => null
										)
								)
						),
						array(
								"id" => "2015050700000008",
								"name" => "汤/粥/煲/砂锅/炖菜",
								"parentId" => "2015050700000000",
								"rootId" => "2015050700000000",
								"categoryList" => array(
										array(
												"id" => "2015061690000025",
												"name" => "其它",
												"parentId" => "2015050700000008",
												"rootId" => "2015050700000000",
												"categoryList" => null
										),
										array(
												"id" => "2015050700000055",
												"name" => "砂锅/煲类/炖菜",
												"parentId" => "2015050700000008",
												"rootId" => "2015050700000000",
												"categoryList" => null
										),
										array(
												"id" => "2015050700000053",
												"name" => "粥",
												"parentId" => "2015050700000008",
												"rootId" => "2015050700000000",
												"categoryList" => null
										),
										array(
												"id" => "2015050700000054",
												"name" => "汤",
												"parentId" => "2015050700000008",
												"rootId" => "2015050700000000",
												"categoryList" => null
										)
								)
						),
						array(
								"id" => "2015050700000009",
								"name" => "其它美食",
								"parentId" => "2015050700000000",
								"rootId" => "2015050700000000",
								"categoryList" => array(
										array(
												"id" => "2015050700000059",
												"name" => "日韩料理",
												"parentId" => "2015050700000009",
												"rootId" => "2015050700000000",
												"categoryList" => null
										),
										array(
												"id" => "2015050700000058",
												"name" => "西餐",
												"parentId" => "2015050700000009",
												"rootId" => "2015050700000000",
												"categoryList" => null
										),
										array(
												"id" => "2015050700000057",
												"name" => "创意菜",
												"parentId" => "2015050700000009",
												"rootId" => "2015050700000000",
												"categoryList" => null
										),
										array(
												"id" => "2015050700000056",
												"name" => "自助餐",
												"parentId" => "2015050700000009",
												"rootId" => "2015050700000000",
												"categoryList" => null
										),
										array(
												"id" => "2015061690000027",
												"name" => "其它餐饮美食",
												"parentId" => "2015050700000009",
												"rootId" => "2015050700000000",
												"categoryList" => null
										),
										array(
												"id" => "2015050700000060",
												"name" => "东南亚菜",
												"parentId" => "2015050700000009",
												"rootId" => "2015050700000000",
												"categoryList" => null
										),
										array(
												"id" => "2015050700000061",
												"name" => "素食",
												"parentId" => "2015050700000009",
												"rootId" => "2015050700000000",
												"categoryList" => null
										),
                                        array(
                                                "id" => "2016031800159501",
                                                "name" => "清真菜",
                                                "parentId" => "2015050700000009",
                                                "rootId" => "2015050700000000",
                                                "categoryList" => null
                                        ),
                                        array(
                                                "id" => "2016031800160959",
                                                "name" => "茶餐厅",
                                                "parentId" => "2015050700000009",
                                                "rootId" => "2015050700000000",
                                                "categoryList" => null
                                        ),
                                        array(
                                                "id" => "2016031800162476",
                                                "name" => "土菜/农家菜",
                                                "parentId" => "2015050700000009",
                                                "rootId" => "2015050700000000",
                                                "categoryList" => null
                                        ),
                                        array(
                                                "id" => "2016070500193665",
                                                "name" => "采摘/农家乐",
                                                "parentId" => "2015050700000009",
                                                "rootId" => "2015050700000000",
                                                "categoryList" => null
                                        )
								)
						),
						array(
								"id" => "2015050700000006",
								"name" => "烘焙糕点",
								"parentId" => "2015050700000000",
								"rootId" => "2015050700000000",
								"categoryList" => array(
										array(
												"id" => "2015050700000046",
												"name" => "面包",
												"parentId" => "2015050700000006",
												"rootId" => "2015050700000000",
												"categoryList" => null
										),
										array(
												"id" => "2015061690000028",
												"name" => "其它烘焙糕点",
												"parentId" => "2015050700000006",
												"rootId" => "2015050700000000",
												"categoryList" => null
										),
										array(
												"id" => "2015050700000044",
												"name" => "蛋糕",
												"parentId" => "2015050700000006",
												"rootId" => "2015050700000000",
												"categoryList" => null
										),
								)
						),
						array(
								"id" => "2015050700000007",
								"name" => "烧烤",
								"parentId" => "2015050700000000",
								"rootId" => "2015050700000000",
								"categoryList" => array(
										array(
												"id" => "2015050700000049",
												"name" => "拉美烧烤",
												"parentId" => "2015050700000007",
												"rootId" => "2015050700000000",
												"categoryList" => null
										),
										array(
												"id" => "2015050700000048",
												"name" => "中式烧烤",
												"parentId" => "2015050700000007",
												"rootId" => "2015050700000000",
												"categoryList" => null
										),
										array(
												"id" => "2015061690000026",
												"name" => "其它烧烤",
												"parentId" => "2015050700000007",
												"rootId" => "2015050700000000",
												"categoryList" => null
										),
										array(
												"id" => "2015050700000051",
												"name" => "铁板烧",
												"parentId" => "2015050700000007",
												"rootId" => "2015050700000000",
												"categoryList" => null
										),
										array(
												"id" => "2015050700000052",
												"name" => "韩式烧烤",
												"parentId" => "2015050700000007",
												"rootId" => "2015050700000000",
												"categoryList" => null
										),
										array(
												"id" => "2015050700000050",
												"name" => "日式烧烤",
												"parentId" => "2015050700000007",
												"rootId" => "2015050700000000",
												"categoryList" => null
										)
								)
						),
                        array(
                                "id" => "休闲茶饮",
                                "name" => "休闲茶饮",
                                "parentId" => "2015050700000000",
                                "rootId" => "2015050700000000",
                                "categoryList" => array(
                                        array(
                                                "id" => "2015050700000042",
                                                "name" => "咖啡",
                                                "parentId" => "休闲茶饮",
                                                "rootId" => "2015050700000000",
                                                "categoryList" => null
                                        ),
                                        array(
                                                "id" => "2015050700000043",
                                                "name" => "奶茶",
                                                "parentId" => "休闲茶饮",
                                                "rootId" => "2015050700000000",
                                                "categoryList" => null
                                        ),
                                        array(
                                                "id" => "2015050700000045",
                                                "name" => "冰激凌",
                                                "parentId" => "休闲茶饮",
                                                "rootId" => "2015050700000000",
                                                "categoryList" => null
                                        ),
                                        array(
                                                "id" => "2015050700000047",
                                                "name" => "饮品/甜点",
                                                "parentId" => "休闲茶饮",
                                                "rootId" => "2015050700000000",
                                                "categoryList" => null
                                        ),
                                        array(
                                                "id" => "2015062600011157",
                                                "name" => "咖啡厅",
                                                "parentId" => "休闲茶饮",
                                                "rootId" => "2015050700000000",
                                                "categoryList" => null
                                        ),
                                        array(
                                                "id" => "2015091100061275",
                                                "name" => "酒吧",
                                                "parentId" => "休闲茶饮",
                                                "rootId" => "2015050700000000",
                                                "categoryList" => null
                                        )
                                )
                        )
				)
		),
		array(
				"id" => "2015080600000001",
				"name" => "航旅",
				"parentId" => "",
				"rootId" => "2015080600000001",
				"categoryList" => array(
						array(
								"id" => "2016062900190313",
								"name" => "酒店式公寓",
								"parentId" => "2015080600000001",
								"rootId" => "2015080600000001",
								"categoryList" => null
						),
						array(
								"id" => "度假村",
								"name" => "度假村",
								"parentId" => "2015080600000001",
								"rootId" => "2015080600000001",
								"categoryList" => array(
										array(
												"id" => "2016062900190330",
												"name" => "度假别墅服务",
												"parentId" => "度假村",
												"rootId" => "2015080600000001",
												"categoryList" => null
										),
										array(
												"id" => "2016062900190331",
												"name" => "运动和娱乐露营",
												"parentId" => "度假村",
												"rootId" => "2015080600000001",
												"categoryList" => null
										),
                                        array(
                                                "id" => "2016062900190332",
                                                "name" => "活动房车和野营",
                                                "parentId" => "度假村",
                                                "rootId" => "2015080600000001",
                                                "categoryList" => null
                                        )
								)
						),
						array(
								"id" => "景区",
								"name" => "景区",
								"parentId" => "2015080600000001",
								"rootId" => "2015080600000001",
								"categoryList" => array(
										array(
												"id" => "2015112300087778",
												"name" => "门票",
												"parentId" => "景区",
												"rootId" => "2015080600000001",
												"categoryList" => null
										),
										array(
												"id" => "2015112300087779",
												"name" => "景区生活服务",
												"parentId" => "景区",
												"rootId" => "2015080600000001",
												"categoryList" => null
										),
										array(
												"id" => "2015112300100346",
												"name" => "景区购物",
												"parentId" => "景区",
												"rootId" => "2015080600000001",
												"categoryList" => null
										)
								)
						),
						array(
								"id" => "客栈",
								"name" => "客栈",
								"parentId" => "2015080600000001",
								"rootId" => "2015080600000001",
								"categoryList" => array(
										array(
												"id" => "2015112300103487",
												"name" => "单体",
												"parentId" => "客栈",
												"rootId" => "2015080600000001",
												"categoryList" => null
										),
										array(
												"id" => "2015112300105211",
												"name" => "连锁",
												"parentId" => "客栈",
												"rootId" => "2015080600000001",
												"categoryList" => null
										)
								)
						),
						array(
								"id" => "汽车站",
								"name" => "汽车站",
								"parentId" => "2015080600000001",
								"rootId" => "2015080600000001",
								"categoryList" => array(
										array(
												"id" => "2015112300107119",
												"name" => "汽车票",
												"parentId" => "汽车站",
												"rootId" => "2015080600000001",
												"categoryList" => null
										),
										array(
												"id" => "2015112300108166",
												"name" => "汽车站购物",
												"parentId" => "汽车站",
												"rootId" => "2015080600000001",
												"categoryList" => null
										),
										array(
												"id" => "2015112300110076",
												"name" => "汽车站生活服务",
												"parentId" => "汽车站",
												"rootId" => "2015080600000001",
												"categoryList" => null
										)
								)
						),
						array(
								"id" => "酒店",
								"name" => "酒店",
								"parentId" => "2015080600000001",
								"rootId" => "2015080600000001",
								"categoryList" => array(
										array(
												"id" => "2015112300111726",
												"name" => "酒店单体",
												"parentId" => "酒店",
												"rootId" => "2015080600000001",
												"categoryList" => null
										),
										array(
												"id" => "2015112300113179",
												"name" => "酒店连锁",
												"parentId" => "酒店",
												"rootId" => "2015080600000001",
												"categoryList" => null
										)
								)
						),
						array(
								"id" => "旅行社",
								"name" => "旅行社",
								"parentId" => "2015080600000001",
								"rootId" => "2015080600000001",
								"categoryList" => array(
										array(
												"id" => "2015112300118105",
												"name" => "旅行社",
												"parentId" => "旅行社",
												"rootId" => "2015080600000001",
												"categoryList" => null
										)
								)
						),
						array(
								"id" => "航空票务",
								"name" => "航空票务",
								"parentId" => "2015080600000001",
								"rootId" => "2015080600000001",
								"categoryList" => array(
										array(
												"id" => "2016062900190333",
												"name" => "航空公司",
												"parentId" => "航空票务",
												"rootId" => "2015080600000001",
												"categoryList" => null
										),
										array(
												"id" => "2016062900190335",
												"name" => "机票平台",
												"parentId" => "航空票务",
												"rootId" => "2015080600000001",
												"categoryList" => null
										),
										array(
												"id" => "2016062900190336",
												"name" => "航空系统商",
												"parentId" => "航空票务",
												"rootId" => "2015080600000001",
												"categoryList" => null
										),
								)
						),
						array(
								"id" => "2016062900190327",
								"name" => "货运代理/报关行",
								"parentId" => "2015080600000001",
								"rootId" => "2015080600000001",
								"categoryList" => null
						),
						array(
								"id" => "2016062900190317",
								"name" => "铁路客运",
								"parentId" => "2015080600000001",
								"rootId" => "2015080600000001",
								"categoryList" => null
						),
                        array(
                                "id" => "2016062900190318",
                                "name" => "公共交通",
                                "parentId" => "2015080600000001",
                                "rootId" => "2015080600000001",
                                "categoryList" => null
                        ),
                        array(
                                "id" => "2016062900190319",
                                "name" => "出租车服务",
                                "parentId" => "2015080600000001",
                                "rootId" => "2015080600000001",
                                "categoryList" => null
                        ),
                        array(
                                "id" => "2016062900190320",
                                "name" => "长途公路客运",
                                "parentId" => "2015080600000001",
                                "rootId" => "2015080600000001",
                                "categoryList" => null
                        ),
                        array(
                                "id" => "2016062900190328",
                                "name" => "游轮/巡游航线",
                                "parentId" => "2015080600000001",
                                "rootId" => "2015080600000001",
                                "categoryList" => null
                        ),
                        array(
                                "id" => "2016062900190321",
                                "name" => "出租船只",
                                "parentId" => "2015080600000001",
                                "rootId" => "2015080600000001",
                                "categoryList" => null
                        ),
                        array(
                                "id" => "2016062900190329",
                                "name" => "船舶/海运服务",
                                "parentId" => "2015080600000001",
                                "rootId" => "2015080600000001",
                                "categoryList" => null
                        ),
                        array(
                                "id" => "2016062900190322",
                                "name" => "路桥通行费",
                                "parentId" => "2015080600000001",
                                "rootId" => "2015080600000001",
                                "categoryList" => null
                        )
				)
		),
		array(
				"id" => "2015063000013612",
				"name" => "美发/美容/美甲(口碑)",
				"parentId" => "",
				"rootId" => "2015063000013612",
				"categoryList" => array(
						array(
								"id" => "2015063000017354",
								"name" => "美甲/手护",
								"parentId" => "2015063000013612",
								"rootId" => "2015063000013612",
								"categoryList" => null
						),
						array(
								"id" => "2015063000019130",
								"name" => "SPA/美容/美体",
								"parentId" => "2015063000013612",
								"rootId" => "2015063000013612",
								"categoryList" => null
						),
						array(
								"id" => "2015101000066113",
								"name" => "美容美发",
								"parentId" => "2015063000013612",
								"rootId" => "2015063000013612",
								"categoryList" => null
						),
						array(
								"id" => "2015101000064159",
								"name" => "美容美甲",
								"parentId" => "2015063000013612",
								"rootId" => "2015063000013612",
								"categoryList" => null
						),
						array(
								"id" => "2015101000067631",
								"name" => "美发美甲",
								"parentId" => "2015063000013612",
								"rootId" => "2015063000013612",
								"categoryList" => null
						),
						array(
								"id" => "2015063000015529",
								"name" => "美发",
								"parentId" => "2015063000013612",
								"rootId" => "2015063000013612",
								"categoryList" => null
						),
                        array(
                                "id" => "2016062900190068",
                                "name" => "彩妆造型",
                                "parentId" => "2015063000013612",
                                "rootId" => "2015063000013612",
                                "categoryList" => null
                        ),
                        array(
                                "id" => "2016062900190069",
                                "name" => "美睫",
                                "parentId" => "2015063000013612",
                                "rootId" => "2015063000013612",
                                "categoryList" => null
                        ),
                        array(
                                "id" => "2016062900190070",
                                "name" => "产后塑形",
                                "parentId" => "2015063000013612",
                                "rootId" => "2015063000013612",
                                "categoryList" => null
                        )
				)
		),
		array(
				"id" => "教育培训",
				"name" => "教育培训",
				"parentId" => "",
				"rootId" => "教育培训",
				"categoryList" => array(
                        array(
                                "id" => "2015112300119923",
                                "name" => "职业技术培训",
                                "parentId" => "教育培训",
                                "rootId" => "教育培训",
                                "categoryList" => null
                        ),
                        array(
                                "id" => "2016062900190282",
                                "name" => "外语",
                                "parentId" => "教育培训",
                                "rootId" => "教育培训",
                                "categoryList" => null
                        ),
                        array(
                                "id" => "2016062900190283",
                                "name" => "音乐",
                                "parentId" => "教育培训",
                                "rootId" => "教育培训",
                                "categoryList" => null
                        ),
                        array(
                                "id" => "2016062900190284",
                                "name" => "升学辅导",
                                "parentId" => "教育培训",
                                "rootId" => "教育培训",
                                "categoryList" => null
                        ),
                        array(
                                "id" => "2016062900190285",
                                "name" => "体育",
                                "parentId" => "教育培训",
                                "rootId" => "教育培训",
                                "categoryList" => null
                        ),
                        array(
                                "id" => "2016062900190286",
                                "name" => "美术",
                                "parentId" => "教育培训",
                                "rootId" => "教育培训",
                                "categoryList" => null
                        ),
                        array(
                                "id" => "2016062900190287",
                                "name" => "留学",
                                "parentId" => "教育培训",
                                "rootId" => "教育培训",
                                "categoryList" => null
                        ),
                        array(
                                "id" => "2016062900190288",
                                "name" => "驾校",
                                "parentId" => "教育培训",
                                "rootId" => "教育培训",
                                "categoryList" => null
                        ),
                        array(
                                "id" => "2016062900190289",
                                "name" => "兴趣生活",
                                "parentId" => "教育培训",
                                "rootId" => "教育培训",
                                "categoryList" => null
                        ),
                        array(
                                "id" => "教育院校",
                                "name" => "教育院校",
                                "parentId" => "教育培训",
                                "rootId" => "教育培训",
                                "categoryList" => array(
                                        array(
                                                "id" => "2016062900190291",
                                                "name" => "中小学校",
                                                "parentId" => "教育院校",
                                                "rootId" => "教育培训",
                                                "categoryList" => null
                                        ),
                                        array(
                                                "id" => "2016062900190292",
                                                "name" => "大学与学院",
                                                "parentId" => "教育院校",
                                                "rootId" => "教育培训",
                                                "categoryList" => null
                                        ),
                                        array(
                                                "id" => "2016062900190293",
                                                "name" => "成人教育/函授",
                                                "parentId" => "教育院校",
                                                "rootId" => "教育培训",
                                                "categoryList" => null
                                        ),
                                        array(
                                                "id" => "2016062900190294",
                                                "name" => "商业/文秘学校",
                                                "parentId" => "教育院校",
                                                "rootId" => "教育培训",
                                                "categoryList" => null
                                        ),
                                        array(
                                                "id" => "2016062900190295",
                                                "name" => "儿童保育/学前",
                                                "parentId" => "教育院校",
                                                "rootId" => "教育培训",
                                                "categoryList" => null
                                        )
                                )
                        )
                )
		),
		array(
				"id" => "2015062600004525",
				"name" => "休闲娱乐(口碑)",
				"parentId" => "",
				"rootId" => "2015062600004525",
				"categoryList" => array(
						array(
								"id" => "2015090700041394",
								"name" => "棋牌休闲",
								"parentId" => "2015062600004525",
								"rootId" => "2015062600004525",
								"categoryList" => null
						),
						array(
								"id" => "2015063000012448",
								"name" => "中医养生",
								"parentId" => "2015062600004525",
								"rootId" => "2015062600004525",
								"categoryList" => null
						),
						array(
								"id" => "2015090700039570",
								"name" => "足疗按摩",
								"parentId" => "2015062600004525",
								"rootId" => "2015062600004525",
								"categoryList" => null
						),
						array(
								"id" => "2016012000122673",
								"name" => "洗浴/桑拿会所",
								"parentId" => "2015062600004525",
								"rootId" => "2015062600004525",
								"categoryList" => null
						),
						array(
								"id" => "2016012000124191",
								"name" => "网吧网咖",
								"parentId" => "2015062600004525",
								"rootId" => "2015062600004525",
								"categoryList" => null
						),
						array(
								"id" => "2016012000126089",
								"name" => "游乐游艺",
								"parentId" => "2015062600004525",
								"rootId" => "2015062600004525",
								"categoryList" => null
						),
						array(
								"id" => "2016012000128641",
								"name" => "密室",
								"parentId" => "2015062600004525",
								"rootId" => "2015062600004525",
								"categoryList" => null
						),
						array(
								"id" => "2016012000129781",
								"name" => "桌面游戏",
								"parentId" => "2015062600004525",
								"rootId" => "2015062600004525",
								"categoryList" => null
						),
						array(
								"id" => "2016012000130831",
								"name" => "真人CS",
								"parentId" => "2015062600004525",
								"rootId" => "2015062600004525",
								"categoryList" => null
						),
						array(
								"id" => "2016012000127534",
								"name" => "图书馆",
								"parentId" => "2015062600004525",
								"rootId" => "2015062600004525",
								"categoryList" => null
						)
				)
		),
		array(
				"id" => "2015110500071135",
				"name" => "运动健身(口碑)",
				"parentId" => "",
				"rootId" => "2015110500071135",
				"categoryList" => array(
						array(
								"id" => "2016051600183429",
								"name" => "壁球馆",
								"parentId" => "2015110500071135",
								"rootId" => "2015110500071135",
								"categoryList" => null
						),
						array(
								"id" => "2016051600181795",
								"name" => "排球场",
								"parentId" => "2015110500071135",
								"rootId" => "2015110500071135",
								"categoryList" => null
						),
						array(
								"id" => "2016012900151604",
								"name" => "溜冰场",
								"parentId" => "2015110500071135",
								"rootId" => "2015110500071135",
								"categoryList" => null
						),
						array(
								"id" => "2016051600179925",
								"name" => "高尔夫球场",
								"parentId" => "2015110500071135",
								"rootId" => "2015110500071135",
								"categoryList" => null
						),
						array(
								"id" => "2016051600179926",
								"name" => "保龄球馆",
								"parentId" => "2015110500071135",
								"rootId" => "2015110500071135",
								"categoryList" => null
						),
						array(
								"id" => "2016051600178152",
								"name" => "体育场馆",
								"parentId" => "2015110500071135",
								"rootId" => "2015110500071135",
								"categoryList" => null
						),
						array(
								"id" => "2015110500074890",
								"name" => "篮球场",
								"parentId" => "2015110500071135",
								"rootId" => "2015110500071135",
								"categoryList" => null
						),
						array(
								"id" => "2015110500081946",
								"name" => "卡丁赛车",
								"parentId" => "2015110500071135",
								"rootId" => "2015110500071135",
								"categoryList" => null
						),
						array(
								"id" => "2015110500083341",
								"name" => "舞蹈",
								"parentId" => "2015110500071135",
								"rootId" => "2015110500071135",
								"categoryList" => null
						),
						array(
								"id" => "2015110500077463",
								"name" => "网球场",
								"parentId" => "2015110500071135",
								"rootId" => "2015110500071135",
								"categoryList" => null
						),
						array(
								"id" => "2015110500077464",
								"name" => "乒乓球馆",
								"parentId" => "2015110500071135",
								"rootId" => "2015110500071135",
								"categoryList" => null
						),
						array(
								"id" => "2015110500078657",
								"name" => "游泳馆",
								"parentId" => "2015110500071135",
								"rootId" => "2015110500071135",
								"categoryList" => null
						),
						array(
								"id" => "2015110500073009",
								"name" => "羽毛球馆",
								"parentId" => "2015110500071135",
								"rootId" => "2015110500071135",
								"categoryList" => null
						),
						array(
								"id" => "2015110500078658",
								"name" => "桌球馆",
								"parentId" => "2015110500071135",
								"rootId" => "2015110500071135",
								"categoryList" => null
						),
						array(
								"id" => "2015110500078659",
								"name" => "瑜伽",
								"parentId" => "2015110500071135",
								"rootId" => "2015110500071135",
								"categoryList" => null
						),
						array(
								"id" => "2015110500075901",
								"name" => "足球场",
								"parentId" => "2015110500071135",
								"rootId" => "2015110500071135",
								"categoryList" => null
						),
						array(
								"id" => "2015110500085004",
								"name" => "武术场馆",
								"parentId" => "2015110500071135",
								"rootId" => "2015110500071135",
								"categoryList" => null
						),
						array(
								"id" => "2015110500080520",
								"name" => "健身中心",
								"parentId" => "2015110500071135",
								"rootId" => "2015110500071135",
								"categoryList" => null
						)
				)
		),
		array(
				"id" => "2015091000052157",
				"name" => "超市便利店(口碑)",
				"parentId" => "",
				"rootId" => "2015091000052157",
				"categoryList" => array(
						array(
								"id" => "2015091000058486",
								"name" => "超市",
								"parentId" => "2015091000052157",
								"rootId" => "2015091000052157",
								"categoryList" => null
						),
						array(
								"id" => "2015091000056956",
								"name" => "个人护理",
								"parentId" => "2015091000052157",
								"rootId" => "2015091000052157",
								"categoryList" => null
						),
						array(
								"id" => "2015091000060134",
								"name" => "便利店",
								"parentId" => "2015091000052157",
								"rootId" => "2015091000052157",
								"categoryList" => null
						),
                        array(
                                "id" => "2016062800188784",
                                "name" => "烟酒杂货",
                                "parentId" => "2015091000052157",
                                "rootId" => "2015091000052157",
                                "categoryList" => null
                        )
				)
		),
        array(
                "id" => "K歌",
                "name" => "K歌(口碑)",
                "parentId" => "",
                "rootId" => "K歌",
                "categoryList" => array(
                        array(
                                "id" => "KTV",
                                "name" => "KTV",
                                "parentId" => "K歌",
                                "rootId" => "K歌",
                                "categoryList" => array(
                                        array(
                                                "id" => "2015090700042466",
                                                "name" => "量贩式KTV",
                                                "parentId" => "KTV",
                                                "rootId" => "K歌",
                                                "categoryList" => null
                                        ),
                                        array(
                                                "id" => "2016042200000058",
                                                "name" => "会所型KTV",
                                                "parentId" => "KTV",
                                                "rootId" => "K歌",
                                                "categoryList" => null
                                        )
                                )
                        ),
                        array(
                                "id" => "2016062900190067",
                                "name" => "录音棚",
                                "parentId" => "K歌",
                                "rootId" => "K歌",
                                "categoryList" => null
                        )
                )
        ),
		array(
				"id" => "2015062600002758",
				"name" => "购物",
				"parentId" => "",
				"rootId" => "2015062600002758",
				"categoryList" => array(
						array(
								"id" => "2015062600009243",
								"name" => "本地购物",
								"parentId" => "2015062600002758",
								"rootId" => "2015062600002758",
								"categoryList" => null
						),
						array(
								"id" => "2015062600006253",
								"name" => "烟酒（只开酒）",
								"parentId" => "2015062600002758",
								"rootId" => "2015062600002758",
								"categoryList" => null
						),
						array(
								"id" => "2015090700035947",
								"name" => "当地特色/保健品",
								"parentId" => "2015062600002758",
								"rootId" => "2015062600002758",
								"categoryList" => null
						),
						array(
								"id" => "2015062600007420",
								"name" => "服装饰品",
								"parentId" => "2015062600002758",
								"rootId" => "2015062600002758",
								"categoryList" => array(
                                        array(
                                                "id" => "2016062900190084",
                                                "name" => "男性服装",
                                                "parentId" => "2015062600007420",
                                                "rootId" => "2015062600002758",
                                                "categoryList" => null
                                        ),
                                        array(
                                                "id" => "2016062900190085",
                                                "name" => "女性成衣",
                                                "parentId" => "2015062600007420",
                                                "rootId" => "2015062600002758",
                                                "categoryList" => null
                                        ),
                                        array(
                                                "id" => "2016062900190086",
                                                "name" => "内衣",
                                                "parentId" => "2015062600007420",
                                                "rootId" => "2015062600002758",
                                                "categoryList" => null
                                        ),
                                        array(
                                                "id" => "2016062900190087",
                                                "name" => "家居服",
                                                "parentId" => "2015062600007420",
                                                "rootId" => "2015062600002758",
                                                "categoryList" => null
                                        ),
                                        array(
                                                "id" => "2016062900190088",
                                                "name" => "皮草皮具",
                                                "parentId" => "2015062600007420",
                                                "rootId" => "2015062600002758",
                                                "categoryList" => null
                                        ),
                                        array(
                                                "id" => "2016062900190089",
                                                "name" => "高档时装正装定制",
                                                "parentId" => "2015062600007420",
                                                "rootId" => "2015062600002758",
                                                "categoryList" => null
                                        ),
                                        array(
                                                "id" => "2016062900190090",
                                                "name" => "裁缝",
                                                "parentId" => "2015062600007420",
                                                "rootId" => "2015062600002758",
                                                "categoryList" => null
                                        )
                                )
						),
                        array(
                                "id" => "2016062900190082",
                                "name" => "化妆品",
                                "parentId" => "2015062600002758",
                                "rootId" => "2015062600002758",
                                "categoryList" => null
                        ),
                        array(
                                "id" => "2016062900190083",
                                "name" => "茶叶",
                                "parentId" => "2015062600002758",
                                "rootId" => "2015062600002758",
                                "categoryList" => null
                        ),
                        array(
                                "id" => "珠宝饰品",
                                "name" => "珠宝饰品",
                                "parentId" => "2015062600002758",
                                "rootId" => "2015062600002758",
                                "categoryList" => array(
                                        array(
                                                "id" => "2016062900190097",
                                                "name" => "珠宝",
                                                "parentId" => "珠宝饰品",
                                                "rootId" => "2015062600002758",
                                                "categoryList" => null
                                        ),
                                        array(
                                                "id" => "2016062900190098",
                                                "name" => "金银",
                                                "parentId" => "珠宝饰品",
                                                "rootId" => "2015062600002758",
                                                "categoryList" => null
                                        ),
                                        array(
                                                "id" => "2016062900190099",
                                                "name" => "钟表店",
                                                "parentId" => "珠宝饰品",
                                                "rootId" => "2015062600002758",
                                                "categoryList" => null
                                        ),
                                        array(
                                                "id" => "2016062900190100",
                                                "name" => "剃须刀",
                                                "parentId" => "珠宝饰品",
                                                "rootId" => "2015062600002758",
                                                "categoryList" => null
                                        ),
                                        array(
                                                "id" => "2016062900190101",
                                                "name" => "瑞士军刀",
                                                "parentId" => "珠宝饰品",
                                                "rootId" => "2015062600002758",
                                                "categoryList" => null
                                        ),
                                        array(
                                                "id" => "2016062900190102",
                                                "name" => "烟酒具",
                                                "parentId" => "珠宝饰品",
                                                "rootId" => "2015062600002758",
                                                "categoryList" => null
                                        ),
                                        array(
                                                "id" => "2016062900190103",
                                                "name" => "配饰商店",
                                                "parentId" => "珠宝饰品",
                                                "rootId" => "2015062600002758",
                                                "categoryList" => null
                                        ),
                                        array(
                                                "id" => "2016062900190104",
                                                "name" => "假发",
                                                "parentId" => "珠宝饰品",
                                                "rootId" => "2015062600002758",
                                                "categoryList" => null
                                        ),
                                        array(
                                                "id" => "2016062900190105",
                                                "name" => "饰物",
                                                "parentId" => "珠宝饰品",
                                                "rootId" => "2015062600002758",
                                                "categoryList" => null
                                        ),
                                )
                        ),
                        array(
                                "id" => "鞋包",
                                "name" => "鞋包",
                                "parentId" => "2015062600002758",
                                "rootId" => "2015062600002758",
                                "categoryList" => array(
                                        array(
                                                "id" => "2016062900190107",
                                                "name" => "鞋类",
                                                "parentId" => "鞋包",
                                                "rootId" => "2015062600002758",
                                                "categoryList" => null
                                        ),
                                        array(
                                                "id" => "2016062900190108",
                                                "name" => "行李箱包",
                                                "parentId" => "鞋包",
                                                "rootId" => "2015062600002758",
                                                "categoryList" => null
                                        )
                                )
                        ),
                        array(
                                "id" => "2016062900190109",
                                "name" => "亲子购物",
                                "parentId" => "2015062600002758",
                                "rootId" => "2015062600002758",
                                "categoryList" => null
                        ),
                        array(
                                "id" => "2016062900190110",
                                "name" => "婚礼小商品",
                                "parentId" => "2015062600002758",
                                "rootId" => "2015062600002758",
                                "categoryList" => null
                        ),
                        array(
                                "id" => "2016062900190111",
                                "name" => "运动户外用品",
                                "parentId" => "2015062600002758",
                                "rootId" => "2015062600002758",
                                "categoryList" => null
                        ),
                        array(
                                "id" => "办公用品",
                                "name" => "办公用品",
                                "parentId" => "2015062600002758",
                                "rootId" => "2015062600002758",
                                "categoryList" => array(
                                        array(
                                                "id" => "2016062900190178",
                                                "name" => "打字/打印/扫描",
                                                "parentId" => "办公用品",
                                                "rootId" => "2015062600002758",
                                                "categoryList" => null
                                        ),
                                        array(
                                                "id" => "2016062900190179",
                                                "name" => "文具",
                                                "parentId" => "办公用品",
                                                "rootId" => "2015062600002758",
                                                "categoryList" => null
                                        )
                                )
                        ),
                        array(
                                "id" => "文化艺术店",
                                "name" => "文化艺术店",
                                "parentId" => "2015062600002758",
                                "rootId" => "2015062600002758",
                                "categoryList" => array(
                                        array(
                                                "id" => "2016062900190180",
                                                "name" => "乐器",
                                                "parentId" => "文化艺术店",
                                                "rootId" => "2015062600002758",
                                                "categoryList" => null
                                        ),
                                        array(
                                                "id" => "2016062900190182",
                                                "name" => "二手商品店",
                                                "parentId" => "文化艺术店",
                                                "rootId" => "2015062600002758",
                                                "categoryList" => null
                                        ),
                                        array(
                                                "id" => "2016062900190183",
                                                "name" => "文物古董",
                                                "parentId" => "文化艺术店",
                                                "rootId" => "2015062600002758",
                                                "categoryList" => null
                                        ),
                                        array(
                                                "id" => "2016062900190184",
                                                "name" => "古玩复制品",
                                                "parentId" => "文化艺术店",
                                                "rootId" => "2015062600002758",
                                                "categoryList" => null
                                        ),
                                        array(
                                                "id" => "2016062900190185",
                                                "name" => "礼品",
                                                "parentId" => "文化艺术店",
                                                "rootId" => "2015062600002758",
                                                "categoryList" => null
                                        ),
                                        array(
                                                "id" => "2016062900190186",
                                                "name" => "卡片",
                                                "parentId" => "文化艺术店",
                                                "rootId" => "2015062600002758",
                                                "categoryList" => null
                                        ),
                                        array(
                                                "id" => "2016062900190187",
                                                "name" => "纪念品",
                                                "parentId" => "文化艺术店",
                                                "rootId" => "2015062600002758",
                                                "categoryList" => null
                                        ),
                                        array(
                                                "id" => "2016062900190188",
                                                "name" => "瓷器",
                                                "parentId" => "文化艺术店",
                                                "rootId" => "2015062600002758",
                                                "categoryList" => null
                                        ),
                                        array(
                                                "id" => "2016062900190189",
                                                "name" => "玻璃和水晶摆件",
                                                "parentId" => "文化艺术店",
                                                "rootId" => "2015062600002758",
                                                "categoryList" => null
                                        ),
                                        array(
                                                "id" => "2016062900190190",
                                                "name" => "工艺美术用品",
                                                "parentId" => "文化艺术店",
                                                "rootId" => "2015062600002758",
                                                "categoryList" => null
                                        ),
                                        array(
                                                "id" => "2016062900190191",
                                                "name" => "艺术品和画廊",
                                                "parentId" => "文化艺术店",
                                                "rootId" => "2015062600002758",
                                                "categoryList" => null
                                        ),
                                        array(
                                                "id" => "2016062900190192",
                                                "name" => "邮票/纪念币",
                                                "parentId" => "文化艺术店",
                                                "rootId" => "2015062600002758",
                                                "categoryList" => null
                                        ),
                                        array(
                                                "id" => "2016062900190193",
                                                "name" => "宗教物品",
                                                "parentId" => "文化艺术店",
                                                "rootId" => "2015062600002758",
                                                "categoryList" => null
                                        )
                                )
                        ),
                        array(
                                "id" => "2016062900190114",
                                "name" => "品牌折扣店",
                                "parentId" => "2015062600002758",
                                "rootId" => "2015062600002758",
                                "categoryList" => null
                        ),
                        array(
                                "id" => "数码产品",
                                "name" => "数码产品",
                                "parentId" => "2015062600002758",
                                "rootId" => "2015062600002758",
                                "categoryList" => array(
                                        array(
                                                "id" => "2016062900190194",
                                                "name" => "手机",
                                                "parentId" => "数码产品",
                                                "rootId" => "2015062600002758",
                                                "categoryList" => null
                                        ),
                                        array(
                                                "id" => "2016062900190195",
                                                "name" => "通讯设备",
                                                "parentId" => "数码产品",
                                                "rootId" => "2015062600002758",
                                                "categoryList" => null
                                        ),
                                        array(
                                                "id" => "2016062900190196",
                                                "name" => "数码产品及配件",
                                                "parentId" => "数码产品",
                                                "rootId" => "2015062600002758",
                                                "categoryList" => null
                                        ),
                                        array(
                                                "id" => "2016062900190197",
                                                "name" => "专业摄影器材",
                                                "parentId" => "数码产品",
                                                "rootId" => "2015062600002758",
                                                "categoryList" => null
                                        ),
                                        array(
                                                "id" => "2016062900190198",
                                                "name" => "计算机/服务器",
                                                "parentId" => "数码产品",
                                                "rootId" => "2015062600002758",
                                                "categoryList" => null
                                        )
                                )
                        ),
                        array(
                                "id" => "2016062900190116",
                                "name" => "家用电器",
                                "parentId" => "2015062600002758",
                                "rootId" => "2015062600002758",
                                "categoryList" => null
                        ),
                        array(
                                "id" => "家居家纺",
                                "name" => "家居家纺",
                                "parentId" => "2015062600002758",
                                "rootId" => "2015062600002758",
                                "categoryList" => array(
                                        array(
                                                "id" => "2016062900190199",
                                                "name" => "草坪和花园用品",
                                                "parentId" => "家居家纺",
                                                "rootId" => "2015062600002758",
                                                "categoryList" => null
                                        ),
                                        array(
                                                "id" => "2016062900190200",
                                                "name" => "地毯窗帘等家纺",
                                                "parentId" => "家居家纺",
                                                "rootId" => "2015062600002758",
                                                "categoryList" => null
                                        ),
                                        array(
                                                "id" => "2016062900190201",
                                                "name" => "帷幕",
                                                "parentId" => "家居家纺",
                                                "rootId" => "2015062600002758",
                                                "categoryList" => null
                                        ),
                                        array(
                                                "id" => "2016062900190202",
                                                "name" => "室内装潢壁炉",
                                                "parentId" => "家居家纺",
                                                "rootId" => "2015062600002758",
                                                "categoryList" => null
                                        ),
                                        array(
                                                "id" => "2016062900190203",
                                                "name" => "屏风",
                                                "parentId" => "家居家纺",
                                                "rootId" => "2015062600002758",
                                                "categoryList" => null
                                        ),
                                        array(
                                                "id" => "2016062900190204",
                                                "name" => "家庭装饰",
                                                "parentId" => "家居家纺",
                                                "rootId" => "2015062600002758",
                                                "categoryList" => null
                                        ),
                                        array(
                                                "id" => "2016062900190205",
                                                "name" => "花木栽种用品",
                                                "parentId" => "家居家纺",
                                                "rootId" => "2015062600002758",
                                                "categoryList" => null
                                        )

                                )
                        ),
                        array(
                                "id" => "建材",
                                "name" => "建材",
                                "parentId" => "2015062600002758",
                                "rootId" => "2015062600002758",
                                "categoryList" => array(
                                        array(
                                                "id" => "2016062900190206",
                                                "name" => "油漆",
                                                "parentId" => "建材",
                                                "rootId" => "2015062600002758",
                                                "categoryList" => null
                                        ),
                                        array(
                                                "id" => "2016062900190207",
                                                "name" => "清漆用品",
                                                "parentId" => "建材",
                                                "rootId" => "2015062600002758",
                                                "categoryList" => null
                                        ),
                                        array(
                                                "id" => "2016062900190208",
                                                "name" => "大型建材卖场卖场",
                                                "parentId" => "建材",
                                                "rootId" => "2015062600002758",
                                                "categoryList" => null
                                        ),
                                        array(
                                                "id" => "2016062900190209",
                                                "name" => "木材与建材商店",
                                                "parentId" => "建材",
                                                "rootId" => "2015062600002758",
                                                "categoryList" => null
                                        ),
                                        array(
                                                "id" => "2016062900190210",
                                                "name" => "玻璃",
                                                "parentId" => "建材",
                                                "rootId" => "2015062600002758",
                                                "categoryList" => null
                                        ),
                                        array(
                                                "id" => "2016062900190211",
                                                "name" => "墙纸",
                                                "parentId" => "建材",
                                                "rootId" => "2015062600002758",
                                                "categoryList" => null
                                        )
                                )
                        ),
                        array(
                                "id" => "2016062900190119",
                                "name" => "特色集市",
                                "parentId" => "2015062600002758",
                                "rootId" => "2015062600002758",
                                "categoryList" => null
                        ),
                        array(
                                "id" => "2016062900190120",
                                "name" => "眼镜店",
                                "parentId" => "2015062600002758",
                                "rootId" => "2015062600002758",
                                "categoryList" => null
                        ),
                        array(
                                "id" => "2016070500193666",
                                "name" => "菜市场",
                                "parentId" => "2015062600002758",
                                "rootId" => "2015062600002758",
                                "categoryList" => null
                        ),
                        array(
                                "id" => "2016062900190212",
                                "name" => "成人用品",
                                "parentId" => "2015062600002758",
                                "rootId" => "2015062600002758",
                                "categoryList" => null
                        ),
                        array(
                                "id" => "报刊/音像/书籍",
                                "name" => "报刊/音像/书籍",
                                "parentId" => "2015062600002758",
                                "rootId" => "2015062600002758",
                                "categoryList" => array(
                                        array(
                                                "id" => "2016062900190174",
                                                "name" => "音像制品租售",
                                                "parentId" => "报刊/音像/书籍",
                                                "rootId" => "2015062600002758",
                                                "categoryList" => null
                                        ),
                                        array(
                                                "id" => "2016062900190175",
                                                "name" => "音像制品/书籍",
                                                "parentId" => "报刊/音像/书籍",
                                                "rootId" => "2015062600002758",
                                                "categoryList" => null
                                        ),
                                        array(
                                                "id" => "2016062900190176",
                                                "name" => "书籍",
                                                "parentId" => "报刊/音像/书籍",
                                                "rootId" => "2015062600002758",
                                                "categoryList" => null
                                        ),
                                        array(
                                                "id" => "2016062900190177",
                                                "name" => "报纸杂志",
                                                "parentId" => "报刊/音像/书籍",
                                                "rootId" => "2015062600002758",
                                                "categoryList" => null
                                        )
                                )
                        ),
                        array(
                                "id" => "2016062900190122",
                                "name" => "烟花爆竹",
                                "parentId" => "2015062600002758",
                                "rootId" => "2015062600002758",
                                "categoryList" => null
                        ),
                        array(
                                "id" => "2016062900190123",
                                "name" => "国外代购及免税店",
                                "parentId" => "2015062600002758",
                                "rootId" => "2015062600002758",
                                "categoryList" => null
                        )
				)
		),
        array(
                "id" => "爱车",
                "name" => "爱车",
                "parentId" => "",
                "rootId" => "爱车",
                "categoryList" => array(
                        array(
                                "id" => "2016062900190125",
                                "name" => "洗车",
                                "parentId" => "爱车",
                                "rootId" => "爱车",
                                "categoryList" => null
                        ),
                        array(
                                "id" => "4S店/汽车销售",
                                "name" => "4S店/汽车销售",
                                "parentId" => "爱车",
                                "rootId" => "爱车",
                                "categoryList" => array(
                                        array(
                                                "id" => "2016062900190213",
                                                "name" => "汽车销售",
                                                "parentId" => "4S店/汽车销售",
                                                "rootId" => "爱车",
                                                "categoryList" => null
                                        ),
                                        array(
                                                "id" => "2016062900190214",
                                                "name" => "二手车销售",
                                                "parentId" => "4S店/汽车销售",
                                                "rootId" => "爱车",
                                                "categoryList" => null
                                        )
                                )
                        ),
                        array(
                                "id" => "2016062900190127",
                                "name" => "配件/车饰",
                                "parentId" => "爱车",
                                "rootId" => "爱车",
                                "categoryList" => null
                        ),
                        array(
                                "id" => "2016062900190128",
                                "name" => "汽车保险",
                                "parentId" => "爱车",
                                "rootId" => "爱车",
                                "categoryList" => null
                        ),
                        array(
                                "id" => "维修保养",
                                "name" => "维修保养",
                                "parentId" => "爱车",
                                "rootId" => "爱车",
                                "categoryList" => array(
                                        array(
                                                "id" => "2016062900190215",
                                                "name" => "拖车",
                                                "parentId" => "维修保养",
                                                "rootId" => "爱车",
                                                "categoryList" => null
                                        ),
                                        array(
                                                "id" => "2016062900190216",
                                                "name" => "维修",
                                                "parentId" => "维修保养",
                                                "rootId" => "爱车",
                                                "categoryList" => null
                                        ),
                                        array(
                                                "id" => "2016062900190217",
                                                "name" => "保养",
                                                "parentId" => "维修保养",
                                                "rootId" => "爱车",
                                                "categoryList" => null
                                        )
                                )
                        ),
                        array(
                                "id" => "2016062900190130",
                                "name" => "汽车美容",
                                "parentId" => "爱车",
                                "rootId" => "爱车",
                                "categoryList" => null
                        ),
                        array(
                                "id" => "2016062900190131",
                                "name" => "汽车租赁",
                                "parentId" => "爱车",
                                "rootId" => "爱车",
                                "categoryList" => null
                        ),
                        array(
                                "id" => "2016062900190132",
                                "name" => "停车场",
                                "parentId" => "爱车",
                                "rootId" => "爱车",
                                "categoryList" => null
                        ),
                        array(
                                "id" => "2016062900190133",
                                "name" => "加油站",
                                "parentId" => "爱车",
                                "rootId" => "爱车",
                                "categoryList" => null
                        )
                )
        ),
		array(
				"id" => "结婚",
				"name" => "结婚(口碑)",
				"parentId" => "",
				"rootId" => "结婚",
				"categoryList" => array(
						array(
								"id" => "2016012900134880",
								"name" => "婚庆公司",
								"parentId" => "结婚",
								"rootId" => "结婚",
								"categoryList" => null
						),
						array(
								"id" => "2016012900138987",
								"name" => "婚礼策划",
								"parentId" => "结婚",
								"rootId" => "结婚",
								"categoryList" => null
						),
						array(
								"id" => "2016012900140916",
								"name" => "婚纱/礼服",
								"parentId" => "结婚",
								"rootId" => "结婚",
								"categoryList" => null
						),
                        array(
                                "id" => "2016062900190077",
                                "name" => "司仪主持",
                                "parentId" => "结婚",
                                "rootId" => "结婚",
                                "categoryList" => null
                        ),
                        array(
                                "id" => "2016062900190078",
                                "name" => "婚车租赁",
                                "parentId" => "结婚",
                                "rootId" => "结婚",
                                "categoryList" => null
                        ),
				)
		),
		array(
				"id" => "宠物",
				"name" => "宠物(口碑)",
				"parentId" => "",
				"rootId" => "宠物",
				"categoryList" => array(
						array(
								"id" => "2016012900148581",
								"name" => "宠物店",
								"parentId" => "宠物",
								"rootId" => "宠物",
								"categoryList" => null
						),
						array(
								"id" => "2016012900149738",
								"name" => "宠物医院",
								"parentId" => "宠物",
								"rootId" => "宠物",
								"categoryList" => null
						)
				)
		),
		array(
				"id" => "亲子",
				"name" => "亲子(口碑)",
				"parentId" => "",
				"rootId" => "亲子",
				"categoryList" => array(
						array(
								"id" => "2016051000171940",
								"name" => "幼儿才艺",
								"parentId" => "亲子",
								"rootId" => "亲子",
								"categoryList" => null
						),
						array(
								"id" => "2016051000165496",
								"name" => "幼儿外语",
								"parentId" => "亲子",
								"rootId" => "亲子",
								"categoryList" => null
						),
						array(
								"id" => "2016051000170050",
								"name" => "早教中心",
								"parentId" => "亲子",
								"rootId" => "亲子",
								"categoryList" => null
						),
						array(
								"id" => "2016051000167013",
								"name" => "科普场馆",
								"parentId" => "亲子",
								"rootId" => "亲子",
								"categoryList" => null
						),
						array(
								"id" => "2016051000168501",
								"name" => "亲子DIY",
								"parentId" => "亲子",
								"rootId" => "亲子",
								"categoryList" => null
						),
						array(
								"id" => "2016051000164228",
								"name" => "亲子游泳",
								"parentId" => "亲子",
								"rootId" => "亲子",
								"categoryList" => null
						),
						array(
								"id" => "2016012900143707",
								"name" => "亲子游乐",
								"parentId" => "亲子",
								"rootId" => "亲子",
								"categoryList" => null
						),
                        array(
                                "id" => "2016062900190080",
                                "name" => "亲子服务",
                                "parentId" => "亲子",
                                "rootId" => "亲子",
                                "categoryList" => null
                        )
				)
		),
		array(
				"id" => "洗衣",
				"name" => "洗衣(口碑)",
				"parentId" => "",
				"rootId" => "洗衣",
				"categoryList" => array(
						array(
								"id" => "2016051000165497",
								"name" => "奢侈品养护",
								"parentId" => "洗衣",
								"rootId" => "洗衣",
								"categoryList" => null
						),
						array(
								"id" => "2016051000171941",
								"name" => "鞋帽清洗",
								"parentId" => "洗衣",
								"rootId" => "洗衣",
								"categoryList" => null
						),
						array(
								"id" => "2016051000173119",
								"name" => "洗衣家纺",
								"parentId" => "洗衣",
								"rootId" => "洗衣",
								"categoryList" => null
						),
                        array(
                                "id" => "2016062900190081",
                                "name" => "自助洗衣",
                                "parentId" => "洗衣",
                                "rootId" => "洗衣",
                                "categoryList" => null
                        )
				)
		),
        array(
                "id" => "2016051000175109",
                "name" => "书店(口碑)",
                "parentId" => "",
                "rootId" => "2016051000175109",
                "categoryList" => null
        ),
		array(
				"id" => "摄影",
				"name" => "摄影(口碑)",
				"parentId" => "",
				"rootId" => "摄影",
				"categoryList" => array(
						array(
								"id" => "2016012900136110",
								"name" => "婚纱摄影",
								"parentId" => "摄影",
								"rootId" => "摄影",
								"categoryList" => array(
                                        array(
                                                "id" => "2016070700195434",
                                                "name" => "旅拍/本地婚纱照",
                                                "parentId" => "2016012900136110",
                                                "rootId" => "摄影",
                                                "categoryList" => null
                                        ),
                                        array(
                                                "id" => "2016070700195435",
                                                "name" => "本地婚纱摄影",
                                                "parentId" => "2016012900136110",
                                                "rootId" => "摄影",
                                                "categoryList" => null
                                        )
                                )
						),
						array(
								"id" => "2016012900145271",
								"name" => "儿童摄影",
								"parentId" => "摄影",
								"rootId" => "摄影",
								"categoryList" => null
						),
						array(
								"id" => "2016051000164227",
								"name" => "孕妇摄影",
								"parentId" => "摄影",
								"rootId" => "摄影",
								"categoryList" => null
						),
						array(
								"id" => "2015063000024698",
								"name" => "艺术写真",
								"parentId" => "摄影",
								"rootId" => "摄影",
								"categoryList" => null
						),
						array(
								"id" => "2016051000165495",
								"name" => "证件照",
								"parentId" => "摄影",
								"rootId" => "摄影",
								"categoryList" => null
						),
						array(
								"id" => "2016051600176301",
								"name" => "跟拍",
								"parentId" => "摄影",
								"rootId" => "摄影",
								"categoryList" => null
						),
                        array(
                                "id" => "2016063000191709",
                                "name" => "商业摄影",
                                "parentId" => "摄影",
                                "rootId" => "摄影",
                                "categoryList" => null
                        )
				)
		),
        array(
                "id" => "医疗健康",
                "name" => "医疗健康",
                "parentId" => "",
                "rootId" => "医疗健康",
                "categoryList" => array(
                        array(
                                "id" => "医院",
                                "name" => "医院",
                                "parentId" => "医疗健康",
                                "rootId" => "医疗健康",
                                "categoryList" => array(
                                        array(
                                                "id" => "2016062900190306",
                                                "name" => "社区医院",
                                                "parentId" => "医院",
                                                "rootId" => "医疗健康",
                                                "categoryList" => null
                                        ),
                                        array(
                                                "id" => "2016062900190307",
                                                "name" => "正骨医生",
                                                "parentId" => "医院",
                                                "rootId" => "医疗健康",
                                                "categoryList" => null
                                        ),
                                        array(
                                                "id" => "2016062900190308",
                                                "name" => "按摩医生",
                                                "parentId" => "医院",
                                                "rootId" => "医疗健康",
                                                "categoryList" => null
                                        ),
                                        array(
                                                "id" => "2016062900190309",
                                                "name" => "眼科医疗服务",
                                                "parentId" => "医院",
                                                "rootId" => "医疗健康",
                                                "categoryList" => null
                                        ),
                                        array(
                                                "id" => "2016062900190310",
                                                "name" => "手足病医疗服务",
                                                "parentId" => "医院",
                                                "rootId" => "医疗健康",
                                                "categoryList" => null
                                        ),
                                        array(
                                                "id" => "2016062900190311",
                                                "name" => "护理/照料服务",
                                                "parentId" => "医院",
                                                "rootId" => "医疗健康",
                                                "categoryList" => null
                                        ),
                                        array(
                                                "id" => "2016062900190312",
                                                "name" => "公立医院",
                                                "parentId" => "医院",
                                                "rootId" => "医疗健康",
                                                "categoryList" => null
                                        )
                                )
                        ),
                        array(
                                "id" => "2016062900190298",
                                "name" => "中医",
                                "parentId" => "医疗健康",
                                "rootId" => "医疗健康",
                                "categoryList" => null
                        ),
                        array(
                                "id" => "2016062900190299",
                                "name" => "齿科",
                                "parentId" => "医疗健康",
                                "rootId" => "医疗健康",
                                "categoryList" => null
                        ),
                        array(
                                "id" => "2016062900190300",
                                "name" => "药店",
                                "parentId" => "医院健康",
                                "rootId" => "医疗健康",
                                "categoryList" => null
                        ),
                        array(
                                "id" => "2016062900190301",
                                "name" => "急救服务",
                                "parentId" => "医院健康",
                                "rootId" => "医疗健康",
                                "categoryList" => null
                        ),
                        array(
                                "id" => "2016062900190302",
                                "name" => "整形",
                                "parentId" => "医院健康",
                                "rootId" => "医疗健康",
                                "categoryList" => null
                        ),
                        array(
                                "id" => "2016062900190303",
                                "name" => "妇幼医院",
                                "parentId" => "医院健康",
                                "rootId" => "医疗健康",
                                "categoryList" => null
                        ),
                        array(
                                "id" => "2016062900190304",
                                "name" => "体检中心",
                                "parentId" => "医院健康",
                                "rootId" => "医疗健康",
                                "categoryList" => null
                        )
                )
        ),
        array(
                "id" => "专业销售/批发",
                "name" => "专业销售/批发",
                "parentId" => "",
                "rootId" => "专业销售/批发",
                "categoryList" => array(
                        array(
                                "id" => "汽车/运输工具",
                                "name" => "汽车/运输工具",
                                "parentId" => "专业销售/批发",
                                "rootId" => "专业销售/批发",
                                "categoryList" => array(
                                        array(
                                                "id" => "2016062900190342",
                                                "name" => "机动车/配件批发",
                                                "parentId" => "汽车/运输工具",
                                                "rootId" => "专业销售/批发",
                                                "categoryList" => null
                                        ),
                                        array(
                                                "id" => "2016062900190343",
                                                "name" => "活动房车销售商",
                                                "parentId" => "汽车/运输工具",
                                                "rootId" => "专业销售/批发",
                                                "categoryList" => null
                                        ),
                                        array(
                                                "id" => "2016062900190344",
                                                "name" => "汽车轮胎经销",
                                                "parentId" => "汽车/运输工具",
                                                "rootId" => "专业销售/批发",
                                                "categoryList" => null
                                        ),
                                        array(
                                                "id" => "2016062900190345",
                                                "name" => "汽车零配件",
                                                "parentId" => "汽车/运输工具",
                                                "rootId" => "专业销售/批发",
                                                "categoryList" => null
                                        ),
                                        array(
                                                "id" => "2016062900190346",
                                                "name" => "船舶及配件",
                                                "parentId" => "汽车/运输工具",
                                                "rootId" => "专业销售/批发",
                                                "categoryList" => null
                                        ),
                                        array(
                                                "id" => "2016062900190347",
                                                "name" => "拖车篷车娱乐用车",
                                                "parentId" => "汽车/运输工具",
                                                "rootId" => "专业销售/批发",
                                                "categoryList" => null
                                        ),
                                        array(
                                                "id" => "2016062900190348",
                                                "name" => "轨道交通设备器材",
                                                "parentId" => "汽车/运输工具",
                                                "rootId" => "专业销售/批发",
                                                "categoryList" => null
                                        ),
                                        array(
                                                "id" => "2016062900190349",
                                                "name" => "飞机/配件",
                                                "parentId" => "汽车/运输工具",
                                                "rootId" => "专业销售/批发",
                                                "categoryList" => null
                                        ),
                                        array(
                                                "id" => "2016062900190350",
                                                "name" => "运输搬运设备",
                                                "parentId" => "汽车/运输工具",
                                                "rootId" => "专业销售/批发",
                                                "categoryList" => null
                                        ),
                                        array(
                                                "id" => "2016062900190351",
                                                "name" => "起重装卸设备",
                                                "parentId" => "汽车/运输工具",
                                                "rootId" => "专业销售/批发",
                                                "categoryList" => null
                                        ),
                                        array(
                                                "id" => "2016062900190352",
                                                "name" => "摩托车及配件",
                                                "parentId" => "汽车/运输工具",
                                                "rootId" => "专业销售/批发",
                                                "categoryList" => null
                                        ),
                                        array(
                                                "id" => "2016062900190353",
                                                "name" => "电动车及配件",
                                                "parentId" => "汽车/运输工具",
                                                "rootId" => "专业销售/批发",
                                                "categoryList" => null
                                        ),
                                        array(
                                                "id" => "2016062900190354",
                                                "name" => "露营及旅行汽车",
                                                "parentId" => "汽车/运输工具",
                                                "rootId" => "专业销售/批发",
                                                "categoryList" => null
                                        ),
                                        array(
                                                "id" => "2016062900190355",
                                                "name" => "雪车",
                                                "parentId" => "汽车/运输工具",
                                                "rootId" => "专业销售/批发",
                                                "categoryList" => null
                                        )
                                )
                        ),
                        array(
                                "id" => "办公用品批发",
                                "name" => "办公用品批发",
                                "parentId" => "专业销售/批发",
                                "rootId" => "专业销售/批发",
                                "categoryList" => array(
                                        array(
                                                "id" => "2016062900190356",
                                                "name" => "商务家具批发",
                                                "parentId" => "办公用品批发",
                                                "rootId" => "专业销售/批发",
                                                "categoryList" => null
                                        ),
                                        array(
                                                "id" => "2016062900190357",
                                                "name" => "办公器材批发",
                                                "parentId" => "办公用品批发",
                                                "rootId" => "专业销售/批发",
                                                "categoryList" => null
                                        ),
                                        array(
                                                "id" => "2016062900190359",
                                                "name" => "办公用品文具批发",
                                                "parentId" => "办公用品批发",
                                                "rootId" => "专业销售/批发",
                                                "categoryList" => null
                                        )
                                )
                        ),
                        array(
                                "id" => "工业产品批发零售",
                                "name" => "工业产品批发零售",
                                "parentId" => "专业销售/批发",
                                "rootId" => "专业销售/批发",
                                "categoryList" => array(
                                        array(
                                                "id" => "2016062900190360",
                                                "name" => "金属产品/服务",
                                                "parentId" => "工业产品批发零售",
                                                "rootId" => "专业销售/批发",
                                                "categoryList" => null
                                        ),
                                        array(
                                                "id" => "2016062900190361",
                                                "name" => "电气产品/设备",
                                                "parentId" => "工业产品批发零售",
                                                "rootId" => "专业销售/批发",
                                                "categoryList" => null
                                        ),
                                        array(
                                                "id" => "2016062900190362",
                                                "name" => "五金器材/用品",
                                                "parentId" => "工业产品批发零售",
                                                "rootId" => "专业销售/批发",
                                                "categoryList" => null
                                        ),
                                        array(
                                                "id" => "2016062900190363",
                                                "name" => "管道/供暖设备",
                                                "parentId" => "工业产品批发零售",
                                                "rootId" => "专业销售/批发",
                                                "categoryList" => null
                                        ),
                                        array(
                                                "id" => "2016062900190364",
                                                "name" => "工业设备/制成品",
                                                "parentId" => "工业产品批发零售",
                                                "rootId" => "专业销售/批发",
                                                "categoryList" => null
                                        ),
                                        array(
                                                "id" => "2016062900190365",
                                                "name" => "工业耐用品",
                                                "parentId" => "工业产品批发零售",
                                                "rootId" => "专业销售/批发",
                                                "categoryList" => null
                                        ),
                                        array(
                                                "id" => "2016062900190366",
                                                "name" => "化工产品",
                                                "parentId" => "工业产品批发零售",
                                                "rootId" => "专业销售/批发",
                                                "categoryList" => null
                                        ),
                                        array(
                                                "id" => "2016062900190367",
                                                "name" => "石油/石油产品",
                                                "parentId" => "工业产品批发零售",
                                                "rootId" => "专业销售/批发",
                                                "categoryList" => null
                                        ),
                                )
                        ),
                        array(
                                "id" => "药品医疗批发",
                                "name" => "药品医疗批发",
                                "parentId" => "专业销售/批发",
                                "rootId" => "专业销售/批发",
                                "categoryList" => array(
                                        array(
                                                "id" => "2016062900190368",
                                                "name" => "医疗器械",
                                                "parentId" => "药品医疗批发",
                                                "rootId" => "专业销售/批发",
                                                "categoryList" => null
                                        ),
                                        array(
                                                "id" => "2016062900190369",
                                                "name" => "药品批发商",
                                                "parentId" => "药品医疗批发",
                                                "rootId" => "专业销售/批发",
                                                "categoryList" => null
                                        ),
                                        array(
                                                "id" => "2016062900190370",
                                                "name" => "康复/身体辅助品",
                                                "parentId" => "药品医疗批发",
                                                "rootId" => "专业销售/批发",
                                                "categoryList" => null
                                        )
                                )
                        )
                )
        ),
        array(
                "id" => "政府/社会组织",
                "name" => "政府/社会组织",
                "parentId" => "",
                "rootId" => "政府/社会组织",
                "categoryList" => array(
                        array(
                                "id" => "政府服务",
                                "name" => "政府服务",
                                "parentId" => "政府/社会组织",
                                "rootId" => "政府/社会组织",
                                "categoryList" => array(
                                        array(
                                                "id" => "2016062900190374",
                                                "name" => "法庭费用",
                                                "parentId" => "政府服务",
                                                "rootId" => "政府/社会组织",
                                                "categoryList" => null
                                        ),
                                        array(
                                                "id" => "2016062900190375",
                                                "name" => "行政费用和罚款",
                                                "parentId" => "政府服务",
                                                "rootId" => "政府/社会组织",
                                                "categoryList" => null
                                        ),
                                        array(
                                                "id" => "2016062900190376",
                                                "name" => "保释金",
                                                "parentId" => "政府服务",
                                                "rootId" => "政府/社会组织",
                                                "categoryList" => null
                                        ),
                                        array(
                                                "id" => "2016062900190377",
                                                "name" => "税务/海关",
                                                "parentId" => "政府服务",
                                                "rootId" => "政府/社会组织",
                                                "categoryList" => null
                                        ),
                                        array(
                                                "id" => "2016062900190378",
                                                "name" => "社会保障服务",
                                                "parentId" => "政府服务",
                                                "rootId" => "政府/社会组织",
                                                "categoryList" => null
                                        ),
                                        array(
                                                "id" => "2016062900190379",
                                                "name" => "使领馆",
                                                "parentId" => "政府服务",
                                                "rootId" => "政府/社会组织",
                                                "categoryList" => null
                                        ),
                                        array(
                                                "id" => "2016062900190380",
                                                "name" => "国家邮政",
                                                "parentId" => "政府服务",
                                                "rootId" => "政府/社会组织",
                                                "categoryList" => null
                                        ),
                                        array(
                                                "id" => "2016062900190381",
                                                "name" => "政府采购",
                                                "parentId" => "政府服务",
                                                "rootId" => "政府/社会组织",
                                                "categoryList" => null
                                        ),
                                        array(
                                                "id" => "2016062900190382",
                                                "name" => "政府贷款",
                                                "parentId" => "政府服务",
                                                "rootId" => "政府/社会组织",
                                                "categoryList" => null
                                        )
                                )
                        ),
                        array(
                                "id" => "社会组织",
                                "name" => "社会组织",
                                "parentId" => "政府/社会组织",
                                "rootId" => "政府/社会组织",
                                "categoryList" => array(
                                        array(
                                                "id" => "2016062900190383",
                                                "name" => "慈善/社会公益",
                                                "parentId" => "社会组织",
                                                "rootId" => "政府/社会组织",
                                                "categoryList" => null
                                        ),
                                        array(
                                                "id" => "2016062900190384",
                                                "name" => "行业协会/社团",
                                                "parentId" => "社会组织",
                                                "rootId" => "政府/社会组织",
                                                "categoryList" => null
                                        ),
                                        array(
                                                "id" => "2016062900190385",
                                                "name" => "宗教组织",
                                                "parentId" => "社会组织",
                                                "rootId" => "政府/社会组织",
                                                "categoryList" => null
                                        ),
                                        array(
                                                "id" => "2016062900190386",
                                                "name" => "汽车协会",
                                                "parentId" => "社会组织",
                                                "rootId" => "政府/社会组织",
                                                "categoryList" => null
                                        )
                                )
                        )
                )
        ),
        array(
                "id" => "电影",
                "name" => "电影",
                "parentId" => "",
                "rootId" => "电影",
                "categoryList" => array(
                        array(
                                "id" => "2016062900190388",
                                "name" => "电影院",
                                "parentId" => "电影",
                                "rootId" => "电影",
                                "categoryList" => null
                        ),
                        array(
                                "id" => "2016062900190389",
                                "name" => "私人影院",
                                "parentId" => "电影",
                                "rootId" => "电影",
                                "categoryList" => null
                        ),
                )
        )
);
//支付宝口碑门店有返佣类目
$GLOBALS['__ALIPAY_KOUBEI_STORE_HAS_OWN_COMMISSION'] = array(
		'2015063000020189',
		'2015090800051000',
		'2015090700048304',
		'2015063000026406',
		'2015090700045519',
		'2015063000028051',
		'2015063000024698',
		'2015091000052157',
		'2015091000060134',
		'2015091000056956',
		'2015091000058486',
		'2015062600004525',
		'2015063000012448',
		'2015062600011157',
		'2015090700042466',
		'2015090700039570',
		'2015091100061275',
		'2015090700041394',
		'2015110500071135',
		'2015110500075901',
		'2015110500085004',
		'2015110500073009',
		'2015110500080520',
		'2015110500083341',
		'2015110500081946',
		'2015110500078657',
		'2015110500078658',
		'2015110500078659',
		'2015110500077463',
		'2015110500077464',
		'2015110500074890',
		'2015063000013612',
		'2015101000066113',
		'2015063000015529',
		'2015063000019130',
		'2015101000067631',
		'2015063000017354',
		'2015101000064159',
		'2015080600000001',
		'2015080600000002',
		'2015080600000003',
		'2015080600000004',
		'2015092200062945',
		'2015062600002758',
		'2015062600007420',
		'2015062600009243',
		'2015090700035947',
		'2015062600006253',
);

//门店版本
define('STORE_EDITION_CASHIER', '1');//收银版
define('STORE_EDITION_MARKETING', '2');//营销版
$GLOBALS['__STORE_EDITION'] = array(
    STORE_EDITION_CASHIER => '收银版',
    STORE_EDITION_MARKETING => '营销版',
);

/*****************支付宝***********************/
//支付宝接口类型by gulei 2016-1-14
define('ALIPAY_API_VERSION_1_API', '1'); //支付宝1.0接口
define('ALIPAY_API_VERSION_2_API', '2'); //支付宝2.0接口
define('ALIPAY_API_VERSION_2_AUTH_API', '3'); //支付宝2.0授权接口

//支付宝门店审核常量by gulei 2016-1-14
define('ALIPAY_STORE_STATUS_AUDITING', 'AUDITING');//审核中
define('ALIPAY_STORE_STATUS_AUDIT_SUCCESS', 'AUDIT_SUCCESS');//审核通过
define('ALIPAY_STORE_STATUS_AUTO_PASS', 'AUTO_PASS');//自动通过
define('ALIPAY_STORE_STATUS_AUTO_FAIL', 'AUTO_FAIL');//支付宝内部的系统错误
define('ALIPAY_STORE_STATUS_AUDIT_FAILED', 'AUDIT_FAILED');//审核驳回

//支付宝用户信息常量by gulei 2016-1-14
//支付宝关注状态
define('ALIPAY_USER_NOTSUBSCRIBE', '1');//未关注
define('ALIPAY_USER_SUBSCRIBE', '2');//已关注
define('ALIPAY_USER_CANCELSUBSCRIBE', '3');//取消关注
//性别
define('ALIPAY_USER_GENDER_M', 'M');//男性
define('ALIPAY_USER_GENDER_F', 'F');//女性
//用户类型
define('ALIPAY_USER_USER_TYPE_VALUE_COMPANY', '1');//公司
define('ALIPAY_USER_USER_TYPE_VALUE_PERSONAL', '2');//个人
//是否经过经营执照认证
define('ALIPAY_USER_IS_LICENCE_AUTH_PASS', 'T');//通过营业执照认证
define('ALIPAY_USER_IS_LICENCE_AUTH_NOTPASS', 'F');//没通过营业执照认证
//是否通过实名认证
define('ALIPAY_USER_IS_CERTIFIED_PASS', 'T');//通过实名认证
define('ALIPAY_USER_IS_CERTIFIED_NOTPASS', 'F');//没有通过实名认证
//是否A类认证
define('ALIPAY_USER_IS_CERTIFY_GRADE_A_PASS', 'T');//通过Alei认证
define('ALIPAY_USER_IS_CERTIFY_GRADE_A_NOTPASS', 'F');//没有通过A类认证
//是否是学生
define('ALIPAY_USER_IS_STUDENT_CERTIFIED_YES', 'T');//是学生
define('ALIPAY_USER_IS_STUDENT_CERTIFIED_NO', 'F');//不是学生
//是否经过银行卡认证
define('ALIPAY_USER_IS_BANK_AUTH_PASS', 'T');//经过银行卡认证
define('ALIPAY_USER_IS_BANK_AUTH_NOTPASS', 'F');//未经过银行卡认证
//是否经过手机认证
define('ALIPAY_USER_IS_MOBILE_AUTH_PASS', 'T');//经过手机认证
define('ALIPAY_USER_IS_MOBILE_AUTH_NOTPASS', 'F');//未经过手机认证
//用户状态(Q/T/B/W)
define('ALIPAY_USER_STATUS_Q', 'Q');//快速注册用户
define('ALIPAY_USER_STATUS_T', 'T');//已认证用户
define('ALIPAY_USER_STATUS_B', 'B');//被冻结账户
define('ALIPAY_USER_STATUS_W', 'W');//注册未激活用户
//是否身份证认证
define('ALIPAY_USER_IS_ID_AUTH_PASS', 'T');//身份证认证
define('ALIPAY_USER_IS_ID_AUTH_NOTPASS', 'F');//非身份证认证

/************************************微信***************************************************/
//微信关注状态
define('WECHAT_USER_NOTSUBSCRIBE', '1');//未关注
define('WECHAT_USER_SUBSCRIBE', '2');//已关注
define('WECHAT_USER_CANCELSUBSCRIBE', '3');//取消关注
//微信用户性别
define('WECHAT_USER_SEX_MAN', '1');//男性
define('WECHAT_USER_SEX_FEMALE', '2');//女性

/************************************用户***************************************************/
//用户类型 微信粉丝 支付宝粉丝 玩券会员
define('USER_TYPE_WANQUAN_MEMBER', '1');//玩券会员
define('USER_TYPE_WECHAT_FANS', '2');//微信粉丝
define('USER_TYPE_ALIPAY_FANS', '3');//支付宝粉丝

//最后登录客户端
define('USER_LOGIN_CLIENT_WECHAT', '1');//微信
define('USER_LOGIN_CLIENT_ALIPAY', '2');//支付宝
define('USER_LOGIN_CLIENT_OTHER', '3');//其他
$GLOBALS['__USER_LOGIN_CLIENT'] = array(
		USER_LOGIN_CLIENT_WECHAT => '微信',
		USER_LOGIN_CLIENT_ALIPAY => '支付宝',
		USER_LOGIN_CLIENT_OTHER => '其他',
);

//微信用户性别
define('USER_SEX_MALE', '1');//男性
define('USER_SEX_FEMALE', '2');//女性

//粉丝绑定状态
define('USER_BIND_STATUS_UNBIND','1');//未绑定
define('USER_BIND_STATUS_BINDED','2');//已绑定


/******************************************************************************************/
//标签取值类型
define('TAG_VALUE_TYPE_ENUMERATION', '1');//枚举
//define('TAG_VALUE_TYPE_ENUMERATION', '2');//整数范围
//define('TAG_VALUE_TYPE_ENUMERATION', '3');//非整数范围
//define('TAG_VALUE_TYPE_ENUMERATION', '4');//布尔类型

define('TAG_VALUE_TYPE_INTEGER_RANGE', '2');//整数范围
define('TAG_VALUE_TYPE_NOT_INTEGER_RANGE', '3');//非整数范围
define('TAG_VALUE_TYPE_BOOLEAN', '4');//布尔类型

//标签类型
define('TAG_TYPE_ATTR', '1');//属性标签
define('TAG_TYPE_CONDITION', '2');//条件标签
$GLOBALS['__TAG_TYPE'] = array(
		TAG_TYPE_ATTR => '属性标签',
		TAG_TYPE_CONDITION => '条件标签'
);
//是否是组合标签
define('TAG_IF_COMBINATION_TAG_NO', '1');//不是
define('TAG_IF_COMBINATION_TAG_YES', '2');//是

//标签类型
define('TAG_TYPE_SYSTEM', '1');//系统标签

//标签类别
define('TAG_CATEGORY_MONETARY', '1');//消费能力
define('TAG_CATEGORY_FREQUENCY', '2');//消费频次
define('TAG_CATEGORY_RECENCY', '3');//流失情况
define('TAG_CATEGORY_CUSTOMER_VALUE', '4');//客户价值
$GLOBALS['__TAG_CATEGORY'] = array(
    TAG_CATEGORY_MONETARY => '消费能力',
    TAG_CATEGORY_FREQUENCY => '消费频次',
    TAG_CATEGORY_RECENCY => '流失情况',
    TAG_CATEGORY_CUSTOMER_VALUE => '客户价值'
);

//消费能力标签值
define('TAG_CATEGORY_MONETARY_VALUE_LOW', '1');//低消费
define('TAG_CATEGORY_MONETARY_VALUE_HIGH', '2');//高消费
$GLOBALS['__TAG_CATEGORY_MONETARY_VALUE'] = array(
    TAG_CATEGORY_MONETARY_VALUE_LOW => '低消费',
    TAG_CATEGORY_MONETARY_VALUE_HIGH => '高消费'
);

//消费频次标签值
define('TAG_CATEGORY_FREQUENCY_VALUE_NOTFREQUENTER', '1');//散客
define('TAG_CATEGORY_FREQUENCY_VALUE_FREQUENTER', '2');//常客
$GLOBALS['__TAG_CATEGORY_FREQUENCY_VALUE'] = array(
    TAG_CATEGORY_FREQUENCY_VALUE_NOTFREQUENTER => '散客',
    TAG_CATEGORY_FREQUENCY_VALUE_FREQUENTER => '常客'
);

//流失情况标签值
define('TAG_CATEGORY_RECENCY_NOTLOSS', '1');//未流失
define('TAG_CATEGORY_RECENCY_LOSS', '2');//已流失
$GLOBALS['__TAG_CATEGORY_RECENCY_VALUE'] = array(
    TAG_CATEGORY_RECENCY_NOTLOSS => '未流失',
    TAG_CATEGORY_RECENCY_LOSS => '已流失'
);

//客户价值标签值
define('TAG_CATEGORY_CUSTOMER_VALUE_IMPORTANT_KEEP', '1');//重要保持
define('TAG_CATEGORY_CUSTOMER_VALUE_IMPORTANT_VALUABLE', '2');//重要价值
define('TAG_CATEGORY_CUSTOMER_VALUE_IMPORTANT_DEVELOPMENT', '3');//重要发展
define('TAG_CATEGORY_CUSTOMER_VALUE_IMPORTANT_STAY', '4');//重要挽留
define('TAG_CATEGORY_CUSTOMER_VALUE_COMMON_KEEP', '5');//一般保持
define('TAG_CATEGORY_CUSTOMER_VALUE_COMMON_VALUABLE', '6');//一般价值
define('TAG_CATEGORY_CUSTOMER_VALUE_COMMON_DEVELOPMENT', '7');//一般发展
define('TAG_CATEGORY_CUSTOMER_VALUE_COMMON_STAY', '8');//一般挽留
$GLOBALS['__TAG_CATEGORY_CUSTOMER_VALUE'] = array(
    TAG_CATEGORY_CUSTOMER_VALUE_IMPORTANT_KEEP => '重要保持',
    TAG_CATEGORY_CUSTOMER_VALUE_IMPORTANT_VALUABLE => '重要价值',
    TAG_CATEGORY_CUSTOMER_VALUE_IMPORTANT_DEVELOPMENT => '重要发展',
    TAG_CATEGORY_CUSTOMER_VALUE_IMPORTANT_STAY => '重要挽留',
    TAG_CATEGORY_CUSTOMER_VALUE_COMMON_KEEP => '一般保持',
    TAG_CATEGORY_CUSTOMER_VALUE_COMMON_VALUABLE => '一般价值',
    TAG_CATEGORY_CUSTOMER_VALUE_COMMON_DEVELOPMENT => '一般发展',
    TAG_CATEGORY_CUSTOMER_VALUE_COMMON_STAY => '一般挽留',
);

/*****************************************营销活动***********************************************************/
//营销活动类型
/*define('MARKETING_ACTIVITY_TYPE_BE_MEMBER_GIVE', '1');//新加入会员赠券
define('MARKETING_ACTIVITY_TYPE_COMPLETE_MMEMBER_DATA', '2');//填资料赠券
define('MARKETING_ACTIVITY_TYPE_NEW_MEMBER_GIVE', '3');//新会员赠券
define('MARKETING_ACTIVITY_TYPE_NO_TRADE_MEMBER', '4');//加入未消费会员赠券
define('MARKETING_ACTIVITY_TYPE_REDEEM_LOSE_MEMBER', '5');//挽回流失客户
define('MARKETING_ACTIVITY_TYPE_PROMOTE_MEMBER', '6');//促进未流失客户
define('MARKETING_ACTIVITY_TYPE_OLD_MEMBER_GIVE', '7');//给老会员赠券
define('MARKETING_ACTIVITY_TYPE_STORED_ACTIVITY', '8');//储值活动
define('MARKETING_ACTIVITY_TYPE_BIRTHDAY_GIVE', '9');//生日赠券
define('MARKETING_ACTIVITY_TYPE_MEMBER_GIVE', '10');//会员赠券
define('MARKETING_ACTIVITY_TYPE_CUMULATIVE_GIVE', '11');//累计消费赠券
define('MARKETING_ACTIVITY_TYPE_FULL_GIVE', '12');//消费满赠券
define('MARKETING_ACTIVITY_TYPE_PRECISION_MARKETING', '13');//精准营销*/

//新营销活动类型
define('MARKETING_ACTIVITY_TYPE_BASICS_JZMA', '10101');//精准营销
define('MARKETING_ACTIVITY_TYPE_BASICS_SCGZMA', '10201');//首次关注赠券
define('MARKETING_ACTIVITY_TYPE_BASICS_XJRHYMA', '10202');//新加入会员赠券
define('MARKETING_ACTIVITY_TYPE_BASICS_XHYMA', '10203');//新会员赠券
define('MARKETING_ACTIVITY_TYPE_BASICS_WSZLMA',	'10204');//完善资料赠券
define('MARKETING_ACTIVITY_TYPE_BASICS_SCZFMA',	'10205');//首次支付赠券
define('MARKETING_ACTIVITY_TYPE_BASICS_CQWXFMA', '10206');//长期未消费赠券
define('MARKETING_ACTIVITY_TYPE_BASICS_LSKHMA', '10301');//流失客户赠券
define('MARKETING_ACTIVITY_TYPE_BASICS_LHYMA', '10302');//老会员赠券
define('MARKETING_ACTIVITY_TYPE_BASICS_HYKHMA', '10303');//活跃客户赠券
define('MARKETING_ACTIVITY_TYPE_BASICS_HYSRMA', '10401');//会员生日赠券
define('MARKETING_ACTIVITY_TYPE_BASICS_JRMA', '10402');//节日赠券
define('MARKETING_ACTIVITY_TYPE_BASICS_HYSJMA', '10403');//会员升级赠券
define('MARKETING_ACTIVITY_TYPE_BASICS_DCXFMMA', '10501');//单次消费满赠券
define('MARKETING_ACTIVITY_TYPE_BASICS_LJXFMMA', '10502');//累计消费满赠券
define('MARKETING_ACTIVITY_TYPE_BASICS_XFCSMMA', '10503');//消费次数满赠券


define('MARKETING_ACTIVITY_TYPE_DMALL_SDLJ', '14');//东钱湖营销活动-首单立减
define('MARKETING_ACTIVITY_TYPE_DMALL_ZFL', '15');//东钱湖营销活动-周福利

$GLOBALS['__MARKETING_ACTIVITY_TYPE'] = array(
		MARKETING_ACTIVITY_TYPE_BASICS_JZMA => '精准营销',
		MARKETING_ACTIVITY_TYPE_BASICS_SCGZMA => '首次关注赠券',
//		MARKETING_ACTIVITY_TYPE_BASICS_XJRHYMA => '新加入会员赠券',
//		MARKETING_ACTIVITY_TYPE_BASICS_XHYMA => '新会员赠券',
//		MARKETING_ACTIVITY_TYPE_BASICS_WSZLMA => '完善资料赠券',
//		MARKETING_ACTIVITY_TYPE_BASICS_SCZFMA => '首次支付赠券',
//		MARKETING_ACTIVITY_TYPE_BASICS_CQWXFMA => '长期未消费赠券',
//		MARKETING_ACTIVITY_TYPE_BASICS_LSKHMA => '流失客户赠券',
//		MARKETING_ACTIVITY_TYPE_BASICS_LHYMA => '老会员赠券',
//		MARKETING_ACTIVITY_TYPE_BASICS_HYKHMA => '活跃客户赠券',
//		MARKETING_ACTIVITY_TYPE_BASICS_HYSRMA => '会员生日赠券',
//		MARKETING_ACTIVITY_TYPE_BASICS_JRMA => '节日赠券',
//		MARKETING_ACTIVITY_TYPE_BASICS_HYSJMA => '会员升级赠券',
//		MARKETING_ACTIVITY_TYPE_BASICS_DCXFMMA => '单次消费满赠券',
//		MARKETING_ACTIVITY_TYPE_BASICS_LJXFMMA => '累计消费满赠券',
//		MARKETING_ACTIVITY_TYPE_BASICS_XFCSMMA => '消费次数满赠券'
);
//营销活动-活动时间类型
define('MARKETING_ACTIVITY_TIME_TYPE_SHORT', '1');//短期
define('MARKETING_ACTIVITY_TIME_TYPE_LONG', '2');//长期
$GLOBALS['MARKETING_ACTIVITY_TIME_TYPE'] = array(
		MARKETING_ACTIVITY_TIME_TYPE_SHORT => '短期活动',
		MARKETING_ACTIVITY_TIME_TYPE_LONG => '长期活动'
);
//营销活动-活动群体类型
define('MARKETING_ACTIVITY_TARGET_TYPE_DEFAULT', '1');//默认群体
define('MARKETING_ACTIVITY_TARGET_TYPE_APPOINT', '2');//指定群体
//营销活动-发券方式
define('MARKETING_ACTIVITY_SEND_TYPE_DIRECT_PUT', '1');//直接放入会员卡包
define('MARKETING_ACTIVITY_SEND_TYPE_ALIWX', '2');//服务窗消息公众号消息发券
// define('MARKETING_ACTIVITY_SEND_TYPE_APPOINT', '2');//指定群体
//营销活动-活动状态
define('MARKETING_ACTIVITY_STATUS_NOSTART', '1');//未开始
define('MARKETING_ACTIVITY_STATUS_START', '2');//进行中
define('MARKETING_ACTIVITY_STATUS_END', '3');//已完成
define('MARKETING_ACTIVITY_STATUS_STOP', '4');//已停止
$GLOBALS['__MARKETING_ACTIVITY_STATUS'] = array(
    MARKETING_ACTIVITY_STATUS_NOSTART => '未开始',
    MARKETING_ACTIVITY_STATUS_START => '进行中',
    MARKETING_ACTIVITY_STATUS_END => '已完成',
    MARKETING_ACTIVITY_STATUS_STOP => '已停止'
);
//营销活动-完善资料
define('MARKETING_ACTIVITY_CONDITION_FULL_NAME', '1');//姓名
define('MARKETING_ACTIVITY_CONDITION_SEX', '2');//性别
define('MARKETING_ACTIVITY_CONDITION_AVATAR', '3');//头像
define('MARKETING_ACTIVITY_CONDITION_NICKNAME', '4');//昵称
define('MARKETING_ACTIVITY_CONDITION_BIRTHDAY', '5');//生日
define('MARKETING_ACTIVITY_CONDITION_ID', '6');//身份证
define('MARKETING_ACTIVITY_CONDITION_EMAIL', '7');//邮箱
define('MARKETING_ACTIVITY_CONDITION_MARITAL_STATUS', '8');//婚姻状况
define('MARKETING_ACTIVITY_CONDITION_WORK', '9');//工作
define('MARKETING_ACTIVITY_CONDITION_ADDRESS', '10');//地址
$GLOBALS['__MARKETING_ACTIVITY_CONDITION'] = array(
		MARKETING_ACTIVITY_CONDITION_FULL_NAME => '姓名',
		MARKETING_ACTIVITY_CONDITION_SEX => '性别',
		MARKETING_ACTIVITY_CONDITION_AVATAR => '头像',
		MARKETING_ACTIVITY_CONDITION_NICKNAME => '昵称',
		MARKETING_ACTIVITY_CONDITION_BIRTHDAY => '生日',
		MARKETING_ACTIVITY_CONDITION_ID => '身份证',
		MARKETING_ACTIVITY_CONDITION_EMAIL => '邮箱',
		MARKETING_ACTIVITY_CONDITION_MARITAL_STATUS => '婚姻状况',
		MARKETING_ACTIVITY_CONDITION_WORK => '工作',
		MARKETING_ACTIVITY_CONDITION_ADDRESS => '地址'
);

//营销渠道
define('MARKETING_ACTIVITY_SEND_CHANNEL_WECHAT', '1');//微信
define('MARKETING_ACTIVITY_SEND_CHANNEL_ALIPAY', '2');//支付宝
define('MARKETING_ACTIVITY_SEND_CHANNEL_BOTH', '3');//同时
$GLOBALS['__MARKETING_ACTIVITY_SEND_CHANNEL'] = array(
    MARKETING_ACTIVITY_SEND_CHANNEL_WECHAT => '微信',
    MARKETING_ACTIVITY_SEND_CHANNEL_ALIPAY => '支付宝',
    MARKETING_ACTIVITY_SEND_CHANNEL_BOTH => '同时'
);

//活动时间类型
define('MARKETING_ACTIVITY_TIME_TYPE_ONETIME', '1');//一次性活动
define('MARKETING_ACTIVITY_TIME_TYPE_PERIODICITY', '2');//周期活动
$GLOBALS['__MARKETING_ACTIVITY_TIME_TYPE'] = array(
    MARKETING_ACTIVITY_TIME_TYPE_ONETIME => '一次性活动',
    MARKETING_ACTIVITY_TIME_TYPE_PERIODICITY => '周期活动'
);

//活动发送类型
define('MARKETING_ACTIVITY_SEND_TYPE_TIMING', '1'); //定时推送
define('MARKETING_ACTIVITY_SEND_TYPE_IMMEDIATELY', '2'); //即时
define('MARKETING_ACTIVITY_SEND_TYPE_CRONTAB', '1'); //定义周期
define('MARKETING_ACTIVITY_SEND_TYPE_LONG', '2'); //长期活动
$GLOBALS['__MARKETING_ACTIVITY_ONETIME_SEND_TYPE'] = array(
    MARKETING_ACTIVITY_SEND_TYPE_TIMING => '定时推送',
    MARKETING_ACTIVITY_SEND_TYPE_IMMEDIATELY => '即时',
);
$GLOBALS['__MARKETING_ACTIVITY_PERIODICITY_SEND_TYPE'] = array(
    MARKETING_ACTIVITY_SEND_TYPE_CRONTAB => '定义周期',
    MARKETING_ACTIVITY_SEND_TYPE_LONG => '长期活动',
);


/***************************************东钱湖商城***********************************************/
//入园时间类型
define('DMALL_DPRODUCT_CHECK_TIME_TYPE_NO_LIMIT',1);//不限制
define('DMALL_DPRODUCT_CHECK_TIME_TYPE_DAY_HOUR_MINUTE', 2);//请至少在入院前xx天xx点xx分以前购买
define('DMALL_DPRODUCT_CHECK_TIME_TYPE_HOUR_MINUTE', 3);//请至少在入院前xx个小时xx分钟以前购买


//东钱湖营销活动状态
define('DMALL_ACTIVITY_STATUS_NOT_START', 1);//未开始
define('DMALL_ACTIVITY_STATUS_STARTING', 2);//进行中
define('DMALL_ACTIVITY_STATUS_NO_STOCK', 3);//已抢完
define('DMALL_ACTIVITY_STATUS_END', 4);//已结束
$GLOBALS['__DMALL_ACTIVITY_STATUS'] = array(
    DMALL_ACTIVITY_STATUS_NOT_START => '未开始',
    DMALL_ACTIVITY_STATUS_STARTING => '进行中',
    DMALL_ACTIVITY_STATUS_NO_STOCK => '已抢完',
    DMALL_ACTIVITY_STATUS_END => '已结束',
);


//东钱湖活动类型
define('DMALL_ACTIVITY_TYPE_SDLJ', 1);//首单立减
define('DMALL_ACTIVITY_TYPE_ZFL', 2);//周福利
$GLOBALS['__DMALL_ACTIVITY_TYPE'] = array(
    DMALL_ACTIVITY_TYPE_SDLJ => '首单立减',
    DMALL_ACTIVITY_TYPE_ZFL => '周福利'
);
    

/*******************************************优惠券************************************************/
//优惠券核销渠道
define('COUPONS_USE_CHANNEL_OFFLINE', 1);//线下使用
define('COUPONS_USE_CHANNEL_ONLINE', 2);//线上使用
define('COUPONS_USE_CHANNEL_ALL', 3);//线上线下通用

//优惠券类型
define('COUPON_TYPE_REDENVELOPE', '1');//红包(作废)
define('COUPON_TYPE_CASH', '2');//代金券
define('COUPON_TYPE_DISCOUNT', '3');//折扣券
define('COUPON_TYPE_EXCHANGE', '4');//兑换券
$GLOBALS['COUPON_TYPE'] = array(
    COUPON_TYPE_REDENVELOPE => '红包',
    COUPON_TYPE_CASH => '代金券',
    COUPON_TYPE_DISCOUNT => '折扣券',
    COUPON_TYPE_EXCHANGE => '兑换券'
);

//有效时间类型
define('VALID_TIME_TYPE_FIXED', '1');//固定时间
define('VALID_TIME_TYPE_RELATIVE', '2');//相对时间
$GLOBALS['VALID_TIME_TYPE'] = array(
    VALID_TIME_TYPE_FIXED => '固定时间',
    VALID_TIME_TYPE_RELATIVE => '相对时间'
);

//可用时段
define('COUPON_AVAILABLE_TIME_ALL', '1');//全部时段
define('COUPON_AVAILABLE_TIME_PART', '2');//部分时段

//是否能与会员折扣同用 1 不能 2 能
define('IF_WITH_USERDISCOUNT_NO','1');
define('IF_WITH_USERDISCOUNT_YES','2');
$GLOBALS['IF_WITH_USERDISCOUNT'] = array(
    IF_WITH_USERDISCOUNT_NO => '不能',
    IF_WITH_USERDISCOUNT_YES => '能'
);

//用户是否可以分享领取链接 1可以 2不可以
define('IF_SHARE_YES', '1');
define('IF_SHARE_NO', '2');

//可否转增其他好友 1 能 2不能
define('IF_GIVE_YES', '1');
define('IF_GIVE_NO', '2');

//优惠券门店限制类型 1全部适用 2指定门店适用
define('COUPONS_STORE_LIMIT_TYPE_ALL', '1');//全部适用
define('COUPONS_STORE_LIMIT_TYPE_PART', '2');//指定门店适用

//优惠券是否失效 1未失效 2已失效
define('IF_INVALID_NO', '1');
define('IF_INVALID_YES', '2');

//微信审核状态   1审核中 2已通过 3未通过
define('WX_CHECK_AUDIT', '1');
define('WX_CHECK_PASS', '2');
define('WX_CHECK_NOTPASS', '3');

$GLOBALS['WX_CHECK'] = array(
    WX_CHECK_AUDIT => '审核中',
    WX_CHECK_PASS => '已通过',
    WX_CHECK_NOTPASS => '未通过'   
);

//营销入口跳转链接类型
define('COUPON_MARKETING_ENTRANCE_URL_TYPE_COMMON', '1');//常用链接
define('COUPON_MARKETING_ENTRANCE_URL_TYPE_CUSTOM', '2');//自定义链接

//优惠券投放状态
define('COUPON_RELEASE_STATUS_NOTRELEASE', 1);//未投放
define('COUPON_RELEASE_STATUS_RELEASED', 2);//已投放
$GLOBALS['COUPON_RELEASE_STATUS'] = array(
    COUPON_RELEASE_STATUS_NOTRELEASE => '未投放',
    COUPON_RELEASE_STATUS_RELEASED => '已投放'
);

//券码使用状态
define('COUPON_CODE_STATUS_NOTUSED', 1);//未使用
define('COUPON_CODE_STATUS_USED', 2);//已使用


/*******************************************应用市场-酒店宾馆订房**********************************************/
//预定订单状态
define('HOTEL_ORDER_STATUS_WAITING', '1');//待确定
define('HOTEL_ORDER_STATUS_CONFIRM', '2');//已确定
define('HOTEL_ORDER_STATUS_REFUSE', '3');//已拒绝
define('HOTEL_ORDER_STATUS_CANCEL', '4');//已取消
define('HOTEL_ORDER_STATUS_CHECKIN', '5');//已入住
$GLOBALS['__HOTEL_ORDER_STATUS'] = array(
    HOTEL_ORDER_STATUS_WAITING => '待确定',
    HOTEL_ORDER_STATUS_CONFIRM => '已确定',
    HOTEL_ORDER_STATUS_REFUSE => '已拒绝',
    HOTEL_ORDER_STATUS_CANCEL => '已取消',
    HOTEL_ORDER_STATUS_CHECKIN => '已入住',
    );
    

/*****************************************玩券管家***************************************************/
//玩券管家版本
define('WANQUAN_TYPE_CASH', '1');//收银版
define('WANQUAN_TYPE_MARKETING', '2');//营销版
// define('WANQUAN_TYPE_CASH', '3');//
// define('WANQUAN_TYPE_CASH', '4');//
$GLOBALS['__WANQUAN_TYPE'] = array(
    WANQUAN_TYPE_CASH => '收银版',
    WANQUAN_TYPE_MARKETING => '营销版',
);

/********************************************积分*****************************************************/
//积分记录类型
define('BALANCE_OF_PAYMENTS_INCOME', 1);//收入
define('BALANCE_OF_PAYMENTS_EXPEND', 2);//支出
define('BALANCE_OF_PAYMENTS_REFUND', 3);//退分
$GLOBALS['__BALANCE_OF_PAYMENTS'] = array(
    BALANCE_OF_PAYMENTS_INCOME => '收入',
    BALANCE_OF_PAYMENTS_EXPEND => '支出',
    BALANCE_OF_PAYMENTS_REFUND => '退分'
);

//积分来源类型及支出类型
define('USER_POINTS_DETAIL_FROM_TRADE', 1);//消费得积分
define('USER_POINTS_DETAIL_FROM_STORED', 2);//储值得积分
define('USER_POINTS_DETAIL_FROM_SIGN', 3);//签到得积分
define('USER_POINTS_DETAIL_FROM_FOLLOW', 4);//关注得积分
define('USER_POINTS_DETAIL_FROM_PERFECT', 5);//完善资料得积分
define('USER_POINTS_DETAIL_FROM_CLEAN', 6);//定时清积分
define('USER_POINTS_DETAIL_FROM_REFUND_TRADE', 7);//消费退款退分
define('USER_POINTS_DETAIL_FROM_REFUND_STORED', 8);//储值撤销退分
define('USER_POINTS_DETAIL_FROM_CONSUME_EXCHANGE_COUPON', 9);//积分换券消费
$GLOBALS['__USER_POINTS_DETAIL_FROM'] = array(
    USER_POINTS_DETAIL_FROM_TRADE => '消费',
    USER_POINTS_DETAIL_FROM_STORED => '储值',
    USER_POINTS_DETAIL_FROM_SIGN => '签到',
    USER_POINTS_DETAIL_FROM_FOLLOW => '关注',
    USER_POINTS_DETAIL_FROM_PERFECT => '完善资料',
    USER_POINTS_DETAIL_FROM_CLEAN => '积分清理',
    USER_POINTS_DETAIL_FROM_REFUND_TRADE => '消费退款退分',
    USER_POINTS_DETAIL_FROM_REFUND_STORED => '储值撤销退分',
    USER_POINTS_DETAIL_FROM_CONSUME_EXCHANGE_COUPON => '积分换券消费'
);


/******************************************注册完善资料***********************************************/
//资料必填项
define('MERCHANT_AUTH_SET_NAME', 1);//会员姓名
define('MERCHANT_AUTH_SET_ADDRESS', 4);//通讯地址
define('MERCHANT_AUTH_SET_SEX', 2);//会员性别
define('MERCHANT_AUTH_SET_BIRTHDAY', 3);//会员生日
define('MERCHANT_AUTH_SET_ID', 5);//身份证号
define('MERCHANT_AUTH_SET_EMAIL', 6);//邮箱
define('MERCHANT_AUTH_SET_MARITAL_STATUS', 7);//婚姻状况
define('MERCHANT_AUTH_SET_WORK', 8);//工作


//用户是否完善注册资料
define('USER_IF_PERFECT_NO', 1);//未完善
define('USER_IF_PERFECT_YES', 2);//已完善

/****************************************营销版门店续费升级****************************************************/
//收费模式 按月收费 按年收费
define('STORE_ORDER_PAY_TYPE_MONTH', '1');//按月
define('STORE_ORDER_PAY_TYPE_YEAR', '2');//按年

//活动价格(月费，年费)
define('STORE_ACTIVITY_MONTH_FEE', 98);//优惠月数
define('STORE_ACTIVITY_YEAR_FEE', 980);//优惠年数

//原价格(月费，年费)
define('STORE_MONTH_FEE', 380);//优惠月数
define('STORE_YEAR_FEE', 3800);//优惠年数

//活动时间（优惠月费98，年费980）
define('STORE_ACTIVITY_START_TIME', '2016-07-01 00:00:00');//开始时间
define('STORE_ACTIVITY_END_TIME', '2017-07-01 00:00:00');//结束时间

//优惠月数
define('STORE_ACTIVITY_DISCOUNT_MONTH_NUM', 36);//优惠月数

/***********************************************积分规则***************************************************/
//积分规则类型
define('POINT_RULE_TYPE_STORED', '1');//储值
define('POINT_RULE_TYPE_POINT_CLEAN', '2');//积分清理

//是否需要开发票
define('STORE_ORDER_IF_INVOICE_YES', '1');//是
define('STORE_ORDER_IF_INVOICE_NO', '2');//否

//是否储值支付获积分
define('POINT_RULE_IF_STOREDPAY_GET_POINT_NO', 1);//否
define('POINT_RULE_IF_STOREDPAY_GET_POINT_YES', 2);//是

//积分清理周期
define('POINT_RULE_CYCLE_YEAR', 1);//每年

/*************************************************会员等级****************************************************/
//会员等级规则类型
define('USER_GRADE_RULE_TYPE_ALLPOINT', '1');//累计积分
define('USER_GRADE_RULE_TYPE_YEARPOINT', '2');//年累计积分
define('USER_GRADE_RULE_TYPE_ALLTRADE', '3');//累计消费金额
define('USER_GRADE_RULE_TYPE_YEARTRADE', '4');//年累计消费金额
$GLOBALS['__USER_POINTS_DETAIL_FROM'] = array(
    USER_GRADE_RULE_TYPE_ALLPOINT => '累计积分',
    USER_GRADE_RULE_TYPE_YEARPOINT => '年累计积分',
    USER_GRADE_RULE_TYPE_ALLTRADE => '累计消费金额',
    USER_GRADE_RULE_TYPE_YEARTRADE => '年累计消费金额'
);

/************************************************商户******************************************************/
//邮箱注册邮箱确认状态
define('MERCHANT_EAMIL_CONFIRM_TOBECONFIRM', 1);//待确认
define('MERCHANT_EAMIL_CONFIRM_CONFIRMED', 2);//已确认

//数据库操作
define('ERROR_DATA_BASE_ADD', '20200');//数据库添加失败
define('ERROR_DATA_BASE_EDIT', '20201');//数据库修改失败
define('ERROR_DATA_BASE_SELECT', '20202');//数据库查询失败
define('ERROR_DATA_BASE_DELETE', '20203');//数据库删除失败

//发送方邮箱
define('POST_EMAIL_ACCOUNT', 'gul@rentongtech.com');
define('POST_EMAIL_PWD', 'Tt2013065');

//业主审核状态
define('PROPRIETOR_VERIFY_STATUS_PENDING_AUDIT', '1');//待审核
define('PROPRIETOR_VERIFY_STATUS_PASS', '2');//通过
define('PROPRIETOR_VERIFY_STATUS_REJECT', '3');//未通过
$GLOBALS['__PROPRIETOR_VERIFY_STATUS'] = array(
	PROPRIETOR_VERIFY_STATUS_PENDING_AUDIT => '待审核',
	PROPRIETOR_VERIFY_STATUS_PASS => '成功',
	PROPRIETOR_VERIFY_STATUS_REJECT => '不成功',
);

//业主类型
define('PROPRIETOR_TYPE_OWNER', '1');//业主
define('PROPRIETOR_TYPE_TENEMENT', '2');//租户
$GLOBALS['__PROPRIETOR_TYPE'] = array(
	PROPRIETOR_TYPE_OWNER => '业主',
	PROPRIETOR_TYPE_TENEMENT => '租户',
);

//订单类型
define('FEEORDER_TYPE_WATER_FEE', '1');//水费
define('FEEORDER_TYPE_ELECTRICITY_FEE', '2');//电费
define('FEEORDER_TYPE_PROPERTY_FEE', '3');//物业费
define('FEEORDER_TYPE_PARKING_FEE', '4');//停车费

//区域类型
define('REPORT_REPAIR_RECORD_TYPE_OUTDOOR', '1');//室外
define('REPORT_REPAIR_RECORD_TYPE_INDOOR', '2');//室内
$GLOBALS['REPORT_REPAIR_RECORD_TYPE'] = array(
		REPORT_REPAIR_RECORD_TYPE_INDOOR => '室内区域',
		REPORT_REPAIR_RECORD_TYPE_OUTDOOR => '室外区域');
//报修状态
define('REPORT_REPAIR_RECORD_STATUS_WAITING', '1');//待报修
define('REPORT_REPAIR_RECORD_STATUS_COMPLETE', '2');//已报修
$GLOBALS['REPORT_REPAIR_RECORD_STATUS'] = array(
		REPORT_REPAIR_RECORD_STATUS_WAITING => '待报修',
		REPORT_REPAIR_RECORD_STATUS_COMPLETE => '已报修'
);

//电费类型
define('COMMUNITY_ELECTRICITY_FEE_SET_DAYPARTING', '1');//分时段
define('COMMUNITY_ELECTRICITY_FEE_SET_NODAYPARTING', '2');//不分时段
$GLOBALS['__COMMUNITY_ELECTRICITY_FEE_SET'] = array(
	COMMUNITY_ELECTRICITY_FEE_SET_DAYPARTING => '分时段',
	COMMUNITY_ELECTRICITY_FEE_SET_NODAYPARTING => '不分时段');

//停车费类型
define('COMMUNITY_PARKING_FEE_SET_OVERGROUND', '1');//地上停车
define('COMMUNITY_PARKING_FEE_SET_UNDERGROUND','2');//地下停车
$GLOBALS['__COMMUNITY_PARKING_FEE_SET'] = array(
	COMMUNITY_PARKING_FEE_SET_OVERGROUND => '地上停车',
	COMMUNITY_PARKING_FEE_SET_UNDERGROUND => '地下停车'
);

/***************************************会员分组*******************************************************/
define('GROUP_TYPE_DYNAMIC', 1);
define('GROUP_TYPE_STATIC', 2);
define('GROUP_TYPE_CUSTOM', 3);
$GLOBALS['__GROUP_TYPES'] = array(
	GROUP_TYPE_DYNAMIC => '动态分组',
	GROUP_TYPE_STATIC  => '静态分组',
	GROUP_TYPE_CUSTOM  => '自定义分组'
);

//素材渠道
define('MATERIAL_CHANNEL_TYPE_WECHAT', '1');//微信公众号
define('MATERIAL_CHANNEL_TYPE_ALIPAY', '2');//支付宝服务窗
