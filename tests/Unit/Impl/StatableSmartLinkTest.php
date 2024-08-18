<?php

namespace Tests\Unit\Impl;

use App\Impl\StatableSmartLink;
use App\Interfaces\StatableSmartLinkInterface;
use App\Models\RedirectRule;
use App\Models\SmartLink;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Mockery;
use Tests\TestCase;

class StatableSmartLinkTest extends TestCase
{
    protected Model $modelMock;
    protected StatableSmartLinkInterface $statableSmartLink;

    protected function setUp(): void
    {
        parent::setUp();

        $this->modelMock = Mockery::mock(SmartLink::class);
        $this->statableSmartLink = new StatableSmartLink($this->modelMock);
    }

    public function test_get_slug()
    {
        $expectedSlug = 'test-slug';
        $this->modelMock->shouldReceive('getAttribute')->once()->with('slug')->andReturn($expectedSlug);
        $result = $this->statableSmartLink->getSlug();

        $this->assertEquals($expectedSlug, $result);
    }

    public function test_has_active_rules_when_active()
    {
        $redirectRulesQuery = Mockery::mock('query');
        $redirectRulesQuery->shouldReceive('firstWhere')->with('is_active', '=', 1)->andReturn(new RedirectRule());

        $this->modelMock->shouldReceive('redirectRules')->andReturn($redirectRulesQuery);
        $this->assertTrue($this->statableSmartLink->hasActiveRules());
    }

    public function test_has_active_rules_when_inactive()
    {
        $redirectRulesQuery = Mockery::mock('query');
        $redirectRulesQuery->shouldReceive('firstWhere')->with('is_active', '=', 1)->andReturn(null);

        $this->modelMock->shouldReceive('redirectRules')->andReturn($redirectRulesQuery);
        $this->assertFalse($this->statableSmartLink->hasActiveRules());
    }

    public function test_get_expires_at_with_valid_date()
    {
        $expirationDate = '2025-02-03 19:54:57';
        $this->modelMock->shouldReceive('getAttribute')->with('expires_at')->andReturn($expirationDate);
        $result = $this->statableSmartLink->getExpiresAt();
        $this->assertInstanceOf(Carbon::class, $result);
        $this->assertEquals($expirationDate, $result->format('Y-m-d H:i:s'));
    }

    public function test_get_expires_at_with_null_date()
    {
        $this->modelMock->shouldReceive('getAttribute')->with('expires_at')->andReturn(null);
        $result = $this->statableSmartLink->getExpiresAt();
        $this->assertNull($result);
    }

    public function test_get_default_url()
    {
        $expectedDefaultUrl = 'http://test.ru';
        $this->modelMock->shouldReceive('getAttribute')->once()->with('default_url')->andReturn($expectedDefaultUrl);
        $result = $this->statableSmartLink->getDefaultUrl();

        $this->assertEquals($expectedDefaultUrl, $result);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}
