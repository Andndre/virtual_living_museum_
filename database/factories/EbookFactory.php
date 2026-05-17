<?php

namespace Database\Factories;

use App\Models\Ebook;
use App\Models\Materi;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Ebook>
 */
class EbookFactory extends Factory
{
  protected $model = Ebook::class;

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
      'path_file' => null,
    ];
  }
}