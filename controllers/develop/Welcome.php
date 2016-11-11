<?php

use App\Services\Controller;

class Welcome extends Controller
{

    public function index()
    {
        // 添加 CI 默认视图目录
        app()->load->add_package_path(dirname(BASEPATH) . '/application/');

        // 加载 CI 默认欢迎页
        return app()->load->view('welcome_message', null, true);
    }

    public function guide()
    {
        // 跳转到项目主页
        return header('Location: https://github.com/sinoci/sinoci');
    }

}
