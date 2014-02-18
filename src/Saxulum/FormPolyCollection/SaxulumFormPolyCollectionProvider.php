<?php

namespace Saxulum\FormPolyCollection;

use Saxulum\BundleProvider\Provider\AbstractBundleProvider;
use Saxulum\FormPolyCollection\Form\FormPolyCollectionExtension;
use Silex\Application;

class SaxulumFormPolyCollectionProvider extends AbstractBundleProvider
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
