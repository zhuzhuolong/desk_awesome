<?php

namespace Proxy\MysqlPacket;

use phpDocumentor\Reflection\Types\Void_;
use function Proxy\Helper\getBytes;
use Proxy\MysqlPacket\BufferUtil;
use Proxy\MysqlPacket\Capabilities;

class HandshakePacket extends MySQLPacket
{
    private static array $FILLER_13 = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];

    public mixed $protocolVersion;
    public mixed $serverVersion;
    public mixed $threadId;
    public mixed $seed;
    public mixed $serverCapabilities;
    public mixed $serverCharsetIndex;
    public mixed $serverStatus;
    public mixed $restOfScrambleBuff;
    public string $pluginName = 'mysql_native_password';
    public mixed $authDataLength;

    /**
     * @param BinaryPacket $bin
     * @return $this
     */
    public function read(BinaryPacket $bin): static
    {
        $this->packetLength = $bin->packetLength;
        $this->packetId = $bin->packetId;
        $mm = new MySQLMessage($bin->data);
        $mm->length = $this->packetLength;
        $mm->move(4);
        $this->protocolVersion = $mm->read();
        $this->serverVersion = $mm->readStringWithNull();
        $this->threadId = $mm->readUB4();
        $this->seed = $mm->readBytesWithNull();
        $this->serverCapabilities = $mm->readUB2();
        $this->serverCharsetIndex = $mm->read();
        $this->serverStatus = $mm->readUB2();
        $this->serverCapabilities |= $mm->readUB2();
        $this->authDataLength = $mm->read();
        $mm->move(10);
        if ($this ->serverCapabilities & Capabilities::CLIENT_SECURE_CONNECTION) {
            $this->restOfScrambleBuff = $mm->readBytesWithNull();
        }
        $this->pluginName             = $mm->readStringWithNull() ?: $this->pluginName;
        return $this;
    }

    public function write(): array
    {
        // default init 256,so it can avoid buff extract
        $buffer = [];
        BufferUtil::writeUB3($buffer, $this->calcPacketSize());
        $buffer[] = $this->packetId;
        $buffer[] = $this->protocolVersion;
        BufferUtil::writeWithNull($buffer, getBytes($this->serverVersion));
        BufferUtil::writeUB4($buffer, $this->threadId);
        BufferUtil::writeWithNull($buffer, $this->seed);
        BufferUtil::writeUB2($buffer, $this->serverCapabilities);
        $buffer[] = $this->serverCharsetIndex;
        BufferUtil::writeUB2($buffer, $this->serverStatus);
        if ($this ->serverCapabilities & Capabilities::CLIENT_PLUGIN_AUTH) {
            BufferUtil::writeUB2($buffer, $this->serverCapabilities);
            $buffer[] = max(13, count($this->seed) + count($this->restOfScrambleBuff) + 1);
            $buffer = array_merge($buffer, [0, 0, 0, 0, 0, 0, 0, 0, 0, 0]);
        } else {
            $buffer = array_merge($buffer, self::$FILLER_13);
        }
        if ($this ->serverCapabilities & Capabilities::CLIENT_SECURE_CONNECTION) {
            BufferUtil::writeWithNull($buffer, $this->restOfScrambleBuff);
        }
        if ($this ->serverCapabilities & Capabilities::CLIENT_PLUGIN_AUTH) {
            BufferUtil::writeWithNull($buffer, getBytes($this->pluginName));
        }
        return $buffer;
    }

    public function calcPacketSize(): int
    {
        $size = 1;
        $size += strlen($this->serverVersion); // n
        $size += 5; // 1+4
        $size += count($this->seed); // 8
        $size += 19; // 1+2+1+2+13
        if ($this ->serverCapabilities & Capabilities::CLIENT_SECURE_CONNECTION) {
            $size += count($this->restOfScrambleBuff); // 12
            ++$size; // 1
        }
        if ($this ->serverCapabilities & Capabilities::CLIENT_PLUGIN_AUTH) {
            $size += strlen($this->pluginName);
            ++$size; // 1
        }
        return $size;
    }

    protected function getPacketInfo(): string
    {
        return 'MySQL Handshake Packet';
    }
}
