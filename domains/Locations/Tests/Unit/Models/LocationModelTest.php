<?php

namespace Domains\Locations\Tests\Unit\Models;

use Domains\Locations\Database\Factories\LocationFactory;
use Domains\Locations\Models\Location;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class LocationModelTest extends TestCase
{
    private Location $model;

    protected function setUp(): void
    {
        parent::setUp();

        $this->model = LocationFactory::new()->make();
    }

    /** @test */
    public function it_has_required_properties(): void
    {
        self::assertIsInt($this->model->customer_id);
        self::assertNull($this->model->parent_id);
        self::assertNotNull($this->model->name);
    }

    /** @test */
    public function it_uses_timestamps(): void
    {
        self::assertTrue($this->model->usesTimestamps());

        self::assertEquals('created_at', $this->model->getCreatedAtColumn());
        self::assertInstanceOf(Carbon::class, $this->model->created_at);

        self::assertEquals('updated_at', $this->model->getUpdatedAtColumn());
        self::assertInstanceOf(Carbon::class, $this->model->updated_at);
    }

    /** @test */
    public function it_uses_soft_deletes(): void
    {
        self::assertArrayHasKey(SoftDeletingScope::class, $this->model->getGlobalScopes());
    }
}
