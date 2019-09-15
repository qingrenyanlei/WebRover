<?php


namespace WebRover\Framework\Kernel\EventListener;


use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use WebRover\Framework\Foundation\Request;
use WebRover\Framework\Kernel\Event\GetResponseEvent;
use WebRover\Framework\Kernel\Exception\AccessDeniedHttpException;
use WebRover\Framework\Kernel\KernelEvents;
use WebRover\Framework\Kernel\UriSigner;

/**
 * Handles content fragments represented by special URIs.
 *
 * All URL paths starting with /_fragment are handled as
 * content fragments by this listener.
 *
 * Throws an AccessDeniedHttpException exception if the request
 * is not signed or if it is not an internal sub-request.
 *
 * Class FragmentListener
 * @package WebRover\Framework\Kernel\EventListener
 */
class FragmentListener implements EventSubscriberInterface
{
    private $signer;
    private $fragmentPath;

    /**
     * @param UriSigner $signer A UriSigner instance
     * @param string $fragmentPath The path that triggers this listener
     */
    public function __construct(UriSigner $signer, $fragmentPath = '/_fragment')
    {
        $this->signer = $signer;
        $this->fragmentPath = $fragmentPath;
    }

    /**
     * Fixes request attributes when the path is '/_fragment'.
     *
     * @throws AccessDeniedHttpException if the request does not come from a trusted IP
     */
    public function onKernelRequest(GetResponseEvent $event)
    {
        $request = $event->getRequest();

        if ($this->fragmentPath !== rawurldecode($request->getPathInfo())) {
            return;
        }

        if ($request->attributes->has('_controller')) {
            // Is a sub-request: no need to parse _path but it should still be removed from query parameters as below.
            $request->query->remove('_path');

            return;
        }

        if ($event->isMasterRequest()) {
            $this->validateRequest($request);
        }

        parse_str($request->query->get('_path', ''), $attributes);
        $request->attributes->add($attributes);
        $request->attributes->set('_route_params', array_replace($request->attributes->get('_route_params', []), $attributes));
        $request->query->remove('_path');
    }

    protected function validateRequest(Request $request)
    {
        // is the Request safe?
        if (!$request->isMethodSafe(false)) {
            throw new AccessDeniedHttpException();
        }

        // is the Request signed?
        // we cannot use $request->getUri() here as we want to work with the original URI (no query string reordering)
        if ($this->signer->check($request->getSchemeAndHttpHost() . $request->getBaseUrl() . $request->getPathInfo() . (null !== ($qs = $request->server->get('QUERY_STRING')) ? '?' . $qs : ''))) {
            return;
        }

        throw new AccessDeniedHttpException();
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::REQUEST => [['onKernelRequest', 48]],
        ];
    }
}