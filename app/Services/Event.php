<?php

namespace App\Services;

/**
 * 框架组件 - 事件
 *
 * @package App\Services
 */
class Event
{

    /**
     * 触发错误处理
     *
     * @return void
     */
    public function error()
    {
        // 忽略框架错误
        if (str_contains(func_get_arg(2), str_replace('/', DIRECTORY_SEPARATOR, BASEPATH))) {
            return;
        }

        // 捕捉非框架错误
        if (APP_DEBUG) {
            call_user_func_array('_error_handler', func_get_args());
        }

        // 显示错误信息
        show_error(func_get_arg(1));
    }

    /**
     * 触发异常处理
     *
     * @return void
     */
    public function exception()
    {
        // 捕捉异常
        if (APP_DEBUG) {
            call_user_func_array('_exception_handler', func_get_args());
        }

        // 显示错误信息
        show_error(func_get_arg(0)->getMessage());
    }

}
