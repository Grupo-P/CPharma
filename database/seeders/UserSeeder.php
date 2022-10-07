<?php

namespace Database\Seeders;

use App\Models\Core\Imagen;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $sergio = User::create([
            'name' => 'Sergio Cova',
            'email' => 'scova@farmacia72.com.ve',
            'email_verified_at' => now(),
            'password' => Hash::make('12345678'),
            'remember_token' => Str::random(10),
            'cambio_clave' => 0,
            'activo' => 1,
            'borrado' => 0,
            'user_created_at' => 1,
        ]);
        $sergio->assignRole('Master');

        Imagen::factory(1)->create([
            'imageable_id' => $sergio->id,
            'imageable_type' => User::class,
            'user_created_at' => $sergio->id,
        ]);

        $giordany = User::create([
            'name' => 'Giodany Prieto',
            'email' => 'giordany@farmacia72.com',
            'email_verified_at' => now(),
            'password' => Hash::make('12345678'),
            'remember_token' => Str::random(10),
            'cambio_clave' => 0,
            'activo' => 1,
            'borrado' => 0,
            'user_created_at' => 1,
        ]);
        $giordany->assignRole('Gerente');

        Imagen::factory(1)->create([
            'imageable_id' => $giordany->id,
            'imageable_type' => User::class,
            'user_created_at' => $giordany->id,
        ]);

        $edwin = User::create([
            'name' => 'Edwin Arias',
            'email' => 'earias@farmacia72.com.ve',
            'email_verified_at' => now(),
            'password' => Hash::make('12345678'),
            'remember_token' => Str::random(10),
            'cambio_clave' => 0,
            'activo' => 1,
            'borrado' => 0,
            'user_created_at' => 1,
        ]);
        $edwin->assignRole('Supervisor');

        Imagen::factory(1)->create([
            'imageable_id' => $edwin->id,
            'imageable_type' => User::class,
            'user_created_at' => $edwin->id,
        ]);

        $nisaul = User::create([
            'name' => 'Nisaul Delgado',
            'email' => 'ndelgado@grupop.com.ve',
            'email_verified_at' => now(),
            'password' => Hash::make('12345678'),
            'remember_token' => Str::random(10),
            'cambio_clave' => 0,
            'activo' => 1,
            'borrado' => 0,
            'user_created_at' => 1,
        ]);
        $nisaul->assignRole('Usuario');

        Imagen::factory(1)->create([
            'imageable_id' => $nisaul->id,
            'imageable_type' => User::class,
            'user_created_at' => $nisaul->id,
        ]);
        
        /*
        for($i=0 ; $i<11; $i++){
            $bootUser = User::factory(1)->create();
            Imagen::factory(1)->create([
                'imageable_id' => $bootUser[0]->id,
                'imageable_type' => User::class,
                'user_created_at' => $bootUser[0]->id,
            ]);
            $bootUser[0]->assignRole('Usuario');
        }
        */    
    }
}
