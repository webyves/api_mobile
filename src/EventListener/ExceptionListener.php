<?php
namespace App\EventListener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

class ExceptionListener implements EventSubscriberInterface
{
	public static function getSubscribedEvents()
    {
        // return the subscribed events, their methods and priorities
        return [
           KernelEvents::EXCEPTION => [
               ['onKernelException', 0],
           ]
        ];
    }

    public function onKernelException(GetResponseForExceptionEvent $event)
    {
        // You get the exception object from the received event
        $exception = $event->getException();
    	// Customize your response object to display the exception details
        if ($exception instanceof \App\Exception\ValidationException) {
	        $response = new JsonResponse(json_decode($exception->getMessage()), Response::HTTP_BAD_REQUEST);
	        // sends the modified response object to the event
	        $event->setResponse($response);
        }
    }
}
