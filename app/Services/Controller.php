<?php

namespace App\Services;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Pagination\AbstractPaginator;
use ReflectionClass;
use ReflectionMethod;

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
        // 检查方法是否存在
        method_exists(app(), $func) OR show_404();

        // 加载类库和语言
        app()->load->add_package_path(APPPATH . 'resources');

        // 依赖注入模型实例
        if (config('use_injection')) {
            $args = (new ReflectionMethod(app(), $func))->getParameters();
            $args = array_map(function ($_) { return app($_->getName()); }, $args);
        }

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
        // 修复 CI 类库
        if (in_array($name, ['config', 'lang', 'load', 'input', 'output', 'router', 'security', 'uri'])) {
            return load_class($name === 'load' ? 'Loader' : is_loaded()[$name], 'core');
        }

        // 修复 cache 类库
        if ($name === 'cache') {
            app()->load->driver('cache', ['adapter' => 'redis', 'backup' => 'file']);
            return app()->cache;
        }

        // 修复 database 类库
        if ($name === 'db') {
            require_once dirname(BASEPATH) . '/application/config/database.php';
            app()->load->database(array_merge($db['default'], [
                'database' => config('database.database'),
                'dbdriver' => 'pdo',
                'hostname' => config('database.host'),
                'password' => config('database.password'),
                'subdriver' => config('database.driver'),
                'username' => config('database.username')
            ]));
            return app()->db;
        }

        // 修复其他类库
        app()->load->library(array_get(['agent' => 'user_agent', 'unit' => 'unit_test'], $name, $name));
        return app()->$name;
    }

    /**
     * 模型链式调用
     */
    public function __call($func, $args)
    {
        // 无参实例
        if (empty($args)) {
            return app($func);
        }

        // 模型类名
        $class = '\\App\\Models\\' . str_replace('.', '\\', $func);

        // 检测类名是否正确
        class_exists($class) OR show_404();

        // 对应反射类
        $model = new ReflectionClass($class);

        // 返回实例
        return $model->newInstanceArgs($args);
    }

}
