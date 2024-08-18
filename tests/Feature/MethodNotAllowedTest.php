<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class MethodNotAllowedTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_ClientSendPostRequest(): void
    {
        $response = $this->post('/post-request');

        $response->assertStatus(405);
    }
}
