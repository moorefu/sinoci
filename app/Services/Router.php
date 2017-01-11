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

    public function get($uri, $action = 'show_404')
    {
        return $this->makeRoute($uri, $action, 'GET');
    }

    public function post($uri, $action = 'show_404')
    {
        return $this->makeRoute($uri, $action, 'POST');
    }

    private function makeCallable($callable)
    {
        if (!is_callable($callable)) {
            list($class, $method) = explode('@', $callable);
            $callable = [new $class, $method];
        }

        return $callable;
    }

    private function makeAction($uri, $method)
    {
        foreach ($uri as $url => $action) {
            if (is_string($url)) {
               $this->route[$url][$method] = $action;
            }
            else {
               $this->route[$action][$method] = preg_replace_callback('/(\w+\/)(.+)/', function ($matches) {
                   return $matches[1] . preg_replace_callback('/\/([a-z])/', function ($matches) {
                       return strtoupper($matches[1]);
                   }, $matches[2]);
               }, $action);
            }
        }
    }

    private function makeRoute($uri, $action = 'show_404', $method = 'GET')
    {
        if (is_string($uri)) {
            $this->route[$uri][$method] = $this->makeCallable($action);
        }
        else {
            $this->makeAction($uri, $method);
        }

        return $this;
    }

}
