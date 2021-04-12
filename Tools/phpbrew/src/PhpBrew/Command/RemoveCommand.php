<?php

namespace PhpBrew\Command;

/*
 * @codeCoverageIgnore
 */
use CLIFramework\Command;
use CLIFramework\Prompter;
use Exception;
use PhpBrew\BuildFinder;
use PhpBrew\Config;
use PhpBrew\Utils;

class RemoveCommand extends Command
{
    public function brief()
    {
        return 'Remove installed php build.';
    }

    public function arguments($args)
    {
        $args->add('installed php')
            ->validValues(function () {
                return BuildFinder::findInstalledBuilds();
            })
            ;
    }

    public function execute($buildName)
    {
        $prefix = Config::getVersionInstallPrefix($buildName);
        if (!file_exists($prefix)) {
            throw new Exception("$prefix does not exist.");
        }
        $prompter = new Prompter();
        $answer = $prompter->ask("Are you sure to delete $buildName?", array('Y', 'n'), 'Y');
        if (strtolower($answer) == 'y') {
            Utils::recursive_unlink($prefix, $this->logger);
            $this->logger->info("$buildName is removed.  I hope you're not surprised. :)");
        } else {
            $this->logger->info('Let me guess, you drunk tonight.');
        }
    }
}
