<?php

namespace App\Services;

/**
 * 框架组件 - 路由绑定
 *
 * @package App\Services
 */
class Router
{

    private $route;

    public function __construct(&$route)
    {
        $this->route =& $route;
    }

    public function get($uri, $action)
    {
        $this->route[$uri]['GET'] = $this->makeCallable($action);
        return $this;
    }

    public function post($uri, $action)
    {
        $this->route[$uri]['POST'] = $this->makeCallable($action);
        return $this;
    }

    private function makeCallable($callable)
    {
        if (!is_callable($callable)) {
            list($class, $method) = explode('@', $callable);
            $callable = [new $class, $method];
        }

        return $callable;
    }

}
