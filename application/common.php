<?php
// 应用公共文件

/**
 * p格式友好输出
 * @param $data
 */
function p($data){
    echo "<pre>";
    print_r($data);
    echo "</pre>";
}

/**
 * 检测用户是否登录
 * @param $key      加密key
 * @param $db       用户数据库
 * @param $model    用户模型
 * @return int
 */
function is_login($key,$db,$model) {
    //先检测cookie
    $cookie_verify=cookie('user_auth_sign');;
    $cookie=cookie('user_auth');
    if(!empty($cookie_verify) &&!empty($cookie)){//存在
        $map['id']=$cookie;
        $user = $db->field('id,user_password,user_name')->where($map)->find();
        $user_id=$user['id'];
        $user_pw=$user['user_password'];
        $data=$user['user_name'].$user_id.$user_pw;
        $user_verify=think_encrypt($data,$key);
//        $_SESSION['user']=$user['user_name'];//编辑器判断是否登录使用
        if($cookie_verify == $user_verify){
            if(!cookie('user_base')){
                $model->set_userbase_cookie($user['id']);
            }
            return $user['id'];
        }
    }
    $user_auth = session ( 'user_auth' );
//    $_SESSION['user']=$user['user_name'];//编辑器判断是否登录使用
    if (empty ( $user_auth )) {
        return 0;
    } else {
        if(session ( 'user_auth_sign' ) == data_auth_sign ( $user_auth )){
            if(!cookie('user_base')){
                $model->set_userbase_cookie($user_auth['id']);
            }
            return   $user_auth ['id'];
        }else{
            return 0;
        }
    }
}
/**
 * 后台密码加密
 * @param $password
 * @param $key
 * @return string
 */
function password($password,$key){
    return '' === $password ? '' : md5(sha1($password) . $key);
}
/**
 * 数据签名认证
 *
 * @param array $data
 *        	被认证的数据
 * @return string 签名
 */
function data_auth_sign($data) {
    // 数据类型检测
    if (! is_array ( $data )) {
        $data = ( array ) $data;
    }
    ksort ( $data ); // 排序
    $code = http_build_query ( $data ); // url编码并生成query字符串
    $sign = sha1 ( $code ); // 生成签名
    return $sign;
}

/**
 * 系统加密方法
 * @param string $data 要加密的字符串
 * @param string $key  加密密钥
 * @param int $expire  过期时间 (单位:秒)
 * @return string
 */
function think_encrypt($data, $key, $expire = 0) {
    $key  = md5($key);
    $data = base64_encode($data);
    $x    = 0;
    $len  = strlen($data);
    $l    = strlen($key);
    $char =  '';
    for ($i = 0; $i < $len; $i++) {
        if ($x == $l) $x=0;
        $char  .= substr($key, $x, 1);
        $x++;
    }
    $str = sprintf('%010d', $expire ? $expire + time() : 0);
    for ($i = 0; $i < $len; $i++) {
        $str .= chr(ord(substr($data,$i,1)) + (ord(substr($char,$i,1)))%256);
    }
    return str_replace('=', '', base64_encode($str));
}

/**
 * 系统解密方法
 * @param string $data 要解密的字符串 （必须是think_encrypt方法加密的字符串）
 * @param string $key  加密密钥
 * @return string
 */
function think_decrypt($data, $key){
    $key    = md5($key);
    $x      = 0;
    $data   = base64_decode($data);
    $expire = substr($data, 0, 10);
    $data   = substr($data, 10);
    if($expire > 0 && $expire < time()) {
        return '';
    }
    $len  = strlen($data);
    $l    = strlen($key);
    $char = $str = '';
    for ($i = 0; $i < $len; $i++) {
        if ($x == $l) $x = 0;
        $char  .= substr($key, $x, 1);
        $x++;
    }
    for ($i = 0; $i < $len; $i++) {
        if (ord(substr($data, $i, 1)) < ord(substr($char, $i, 1))) {
            $str .= chr((ord(substr($data, $i, 1)) + 256) - ord(substr($char, $i, 1)));
        }else{
            $str .= chr(ord(substr($data, $i, 1)) - ord(substr($char, $i, 1)));
        }
    }
    return base64_decode($str);
}

