<?php

declare(strict_types=1);

namespace Cydrickn\OpenApiValidatorBundle\Schema;

use cebe\openapi\spec\OpenApi;
use League\OpenAPIValidation\PSR7\OperationAddress;
use League\OpenAPIValidation\PSR7\PathFinder;
use Psr\Http\Message\RequestInterface;

class Schema extends OpenApi
{
    public function findMatchingOperations(RequestInterface $request): ?OperationAddress
    {
        $pathFinder = new PathFinder($this, (string)$request->getUri(), $request->getMethod());
        $matchingOperationsAddrs = $pathFinder->search();

        if (count($matchingOperationsAddrs) === 1) {
            return $matchingOperationsAddrs[0];
        }

        return null;
    }
}
