<?php


namespace WebRover\Framework\Routing\Matcher;


use WebRover\Framework\Routing\Exception\ResourceNotFoundException;
use WebRover\Framework\Routing\Route;

/**
 * Class RedirectableUrlMatcher
 * @package WebRover\Framework\Routing\Matcher
 */
abstract class RedirectableUrlMatcher extends UrlMatcher implements RedirectableUrlMatcherInterface
{
    /**
     * {@inheritdoc}
     */
    public function match($pathinfo)
    {
        try {
            $parameters = parent::match($pathinfo);
        } catch (ResourceNotFoundException $e) {
            if ('/' === substr($pathinfo, -1) || !\in_array($this->context->getMethod(), ['HEAD', 'GET'])) {
                throw $e;
            }

            try {
                $parameters = parent::match($pathinfo . '/');

                return array_replace($parameters, $this->redirect($pathinfo . '/', isset($parameters['_route']) ? $parameters['_route'] : null));
            } catch (ResourceNotFoundException $e2) {
                throw $e;
            }
        }

        return $parameters;
    }

    /**
     * {@inheritdoc}
     */
    protected function handleRouteRequirements($pathinfo, $name, Route $route)
    {
        // expression condition
        if ($route->getCondition() && !$this->getExpressionLanguage()->evaluate($route->getCondition(), ['context' => $this->context, 'request' => $this->request ?: $this->createRequest($pathinfo)])) {
            return [self::REQUIREMENT_MISMATCH, null];
        }

        // check HTTP scheme requirement
        $scheme = $this->context->getScheme();
        $schemes = $route->getSchemes();
        if ($schemes && !$route->hasScheme($scheme)) {
            return [self::ROUTE_MATCH, $this->redirect($pathinfo, $name, current($schemes))];
        }

        return [self::REQUIREMENT_MATCH, null];
    }
}