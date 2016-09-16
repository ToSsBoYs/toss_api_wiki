<?php
/**
 * Created by PhpStorm.
 * User: xun
 * Date: 2016/7/28
 * Time: 16:43
 */
namespace app\admin\controller;
class UserInfo extends Base{
    public function index(){
        $model=model('common/AdminUser');

        $auth_group_title=model('common/AuthGroup')->get_group_title();
        $this->assign('get_group_title',$auth_group_title);

        $data=$model->order('id desc')->paginate(15);
        $this->assign('data',$data);

        return $this->fetch('index');
    }

    public function del(){
        $Model=model('common/AdminUser');
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
        $model=model('common/AdminUser');
        $id=input('param.id');
        if(empty($id)){
            $this->error('非法参数！');
        }
        if(request()->isPost()){
            $data=input('param.');
            $result=$this->validate($data,'AdminUser.edit');
            if(true !== $result){
                // 验证失败 输出错误信息
                $this->error($result);
            }elseif($model->allowField(true)->save($data,['id'=>$id])){
                return $this->success('更新成功', 'UserInfo/index');
            }else{
                $this->error('更新失败！');
            }

        }else{
            $auth_group=model('AuthGroup')->get_group();
            $this->assign('auth_group',$auth_group);

            $data=$model->where(['id'=>$id])->find();
            $this->assign('data',$data);
            return $this->fetch('edit');
        }
    }
    public function add(){
        if(request()->isPost()){
            $model=model('common/AdminUser');

            $data=input('param.');
            $result=$this->validate($data,'AdminUser.add');
            if(true !== $result){
                // 验证失败 输出错误信息
                $this->error($result);
            }elseif($model->allowField(true)->data($data)->save()){
                return $this->success('新增成功', 'UserInfo/index');
            }else{
                $this->error('新增失败！');
            }
        }else{
            $auth_group=model('AuthGroup')->get_group();
            $this->assign('auth_group',$auth_group);
            return $this->fetch('add');
        }
    }
}