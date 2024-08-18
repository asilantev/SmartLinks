<?php

namespace Tests\Unit\Impl\ConditionHandlers;

use App\Exceptions\ConditionRuleHandlerNotFoundException;
use App\Exceptions\ConditionRuleHandlerProcessException;
use App\Impl\ConditionHandlers\BrowserConditionCommand;
use App\Impl\ConditionHandlers\PlatformConditionCommand;
use App\Impl\ConditionHandlers\TimeIntervalConditionCommand;
use App\Interfaces\CommandInterface;
use App\Interfaces\ConditionHandlerFactoryInterface;
use App\Interfaces\ConditionTypeInterface;
use App\Interfaces\SupportedHttpRequestInterface;
use Illuminate\Support\Carbon;
use stdClass;
use Tests\TestCase;

class TimeIntervalConditionCommandTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        Carbon::setTestNow('2023-06-15 12:00:00');
    }

    protected function tearDown(): void
    {
        Carbon::setTestNow();
        parent::tearDown();
    }

    public function testExecuteWithinTimeInterval()
    {
        $params = new stdClass();
        $params->start = '2023-06-15 10:00:00';
        $params->end = '2023-06-15 14:00:00';

        $command = new TimeIntervalConditionCommand($params);

        try {
            $command->execute();
            $this->assertTrue(true);
        } catch (ConditionRuleHandlerProcessException $e) {
            $this->fail('ConditionRuleHandlerProcessException was thrown unexpectedly');
        }
    }

    public function testExecuteBeforeTimeInterval()
    {
        $params = new stdClass();
        $params->start = '2023-06-15 13:00:00';
        $params->end = '2023-06-15 14:00:00';

        $command = new TimeIntervalConditionCommand($params);

        $this->expectException(ConditionRuleHandlerProcessException::class);
        $command->execute();
    }

    public function testExecuteAfterTimeInterval()
    {
        $params = new stdClass();
        $params->start = '2023-06-15 10:00:00';
        $params->end = '2023-06-15 11:00:00';

        $command = new TimeIntervalConditionCommand($params);

        $this->expectException(ConditionRuleHandlerProcessException::class);
        $command->execute();
    }

    public function testExecuteWithExactStartTime()
    {
        $params = new stdClass();
        $params->start = '2023-06-15 12:00:00';
        $params->end = '2023-06-15 14:00:00';

        $command = new TimeIntervalConditionCommand($params);

        $this->expectException(ConditionRuleHandlerProcessException::class);
        $command->execute();
    }

    public function testExecuteWithExactEndTime()
    {
        $params = new stdClass();
        $params->start = '2023-06-15 10:00:00';
        $params->end = '2023-06-15 12:00:00';

        $command = new TimeIntervalConditionCommand($params);

        $this->expectException(ConditionRuleHandlerProcessException::class);
        $command->execute();
    }

    public function testExecuteWithDifferentDates()
    {
        $params = new stdClass();
        $params->start = '2023-06-14 10:00:00';
        $params->end = '2023-06-16 14:00:00';

        $command = new TimeIntervalConditionCommand($params);

        try {
            $command->execute();
            $this->assertTrue(true);
        } catch (ConditionRuleHandlerProcessException $e) {
            $this->fail('ConditionRuleHandlerProcessException was thrown unexpectedly');
        }
    }
}
