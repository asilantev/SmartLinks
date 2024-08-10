<?php

namespace Tests\Unit\Impl;

use App\Impl\StatableSmartLink;
use App\Impl\SupportedHttpRequest;
use App\Interfaces\StatableSmartLinkInterface;
use App\Interfaces\SupportedHttpRequestInterface;
use App\Models\RedirectRule;
use App\Models\SmartLink;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Mockery;
use Tests\TestCase;

class SupportedHttpRequestTest extends TestCase
{
    protected Request $requestMock;
    protected SupportedHttpRequestInterface $supportedHttpRequest;
    protected function setUp(): void
    {
        parent::setUp();
        $this->requestMock = Mockery::mock(Request::class);
        $this->supportedHttpRequest = new SupportedHttpRequest($this->requestMock);
    }

    public function test_method_is_supported_returns_true_for_get_request()
    {
        $this->requestMock->shouldReceive('getMethod')->once()->andReturn(Request::METHOD_GET);
        $this->assertTrue($this->supportedHttpRequest->methodIsSupported());
    }

    public function test_method_is_supported_returns_false_for_post_request()
    {
        $this->requestMock->shouldReceive('getMethod')->once()->andReturn(Request::METHOD_POST);
        $this->assertFalse($this->supportedHttpRequest->methodIsSupported());
    }

    public function test_method_is_supported_returns_false_for_put_request()
    {
        $this->requestMock->shouldReceive('getMethod')->once()->andReturn(Request::METHOD_PUT);
        $this->assertFalse($this->supportedHttpRequest->methodIsSupported());
    }

    public function test_get_request_returns_original_request_object()
    {
        $this->assertSame($this->requestMock, $this->supportedHttpRequest->getRequest());
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}
