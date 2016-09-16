<?php
/**
 * Created by PhpStorm.
 * User: xun
 * Date: 2016/9/8
 * Time: 15:24
 */

namespace app\edit\controller;


class Cate extends Base
{
    public function del(){
        $project_cate_id=input('param.cate_id');
        $project_id=input('param.project_id');
        $project_module_id=input('param.project_module_id');
        if(empty($project_cate_id) || empty($project_id) || empty($project_module_id) ){
            $this->error('参数错误！');
        }
        $model=model('common/ProjectApi');
        $model_1=model('common/ProjectCate');
        $data_1=$model_1->where(['id'=>$project_cate_id])->delete();
        $data=$model->where(['project_cate_id'=>$project_cate_id])->delete();
        if($data_1){
            $this->redirect('project/show',['project_id'=>$project_id,'project_module_id'=>$project_module_id]);
        }else{
            $this->error('删除失败!');
        }
    }

    public function add(){
        $project_api_id=input('param.project_api_id');
        $project_id=input('param.project_id');
        $project_module_id=input('param.project_module_id');
        $cate_name=input('param.cate_name');
        if(empty($project_id) || empty($project_module_id) ||empty($cate_name)){
            $this->error('参数错误！');
        }
        $model=model('common/ProjectCate');
        if($model->data(['cate_name'=>$cate_name,'project_module_id'=>$project_module_id,'project_id'=>$project_id])->save()){
            $this->redirect('project/show',['project_id'=>$project_id,'project_module_id'=>$project_module_id,'project_api_id'=>$project_api_id]);
        }else{
            $this->error('新增失败!');
        }

    }

    public function edit(){
        $project_api_id=input('param.project_api_id');
        $project_id=input('param.project_id');
        $project_module_id=input('param.project_module_id');
        $cate_name=input('param.cate_name');
        $cate_id=input('param.cate_id');
        if(empty($project_id) || empty($project_module_id) ||empty($cate_name) || empty($cate_id)){
            $this->error('参数错误！');
        }
        $model=model('common/ProjectCate');
        if($model->save(['cate_name'=>$cate_name],['id'=>$cate_id])){
            $this->redirect('project/show',['project_id'=>$project_id,'project_module_id'=>$project_module_id,'project_api_id'=>$project_api_id]);
        }else{
            $this->error('修改失败!');
        }

    }
}