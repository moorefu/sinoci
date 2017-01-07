<?php

namespace App\Models;

use App\Services\Model;

/**
 * 入门手册业务模型
 *
 * @package App\Models
 */
class Guide extends Model
{

    public function welcome()
    {
        // 添加 CI 默认视图目录
        app()->load->add_package_path(dirname(BASEPATH) . '/application/');

        // 加载 CI 默认欢迎页
        return app()->load->view('welcome_message', null, true);
    }

    public function sinoci()
    {
        // 修正信息
        return strtr($this->welcome(), [
            ' to CodeIgniter!' => '',
            'application/controllers/Welcome.php' => 'controllers/' . APP_ENV . '/Welcome.php',
            'application/views/welcome_message.php' => 'app/Models/Guide.php',
            'CodeIgniter' => '<a href="//github.com/sinoci/sinoci" target="_blank"><strong>sinoci</strong></a>',
            'container"' => 'container" style="width:600px;margin:10px auto"',
            'user_guide/' => '//github.com/sinoci/sinoci/wiki" target="_blank',
            'Welcome to CodeIgniter<' => 'S I N O C I<',
        ]);
    }

}
