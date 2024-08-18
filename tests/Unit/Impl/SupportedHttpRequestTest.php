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

    public function test_get_platform()
    {
        $testCases = [
            ['header' => 'Windows', 'expected' => 'Windows'],
            ['header' => '"macOS"', 'expected' => 'macOS'],
            ['header' => '"Android"', 'expected' => 'Android'],
            ['header' => '"iOS"', 'expected' => 'iOS'],
            ['header' => '', 'expected' => ''],
        ];

        foreach ($testCases as $case) {
            $request = new Request();
            $request->headers->set('user-agent', $case['header']);

            $supportedRequest = new SupportedHttpRequest($request);

            $this->assertEquals($case['expected'], $supportedRequest->getPlatform(), "Failed asserting that platform '{$case['header']}' is correctly parsed.");
        }
    }

    public function test_get_browser()
    {
        $testCases = [
            ['header' => '"Chromium";v="110", "Not A(Brand";v="24", "Google Chrome";v="110"', 'expected' => 'Chromium";v="110", "Not A(Brand";v="24", "Google Chrome";v="110'],
            ['header' => '"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36"', 'expected' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36'],
            ['header' => '', 'expected' => ''],
        ];

        foreach ($testCases as $case) {
            $request = new Request();
            $request->headers->set('user-agent', $case['header']);

            $supportedRequest = new SupportedHttpRequest($request);

            $this->assertEquals($case['expected'], $supportedRequest->getBrowser(), "Failed asserting that browser '{$case['header']}' is correctly parsed.");
        }
    }

    public function test_get_platform_with_missing_header()
    {
        $request = new Request();
        $supportedRequest = new SupportedHttpRequest($request);

        $this->assertEquals('', $supportedRequest->getPlatform(), "Failed asserting that missing platform header returns empty string.");
    }

    public function test_get_browser_with_missing_header()
    {
        $request = new Request();
        $supportedRequest = new SupportedHttpRequest($request);

        $this->assertEquals('', $supportedRequest->getBrowser(), "Failed asserting that missing browser header returns empty string.");
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}
