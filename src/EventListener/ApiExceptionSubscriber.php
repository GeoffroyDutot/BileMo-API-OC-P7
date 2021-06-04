<?php


namespace App\EventListener;


use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Serializer\Exception\NotEncodableValueException;
use Symfony\Component\Serializer\SerializerInterface;

class ApiExceptionSubscriber implements EventSubscriberInterface
{
    private SerializerInterface $serializer;

    public function __construct(SerializerInterface $serializer) {
        $this->serializer = $serializer;
    }

    public function onKernelException(ExceptionEvent $event)
    {
        switch (get_class($event->getThrowable())) {
            case NotFoundHttpException::class:
                return $event->setResponse(new JsonResponse($this->serializer->serialize(['code' => 404, 'message' => 'Not found.'], 'json'), 404, ['Content-Type' => 'application/problem+json'], true));
            case NotEncodableValueException::class:
                return $event->setResponse(new JsonResponse($this->serializer->serialize(['code' => 400, 'message' => 'Syntax Error.'], 'json'), 400, ['Content-Type' => 'application/problem+json'], true));
            case AccessDeniedHttpException::class:
                return $event->setResponse(new JsonResponse($this->serializer->serialize(['code' => 403, 'message' => 'Access denied.'], 'json'), 403, ['Content-Type' => 'application/problem+json'], true));
        }

        return $event->setResponse(new JsonResponse($this->serializer->serialize(['code' => 500, 'msg' => 'Internal error.'], 'json'), 500, ['Content-Type' => 'application/problem+json'], true));
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::EXCEPTION => 'onKernelException'
        ];
    }
}
