<?php

$hook['pre_system'][] = function () {

    // 如果开发环境的数据库文件不存在
    if (noFile(config('database.database'))) {

        // 转义中文字符
        $file = iconv('gbk', 'utf-8', config('database.database'));

        // 创建 Sqlite 数据库
        new PDO('sqlite:' . $file);
    }

};
