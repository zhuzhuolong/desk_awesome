<?php declare(strict_types=1);

namespace Proxy\Handler\Frontend;

interface FrontendQueryHandler
{
    public function query(string $sql);
}
