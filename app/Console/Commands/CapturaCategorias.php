<?php

namespace compras\Console\Commands;

use Illuminate\Console\Command;
use compras\Auditoria;

class CapturaCategorias extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'Capturar:Categorias';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Este comando ejecuta la captura automatica de la data necesaria para categorizar';

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

        FG_Validar_Categorias();
 
        $Auditoria = new Auditoria();
        $Auditoria->accion = 'CAPTURAR';
        $Auditoria->tabla = 'CATEGORIAS';
        $Auditoria->registro = 'CPHARMA';
        $Auditoria->user = 'SYSTEM';
        $Auditoria->save();

        $this->info('La captura de la data necesaria para categorizar los articulos fue ejecutada satisfactoriamente!');
    }
}
