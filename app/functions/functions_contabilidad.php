<?php

function simbolo($moneda)
{
    switch ($moneda) {
        case 'Euros':
            return '€';
            break;
        case 'Dólares':
            return '$';
            break;
        case 'Bolívares':
            return 'Bs.';
            break;
    }

}

function monto_banco($base, $iva = '', $retencion_deuda_1 = '', $retencion_deuda_2 = '', $retencion_iva = '')
{
    $monto = $base;

    if ($monto == 0) {
        return 0;
    }

    if ($retencion_deuda_1) {
        $retencion_deuda_1 = (float) $base * ((float) $retencion_deuda_1 / 100);
        $monto             = (float) $monto - (float) $retencion_deuda_1;
    }

    if ($retencion_deuda_2) {
        $retencion_deuda_2 = (float) $base * ((float) $retencion_deuda_2 / 100);
        $monto             = (float) $monto - (float) $retencion_deuda_2;
    }

    if ($retencion_iva) {
        $retencion_iva = (float) $iva * ((float) $retencion_iva / 100);
        $iva           = (float) $iva - (float) $retencion_iva;
    }

    $monto = (float) $monto + (float) $iva;

    return $monto;
}
