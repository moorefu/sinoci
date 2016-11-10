<?php

use App\Services\Event;
use App\Services\Laravel;

$hook['post_controller_constructor'][] = function () {

    // 重新绑定实例
    $app =& get_instance();
    $app = app();
};

$hook['pre_system'][] = function () {

    // 绑定异常处理
    set_error_handler([new Event, 'error']);
    set_exception_handler([new Event, 'exception']);

    // 启动 Laravel 扩展
    config('use_laravel') && new Laravel;
};
