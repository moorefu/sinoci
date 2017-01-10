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

    public function next($resolve = null)
    {
        $resolve = $this->parse($resolve);
        return $this->then($resolve);
    }

    public function fail($reject = null)
    {
        $reject = $this->parse($reject);
        return $this->otherwise($reject);
    }

    public function parse($input)
    {
        if (is_callable($input)) {
            return $input;
        }

        list($model, $func) = explode('@', $input);

        return [app($model), $func];
    }

}
