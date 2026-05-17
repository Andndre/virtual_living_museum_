<?php

namespace Database\Factories;

use App\Models\VideoPeninggalan;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\VideoPeninggalan>
 */
class VideoPeninggalanFactory extends Factory
{
  protected $model = VideoPeninggalan::class;

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
      'url_video' => 'https://youtube.com/watch?v='.fake()->uuid(),
      'thumbnail' => fake()->word().'.jpg',
    ];
  }
}