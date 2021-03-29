<?php

namespace LastCall\Mannequin\Core\Discovery;

use LastCall\Mannequin\Html\HtmlExtension;
use Symfony\Component\Finder\Finder;

class ChainDiscoveryConfigurator
{
    protected $extensions = [];

    public function __construct($mannequinExtensionConfig)
    {
//        var_dump($mannequinExtensionConfig);
        foreach ($mannequinExtensionConfig as $clientName => $extensionConfig) {
            switch ($extensionConfig['extension']) {
                case 'html':
                    $finder = Finder::create()
                        ->files()
                        ->in($extensionConfig['directories'])
                        ->name($extensionConfig['pattern']);

                    $extension = new HtmlExtension(['files' => $finder, 'root' => $extensionConfig['root']]);

                    $this->extensions[] = $extension;
                    break;
            }
        }
    }

    public function configure(ChainDiscovery $chainDiscovery)
    {
        $discoverers = [];
        foreach ($this->getExtensions() as $extension) {
            $discoverers = array_merge($discoverers, $extension->getDiscoverers());
        }

        $chainDiscovery->setDiscoverers($discoverers);
    }

    protected function getExtensions()
    {
        return $this->extensions;
    }
}
