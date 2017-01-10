<?php

namespace App\Services;

use PHPSocketIO\SocketIO;
use Workerman\Worker;

/**
 * 框架组件 - 推送通知
 *
 * @package App\Services
 */
class Socket
{

    /**
     * 全局长链接
     *
     * @var
     */
    public static $server;

    /**
     * 客户端队列
     *
     * @var
     */
    public static $client;

    /**
     * 当前套接字
     *
     * @var
     */
    public $socket;

    /**
     * 构造函数
     *
     * @param int $port
     */
    public function __construct($port = 2020)
    {
        if (is_null(static::$server)) {
            static::$server = new SocketIO($port);
            static::$server->on('connection', [$this, 'connect']);
            static::$server->on('workerStart', [$this, 'listen']);
            Worker::runAll();
        }
    }

    /**
     * 链接初始化
     *
     * @param $socket
     */
    public function connect($socket)
    {
        // 变量赋值
        $socket->addedUser = false;
        $socket->token = static::$client ? count(static::$client) : 0;

        // 连接客户端
        $client = new static;
        $client->socket = $socket;

        // 需监听事件
        $events = ['new message', 'add user', 'typing', 'stop typing', 'disconnect'];

        // 绑定事件
        array_walk($events, function ($event) use ($client) {
            $client->socket->on($event, [$client, 'on' . studly_case($event)]);
        });

        // 添加到客户端队列
        static::$client[] = $client;
    }

    /**
     * 开启内部端口监听
     */
    public function listen()
    {
        $inner = new Worker('text://127.0.0.1:2021');
        $inner->onMessage = function ($socket, $input) {
            $input = json_decode($input);
            $output = true;
            foreach ($input as $k => $v) {
                $output = $output && call_user_func_array([$this, 'do' . studly_case($k)], $v);
            }
            $socket->send($output);
        };
        $inner->listen();
    }

    /**
     * 用指定身份发送消息
     *
     * @param $target
     * @param $message
     * @return mixed
     */
    public function doNewMessage($target, $message)
    {
        return static::$client[$target]->socket->broadcast->emit('new message', [
            'username' => '[robot]' . static::$client[$target]->socket->username,
            'message' => $message
        ]);
    }

    public function onNewMessage($data)
    {
        // we tell the client to execute 'new message'
        $this->socket->broadcast->emit('new message', [
            'username' => $this->socket->username,
            'message' => $data . ':' . $this->socket->token
        ]);
    }

    public function onAddUser($username)
    {
        global $usernames, $numUsers;
        // we store the username in the socket session for this socket
        $this->socket->username = $username;
        // add the socket's username to the global list
        $usernames[$username] = $username;
        ++$numUsers;
        $this->socket->addedUser = true;
        $this->socket->emit('login', [
            'numUsers' => $numUsers
        ]);
        // echo globally (all clients) that a person has connected
        $this->socket->broadcast->emit('user joined', [
            'username' => $this->socket->username,
            'numUsers' => $numUsers
        ]);
    }

    public function onTyping()
    {
        $this->socket->broadcast->emit('typing', [
            'username' => $this->socket->username
        ]);
    }

    public function onStopTyping()
    {
        $this->socket->broadcast->emit('stop typing', [
            'username' => $this->socket->username
        ]);
    }

    public function onDisconnect()
    {
        global $usernames, $numUsers;
        // remove the username from global usernames list
        if ($this->socket->addedUser) {
            unset($usernames[$this->socket->username]);
            --$numUsers;

            // echo globally that this socket has left
            $this->socket->broadcast->emit('user left', [
                'username' => $this->socket->username,
                'numUsers' => $numUsers
            ]);
        }
    }

}
