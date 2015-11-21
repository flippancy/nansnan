<?php
return array(
	//'配置项'=>'配置值'
    'UPLOAD_SITEIMG_QINIU' => array ( 
                    'maxSize' => 5 * 1024 * 1024,//文件大小
                    'rootPath' => './',
                    'saveName' => array ('uniqid', ''),
                    'driver' => 'Qiniu',
                    'driverConfig' => array (
                            'secrectKey' => '-fxARUAKUBGULVNH1-pF-ApqbYVdRga4FBrsdPqd', 
                            'accessKey' => 'Y86vP1ZhTFtOUP8MkLJz3cPCFZ-KP4FR0o8qUdFR',
                            'domain' => '7xl0od.com1.z0.glb.clouddn.com',
                            'bucket' => 'flippancy',
                )
    ),

    'DB_TYPE' => 'mysql',
    'DB_HOST' => '115.28.69.109',
    'DB_NAME' => 'nsn',
    'DB_USER' => 'root',
    'DB_PWD' => 'myhengge',
    'DB_PORT' => '3306',
    'DB_PREFIX' => 'ma_',

    'TMPL_PARSE_STRING' =>array(
        '__PUBLIC__'  => __ROOT__.'/Public',
        '__HJS__'     => __ROOT__.'/Public/home/js',
        '__HCSS__'    => __ROOT__.'/Public/home/css',
        '__HIMG__'    => __ROOT__.'/Public/home/img',
        '__AJS__'     => __ROOT__.'/Public/admin/js',
        '__ACSS__'    => __ROOT__.'/Public/admin/css',
        '__AIMG__'    => __ROOT__.'/Public/admin/img',
        '__UPLOADS__' => __ROOT__.'/Uploads',
    ),

    'ROOMID' => 'ROOMID',
    'STATE1' => 'shangxian',
    'STATE2' => 'likai',
    'USERID' => 'USERID',
    'SESSIONID' => 'SESSIONID',
    'LOGINNAME' => 'LOGINNAME',

	'THINK_EMAIL' => array(

	'SMTP_HOST' => 'smtp.163.com', //SMTP服务器

	'SMTP_PORT' => '465', //SMTP服务器端口

	'SMTP_USER' => '13160673215@163.com', //SMTP服务器用户名

	'SMTP_PASS' => 'chenjianxiang123', //SMTP服务器密码

	'FROM_EMAIL' => '13160673215@163.com', //发件人EMAIL

	'FROM_NAME' => 'flippancy', //发件人名称

	'REPLY_EMAIL' => '13160673215@163.com', //回复EMAIL（留空则为发件人EMAIL）

	'REPLY_NAME' => 'flippancy', //回复名称（留空则为发件人名称）

	),
);
?>