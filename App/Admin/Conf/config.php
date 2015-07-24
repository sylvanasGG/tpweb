<?php
return array(
	//'配置项'=>'配置值'
    'URL_ROUTE_RULES'=>array(
        //'admin/index' => 'Admin/Index/index',
        //'index/show' => 'Admin/Index/show'
    ),
    'TMPL_PARSE_STRING'=>array(
        '__PUBLIC__'=>__ROOT__."/Public",
    ),
    //开启布局
    'LAYOUT_ON'=>true,
    'LAYOUT_NAME'=>'layouts/admin',
    // 设置默认的模板主题
    //'DEFAULT_THEME'    =>    'admin'

    'LAYOUT_MENU'=>array(
        'article' => [
            'treeView' => ['name' => '文章', 'icon' => 'fa-pagelines', 'url' => '#', 'actionName' => 'Article'],
            'treeViewMenu' => [
                ['name' => '文章列表', 'icon' => 'fa-circle-o', 'url' => "Article/index", 'actionName' => "Article/index",'auth' =>[
                    ''
                ]],
                ['name' => '新增文章', 'icon' => 'fa-circle-o', 'url' => "Article/create", 'actionName' => 'Article/create','auth' =>[
                    ''
                ]]
            ]
        ],
        'comment' => [
            'treeView' => ['name' => '评论', 'icon' => 'fa-pencil', 'url' => '#', 'actionName' => 'Comment'],
            'treeViewMenu' => [
                ['name' => '评论列表', 'icon' => 'fa-circle-o', 'url' => "Comment/index", 'actionName' => 'Comment/index','auth' =>[
                    ''
                ]]
            ]
        ],
        'user' => [
            'treeView' => ['name' => '用户', 'icon' => 'fa-user', 'url' => '#', 'actionName' => 'User'],
            'treeViewMenu' => [
                ['name' => '用户列表', 'icon' => 'fa-circle-o', 'url' => "User/index", 'actionName' => 'User/index','auth' =>[
                    ''
                ]],
                ['name' => '增加用户', 'icon' => 'fa-circle-o', 'url' => "User/showAdd", 'actionName' => 'User/showAdd','auth' =>[
                    ''
                ]],
                ['name' => '管理组权限', 'icon' => 'fa-circle-o', 'url' => "Perm/showGroupsList", 'actionName' => 'Perm/showGroupsList','auth' =>[
                    ''
                ]],
            ]
        ],
    ),



);