<?php

namespace Database\Factories\Core;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Core\Conexion>
 */
class ConexionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'nombre' => $this->faker->word(10),
            'nombre_mostrar' => $this->faker->word(10),
            'siglas' => 'SACB',
            'ip_address' => $this->faker->randomNumber(6),
            'driver_db' => $this->faker->text(50),
            'instancia_db' => $this->faker->text(50),
            'usuario' => $this->faker->text(50),
            'clave' => $this->faker->text(50),
            'db_online' => $this->faker->text(15),
            'db_offline' => $this->faker->text(15),
            'online' => $this->faker->randomElement([0,1]),
            'activo' => $this->faker->randomElement([0,1]),
            'borrado' => $this->faker->randomElement([0,1]),
            'user_created_at' => User::all()->random()->id,
        ];
    }
}
