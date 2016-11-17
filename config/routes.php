<?php

use App\Services\Loader;

// 引入 初始路由配置
require_once dirname(BASEPATH) . '/application/config/routes.php';

// 静态资源
$route['assets/(.+)']['GET'] = [new Loader, 'assets'];
