<?php declare(strict_types=1);


namespace Proxy\MysqlPacket;

interface Versions
{
    /** 协议版本 */
    const PROTOCOL_VERSION = 10;

    /** 服务器版本 */
    const SERVER_VERSION = '5.6.0-Proxy';
}
