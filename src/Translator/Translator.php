<?php


namespace WebRover\Framework\Translator;


use Symfony\Component\Finder\Finder;
use Symfony\Component\Translation\Loader\PhpFileLoader;
use Symfony\Component\Translation\Translator as BaseTranslator;
use WebRover\Framework\Kernel\Module\ModuleInterface;

/**
 * Class Translator
 * @package WebRover\Framework\Translator
 */
class Translator
{
    private $params = [];

    /**
     * @var ModuleInterface[]
     */
    private $modules = [];

    private $debug;

    /**
     * Translator constructor.
     * @param array $params
     * @param array $modules
     * @param bool $debug
     */
    public function __construct(array $params, array $modules = [], $debug = false)
    {
        $this->params = $params;
        $this->modules = $modules;
        $this->debug = (bool)$debug;
    }

    /**
     * @return BaseTranslator
     */
    public function getTranslator()
    {
        $locale = isset($this->params['locale']) ? $this->params['locale'] : 'en';

        $fallback = isset($this->params['fallback']) ? $this->params['fallback'] : 'en';

        if (!is_array($fallback)) {
            $fallback = [$fallback];
        }

        $cacheDir = isset($this->params['cache_dir']) ? $this->params['cache_dir'] : null;

        $translator = new BaseTranslator($locale, null, $cacheDir, $this->debug);

        $translator->setFallbackLocales($fallback);

        $loader = new PhpFileLoader();

        $translator->addLoader('array', $loader);

        $translationPaths = [];

        $defaultPath = resource_path('translation');

        if (isset($this->params['default_path']) && $this->params['default_path']) {
            $defaultPath = $this->params['default_path'];
        }

        if (is_dir($defaultPath)) {
            $translationPaths[] = $defaultPath;
        }

        foreach ($this->modules as $module) {
            $translationPath = $module->getResourcePath() . DIRECTORY_SEPARATOR . 'translation';

            if (is_dir($translationPath)) {
                $translationPaths[] = $translationPath;
            }
        }

        foreach ($translationPaths as $translationPath) {

            $finder = new Finder();

            $finder->depth(0)->files()->filter(function (\SplFileInfo $fileInfo) {
                $fileName = $fileInfo->getFilename();

                $fileName = explode('.', $fileName);

                if (count($fileName) != 3) {
                    return false;
                }

                $extension = $fileInfo->getExtension();

                if ($extension != 'php') {
                    return false;
                }

                return true;
            })->in($translationPath);

            foreach ($finder as $file) {
                $pathName = $file->getRelativePathname();

                list($domain, $locale, $format) = explode('.', $pathName);

                $resource = $translationPath . DIRECTORY_SEPARATOR . $pathName;

                $translator->addResource('array', $resource, $locale, $domain);
            }
        }

        return $translator;
    }
}