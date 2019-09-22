<?php


namespace WebRover\Framework\Routing;

use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Routing\Router as BaseRouter;
use WebRover\Framework\Config\Config;
use WebRover\Framework\Container\ContainerParametersResource;
use WebRover\Framework\Container\Exception\RuntimeException;

/**
 * Class Router
 * @package WebRover\Framework\Routing
 */
class Router extends BaseRouter
{
    private $config;

    private $collectedParameters = [];


    public function __construct(Config $config, LoaderInterface $loader, $resource, array $options = [], RequestContext $context = null)
    {
        $this->config = $config;
        parent::__construct($loader, $resource, $options, $context);
    }

    /**
     * {@inheritdoc}
     */
    public function getRouteCollection()
    {
        if (null === $this->collection) {
            $this->collection = $this->loader->load($this->resource, $this->options['resource_type']);

            $this->resolveParameters($this->collection);
            $this->collection->addResource(new ContainerParametersResource($this->collectedParameters));
        }

        return $this->collection;
    }

    /**
     * Replaces placeholders with service container parameter values in:
     * - the route defaults,
     * - the route requirements,
     * - the route path,
     * - the route host,
     * - the route schemes,
     * - the route methods.
     */
    private function resolveParameters(RouteCollection $collection)
    {
        foreach ($collection as $route) {
            foreach ($route->getDefaults() as $name => $value) {
                $route->setDefault($name, $this->resolve($value));
            }

            foreach ($route->getRequirements() as $name => $value) {
                $route->setRequirement($name, $this->resolve($value));
            }

            $route->setPath($this->resolve($route->getPath()));
            $route->setHost($this->resolve($route->getHost()));

            $schemes = [];
            foreach ($route->getSchemes() as $scheme) {
                $schemes = array_merge($schemes, explode('|', $this->resolve($scheme)));
            }
            $route->setSchemes($schemes);

            $methods = [];
            foreach ($route->getMethods() as $method) {
                $methods = array_merge($methods, explode('|', $this->resolve($method)));
            }
            $route->setMethods($methods);
            $route->setCondition($this->resolve($route->getCondition()));
        }
    }

    /**
     * Recursively replaces placeholders with the service container parameters.
     *
     * @param mixed $value The source which might contain "%placeholders%"
     *
     * @return mixed The source with the placeholders replaced by the container
     *               parameters. Arrays are resolved recursively.
     *
     * @throws RuntimeException           When a container value is not a string or a numeric value
     */
    private function resolve($value)
    {
        if (\is_array($value)) {
            foreach ($value as $key => $val) {
                $value[$key] = $this->resolve($val);
            }

            return $value;
        }

        if (!\is_string($value)) {
            return $value;
        }

        $config = $this->config;

        $escapedValue = preg_replace_callback('/%%|%([^%\s]++)%/', function ($match) use ($config, $value) {
            // skip %%
            if (!isset($match[1])) {
                return '%%';
            }

            if (preg_match('/^env\(\w+\)$/', $match[1])) {
                throw new RuntimeException(sprintf('Using "%%%s%%" is not allowed in routing configuration.', $match[1]));
            }

            $resolved = $config->get($match[1]);

            if (\is_string($resolved) || is_numeric($resolved)) {
                $this->collectedParameters[$match[1]] = $resolved;

                return (string)$resolved;
            }

            throw new RuntimeException(sprintf('The parameter "%s", used in the route configuration value "%s", must be a string or numeric, but it is of type %s.', $match[1], $value, \gettype($resolved)));
        }, $value);

        return str_replace('%%', '%', $escapedValue);
    }
}