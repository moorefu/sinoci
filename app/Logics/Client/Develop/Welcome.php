<?php

namespace App\Logics\Client\Develop;

interface Welcome
{

    /**
     * 首页欢迎界面
     *
     * @param \App\Models\Site $site
     */
    public function index($site);

    /**
     * 聊天室发送消息接口
     */
    public function chat();

    /**
     * 数据库迁移示例
     */
    public function migrate();

}
