<?php

namespace App\Logics\Client\Develop;

interface Welcome
{

    /**
     * 首页欢迎界面
     */
    public function index();

    /**
     * 聊天室发送消息接口
     */
    public function chat($client, $message);

    /**
     * 数据库迁移示例
     */
    public function migrate();

}
