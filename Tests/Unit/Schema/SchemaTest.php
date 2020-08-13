<?php

declare(strict_types=1);

namespace Cydrickn\OpenApiValidatorBundle\Tests\Unit\Schema;

use Cydrickn\OpenApiValidatorBundle\Schema\Factory\JsonFileFactory;
use Cydrickn\OpenApiValidatorBundle\Schema\Schema;
use League\OpenAPIValidation\PSR7\OperationAddress;
use Nyholm\Psr7\Request;
use Cydrickn\OpenApiValidatorBundle\Tests\Unit\TestCase;

class SchemaTest extends TestCase
{
    private function createSchema(): Schema
    {
        $spec = require $this->getAssetFolder() . '/spec.php';

        return new Schema($spec);
    }

    /**
     * @dataProvider dataFindMatchingOperationsReturnOperationAddress
     */
    public function testFindMatchingOperationsReturnOperationAddress(string $method, string $uri, string $expectedUri)
    {
        $schema = $this->createSchema();
        $request = new Request($method, $uri);
        $result = $schema->findMatchingOperations($request);
        $this->assertInstanceOf(OperationAddress::class, $result);
        $this->assertSame(strtolower($method), $result->method());
        $this->assertSame($expectedUri, $result->path());
    }

    public function dataFindMatchingOperationsReturnOperationAddress()
    {
        yield ['GET', '/names', '/names'];
        yield ['POST', '/names', '/names'];
    }

    public function testFindMatchingOperationsReturnNull()
    {
        $schema = $this->createSchema();
        $request = new Request('GET', '/wrongapi');
        $result = $schema->findMatchingOperations($request);

        $this->assertNull($result);
    }
}