<?php

declare(strict_types=1);

namespace Cydrickn\OpenApiValidatorBundle\Validator;

use League\OpenAPIValidation\PSR7\Exception\ValidationFailed;
use League\OpenAPIValidation\PSR7\ValidatorBuilder;
use Cydrickn\OpenApiValidatorBundle\Schema\Schema;
use Nyholm\Psr7\Factory\Psr17Factory;
use Symfony\Bridge\PsrHttpMessage\Factory\PsrHttpFactory;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class Validator
{
    private ValidatorBuilder $validatorBuilder;
    private Schema $schema;
    private PsrHttpFactory $psrHttpFactory;

    public function __construct(Schema $schema)
    {
        $this->schema = $schema;
        $this->validatorBuilder = new ValidatorBuilder();
        $this->validatorBuilder->fromSchema($schema);
        $psr17Factory = new Psr17Factory();
        $this->psrHttpFactory = new PsrHttpFactory($psr17Factory, $psr17Factory, $psr17Factory, $psr17Factory);
    }

    public function validateRequest(Request $request): void
    {
        try {
            $psrRequest = $this->psrHttpFactory->createRequest($request);
            $validator = $this->validatorBuilder->getRoutedRequestValidator();
            $operationAddress = $this->schema->findMatchingOperations($psrRequest);
            $validator->validate($operationAddress, $psrRequest);
        } catch (ValidationFailed $exception) {
            throw new ValidatorFailed($exception->getPrevious()->getMessage(), Errors::ERROR_REQUEST, $exception);
        }
    }

    public function validateResponse(Request $request, Response $response): void
    {
        try {
            $psrRequest = $this->psrHttpFactory->createRequest($request);
            $psrResponse = $this->psrHttpFactory->createResponse($response);
            $validator = $this->validatorBuilder->getResponseValidator();
            $operationAddress = $this->schema->findMatchingOperations($psrRequest);
            $validator->validate($operationAddress, $psrResponse);
        } catch (ValidationFailed $exception) {
            throw new ValidatorFailed($exception->getPrevious()->getMessage(), Errors::ERROR_RESPONSE, $exception);
        }
    }
}
