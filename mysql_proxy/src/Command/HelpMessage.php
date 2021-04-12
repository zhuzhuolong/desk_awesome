<?php


namespace Proxy\Command;


class HelpMessage
{
    public static string $logo = <<<'LOGO'

 /$$$$$$$                                        
| $$__  $$                                       
| $$  \ $$ /$$$$$$   /$$$$$$  /$$   /$$ /$$   /$$
| $$$$$$$//$$__  $$ /$$__  $$|  $$ /$$/| $$  | $$
| $$____/| $$  \__/| $$  \ $$ \  $$$$/ | $$  | $$
| $$     | $$      | $$  | $$  >$$  $$ | $$  | $$
| $$     | $$      |  $$$$$$/ /$$/\  $$|  $$$$$$$
|__/     |__/       \______/ |__/  \__/ \____  $$
                                        /$$  | $$
                                       |  $$$$$$/
                                        \______/ 

LOGO;


    public static $version = 'Proxy version: ' . SMPROXY_VERSION . PHP_EOL;

    /**
     * @var string
     */
    public static $usage      = <<<'USAGE'
Usage:
  Proxy [ start | stop | restart | status | reload ] [ -c | --config <configuration_path> | --console ]
  Proxy -h | --help
  Proxy -v | --version

USAGE;

    /**
     * @var string
     */
    public static string $desc       = <<<'DESC'
Options:
  start                            Start server
  stop                             Shutdown server
  restart                          Restart server
  status                           Show server status
  reload                           Reload configuration
  -h --help                        Display help
  -v --version                     Display version
  -e --env    <configuration_env> Specify configuration path
  -c --config <configuration_path> Specify configuration path
  --console                        Front desk operation

DESC;

    public static string $status     = <<<'STATUS'
Proxy[${version}] - ${uname}
Host: ${host}, Port: ${port}, PHPVerison: ${php_version}
SwooleVersion: ${swoole_version}, WorkerNum: ${worker_num}
STATUS;
}