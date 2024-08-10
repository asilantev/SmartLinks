<?php

namespace Tests\Unit\Impl;

use App\Impl\DbRepository;
use App\Interfaces\DbRepositoryInterface;
use App\Interfaces\SmartLinkInterface;
use Illuminate\Support\Collection;
use Mockery;
use Tests\TestCase;

class DbRepositoryTest extends TestCase
{
    protected Collection $collection;
    protected SmartLinkInterface $smartLink;
    protected DbRepositoryInterface $repository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->collection = new Collection();
        $this->smartLink = Mockery::mock(SmartLinkInterface::class);
        $this->repository = new DbRepository($this->collection, $this->smartLink);
    }

    public function test_read_returns_null_when_no_match()
    {
        $this->smartLink->shouldReceive('getValue')->andReturn('test-slug');

        $result = $this->repository->read();

        $this->assertNull($result);
    }

    public function test_read_returns_matching_item()
    {
        $expectedModel = ['slug' => 'test-slug', 'default_url' => 'https://test.ru'];
        $this->collection->push($expectedModel);

        $this->smartLink->shouldReceive('getValue')->once()->andReturn('test-slug');

        $result = $this->repository->read();

        $this->assertEquals($expectedModel['slug'], $result['slug']);
        $this->assertEquals($expectedModel['default_url'], $result['default_url']);
    }

    public function test_read_returns_first_matching_item()
    {
        $model1 = ['slug' => 'test-slug', 'default_url' => 'https://test1.ru'];
        $model2 = ['slug' => 'test-slug', 'default_url' => 'https://test2.ru'];
        $this->collection->push($model1, $model2);

        $this->smartLink->shouldReceive('getValue')->andReturn('test-slug');

        $result = $this->repository->read();

        $this->assertEquals($model1['slug'], $result['slug']);
        $this->assertEquals($model1['default_url'], $result['default_url']);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}
