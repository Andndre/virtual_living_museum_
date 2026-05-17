<?php

namespace Database\Factories;

use App\Models\Materi;
use App\Models\Posttest;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Posttest>
 */
class PosttestFactory extends Factory
{
  protected $model = Posttest::class;

  /**
   * Define the model's default state.
   *
   * @return array<string, mixed>
   */
  public function definition(): array
  {
    return [
      'materi_id' => Materi::factory(),
      'pertanyaan' => fake()->sentence().'?',
      'pilihan_a' => 'Option A',
      'pilihan_b' => 'Option B',
      'pilihan_c' => 'Option C',
      'pilihan_d' => 'Option D',
      'pilihan_e' => 'Option E',
      'jawaban_benar' => fake()->randomElement(['A', 'B', 'C', 'D', 'E']),
    ];
  }
}