<?php

namespace Database\Seeders\Core;

use App\Models\Core\Licencia;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LiceciaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Licencia::factory(1)->create();
    }
}
