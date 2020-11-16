<?php

namespace PhpBrew\Tasks;

use Exception;
use PhpBrew\ConfigureParameters;
use PhpBrew\Utils;

class Apxs2CheckTask extends BaseTask
{
    public function check(ConfigureParameters $parameters)
    {
        $options = $parameters->getOptions();

        if (isset($options['--with-apxs2'])) {
            $apxs = $options['--with-apxs2'];
        } else {
            // trying to find apxs binary in case it wasn't explicitly specified (+apxs variant without path)
            $apxs = Utils::findbin('apxs');
            $this->logger->debug("Found apxs2 binary: $apxs");
        }

        if (!is_executable($apxs)) {
            throw new Exception("apxs binary is not executable: $apxs");
        }

        // use apxs to check module dir permission
        if ($libdir = exec("$apxs -q LIBEXECDIR")) {
            if (false === is_writable($libdir)) {
                $this->logger->error(
                    <<<EOF
Apache module dir $libdir is not writable.
Please consider using chmod to change the folder permission:
    \$ sudo chmod -R oga+rw $libdir
Warnings: the command above is not safe for public systems. Please use with discretion.
EOF
                );

                throw new Exception();
            }
        }

        if ($confdir = exec("$apxs -q SYSCONFDIR")) {
            if (false === is_writable($confdir)) {
                $this->logger->error(
                    <<<EOF
Apache conf dir $confdir is not writable for phpbrew.
Please consider using chmod to change the folder permission:
    \$ sudo chmod -R oga+rw $confdir
Warnings: the command above is not safe for public systems. Please use with discretion.
EOF
                );

                throw new Exception();
            }
        }
    }
}
