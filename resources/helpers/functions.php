<?php

use App\Services\Process;
use App\Services\Router;
use Illuminate\Database\Capsule\Manager;
use Illuminate\Support\Facades\View;

if (empty(function_exists('noFunc'))) {

    function noFunc($func)
    {
        return empty(function_exists($func));
    }

}

if (noFunc('noFile')) {

    function noFile($file)
    {
        return empty(file_exists($file));
    }

}

if (noFunc('app')) {

    function app($name = null)
    {
        if (is_null($name)) {
            return $GLOBALS['CI'];
        }

        $model = '\\App\\Models\\' . str_replace('.', '\\', $name);

        class_exists($model) OR show_404();

        return new $model;
    }

}

if (noFunc('config')) {

    function config($key = null, $default = null)
    {
        if (is_null($key)) {
            return get_config();
        }

        if (!array_has(get_config(), $key)) {
            $config = explode('.', $key)[0];
            load_class('Config', 'core')->load($config, true);
        }

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

    function lang($key, $lang = null)
    {
        is_null($lang) && $lang = app()->session->language ?: config('language');

        list($index, $file) = explode('@', $key);

        app()->lang->load($file ?: APP_ENV, $lang, false, false);

        return app()->lang->line($index) ?: $index;
    }

}

if (noFunc('process')) {

    function process()
    {
        return Process::instance();
    }

}

if (noFunc('push')) {

    function push($input, $data = [])
    {
        is_string($input) && $input = [$input => $data];
        $socket = stream_socket_client('tcp://127.0.0.1:2021');
        fwrite($socket, json_encode($input) . "\n");
        $output = fread($socket, 8192);
        fclose($socket);
        return $output;
    }

}

if (noFunc('request')) {

    function request($key = null, $default = null)
    {
        if (is_null($key)) {
            return app()->input;
        }

        $method = app()->input->method();

        $value = array_get(app()->input->{$method}(), $key, $default);

        return $value === '' ? null : $value;
    }

}

if (noFunc('response')) {

    function response($data = null)
    {
        if (is_null($data)) {
            return app()->output;
        }

        return app()->output->set_output($data);
    }

}

if (noFunc('routes')) {

    function routes(&$route)
    {
        return new Router($route);
    }

}

if (noFunc('session')) {

    function session($key = null, $value = null)
    {
        $session = app()->session;

        if (is_null($key)) {
            return $session;
        }

        $key = APP_ENV . '.' . $key;

        if (func_num_args() == 1) {
            return array_get($_SESSION, $key);
        }

        array_set($_SESSION, $key, $value);
        return $value;
    }

}

if (noFunc('table')) {

    function table($name = null, $schema = null)
    {
        if (is_null($name)) {
            return Manager::connection();
        }

        if (is_callable($schema)) {
            return Manager::schema()->hasTable($name) OR Manager::schema()->create($name, $schema);
        }

        $table = '\\App\\Tables\\' . str_replace('.', '\\', $name);

        return class_exists($table) ? new $table : Manager::table($name);
    }

}

if (noFunc('view')) {

    function view($path, $data = [])
    {
        return View::make($path, $data);
    }

}
