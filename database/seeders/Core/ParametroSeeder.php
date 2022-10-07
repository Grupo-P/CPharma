<?php

namespace Database\Seeders\Core;

use App\Models\Core\Parametro;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ParametroSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Parametro::create([
            'variable' => 'razon_social',
            'valor' => 'Razón Social C.A',
            'descripcion' => 'Razón social',
            'activo' => 1,
            'borrado' => 0,
            'user_created_at' => 1,
        ]);

        Parametro::create([
            'variable' => 'rif',
            'valor' => 'J-19968352-0',
            'descripcion' => 'RIF del comercio',
            'activo' => 1,
            'borrado' => 0,
            'user_created_at' => 1,
        ]);

        Parametro::create([
            'variable' => 'fecha_limite',
            'valor' => '2022-11-01',
            'descripcion' => 'Fecha limite de la licencia',
            'activo' => 1,
            'borrado' => 0,
            'user_created_at' => 1,
        ]);

        Parametro::create([
            'variable' => 'tipo_licencia',
            'valor' => 'basica',
            'descripcion' => 'Tipo de licencia',
            'activo' => 1,
            'borrado' => 0,
            'user_created_at' => 1,
        ]);

        Parametro::create([
            'variable' => 'leyenda_factura',
            'valor' => 'Leyenda de factura demo',
            'descripcion' => 'Leyenda de factura',
            'activo' => 1,
            'borrado' => 0,
            'user_created_at' => 1,
        ]);

        Parametro::create([
            'variable' => 'leyenda_cotizacion',
            'valor' => 'Leyenda de cotizacion demo',
            'descripcion' => 'Leyenda de cotizacion',
            'activo' => 1,
            'borrado' => 0,
            'user_created_at' => 1,
        ]);
        
        //Parametro::factory(49)->create();
    }
}
