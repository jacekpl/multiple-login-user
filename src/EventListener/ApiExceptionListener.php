<?php

namespace App\EventListener;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;

class ApiExceptionListener
{
    public function onKernelException(ExceptionEvent $event)
    {
        $exception = $event->getThrowable();
        $uri = $event->getRequest()->getRequestUri();

        if (substr($uri, 0, 5) !== "/api/") {
            return;
        }

        $responseData = ['error' => $exception->getMessage()];

        $code = $exception->getCode();

        if (method_exists($exception, 'getStatusCode')) {
            $code = $exception->getStatusCode();
        }

        if (!$code) {
            return;
        }

        $event->setResponse(new JsonResponse($responseData, $code));
    }
}
