<?php

namespace PhpBrew\Extension;

use CLIFramework\Logger;
use GetOptionKit\OptionResult;
use PhpBrew\Config;
use PhpBrew\Tasks\MakeTask;
use PhpBrew\Utils;

class ExtensionInstaller
{
    public $logger;

    public $options;

    public function __construct(Logger $logger, OptionResult $options = null)
    {
        $this->logger = $logger;
        $this->options = $options ?: new OptionResult();
    }

    public function install(Extension $ext, array $configureOptions = array())
    {
        $sourceDir = $ext->getSourceDirectory();
        $pwd = getcwd();
        $buildLogPath = $sourceDir . DIRECTORY_SEPARATOR . 'build.log';
        $make = new MakeTask($this->logger, $this->options);

        $make->setBuildLogPath($buildLogPath);

        $this->logger->info("Log stored at: $buildLogPath");

        $this->logger->info("Changing directory to $sourceDir");
        chdir($sourceDir);

        if (!$this->options->{'no-clean'} && $ext->isBuildable()) {
            $clean = new MakeTask($this->logger, $this->options);
            $clean->setQuiet();
            $clean->clean($ext);
        }

        if ($ext->getConfigM4File() !== 'config.m4' && !file_exists($sourceDir . DIRECTORY_SEPARATOR . 'config.m4')) {
            symlink($ext->getConfigM4File(), $sourceDir . DIRECTORY_SEPARATOR . 'config.m4');
        }

        // If the php version is specified, we should get phpize with the correct version.
        $this->logger->info('===> Phpize...');
        Utils::system("phpize > $buildLogPath 2>&1", $this->logger);

        $this->logger->info('===> Configuring...');

        $phpConfig = Config::getCurrentPhpConfigBin();
        if (file_exists($phpConfig)) {
            $this->logger->debug("Appending argument: --with-php-config=$phpConfig");
            $configureOptions[] = '--with-php-config=' . $phpConfig;
        }

        $cmd = './configure ' . implode(' ', array_map('escapeshellarg', $configureOptions));

        if (!$this->logger->isDebug()) {
            $cmd .= ' >> ' . escapeshellarg($buildLogPath) . ' 2>&1';
        }

        Utils::system($cmd, $this->logger);

        $this->logger->info('===> Building...');

        if ($this->logger->isDebug()) {
            passthru('make');
        } else {
            $make->run($ext);
        }

        $this->logger->info('===> Installing...');

        // This function is disabled when PHP is running in safe mode.
        if ($this->logger->isDebug()) {
            passthru('make install');
        } else {
            $make->install($ext);
        }

        // TODO: use getSharedLibraryPath()
        $this->logger->debug('Installed extension library: ' . $ext->getSharedLibraryPath());

        // Try to find the installed path by pattern
        // Installing shared extensions: /Users/c9s/.phpbrew/php/php-5.4.10/lib/php/extensions/debug-non-zts-20100525/
        chdir($pwd);
        $this->logger->info('===> Extension is installed.');
    }
}
