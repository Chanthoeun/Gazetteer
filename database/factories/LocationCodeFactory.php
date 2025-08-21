<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\LocationCode;
use App\Models\Parent;
use App\Models\\App\Models\Location;

class LocationCodeFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = LocationCode::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'location_id' => \App\Models\Location::factory(),
            'parent_id' => Parent::factory(),
            'code' => fake()->regexify('[A-Za-z0-9]{12}'),
            'postal_code' => fake()->postcode(),
            'reference' => fake()->text(),
            'note' => fake()->text(),
            'status' => fake()->boolean(),
            'created_by' => fake()->regexify('[A-Za-z0-9]{50}'),
        ];
    }
}
