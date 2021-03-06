<?php

namespace compras\Console\Commands;

use Illuminate\Console\Command;
use compras\Auditoria;
use compras\Configuracion;

class CorridaPrecios extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'Capturar:CorridaPrecios';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Este comando ejecuta la corrida de precios automatica';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {        
        include(app_path().'\functions\config.php');
        include(app_path().'\functions\functions.php');
        include(app_path().'\functions\querys_mysql.php');
        include(app_path().'\functions\querys_sqlserver.php');
                       
        $configuracion = Configuracion::where('variable','DolarCalculo')->get();
        FG_Corrida_Precio('bajada',$configuracion[0]->valor,'SYSTEM');    

        $Auditoria = new Auditoria();
        $Auditoria->accion = 'EJECUTAR';
        $Auditoria->tabla = 'CORRIDA DE PRECIOS';
        $Auditoria->registro = 'CPHARMA';
        $Auditoria->user = 'SYSTEM';
        $Auditoria->save();    
        
        //Limpieza del cache
        Artisan::call('cache:clear');

        $this->info('La corrida de precios fue ejecutada satisfactoriamente!');
    }
}