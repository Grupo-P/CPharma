<?php

namespace compras\Console\Commands;

use Illuminate\Console\Command;
use compras\Auditoria;

class ProductosCaida extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'Capturar:ProductosCaida';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Este comando ejecuta la captura automatica para el reporte de productos en caida';

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

        FG_Prouctos_EnCaida();

        $Auditoria = new Auditoria();
        $Auditoria->accion = 'CAPTURAR';
        $Auditoria->tabla = 'PRODUCTOS EN CAIDA';
        $Auditoria->registro = 'CPHARMA';
        $Auditoria->user = 'SYSTEM';
        $Auditoria->save();

        $this->info('La captura de la data para productos en caida fue ejecutada satisfactoriamente!');
    }
}