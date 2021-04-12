<?php
namespace Proxy\Helper;

use \Swoole\Coroutine;

/**
 * php帮助类
 */
class PhpHelper
{
    /**
     * is Cli
     *
     * @return  boolean
     */
    public static function isCli(): bool
    {
        return PHP_SAPI === 'cli';
    }

    /**
     * 是否是mac环境
     *
     * @return bool
     */
    public static function isMac(): bool
    {
        return php_uname('s') === 'Darwin';
    }

    /**
     * 调用
     *
     * @param mixed $cb callback函数，多种格式
     * @param array $args 参数
     *
     * @return mixed
     */
    public static function call(mixed $cb, array $args = []): mixed
    {
        if (version_compare(SWOOLE_VERSION, '4.0', '>=')) {
            $ret = call_user_func_array($cb, $args);
        } else {
            $ret = Coroutine::call_user_func_array($cb, $args);
        }

        return $ret;
    }
}
