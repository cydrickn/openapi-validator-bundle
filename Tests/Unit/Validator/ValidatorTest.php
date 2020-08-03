<?php

declare(strict_types=1);

namespace Cydrickn\OpenApiValidatorBundle\Tests\Unit\Validator;

use Cydrickn\OpenApiValidatorBundle\Schema\Factory\JsonFileFactory;
use Cydrickn\OpenApiValidatorBundle\Schema\Schema;
use Cydrickn\OpenApiValidatorBundle\Validator\Errors;
use Cydrickn\OpenApiValidatorBundle\Validator\Validator;
use Cydrickn\OpenApiValidatorBundle\Validator\ValidatorFailed;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ValidatorTest extends TestCase
{
    private function createSchema(): Schema
    {
        $factory = new JsonFileFactory(dirname(__DIR__) . '/../assets/petstore.json');

        return $factory->createSchema();
    }

    /**
     * @dataProvider dataValidateRequestWithoutException
     */
    public function testValidateRequestWithoutException(string $method, string $uri, array $headers, ?string $data = null)
    {
        $validator = new Validator($this->createSchema());
        $server = [];
        foreach ($headers as $header => $value) {
            $formattedHeader = str_replace('-', '_', strtoupper($header));
            $server[$formattedHeader] = $value;
        }
        $request = Request::create($uri, $method, [], [], [], $server, $data);
        $validator->validateRequest($request);
        $this->assertTrue(true);
    }

    public function dataValidateRequestWithoutException()
    {
        yield ['GET', '/api/v3/pet/1', ['Content-Type' => 'application/json']];
        yield ['POST', '/api/v3/pet', ['Content-Type' => 'application/json'], json_encode([
            'name' => 'test',
            'photoUrls' => ['test'],
        ])];
    }

    /**
     * @dataProvider dataValidateRequestWithException
     */
    public function testValidateRequestWithException(string $method, string $uri, array $headers, ?string $data = null, string $expectedMessage)
    {
        $this->expectException(ValidatorFailed::class);
        $this->expectExceptionCode(Errors::ERROR_REQUEST);
        $this->expectExceptionMessage($expectedMessage);
        $validator = new Validator($this->createSchema());
        $server = [];
        foreach ($headers as $header => $value) {
            $formattedHeader = 'HTTP_' . str_replace('-', '_', strtoupper($header));
            $server[$formattedHeader] = $value;
        }
        $request = Request::create($uri, $method, [], [], [], $server, $data);
        $validator->validateRequest($request);
    }

    public function dataValidateRequestWithException()
    {
        yield ['GET', '/api/v3/pet/a', ['Content-Type' => 'application/json'], null, 'Parameter \'petId\' has invalid value \'a\''];
        yield ['POST', '/api/v3/pet', ['Content-Type' => 'application/json'], json_encode([]), 'Keyword validation failed: Required property \'name\' must be present in the object'];
    }

    /**
     * @dataProvider dataValidateResponseWithoutException
     */
    public function testValidateResponseWithoutException(string $method, string $uri, Response $response)
    {
        $validator = new Validator($this->createSchema());
        $request = Request::create($uri, $method);
        $validator->validateResponse($request, $response);
        $this->assertTrue(true);
    }

    public function dataValidateResponseWithoutException()
    {
        yield ['GET', '/api/v3/pet/1', new JsonResponse([
            'id' => 10,
            'name' => 'doggie',
            'category' => ['id' => 1, 'name' => 'Dogs'],
            'photoUrls' => ['dogphoto'],
            'tags' => [['id' => 0, 'name' => 'string']],
            'status' => 'available'
        ])];
        yield ['POST', '/api/v3/pet', new JsonResponse([
            'id' => 10,
            'name' => 'doggie',
            'category' => ['id' => 1, 'name' => 'Dogs'],
            'photoUrls' => ['dogphoto'],
            'tags' => [['id' => 0, 'name' => 'string']],
            'status' => 'available'
        ])];
    }

    /**
     * @dataProvider dataValidateResponseWithException
     */
    public function testValidateResponseWithException(string $method, string $uri, Response $response, string $expectedMessage)
    {
        $this->expectException(ValidatorFailed::class);
        $this->expectExceptionCode(Errors::ERROR_RESPONSE);
        $this->expectExceptionMessage($expectedMessage);

        $validator = new Validator($this->createSchema());
        $request = Request::create($uri, $method);
        $validator->validateResponse($request, $response);
        $this->assertTrue(true);
    }

    public function dataValidateResponseWithException()
    {
        yield ['GET', '/api/v3/pet/1', new JsonResponse([
            'id' => 10,
            'name' => 'doggie',
        ]), 'Keyword validation failed: Required property \'photoUrls\' must be present in the object'];
        yield ['POST', '/api/v3/pet', new JsonResponse([
            'id' => 10,
            'category' => ['id' => 1, 'name' => 'Dogs'],
            'photoUrls' => ['dogphoto'],
            'tags' => [['id' => 0, 'name' => 'string']],
            'status' => 'available'
        ]), 'Keyword validation failed: Required property \'name\' must be present in the object'];
    }
}