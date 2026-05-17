<?php

namespace Database\Factories;

use App\Models\Era;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Era>
 */
class EraFactory extends Factory
{
  protected $model = Era::class;

  /**
   * Define the model's default state.
   *
   * @return array<string, mixed>
   */
  public function definition(): array
  {
    return [
      'kode' => fake()->unique()->lexify('?????'),
      'nama' => fake()->word(),
      'urutan' => fake()->numberBetween(1, 10),
      'rentang_waktu' => fake()->year().' - '.fake()->year(),
    ];
  }
}