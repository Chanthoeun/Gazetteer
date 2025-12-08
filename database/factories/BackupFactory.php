<?php

namespace Database\Factories;

use App\Models\Parent;
use App\Models\Place;
use App\Models\Type;
use Illuminate\Database\Eloquent\Factories\Factory;

class BackupFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'place_id' => Place::factory(),
            'type_id' => Type::factory(),
            'parent_id' => parent::factory(),
            'code' => fake()->regexify('[A-Za-z0-9]{10}'),
            'khmer' => fake()->regexify('[A-Za-z0-9]{150}'),
            'latin' => fake()->regexify('[A-Za-z0-9]{150}'),
            'postal_code' => fake()->postcode(),
            'geo_location' => fake()->word(),
            'geo_boundary' => fake()->word(),
            'reference' => fake()->word(),
            'issued_date' => fake()->date(),
            'official_note' => fake()->text(),
        ];
    }
}
