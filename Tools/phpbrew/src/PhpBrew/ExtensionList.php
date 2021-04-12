<?php

namespace PhpBrew;

use CLIFramework\Logger;
use GetOptionKit\OptionResult;
use PhpBrew\Extension\Provider\Provider;

class ExtensionList
{
    private $logger;
    private $options;

    public function __construct(Logger $logger, OptionResult $options)
    {
        $this->logger = $logger;
        $this->options = $options;
    }

    /**
     * Returns available extension providers
     *
     * @return Provider[]
     */
    public function getProviders()
    {
        static $providers;
        if ($providers) {
            return $providers;
        }
        $providers = array(
            new Extension\Provider\GithubProvider(),
            new Extension\Provider\BitbucketProvider(),
            new Extension\Provider\PeclProvider($this->logger, $this->options),
        );

        return $providers;
    }

    /**
     * Returns provider for the given extension
     *
     * @param string $extensionName
     * @return Provider|null
     */
    public function exists($extensionName)
    {
        // determine which provider support this extension
        $providers = $this->getProviders();
        foreach ($providers as $provider) {
            if ($provider->exists($extensionName)) {
                return $provider;
            }
        }

        return null;
    }
}
