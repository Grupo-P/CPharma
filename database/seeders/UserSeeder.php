<?php

namespace Database\Seeders;

use App\Models\Core\Imagen;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $userDemo = User::create([
            'name' => 'Sergio Cova',
            'email' => 'covascode@gmail.com',
            'password' => bcrypt('12345678')
        ]);

        Imagen::factory(1)->create([
            'imageable_id' => $userDemo->id,
            'imageable_type' => Imagen::class
        ]);
        
        $users = User::factory(15)->create();
    
        foreach ($users as $user) {
            Imagen::factory(1)->create([
                'imageable_id' => $user->id,
                'imageable_type' => Imagen::class
            ]);
        }
    }
}
