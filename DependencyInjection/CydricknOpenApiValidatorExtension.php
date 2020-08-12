<?php

declare(strict_types=1);

namespace Cydrickn\OpenApiValidatorBundle\DependencyInjection;

use Cydrickn\OpenApiValidatorBundle\EventListener\ValidatorListener;
use Cydrickn\OpenApiValidatorBundle\Schema\Factory\JsonFileFactory;
use Cydrickn\OpenApiValidatorBundle\Schema\Factory\NelmioFactory;
use Cydrickn\OpenApiValidatorBundle\Schema\Factory\YamlFileFactory;
use Cydrickn\OpenApiValidatorBundle\Schema\Schema;
use Cydrickn\OpenApiValidatorBundle\Validator\Validator;
use League\OpenAPIValidation\PSR7\SchemaFactory\FileFactory;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpKernel\DependencyInjection\ConfigurableExtension;
use Cydrickn\OpenApiValidatorBundle\Schema\Factory\PHPFileFactory;

class CydricknOpenApiValidatorExtension extends ConfigurableExtension
{
    private const SCHEMAS = [
        'json-file' => JsonFileFactory::class,
        'yaml-file' => YamlFileFactory::class,
        'nelmio' => NelmioFactory::class,
        'php' => PHPFileFactory::class,
    ];

    protected function loadInternal(array $mergedConfig, ContainerBuilder $container)
    {
        $factoryClass = self::SCHEMAS[$mergedConfig['schema']['factory']];
        $factoryArguments = [];
        if (in_array(FileFactory::class, class_parents($factoryClass))) {
            $factoryArguments['$filename'] = $mergedConfig['schema']['file'];
        } elseif ($factoryClass === NelmioFactory::class) {
            $factoryArguments[] = new Reference('nelmio_api_doc.generator_locator');
        }
        $schemaFactory = new Definition($factoryClass, $factoryArguments);
        $schemaFactory->setAutoconfigured(true);
        $schemaFactory->setAutowired(true);
        $container->setDefinition('cydrickn.openapi_validator.schema_factory', $schemaFactory);

        $schema = new Definition(Schema::class);
        $schema->setFactory([new Reference('cydrickn.openapi_validator.schema_factory'), 'createSchema']);
        $container->setDefinition('cydrickn.openapi_validator.schema', $schema);

        $validator = new Definition(Validator::class, [
            new Reference('cydrickn.openapi_validator.schema'),
        ]);
        $container->setDefinition('cydrickn.openapi_validator.validator', $validator);

        $validatorListener = new Definition(ValidatorListener::class, [
            new Reference('cydrickn.openapi_validator.validator'),
        ]);
        if ($mergedConfig['validate_request']) {
            $validatorListener->addTag('kernel.event_listener', ['event' => 'kernel.request']);
        }
        if ($mergedConfig['validate_response']) {
            $validatorListener->addTag('kernel.event_listener', ['event' => 'kernel.response']);
        }
        $validatorListener->addTag('kernel.event_listener', ['event' => 'kernel.exception']);
        $container->setDefinition('cydrickn.openapi_validator.validator_listener', $validatorListener);
    }
}