<?php

// 配置表单验证
$config['error_prefix'] = '<div class="error_prefix">';
$config['error_suffix'] = '</div>';

// 验证规则
$config['signup'] = [
    [
        'field' => 'username',
        'label' => 'Username',
        'rules' => 'required'
    ]
];
