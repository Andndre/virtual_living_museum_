<?php

namespace Database\Factories;

use App\Models\SitusPeninggalan;
use App\Models\VirtualMuseum;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\VirtualMuseum>
 */
class VirtualMuseumFactory extends Factory
{
  protected $model = VirtualMuseum::class;

  /**
   * Define the model's default state.
   *
   * @return array<string, mixed>
   */
  public function definition(): array
  {
    return [
      'nama' => fake()->company().' Virtual Museum',
      'situs_id' => SitusPeninggalan::factory(),
      'path_obj' => 'virtual-museum/models/'.fake()->uuid().'.glb',
    ];
  }
}