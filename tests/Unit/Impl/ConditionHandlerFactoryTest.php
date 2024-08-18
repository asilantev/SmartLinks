<?php

namespace Tests\Unit\Impl;

use App\Exceptions\ConditionRuleHandlerNotFoundException;
use App\Interfaces\CommandInterface;
use App\Interfaces\ConditionHandlerFactoryInterface;
use App\Interfaces\ConditionTypeInterface;
use App\Interfaces\SupportedHttpRequestInterface;
use Tests\TestCase;

class ConditionHandlerFactoryTest extends TestCase
{
    private SupportedHttpRequestInterface $mockRequest;
    private ConditionHandlerFactoryInterface $factory;

    protected function setUp(): void
    {
        parent::setUp();
        $this->mockRequest = $this->createMock(SupportedHttpRequestInterface::class);
        $this->factory = app(ConditionHandlerFactoryInterface::class, ['request' => $this->mockRequest]);
    }

    public function testCreateWithValidConditionType()
    {
        $mockConditionType = $this->createMock(ConditionTypeInterface::class);
        $mockConditionType->method('getCode')->willReturn('test_condition');

        app()->bind('Namespace.ConditionHandlers', function () {
            return 'App\\ConditionHandlers\\';
        });

        $stubClass = new class() implements CommandInterface {
            public function __construct($params = null, SupportedHttpRequestInterface $request = null) {}
            public function execute(): void {}
        };

        class_alias(get_class($stubClass), 'App\\ConditionHandlers\\TestConditionConditionCommand');

        $this->app->bind('App\\ConditionHandlers\\TestConditionConditionCommand', function() use ($stubClass) {
            return $stubClass;
        });

        $result = $this->factory->create($mockConditionType);

        $this->assertInstanceOf('App\\ConditionHandlers\\TestConditionConditionCommand', $result);
    }

    public function testCreateWithInvalidConditionType()
    {
        $mockConditionType = $this->createMock(ConditionTypeInterface::class);
        $mockConditionType->method('getCode')->willReturn('invalid_condition');

        app()->bind('Namespace.ConditionHandlers', function () {
            return 'App\\ConditionHandlers\\';
        });

        $this->expectException(ConditionRuleHandlerNotFoundException::class);
        $this->expectExceptionMessage('Condition handler App\\ConditionHandlers\\InvalidConditionConditionCommand does not exist');

        $this->factory->create($mockConditionType);
    }

    public function testCreateWithNumericPrefix()
    {
        $mockConditionType = $this->createMock(ConditionTypeInterface::class);
        $mockConditionType->method('getCode')->willReturn('123test_condition');

        app()->bind('Namespace.ConditionHandlers', function () {
            return 'App\\ConditionHandlers\\';
        });

        $mockCommand = $this->getMockBuilder('App\\ConditionHandlers\\TestConditionConditionCommand')
            ->disableOriginalConstructor()
            ->getMock();

        $this->app->instance('App\\ConditionHandlers\\TestConditionConditionCommand', $mockCommand);

        $result = $this->factory->create($mockConditionType);

        $this->assertInstanceOf('App\\ConditionHandlers\\TestConditionConditionCommand', $result);
    }
}
