<?php

namespace Database\Factories;

use App\Models\Materi;
use App\Models\Pretest;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Pretest>
 */
class PretestFactory extends Factory
{
    protected $model = Pretest::class;

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
