<?php
$tempp=$_GET['t']?$_GET['t']:(cookie('think_template')?cookie('think_template'):C('DEFAULT_THEME'));
// session(array('expire'=>10));
return array(
	//'配置项'=>'配置值'
	'DEFAULT_THEME'         =>  'default',
    'TMPL_DETECT_THEME' => true,
    'THEME_LIST' => 'default,zzqss',
    'LANG_SWITCH_ON' => true,
    'LANG_AUTO_DETECT' => true, // 自动侦测语言 开启多语言功能后有效
    'LANG_LIST'        => 'zh-cn,en-us', // 允许切换的语言列表 用逗号分隔
    'VAR_LANGUAGE'     => 'l', // 默认语言切换变量
    'TMPL_PARSE_STRING' => array(
        '__IMG__'    => __ROOT__ . '/Public/Home/'.$tempp.'/images',
        '__CSS__'    => __ROOT__ . '/Public/Home/'.$tempp.'/css',
        '__JS__'     => __ROOT__ . '/Public/Home/'.$tempp.'/js',
        '__UPLOADS__'=> __ROOT__ . '/Public/Uploads',
        '__AVATARS__'=>__ROOT__ . '/Public/Home/avatars',
        '__COMMONJS__'=>__ROOT__ . '/Public/Common/js',
    ),
    'SESSION_OPTIONS'=>array(
        'expire'=>3600,
    )
);


       
