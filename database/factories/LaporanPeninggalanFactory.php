<?php

namespace Database\Factories;

use App\Models\LaporanPeninggalan;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<LaporanPeninggalan>
 */
class LaporanPeninggalanFactory extends Factory
{
    protected $model = LaporanPeninggalan::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'nama_peninggalan' => fake()->sentence(2),
            'alamat' => fake()->address(),
            'lat' => fake()->latitude(-8, -6),
            'lng' => fake()->longitude(110, 112),
            'deskripsi' => fake()->paragraph(),
        ];
    }
}
