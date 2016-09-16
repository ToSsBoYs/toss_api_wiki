<?php
/**
 * Created by PhpStorm.
 * User: xun
 * Date: 2016/7/28
 * Time: 11:25
 */

namespace app\admin\controller;
use qiniu\myqiniu;
class QiniuUpload extends Base{
    public function index(){
        if(request()->isPost()){
            $domain=config('myconfig.qiniu_domain_video');
            $bucket=config('myconfig.qiniu_bucket_pic');
            $file_path=$_FILES['image']['tmp_name'];//获取文件地址
            $file_suffix=substr(strrchr($_FILES['image']['name'], '.'), 1);//后缀
            $key=time().'.'.$file_suffix;
            $qiniu=new myqiniu();
            $return=$qiniu->upload($bucket,$key,$file_path);

            if($return === null){
                echo '上传失败';
            }else{
                var_dump($return);
            }
        }else{
            return $this->fetch('index');
        }
    }

    /**
     * 公有文件下载
     */
    public function download()
    {
        return $this->fetch('download');
        echo $baseUrl;
    }

}