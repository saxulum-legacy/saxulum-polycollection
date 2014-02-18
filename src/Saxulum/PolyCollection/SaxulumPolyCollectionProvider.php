<?php

namespace Saxulum\PolyCollection;

use Saxulum\BundleProvider\Provider\AbstractBundleProvider;
use Saxulum\PolyCollection\Form\FormPolyCollectionExtension;
use Silex\Application;

class SaxulumPolyCollectionProvider extends AbstractBundleProvider
{
    public function register(Application $app)
    {
        $this->addTwigLoaderFilesystemPath($app);

        $app['form.extensions'] = $app->share(
            $app->extend('form.extensions', function ($extensions) {
                $extensions[] = new FormPolyCollectionExtension();

                return $extensions;
            })
        );
    }

    public function boot(Application $app) {}
}
