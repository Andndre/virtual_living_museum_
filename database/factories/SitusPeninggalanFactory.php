<?php

namespace Database\Factories;

use App\Models\SitusPeninggalan;
use App\Models\Materi;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SitusPeninggalan>
 */
class SitusPeninggalanFactory extends Factory
{
  protected $model = SitusPeninggalan::class;

  /**
   * Define the model's default state.
   *
   * @return array<string, mixed>
   */
  public function definition(): array
  {
    return [
      'nama' => fake()->city().' Temple',
      'alamat' => fake()->address(),
      'lat' => fake()->latitude(-8, -6),
      'lng' => fake()->longitude(110, 112),
      'deskripsi' => fake()->paragraph(),
      'materi_id' => Materi::factory(),
      'user_id' => User::factory(),
      'thumbnail' => null,
    ];
  }
}