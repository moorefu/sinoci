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
            if (method_exists($this, $before = array_get($args, count($args) - 2))) {
                $args = call_user_func([$this, $before], $args);
            }
            $data = call_user_func_array([$this, substr($func, strlen($prefix))], $args);
            if (method_exists($this, $after = array_get($args, count($args) - 1))) {
                $data = call_user_func([$this, $after], $data);
            }
            return $data;
        }

        throw new \Error('Call to undefined method ' . get_called_class() . "::{$func}()");
    }

}
