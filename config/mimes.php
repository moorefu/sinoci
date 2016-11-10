<?php

// 引入 初始文件类型配置
$mimes = require_once dirname(BASEPATH) . '/application/config/mimes.php';

// 返回文件类型列表
return $mimes;
