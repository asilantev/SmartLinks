<?php

namespace Tests\Unit\Impl;

use App\Impl\FreezeSmartLinkService;
use App\Interfaces\FreezeSmartLinkServiceInterface;
use App\Interfaces\StatableSmartLinkInterface;
use App\Interfaces\StatableSmartLinkRepositoryInterface;
use Illuminate\Support\Carbon;
use Mockery;
use Tests\TestCase;

class FreezeSmartLinkServiceTest extends TestCase
{
    protected StatableSmartLinkRepositoryInterface $repositoryMock;
    protected FreezeSmartLinkServiceInterface $service;

    protected function setUp(): void
    {
        parent::setUp();

        $this->repositoryMock = Mockery::mock(StatableSmartLinkRepositoryInterface::class);
        $this->service = new FreezeSmartLinkService($this->repositoryMock);
    }

    public function test_should_freeze_smart_link_when_expired()
    {
        $smartLinkMock = Mockery::mock(StatableSmartLinkInterface::class);
        $smartLinkMock->shouldReceive('getExpiresAt')->once()->andReturn(Carbon::now()->subDay());
        $smartLinkMock->shouldNotReceive('hasActiveRules');

        $this->repositoryMock->shouldReceive('read')->once()->andReturn($smartLinkMock);

        $result = $this->service->shouldSmartLinkBeFreezed();

        $this->assertTrue($result);
    }

    public function test_should_freeze_smart_link_when_no_active_rules()
    {
        $smartLinkMock = Mockery::mock(StatableSmartLinkInterface::class);
        $smartLinkMock->shouldReceive('getExpiresAt')->once()->andReturn(Carbon::now()->addDay());
        $smartLinkMock->shouldReceive('hasActiveRules')->once()->andReturn(false);

        $this->repositoryMock->shouldReceive('read')->once()->andReturn($smartLinkMock);

        $result = $this->service->shouldSmartLinkBeFreezed();

        $this->assertTrue($result);
    }

    public function test_should_not_freeze_smart_link_when_active()
    {
        $smartLinkMock = Mockery::mock(StatableSmartLinkInterface::class);
        $smartLinkMock->shouldReceive('getExpiresAt')->once()->andReturn(Carbon::now()->addDay());
        $smartLinkMock->shouldReceive('hasActiveRules')->once()->andReturn(true);

        $this->repositoryMock->shouldReceive('read')->once()->andReturn($smartLinkMock);

        $result = $this->service->shouldSmartLinkBeFreezed();

        $this->assertFalse($result);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}
