<?php

namespace App\Models;

use App\Services\Model;
use App\Logics\Model\Util as Logic;

/**
 * 辅助业务模型
 *
 * @package App\Models
 */
class Util extends Model implements Logic
{
    
    public function upload($file, $path = null)
    {
        app()->upload->initialize(config('upload') + [
            'file_name' => md5_file($_FILES[$file]['tmp_name'])
        ]);
        
        if (is_string($path)) {
            app()->upload->upload_path = config('upload.upload_path') . $path;
            
            if (noFile($path = app()->upload->upload_path)) {
                mkdir($path) && mkdir($path . 'thumb/');
            }
        }
        
        return app()->upload->do_upload($file);
    }
    

    public function validate($data, $rules = [], $callback = 'show_error')
    {
        // 设置数据
        app()->form_validation->set_data($data);

        // 设置规则
        app()->form_validation->set_rules($rules);

        // 验证权限
        if (app()->form_validation->run()) {
            return true;
        }

        // 获取错误
        $error = head(app()->form_validation->error_array());

        // 执行错误回调
        return $callback($error);
    }

}
