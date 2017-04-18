<?php

use App\Services\Controller;
use App\Logics\Client\Develop\Welcome as Logic;

class Welcome extends Controller implements Logic
{

    public function index($site)
    {
        // 欢迎界面
        return $site->showWelcome();
    }

    public function chat()
    {
        // 参数解析
        $client = app()->uri->rsegment(3);
        $message = app()->uri->rsegment(4);

        // 发送消息
        return push('new message', [$client, $message]);
    }

    public function migrate()
    {
        // 创建数据表
        table('login')->make();
        table('user')->make();

        // 插入数据
        $login = table('login')->insert();
        table('user')->insert([
            'login_id' => $login->id
        ]);

        // 查询全部数据
        return table('user')->with('login')->withTrashed()->get();
    }

}
