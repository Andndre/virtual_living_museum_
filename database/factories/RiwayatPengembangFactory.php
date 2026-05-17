<?php

namespace Database\Factories;

use App\Models\RiwayatPengembang;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\RiwayatPengembang>
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
      'nama' => fake()->name(),
      'peran' => fake()->jobTitle(),
      'tahun' => fake()->year(),
      'deskripsi' => fake()->paragraph(),
    ];
  }
}