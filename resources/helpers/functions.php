<?php

use App\Services\Table;
use Illuminate\Support\Facades\View;

if (empty(function_exists('noFunc'))) {

    function noFunc($name)
    {
        return empty(function_exists($name));
    }

}

if (noFunc('noFile')) {

    function noFile($path)
    {
        return empty(file_exists($path));
    }

}

if (noFunc('app')) {

    function app($name = '')
    {
        if (empty($name)) {
            return $GLOBALS['CI'];
        }

        $model = '\\App\\Models\\' . $name;

        return new $model;
    }

}

if (noFunc('config')) {

    function config($key, $default = null)
    {
        return array_get(get_config(), $key, $default);
    }

}

if (noFunc('env')) {

    function env($key, $default = null)
    {
        return getenv($key) ?: $default;
    }

}

if (noFunc('lang')) {

    function lang($key, $lang = '')
    {
        $lang OR $lang = app()->session->language ?: config('language');

        list($index, $file) = explode('@', $key);

        app()->lang->load($file ?: APP_ENV, $lang, false, false);

        return app()->lang->line($index) ?: $index;
    }

}

if (noFunc('push')) {

    function push($target = [])
    {
        $client = stream_socket_client('tcp://localhost:2021');
        fwrite($client, json_encode($target) . "\n");
        fread($client, 8192);
        fclose($client);
    }

}

if (noFunc('request')) {

    function request()
    {
        return app()->input;
    }

}

if (noFunc('response')) {

    function response()
    {
        return app()->output;
    }

}

if (noFunc('session')) {

    function session()
    {
        return app()->session;
    }

}

if (noFunc('table')) {

    function table($name = '')
    {
        if (empty($name)) {
            return new Table;
        }

        $table = '\\App\\Tables\\' . $name;

        return new $table;
    }

}

if (noFunc('view')) {

    function view($path = '', $data = [])
    {
        return View::make($path, $data);
    }

}
