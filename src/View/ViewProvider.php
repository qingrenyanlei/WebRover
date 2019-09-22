<?php


namespace WebRover\Framework\View;


use Symfony\Bridge\Twig\Extension\AssetExtension;
use Symfony\Bridge\Twig\Extension\CodeExtension;
use Symfony\Bridge\Twig\Extension\DumpExtension;
use Symfony\Bridge\Twig\Extension\HttpFoundationExtension;
use Symfony\Bridge\Twig\Extension\RoutingExtension;
use Symfony\Bridge\Twig\Extension\TranslationExtension;
use Symfony\Component\Templating\PhpEngine;
use Symfony\Component\Templating\TemplateNameParserInterface;
use Symfony\Component\Templating\Loader\FilesystemLoader as PhpFilesystemLoader;
use Symfony\Component\VarDumper\Cloner\VarCloner;
use Twig\Environment;
use Twig\Extension\ExtensionInterface;
use WebRover\Framework\Container\ServiceProvider;
use WebRover\Framework\Kernel\Application;
use WebRover\Framework\Kernel\Debug\FileLinkFormatter;

/**
 * Class ViewProvider
 * @package WebRover\Framework\View
 */
class ViewProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton('view', function (Application $app) {

            $config = $app->make('config');

            $params = $config->get('service.view', []);

            if (!isset($params['options'])) {
                $params['options'] = [];
            }

            $paths = [];

            $rootViewPath = $app->getResourcePath() . DIRECTORY_SEPARATOR . 'view';

            if (isset($params['options']['root_path']) && $params['options']['root_path']) {
                $rootViewPath = $params['options']['root_path'];
            }

            $bundles = $app->getBundles();

            foreach ($bundles as $bundleName => $bundle) {
                $bundleViewPath = $bundle->getResourcePath() . DIRECTORY_SEPARATOR . 'view';
                if (!is_dir($bundleViewPath)) continue;

                $paths[$bundleName] = $bundleViewPath;
            }

            $templateNameParser = new TemplateNameParser($app);

            $phpEngine = $this->makePhpEngine($app, $templateNameParser, array_merge(array_values($paths), [$rootViewPath]));

            $twigEngine = $this->makeTwigEngine($app, $templateNameParser, $params, $rootViewPath, $paths);

            return new View([
                $phpEngine,
                $twigEngine
            ]);
        });
    }

    /**
     * @param Application $app
     * @param TemplateNameParserInterface $templateNameParser
     * @param array $paths
     * @return PhpEngine
     */
    private function makePhpEngine(Application $app, TemplateNameParserInterface $templateNameParser, $paths = [])
    {
        $pathPatterns = [];

        foreach ($paths as $path) {
            $pathPatterns[] = $path . DIRECTORY_SEPARATOR . 'view';
        }

        $loader = new PhpFilesystemLoader($pathPatterns);

        return new PhpEngine($templateNameParser, $loader);
    }

    /**
     * @param Application $app
     * @param TemplateNameParserInterface $templateNameParser
     * @param array $params
     * @param null $rootViewPath
     * @param array $paths
     * @return TwigEngine
     * @throws \Twig\Error\LoaderError
     */
    private function makeTwigEngine(Application $app, TemplateNameParserInterface $templateNameParser, array $params = [], $rootViewPath = null, $paths = [])
    {
        $options = array_merge([
            'charset' => 'utf-8',
            'auto_reload' => true,
            'strict_variables' => true,
            'autoescape' => 'html',
            'optimizations' => 0,
            'debug' => $app->isDebug()
        ], $params['options']);

        $fileLocator = $app->make('file_locator');
        $locator = new TemplateLocator($fileLocator);
        $loader = new TwigFilesystemLoader($locator, $templateNameParser);

        $loader->setPaths($rootViewPath);

        foreach ($paths as $namespace => $path) {
            $loader->addPath($path, $namespace);
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

        $this->registerBuiltInExtensions();

        $twig = $this->addBuiltInTwigExtensions($twig);

        return new TwigEngine($twig, $templateNameParser, $locator);
    }

    private function registerBuiltInExtensions()
    {
        $this->app->singleton(AssetExtension::class, function (Application $app) {
            return new AssetExtension($app->make('asset'));
        });

        $this->app->singleton(CodeExtension::class, function (Application $app) {
            $fileLinkFormatter = new FileLinkFormatter();
            return new CodeExtension($fileLinkFormatter, $app->getRootPath(), $app->getCharset());
        });

        $this->app->singleton(DumpExtension::class, function (Application $app) {
            $cloner = new VarCloner();
            return new DumpExtension($cloner);
        });

        $this->app->singleton(HttpFoundationExtension::class, function (Application $app) {
            $requestStack = $app->make('request_stack');

            $requestContext = $app->make('router')->getContext();

            return new HttpFoundationExtension($requestStack, $requestContext);
        });

        $this->app->singleton(RoutingExtension::class, function (Application $app) {
            $generator = $app->make('router')->getGenerator();
            return new RoutingExtension($generator);
        });

        $this->app->singleton(TranslationExtension::class, function (Application $app) {
            return new TranslationExtension($app->make('translator'));
        });
    }

    private function addBuiltInTwigExtensions(Environment $twig)
    {
        $app = $this->app;
        $twig->addExtension($app->make(AssetExtension::class));
        $twig->addExtension($app->make(CodeExtension::class));
        $twig->addExtension($app->make(DumpExtension::class));
        $twig->addExtension($app->make(HttpFoundationExtension::class));
        $twig->addExtension($app->make(RoutingExtension::class));
        $twig->addExtension($app->make(TranslationExtension::class));

        $globalVar = new GlobalVariables($app);
        $twig->addGlobal('app', $globalVar);

        return $twig;
    }
}