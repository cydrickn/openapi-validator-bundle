<?php

declare(strict_types=1);

namespace Cydrickn\OpenApiValidatorBundle\Validator;

class Errors
{
    public const ERROR_REQUEST = 1001;
    public const ERROR_RESPONSE = 1002;

    private const RESPONSE_CODES = [
        Errors::ERROR_RESPONSE => 500,
        Errors::ERROR_REQUEST => 400,
    ];

    public static function getResponseCode(int $code): int
    {
        return static::RESPONSE_CODES[$code];
    }
}