<?php declare(strict_types=1);

namespace Proxy\Handler\Frontend;

use function Proxy\Helper\getString;
use Proxy\MysqlPacket\HandshakePacket;
use Proxy\MysqlPacket\Capabilities;
use Proxy\MysqlPacket\CharsetUtil;
use Proxy\MysqlPacket\RandomUtil;
use Proxy\MysqlPacket\SecurityUtil;
use Proxy\MysqlPacket\Versions;

class FrontendAuthenticator
{
    public $seed = [];
    public $auth = false;
    public mixed $database;
    public mixed $user;

    public function getHandshakePacket(int $server_id): string
    {
        $rand1 = RandomUtil::randomBytes(8);
        $rand2 = RandomUtil::randomBytes(12);
        $this->seed = array_merge($rand1, $rand2);
        $hs = new HandshakePacket();
        $hs->packetId = 0;
        $hs->protocolVersion = Versions::PROTOCOL_VERSION;
        $hs->serverVersion   = Versions::SERVER_VERSION;
        $hs->threadId = $server_id;
        $hs->seed = $rand1;
        $hs->serverCapabilities = $this->getServerCapabilities();
        $hs->serverCharsetIndex = (CharsetUtil::getIndex(CONFIG['server']['charset'] ?? 'utf8mb4') & 0xff);
        $hs->serverStatus = 2;
        $hs->restOfScrambleBuff = $rand2;

        return getString($hs->write());
    }

    public function checkPassword(array $password, string $pass): bool
    {
        // check null
        if (null == $pass || 0 == strlen($pass)) {
            if (null == $password || 0 == count($password)) {
                return true;
            } else {
                return false;
            }
        }
        if (null == $password || 0 == count($password)) {
            return false;
        }

        // encrypt
        $encryptPass = null;
        try {
            $encryptPass = SecurityUtil::scramble411($pass, $this->seed);
        } catch (\Exception $e) {
            return false;
        }
        if (null != $encryptPass && (count($encryptPass) == count($password))) {
            $i = count($encryptPass);
            while (0 != $i--) {
                if ($encryptPass[$i] != $password[$i]) {
                    return false;
                }
            }
        } else {
            return false;
        }

        return true;
    }

    protected function getServerCapabilities(): int
    {
        $flag = 0;
        $flag |= Capabilities::CLIENT_LONG_PASSWORD;
        $flag |= Capabilities::CLIENT_FOUND_ROWS;
        $flag |= Capabilities::CLIENT_LONG_FLAG;
        $flag |= Capabilities::CLIENT_CONNECT_WITH_DB;
        // flag |= Capabilities::CLIENT_NO_SCHEMA;
        // flag |= Capabilities::CLIENT_COMPRESS;
        $flag |= Capabilities::CLIENT_ODBC;
        // flag |= Capabilities::CLIENT_LOCAL_FILES;
        $flag |= Capabilities::CLIENT_IGNORE_SPACE;
        $flag |= Capabilities::CLIENT_PROTOCOL_41;
        $flag |= Capabilities::CLIENT_INTERACTIVE;
        // flag |= Capabilities::CLIENT_SSL;
        $flag |= Capabilities::CLIENT_IGNORE_SIGPIPE;
        $flag |= Capabilities::CLIENT_TRANSACTIONS;
        // flag |= ServerDefs.CLIENT_RESERVED;
        $flag |= Capabilities::CLIENT_SECURE_CONNECTION;
//        $flag |= Capabilities::CLIENT_PLUGIN_AUTH;

        return $flag;
    }

    protected function failure(int $errno, string $info)
    {
    }
}
