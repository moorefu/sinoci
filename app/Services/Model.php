<?php

namespace App\Services;

/**
 * 框架组件 - 模型
 *
 * @package App\Services
 */
class Model
{

    public function __call($func, $args)
    {
        if (starts_with($func, $prefix = 'adapt')) {
            $func = substr($func, strlen($prefix));
            if (method_exists($this, $before = "before{$func}")) {
                $args = call_user_func([$this, $before], $args);
            }
            $data = call_user_func_array([$this, $func], $args);
            if (method_exists($this, $after = "after{$func}")) {
                $data = call_user_func([$this, $after], $data);
            }
            return $data;
        }

        throw new \Error('Call to undefined method ' . get_called_class() . "::{$func}()");
    }

}
