<?php

namespace Tests\Unit\Impl;

use App\Impl\StatableSmartLink;
use App\Impl\StatableSmartLinkRepository;
use App\Interfaces\DbRepositoryInterface;
use App\Interfaces\StatableSmartLinkInterface;
use App\Interfaces\StatableSmartLinkRepositoryInterface;
use App\Models\RedirectRule;
use App\Models\SmartLink;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Mockery;
use Tests\TestCase;

class StatableSmartLinkRepositoryTest extends TestCase
{
    private DbRepositoryInterface $dbRepositoryMock;
    private StatableSmartLinkRepositoryInterface $statableSmartLinkRepository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->dbRepositoryMock = Mockery::mock(DbRepositoryInterface::class);
        $this->statableSmartLinkRepository = new StatableSmartLinkRepository($this->dbRepositoryMock);
    }

    public function test_read_returns_statable_smart_link_when_model_exists()
    {
        $smartLinkModel = Mockery::mock(SmartLink::class);
        $this->dbRepositoryMock->shouldReceive('read')->once()->andReturn($smartLinkModel);

        $result = $this->statableSmartLinkRepository->read();

        $this->assertInstanceOf(StatableSmartLinkInterface::class, $result);
        $this->assertInstanceOf(StatableSmartLink::class, $result);
    }

    public function test_read_returns_null_when_model_does_not_exist()
    {
        $this->dbRepositoryMock->shouldReceive('read')->once()->andReturn(null);
        $result = $this->statableSmartLinkRepository->read();

        $this->assertNull($result);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}
