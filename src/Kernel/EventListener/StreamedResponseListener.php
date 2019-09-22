<?php


namespace WebRover\Framework\Kernel\EventListener;


use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\StreamedResponse;
use WebRover\Framework\Kernel\Event\FilterResponseEvent;
use WebRover\Framework\Kernel\KernelEvents;

/**
 * StreamedResponseListener is responsible for sending the Response
 * to the client.
 *
 * Class StreamedResponseListener
 * @package WebRover\Framework\Kernel\EventListener
 */
class StreamedResponseListener implements EventSubscriberInterface
{
    /**
     * Filters the Response.
     */
    public function onKernelResponse(FilterResponseEvent $event)
    {
        if (!$event->isMasterRequest()) {
            return;
        }

        $response = $event->getResponse();

        if ($response instanceof StreamedResponse) {
            $response->send();
        }
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::RESPONSE => ['onKernelResponse', -1024],
        ];
    }
}
