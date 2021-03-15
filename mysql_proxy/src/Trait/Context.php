<?php

namespace Proxy\Trait;

use Swoole\Coroutine;

trait Context
{
    protected static $pool = [];

    public static function get(string $key)
    {
        $cid = Coroutine::getuid();
        if ($cid < 0) {
            return null;
        }
        if (isset(self::$pool[$cid][$key])) {
            return self::$pool[$cid][$key];
        }

        return null;
    }

    public static function put(string $key, $item)
    {
        $cid = Coroutine::getuid();
        if ($cid > 0) {
            self::$pool[$cid][$key] = $item;
        }
    }

    public static function delete(string $key = null)
    {
        $cid = Coroutine::getuid();
        if ($cid > 0) {
            if ($key) {
                unset(self::$pool[$cid][$key]);
            } else {
                unset(self::$pool[$cid]);
            }
        }
    }
}
