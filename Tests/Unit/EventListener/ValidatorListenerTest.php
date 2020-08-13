<?php

declare(strict_types=1);

namespace Cydrickn\OpenApiValidatorBundle\Tests\Unit\EventListener;

use Cydrickn\OpenApiValidatorBundle\Tests\Unit\TestCase;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpFoundation\Request;
use Cydrickn\OpenApiValidatorBundle\EventListener\ValidatorListener;
use Cydrickn\OpenApiValidatorBundle\Validator\Validator;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Cydrickn\OpenApiValidatorBundle\Validator\Errors;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Cydrickn\OpenApiValidatorBundle\Validator\ValidatorFailed;

class ValidatorListenerTest extends TestCase
{
    public function testOnKernelRequest()
    {
        $request = $this->createMock(Request::class);
        $httpKernel = $this->createMock(HttpKernelInterface::class);
        $event = new RequestEvent($httpKernel, $request, HttpKernelInterface::MASTER_REQUEST);

        $validator = $this->createMock(Validator::class);
        $validator->expects($this->once())
            ->method('validateRequest')
            ->with($request)
        ;

        $listener = new ValidatorListener($validator);
        $listener->onKernelRequest($event);
    }

    public function testOnKernelResponse()
    {
        $request = new Request([], [], ['schema' => ['request' => true]]);
        $response = $this->createMock(Response::class);
        $httpKernel = $this->createMock(HttpKernelInterface::class);
        $event = new ResponseEvent($httpKernel, $request, HttpKernelInterface::MASTER_REQUEST, $response);

        $validator = $this->createMock(Validator::class);
        $validator->expects($this->once())
            ->method('validateResponse')
            ->with($request, $response)
        ;

        $listener = new ValidatorListener($validator);
        $listener->onKernelResponse($event);
    }

    public function testOnKernelException()
    {
        $request = new Request([], [], ['schema' => ['request' => true]]);
        $httpKernel = $this->createMock(HttpKernelInterface::class);
        $message = 'test error';
        $validationException = new ValidatorFailed($message, Errors::ERROR_REQUEST);
        $event = new ExceptionEvent($httpKernel, $request, HttpKernelInterface::MASTER_REQUEST, $validationException);
        $validator = $this->createMock(Validator::class);

        $listener = new ValidatorListener($validator);
        $listener->onKernelException($event);

        $this->assertInstanceOf(HttpException::class, $event->getThrowable());
        $this->assertSame($validationException, $event->getThrowable()->getPrevious());
        $this->assertSame($message, $event->getThrowable()->getMessage());
        $this->assertFalse($request->attributes->get('schema.request'));
    }
}