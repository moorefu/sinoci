<?php

use Sami\Sami;
use Sami\Parser\Filter\TrueFilter;
use Symfony\Component\Finder\Finder;

// 应用根目录
$root = dirname(__DIR__);

// 生成解析类
$finder = Finder::create()->files()->name('*.php');

// 配置规则
$finder->exclude(['storage', 'vendor'])->in($root);

// 生成手册
return new Sami($finder, [
    'build_dir' => $root . '/docs/api',
    'cache_dir' => $root . '/storage/cache/sami',
    'filter' => function () {
        return new TrueFilter();
    }
]);
