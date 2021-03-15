<?php

namespace Proxy;

use JetBrains\PhpStorm\Pure;

class ProxyException extends \Exception
{
    #[Pure]
    public function errorMessage(): string
    {
        return sprintf('%s (%s:%s)', trim($this->getMessage()), $this->getFile(), $this->getLine());
    }
}
