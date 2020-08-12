<?php

declare(strict_types=1);

namespace Cydrickn\OpenApiValidatorBundle\Schema\Factory;

use Cydrickn\OpenApiValidatorBundle\Schema\Schema;
use cebe\openapi\ReferenceContext;
use Symfony\Component\Yaml\Yaml;

class YamlDirFactory extends DirFactory
{
    public function createSchema(): Schema
    {
        $files = $this->getFiles('yaml');
        $spec = $this->combineData($files, function ($file): array {
            $content = file_get_contents($file);

            return Yaml::parse($content);
        });
        $schema = new Schema($spec);
        $schema->setReferenceContext(new ReferenceContext($schema, $this->getDirectoryName()));
        $schema->resolveReferences();

        return $schema;
    }
}