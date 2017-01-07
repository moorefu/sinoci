<?php

call_user_func(function ($routes = [
    'GET' => [],
    'POST' => []
]) use (&$route) {
    foreach ($routes as $method => $uri) {
        foreach ($uri as $url => $action) {
            if (is_string($url)) {
                $route[substr($url, 1)][$method] = $action;
            }
            else {
                $route[substr($action, 1)][$method] = preg_replace_callback('/\/(\w+\/)(.+)/', function ($matches) {
                    return $matches[1] . preg_replace_callback('/\/([a-z])/', function ($matches) {
                        return strtoupper($matches[1]);
                    }, $matches[2]);
                }, $action);
            }
        }
    }
});
