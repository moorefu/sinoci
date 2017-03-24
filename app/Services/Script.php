<?php

namespace App\Services;

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
    public static function run($event)
    {
        // 自动加载
        define('APPPATH', dirname($event->getComposer()->getConfig()->get('vendor-dir')) . '/');
        require APPPATH . 'vendor/autoload.php';

        // 执行命令
        $args = $event->getArguments();
        call_user_func_array([new static, array_shift($args)], $args);
    }

    /**
     * 生成接口手册
     */
    public function api()
    {
        passthru('php vendor/sami/sami/sami.php update --force scripts/docs.api.php');
        echo "Listening on http://localhost:9000\n";
        echo "Press Ctrl-C to quit.\n";
        passthru('php -S localhost:9000 -t docs/api');
    }

    /**
     * 进入调试模式
     */
    public function debug()
    {
        passthru('php vendor/psy/psysh/bin/psysh');
    }

    /**
     * 开启推送服务
     */
    public function push($command = 'start')
    {
        $command == 'daemon' && $command = 'start -d';
        passthru('php scripts/serve.push.php ' . $command);
    }

    /**
     * 启动服务器
     */
    public function serve($address = 'localhost', $port = 9000)
    {
        $address .= ':' . $port;
        echo "Listening on http://{$address}\n";
        echo "Press Ctrl-C to quit.\n";
        passthru("php -S {$address} -t public scripts/serve.dev.php");
    }

}
