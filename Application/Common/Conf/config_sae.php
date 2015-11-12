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
    'SALT' => 'flippancy',

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

    'STATE1' => 'shangxian',
    'STATE2' => 'likai',
    'SESSIONID' => 'SESSIONID',
    'LOGINNAME' => 'yyz',
    'PASSWORD' => 'c7e7de03339359085b7a19c3be86bda5',
);
?>