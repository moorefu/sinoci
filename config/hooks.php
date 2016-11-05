<?php

$hook['post_controller_constructor'][] = function () {

    // 重新绑定实例
    $app =& get_instance();
    $app = app();
};
