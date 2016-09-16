<?php
/**
 * Created by PhpStorm.
 * User: 珣
 * Date: 2016/9/6
 * Time: 23:09
 */

namespace app\edit\controller;


class Project extends Base
{
    public function show(){
        if(request()->post()){
            $data=input('param.');
            $id=$data['id'];
            if(empty($id)){
                $this->error('参数错误!');
            }

            if(!empty($data['request_name'])){
                $request_name=$data['request_name'];
                $api_parameter_array=[];
                foreach ($request_name as $k=>$v){
                    if(!empty($v)){
                        $api_parameter_array[]=[
                            'request_name' => $v,
                            'request_must' => $data['request_must'][$k],
                            'request_type'  => $data['request_type'][$k],
                            'request_remark' => $data['request_remark'][$k],
                        ];
                    }
                }
                $data['api_parameter']=json_encode($api_parameter_array);
            }
            if(!empty($data['back_name'])){
                $back_name=$data['back_name'];
                $api_re_array=[];
                foreach ($back_name as $k=>$v){
                    if(!empty($v)){
                        $api_re_array[]=[
                            'back_name' => $v,
                            'back_must' => $data['back_must'][$k],
                            'back_type'  => $data['back_type'][$k],
                            'back_remark' => $data['back_remark'][$k],
                        ];
                    }
                }
                $data['api_re']=json_encode($api_re_array);
            }
            $model=model('common/ProjectApi');
            if(!$model->allowField(true)->save($data,['id'=>$id])){
                $this->error('修改失败!');
            }

        }

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
            $project_request_data=[];
        }else{
            $project_request_data=json_decode($project_api_data['api_parameter'],true);
        }
        if(empty($project_api_data)){
            $project_back_data=[];
        }else{
            $project_back_data=json_decode($project_api_data['api_re'],true);
        }

//        var_dump($project_api_data);exit;
        $this->assign('project_api_data',$project_api_data);//接口文档数据
        $this->assign('left_data',$left_data);//左侧导航菜单
        $this->assign('project_module_data',$project_module_data);//模块数据
        $this->assign('project_id',$project_id);//项目id
        $this->assign('project_module_id',$project_module_id);//模块id
        $this->assign('project_api_id',$project_api_id);//文档id
        $this->assign('project_url',project_url($project_id));//url数据
        $this->assign('request_data',request_data());//请求类型
        $this->assign('project_request_data',$project_request_data);//请求数据
        $this->assign('project_back_data',$project_back_data);//返回数据
        $this->assign('data_type',data_type());//数据类型
        $this->assign('js_data_type',json_encode(data_type()));//数据类型json

        return $this->fetch('show');
    }

    public function add(){
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
        if(request()->post()){
            $data=input('param.');

            if(!empty($data['request_name'])){
                $request_name=$data['request_name'];
                $api_parameter_array=[];
                foreach ($request_name as $k=>$v){
                    if(!empty($v)){
                        $api_parameter_array[]=[
                            'request_name' => $v,
                            'request_must' => $data['request_must'][$k],
                            'request_type'  => $data['request_type'][$k],
                            'request_remark' => $data['request_remark'][$k],
                        ];
                    }
                }
                $data['api_parameter']=json_encode($api_parameter_array);
            }
            if(!empty($data['back_name'])){
                $back_name=$data['back_name'];
                $api_re_array=[];
                foreach ($back_name as $k=>$v){
                    if(!empty($v)){
                        $api_re_array[]=[
                            'back_name' => $v,
                            'back_must' => $data['back_must'][$k],
                            'back_type'  => $data['back_type'][$k],
                            'back_remark' => $data['back_remark'][$k],
                        ];
                    }
                }
                $data['api_re']=json_encode($api_re_array);
            }
//            dump($data);exit;
            $model=model('common/ProjectApi');
            if(!$model->data($data)->allowField(true)->save()){
                $this->error('新增失败!');
            }else{
                $this->redirect('project/show',['project_id'=>$project_id,'project_module_id'=>$project_module_id,'project_api_id'=>$model->id]);
            }

        }


        $cate_id=input('param.cate_id');
        if(!$cate_id){
            $this->redirect('index/index');
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

        $this->assign('left_data',$left_data);//左侧导航菜单
        $this->assign('project_module_data',$project_module_data);//模块数据
        $this->assign('project_id',$project_id);//项目id
        $this->assign('project_module_id',$project_module_id);//模块id
        $this->assign('project_api_id','');//文档id
        $this->assign('project_url',project_url($project_id));//url数据
        $this->assign('request_data',request_data());//请求类型
        $this->assign('project_cate_id',$cate_id);//分类id
        $this->assign('data_type',data_type());//数据类型
        $this->assign('js_data_type',json_encode(data_type()));//数据类型json

        return $this->fetch('add');
    }

    public function del(){
        $project_api_id=input('param.project_api_id');
        $project_id=input('param.project_id');
        $project_module_id=input('param.project_module_id');
        if(empty($project_api_id) || empty($project_id) || empty($project_module_id) ){
            $this->error('参数错误！');
        }
        $model=model('common/ProjectApi');
        if(!$model->where(['id'=>$project_api_id])->delete()){
            $this->error('删除失败!');
        }else{
            $this->redirect('project/show',['project_id'=>$project_id,'project_module_id'=>$project_module_id]);
        }
    }

    public function project_add(){
        $project_name=input('param.project_name');
        $project_about=input('param.project_about');
        $project_icon=input('param.project_icon');
        if(empty($project_name) || empty($project_about)){
            $this->error('参数错误');
        }
        $data=[
            'project_name'=> $project_name,
            'project_about'=>$project_about,
            'project_icon'=>$project_icon,
        ];
        $model=model('common/Project');
        if($model->data($data)->allowField(true)->save()){
            $this->redirect('index/index');
        }else{
            $this->error('新增失败！');
        }
    }

    public function project_edit(){
        $project_name=input('param.project_name');
        $project_about=input('param.project_about');
        $project_icon=input('param.project_icon');
        $project_id=input('param.project_id');
        if(empty($project_name) || empty($project_about) || empty($project_id)){
            $this->error('参数错误');
        }
        $data=[
            'project_name'=> $project_name,
            'project_about'=>$project_about,
            'project_icon'=>$project_icon,
        ];
        $model=model('common/Project');
        if($model->save($data,['id'=>$project_id])){
            $this->redirect('index/index');
        }else{
            $this->error('修改失败！');
        }
    }

    public function project_del(){
        $project_id=input('param.id');
        $model_project=db('Project');
        $model_module=db('ProjectModule');
        $model_cate=db('ProjectCate');
        $model_api=db('ProjectApi');
        if(empty($project_id)){
            $this->error('非法参数');
        }

        if($model_project->where(['id'=>$project_id])->delete()){
            $model_module->where(['project_id'=>$project_id])->delete();
            $model_api->where(['project_id'=>$project_id])->delete();
            $model_cate->where(['project_id'=>$project_id])->delete();
            $this->redirect('index/index');
        }else{
            $this->error('删除失败！');
        }
    }
}