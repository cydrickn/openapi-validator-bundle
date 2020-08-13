<?php

declare(strict_types=1);

namespace Cydrickn\OpenApiValidatorBundle\Tests\Unit\Schema\Factory;

use Cydrickn\OpenApiValidatorBundle\Tests\Unit\TestCase;
use Cydrickn\OpenApiValidatorBundle\Schema\Factory\DirFactory;

class DirFactoryTest extends TestCase
{
    public function testGetCacheKey()
    {
        $dirName = $this->getAssetFolder();
        $dirFactory = $this->getMockForAbstractClass(DirFactory::class, [$dirName]);
        $expected = 'openapi_' . crc32(realpath($dirName));
        $this->assertSame($expected, $dirFactory->getCacheKey());
    }
}