/**
 * 获取拼接好的导航数据
 * @param string $module 所属模块
 * @return array
 */
function li($module){
    $model=model('common/Li');

    $data=$model->get_li($module);
    $result=[];
    foreach($data as $k=>$v){
        if($v['li_up']==0){
            $v['li_two']=[];
            $result[]=$v;
            unset($data[$k]);
        }
    }
    foreach($result as $key=>$val){
        foreach($data as $k=>$v){
            if($val['id']==$v['li_up']){
                $result[$key]['li_two'][]=$v;
            }
        }
    }
    return $result;

}

/**
 * 获取单个标题名
 * @param $id
 * @return mixed
 */
function get_li_title($id){
    $model=model('common/Li');
    $data=$model->field('li_title')->where(['id'=>$id])->find();
    return $data['li_title'];
}

function get_li_title_array(){
    $model=model('common/Li');
    $data=$model->field('id,li_title')->select();
    $result=[];
    foreach($data as $k=>$v){
        $result[$v['id']]=$v['li_title'];
    }
    $result[0]='其他';
    return $result;
}

function now_time(){
    return $_SERVER['REQUEST_TIME'];
}

/**
 * 对象转数组
 * @param array $data
 * @return array
 */
function toArray($data=[]){
    $result=[];
    foreach ($data as $k=>$v){
        $result[$k]=$v->toArray();
    }
    return $result;
}

/**
 * 检测验证码
 * @param $verify 验证码
 * @param $mobile 手机号码
 * @param $type 类型 1注册 2忘记密码
 * @param $expired_time 失效时间 秒
 */
function verify($verify,$mobile,$type,$expired_time){
    $model=model('common/sms');
    $data=$model->where(['mobile'=>$mobile])->find();
    if(!empty($data)){
        $data=$data->toArray();
        if($type == 1){
            $code=$data['reg_code'];
            $time=$data['reg_time'];
        }else{
            $code=$data['pw_code'];
            $time=$data['pw_time'];
        }
        if($verify != $code){
            json_send([],40025,0);
        }

        if((now_time()-$time)>= $expired_time){
            json_send([],40024,0);
        }

    }else{
        json_send([],40024,0);
    }
}

/**
 * 清除验证码
 * @param $mobile
 * @param $type
 */
function clear_verify($mobile,$type){
    $model=model('common/sms');
    if($type == 1){
        $data['reg_code']=0;
        $data['reg_time']=0;
    }else{
        $data['pw_code']=0;
        $data['pw_time']=0;
    }
    $model->save($data,['mobile'=>$mobile]);
}

/**
 * 二维数组键名重命名
 * @param array $data  二维数组 原始数据
 * @param array $array  一维数组 key为需要替换的名称 val为替换之后的名称
 * @return array
 */
function rename_arrays($data=[],$array=[]){
    foreach ($data as $k=>$v){
        foreach ($v as $kk=>$vv){
            foreach ($array as $key=>$value){
                if($kk == $key){
                    $data[$k][$value]=$vv;
                    unset($data[$k][$kk]);
                }
            }
        }
//        foreach ($array as $key=>$value){
//            if($v[$key]){
//                $data[$k][$value]=$v[$key];
//                unset($data[$k][$key]);
//            }
//        }
    }
    return $data;
}

/**
 * 一维数组键名重命名
 * @param array $data 一维数组 原始数据
 * @param array $array 一维数组 键名为需要替换的名称 键值为替换之后的名称
 * @return array
 */
