<?php
return array(
	//'配置项'=>'配置值'
    'URL_ROUTE_RULES'=>array(
        'index/index' => 'Home/Index/index',
        //'/^article\/(\d+)$/'   => 'Home/Article/show',
        //'post/:name' => 'Home/Article/showArticleFeilei'
    ),
    'TMPL_PARSE_STRING'=>array(
        '__PUBLIC__'=>__ROOT__."/Public",
    ),
    //开启布局
    'LAYOUT_ON'=>true,
    'LAYOUT_NAME'=>'layouts/home',
);