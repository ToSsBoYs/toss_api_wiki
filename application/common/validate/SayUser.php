<?php
/**
 * Created by PhpStorm.
 * User: 珣
 * Date: 2016/8/24
 * Time: 22:08
 */

namespace app\common\validate;


use think\Validate;

class SayUser extends Validate
{
    protected $rule = [
        'user_mobile'=>['regex'=>'/^[1]{1}[3|5|7|8]{1}[0-9]{9}$/','require'],
        'user_password'=>'require|confirm:user_re_password|length:6,20',
        'user_re_password'=>'require',
        'verify'    => 'require'
    ];

    protected $message = [
        'user_mobile.require'  =>  '40016',
        'user_mobile.regex'  =>  '40021',
        'user_password.require'  =>  '40017',
        'user_password.confirm'  =>  '40019',
        'user_password.length'  =>  '40022',
        'user_re_password.require'  =>  '40018',
        'verify.require'  =>  '40020',
    ];

    protected $scene = [
        'add'   =>  ['user_mobile','user_password','user_re_password','verify'],
        'send'   =>  ['user_mobile'],
    ];
}