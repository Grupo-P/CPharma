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
        Licencia::create([
            'hash1' => '$2y$10$Rdbbvw7igV1yIIu63FzIdu5mHwwzFhotLC2pFmeQ4zYGJhyboAq2m',
            'hash2' => '$2y$10$/Ll4SNEC2uat7eqPzBiPgeCrSJHvp2zdABnTCIZCIxAt1ScnqMbCq',
            'hash3' => '$2y$10$T31PXlS7OlsZBJCaBFFV3OSZgFODk9R.gHcv.q7QOwGwSv3jSN9q6',
            'hash4' => '$2y$10$nIhVZeNz51N7JsflbHRt.uDL8USlhgS.LVR/IDWNHOt6SO1hwC3Dq',
            'activo' => 1,
            'borrado' => 0,
            'user_created_at' => 1,
        ]);
        
        //Licencia::factory(1)->create();
    }
}
