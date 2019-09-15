<?php


namespace WebRover\Framework\Kernel\Controller;


use SplFileInfo;
use WebRover\Framework\Foundation\BinaryFileResponse;
use WebRover\Framework\Foundation\RedirectResponse;
use WebRover\Framework\Foundation\Response;
use WebRover\Framework\Foundation\ResponseHeaderBag;
use WebRover\Framework\Foundation\StreamedResponse;
use WebRover\Framework\Kernel\HttpKernel;
use WebRover\Framework\Kernel\HttpKernelInterface;
use WebRover\Framework\Routing\Generator\UrlGeneratorInterface;

/**
 * Class Controller
 * @package WebRover\Framework\Kernel\Controller
 */
abstract class Controller
{
    /**
     * @param $id
     * @return bool
     */
    protected function has($id)
    {
        return app()->offsetExists($id);
    }

    /**
     * @param $id
     * @return mixed
     */
    protected function make($id)
    {
        return app()->make($id);
    }

    /**
     * Generates a URL from the given parameters.
     *
     * @param string $route The name of the route
     * @param array $parameters An array of parameters
     * @param int $referenceType The type of reference (one of the constants in UrlGeneratorInterface)
     * @return string The generated URL
     */
    protected function generateUrl($route, $parameters = [], $referenceType = UrlGeneratorInterface::ABSOLUTE_PATH)
    {
        return app()->make('router')->generate($route, $parameters, $referenceType);
    }

    /**
     * Forwards the request to another controller.
     *
     * @param string $controller The controller name (a string like Blog:Post:index)
     * @param array $path An array of path parameters
     * @param array $query An array of query parameters
     * @return Response A Response instance
     */
    protected function forward($controller, array $path = [], array $query = [])
    {
        $request = app()->make('request_stack')->getCurrentRequest();

        $path['_forwarded'] = $request->attributes;
        $path['_controller'] = $controller;
        $subRequest = $request->duplicate($query, null, $path);

        return app()->make(HttpKernel::class)->handle($subRequest, HttpKernelInterface::SUB_REQUEST);
    }

    /**
     * Returns a RedirectResponse to the given URL.
     *
     * @param string $url The URL to redirect to
     * @param int $status The status code to use for the Response
     * @return RedirectResponse
     */
    protected function redirect($url, $status = 302)
    {
        return new RedirectResponse($url, $status);
    }

    /**
     * Returns a RedirectResponse to the given route with the given parameters.
     *
     * @param string $route The name of the route
     * @param array $parameters An array of parameters
     * @param int $status The status code to use for the Response
     * @return RedirectResponse
     */
    protected function redirectToRoute($route, array $parameters = [], $status = 302)
    {
        return $this->redirect($this->generateUrl($route, $parameters), $status);
    }

    /**
     * Returns a BinaryFileResponse object with original or customized file name and disposition header.
     *
     * @param SplFileInfo|string $file File object or path to file to be sent as response
     * @param string|null $fileName File name to be sent to response or null (will use original file name)
     * @param string $disposition Disposition of response ("attachment" is default, other type is "inline")
     * @return BinaryFileResponse
     */
    protected function file($file, $fileName = null, $disposition = ResponseHeaderBag::DISPOSITION_ATTACHMENT)
    {
        $response = new BinaryFileResponse($file);
        $response->setContentDisposition($disposition, null === $fileName ? $response->getFile()->getFilename() : $fileName);

        return $response;
    }

    /**
     * Returns a rendered view.
     *
     * @param string $view The view name
     * @param array $parameters An array of parameters to pass to the view
     * @return string The rendered view
     */
    protected function renderView($view, array $parameters = [])
    {
        return app()->make('view')->render($view, $parameters);
    }

    /**
     * Renders a view.
     *
     * @param string $view The view name
     * @param array $parameters An array of parameters to pass to the view
     * @param Response|null $response
     * @return Response A Response instance
     */
    protected function render($view, array $parameters = [], Response $response = null)
    {
        $content = $this->renderView($view, $parameters);

        if (null === $response) {
            $response = new Response();
        }

        $response->setContent($content);

        return $response;
    }

    /**
     * Streams a view.
     *
     * @param string $view The view name
     * @param array $parameters An array of parameters to pass to the view
     * @param StreamedResponse|null $response
     * @return StreamedResponse A StreamedResponse instance
     */
    protected function stream($view, array $parameters = [], StreamedResponse $response = null)
    {
        $twig = app()->make('view');

        $callback = function () use ($twig, $view, $parameters) {
            $twig->display($view, $parameters);
        };

        if (null === $response) {
            return new StreamedResponse($callback);
        }

        $response->setCallback($callback);

        return $response;
    }

    /**
     * Translator
     *
     * @param $id
     * @param array $parameters
     * @param null $domain
     * @param null $locale
     * @return mixed
     */
    protected function trans($id, array $parameters = [], $domain = null, $locale = null)
    {
        return app()->make('translator')->trans($id, $parameters, $domain, $locale);
    }
}