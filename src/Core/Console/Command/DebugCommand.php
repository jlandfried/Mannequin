<?php

/*
 * This file is part of Mannequin.
 *
 * (c) 2017 Last Call Media, Rob Bayliss <rob@lastcallmedia.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace LastCall\Mannequin\Core\Console\Command;

use LastCall\Mannequin\Core\Discovery\DiscoveryInterface;
use LastCall\Mannequin\Core\Ui\ManifestBuilder;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Yaml\Yaml;

class DebugCommand extends Command
{
    protected static $defaultName = 'mannequin:debug';

    private $builder;
    private $discovery;

    public function __construct(ManifestBuilder $builder, DiscoveryInterface $chainDiscovery)
    {
        parent::__construct();
        $this->builder = $builder;
        $this->discovery = $chainDiscovery;
    }

    public function configure()
    {
        $this->setDescription('Display information on components');
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);

        $io->block('Components');
        $manifest = $this->builder->generate($this->discovery->discover());

        $yaml = Yaml::dump($manifest['components'], 5);
        try {
            $output->write($yaml);
        } catch (\Exception $e) {
            return Command::FAILURE;
        }

        return Command::SUCCESS;
    }
}
