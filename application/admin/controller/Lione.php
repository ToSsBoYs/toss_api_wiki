<?php
/**
 * Created by PhpStorm.
 * User: xun
 * Date: 2016/7/27
 * Time: 14:42
 */

namespace app\admin\controller;
class Lione extends Base{
    public function index(){
        $model=model('common/li');

        $where=[
            'li_module' =>      'Admin',
            'li_up'     =>      0,
        ];
        $order='li_order desc,id';
        $data=$model->where($where)->order($order)->paginate(15);
        $this->assign('data',$data);

        return $this->fetch('index');
    }
    public function del(){
        $Model=model('common/li');
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
        $model=model('common/li');
        $id=input('param.id');
        if(empty($id)){
            $this->error('非法参数！');
        }
        if(request()->isPost()){
            $data=input('param.');
            $result=$this->validate($data,'Lione.add');
            if(true !== $result){
                // 验证失败 输出错误信息
                $this->error($result);
            }elseif($model->save($data,['id'=>$id])){
                return $this->success('更新成功', 'Lione/index');
            }else{
                $this->error('更新失败！');
            }
        }else{
            $data=$model->where(['id'=>$id])->find();
            $this->assign('data',$data);
            return $this->fetch("edit");
        }
    }
    public function add(){
        if(request()->isPost()){
            $model=model('common/li');
            $data=input('param.');
            $result=$this->validate($data,'Lione.add');
            if(true !== $result){
                // 验证失败 输出错误信息
                $this->error($result);
            }elseif($model->data($data)->save()){
                return $this->success('新增成功', 'Lione/index');
            }else{
                $this->error('新增失败！');
            }
        }else{
            return $this->fetch('add');
        }
    }

    public function ajax_icon(){
        return $this->fetch('Public/icon');
    }
}