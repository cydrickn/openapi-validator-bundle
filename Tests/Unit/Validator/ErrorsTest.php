<?php

declare(strict_types=1);

namespace Cydrickn\OpenApiValidatorBundle\Tests\Unit\Validator;

use Cydrickn\OpenApiValidatorBundle\Validator\Errors;
use Cydrickn\OpenApiValidatorBundle\Tests\Unit\TestCase;

class ErrorsTest extends TestCase
{
    /**
     * @dataProvider dataGetResponseCode
     */
    public function testGetResponseCode(int $code, int $expected)
    {
        $this->assertSame($expected, Errors::getResponseCode($code));
    }

    public function dataGetResponseCode()
    {
        yield [1001, 400];
        yield [1002, 500];
    }
}