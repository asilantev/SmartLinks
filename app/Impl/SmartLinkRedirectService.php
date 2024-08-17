<?php

namespace App\Impl;

use App\Exceptions\ConditionRuleHandlerNotFoundException;
use App\Exceptions\ConditionRuleHandlerProcessException;
use App\Interfaces\ConditionHandlerFactoryInterface;
use App\Interfaces\RedirectRuleInterface;
use App\Interfaces\RuleConditionInterface;
use App\Interfaces\SmartLinkRedirectRulesRepositoryInterface;
use App\Interfaces\SmartLinkRedirectServiceInterface;
use App\Interfaces\StatableSmartLinkRepositoryInterface;
use App\Models\RuleCondition;

class SmartLinkRedirectService implements SmartLinkRedirectServiceInterface
{
    public function __construct(
        private SmartLinkRedirectRulesRepositoryInterface $redirectRulesRepository,
        private ConditionHandlerFactoryInterface $conditionHandlerFactory,
        private StatableSmartLinkRepositoryInterface $smartLinkRepository
    )
    {
    }


    public function evaluate(): string
    {
        $targetUrl = '';

        $rules = $this->redirectRulesRepository->read();
        /** @var RedirectRuleInterface $rule */
        foreach ($rules as $rule) {
            try {
                $conditionHandlerCommands = [];
                /** @var RuleConditionInterface $condition */
                foreach ($rule->getConditions() as $condition) {
                    $conditionHandlerCommands[] = $this->conditionHandlerFactory->create($condition->getType(), $condition->getValue());
                }
                app(MacroCommand::class, $conditionHandlerCommands)->execute();
                $targetUrl = $rule->getTargetUrl();

                break;
            } catch (ConditionRuleHandlerNotFoundException $e) {
            } catch (ConditionRuleHandlerProcessException $e) {
            }
        }

        if (empty($targetUrl)) {
            $smartLink = $this->smartLinkRepository->read();
            $targetUrl = $smartLink->getDefaultUrl();
        }

        return $targetUrl;
    }
}
