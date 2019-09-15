<?php


namespace WebRover\Framework\View\Loader;


use Twig\Error\LoaderError;
use WebRover\Framework\Support\Str;

class FilesystemLoader extends \Twig\Loader\FilesystemLoader
{
    private $rootPath;

    public function __construct($paths = [], $rootPath = null)
    {
        $this->rootPath = (null === $rootPath ? getcwd() : $rootPath) . \DIRECTORY_SEPARATOR;
        if (false !== $realPath = realpath($rootPath)) {
            $this->rootPath = $realPath . \DIRECTORY_SEPARATOR;
        }

        parent::__construct($paths, $rootPath);
    }

    protected function findTemplate($name)
    {
        $throw = \func_num_args() > 1 ? func_get_arg(1) : true;
        $name = $this->normalizeName($name);

        if (isset($this->cache[$name])) {
            return $this->cache[$name];
        }

        if (isset($this->errorCache[$name])) {
            if (!$throw) {
                return false;
            }

            throw new LoaderError($this->errorCache[$name]);
        }

        try {
            $this->validateName($name);

            list($namespace, $shortname) = $this->parseName($name);

        } catch (LoaderError $e) {
            if (!$throw) {
                return false;
            }

            throw $e;
        }

        if (!isset($this->paths[$namespace])) {
            $this->errorCache[$name] = sprintf('There are no registered paths for namespace "%s".', $namespace);

            if (!$throw) {
                return false;
            }

            throw new LoaderError($this->errorCache[$name]);
        }

        foreach ($this->paths[$namespace] as $path) {
            if (!$this->isAbsolutePath($path)) {
                $path = $this->rootPath . $path;
            }

            $shortnameArr = explode('/', $shortname);
            $tryShortNameArr = [];
            foreach ($shortnameArr as $node) {
                $tryShortNameArr[] = lcfirst(Str::snake($node, '_'));
            }
            $tryShortName = implode('/', $tryShortNameArr);

            if (is_file($path . '/' . $shortname) || is_file($path . '/' . $tryShortName)) {
                if (!is_file($path . '/' . $shortname)) {
                    $shortname = $tryShortName;
                }
                if (false !== $realpath = realpath($path . '/' . $shortname)) {
                    return $this->cache[$name] = $realpath;
                }

                return $this->cache[$name] = $path . '/' . $shortname;
            }
        }

        $this->errorCache[$name] = sprintf('Unable to find template "%s" (looked into: %s).', $name, implode(', ', $this->paths[$namespace]));

        if (!$throw) {
            return false;
        }

        throw new LoaderError($this->errorCache[$name]);
    }

    protected function parseName($name, $default = \Twig\Loader\FilesystemLoader::MAIN_NAMESPACE)
    {
        if (isset($name[0]) && '@' == $name[0]) {
            if (false === $pos = strpos($name, '/')) {
                throw new LoaderError(sprintf('Malformed namespaced template name "%s" (expecting "@namespace/template_name").', $name));
            }

            $namespace = substr($name, 1, $pos - 1);
            $shortname = substr($name, $pos + 1);

            return [$namespace, $shortname];
        } elseif (strpos($name, ':') && preg_match('/^(?:([^:]*):([^:]*):)?(.+)\.([^\.]+)$/', $name, $matches)) {
            $namespace = $matches[1];

            if (!$namespace) {
                throw new \InvalidArgumentException(sprintf('Template name "%s" is not valid.', $name));
            }

            $shortname = $matches[2] . '/' . $matches[3] . '.' . $matches[4];

            return [$namespace, $shortname];
        }

        return [$default, $name];
    }

    protected function validateName($name)
    {
        $arr = explode('.', $name);

        if (end($arr) != 'twig') {
            throw new LoaderError(sprintf('Template "%s" must end with twig.', $name));
        }
        parent::validateName($name);
    }

    protected function normalizeName($name)
    {
        return str_replace(':/', ':', preg_replace('#/{2,}#', '/', str_replace('\\', '/', $name)));
    }

    private function isAbsolutePath($file)
    {
        return strspn($file, '/\\', 0, 1)
            || (\strlen($file) > 3 && ctype_alpha($file[0])
                && ':' === substr($file, 1, 1)
                && strspn($file, '/\\', 2, 1)
            )
            || null !== parse_url($file, PHP_URL_SCHEME);
    }
}