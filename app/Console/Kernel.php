<?php

namespace compras\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
        'compras\Console\Commands\CapturaDiasCero',
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('Capturar:DiasCero')->withoutOverlapping()->timezone('America/Caracas')->between('2:00', '10:00');

        $schedule->command('Capturar:Etiquetas')->withoutOverlapping()->timezone('America/Caracas')->between('2:00', '10:00');

        $schedule->command('Capturar:ProductosCaida')->withoutOverlapping()->timezone('America/Caracas')->between('2:00', '10:00');

        $schedule->command('Capturar:Categorias')->withoutOverlapping()->timezone('America/Caracas')->between('2:00', '10:00');

        $schedule->command('articulos:vencer')->withoutOverlapping()->timezone('America/Caracas')->between('2:00', '10:00');

        $schedule->command('Capturar:CorridaPrecios')->withoutOverlapping()->timezone('America/Caracas')->between('10:20', '23:00');
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
