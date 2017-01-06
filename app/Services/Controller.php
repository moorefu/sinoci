<?php

namespace App\Services;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Pagination\AbstractPaginator;

/**
 * 框架组件 - 控制器
 *
 * @package App\Services
 */
class Controller
{

    /**
     * 程序执行逻辑
     *
     * @param string $func
     * @param array $args
     * @return \CI_Output
     */
    public function _remap($func, array $args)
    {
        // 排除不存在的方法
        method_exists(app(), $func) OR show_404();

        // 加载类库和语言
        app()->load->add_package_path(APPPATH . 'resources');

        // 获取程序执行结果
        $output = call_user_func_array([app(), $func], $args);

        // 分步执行
        if ($output instanceof Process) {
            process()->resolve(null);
            $output = $output->wait();
        }

        // 转换为数组
        $output instanceof AbstractPaginator && $output = $output->getCollection();
        $output instanceof Arrayable && $output = $output->toArray();

        // 设置编码与响应类型
        if (is_array($output)) {
            $output = json_encode($output, JSON_UNESCAPED_UNICODE);
            response()->set_content_type('json');
        }

        // 返回请求结果
        return response($output);
    }

    /**
     * 常用类库自动加载
     *
     * @param string $name
     * @return mixed
     */
    public function __get($name)
    {
        // 修复 cache 类库
        if ($name === 'cache') {
            app()->load->driver('cache', ['adapter' => 'redis', 'backup' => 'file']);
            return app()->cache;
        }

        // 加载 CI 类库
        if (in_array($name, ['agent', 'cart', 'email', 'encryption', 'form_validation', 'image_lib', 'session', 'unit', 'upload'])) {
            app()->load->library(array_get(['agent' => 'user_agent', 'unit' => 'unit_test'], $name, $name));
            return app()->$name;
        }

        // 加载系统类库
        return load_class($name === 'load' ? 'Loader' : is_loaded()[$name], 'core');
    }

    /**
     * 相应渲染视图
     *
     * @param mixed $data
     * @param string $view
     * @param bool $absolute
     * @return \Illuminate\Contracts\View\View
     */
    public function view($data = null, $view = null, $absolute = false)
    {
        // 转换无数据模板
        is_string($data) && $view = $data;

        // 映射相应模板
        $view = $view ?: implode('.', [
            app()->uri->rsegment(1), app()->uri->rsegment(2)
        ]);

        // 添加路径前缀
        $absolute OR $view = APP_ENV . '.' . $view;

        // 返回渲染结果
        return view($view, compact('data'));
    }

}
