<?php

namespace Domains\Locations\Tests\Feature;

use Domains\Locations\Database\Factories\LocationFactory;
use Illuminate\Http\Response;
use Laravel\Lumen\Testing\DatabaseMigrations;
use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Token;
use Tests\TestCase;

class LocationsStoreControllerTest extends TestCase
{
    use DatabaseMigrations;

    private Token $jwt;
    private string $endpoint = '/locations';

    protected function setUp(): void
    {
        parent::setUp();

        $this->jwt = (new Builder())
            ->relatedTo(1)
            ->withClaim('scopes', ['asset-active-directory:locations:view', 'asset-active-directory:locations:manage'])
            ->withClaim('customer_id', 1)
            ->getToken();
    }

    /** @test */
    public function unauthorized_users_cant_access_endpoint(): void
    {
        $this->post($this->endpoint)
            ->assertResponseStatus(Response::HTTP_UNAUTHORIZED);
    }

    /** @test */
    public function it_rejects_request_if_required_input_is_missing(): void
    {
        $this->post($this->endpoint, [], ['Authorization' => "Bearer {$this->jwt}"])
            ->seeJsonStructure([
                'customer_id',
                'name',
            ])
            ->assertResponseStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /** @test */
    public function it_stores_resource(): void
    {
        $payload = [
            'customer_id' => $this->jwt->getClaim('customer_id'),
            'name' => 'Location Name',
        ];

        $this->post($this->endpoint, $payload, ['Authorization' => "Bearer {$this->jwt}"])
            ->assertResponseStatus(Response::HTTP_NO_CONTENT);

        $this->seeInDatabase('locations', [
            'customer_id' => $payload['customer_id'],
            'name' => $payload['name'],
        ]);
    }

    /** @test */
    public function it_stores_resource_on_a_parent(): void
    {
        $parent = LocationFactory::new([
            'customer_id' => $this->jwt->getClaim('customer_id'),
        ])->create();

        $payload = [
            'customer_id' => $this->jwt->getClaim('customer_id'),
            'parent_id' => $parent->id,
            'name' => 'Location 2 Name',
        ];

        $this->post($this->endpoint, $payload, ['Authorization' => "Bearer {$this->jwt}"])
            ->assertResponseStatus(Response::HTTP_NO_CONTENT);

        $this->seeInDatabase('locations', [
            'customer_id' => $payload['customer_id'],
            'parent_id' => $payload['parent_id'],
            'name' => $payload['name'],
        ]);
    }

    /** @test */
    public function it_cant_store_resource_on_a_parent_not_related_to_same_customer(): void
    {
        $forbiddenParent = LocationFactory::new([
            'customer_id' => 2,
        ])->create();

        $payload = [
            'customer_id' => $this->jwt->getClaim('customer_id'),
            'parent_id' => $forbiddenParent->id,
            'name' => 'Location 2 Name',
        ];

        $this->post($this->endpoint, $payload, ['Authorization' => "Bearer {$this->jwt}"])
            ->assertResponseStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }
}
