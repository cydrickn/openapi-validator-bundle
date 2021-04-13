<?php


namespace Cydrickn\OpenApiValidatorBundle\EventListener\Condition;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\RequestEvent;

/**
 * @author Ashleigh Kaffenberger <akaffenberger@gmail.com>
 */
class QueryParameterCondition implements Condition
{
    private string $name;
    private string $value;

    /**
     * QueryParameterCondition constructor.
     * @param array $info
     */
    public function __construct(array $info)
    {
        $this->name = $info['name'];
        $this->value = $info['value'];
    }

    /**
     * @param Request $request
     * @return bool
     */
    public function enabled(Request $request): bool
    {
        return $request->query->get($this->name, null) === $this->value;
    }
}