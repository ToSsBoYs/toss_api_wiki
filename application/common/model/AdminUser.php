<?php
/**
 * Created by PhpStorm.
 * User: xun
 * Date: 2016/7/28
 * Time: 16:48
 */

namespace app\common\model;
use think\Model;
class AdminUser extends Model{
    protected $auto = [];
    protected $insert = ['user_password','user_header_img'];
    protected $update = [];
    protected function setUserPasswordAttr($value){
        return password($value,$this->key());
    }
    protected function setUserHeaderImgAttr(){
        return '/img/head.png';
    }
    protected function key(){
        return config('myconfig.admin_key');
    }
    protected function session_key(){
        return config('myconfig.admin_session_key');
    }
    protected function cookie_key(){
        return config('myconfig.admin_cookie_key');
    }
    /**
     * 用户登录认证
     * @param  string  $username 用户名
     * @param  string  $password 用户密码
     * @param  integer $type     用户名类型 （1-用户名，2-邮箱，3-手机，4-UID）
     * @return integer           登录成功-用户ID，登录失败-错误编号
     * @param  string  $auto 是否自动登录 on为自动登录
     */
    public function login($username, $password, $type = 1 ,$auto){
        $map = array();
        switch ($type) {
            case 1:
                $map['user_name'] = $username;
                break;
            case 2:
                $map['user_email'] = $username;
                break;
            case 3:
                $map['user_mobile'] = $username;
                break;
            case 4:
                $map['id'] = $username;
                break;
            default:
                return 0; //参数错误
        }

        /* 获取用户数据 */
        $user = $this->where($map)->find();
        if($user && $user['user_status']==1){
            /* 验证用户密码 */
            if(password($password,$this->key()) === $user['user_password']){
                $this->updateLogin($user['id']); //更新用户登录信息
                if($auto=='on'){
                    $this->set_userbase_cookie($user['id']);
                    $this->user_cookie($user);
                }else{
                    $this->set_userbase_cookie($user['id']);
                    $this->user_session($user);
                }
                return $user['id']; //登录成功，返回用户ID
            } else {
                return -2; //密码错误
            }
        } else {
            return -1; //用户不存在或被禁用
        }
    }

    protected function user_session($user=[]){
        $data=$user['user_name'].$user['id'].$user['user_password'];
        $verify=think_encrypt($data,$this->session_key());

        $auth = array (
            'verify' => $verify,
            'user_name' => $user['user_name'],
            'id'      => $user['id'],
        );
        session ( 'user_auth', $auth );
        session ( 'user_auth_sign', data_auth_sign ( $auth ));

    }

    protected function user_cookie($user=[]){
        $data=$user['user_name'].$user['id'].$user['user_password'];
        $verify=think_encrypt($data,$this->cookie_key());
        cookie('user_auth',$user['id'],2952000);
        cookie('user_auth_sign',$verify,2592000); // 指定cookie保存时间30天
    }

    /**
     * 更新用户登录信息
     * @param  integer $uid 用户ID
     */
    protected function updateLogin($uid){
        $data = array(
            'last_login_ip'   => request()->ip(),//0返回ip地址 1 返回纯数字
            'user_login'           => ['exp','user_login+1'],
        );
        $this->save($data,['id'=>$uid]);
    }

    /**
     * 注销当前用户
     *
     * @return void
     */
    public function logout() {
        session ('user_auth', null );
        session ('user_auth_sign', null);
        cookie('user_base',null);
        cookie('user_auth',null);
        cookie('user_auth_sign',null);
    }
    public function set_userbase_cookie($user_id=''){
        $user=$this->where(['id'=>$user_id])->find();
        $user_data=[
            'user_name' =>  $user['user_name'],
            'user_header_img'   => $user['user_header_img'],
            'create_time'   => $user['create_time'],
            'update_time'   =>  $user['update_time'],
        ];
        cookie('user_base',$user_data);
    }
}