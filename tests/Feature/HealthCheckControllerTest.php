<?php

namespace Tests\Feature;

use Laravel\Lumen\Testing\DatabaseMigrations;
use Tests\TestCase;

class HealthCheckControllerTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function it_responds_to_health_check_requests(): void
    {
        $this->get('/health-check')
            ->assertResponseOk();
    }
}
