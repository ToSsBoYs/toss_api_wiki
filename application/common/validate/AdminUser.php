<?php
/**
 * Created by PhpStorm.
 * User: xun
 * Date: 2016/7/28
 * Time: 16:52
 */

namespace app\common\validate;
use think\Validate;

class AdminUser extends Validate{
    protected $rule = [
        'user_name'=>'require',
        'user_password'=>'require',
        'verify'    =>'require|captcha'
    ];

    protected $message = [
        'user_name.require'  =>  '用户名必须',
        'user_password.require'     =>  '密码必须',
        'verify.require'    =>  '验证码必须',
        'verify.captcha'    =>  '验证码错误',
    ];

    protected $scene = [
        'add'   =>  ['user_name','user_password'],
        'edit'  =>  ['user_name'],
        'login' =>  ['user_name','user_password','verify'],
    ];
}