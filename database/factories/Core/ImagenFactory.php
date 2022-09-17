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
        return [
            'url' => 'imagenes/'.$this->faker->image('public/storage/imagenes', 50, 50,  null, false),
            'activo' => $this->faker->randomElement([0,1]),
            'borrado' => $this->faker->randomElement([0,1]),
        ];
    }
}
