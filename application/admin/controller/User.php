<?php
/**
 * Created by PhpStorm.
 * User: 珣
 * Date: 2016/7/30
 * Time: 16:13
 */

namespace app\admin\controller;
use think\Controller;

class User extends Controller{
    public function login(){
        if(request()->isPost()){
            $user_name=input('post.user_name');
            $user_password=input('post.user_password');
            $verify=input('post.verify');
            $remember=input('post.remember');
            $data=[
                'user_name' =>   $user_name,
                'user_password' => $user_password,
                'verify'        =>  $verify,
            ];
            $result=$this->validate($data,'AdminUser.login');
//            if($result!==true){
//                $this->error($result);
//            }else{
                $model=model('common/AdminUser');
                $uid=$model->login($user_name,$user_password,$type = 1,$remember);
                if (0 < $uid) { // UC登录成功
                    /* 登录用户 */
                    return $this->success('登陆成功!','Lione/index');
                } else { // 登录失败
                    switch ($uid) {
                        case - 1 :
                            $error = '用户不存在或被禁用！';
                            break; // 系统级别禁用
                        case - 2 :
                            $error = '密码错误！';
                            break;
                        default :
                            $error = '未知错误！';
                            break; // 0-接口参数错误（调试阶段使用）
                    }
                    $this->error ( $error );
                }

//            }
        }else{
            return $this->fetch('login');
        }
    }
    public function logout(){
        $model=model('common/AdminUser');
        $model->logout();
        return $this->success('退出成功','user/login');
    }
}