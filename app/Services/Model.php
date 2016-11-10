<?php

namespace App\Services;

/**
 * 框架组件 - 模型
 *
 * @package App\Services
 */
class Model
{

    /**
     * 静态的调用方式
     *
     * @param string $func
     * @param array $args
     * @return mixed
     */
	public static function __callStatic($func, $args)
	{
		$instance = new static;

		return call_user_func_array([$instance, $func], $args);
	}

}
