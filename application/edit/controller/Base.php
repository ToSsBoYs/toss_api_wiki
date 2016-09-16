<?php
/**
 * Created by PhpStorm.
 * User: xun
 * Date: 2016/7/27
 * Time: 9:26
 */

namespace app\edit\controller;
use think\Controller;
use think\Request;

class Base extends Controller{
    public function _initialize()
    {
        $aid=is_login(config('myconfig.admin_cookie_key'),db('AdminUser'),model('common/AdminUser'));

        if( !$aid ){// 还没登录 跳转到登录页面
            $this->redirect(url('User/login'));
        }
        //权限验证
        $this->aid=$aid;
        $request=Request::instance();
//        if (!authCheck($request->module(),$request->controller(),$request->action(),$this->aid))        {
//            $this->error('你没有权限!');
//        }
    }
}