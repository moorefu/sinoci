<?php

namespace App\Models;

use App\Services\Model;
use App\Logics\Model\Site as Logic;

/**
 * 网站业务模型
 *
 * @package App\Models
 */
class Site extends Model implements Logic
{

    public function showWelcome()
    {
        // 添加 CI 默认视图目录
        app()->load->add_package_path(dirname(BASEPATH) . '/application/');

        // 加载 CI 默认欢迎页
        $welcome = app()->load->view('welcome_message', null, true);

        // 修正信息
        return strtr($welcome, [
            ' to CodeIgniter!' => '',
            '40px' => '40px 10px',
            'application/controllers/Welcome.php' => 'controllers/' . APP_ENV . '/Welcome.php',
            'application/views/welcome_message.php' => 'app/Models/Site.php',
            'CodeIgniter' => '<a href="//github.com/sinoci/sinoci" target="_blank"><strong>sinoci</strong></a>',
            'container"' => 'container" style="max-width:600px;margin:10px auto"',
            'user_guide/' => '//github.com/sinoci/sinoci/wiki" target="_blank',
            'Welcome to CodeIgniter<' => 'S I N O C I<',
        ]);
    }

}
