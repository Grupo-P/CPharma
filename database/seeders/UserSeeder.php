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

        $mainUsers = [$sergio,$giordany,$edwin,$nisaul];
        $bootUsers = User::factory(11)->create();

        foreach ($mainUsers as $user) {
            Imagen::factory(1)->create([
                'imageable_id' => $user->id,
                'imageable_type' => User::class,
                'user_created_at' => $user->id,
            ]);
        }
    
        foreach ($bootUsers as $user) {
            Imagen::factory(1)->create([
                'imageable_id' => $user->id,
                'imageable_type' => User::class,
                'user_created_at' => $user->id,
            ]);
            $user->assignRole('Usuario');
        }
    }
}
