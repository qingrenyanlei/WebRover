<?php


namespace WebRover\Framework\Translator;


use WebRover\Framework\Kernel\Application;
use WebRover\Framework\Kernel\Container\ServiceProvider;

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

            $modules = $app->getModules();

            $debug = $app->isDebug();

            $translator = new Translator($params, $modules, $debug);

            return $translator->getTranslator();
        });
    }
}