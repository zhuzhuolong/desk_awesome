<?php

namespace Proxy;

abstract class MysqlClient extends Base
{
    public $connName;
    public $client;
    public $database;
    public $model;
    public $ssl = false;

    public function connect(string $host, int $port, float $timeout = 0.1)
    {
    }

    public function onClientReceive(\Swoole\Coroutine\Client $cli, string $data)
    {
    }

    public function onClientClose(\Swoole\Coroutine\Client $cli)
    {
    }

    public function onClientError(\Swoole\Coroutine\Client $cli)
    {
    }
}
