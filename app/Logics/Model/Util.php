<?php

namespace App\Logics\Model;

interface Util
{

    /**
     * 权限验证
     */
    public function validate($data, $rules = [], $callback = 'show_error');

}
