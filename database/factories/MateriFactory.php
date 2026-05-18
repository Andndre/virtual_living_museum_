<?php

namespace Database\Factories;

use App\Models\Era;
use App\Models\Materi;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Materi>
 */
class MateriFactory extends Factory
{
    protected $model = Materi::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'judul' => fake()->sentence(3),
            'deskripsi' => fake()->paragraph(),
            'urutan' => fake()->numberBetween(1, 10),
        ];
    }

    /**
     * Associate with an era.
     */
    public function forEra(Era $era): static
    {
        return $this->state(fn (array $attributes) => [
            'era_id' => $era->era_id,
        ]);
    }
}
