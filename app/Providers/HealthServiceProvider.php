<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Spatie\Health\Facades\Health;
use Spatie\CpuLoadHealthCheck\CpuLoadCheck;
use Spatie\Health\Checks\Checks\UsedDiskSpaceCheck;
use Spatie\Health\Checks\Checks\DatabaseCheck;
use Spatie\Health\Checks\Checks\PingCheck;
use Spatie\Health\Checks\Checks\CacheCheck;
use Spatie\Health\Checks\Checks\EnvironmentCheck;
use Spatie\Health\Checks\Checks\DebugModeCheck;

class HealthServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        Health::checks([
            CpuLoadCheck::new(),
            UsedDiskSpaceCheck::new(),
            DatabaseCheck::new(),
            CacheCheck::new(),
            EnvironmentCheck::new(),
            DebugModeCheck::new(),
            PingCheck::new()->url('https://google.com')->name('Ping Google'),
            PingCheck::new()->url('https://facebook.com')->name('Ping Facebook'),
            PingCheck::new()->url('https://instagram.com')->name('Ping Instagram'),
        ]);
    }    
}
