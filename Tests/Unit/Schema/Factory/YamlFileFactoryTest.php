<?php

declare(strict_types=1);

namespace Cydrickn\OpenApiValidatorBundle\Tests\Unit\Schema\Factory;

use Cydrickn\OpenApiValidatorBundle\Schema\Schema;
use Cydrickn\OpenApiValidatorBundle\Tests\Unit\TestCase;
use Cydrickn\OpenApiValidatorBundle\Schema\Factory\YamlFileFactory;

class YamlFileFactoryTest extends TestCase
{
    public function testCreateSchema()
    {
        $factory =  new YamlFileFactory($this->getAssetPath('spec.yaml'));
        $this->assertInstanceOf(Schema::class, $factory->createSchema());
    }
}