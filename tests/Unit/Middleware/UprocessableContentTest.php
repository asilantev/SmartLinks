<?php

namespace Tests\Unit\Middleware;

use App\Http\Middleware\Response_422_UprocessableContent_Middleware;
use App\Interfaces\FreezeSmartLinkServiceInterface;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Mockery;
use Tests\TestCase;

class UprocessableContentTest extends TestCase
{
    protected FreezeSmartLinkServiceInterface $freezeSmartLinkServiceMock;
    protected Response_422_UprocessableContent_Middleware $middleware;

    protected function setUp(): void
    {
        parent::setUp();

        $this->freezeSmartLinkServiceMock = Mockery::mock(FreezeSmartLinkServiceInterface::class);
        $this->middleware = new Response_422_UprocessableContent_Middleware($this->freezeSmartLinkServiceMock);
    }

    public function test_returns_422_when_smart_link_should_be_frozen()
    {
        $this->freezeSmartLinkServiceMock->shouldReceive('shouldSmartLinkBeFreezed')->once()->andReturn(true);

        $request = Request::create('/');
        $next = function ($request) { return new Response('OK'); };

        $response = $this->middleware->handle($request, $next);

        $this->assertEquals(Response::HTTP_UNPROCESSABLE_ENTITY, $response->getStatusCode());
        $this->assertEquals('', $response->getContent());
    }

    public function test_passes_request_to_next_middleware_when_smart_link_should_not_be_frozen()
    {
        $this->freezeSmartLinkServiceMock->shouldReceive('shouldSmartLinkBeFreezed')->once()->andReturn(false);

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
