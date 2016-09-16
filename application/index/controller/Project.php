<?php
/**
 * Created by PhpStorm.
 * User: xun
 * Date: 2016/9/8
 * Time: 13:44
 */

namespace app\index\controller;


use think\Controller;

class Project extends Controller
{
    public function show(){
        $project_id=input('param.project_id');
        if(!$project_id){
            $this->redirect('index/index');
        }
        //模块数据
        $project_module_data=project_module($project_id);
        //模块id
        $project_module_id=input('param.project_module_id');
        if(!$project_module_id){
            if($project_module_data){
                $project_module_id=$project_module_data[0]['id'];
            }else{
                $project_module_data=[];
            }
        }
        //分类数据
        $project_cate_data=[];
        if($project_module_id){
            $project_cate_data=project_cate($project_module_id);
        }
        $left_data=[];//左侧导航菜单
        if(!empty($project_cate_data)){
            foreach ($project_cate_data as $k=>$v){
                $left_data[$v['cate_name']]=[
                    'cate_id' => $v['id'],
                    'project_api' => project_api($v['id']),
                ];
            }
        }
        //接口文档id
        $project_api_id=input('param.project_api_id');
        $project_api_data=[];//接口文档数据
        if(empty($project_api_id)){
            if(!empty($left_data)){
                foreach ($left_data as $k=>$v){
                    if(!empty($v['project_api'])){
                        $project_api_data=api_wiki($v['project_api'][0]['id']);
                        $project_api_id=$v['project_api'][0]['id'];
                        break;
                    }
                }
            }
        }else{
            $project_api_data=api_wiki($project_api_id);
        }
        if(empty($project_api_data)){
            $project_request_data='';
        }else{
            $project_request_data=$project_api_data['api_parameter'];
        }
        if(empty($project_api_data)){
            $project_back_data='';
        }else{
            $project_back_data=$project_api_data['api_re'];
        }

        $this->assign('project_api_data',$project_api_data);//接口文档数据
        $this->assign('left_data',$left_data);//左侧导航菜单
        $this->assign('project_module_data',$project_module_data);//模块数据
        $this->assign('project_id',$project_id);//项目id
        $this->assign('project_module_id',$project_module_id);//模块id
        $this->assign('project_api_id',$project_api_id);//文档id
        $this->assign('project_url',project_url($project_id));//url数据
        $this->assign('request_data',request_data());//请求类型
        $this->assign('project_request_data',json_decode($project_request_data,true));//请求数据
        $this->assign('project_back_data',json_decode($project_back_data,true));//返回数据
        $this->assign('data_type',data_type());//数据类型
        $this->assign('js_data_type',json_encode(data_type()));//数据类型json

        return $this->fetch('show');
    }
}