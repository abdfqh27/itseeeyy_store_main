<?php

namespace App\Mail;

use App\Models\Product;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ExpiryAlertMail extends Mailable
{
    use Queueable, SerializesModels;

    public $product;

    public function __construct(Product $product)
    {
        $this->product = $product;
    }

    public function build()
    {
        return $this->subject('🚨 Peringatan Produk Kedaluwarsa - ' . $this->product->name)
                    ->view('emails.expiry-notification-template')
                    ->with([
                        'product' => $this->product,
                        'daysUntilExpiry' => $this->product->getDaysUntilExpiry()
                    ]);
    }
}