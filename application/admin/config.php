<?php
return [
    // 默认模块名
//    'default_module'        => 'admin',
    // 默认控制器名
    'default_controller'    => 'Lione',
    // 默认操作名
    'default_action'        => 'index',

    // +----------------------------------------------------------------------
    // | 会话设置
    // +----------------------------------------------------------------------

    'session'                => [
        'id'             => '',
        // SESSION_ID的提交变量,解决flash上传跨域
        'var_session_id' => '',
        // SESSION 前缀
        'prefix'         => 'admin_',
        // 驱动方式 支持redis memcache memcached
        'type'           => '',
        // 是否自动开启 SESSION
        'auto_start'     => true,
    ],


    // +----------------------------------------------------------------------
    // | Cookie设置
    // +----------------------------------------------------------------------
    'cookie'                 => [
        // cookie 名称前缀
        'prefix'    => 'admin_',
        // cookie 保存时间
        'expire'    => 0,
        // cookie 保存路径
        'path'      => '/',
        // cookie 有效域名
        'domain'    => '',
        //  cookie 启用安全传输
        'secure'    => false,
        // httponly设置
        'httponly'  => '',
        // 是否使用 setcookie
        'setcookie' => true,
    ],
    //分页配置
    'paginate'               => [
        'type'     => 'admin\adminpage',
        'var_page' => 'p',
    ],
//默认错误跳转对应的模板文件
    'dispatch_error_tmpl' => 'waring/jump',
//默认成功跳转对应的模板文件
    'dispatch_success_tmpl' => 'waring/jump',

    'admin_static' =>  '/static',
    'upload_path'=>  '',

    'admin_is_trator' =>  [1],

];