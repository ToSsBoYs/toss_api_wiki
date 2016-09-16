<?php
/**
 * Created by PhpStorm.
 * User: xun
 * Date: 2016/8/1
 * Time: 13:35
 */

namespace qiniu;
// 引入鉴权类
use Qiniu\Auth;
// 引入上传类
use Qiniu\Storage\UploadManager;
class myqiniu {
    /**
     * 上传文件
     * @param $bucket  空间名
     * @param $key  文件名
     * @param $filePath 上传的文件路径
     * @return null
     * @throws \Exception
     */
    public function upload($bucket,$key,$filePath){
        $token=$this->create_token($bucket);
        // 初始化 UploadManager 对象并进行文件的上传
        $uploadMgr = new UploadManager();
        // 调用 UploadManager 的 putFile 方法进行文件的上传
        list($ret, $err) = $uploadMgr->putFile($token, $key, $filePath);

        if ($err !== null) {
            //上传失败
            return null;
        } else {
            //上传成功
            return $ret;
        }
    }

    /**
     * 生成token
     * @param string $bucket 空间名
     * @return string
     */
    public function create_token($bucket){
        $qiniu_access_key=config('myconfig.qiniu_access_key');
        $qiniu_secret_key=config('myconfig.qiniu_secret_key');
        // 构建鉴权对象
        $auth = new Auth($qiniu_access_key, $qiniu_secret_key);
        // 要上传的空间
        // 生成上传 Token
        $token = $auth->uploadToken($bucket);
        return $token;
    }


    /**
     * 私有文件下载
     * @param string $key 文件名（key）
     * @param string $qiniu_domain 私有域名
     * @return string 私有下载地址
     */
    public function private_download($key='',$qiniu_domain=''){
        // 需要填写你的 Access Key 和 Secret Key
        $qiniu_access_key=config('myconfig.qiniu_access_key');
        $qiniu_secret_key=config('myconfig.qiniu_secret_key');

        $qiniu_domain_1=config::get('myconfig.qiniu_domain_1');

        // 构建鉴权对象
        $auth = new Auth($qiniu_access_key, $qiniu_secret_key);
        //baseUrl构造成私有空间的域名/key的形式
        $baseUrl = $qiniu_domain_1.$key;
        $authUrl = $auth->privateDownloadUrl($baseUrl);

        return $authUrl;
    }
}