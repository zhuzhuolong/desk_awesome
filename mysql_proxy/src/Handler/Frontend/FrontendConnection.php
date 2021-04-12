<?php declare(strict_types=1);

namespace Proxy\Handler\Frontend;

use Proxy\MysqlPacket\BinaryPacket;
use Proxy\MysqlPacket\MySQLMessage;
use Proxy\MysqlPool\MySQLException;


class FrontendConnection
{
    protected $queryHandler;

    public function __construct()
    {
        $this->setQueryHandler(new ServerQueryHandler());
    }

    public function setQueryHandler(FrontendQueryHandler $queryHandler)
    {
        $this->queryHandler = $queryHandler;
    }

    /**
     * @param BinaryPacket $bin
     *
     * @return mixed
     * @throws MySQLException
     */
    public function query(BinaryPacket $bin)
    {
        // 取得语句
        $mm = new MySQLMessage($bin->data);
        $mm->position(5);
        $sql = $mm->readString();
        if (null == $sql || 0 == strlen($sql)) {
            throw new MySQLException('Empty SQL');
        }
        // 执行查询
        return $this->queryHandler->query($sql);
    }
}
