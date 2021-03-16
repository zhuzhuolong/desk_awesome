<?php declare(strict_types=1);

namespace Proxy\Handler\Frontend;

use Proxy\Parser\ServerParse;

class ServerQueryHandler implements FrontendQueryHandler
{
    public function query(string $sql)
    {
        $rs = ServerParse::parse($sql);

        return $rs & 0xff;
    }
}
