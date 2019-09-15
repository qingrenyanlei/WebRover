<?php


namespace WebRover\Framework\View;


use Twig\Environment;
use Twig\Extension\ExtensionInterface;
use WebRover\Framework\Kernel\Application;
use WebRover\Framework\Kernel\Container\ServiceProvider;
use WebRover\Framework\View\Extension\AppExtension;
use WebRover\Framework\View\Extension\AssetExtension;
use WebRover\Framework\View\Extension\TranslatorExtension;
use WebRover\Framework\View\Loader\FilesystemLoader;

/**
 * Class ViewProvider
 * @package WebRover\Framework\View
 */
class ViewProvider extends ServiceProvider
{
    public function register()
    {
        /**
         * 注册App扩展
         */
        $this->app->singleton(AppExtension::class, function (Application $app) {

            return new AppExtension($app->make('request_stack'));

        });

        /**
         * 注册Asset扩展
         */
        $this->app->singleton(AssetExtension::class, function (Application $app) {

            return new AssetExtension($app->make('asset'));

        });

        /**
         * 注册Translator扩展
         */
        if ($this->app->offsetExists('translator')) {

            $this->app->singleton(TranslatorExtension::class, function (Application $app) {
                $translator = $app->make('translator');

                return new TranslatorExtension($translator);
            });
        }

        /**
         * 注册视图服务
         */
        $this->app->singleton('view', function (Application $app) {

            $config = $app->make('config');

            $params = $config->get('service.view', []);

            $options = isset($params['options']) ? $params['options'] : [];

            $options = array_merge([
                'charset' => 'utf-8',
                'auto_reload' => true,
                'strict_variables' => true,
                'autoescape' => true,
                'optimizations' => 0,
                'debug' => $app->isDebug()
            ], $options);

            $rootViewPath = resource_path('view');

            if (isset($options['root_path']) && $options['root_path']) {
                $rootViewPath = $options['root_path'];
            }

            $loader = new FilesystemLoader($rootViewPath);

            $modules = $app->getModules();

            foreach ($modules as $modName => $module) {
                $modViewPath = $module->getResourcePath() . DIRECTORY_SEPARATOR . 'view';

                if (!is_dir($modViewPath)) continue;

                $loader->addPath($modViewPath, $modName);
            }

            $twig = new Environment($loader, $options);

            $extensions = isset($params['extensions']) ? $params['extensions'] : [];

            if (!is_array($extensions)) $extensions = [];

            foreach ($extensions as $class) {

                if (!$app->offsetExists($class)) {
                    throw new \InvalidArgumentException(sprintf('Extension "%s" does not exist', $class), $class);
                }

                $extension = $app->make($class);

                if (!$extension instanceof ExtensionInterface) {
                    throw new \InvalidArgumentException(sprintf('Class "%s" must be instanceof "%s"', $class, ExtensionInterface::class));
                }

                $twig->addExtension($extension);
            }


            return $twig;
        });
    }
}