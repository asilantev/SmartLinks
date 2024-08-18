<?php

namespace Tests\Unit\Impl;

use App\Impl\RedirectRule;
use App\Interfaces\RuleConditionInterface;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Models\RedirectRule as RedirectRuleModel;
use Illuminate\Support\Collection;
use Mockery;
use Tests\TestCase;

class RedirectRuleTest extends TestCase
{
    use DatabaseTransactions;

    public function testGetTargetUrlReturnsTargetUrlAsString()
    {
        $targetUrl = 'https://example.com';
        $model = RedirectRuleModel::factory()->make(['target_url' => $targetUrl]);
        $redirectRule = new RedirectRule($model);
        $result = $redirectRule->getTargetUrl();
        $this->assertEquals($targetUrl, $result);
    }


    public function testGetConditions_returns_empty_collection_when_no_conditions()
    {
        $model = RedirectRuleModel::factory()->make(['conditions' => []]);
        $redirectRule = new RedirectRule($model);
        $result = $redirectRule->getConditions();
        $this->assertInstanceOf(Collection::class, $result);
        $this->assertEmpty($result);
    }
}
