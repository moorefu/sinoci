<?php

namespace App\Services;

use Composer\Script\Event;

/**
 * 框架组件 - 命令脚本
 *
 * @package App\Services
 */
class Script
{

    /**
     * 命令执行入口
     *
     * @param Event $event
     */
    public static function run(Event $event)
    {
        // 自动加载
        define('APPPATH', dirname($event->getComposer()->getConfig()->get('vendor-dir')) . '/');
        require APPPATH . 'vendor/autoload.php';

        // 执行命令
        $args = $event->getArguments();
        call_user_func_array([new static, array_shift($args)], $args);
    }

    /**
     * 进入调试模式
     */
    public function fly()
    {
        eval(\Psy\sh());
    }

    /**
     * 启动服务器
     */
    public function dev()
    {
        passthru(PHP_BINARY . ' -S localhost:9000 -t public scripts/serve.dev.php');
    }

    /**
     * 生成接口手册
     */
    public function api()
    {
        passthru('php vendor/bin/sami.php update --force scripts/docs.api.php');
        passthru('php -S localhost:9000 -t docs/api');
    }

}