function rename_array($data=[],$array=[]){
    foreach ($data as $k=>$v){
        foreach ($array as $key=>$value){
            if($k == $key){
                $data[$value]=$v;
                unset($data[$k]);
            }
        }
    }
    return $data;
}

/**
 * @param $url  [请求的URL地址]
 * @param bool $params  [请求的参数]
 * @param int $ispost  [是否采用POST形式]
 * @return bool|mixed
 */
function juhecurl($url,$params=false,$ispost=0){
    $httpInfo = array();
    $ch = curl_init();
    curl_setopt( $ch, CURLOPT_HTTP_VERSION , CURL_HTTP_VERSION_1_1 );
    curl_setopt( $ch, CURLOPT_USERAGENT , 'Mozilla/5.0 (Windows NT 5.1) AppleWebKit/537.22 (KHTML, like Gecko) Chrome/25.0.1364.172 Safari/537.22' );
    curl_setopt( $ch, CURLOPT_CONNECTTIMEOUT , 30 );
    curl_setopt( $ch, CURLOPT_TIMEOUT , 30);
    curl_setopt( $ch, CURLOPT_RETURNTRANSFER , true );
    if( $ispost )
    {
        curl_setopt( $ch , CURLOPT_POST , true );
        curl_setopt( $ch , CURLOPT_POSTFIELDS , $params );
        curl_setopt( $ch , CURLOPT_URL , $url );
    }
    else
    {
        if($params){
            curl_setopt( $ch , CURLOPT_URL , $url.'?'.$params );
        }else{
            curl_setopt( $ch , CURLOPT_URL , $url);
        }
    }
    $response = curl_exec( $ch );
    if ($response === FALSE) {
        //echo "cURL Error: " . curl_error($ch);
        return false;
    }
    $httpCode = curl_getinfo( $ch , CURLINFO_HTTP_CODE );
    $httpInfo = array_merge( $httpInfo , curl_getinfo( $ch ) );
    curl_close( $ch );
    return $response;
}

/**
 * 返回随机颜色
 * @return mixed
 */
function color(){
    $color=[
        'bg-maroon',
        'bg-purple',
        'bg-navy',
        'bg-orange',
        'bg-olive',
        'bg-aqua',
        'bg-green',
        'bg-yellow',
        'bg-red',
        'btn-info',
        'btn-primary',
    ];

    return $color[array_rand($color)];
}

/**
 * 获取项目模块
 * @param $project_id
 * @return array|bool
 */
function project_module($project_id){
    $model=model('common/ProjectModule');
    $data=$model->where(['project_id'=>$project_id])->order('id desc')->select();
    if($data){
        return toArray($data);
    }else{
        return false;
    }
}

/**
 * 获取项目分类
 * @param $project_module_id
 * @return array|bool
 */
function project_cate($project_module_id){
    $model=model('common/ProjectCate');
    $data=$model->where(['project_module_id'=>$project_module_id])->select();
    if($data){
        return toArray($data);
    }else{
        return false;
    }
}

/**
 * 获取API列表
 * @param $project_cate_id
 * @return array|bool
 */
function project_api($project_cate_id){
    $model=model('common/ProjectApi');
    $data=$model->field('id,api_name')->where(['project_cate_id'=>$project_cate_id])->select();
    if($data){
        return toArray($data);
    }else{
        return [];
    }
}

/**
 * 获取api详情
 * @param $id
 * @return array|bool
 */
function api_wiki($id){
    $model=model('common/ProjectApi');
    $join=[
        ['project_url as pu','pu.id = a.project_url_id','left'],
    ];
    $data=$model
        ->field('a.*,pu.url_domain')
        ->alias('a')
        ->join($join)
        ->where(['a.id'=>$id])
        ->find();
    if($data){
        return $data->toArray();
    }else{
        return [];
    }
}

/**
 * 项目域名表
 * @param $project_id
 * @return array
 */
