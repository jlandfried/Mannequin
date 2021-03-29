<?php

namespace LastCall\Mannequin\Core\Console;
use Symfony\Bundle\FrameworkBundle\Console\Application as ConsoleApplication;
use LastCall\Mannequin\Core\Console\Command\DebugCommand;
use LastCall\Mannequin\Core\Console\Command\StartCommand;
use LastCall\Mannequin\Core\Discovery\ChainDiscovery;
use LastCall\Mannequin\Core\Ui\ManifestBuilder;
use LastCall\Mannequin\Kernel;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Application extends ConsoleApplication {

    private $kernel;

    public function doRun(InputInterface $input, OutputInterface $output)
    {
        parent::doRun($input, $output);
    }

}
