<?php
namespace App;
use Admin\IndexController;
return array(
	//'配置项'=>'配置值'
    // 开启路由
    'URL_ROUTER_ON'   => true,

    // 多个伪静态后缀设置 用|分割
    'URL_HTML_SUFFIX'=>'html|shtml|xml',
    // URL禁止访问的后缀设置
    'URL_DENY_SUFFIX' => 'pdf|ico|png|gif|jpg',
    //不区分大小写访问路由
    'URL_CASE_INSENSITIVE' =>true,
    //模板标签
    'TMPL_L_DELIM'=>'{',
    'TMPL_R_DELIM'=>'}',
    //数据库配置信息
    'DB_TYPE'   => 'mysql', // 数据库类型
    'DB_HOST'   => 'localhost', // 服务器地址
    'DB_NAME'   => 'tpweb', // 数据库名
    'DB_USER'   => 'root', // 用户名
    'DB_PWD'    => 'root', // 密码
    'DB_PORT'   => 3306, // 端口
    //'DB_PREFIX' => '', // 数据库表前缀
    'DB_CHARSET'=> 'utf8', // 字符集
    'DB_DEBUG'  =>  TRUE, // 数据库调试模式 开启后可以记录SQL日志 3.2.3新增
    'HTML_CACHE_ON' => false, // 默认关闭静态缓存
    'DB_FIELD_CACHE'=>false,
    'HTML_CACHE_ON'=>false,
    "LOAD_EXT_FILE"=>"Fun,ArticleLib,SendMail",
    //设置系统管理员
    'SYSTEM_ADMIN' => 1,

    // 配置邮件发送服务器
    'MAIL_HOST' =>'smtp.qq.com',//smtp服务器的名称
    'MAIL_SMTPAUTH' =>TRUE, //启用smtp认证
    'MAIL_USERNAME' =>'406874221@qq.com',//你的邮箱名
    'MAIL_FROM' =>'406874221@qq.com',//发件人地址
    'MAIL_FROMNAME'=>'first_blood',//发件人姓名
    'MAIL_PASSWORD' =>'zz123456',//邮箱密码
    'MAIL_CHARSET' =>'utf-8',//设置邮件编码
    'MAIL_ISHTML' =>TRUE, // 是否HTML格式邮件
);