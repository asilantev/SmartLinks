<?php

namespace App\Impl;

use App\Exceptions\ConditionRuleHandlerNotFoundException;
use App\Interfaces\CommandInterface;
use App\Interfaces\ConditionHandlerFactoryInterface;
use App\Interfaces\ConditionTypeInterface;
use App\Interfaces\SupportedHttpRequestInterface;

class ConditionHandlerFactory implements ConditionHandlerFactoryInterface
{
    public function __construct(private SupportedHttpRequestInterface $request)
    {
    }

    /**
     * @throws ConditionRuleHandlerNotFoundException
     */
    public function create(ConditionTypeInterface $conditionType, mixed $params = null): CommandInterface
    {
        $preparedConditionTypeValue = ltrim($conditionType->getCode(), '\\0..9');
        $classNameExploded = explode('_', $preparedConditionTypeValue);
        $classNameExploded = array_map(fn ($item) => ucfirst(trim($item)), $classNameExploded);
        $className = implode('', $classNameExploded);
        $className = ltrim($className, '\\0..9');

        $fullNameClass = app('Namespace.ConditionHandlers') . "{$className}ConditionCommand";
        if (!class_exists($fullNameClass)) {
            throw new ConditionRuleHandlerNotFoundException("Condition handler $fullNameClass does not exist");
        }

        return new $fullNameClass($params, $this->request);
    }
}
