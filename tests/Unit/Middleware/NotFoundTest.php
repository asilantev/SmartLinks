<?php

namespace Tests\Unit\Middleware;

use App\Http\Middleware\Response_404_NotFound_Middleware;
use App\Interfaces\StatableSmartLinkInterface;
use App\Interfaces\StatableSmartLinkRepositoryInterface;
use App\Interfaces\SupportedHttpRequestInterface;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Mockery;
use Tests\TestCase;

class NotFoundTest extends TestCase
{
    protected StatableSmartLinkRepositoryInterface $repositoryMock;
    protected Response_404_NotFound_Middleware $middleware;

    protected function setUp(): void
    {
        parent::setUp();

        $this->repositoryMock = Mockery::mock(StatableSmartLinkRepositoryInterface::class);
        $this->middleware = new Response_404_NotFound_Middleware($this->repositoryMock);
    }

    public function test_returns_404_when_link_is_null()
    {
        $this->repositoryMock->shouldReceive('read')->once()->andReturnNull();

        $request = Request::create('/');
        $next = function ($request) { return new Response('OK'); };

        $response = $this->middleware->handle($request, $next);

        $this->assertEquals(Response::HTTP_NOT_FOUND, $response->getStatusCode());
        $this->assertEquals('', $response->getContent());
    }

    public function test_returns_404_when_link_has_no_active_rules()
    {
        $linkMock = Mockery::mock(StatableSmartLinkInterface::class);
        $linkMock->shouldReceive('hasActiveRules')->once()->andReturn(false);

        $this->repositoryMock->shouldReceive('read')->once()->andReturn($linkMock);

        $request = Request::create('/');
        $next = function ($request) { return new Response('OK'); };

        $response = $this->middleware->handle($request, $next);

        $this->assertEquals(Response::HTTP_NOT_FOUND, $response->getStatusCode());
        $this->assertEquals('', $response->getContent());
    }

    public function test_passes_request_to_next_middleware_when_link_has_active_rules()
    {
        $linkMock = Mockery::mock(StatableSmartLinkInterface::class);
        $linkMock->shouldReceive('hasActiveRules')->once()->andReturn(true);

        $this->repositoryMock->shouldReceive('read')->once()->andReturn($linkMock);

        $request = Request::create('/');
        $next = function ($request) { return new Response('OK'); };

        $response = $this->middleware->handle($request, $next);

        $this->assertEquals('OK', $response->getContent());
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}
