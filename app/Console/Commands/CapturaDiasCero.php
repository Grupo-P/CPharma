<?php

namespace compras\Console\Commands;

use Illuminate\Console\Command;
use compras\Auditoria;

class CapturaDiasCero extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'Capturar:DiasCero';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Este comando ejecuta la captura automatica de la data necesaria para dias en cero';

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
        include(app_path().'\functions\querys.php');
        include(app_path().'\functions\funciones.php');
        include(app_path().'\functions\reportes.php');

        DiasEnCero();

        $Auditoria = new Auditoria();
        $Auditoria->accion = 'CAPTURAR';
        $Auditoria->tabla = 'DIAS CERO';
        $Auditoria->registro = 'CPHARMA';
        $Auditoria->user = 'SYSTEM';
        $Auditoria->save();

        $this->info('La captura de la data de dias en cero fue ejecutada satisfactoriamente!');
    }
}
