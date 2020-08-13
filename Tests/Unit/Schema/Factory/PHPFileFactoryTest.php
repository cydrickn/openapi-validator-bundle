<?php

declare(strict_types=1);

namespace Cydrickn\OpenApiValidatorBundle\Tests\Unit\Schema\Factory;

use Cydrickn\OpenApiValidatorBundle\Tests\Unit\TestCase;
use Cydrickn\OpenApiValidatorBundle\Schema\Factory\PHPFileFactory;
use Cydrickn\OpenApiValidatorBundle\Schema\Schema;

class PHPFileFactoryTest extends TestCase
{
    public function testCreateSchema()
    {
        $factory = new PHPFileFactory($this->getAssetPath('spec.php'));
        $this->assertInstanceOf(Schema::class, $factory->createSchema());
    }
}