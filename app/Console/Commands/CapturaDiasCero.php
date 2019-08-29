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
    protected $description = 'Este comando ejecuta la captura automatica de la datas necesaria para dias en cero';

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
        $Auditoria = new Auditoria();
        $Auditoria->accion = 'CAPTURA';
        $Auditoria->tabla = 'AUTOMATICA';
        $Auditoria->registro = 'LARAVEL';
        $Auditoria->user = 'SERGIO COVA';
        $Auditoria->save();

        $this->info('La captura fue ejecutada satisfactoriamente!');
    }
}
