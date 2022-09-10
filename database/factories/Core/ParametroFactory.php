<?php

namespace Database\Factories\Core;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Core\Parametro>
 */
class ParametroFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'variable' => $this->faker->word(10),
            'valor' => $this->faker->word(10),
            'descripcion' => $this->faker->text(50),
            'activo' => $this->faker->randomElement([0,1]),
            'borrado' => $this->faker->randomElement([0,1]),
            'user_created_at' => User::all()->random()->id,            
        ];
    }
}
