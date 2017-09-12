<?php
return array(
	'TMPL_PARSE_STRING' => array(
        '__IMG__'    => __ROOT__ . '/Public/Admin/images',
        '__CSS__'    => __ROOT__ . '/Public/Admin/css',
        '__JS__'     => __ROOT__ . '/Public/Admin/js',
        '__UPLOADS__'=> __ROOT__ . '/Public/Uploads',
        '__AVATARS__'=>__ROOT__ . '/Public/Admin/avatars',
        '__COMMONJS__'=>__ROOT__ . '/Public/Common/js',
    ),
    'AUTH_CONFIG'=>array(
        'AUTH_ON' => true, //认证开关
        'AUTH_TYPE' => 1, // 认证方式，1为时时认证；2为登录认证。
        'AUTH_GROUP' => 'ls_auth_group', //用户组数据表名
        'AUTH_GROUP_ACCESS' => 'ls_auth_group_access', //用户组明细表
        'AUTH_RULE' => 'ls_auth_rule', //权限规则表
        'AUTH_USER' => 'ls_members'//用户信息表
    ),
    'DEFAULT_THEME'         =>  'default',
    'TMPL_DETECT_THEME' => true,
    'THEME_LIST' => 'default,zzqss',
    'SESSION_OPTIONS'=>array(
        'expire'=>10800,
    )
    
);