<?php

namespace Tests\Unit\Models;

use App\Models\RedirectRule;
use App\Models\SmartLink;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class SmartLinkTest extends TestCase
{
    use DatabaseTransactions;

    public function test_create_smart_link()
    {
        $smartLink = SmartLink::factory()->create([
            'slug' => 'test-link',
            'default_url' => 'https://example.com',
            'expires_at' => now()->addDays(7),
        ]);

        $this->assertDatabaseHas('smart_links', [
            'id' => $smartLink->id,
            'slug' => 'test-link',
            'default_url' => 'https://example.com',
        ]);

        $this->assertInstanceOf(SmartLink::class, $smartLink);
        $this->assertEquals('test-link', $smartLink->slug);
        $this->assertEquals('https://example.com', $smartLink->default_url);
        $this->assertNotNull($smartLink->expires_at);
    }

    public function test_update_smart_link()
    {
        $smartLink = SmartLink::factory()->create();
        $smartLink->update([
            'slug' => 'updated-slug',
            'default_url' => 'https://updated-example.com',
        ]);

        $this->assertDatabaseHas('smart_links', [
            'id' => $smartLink->id,
            'slug' => 'updated-slug',
            'default_url' => 'https://updated-example.com',
        ]);
    }

    public function test_delete_smart_link()
    {
        $smartLink = SmartLink::factory()->create();
        $smartLink->delete();

        $this->assertDatabaseMissing('smart_links', [
            'id' => $smartLink->id,
        ]);
    }

    public function test_smart_link_redirect_rules_relationship()
    {
        $smartLink = SmartLink::factory()->create();
        $redirectRule = RedirectRule::factory()->create(['smart_link_id' => $smartLink->id]);

        $this->assertTrue($smartLink->redirectRules->contains($redirectRule));
        $this->assertInstanceOf(RedirectRule::class, $smartLink->redirectRules->first());
    }

    public function test_smart_link_expires_at_attribute()
    {
        $expiresAt = now()->addDays(7);
        $smartLink = SmartLink::factory()->create([
            'expires_at' => $expiresAt,
        ]);

        $this->assertInstanceOf(\Carbon\Carbon::class, $smartLink->expires_at);
        $this->assertEquals($expiresAt->toDateTimeString(), $smartLink->expires_at->toDateTimeString());
    }
}
