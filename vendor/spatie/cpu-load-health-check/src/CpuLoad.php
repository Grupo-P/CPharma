<?php

namespace Spatie\CpuLoadHealthCheck;

use Spatie\CpuLoadHealthCheck\Exceptions\CouldNotMeasureCpuLoad;

class CpuLoad
{
    public static function measure(): self
    {
        //$result = sys_getloadavg();

        if (stristr(PHP_OS, 'win')) {
            $result = array(0.000, 0.000, 0.000);
        } else {       
            $result = sys_getloadavg();       
        }

        if (! $result) {
            throw CouldNotMeasureCpuLoad::make();
        }

        return new self(...$result);
    }

    public function __construct(
        public float $lastMinute,
        public float $last5Minutes,
        public float $last15Minutes,
    ) {
    }
}
