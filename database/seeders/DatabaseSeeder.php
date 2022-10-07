<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use Illuminate\Database\Seeder;
use Database\Seeders\Core\ParametroSeeder;
use Database\Seeders\Core\RoleSeeder;
use Illuminate\Support\Facades\Storage;
use Database\Seeders\Core\LiceciaSeeder;
use Database\Seeders\Core\ConexionSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        Storage::deleteDirectory('public/usuarios');
        Storage::makeDirectory('public/usuarios');
        $this->call(RoleSeeder::class);
        $this->call(UserSeeder::class);
        $this->call(ParametroSeeder::class);
        $this->call(LiceciaSeeder::class);
        $this->call(ConexionSeeder::class);
    }
}
