<?php declare(strict_types=1);

namespace Proxy\MysqlPacket;

use function Proxy\Helper\getBytes;
use function Proxy\Helper\getMysqlPackSize;
use function Proxy\Helper\getString;

class ErrorPacket extends MySQLPacket
{
    public static $FIELD_COUNT = 255;
    public $marker = '#';
    public $sqlState = 'HY000';
    public $errno = ErrorCode::ER_NO_SUCH_USER;
    public $message;

    public function read(BinaryPacket $bin)
    {
        $this->packetLength = $bin->packetLength;
        $this->packetId = $bin->packetId;
        $mm = new MySQLMessage($bin->data);
        $mm->move(4);
        $this->fieldCount = $mm->read();
        $this->errno = $mm->readUB2();
        $_chr = $mm->read($mm->position()) == $this->marker ? 1 : 0;
        if ($mm->hasRemaining() && chr($_chr)) {
            $mm->read();
            $this->sqlState = getString($mm->readBytes(5));
        }
        $this->message = getString($mm->readBytes());

        return $this;
    }

    public function write()
    {
        $data = [];
        $size = $this->calcPacketSize();
        $data = array_merge($data, $size);
        $data[] = $this->packetId;
        $data[] = self::$FIELD_COUNT;
        BufferUtil::writeUB2($data, $this->errno);
        $data[] = ord($this->marker);
        $data = array_merge($data, getBytes($this->sqlState));
        if (null != $this->message) {
            $data = array_merge($data, getBytes($this->message));
        }

        return $data;
    }

    public function calcPacketSize()
    {
        $size = 9;
        if (null != $this->message) {
            $sizeData = getMysqlPackSize($size + strlen($this->message));
        } else {
            $sizeData[] = $size;
            $sizeData[] = 0;
            $sizeData[] = 0;
        }

        return $sizeData;
    }

    protected function getPacketInfo()
    {
        return 'MySQL Error Packet';
    }
}
