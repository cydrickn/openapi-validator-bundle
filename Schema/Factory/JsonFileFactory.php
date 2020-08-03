<?php

declare(strict_types=1);

namespace Cydrickn\OpenApiValidatorBundle\Schema\Factory;

use cebe\openapi\Reader;
use League\OpenAPIValidation\PSR7\SchemaFactory\FileFactory;
use Cydrickn\OpenApiValidatorBundle\Schema\Schema;

class JsonFileFactory extends FileFactory
{
    public function createSchema(): Schema
    {
        return Reader::readFromJsonFile(realpath($this->getFilename()), Schema::class);
    }
}