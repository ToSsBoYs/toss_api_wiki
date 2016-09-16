<?php
return [
    // 默认模块名
//    'default_module'        => 'admin',
    // 默认控制器名
    'default_controller'    => 'Index',
    // 默认操作名
    'default_action'        => 'index',

    //分页配置
    'paginate'               => [
        'type'     => 'admin\adminpage',
        'var_page' => 'p',
    ],
//默认错误跳转对应的模板文件
    'dispatch_error_tmpl' => 'common@waring/jump',
//默认成功跳转对应的模板文件
    'dispatch_success_tmpl' => 'common@waring/jump',

    'admin_static' =>  '/static',
    'upload_path'=>  '',

    'admin_is_trator' =>  [1],

];