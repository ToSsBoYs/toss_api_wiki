<?php

/**
 * @param string $rule     需要验证的规则列表,支持逗号分隔的权限规则或索引数组
 * @param int $uid      认证用户的id
 * @param int $type 执行check的模式
 * @param string $mode  如果为 'or' 表示满足任一条规则即通过验证;如果为 'and'则表示需满足所有规则才能通过验证
 * @param string $relation  通过验证返回true;失败返回false
 * @return bool
 */
/**
 * @param $module   string    所属模块
 * @param $controller string  所属控制器
 * @param $function   string  所属方法
 * @param $uid     int     用户id
 * @param int $is_function 是否精确到方法 1精确 0只精确到控制器
 * @param int $type     认证方式，1为实时认证；2为登录认证。
 * @param string $mode  执行check的模式
 * @param string $relation  如果为 'or' 表示满足任一条规则即通过验证;如果为 'and'则表示需满足所有规则才能通过验证
 * @return bool
 */
function authCheck($module,$controller,$function,$uid,$is_function=2,$type=1, $mode='url', $relation='or'){
    //超级管理员跳过验证
    $auth=new \admin\auth();
    //获取当前uid所在的角色组id
    $groups=$auth->getGroups($uid);

    //这里偷懒了,因为我设置的是一个用户对应一个角色组,所以直接取值.如果是对应多个角色组的话,需另外处理
    if(in_array($groups[0]['id'], config('admin_is_trator'))){
        return true;
    }else{
        return $auth->check($module,$controller,$function,$uid,$is_function,$type,$mode,$relation)?true:false;
    }
}


/**
 * 返回符合格式的规则
 */
function rules(){
    $model=db('AuthRule');
    $data=$model->field('id,module,controller,function,title,type,li_id')
//        ->where(['status'=>1])
        ->select();
    $li_array=model('Li')->get_li('admin');
    foreach($data as $k=>$v){
        $data[$k]['name']=$v['module'].'/'.$v['controller'].'/'.$v['function'];
        foreach($li_array as $key=>$val){
            if($v['li_id'] == $val['id']){
                if($val['li_up'] == 0){
                    $data[$k]['one_id']=$val['id'];
                    $data[$k]['two_id']='0';
                }else{
                    $data[$k]['one_id']=$val['li_up'];
                    $data[$k]['two_id']=$val['id'];
                }
            }
        }
        if(empty($data[$k]['one_id'])){
            $data[$k]['one_id']='0';
        }
        if(empty($data[$k]['two_id'])){
            $data[$k]['two_id']='0';
        }
    }
    $return=[];
    foreach($data as $k=>$v){
        if($v['two_id']==0&&$v['one_id']==0){//其他分类
            if(empty($return[0])){
                $return[0]=[
                    'one_title' => '其他',
                    'one_id'    => '0',
                    'rules'     => [
                        [
                            'rule_id'   => $v['id'],
                            'rule_name' => $v['name'],
                            'rule_title'=> $v['title']
                        ],
                    ],
                    'status'    => 0,
                ];
            }else{
                $return[0]['rules'][]=[
                    'rule_id'   => $v['id'],
                    'rule_name' => $v['name'],
                    'rule_title'=> $v['title'],
                ];
            }

        }else if($v['two_id']==0&&$v['one_id']!=0){//不存在二级标题

            if(empty($return[$v['one_id']])){
                $return[$v['one_id']]=[
                    'one_title' => title($v['one_id']),
                    'one_id'    => $v['one_id'],
                    'rules'     => [
                        [
                            'rule_id'   => $v['id'],
                            'rule_name' => $v['name'],
                            'rule_title'=> $v['title'],
                        ]
                    ],
                    'status'    =>  1,
                ];
            }else{
                $return[$v['one_id']]['rules'][]=[
                    'rule_id'   => $v['id'],
                    'rule_name' => $v['name'],
                    'rule_title'=> $v['title'],
                ];
            }


        }else{//存在二级标题
            if(empty($return[$v['one_id']])){
                //一级标题没有设置过 设置一级标题 顺便设置二级标题规则
                $return[$v['one_id']]=[
                    'one_title' => title($v['one_id']),
                    'one_id'    => $v['one_id'],
                    'li_two'    => [
                        $v['two_id']=>[
                            'two_title' =>  title($v['one_id'],$v['two_id']),
                            'two_id'    =>  $v['two_id'],
                            'rules'    =>  [
                                [
                                    'rule_id'   => $v['id'],
                                    'rule_name' => $v['name'],
                                    'rule_title'=> $v['title']
                                ],
                            ],
                        ],
                    ],
                    'status'     => 2,
                ];

            }else{
                //一级标题已经设置过
                if(empty($return[$v['one_id']]['li_two'][$v['two_id']])){
                    //二级标题规则不存在 设置二级标题规则
                    $return[$v['one_id']]['li_two'][$v['two_id']]=
                        [
                            'two_title' =>  title($v['one_id'],$v['two_id']),
                            'two_id'    =>  $v['two_id'],
                            'rules'    =>  [
                                [
                                    'rule_id'   => $v['id'],
                                    'rule_name' => $v['name'],
                                    'rule_title'=> $v['title']
                                ],
                            ],
                        ];

                }else{
                    //二级标题规则已存在  直接添加新规则
                    $return[$v['one_id']]['li_two'][$v['two_id']]['rules'][]=[
                        'rule_id'   => $v['id'],
                        'rule_name' => $v['name'],
                        'rule_title'=> $v['title'],
                    ];
                }

            }
        }

    }
    sort($return);
    if(!empty($return[0])){
        $arr=$return[0];
        unset($return[0]);
        $return[0]=$arr;
    }
//    p($li_array);
//    p($data);
//    p($return);exit;
    return $return;
}

/**
 * 返回标题名称 如果没有二级标题id就只查询以及标题id
 * @param mixed $oneid  一级标题id
 * @param string $twoid 二级标题id
 * @return mixed    标题名称
 */
function title($oneid,$twoid=''){
    $data=li_title();
//    p($data);exit;
    if(empty($twoid)){
        if(empty($data[$oneid]['title'])){
            $return='未知';
        }else{
            $return=$data[$oneid]['title'];
        }
        return $return;
    }else{
        if(empty($data[$oneid]['li_two'][$twoid]['li_title'])){
            $return='未知';
        }else{
            $return=$data[$oneid]['li_two'][$twoid]['li_title'];
        }
        return $return;
    }
}
/**
 * 菜单格式重排列
 * @return array
 */
function li_title(){
    $data=li('admin');
    $return=[];
    foreach($data as $k=>$v){
        $return[$v['id']]['id']=$v['id'];
        $return[$v['id']]['title']=$v['li_title'];
        $return[$v['id']]['sign']=$v['li_sign'];
        foreach($v['li_two'] as $key=>$val){
            $return[$v['id']]['li_two'][$val['id']]=$val;
        }
    }
    return $return;
}