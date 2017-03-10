<?php

namespace App\Services;

use Leafo\ScssPhp\Compiler;
use Leafo\ScssPhp\Formatter\Crunched;
use Leafo\ScssPhp\Server;

/**
 * 框架组件 - 资源加载
 *
 * @package App\Services
 */
class Loader
{

    /**
     * 静态资源加载
     *
     * @param string $name
     * @return void
     */
    public function assets($name)
    {
        // 解码资源名
        $name = rawurldecode($name);

        // 资源类型
        $type = head(explode('/', $name));

        // 编译插件
        method_exists($this, $type) OR $type = 'raw';

        // 加载资源
        call_user_func([$this, $type], $name);

        // 结束程序
        exit;
    }

    /**
     * 原始资源加载
     *
     * @param string $name
     * @return \CI_Output
     */
    public function raw($name, $prefix = false)
    {
        // 资源路径
        $prefix OR $name = APPPATH . 'resources/assets/' . $name;

        // 文件是否存在
        file_exists($name) OR show_404();

        // 加载输出类库
        $output = load_class('Output', 'core');

        // 设置响应类型
        $output->set_content_type(pathinfo($name, PATHINFO_EXTENSION));

        // 输出响应
        $output->set_output(file_get_contents($name))->_display();
    }

    /**
     * scss 文件编译
     *
     * @param string $name
     * @return void
     */
    public function scss($name)
    {
        // 优化文件名
        $name && $_GET['p'] = $name;

        // scss 编译器
        $scss = new Compiler;
        $scss->setFormatter(Crunched::class);
        $scss->addImportPath(APPPATH . 'resources/assets/scss/');
        $scss->addImportPath(APPPATH . 'resources/assets/vendor/uikit/scss/');

        // scss 服务启动
        $server = new Server(APPPATH . 'resources/assets', config('cache_path'), $scss);
        $server->serve();
    }

    /**
     * 上传资源加载
     *
     * @param string $name
     * @return \CI_Output
     */
    public function upload($name)
    {
        // 资源路径
        $name = dirname(config('upload.upload_path')) . '/' . $name;

        // 加载资源
        $this->raw($name, true);
    }

}
