<?php

namespace compras\Console\Commands;

use Illuminate\Console\Command;
use compras\Auditoria;

class CapturaEtiquetas extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'Capturar:Etiquetas';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'ste comando ejecuta la captura automatica de la data necesaria para clasificar la etiquetas';

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

        FG_Validar_Etiquetas();

        $Auditoria = new Auditoria();
        $Auditoria->accion = 'CAPTURAR';
        $Auditoria->tabla = 'ETIQUETAS';
        $Auditoria->registro = 'CPHARMA';
        $Auditoria->user = 'SYSTEM';
        $Auditoria->save();

        $this->info('La captura de la data necesaria para las etiquetas fue ejecutada satisfactoriamente!');
    }
}
