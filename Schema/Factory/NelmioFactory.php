<?php

declare(strict_types=1);

namespace Cydrickn\OpenApiValidatorBundle\Schema\Factory;

use cebe\openapi\ReferenceContext;
use Cydrickn\OpenApiValidatorBundle\Schema\Schema;
use League\OpenAPIValidation\PSR7\SchemaFactory;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;

class NelmioFactory implements SchemaFactory
{
    private ContainerInterface $generatorLocator;
    private LoggerInterface $logger;

    public function __construct(ContainerInterface $generatorLocator, LoggerInterface $logger)
    {
        $this->generatorLocator = $generatorLocator;
        $this->logger = $logger;
    }

    public function createSchema(): Schema
    {
        $apiDoc = $this->generatorLocator->get('default');
        $spec = $apiDoc->generate();

        if (method_exists($spec,'toArray')) {
            $spec = $spec->toArray();
        } else {
            $spec = json_decode(json_encode($spec), true);
        }

        if (!empty($spec['swagger'])) {
            $this->logger->warning(
                'Validating of request/response will be ignored due to the Open API Spec'
                . ' Version 2 and below are not supported.'
            );
        }
        $schema = new Schema($spec);
        $context = new ReferenceContext($schema, '://');
        $schema->resolveReferences($context);
        return $schema;
    }
}