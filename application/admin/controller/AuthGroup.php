<?php
/**
 * Created by PhpStorm.
 * User: xun
 * Date: 2016/7/28
 * Time: 11:04
 */

namespace app\admin\controller;


class AuthGroup extends Base{
    public function index(){
        $model=model('common/AuthGroup');
        
        $data=$model->order('id desc')->paginate(15);
        $this->assign('data',$data);

        return $this->fetch('index');
    }

    public function del(){
        $Model=model('common/AuthGroup');
        $id = input('param.id/a');
        if(!$id){
            $this->error('没有选中数据！');
            exit;
        }
        //判断id是数组还是一个数值
        if(is_array($id)){
            $where['id'] = ['in',$id];
        }else{
            $where['id'] = $id;
        }
        $list=$Model->where($where)->delete();
        if($list!==false){
            return $this->success("成功删除{$list}条！");
        }else{
            $this->error('删除失败！');
        }
    }
    public function edit(){
        $model=model('common/AuthGroup');
        $id=input('param.id');
        if(empty($id)){
            $this->error('非法参数！');
        }
        if(request()->isPost()){
            $data=input('param.');
            $result=$this->validate($data,'AuthGroup.edit');
            if(true !== $result){
                // 验证失败 输出错误信息
                $this->error($result);
            }elseif($model->allowField(true)->save($data,['id'=>$id])){
                return $this->success('更新成功', 'AuthGroup/index');
            }else{
                $this->error('更新失败！');
            }
        }else{
            $data=$model->where(['id'=>$id])->find()->toArray();

            $this->assign('data',$data);
            return $this->fetch('edit');
        }
    }
    public function add(){
        if(request()->isPost()){
            $model=model('common/AuthGroup');

            $data=input('param.');
            $result=$this->validate($data,'AuthGroup.add');
            if(true !== $result){
                // 验证失败 输出错误信息
                $this->error($result);
            }elseif($model->data($data)->save()){
                return $this->success('新增成功', 'AuthGroup/index');
            }else{
                $this->error('新增失败！');
            }

        }else{
            return $this->fetch('add');
        }
    }

    public function rule_show(){
        $id=input('param.id');
        if(request()->isPost()){
            $rule_id=input('param.rule_id/a');
            $rule_id=implode(',',$rule_id);
            $data=[
                'rules' =>  $rule_id,
            ];
            if(model('AuthGroup')->save($data,['id'=>$id])){
                return $this->success('设置成功！','AuthGroup/index');
            }else{
                $this->error('设置失败！');
            }
        }else{
            $rule_is=db('AuthGroup')->field('rules')->where(['id'=>$id])->find();

            if(empty($rule_is)){
                $this->error('无此数据！');
            }
            $rule_is=explode(',',$rule_is['rules']);
            $this->assign('id',$id);
            $this->assign('rule_is',$rule_is);
            $this->assign('rules',rules());
//            print_r(rules());exit;
            return $this->fetch('rule');
        }

    }
}