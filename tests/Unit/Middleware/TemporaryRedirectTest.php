<?php

namespace Tests\Unit\Middleware;

use App\Http\Middleware\Response_307_TemporaryRedirect_Middleware;
use App\Interfaces\SmartLinkRedirectServiceInterface;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Mockery;
use Tests\TestCase;

class TemporaryRedirectTest extends TestCase
{
    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function testHandleRedirectsWhenTargetUrlIsProvided()
    {
        $targetUrl = 'https://example.com';

        $smartLinkRedirectService = Mockery::mock(SmartLinkRedirectServiceInterface::class);
        $smartLinkRedirectService->shouldReceive('evaluate')->once()->andReturn($targetUrl);

        $middleware = new Response_307_TemporaryRedirect_Middleware($smartLinkRedirectService);

        $request = new Request();
        $response = $middleware->handle($request, function () {
            $this->fail('Next middleware should not be called');
        });

        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(302, $response->getStatusCode());
        $this->assertEquals($targetUrl, $response->headers->get('Location'));
    }

    public function testHandleCallsNextMiddlewareWhenNoTargetUrl()
    {
        $smartLinkRedirectService = Mockery::mock(SmartLinkRedirectServiceInterface::class);
        $smartLinkRedirectService->shouldReceive('evaluate')->once()->andReturn('');

        $middleware = new Response_307_TemporaryRedirect_Middleware($smartLinkRedirectService);

        $request = new Request();
        $response = new Response();

        $nextCalled = false;
        $next = function ($req) use (&$nextCalled, $response) {
            $nextCalled = true;
            return $response;
        };

        $result = $middleware->handle($request, $next);

        $this->assertTrue($nextCalled, 'Next middleware was not called');
        $this->assertSame($response, $result);
    }

    public function testMiddlewareUsesCorrectRedirectStatusCode()
    {
        $targetUrl = 'https://example.com';

        $smartLinkRedirectService = Mockery::mock(SmartLinkRedirectServiceInterface::class);
        $smartLinkRedirectService->shouldReceive('evaluate')->once()->andReturn($targetUrl);

        $middleware = new Response_307_TemporaryRedirect_Middleware($smartLinkRedirectService);

        $request = new Request();
        $response = $middleware->handle($request, function () {});

        $this->assertEquals(302, $response->getStatusCode(), 'Incorrect redirect status code');
    }
}
