<?php


namespace WebRover\Framework\Routing;


use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\AnnotationRegistry;
use Symfony\Component\Config\Loader\LoaderResolver;
use Symfony\Component\Routing\Loader\AnnotationDirectoryLoader;
use Symfony\Component\Routing\RequestContext;
use WebRover\Framework\Container\ServiceProvider;
use WebRover\Framework\Kernel\Application;
use WebRover\Framework\Kernel\Controller\ControllerNameParser;
use WebRover\Framework\Routing\Loader\AnnotatedRouteControllerLoader;
use WebRover\Framework\Routing\Loader\DelegatingLoader;
use WebRover\Framework\Routing\Loader\PhpFileLoader;
use WebRover\Framework\Routing\Loader\RestfulLoader;

/**
 * Class RouterProvider
 * @package WebRover\Framework\Routing
 */
class RouterProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton('router', function (Application $app) {
            $parser = new ControllerNameParser($app);

            $loaderResolver = new LoaderResolver();

            $locator = $app->make('file_locator');

            $phpFileLoader = new PhpFileLoader($locator);

            $restfulLoader = new RestfulLoader();

            $annotationRegistry = new AnnotationRegistry();

            $annotationRegistry::registerUniqueLoader('class_exists');

            $annotationReader = new AnnotationReader();
            $annotationReader::addGlobalIgnoredName('required', $annotationRegistry);

            $annotatedRouteControllerLoader = new AnnotatedRouteControllerLoader($annotationReader);
            $annotationLoader = new AnnotationDirectoryLoader($locator, $annotatedRouteControllerLoader);

            $loaderResolver->addLoader($phpFileLoader);
            $loaderResolver->addLoader($restfulLoader);
            $loaderResolver->addLoader($annotationLoader);

            $loader = new DelegatingLoader($parser, $loaderResolver);

            $options = [
                'debug' => $app->isDebug()
            ];

            $context = new RequestContext('/');

            $router = new Router($app->make('config'), $loader, 'routing.php', $options, $context);

            return $router;
        });
    }
}