<?php

declare(strict_types=1);

namespace Cydrickn\OpenApiValidatorBundle\Tests\Unit\Schema\Factory;

use Cydrickn\OpenApiValidatorBundle\Tests\Unit\TestCase;
use Cydrickn\OpenApiValidatorBundle\Schema\Factory\JsonFileFactory;
use Cydrickn\OpenApiValidatorBundle\Schema\Schema;

class JsonFileFactoryTest extends TestCase
{
    public function testCreateSchema()
    {
        $factory =  new JsonFileFactory($this->getAssetPath('spec.json'));
        $this->assertInstanceOf(Schema::class, $factory->createSchema());
    }
}