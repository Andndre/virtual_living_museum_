<?php

namespace Database\Factories;

use App\Models\Materi;
use App\Models\Tugas;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Tugas>
 */
class TugasFactory extends Factory
{
  protected $model = Tugas::class;

  /**
   * Define the model's default state.
   *
   * @return array<string, mixed>
   */
  public function definition(): array
  {
    return [
      'materi_id' => Materi::factory(),
      'judul' => fake()->sentence(3),
      'deskripsi' => fake()->paragraph(),
      'tipe' => fake()->randomElement(['file', 'teks']),
      'file_path' => null,
    ];
  }
}