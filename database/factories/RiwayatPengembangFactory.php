<?php

namespace Database\Factories;

use App\Models\RiwayatPengembang;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<RiwayatPengembang>
 */
class RiwayatPengembangFactory extends Factory
{
    protected $model = RiwayatPengembang::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'judul' => fake()->sentence(3),
            'tahun' => fake()->date(),
            'tahun_selesai' => fake()->optional()->date(),
        ];
    }
}
