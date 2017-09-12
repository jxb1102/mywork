<?php
return array(
	//连接数据库
	'DB_TYPE'   => 'mysql', // 数据库类型
	'DB_HOST'   => '127.0.0.1', // 服务器地址
	'DB_NAME'   => 'mywork', // 数据库名
	'DB_USER'   => 'root', // 用户名
	'DB_PWD'    => '', // 密码  kemgqqXPkwsremW
	'DB_PORT'   => 3306, // 端口
	'DB_PREFIX' => 'jixb_', // 数据库表前缀
	'DB_CHARSET'=> 'utf8', // 字符集

	//子域名配置
	/*'APP_SUB_DOMAIN_DEPLOY'   =>    1, // 开启子域名配置
	'APP_SUB_DOMAIN_RULES'    =>    array(
	'www.daiyun.com' =>'Home',
	),*/

	//权限认证
	
	'URL_MODEL'=>2,
    'TMPL_FILE_DEPR'=>'_',
    // 'URL_PATHINFO_DEPR'=>'-',
    'ADMINISTRATOR'=>array('1'),
    //'SHOW_PAGE_TRACE'    => 1,
	'DEFAULT_THEME'         =>  'default',
    'TMPL_DETECT_THEME' => true,
    'THEME_LIST' => 'default,zzqss',
    'DEFAULT_MODULE'=>'Home',
);