<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendInvoice extends Mailable
{
    use Queueable, SerializesModels;

    public $invoiceData;

    /**
     * Create a new message instance.
     */
    public function __construct($invoiceData)
    {
        $this->invoiceData = $invoiceData;
        $this->template = $invoiceData['template'] ?? 'default';
        $this->color = $invoiceData['color'] ?? '#000000';
        $this->logo = asset('favicon.ico'); // Replace with your actual logo path
    }


    /**
     * Build the message.
     */
    public function build()
    {
        return $this
            ->subject('Your Invoice')
            ->view('emails.email-invoice')
            ->with([
                'invoiceData' => $this->invoiceData,
                'template' => $this->template,
                'color' => $this->color,
                'logo' => $this->logo,
            ]);
    }

}
