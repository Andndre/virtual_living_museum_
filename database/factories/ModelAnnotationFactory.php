<?php

namespace Database\Factories;

use App\Models\ModelAnnotation;
use App\Models\VirtualMuseum;
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
      'museum_id' => VirtualMuseum::factory(),
      'label' => fake()->word(),
      'position_x' => fake()->randomFloat(2, -10, 10),
      'position_y' => fake()->randomFloat(2, -10, 10),
      'position_z' => fake()->randomFloat(2, -10, 10),
      'is_visible' => true,
      'display_order' => 0,
    ];
  }
}