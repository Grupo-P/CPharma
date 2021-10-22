<?php

function monto_banco($base, $iva = 0)
{
    return (float) $base + (float) $iva;
}
