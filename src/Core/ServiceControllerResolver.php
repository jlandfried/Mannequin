<?php

/*
 * This file is part of Mannequin.
 *
 * (c) 2017 Last Call Media, Rob Bayliss <rob@lastcallmedia.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace LastCall\Mannequin\Core;


use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ControllerResolverInterface;

/**
 * Enables name_of_service:method_name syntax for declaring controllers.
 *
 * @see https://silex.symfony.com/doc/providers/service_controller.html
 */
class ServiceControllerResolver implements ControllerResolverInterface
{
    protected $controllerResolver;
    protected $callbackResolver;

    /**
     * Constructor.
     *
     * @param ControllerResolverInterface $controllerResolver A ControllerResolverInterface instance to delegate to
     * @param CallbackResolver            $callbackResolver   A service resolver instance
     */
    public function __construct(ControllerResolverInterface $controllerResolver, CallbackResolver $callbackResolver)
    {
        $this->controllerResolver = $controllerResolver;
        $this->callbackResolver = $callbackResolver;
    }

    /**
     * {@inheritdoc}
     */
    public function getController(Request $request)
    {
        $controller = $request->attributes->get('_controller', null);

        if (!$this->callbackResolver->isValid($controller)) {
            return $this->controllerResolver->getController($request);
        }

        return $this->callbackResolver->convertCallback($controller);
    }

    /**
     * {@inheritdoc}
     */
    public function getArguments(Request $request, $controller)
    {
        return $this->controllerResolver->getArguments($request, $controller);
    }
}
