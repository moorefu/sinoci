<?php

namespace App\Services;

use GuzzleHttp\Promise\Promise;

/**
 * 框架组件 - 执行流程
 *
 * @package App\Services
 */
class Process extends Promise
{

    private static $instance;

    public static function instance()
    {
        if (is_null(self::$instance)) {
            self::$instance = new static;
        }

        return self::$instance;
    }

    public function next($resolve = null, $reject = null)
    {
        return $this->then($resolve, $reject);
    }

}