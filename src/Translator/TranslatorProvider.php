<?php


namespace WebRover\Framework\Translator;


use WebRover\Framework\Container\ServiceProvider;
use WebRover\Framework\Kernel\Application;

/**
 * Class TranslatorProvider
 * @package WebRover\Framework\Translator
 */
class TranslatorProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton('translator', function (Application $app) {

            $config = $app->make('config');

            $params = $config->get('service.translator', []);

            $bundles = $app->getBundles();

            $debug = $app->isDebug();

            $translator = new Translator($params, $bundles, $debug);

            return $translator->getTranslator();
        });
    }
}