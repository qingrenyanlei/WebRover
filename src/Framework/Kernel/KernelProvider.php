<?php


namespace WebRover\Framework\Kernel;


use WebRover\Framework\Kernel\Container\ServiceProvider;
use WebRover\Framework\Kernel\Controller\ArgumentResolver;
use WebRover\Framework\Kernel\Controller\ControllerResolver;
use WebRover\Framework\Kernel\EventListener\RouterListener;
use Whoops\Handler\JsonResponseHandler;
use Whoops\Handler\PlainTextHandler;
use Whoops\Handler\PrettyPageHandler;
use Whoops\Run;
use Whoops\Util\Misc;

/**
 * Class KernelProvider
 * @package WebRover\Framework\Kernel
 */
class KernelProvider extends ServiceProvider
{
    public function register()
    {
        /**
         * 注册异常处理服务
         */
        $this->app->singleton('whoops', function (Application $app) {
            $whoops = new Run();

            if (Misc::isAjaxRequest()) {
                $handler = new JsonResponseHandler();
                $handler->addTraceToOutput(true);
            } else {
                if (Misc::isCommandLine()) {
                    $handler = new PlainTextHandler();
                } else {
                    $handler = new PrettyPageHandler();
                }
            }

            $whoops->prependHandler($handler);

            return $whoops;
        });

        /**
         * 注册路由监听者
         */
        $this->app->singleton(RouterListener::class, function (Application $app) {
            $router = $app->make('router');

            $requestStack = $app->make('request_stack');

            $logger = $app->offsetExists('logger') ? $app->make('logger') : null;

            if (null !== $logger) {
                $logger = $logger->withName('request');
            }

            $modules = $app->getModules();

            $debug = $app->isDebug();

            return new RouterListener($modules, $router->getMatcher(), $requestStack, null, $logger, $debug);
        });

        /**
         * 注册控制器解析者服务
         */
        $this->app->singleton('controller_resolver', function (Application $app) {
            $logger = $app->offsetExists('logger') ? $app->make('logger') : null;

            return new ControllerResolver($logger);
        });

        /**
         * 注册参数解析者服务
         */
        $this->app->singleton('argument_resolver', function () {
            return new ArgumentResolver();
        });

        /**
         * 注册HTTP核心服务
         */
        $this->app->singleton('http_kernel', function (Application $app) {
            return new HttpKernel(
                $app->make('event'),
                $app->make('controller_resolver'),
                $app->make('argument_resolver'),
                $app->make('request_stack')
            );
        });
    }
}