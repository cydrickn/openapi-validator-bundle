<?php

declare(strict_types=1);

namespace Cydrickn\OpenApiValidatorBundle\Schema\Factory;

use cebe\openapi\spec\OpenApi;
use League\OpenAPIValidation\PSR7\SchemaFactory;

class NelmioFactory implements SchemaFactory
{
    public function createSchema(): OpenApi
    {
        // TODO: Implement createSchema() method.
    }
}