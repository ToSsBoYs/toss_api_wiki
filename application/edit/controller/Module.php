<?php
/**
 * Created by PhpStorm.
 * User: 珣
 * Date: 2016/9/8
 * Time: 23:49
 */

namespace app\edit\controller;

class Module extends Base
{
    public function add(){
        $project_api_id=input('param.project_api_id');
        $project_id=input('param.project_id');
        $project_module_id=input('param.project_module_id');
        $module_name=input('param.model_name');
        if(empty($project_id) || empty($module_name)){
            $this->error('参数错误！');
        }
        $model=model('common/ProjectModule');
        if($model->data(['model_name'=>$module_name,'project_id'=>$project_id])->save()){
            $this->redirect('project/show',['project_id'=>$project_id,'project_module_id'=>$project_module_id,'project_api_id'=>$project_api_id]);
        }else{
            $this->error('新增失败!');
        }

    }

    public function del(){
        $project_id=input('param.project_id');
        $project_module_id=input('param.project_module_id');//模块id
        $model_module=db('ProjectModule');
        $model_cate=db('ProjectCate');
        $model_api=db('ProjectApi');
        if(empty($project_id) || empty($project_module_id)){
            $this->error('非法参数');
        }

        if($model_module->where(['id'=>$project_module_id])->delete()){
            $model_api->where(['project_module_id'=>$project_module_id])->delete();
            $model_cate->where(['project_module_id'=>$project_module_id])->delete();
            $this->redirect('Project/show',['project_id'=>$project_id]);
        }else{
            $this->error('删除失败！');
        }
    }

    public function edit(){
        $project_api_id=input('param.project_api_id');//当前文档
        $project_id=input('param.project_id');//当前项目
        $project_module_id=input('param.module_id');//修改的模块id
        $project_now_model_id=input('param.now_module_id');//当前模块
        $model_name=input('param.model_name');

        if(empty($project_id) || empty($project_module_id) ||empty($model_name) || empty($project_module_id)){
            $this->error('参数错误！');
        }
        $model=model('common/ProjectModule');
        if($model->save(['model_name'=>$model_name],['id'=>$project_module_id])){
            $this->redirect('project/show',['project_id'=>$project_id,'project_module_id'=>$project_now_model_id,'project_api_id'=>$project_api_id]);
        }else{
            $this->error('修改失败!');
        }
    }
}