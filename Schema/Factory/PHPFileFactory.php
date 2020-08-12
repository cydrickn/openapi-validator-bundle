<?php

declare(strict_types=1);

namespace Cydrickn\OpenApiValidatorBundle\Schema\Factory;

use League\OpenAPIValidation\PSR7\SchemaFactory\FileFactory;
use cebe\openapi\spec\OpenApi;
use Cydrickn\OpenApiValidatorBundle\Schema\Schema;
use cebe\openapi\ReferenceContext;

class PHPFileFactory extends FileFactory
{
    public function createSchema(): Schema
    {
        $fileName = realpath($this->getFilename());
        $spec = require $fileName;
        $schema = new Schema($spec);
        $spec->setReferenceContext(new ReferenceContext($schema, $fileName));
        $spec->resolveReferences();

        return $schema;
    }
}