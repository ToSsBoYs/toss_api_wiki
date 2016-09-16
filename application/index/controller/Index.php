<?php
namespace app\index\controller;

use think\Controller;

class Index extends Controller
{
    public function index()
    {
        $model=model('common/Project');
        $data=$model->select();
        $this->assign('data',$data);
        return $this->fetch('index');
    }
}
