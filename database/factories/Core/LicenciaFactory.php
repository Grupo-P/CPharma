<?php

namespace Database\Factories\Core;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Core\Licencia>
 */
class LicenciaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'hash1' => Hash::make('d3m0'),
            'hash2' => Hash::make('d3m0'),
            'hash3' => Hash::make('d3m0'),
            'hash4' => Hash::make('d3m0'),
            'activo' => $this->faker->randomElement([0,1]),
            'borrado' => $this->faker->randomElement([0,1]),
            'user_created_at' => 1,
        ];
    }
}
