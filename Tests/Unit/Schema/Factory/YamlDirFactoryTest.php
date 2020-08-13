<?php

declare(strict_types=1);

namespace Cydrickn\OpenApiValidatorBundle\Tests\Unit\Schema\Factory;

use Cydrickn\OpenApiValidatorBundle\Tests\Unit\TestCase;
use Cydrickn\OpenApiValidatorBundle\Schema\Factory\JsonDirFactory;
use Cydrickn\OpenApiValidatorBundle\Schema\Schema;
use Cydrickn\OpenApiValidatorBundle\Schema\Factory\YamlDirFactory;

class YamlDirFactoryTest extends TestCase
{
    public function testCreateSchema()
    {
        $factory = new YamlDirFactory($this->getAssetFolder());
        $schema = $factory->createSchema();
        $this->assertInstanceOf(Schema::class, $schema);
        $this->assertTrue($schema->paths->hasPath('/names/{id}'));
        $this->assertTrue($schema->paths->hasPath('/names'));
    }
}