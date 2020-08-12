<?php

namespace Cydrickn\OpenApiValidatorBundle\Schema\Factory;

use League\OpenAPIValidation\PSR7\CacheableSchemaFactory;
use Webmozart\Assert\Assert;

abstract class DirFactory implements CacheableSchemaFactory
{
    /** @var string */
    private $dirName;

    public function __construct(string $dirName)
    {
        Assert::directory($dirName);

        $this->dirName = $dirName;
    }

    public function getCacheKey() : string
    {
        return 'openapi_' . crc32(realpath($this->getDirectoryName()));
    }

    protected function getDirectoryName() : string
    {
        return $this->dirName;
    }

    protected function combineData(array $files, callable $callback): array
    {
        $spec = [];
        foreach ($files as $file) {
            $spec = array_merge_recursive($spec, $callback($file));
        }

        return $spec;
    }

    /**
     * Get all files with extension inside the directory
     *
     * @param string $ext
     * @return string[]
     */
    protected function getFiles(string $ext): array
    {
        $pattern = $this->getDirectoryName() . '/*.' . $ext;

        return glob($pattern);
    }
}
