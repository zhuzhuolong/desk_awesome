<?php

namespace Proxy\MysqlPacket;

class BinaryPacket extends MySQLPacket
{
    public static int $OK = 1;
    public static int $ERROR = 2;
    public static int $HEADER = 3;
    public static int $FIELD = 4;
    public static int $FIELD_EOF = 5;
    public static int $ROW = 6;
    public static int $PACKET_EOF = 7;
    public $data;

    /**
     * @return int
     */
    public function calcPacketSize(): int
    {
        return null == $this->data ? 0 : count($this->data);
    }

    /**
     * @return string
     */
    protected function getPacketInfo(): string
    {
        return 'MySQL Binary Packet';
    }
}
