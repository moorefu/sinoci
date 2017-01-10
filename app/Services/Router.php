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
        if (! is_callable($action)) {
            list($class, $method) = explode('@', $action);
            $action = [new $class, $method];
        }

        $this->route[$uri]['GET'] = $action;
    }

}
