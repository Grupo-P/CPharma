<?php

function monto_banco($base, $iva = '', $retencion_deuda_1 = '', $retencion_deuda_2 = '', $retencion_iva = '')
{
    if ($retencion_deuda_1) {
        $retencion_deuda_1 = (float) $base * ((float) $retencion_deuda_1 / 100);
        $base              = (float) $base - (float) $retencion_deuda_1;
    }

    if ($retencion_deuda_2) {
        $retencion_deuda_2 = (float) $base * ((float) $retencion_deuda_2 / 100);
        $base              = (float) $base - (float) $retencion_deuda_2;
    }

    if ($retencion_iva) {
        $retencion_iva = (float) $iva * ((float) $retencion_iva / 100);
        $iva           = (float) $iva - (float) $retencion_iva;
    }

    $base = (float) $base + (float) $iva;

    return $base;
}
