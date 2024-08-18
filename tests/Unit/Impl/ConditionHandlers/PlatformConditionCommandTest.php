<?php

namespace Tests\Unit\Impl\ConditionHandlers;

use App\Exceptions\ConditionRuleHandlerNotFoundException;
use App\Exceptions\ConditionRuleHandlerProcessException;
use App\Impl\ConditionHandlers\BrowserConditionCommand;
use App\Impl\ConditionHandlers\PlatformConditionCommand;
use App\Interfaces\CommandInterface;
use App\Interfaces\ConditionHandlerFactoryInterface;
use App\Interfaces\ConditionTypeInterface;
use App\Interfaces\SupportedHttpRequestInterface;
use stdClass;
use Tests\TestCase;

class PlatformConditionCommandTest extends TestCase
{
    private $mockRequest;

    protected function setUp(): void
    {
        parent::setUp();
        $this->mockRequest = $this->createMock(SupportedHttpRequestInterface::class);
    }

    public function testExecuteWithMatchingPlatform()
    {
        $params = new stdClass();
        $params->value = 'Windows';

        $this->mockRequest->expects($this->once())
            ->method('getPlatform')
            ->willReturn('Windows 10');

        $command = new PlatformConditionCommand($params, $this->mockRequest);

        try {
            $command->execute();
            $this->assertTrue(true); // Если не было исключения, тест пройден
        } catch (ConditionRuleHandlerProcessException $e) {
            $this->fail('ConditionRuleHandlerProcessException was thrown unexpectedly');
        }
    }

    public function testExecuteWithNonMatchingPlatform()
    {
        $params = new stdClass();
        $params->value = 'macOS';

        $this->mockRequest->expects($this->once())
            ->method('getPlatform')
            ->willReturn('Windows 10');

        $command = new PlatformConditionCommand($params, $this->mockRequest);

        $this->expectException(ConditionRuleHandlerProcessException::class);
        $command->execute();
    }

    public function testExecuteWithEmptyPlatformString()
    {
        $params = new stdClass();
        $params->value = 'Windows';

        $this->mockRequest->expects($this->once())
            ->method('getPlatform')
            ->willReturn('');

        $command = new PlatformConditionCommand($params, $this->mockRequest);

        $this->expectException(ConditionRuleHandlerProcessException::class);
        $command->execute();
    }

    public function testExecuteWithCaseSensitivity()
    {
        $params = new stdClass();
        $params->value = 'Windows';

        $this->mockRequest->expects($this->once())
            ->method('getPlatform')
            ->willReturn('Windows 10');

        $command = new PlatformConditionCommand($params, $this->mockRequest);

        try {
            $command->execute();
            $this->assertTrue(true); // Если не было исключения, тест пройден
        } catch (ConditionRuleHandlerProcessException $e) {
            $this->fail('ConditionRuleHandlerProcessException was thrown unexpectedly');
        }
    }

    public function testExecuteWithPartialMatch()
    {
        $params = new stdClass();
        $params->value = 'Win';

        $this->mockRequest->expects($this->once())
            ->method('getPlatform')
            ->willReturn('Windows 10');

        $command = new PlatformConditionCommand($params, $this->mockRequest);

        try {
            $command->execute();
            $this->assertTrue(true); // Если не было исключения, тест пройден
        } catch (ConditionRuleHandlerProcessException $e) {
            $this->fail('ConditionRuleHandlerProcessException was thrown unexpectedly');
        }
    }
}
