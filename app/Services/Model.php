<?php

namespace App\Services;

/**
 * 框架组件 - 模型
 *
 * @package App\Services
 */
class Model
{

    protected $onAir;

    public function current($attributes = null)
    {
        $onAir = $this->onAir ?: get_called_class();

        if (func_num_args() == 0) {
            return session($onAir);
        }

        if (is_string($attributes)) {
            return data_get(session($onAir), $attributes);
        }

        return session($onAir, $attributes);
    }

}
