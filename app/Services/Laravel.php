<?php

namespace App\Services;

use Illuminate\Container\Container;
use Illuminate\Database\Capsule\Manager;
use Illuminate\Database\DatabaseServiceProvider;
use Illuminate\Events\EventServiceProvider;
use Illuminate\Filesystem\FilesystemServiceProvider;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Facade;
use Illuminate\View\ViewServiceProvider;

/**
 * 框架组件 - Laravel
 *
 * @package App\Services
 */
class Laravel
{

    /**
     * Laravel 容器
     *
     * @var \Illuminate\Contracts\Container\Container
     */
    private $container;

    /**
     * 初始化 Laravel 服务
     *
     * @return void
     */
    public function __construct()
    {
        // 初始化容器
        $this->container = new Container;

        // 循环开启功能
        array_filter($this->getServices(), function ($service) {
            call_user_func([$this, 'boot' . $service]);
        });

        // 绑定应用容器
        Facade::setFacadeApplication($this->container);

        // 开启事件监听
        (new Event)->bindEvents();
    }

    /**
     * 获取服务列表
     *
     * @return array
     */
    public function getServices()
    {
        // 获取配置
        $services = config('use_laravel');

        // 格式转换
        is_bool($services) && $services = ['eloquent', 'blade'];

        // 返回服务列表
        return (array)$services;
    }

    /**
     * 开启 Eloquent 功能
     *
     * @return void
     */
    public function bootEloquent()
    {
        // 初始化相关服务
        $this->bootService(EventServiceProvider::class);
        $this->bootService(DatabaseServiceProvider::class);

        // 修复分页页码
        Paginator::currentPageResolver(function () {
            return request()->get('page') ?: 1;
        });

        // 定制分页样式
        Paginator::presenter(function ($paginator) {

            // 修复分页路径
            $paginator->setPath(null);

            // 添加已有参数
            $paginator->appends(array_except(request()->get(), 'page'));

            // 返回分页模板
            return new Pagination($paginator);
        });

        // 初始化 Eloquent
        $manager = new Manager($this->container);
        $manager->addConnection(config('database'));
        $manager->setAsGlobal();
        $manager->bootEloquent();
    }

    /**
     * 开启 Blade 功能
     *
     * @return void
     */
    public function bootBlade()
    {
        // 模板相关配置
        $this->container['config']['view.paths'] = [VIEWPATH];
        $this->container['config']['view.compiled'] = config('cache_path');

        // 初始化相关服务
        $this->bootService(FilesystemServiceProvider::class);
        $this->bootService(ViewServiceProvider::class);

        // 创建模板编译目录
        file_exists(config('cache_path')) OR mkdir(config('cache_path'));
    }

    /**
     * 启动相应服务
     *
     * @param mixed $provider
     * @return void
     */
    public function bootService($provider)
    {
        // 获取服务提供者
        $service = new $provider($this->container);

        // 注册相关服务
        $service->register();
    }

}
