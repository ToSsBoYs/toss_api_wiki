<?php
/**
 * Created by PhpStorm.
 * User: 珣
 * Date: 2016/9/5
 * Time: 22:52
 */

namespace app\edit\controller;


class Index extends Base
{
    public function index(){
        $model=model('common/Project');
        $data=$model->select();
        $this->assign('data',$data);
        return $this->fetch('index');
    }
}