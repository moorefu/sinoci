<?php

namespace App\Services;

use GuzzleHttp\Promise\Promise;
use GuzzleHttp\Promise\PromiseInterface;

/**
 * 框架组件 - 执行流程
 *
 * @package App\Services
 */
class Process implements PromiseInterface
{

    private static $instance;
    private $promise;

    private function __construct($promise)
    {
        $this->promise = $promise;
    }

    public static function instance()
    {
        if (is_null(self::$instance)) {
            self::$instance = new static(new Promise);
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

    private function parse($input)
    {
        if (is_callable($input)) {
            return $input;
        }

        list($model, $func) = explode('@', $input);

        return [app($model), $func];
    }

    public function then(callable $onFulfilled = null, callable $onRejected = null)
    {
        return new static($this->promise->then($onFulfilled, $onRejected));
    }

    public function otherwise(callable $onRejected)
    {
        return new static($this->promise->otherwise($onRejected));
    }

    public function getState()
    {
        return $this->promise->getState();
    }

    public function resolve($value)
    {
        return $this->promise->resolve($value);
    }

    public function reject($reason)
    {
        return $this->promise->reject($value);
    }

    public function cancel()
    {
        return $this->promise->cancel();
    }

    public function wait($unwrap = true)
    {
        return $this->promise->wait($unwrap);
    }

}
