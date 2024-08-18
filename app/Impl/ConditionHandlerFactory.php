<?php

namespace App\Impl;

use App\Exceptions\ConditionRuleHandlerNotFoundException;
use App\Interfaces\CommandInterface;
use App\Interfaces\ConditionHandlerFactoryInterface;
use App\Interfaces\ConditionTypeInterface;
use App\Interfaces\SupportedHttpRequestInterface;
use function Widmogrod\Monad\Control\Doo\in;

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
        $this->checkClassExists($fullNameClass);

        return new $fullNameClass($params, $this->request);
    }

    private function checkClassExists(string $className): void
    {
        if (!class_exists($className)) {
            throw new ConditionRuleHandlerNotFoundException("Condition handler $className does not exist");
        }
    }
}
