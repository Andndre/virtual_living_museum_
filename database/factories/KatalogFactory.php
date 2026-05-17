<?php

namespace Database\Factories;

use App\Models\Katalog;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Katalog>
 */
class KatalogFactory extends Factory
{
    protected $model = Katalog::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'path_pdf' => null,
        ];
    }
}
