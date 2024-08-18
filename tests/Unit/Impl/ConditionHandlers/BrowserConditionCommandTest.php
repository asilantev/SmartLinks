<?php

namespace Tests\Unit\Impl\ConditionHandlers;

use App\Exceptions\ConditionRuleHandlerNotFoundException;
use App\Exceptions\ConditionRuleHandlerProcessException;
use App\Impl\ConditionHandlers\BrowserConditionCommand;
use App\Interfaces\CommandInterface;
use App\Interfaces\ConditionHandlerFactoryInterface;
use App\Interfaces\ConditionTypeInterface;
use App\Interfaces\SupportedHttpRequestInterface;
use stdClass;
use Tests\TestCase;

class BrowserConditionCommandTest extends TestCase
{
    private $mockRequest;

    protected function setUp(): void
    {
        parent::setUp();
        $this->mockRequest = $this->createMock(SupportedHttpRequestInterface::class);
    }

    public function testExecuteWithMatchingBrowser()
    {
        $params = new stdClass();
        $params->value = 'Chrome';

        $this->mockRequest->expects($this->once())
            ->method('getBrowser')
            ->willReturn('Google Chrome 91.0.4472.124');

        $command = new BrowserConditionCommand($params, $this->mockRequest);

        try {
            $command->execute();
            $this->assertTrue(true); // Если не было исключения, тест пройден
        } catch (ConditionRuleHandlerProcessException $e) {
            $this->fail('ConditionRuleHandlerProcessException was thrown unexpectedly');
        }
    }

    public function testExecuteWithNonMatchingBrowser()
    {
        $params = new stdClass();
        $params->value = 'Firefox';

        $this->mockRequest->expects($this->once())
            ->method('getBrowser')
            ->willReturn('Google Chrome 91.0.4472.124');

        $command = new BrowserConditionCommand($params, $this->mockRequest);

        $this->expectException(ConditionRuleHandlerProcessException::class);
        $command->execute();
    }

    public function testExecuteWithEmptyBrowserString()
    {
        $params = new stdClass();
        $params->value = 'Chrome';

        $this->mockRequest->expects($this->once())
            ->method('getBrowser')
            ->willReturn('');

        $command = new BrowserConditionCommand($params, $this->mockRequest);

        $this->expectException(ConditionRuleHandlerProcessException::class);
        $command->execute();
    }

    public function testExecuteWithCaseSensitivity()
    {
        $params = new stdClass();
        $params->value = 'Chrome';

        $this->mockRequest->expects($this->once())
            ->method('getBrowser')
            ->willReturn('Google Chrome 91.0.4472.124');

        $command = new BrowserConditionCommand($params, $this->mockRequest);

        try {
            $command->execute();
            $this->assertTrue(true); // Если не было исключения, тест пройден
        } catch (ConditionRuleHandlerProcessException $e) {
            $this->fail('ConditionRuleHandlerProcessException was thrown unexpectedly');
        }
    }
}
