<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Location;
use App\Models\\App\Models\LocationType;

class LocationFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Location::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'location_type_id' => \App\Models\LocationType::factory(),
            'coordination' => fake()->word(),
            'created_by' => fake()->regexify('[A-Za-z0-9]{50}'),
        ];
    }
}
