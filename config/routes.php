<?php

// 引入 初始路由配置
require_once dirname(BASEPATH) . '/application/config/routes.php';

// 映射路由
routes($route)
// 静态资源
->get('assets/(.+)', 'App\Services\Loader@assets');
