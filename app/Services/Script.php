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
        passthru('php -S localhost:9000 -t docs/api');
    }

    /**
     * 导入已有静态资源
     */
    public function assets()
    {
        $resources = [
            'vendor/sami/sami/Sami/Resources/themes/default/css' => [
                'bootstrap.min.css',
                'bootstrap-theme.min.css'
            ],
            'vendor/sami/sami/Sami/Resources/themes/default/js' => [
                'bootstrap.min.js'
            ],
            'vendor/workerman/phpsocket.io/examples/chat/public' => [
                'jquery.min.js'
            ],
            'vendor/workerman/phpsocket.io/examples/chat/public/socket.io-client' => [
                'socket.io.js'
            ]
        ];

        array_walk($resources, function ($resource, $key, $vendor = 'resources/assets/vendor') {

            file_exists(APPPATH . $vendor) OR mkdir(APPPATH . $vendor);

            foreach ($resource as $filename) {
                copy(APPPATH . $key . '/' . $filename, APPPATH . $vendor . '/' . $filename);
            }
        });
    }

    /**
     * 启动服务器
     */
    public function dev()
    {
        passthru('php -S localhost:9000 -t public scripts/serve.dev.php');
    }

    /**
     * 进入调试模式
     */
    public function fly()
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

}
