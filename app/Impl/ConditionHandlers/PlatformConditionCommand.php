<?php

namespace App\Impl\ConditionHandlers;

use App\Exceptions\ConditionRuleHandlerProcessException;
use App\Interfaces\CommandInterface;
use App\Interfaces\SupportedHttpRequestInterface;

class PlatformConditionCommand implements CommandInterface
{
    public function __construct(private \stdClass $params, private SupportedHttpRequestInterface $request)
    {
    }

    /**
     * @throws ConditionRuleHandlerProcessException
     */
    public function execute(): void
    {
        if (mb_strpos($this->request->getPlatform(), $this->params->value) === false) {
            throw new ConditionRuleHandlerProcessException();
        }
    }
}
