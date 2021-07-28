<?php

namespace compras\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NotificarPagoProveedor extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($pago, $filename)
    {
        $this->pago     = $pago;
        $this->filename = $filename;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $proveedor   = $this->pago->proveedor->nombre_proveedor;
        $comprobante = str_pad($this->pago->id, 5, 0, STR_PAD_LEFT);

        $subject = "Soporte de pago Farmacia Tierra Negra / {$proveedor} / {$comprobante}";

        return $this->subject($subject)
            ->text('pages.contabilidad.notificacion')
            ->attach($this->filename);
    }
}