function project_url($project_id){
    $model=model('common/ProjectUrl');
    $data=$model->where(['project_id'=>$project_id])->select();
    if($data){
        return toArray($data);
    }else{
        return [];
    }
}

/**
 * 请求类型
 * @return array
 */
function request_data(){
    $model=model('common/RequestType');
    $data=$model->select();
    if($data){
        return toArray($data);
    }else{
        return [];
    }
}

function request_data_id($request_id){
    $data=request_data();
    $return='未知';
    foreach ($data as $k=>$v){
        if($v['id']==$request_id){
            $return=$v['type_name'];
            break;
        }
    }
    return $return;
}

/**
 * 数据类型
 * @return array
 */
function data_type(){
    $model=model('common/DateType');
    $data=$model->select();
    if($data){
        return toArray($data);
    }else{
        return [];
    }
}

function data_type_id($type_id){
    $data=data_type();
    $return='未知';
    foreach ($data as $k=>$v){
        if($v['id']==$type_id){
            $return=$v['type_name'];
            break;
        }
    }
    return $return;
}

/**
 * 返回指定格式json
 * @param array $data 返回数据
 * @param int $code 返回码
 * @param int $status 状态 默认1=成功 0=失败
 * @param string $token
 * @param mixed $backup 备注
 * @return string
 */
function json_send($data=[],$code=1,$status=1,$token='',$backup=''){
    $arr=[
        'header' => [
            'status'    =>  $status,//状态
            'info'      =>  code_val($code,$backup),//操作信息
            'code'      =>  $code,
            'token'     =>  $token,
            'server_time'   =>  now_time(),//服务器时间
        ],
        'body'  => [
            'data'  => $data,//具体数据
            'backup'=> $backup,//备注
        ],
    ];
    header("Content-type: text/json; charset=utf-8");
    header("Content-type: application/json; charset=utf-8");
    echo json_encode($arr,JSON_UNESCAPED_UNICODE);
    exit;
}

/**
 * @param $k
 * @param string $error
 * @return mixed|string
 */
function code_val($k,$error=''){
    if(is_array($k)){//登陆自动验证失败时返回的是数组
        return $k[0];//返回失败错误信息
    }else{
        $return = [
            '-1'        =>      '系统繁忙，请重试！',
            '1'         =>      '请求成功！',
            '40000'     =>      '请求失败！',
            '40001'     =>      '系统繁忙，请重试！',
            '40002'     =>      '登陆失败，用户不存在！',
            '40003'     =>      '登陆失败，密码错误！',
            '40004'     =>      '登陆失败，未知状态！',
            '40005'     =>      '登陆失败，用户被禁用！',
            '40006'     =>      '登陆失败，账号已被删除！',
            '40007'     =>      '登陆失败，账号已停用！',
            '40008'     =>      '验证失败，请重新登陆！',
            '40009'     =>      '手机号码必须！',
            '40010'     =>      '密码必须！',
            '40011'     =>      '手机已注册！',
            '40012'     =>      '密码过短！',
            '40013'     =>      '非法空间！',
            '40014'     =>      '短信发送失败!'.$error,
            '40015'     =>      '短信已经发送,请注意查看手机!',
            '40016'     =>      '手机号码必须!',
            '40017'     =>      '密码必须',
            '40018'     =>      '确认密码必须!',
            '40019'     =>      '确认密码不一致',
            '40020'     =>      '验证码必须',
            '40021'     =>      '手机格式不正确',
            '40022'     =>      '密码请大于6位',
            '40023'     =>      '注册失败',
            '40024'     =>      '验证码失效,请重新获取',
            '40025'     =>      '验证码错误',
            '40026'     =>      '手机尚未注册',
            '40027'     =>      '无此用户',
            '40028'     =>      '无此项目',
            '40029'     =>      '一天只能点赞一次哦',
            '40030'     =>      '点赞失败',
        ];
        if(empty($return[$k])){
            return '未知错误！';
        }else{
            return $return[$k];
        }
    }

}
