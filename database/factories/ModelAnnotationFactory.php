<?php

namespace Database\Factories;

use App\Models\ModelAnnotation;
use App\Models\VirtualMuseumObject;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ModelAnnotation>
 */
class ModelAnnotationFactory extends Factory
{
  protected $model = ModelAnnotation::class;

  /**
   * Define the model's default state.
   *
   * @return array<string, mixed>
   */
  public function definition(): array
  {
    return [
      'object_id' => VirtualMuseumObject::factory(),
      'label' => fake()->word(),
      'position_x' => fake()->randomFloat(2, -10, 10),
      'position_y' => fake()->randomFloat(2, -10, 10),
      'position_z' => fake()->randomFloat(2, -10, 10),
      'deskripsi' => fake()->sentence(),
    ];
  }
}