<?php

namespace Database\Seeders\Core;

use App\Models\Core\Conexion;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ConexionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Conexion::factory(50)->create();
    }
}
