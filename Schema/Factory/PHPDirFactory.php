<?php

declare(strict_types=1);

namespace Cydrickn\OpenApiValidatorBundle\Schema\Factory;

use Cydrickn\OpenApiValidatorBundle\Schema\Schema;
use cebe\openapi\ReferenceContext;

class PHPDirFactory extends DirFactory
{
    public function createSchema(): Schema
    {
        $files = $this->getFiles('php');
        $spec = $this->combineData($files, function ($file): array {
            return require $file;
        });
        $schema = new Schema($spec);
        $schema->setReferenceContext(new ReferenceContext($schema, $this->getDirectoryName()));
        $schema->resolveReferences();

        return $schema;
    }
}