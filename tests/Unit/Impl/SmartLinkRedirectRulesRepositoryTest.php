<?php

namespace Tests\Unit\Impl;

use App\Impl\SmartLinkRedirectRulesRepository;
use App\Interfaces\DbRepositoryInterface;
use App\Interfaces\RedirectRuleInterface;
use App\Models\RedirectRule;
use App\Models\SmartLink;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Collection;
use Mockery;
use Tests\TestCase;

class SmartLinkRedirectRulesRepositoryTest extends TestCase
{
    use DatabaseTransactions;


    public function testCanReadEmptyRuleCollectionWhenNoModel()
    {
        $dbRepositoryMock = Mockery::mock(DbRepositoryInterface::class);
        $dbRepositoryMock->shouldReceive('read')->andReturnNull();

        $repository = new SmartLinkRedirectRulesRepository($dbRepositoryMock);

        $ruleCollection = $repository->read();

        $this->assertInstanceOf(Collection::class, $ruleCollection);
        $this->assertEmpty($ruleCollection);
    }

    public function testCanReadRuleCollectionWithActiveRedirectRules()
    {
        $model = SmartLink::factory()->has(
            RedirectRule::factory()->count(2)->state(['is_active' => true]),
            'redirectRules'
        )->create();

        $dbRepositoryMock = Mockery::mock(DbRepositoryInterface::class);
        $dbRepositoryMock->shouldReceive('read')->andReturn($model);

        $repository = new SmartLinkRedirectRulesRepository($dbRepositoryMock);

        $ruleCollection = $repository->read();

        $this->assertCount(2, $ruleCollection);
        $this->assertInstanceOf(RedirectRuleInterface::class, $ruleCollection->first());
    }
}
