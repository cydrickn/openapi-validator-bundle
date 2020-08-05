<?php

declare(strict_types=1);

namespace Cydrickn\OpenApiValidatorBundle\Schema\Factory;

use cebe\openapi\Reader;
use cebe\openapi\spec\OpenApi;
use Cydrickn\OpenApiValidatorBundle\Schema\Schema;
use League\OpenAPIValidation\PSR7\SchemaFactory\FileFactory;

class YamlFileFactory extends FileFactory
{
    public function createSchema(): Schema
    {
        return Reader::readFromYamlFile(realpath($this->getFilename()), Schema::class);
    }
}