<?php

declare(strict_types=1);

namespace Cydrickn\OpenApiValidatorBundle\Tests\Common;

use PHPUnit\Framework\TestCase as BaseTestCase;

class TestCase extends BaseTestCase
{
    protected function getAssetFolder(): string
    {
        return realpath(dirname(__DIR__) . '/assets');
    }

    protected function getAssetPath(string $asset): string
    {
        return realpath($this->getAssetFolder() . '/' . $asset);
    }
}