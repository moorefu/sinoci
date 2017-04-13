<?php

namespace App\Logics\Model;

interface Util
{
    
    /**
     * 文件上传功能
     */
    public function upload($file, $path = null);
    
    /**
     * 权限验证
     */
    public function validate($data, $rules = [], $callback = 'show_error');

}
