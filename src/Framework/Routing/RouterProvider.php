<?php


namespace WebRover\Framework\Routing;


use Symfony\Component\Config\Loader\LoaderResolver;
use WebRover\Framework\Kernel\Application;
use WebRover\Framework\Kernel\Container\ServiceProvider;
use WebRover\Framework\Routing\Loader\PhpFileLoader;
use WebRover\Framework\Routing\Loader\RestfulLoader;
use WebRover\Framework\Routing\Locator\FileLocator;

/**
 * Class RouterProvider
 * @package WebRover\Framework\Routing
 */
class RouterProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton('router', function (Application $app) {

            $modules = $app->getModules();

            $configPath = $app->getConfigPath();

            $locator = new FileLocator($modules, $configPath);

            $yamlLoader = new PhpFileLoader($locator);

            $restfulLoader = new RestfulLoader();

            $loaderResolver = new LoaderResolver();

            $loaderResolver->addLoader($restfulLoader);

            $yamlLoader->setResolver($loaderResolver);

            $options = [
                'debug' => $app->isDebug()
            ];

            $context = new RequestContext('/');

            $router = new Router($yamlLoader, 'routing.php', $options, $context);

            return $router;
        });
    }
}