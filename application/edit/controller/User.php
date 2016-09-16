<?php
/**
 * Created by PhpStorm.
 * User: 珣
 * Date: 2016/7/30
 * Time: 16:13
 */

namespace app\edit\controller;
use think\Controller;

class User extends Controller{
    public function login(){
        if(request()->isPost()){
            $user_mobile=input('post.user_mobile');
            $user_password=input('post.user_password');
            $verify=input('post.verify');
            $remember=input('post.remember');
            $data=[
                'user_mobile' =>   $user_mobile,
                'user_password' => $user_password,
                'verify'        =>  $verify,
            ];
            $result=$this->validate($data,'AdminUser.login');
//            if($result!==true){
//                $this->error($result);
//            }else{
                $model=model('common/AdminUser');
                $uid=$model->login($user_mobile,$user_password,$type = 3,$remember);
                if (0 < $uid) { // UC登录成功
                    /* 登录用户 */
                    return $this->success('登陆成功!','Index/index');
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
        return $this->success('退出成功','index/index/index');
    }

    public function register(){
        if(request()->isAjax()){
            $data=input('param.');
            $result=$this->validate($data,'SayUser.add');
            if(true !== $result){
                // 验证失败 输出错误信息
                json_send([],$result,0);
            }

            verify(input('param.verify'),input('param.user_mobile'),1,60*5);

            $user_model=model('common/SayUser');
            $add_data=[
                'user_name' => input('param.user_mobile'),
                'user_mobile' =>input('param.user_mobile'),
                'user_password' =>input('param.user_password'),
            ];

            if($user_model->data($add_data)->save()){
                clear_verify(input('param.user_mobile'),1);
                json_send();
            }else{
                json_send([],40023,0);
            }
        }else{
            return $this->fetch('register');
        }

    }

    public function forget_password(){
        if(request()->isAjax()){
            $data=input('param.');
            $result=$this->validate($data,'SayUser.add');
            if(true !== $result){
                // 验证失败 输出错误信息
                json_send([],$result,0);
            }

            verify(input('param.verify'),input('param.user_mobile'),2,60*5);

            $user_model=model('common/SayUser');
            $update_data=[
                'user_password' =>input('param.user_password'),
            ];

            if($user_model->save($update_data,['user_mobile'=>input('param.user_mobile')])){
                clear_verify(input('param.user_mobile'),2);
                json_send();
            }else{
                json_send([],40023,0);
            }
        }else{
            return $this->fetch('forget_password');
        }
    }
}