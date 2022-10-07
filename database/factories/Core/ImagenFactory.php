<?php

namespace Database\Factories\Core;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Core\Imagen>
 */
class ImagenFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $user = User::orderBy('id', 'desc')->first();
        $nombre = explode(' ', $user->name);
        $inicial = substr($nombre[0],0,1).substr($nombre[1],0,1);

        return [
            'url' => 'usuarios/'.$this->faker->image('public/storage/usuarios', 80, 80,  null, false, false, $inicial, false, 'png'),
            'activo' => $this->faker->randomElement([0,1]),
            'borrado' => $this->faker->randomElement([0,1]),
        ];
    }
}
