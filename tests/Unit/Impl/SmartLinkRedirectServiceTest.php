<?php

namespace Tests\Unit\Impl;

use App\Exceptions\ConditionRuleHandlerNotFoundException;
use App\Exceptions\ConditionRuleHandlerProcessException;
use App\Impl\MacroCommand;
use App\Impl\SmartLinkRedirectService;
use App\Interfaces\ConditionHandlerFactoryInterface;
use App\Interfaces\RedirectRuleInterface;
use App\Interfaces\RuleConditionInterface;
use App\Interfaces\SmartLinkRedirectRulesRepositoryInterface;
use App\Interfaces\StatableSmartLinkInterface;
use App\Interfaces\StatableSmartLinkRepositoryInterface;
use Illuminate\Support\Collection;
use Tests\TestCase;

class SmartLinkRedirectServiceTest extends TestCase
{
    private $redirectRulesRepository;
    private $conditionHandlerFactory;
    private $smartLinkRepository;
    private $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->redirectRulesRepository = $this->createMock(SmartLinkRedirectRulesRepositoryInterface::class);
        $this->conditionHandlerFactory = $this->createMock(ConditionHandlerFactoryInterface::class);
        $this->smartLinkRepository = $this->createMock(StatableSmartLinkRepositoryInterface::class);
        $this->service = new SmartLinkRedirectService(
            $this->redirectRulesRepository,
            $this->conditionHandlerFactory,
            $this->smartLinkRepository
        );
    }

    public function testEvaluateWithMatchingRule()
    {
        $rule = $this->createMock(RedirectRuleInterface::class);
        $condition = $this->createMock(RuleConditionInterface::class);
        $command = $this->createMock(MacroCommand::class);

        $rule->method('getConditions')->willReturn(new Collection([$condition]));
        $rule->method('getTargetUrl')->willReturn('https://example.com');

        $this->redirectRulesRepository->method('read')->willReturn(new Collection([$rule]));
        $this->conditionHandlerFactory->method('create')->willReturn($command);

        $command->expects($this->once())->method('execute');

        $result = $this->service->evaluate();

        $this->assertEquals('https://example.com', $result);
    }

    public function testEvaluateWithNoMatchingRules()
    {
        $rule = $this->createMock(RedirectRuleInterface::class);
        $condition = $this->createMock(RuleConditionInterface::class);
        $command = $this->createMock(MacroCommand::class);
        $smartLink = $this->createMock(StatableSmartLinkInterface::class);

        $rule->method('getConditions')->willReturn(new Collection([$condition]));
        $smartLink->method('getDefaultUrl')->willReturn('https://default.com');

        $this->redirectRulesRepository->method('read')->willReturn(new Collection([$rule]));
        $this->conditionHandlerFactory->method('create')->willReturn($command);
        $this->smartLinkRepository->method('read')->willReturn($smartLink);

        $command->method('execute')->willThrowException(new ConditionRuleHandlerProcessException());

        $result = $this->service->evaluate();

        $this->assertEquals('https://default.com', $result);
    }

    public function testEvaluateWithConditionRuleHandlerNotFoundException()
    {
        $rule = $this->createMock(RedirectRuleInterface::class);
        $condition = $this->createMock(RuleConditionInterface::class);
        $smartLink = $this->createMock(StatableSmartLinkInterface::class);

        $rule->method('getConditions')->willReturn(new Collection([$condition]));
        $smartLink->method('getDefaultUrl')->willReturn('https://default.com');

        $this->redirectRulesRepository->method('read')->willReturn(new Collection([$rule]));
        $this->conditionHandlerFactory->method('create')->willThrowException(new ConditionRuleHandlerNotFoundException());
        $this->smartLinkRepository->method('read')->willReturn($smartLink);

        $result = $this->service->evaluate();

        $this->assertEquals('https://default.com', $result);
    }

    public function testEvaluateWithMultipleRules()
    {
        $rule1 = $this->createMock(RedirectRuleInterface::class);
        $rule2 = $this->createMock(RedirectRuleInterface::class);
        $condition = $this->createMock(RuleConditionInterface::class);
        $command1 = $this->createMock(MacroCommand::class);
        $command2 = $this->createMock(MacroCommand::class);

        $rule1->method('getConditions')->willReturn(new Collection([$condition]));
        $rule1->method('getTargetUrl')->willReturn('https://example1.com');
        $rule2->method('getConditions')->willReturn(new Collection([$condition]));
        $rule2->method('getTargetUrl')->willReturn('https://example2.com');

        $this->redirectRulesRepository->method('read')->willReturn(new Collection([$rule1, $rule2]));
        $this->conditionHandlerFactory->method('create')
            ->willReturnOnConsecutiveCalls($command1, $command2);

        $command1->method('execute')->willThrowException(new ConditionRuleHandlerProcessException());
        $command2->expects($this->once())->method('execute');

        $result = $this->service->evaluate();

        $this->assertEquals('https://example2.com', $result);
    }
}
