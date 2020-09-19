<?php

namespace Domains\Locations\Database\Factories;

use Domains\Locations\Models\Location;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class LocationFactory extends Factory
{
    protected $model = Location::class;

    public function definition(): array
    {
        return [
            'customer_id' => $this->faker->randomNumber(),
            'parent_id' => null,
            'name' => $this->faker->address,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'deleted_at' => null,
        ];
    }
}
