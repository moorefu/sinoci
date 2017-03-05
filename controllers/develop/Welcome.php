<?php

use App\Services\Controller;
use App\Logics\Client\Develop\Welcome as Logic;

class Welcome extends Controller implements Logic
{

    public function index()
    {
        // 欢迎界面
        return process()
            ->next('Site@showWelcome');
    }

    public function chat($client, $message)
    {
        // 发送消息
        return push('new message', [$client, $message]);
    }

}
