<?php

/*
 * This file is part of Mannequin.
 *
 * (c) 2017 Last Call Media, Rob Bayliss <rob@lastcallmedia.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace LastCall\Mannequin\Core\Extension;

use LastCall\Mannequin\Core\Rendered;
use LastCall\Mannequin\Core\Subscriber\GlobalAssetSubscriber;
use LastCall\Mannequin\Core\Subscriber\LastChanceNameSubscriber;
use LastCall\Mannequin\Core\Subscriber\VariableResolverSubscriber;
use LastCall\Mannequin\Core\Subscriber\YamlFileMetadataSubscriber;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\ExpressionLanguage\ExpressionFunction;
use Symfony\Component\ExpressionLanguage\ExpressionFunctionProviderInterface;

class CoreExtension extends AbstractExtension implements ExpressionFunctionProviderInterface
{
    public function getFunctions()
    {
        return [
            $this->getComponentExpressionFunction(),
            $this->getMarkupExpressionFunction(),
            $this->getAssetExpressionFunction(),
        ];
    }

    public function subscribe(EventDispatcherInterface $dispatcher)
    {
        $dispatcher->addSubscriber(new YamlFileMetadataSubscriber($this->mannequin->getMetadataParser()));
        $dispatcher->addSubscriber(new LastChanceNameSubscriber());
        $dispatcher->addSubscriber(new GlobalAssetSubscriber(
            $this->mannequin->getAssetPackage(),
            $this->mannequin->getConfig()->getGlobalCss(),
            $this->mannequin->getConfig()->getGlobalJs()
        ));
        $dispatcher->addSubscriber(new VariableResolverSubscriber($this->mannequin->getVariableResolver()));
    }

    private function getComponentExpressionFunction()
    {
        return new ExpressionFunction('component', function ($arguments, $pid) {
            throw new \ErrorException('Component expressions cannot yet be compiled.');
        }, function ($context, $pid) {
            /** @var \LastCall\Mannequin\Core\Component\ComponentCollection $collection */
            $collection = $context['collection'];
            $component = $collection->get($pid);
            $sample = reset($component->getSamples());
            $renderer = $this->mannequin->getRenderer();

            return $renderer->render($collection, $component, $sample);
        });
    }

    private function getMarkupExpressionFunction()
    {
        return new ExpressionFunction('markup', function () {
            throw new \ErrorException('Markup expressions cannot be compiled.');
        }, function ($args, $markup) {
            $rendered = new Rendered();
            $rendered->setMarkup($markup);

            return $rendered;
        });
    }

    private function getAssetExpressionFunction()
    {
        return new ExpressionFunction('asset', function () {
            throw new \ErrorException('Asset expressions cannot be compiled.');
        }, function ($context, $path) {
            $package = $this->mannequin->getAssetPackage();

            return $package->getUrl($path);
        });
    }
}
