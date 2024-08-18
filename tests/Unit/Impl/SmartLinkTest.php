<?php

namespace Tests\Unit\Impl;

use App\Impl\FreezeSmartLinkService;
use App\Impl\SmartLink;
use App\Interfaces\FreezeSmartLinkServiceInterface;
use App\Interfaces\SmartLinkInterface;
use App\Interfaces\StatableSmartLinkInterface;
use App\Interfaces\StatableSmartLinkRepositoryInterface;
use App\Interfaces\SupportedHttpRequestInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Mockery;
use Tests\TestCase;

class SmartLinkTest extends TestCase
{
    protected SupportedHttpRequestInterface $requestMock;
    protected SmartLinkInterface $smartLink;

    protected function setUp(): void
    {
        parent::setUp();

        $this->requestMock = Mockery::mock(SupportedHttpRequestInterface::class);
        $this->smartLink = new SmartLink($this->requestMock);
    }

    public function test_get_value_returns_slug()
    {
        $requestMock = Mockery::mock(Request::class);
        $requestMock->shouldReceive('route')->once()->with('slug')->andReturn('test-slug');

        $this->requestMock->shouldReceive('getRequest')->once()->andReturn($requestMock);

        $result = $this->smartLink->getValue();

        $this->assertEquals('test-slug', $result);
    }
    public function test_get_value_returns_empty_string_when_no_slug()
    {
        $requestMock = Mockery::mock(Request::class);
        $requestMock->shouldReceive('route')->once()->with('slug')->andReturnNull();

        $this->requestMock->shouldReceive('getRequest')->once()->andReturn($requestMock);

        $result = $this->smartLink->getValue();

        $this->assertEquals('', $result);
    }
    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}
