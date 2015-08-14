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
        ''=>array(
            'treeView' => array('name' => '仪表盘', 'icon' => 'fa-dashboard', 'url' => '#', 'actionName' => 'Admin'),
            'treeViewMenu' => array(
                array('name' => '首页', 'icon' => 'fa-circle-o', 'url' => "Admin/index", 'actionName' => "Admin/index",'auth' =>array()
                ),

            )
        ),
        'article' => array(
            'treeView' => array('name' => '文章', 'icon' => 'fa-pagelines', 'url' => '#', 'actionName' => 'Article'),
            'treeViewMenu' => array(
                array('name' => '文章列表', 'icon' => 'fa-circle-o', 'url' => "Article/index", 'actionName' => "Article/index",'auth' =>array()
                ),
                array('name' => '新增文章', 'icon' => 'fa-circle-o', 'url' => "Article/create", 'actionName' => 'Article/create','auth' =>array(
                    ''
                ))
        )
        ),
        'comment' => array(
            'treeView' => array('name' => '评论', 'icon' => 'fa-pencil', 'url' => '#', 'actionName' => 'Comment'),
            'treeViewMenu' => array(
                array('name' => '评论列表', 'icon' => 'fa-circle-o', 'url' => "Comment/index", 'actionName' => 'Comment/index','auth' =>array(
                    ''
                ))
            )
        ),
        'user' => array(
            'treeView' => array('name' => '管理员', 'icon' => 'fa-user', 'url' => '#', 'actionName' => 'User'),
            'treeViewMenu' => array(
                array('name' => '管理员列表', 'icon' => 'fa-circle-o', 'url' => "User/index", 'actionName' => 'User/index','auth' =>array(
                    ''
                )),
                array('name' => '增加管理员', 'icon' => 'fa-circle-o', 'url' => "User/showAdd", 'actionName' => 'User/showAdd','auth' =>array(
                    ''
                )),
                array('name' => '管理组权限', 'icon' => 'fa-circle-o', 'url' => "Perm/showGroupsList", 'actionName' => 'Perm/showGroupsList','auth' =>array(
                    ''
                )),

            )
        ),
        'visitor' => array(
            'treeView' => array('name' => '用户', 'icon' => 'fa-comments', 'url' => '#', 'actionName' => 'Visitor'),
            'treeViewMenu' => array(
                array('name' => '访问者列表', 'icon' => 'fa-circle-o', 'url' => "Visitor/index", 'actionName' => 'Visitor/index','auth' =>array(
                    ''
                )),
                array('name' => '注册用户', 'icon' => 'fa-circle-o', 'url' => "Visitor/index", 'actionName' => 'Visitor/index','auth' =>array(
                    ''
                )),

            )
        ),
        'example' => array(
            'treeView' => array('name' => '示例', 'icon' => 'fa-folder', 'url' => '#', 'actionName' => 'Example'),
            'treeViewMenu' => array(
                array('name' => '表格', 'icon' => 'fa-circle-o', 'url' => "Example/index", 'actionName' => 'Example/index','auth' =>array(
                    ''
                )),
                
            )
        ),
    ),



);