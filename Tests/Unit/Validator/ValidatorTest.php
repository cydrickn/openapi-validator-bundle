<?php

declare(strict_types=1);

namespace Cydrickn\OpenApiValidatorBundle\Tests\Unit\Validator;

use Cydrickn\OpenApiValidatorBundle\Schema\Factory\JsonFileFactory;
use Cydrickn\OpenApiValidatorBundle\Schema\Schema;
use Cydrickn\OpenApiValidatorBundle\Validator\Errors;
use Cydrickn\OpenApiValidatorBundle\Validator\Validator;
use Cydrickn\OpenApiValidatorBundle\Validator\ValidatorFailed;
use Cydrickn\OpenApiValidatorBundle\Tests\Unit\TestCase;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ValidatorTest extends TestCase
{
    private function createSchema(): Schema
    {
        $spec = array_merge_recursive(
            require $this->getAssetPath('spec.php'),
            require $this->getAssetPath('spec-other.php')
        );

        return new Schema($spec);
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
        yield ['GET', '/names', ['Content-Type' => 'application/json']];
        yield ['GET', '/names/1', ['Content-Type' => 'application/json']];
        yield ['POST', '/names', ['Content-Type' => 'application/json'], json_encode([
            'name' => 'test',
            'lang' => 'en',
            'country' => 'ph',
            'type' => 'last'
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
            if (strtolower($header) === 'content-type') {
                $server['CONTENT_TYPE'] = $value;
            }
        }

        $request = Request::create($uri, $method, [], [], [], $server, $data);
        $validator->validateRequest($request);
    }

    public function dataValidateRequestWithException()
    {
        yield [
            'GET',
            '/names/a',
            ['Content-Type' => 'application/json'],
            null,
            'Parameter \'id\' has invalid value \'a\''
        ];
        yield [
            'POST',
            '/names',
            ['Content-Type' => 'application/json'],
            json_encode([]),
            'Keyword validation failed: Required property \'name\' must be present in the object'
        ];
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
        yield ['GET', '/names/1', new JsonResponse([
            'data' => [
                'id' => 1,
                'name' => 'test',
                'lang' => 'en',
                'country' => 'ph',
                'type' => 'last'
            ],
            'meta' => ['type' => 'name'],
        ])];
        yield ['POST', '/names', new JsonResponse([
            'data' => [
                'id' => 1,
                'name' => 'test',
                'lang' => 'en',
                'country' => 'ph',
                'type' => 'last'
            ],
            'meta' => ['type' => 'name'],
        ], JsonResponse::HTTP_CREATED)];
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
        yield ['GET', '/names/1', new JsonResponse([
            'id' => 10,
            'name' => 'tes',
        ]), 'Keyword validation failed: Required property \'data\' must be present in the object'];
        yield [
            'POST',
            '/names',
            new JsonResponse([]),
            'OpenAPI spec contains no such operation [/names,post,200]'
        ];
    }
}