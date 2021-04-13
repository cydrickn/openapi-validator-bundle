<?php

namespace Cydrickn\OpenApiValidatorBundle\EventListener;

use Cydrickn\OpenApiValidatorBundle\EventListener\Condition\Condition;
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
    private ?Condition $condition;

    public function __construct(Validator $validator, ?Condition $condition = null)
    {
        $this->validator = $validator;
        $this->condition = $condition;
    }

    public function onKernelRequest(RequestEvent $event)
    {
        if ($event->isMasterRequest()) {
            if (!($this->condition) || ($this->condition->enabled($event->getRequest()))) {
                $this->validator->validateRequest($event->getRequest());
            }
        }
    }

    public function onKernelResponse(ResponseEvent $event)
    {
        if ($event->isMasterRequest() && $event->getRequest()->attributes->get('schema.request', true)) {
            if (!($this->condition) || $this->condition->enabled($event->getRequest())) {
                $this->validator->validateResponse($event->getRequest(), $event->getResponse());
            }
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
