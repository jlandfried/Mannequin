---
title: Configuration
description: Configuration for the Mannequin Drupal extension.
---

The `DrupalExtension` object accepts the following configuration options:

| Key | Description |
| --- | ----------- |
| finder | A [Symfony Finder](https://symfony.com/doc/current/components/finder.html) object that will search for the Twig template files you want to use as components. |
| drupal_root | An absolute path to the base directory containing your Drupal installation.  This will be used to create a Twig filesystem loader internally. |
| twig_options | An associative array of [options to pass to Twig](https://twig.symfony.com/api/2.x/Twig_Environment.html#method___construct).  Defaults to using a `cache` property of the Mannequin cache directory. |

It also has the following methods to be used for configuration:
* `addTwigPath(string $namespace, string $path)` Adds an additional path to the Twig loader, under a specific namespace.  Use this method to add additional namespaces to the loader.  If you want to use components inside of the added namespace, make sure to add the paths to your `Finder` as well.
* `setFallbackExtensions(array $extensions =['stable'])` Sets the lookup paths for any Twig include/extend statements that don't use a namespace.  While you should always use a namespace in your Twig extensions to make inheritance explicit, Drupal allows for "theme registry" inheritance (eg: `block.html.twig` is resolved to the `block.html.twig` template in the parent theme).  Mannequin does not use the theme registry, so we provide an alternate Twig loader that searches for templates in parent themes, as specified by this function.  The default is to look up templates against the Stable theme.

Example
-------
```php
// .mannequin.php

$extension = new DrupalExtension([
  'finder' => Finder::create(),
  'drupal_root' => __DIR__,
  'twig_options' => [
    'debug' => true,
  ]
]);

// Add an additional namespace to the loader.
// Note: To use this namespace in Drupal, you would also need to register it there.
$extension->addTwigPath('atoms', __DIR__.'/themes/mytheme/atoms');

// Load unqualified templates used in include/extend statements
// (ex: {% extends 'block.html.twig' %})
// from the Classy theme instead of Stable.
$extension->setFallbackExtensions(['classy']);
```