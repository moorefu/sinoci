<?php

namespace App\Services;

/**
 * 框架组件 - 模型
 *
 * @package App\Services
 */
class Model
{

    private $adapt;

    public function adapt($before, $after = null)
    {
        $model = new self;
        $model->adapt = [
            'model' => $this,
            'before' => $before,
            'after' => $after
        ];
        return $model;
    }

    public function __call($func, $args)
    {
        if ($this->adapt) {
            $model = $this->adapt['model'];
            $adapt = function ($model, $func, $args) {
                if (is_callable($func)) {
                    return call_user_func($func, $args);
                }
                if (method_exists($model, $func)) {
                    return call_user_func([$model, $func], $args);
                }
                return $args;
            };

            $args = $adapt($model, $this->adapt['before'], $args);
            $data = call_user_func_array([$model, $func], $args);
            return $adapt($model, $this->adapt['after'], $data);
        }

        throw new \Error('Call to undefined method ' . get_called_class() . "::{$func}()");
    }

}
