<?php
return array(
	//PDO专用定义
	'DB_TYPE'=>'mysql',	//数据库类型
	'DB_USER'=>'root', //用户名 
	'DB_PWD'=>'123456', //密码
	'DB_PREFIX'=>'zyyj_',	//数据库表前缀
	'DB_HOST' => '127.0.0.1',
	'DB_NAME' => 'zyyj',
	// 'DB_DSN'=>'mysql:host=localhost;dbname=zyyj;charset=UTF8',
	'DB_PORT'=>'3306',
	'SHOW_PAGE_TRACE' =>true,//开启页面调试工具
	'TMPL_TEMPLATE_SUFFIX'=>'.php',//修改视图后缀名
	'TMPL_FILE_DEPR'=>'_',	//用下划线代替目录层次
	'TMPL_PARSE_STRING' =>array(
		//'__UPLOAD__' => '/Uploads', // 增加新的上传路径替换规则
		'__SITE__'	=>'http://localhost/zyyj/',
	),
	'__HEADER__'	=>'./Application/Home/View/header.php',
	//初始化session
	'SESSION_OPTIONS'=>array(
		'name'=>'session_id',
		'expire'=>3600,
	),
);