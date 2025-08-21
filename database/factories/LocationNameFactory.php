<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\LocationName;
use App\Models\\App\Models\Location;

class LocationNameFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = LocationName::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'location_id' => \App\Models\Location::factory(),
            'name_kh' => fake()->regexify('[A-Za-z0-9]{100}'),
            'name_en' => fake()->regexify('[A-Za-z0-9]{80}'),
            'reference' => fake()->text(),
            'note' => fake()->text(),
            'status' => fake()->boolean(),
            'created_by' => fake()->regexify('[A-Za-z0-9]{50}'),
        ];
    }
}
