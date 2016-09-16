<?php
/**
 * Created by PhpStorm.
 * User: xun
 * Date: 2016/7/27
 * Time: 10:33
 */

namespace app\admin\controller;
class Index extends Base{
    public function index(){
        $model=model('common/ImgTest');
        $data=$model->paginate(15);
        $this->assign('data',$data);

        return $this->fetch('index');
    }
}