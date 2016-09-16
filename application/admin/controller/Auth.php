<?php
/**
 * Created by PhpStorm.
 * User: xun
 * Date: 2016/7/28
 * Time: 15:19
 */

namespace app\admin\controller;
class Auth extends Base{
    public function index(){
        $model=model('common/AuthRule');

        $data=$model->order('id desc')->paginate(15);
        $this->assign('data',$data);

        $this->assign('li_title',get_li_title_array());
        return $this->fetch('index');
}

    public function del(){
        $Model=model('common/AuthRule');
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
        $model=model('common/AuthRule');
        $id=input('param.id');
        if(empty($id)){
            $this->error('非法参数！');
        }
        if(request()->isPost()){
            $data=input('param.');
            $li_one_id=input('param.one_id');
            $li_two_id=input('param.two_id');
            if(empty($li_two_id) || $li_two_id==0){
                $data['li_id']=$li_one_id;
            }else{
                $data['li_id']=$li_two_id;
            }

            $result=$this->validate($data,'AuthRule.edit');
            if(true !== $result){
                // 验证失败 输出错误信息
                $this->error($result);
            }elseif($model->allowField(true)->save($data,['id'=>$id])){
                return $this->success('更新成功', 'Auth/index');
            }else{
                $this->error('更新失败！');
            }

        }else{
            $this->assign('get_li_title_array',get_li_title_array('admin'));
            $this->assign('js_li_info',json_encode(li('admin')));
            $data=$model->where(['id'=>$id])->find()->toArray();

            $li_array=model('common/Li')->get_li('admin');
            foreach($li_array as $k=>$v){
                if($data['li_id'] == $v['id']){
                    if($v['li_up'] == 0){
                        $data['one_id']=$v['id'];
                        $data['two_id']='0';
                    }else{
                        $data['one_id']=$v['li_up'];
                        $data['two_id']=$v['id'];
                    }
                }else{
                    $data['one_id']='0';
                    $data['two_id']='0';
                }
            }
            $this->assign('data',$data);
            return $this->fetch('edit');
        }
    }
    public function add(){
        if(request()->isPost()){
            $model=model('common/AuthRule');
            $data=input('param.');

            $li_one_id=input('param.one_id');
            $li_two_id=input('param.two_id');
            if(empty($li_two_id) || $li_two_id==0){
                $data['li_id']=$li_one_id;
            }else{
                $data['li_id']=$li_two_id;
            }

            $result=$this->validate($data,'AuthRule.add');
            if(true !== $result){
                // 验证失败 输出错误信息
                $this->error($result);
            }elseif($model->allowField(true)->data($data)->save()){
                return $this->success('新增成功', 'Auth/index');
            }else{
                $this->error('新增失败！');
            }

        }else{
            $this->assign('js_li_info',json_encode(li('admin')));
            return $this->fetch('add');
        }
    }
}