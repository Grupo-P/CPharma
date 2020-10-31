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
    
        /*
        $configuracion = Configuracion::where('variable','DolarCalculo')->get();
        $rango_dias = (FG_Rango_Dias(date('Y-m-d'),$configuracion[0]->updated_at->format('Y-m-d'))); 

        if($rango_dias>=3){        
            $this->info('La corrida de precios no fue ejecutada, la tasa de calculo esta desactualizada!');
        }
        else{
            FG_Corrida_Precio('bajada',$configuracion[0]->valor,'SYSTEM');
            $this->info('La corrida de precios fue ejecutada satisfactoriamente!');
        }
        */
        
        $Auditoria = new Auditoria();
        $Auditoria->accion = 'EJECUTAR';
        $Auditoria->tabla = 'CORRIDA DE PRECIOS';
        $Auditoria->registro = 'CPHARMA';
        $Auditoria->user = 'SYSTEM';
        $Auditoria->save();    
    }
}