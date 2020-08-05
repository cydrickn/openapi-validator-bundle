<?php

namespace Cydrickn\OpenApiValidatorBundle\EventListener;

use Cydrickn\OpenApiValidatorBundle\Schema\SchemaProblem;
use Cydrickn\OpenApiValidatorBundle\Validator\Errors;
use Cydrickn\OpenApiValidatorBundle\Validator\Validator;
use Cydrickn\OpenApiValidatorBundle\Validator\ValidatorFailed;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\HttpKernel\Exception\HttpException;

class ValidatorListener
{
    private Validator $validator;

    public function __construct(Validator $validator)
        {
            $this->validator = $validator;
        }

    public function onKernelRequest(RequestEvent $event)
    {
        if ($event->isMasterRequest()) {
            $this->validator->validateRequest($event->getRequest());
        }
    }

    public function onKernelResponse(ResponseEvent $event)
    {
        if ($event->isMasterRequest() && $event->getRequest()->attributes->get('schema.request', true)) {
            $this->validator->validateResponse($event->getRequest(), $event->getResponse());
        }
    }

    public function onKernelException(ExceptionEvent $event)
    {
        $exception = $event->getThrowable();
        if ($exception instanceof ValidatorFailed) {
            $responseCode = Errors::getResponseCode($exception->getCode());
            $event->setThrowable(new HttpException($responseCode, $exception->getMessage(), $exception));
            if ($exception->getCode() === Errors::ERROR_REQUEST) {
                $event->getRequest()->attributes->set('schema.request', false);
            }
        }
    }
}
