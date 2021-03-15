<?php declare(strict_types=1);
namespace Proxy\Command;

use Proxy\ProxyException;
use function Proxy\Helper\initConfig;
use Proxy\Helper\PhpHelper;
use function Proxy\Helper\proxy_error;

class Command
{
    /**
     * 运行
     *
     * @param array $argv
     * @throws ProxyException
     */
    public function run(array $argv)
    {
        $command = count($argv) >= 2 ? $argv[1] : false;
        $this ->settingConfig($argv);
        $this ->commandHandler($command, $argv);
    }

    /**
     * 设置配置文件
     *
     * @param array $argv
     *
     * @throws ProxyException
     */
    protected function settingConfig(array $argv)
    {
        //指定配置文件
        $configPath = ROOT . '/conf/';
        $configKey  = array_search('-c', $argv) ?: array_search('--config', $argv);
        if ($configKey) {
            if (!isset($argv[$configKey + 1])) {
                proxy_error(HelpMessage::$version . PHP_EOL . HelpMessage::$usage);
            }
            $configPath = $argv[$configKey + 1];
        }


        //前台运行
        $consoleKey  = array_search('--console', $argv);
        if ($consoleKey) {
            define('CONSOLE', true);
        } else {
            define('CONSOLE', false);
        }

        if (file_exists($configPath)) {
            define('CONFIG_PATH', realpath($configPath) . '/');
            define('CONFIG', initConfig(CONFIG_PATH));
        } else {
            proxy_error('ERROR: ' . $configPath . ' No such file or directory!');
        }
    }

    /**
     * 处理命令
     *
     * @param string $command
     * @param array $argv
     */
    protected function commandHandler(mixed $command, array $argv)
    {
        $serverCommand = new ServerCommand();
        $serverCommand->argv = $argv;

        if ('-h' == $command || '--help' == $command) {
            echo $serverCommand->desc, PHP_EOL;
            return;
        }

        if ('-v' == $command || '--version' == $command) {
            echo $serverCommand->logo, PHP_EOL;
            return;
        }

        if (!$command || !method_exists($serverCommand, $command)) {
            echo $serverCommand->usage, PHP_EOL;
            return;
        }

        PhpHelper::call([$serverCommand, $command]);
    }
}
