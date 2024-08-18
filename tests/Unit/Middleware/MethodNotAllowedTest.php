<?php

namespace Tests\Unit\Middleware;

use App\Http\Middleware\Response_405_MethodNotAllowed_Middleware;
use App\Interfaces\SupportedHttpRequestInterface;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Mockery;
use Tests\TestCase;

class MethodNotAllowedTest extends TestCase
{

    private SupportedHttpRequestInterface $supportedHttpRequestMock;
    private Response_405_MethodNotAllowed_Middleware $middleware;

    protected function setUp(): void
    {
        parent::setUp();

        $this->supportedHttpRequestMock = \Mockery::mock(SupportedHttpRequestInterface::class);
        $this->middleware = new Response_405_MethodNotAllowed_Middleware($this->supportedHttpRequestMock);
    }

    public function test_returns_405_when_method_not_supported(): void
    {
        $this->supportedHttpRequestMock->shouldReceive('methodIsSupported')->once()->andReturn(false);
        $request = Request::create('/');
        $next = function ($request) { return new Response('OK'); };
        $response = $this->middleware->handle($request, $next);

        $this->assertEquals(Response::HTTP_METHOD_NOT_ALLOWED, $response->getStatusCode());
        $this->assertEquals('', $response->getContent());
    }

    public function test_passes_request_to_next_middleware_when_method_supported()
    {
        $this->supportedHttpRequestMock->shouldReceive('methodIsSupported')->once()->andReturn(true);

        $request = Request::create('/');
        $next = function ($request) { return new Response('OK'); };

        $response = $this->middleware->handle($request, $next);

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals('OK', $response->getContent());
    }

    public function test_handles_various_http_methods()
    {
        $methods = [
            'GET' => true,
            'POST' => true,
            'PUT' => false,
            'DELETE' => false,
            'XYZZY' => false, // Невалидный метод
        ];

        foreach ($methods as $method => $isSupported) {
            $this->supportedHttpRequestMock->shouldReceive('methodIsSupported')->once()->andReturn($isSupported);

            $request = Request::create('/', $method);
            $next = function ($request) { return new Response('OK'); };

            $response = $this->middleware->handle($request, $next);

            if ($isSupported) {
                $this->assertEquals('OK', $response->getContent());
            } else {
                $this->assertEquals(Response::HTTP_METHOD_NOT_ALLOWED, $response->getStatusCode());
            }
        }
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}
