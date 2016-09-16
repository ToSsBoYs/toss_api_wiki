<?php
/**
 * Created by PhpStorm.
 * User: 珣
 * Date: 2016/7/28
 * Time: 21:58
 */

namespace app\admin\controller;
use think\Controller;
use think\db\Query;
class GetAuth extends Controller{
    //方法中文名称
    public $title_arr=[
        'index'      =>  '主页',
        'del'        =>  '删除',
        'edit'       =>  '编辑更新',
        'add'        =>  '新增',
        'ajax_icon'  =>  '获取图标集',
        'rule'       =>  '权限更新',
        'rule_show'  =>  '权限详情页'
    ];
    //排除方法
    public $inherents_functions=[
        '_initialize',
        '__construct',
        'getActionName',
        'isAjax',
        'display',
        'show',
        'fetch',
        'buildHtml',
        'assign',
        '__set',
        'get',
        '__get',
        '__isset',
        '__call',
        'error',
        'success',
        'ajaxReturn',
        'redirect',
        '__destruct',
        '_empty',
        'theme',
        'beforeAction',
        'engine',
        'validateFailException',
        'validate',
        'result',
        'getResponseType',
    ];
    //排除控制器
    public $inherents_controller=[
        'Base',
        'GetAuth',
        'User',
    ];
    public function index(){
        $modules = array('admin');  //模块名称
        $i = 0;
        foreach ($modules as $module) {
            $all_controller = $this->getController($module);
            foreach ($all_controller as $controller) {
                $controller_name = $module.'/'.$controller;
                $all_action = $this->getAction($controller_name);
                foreach ($all_action as $action) {
                    $data[$i]['module'] = $module;
                    $data[$i]['controller'] = strtolower(preg_replace('/((?<=[a-z])(?=[A-Z]))/', '_', $controller));
                    $data[$i]['action'] = $action;
                    $i++;
                }
            }
        }
        $i=0;



        // 启动事务
        $auth_rule_model=db('AuthRule');
        $auth_rule_model->startTrans();



        $title_arr=$this->title_arr;

        foreach($data as $k=>$v){
            $name_where=[
                'module'        =>  $v['module'],
                'controller'    =>  $v['controller'],
                'function'      =>  $v['action'],
            ];
            $li_where=[
                'li_module'        =>  $v['module'],
                'li_sign'    =>  $v['controller'],
            ];
            $name=$v['module'].'/'.$v['controller'].'/'.$v['action'];
            $title_name=$title_arr[$v['action']];
            //验证规则是否存在过并添加
            if(!db('AuthRule')->field('id')->where($name_where)->find()){
                //判断当前添加的规则所属一级标题和二级标题id
                $li_data=model('Li')->field('id,li_title,li_up')->where($li_where)->find();
                if(empty($title_name)){
                    $title=$li_data['li_title'];
                }else{
                    $title=$title_name;
                }
                $li_id=$li_data['id'];
                if(empty($li_id)){
                    $li_id=0;
                }

                $data=[
                    'module'        =>  $v['module'],
                    'controller'    =>  $v['controller'],
                    'function'      =>  $v['action'],
                    'li_id'         =>  $li_id,
                    'title'         =>  $title,
                    'create_time'   =>  now_time(),
                    'update_time'   =>  now_time(),
                ];
                //添加规则
                if(!$auth_rule_model->insert($data)){
                    $auth_rule_model->rollback();
                    exit("添加{$name}失败！");
                }

                $i++;
            }
        }
        // 提交事务
        $auth_rule_model->commit();
        exit("成功添加{$i}条");
//        $this->success("成功添加{$i}条");
    }
    //获取所有控制器名称
    protected function getController($module){
        if(empty($module)) return null;
        $module_path = APP_PATH . '/' . $module . '/controller/';  //控制器路径
        if(!is_dir($module_path)) return null;
        $module_path .= '/*.php';
        $ary_files = glob($module_path);

        //排除控制器
        $inherents_functions=$this->inherents_controller;
        foreach ($ary_files as $file) {
            if (is_dir($file)) {
                continue;
            }else {
                $con=basename($file,'.php');
                if(!in_array($con, $inherents_functions)){
                    $files[] = $con;
                }

            }
        }
        return $files;
    }
    //获取所有方法名称
    protected function getAction($controller){
        if(empty($controller)) return null;
        $con = controller($controller);
        $functions = get_class_methods($con);
        //排除部分方法
        $inherents_functions = $this->inherents_functions;
        foreach ($functions as $func){
            if(!in_array($func, $inherents_functions)){
                $customer_functions[] = $func;
            }
        }
        return $customer_functions;
    }
}