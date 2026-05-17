<?php

namespace Database\Factories;

use App\Models\KritikSaran;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\KritikSaran>
 */
class KritikSaranFactory extends Factory
{
  protected $model = KritikSaran::class;

  /**
   * Define the model's default state.
   *
   * @return array<string, mixed>
   */
  public function definition(): array
  {
    return [
      'user_id' => User::factory(),
      'pesan' => fake()->paragraph(),
    ];
  }
}