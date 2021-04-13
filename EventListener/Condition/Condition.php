<?php

namespace Cydrickn\OpenApiValidatorBundle\EventListener\Condition;



use Symfony\Component\HttpFoundation\Request;

/**
 * @author Ashleigh Kaffenberger <akaffenberger@gmail.com>
 */
interface Condition
{
    function enabled(Request $request): bool;
}