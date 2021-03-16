<?php declare(strict_types=1);

namespace Proxy;

use Proxy\Helper\ProcessHelper;
use function Proxy\Helper\packageLengthSetting;
use function Proxy\Helper\proxy_error;
use Proxy\Log\Log;
use Swoole\Coroutine;

abstract class BaseServer extends Base
{
    protected array $connectReadState = [];
    protected array $connectHasTransaction = [];
    protected array $connectHasAutoCommit = [];
    protected \swoole_server $server;

    /**
     * BaseServer constructor.
     *
     */
    public function __construct()
    {
        try {
            if (!(CONFIG['server']['swoole'] ?? false)) {
                $system_log = Log::getLogger('system');
                $system_log->error('config [swoole] is not found !');
                throw new ProxyException('config [swoole] is not found !');
            }
            # 二次校验配置
            if ((CONFIG['server']['port'] ?? false)) {
                $ports = explode(',', CONFIG['server']['port']);
            } else {
                $ports = [19016];
            }

            $this->server = new \swoole_server(
                CONFIG['server']['host'],
                (int)$ports[0],
                CONFIG['server']['mode'],
                CONFIG['server']['sock_type']
            );
            # 多端口监听
            if (count($ports) > 1) {
                for ($i = 1; $i < count($ports); ++$i) {
                    $this->server->addListener(
                        CONFIG['server']['host'],
                        $ports[$i],
                        CONFIG['server']['sock_type']
                    );
                }
            }
            # 注册监听回调函数
            $this->server->set(CONFIG['server']['swoole']);
            $this->server->on('connect', [$this, 'onConnect']);
            $this->server->on('receive', [$this, 'onReceive']);
            $this->server->on('close', [$this, 'onClose']);
            $this->server->on('start', [$this, 'onStart']);
            $this->server->on('WorkerStart', [$this, 'onWorkerStart']);
            $this->server->on('ManagerStart', [$this, 'onManagerStart']);
            $this->server->set(packageLengthSetting());
            $result = $this->server->start();
            if ($result) {
                proxy_error('WARNING: Server is shutdown!');
            } else {
                proxy_error('ERROR: Server start failed!');
            }
        } catch (\Swoole\Exception $exception) {
            proxy_error('ERROR:' . $exception->getMessage());
        } catch (\ErrorException $exception) {
            proxy_error('ERROR:' . $exception->getMessage());
        } catch (ProxyException $exception) {
            proxy_error('ERROR:' . $exception->errorMessage());
        }
    }

    protected function onConnect(\swoole_server $server, int $fd)
    {
    }

    protected function onReceive(\swoole_server $server, int $fd, int $reactor_id, string $data)
    {
    }

    protected function onWorkerStart(\swoole_server $server, int $worker_id)
    {
    }

    public function onStart(\swoole_server $server)
    {
        \file_put_contents(CONFIG['server']['swoole']['pid_file'], $server->master_pid . ',' . $server->manager_pid);
        ProcessHelper::setProcessTitle('Proxy master  process');
    }

    public function onManagerStart(\swoole_server $server)
    {
        ProcessHelper::setProcessTitle('Proxy manager process');
    }

    /**
     * 关闭连接 销毁协程变量.
     *
     * @param $server
     * @param $fd
     */
    protected function onClose(\swoole_server $server, int $fd)
    {
        $cid = Coroutine::getuid();
        if ($cid > 0 && isset(self::$pool[$cid])) {
            unset(self::$pool[$cid]);
        }
    }
}
