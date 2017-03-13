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

    public function adapt($before, $after)
    {
        $model = new self;
        $model->adapt = [
            'model' => get_called_class(),
            'before' => $before,
            'after' => $after
        ];
        return $model;
    }

    public function __call($func, $args)
    {
        if ($this->adapt) {
            $model = new $this->adapt['model'];

            if (method_exists($model, $before = $this->adapt['before'])) {
                $args = call_user_func([$model, $before], $args);
            }

            $data = call_user_func_array([$model, $func], $args);

            if (method_exists($model, $after = $this->adapt['after'])) {
                $data = call_user_func([$model, $after], $data);
            }

            return $data;
        }

        throw new \Error('Call to undefined method ' . get_called_class() . "::{$func}()");
    }

}
