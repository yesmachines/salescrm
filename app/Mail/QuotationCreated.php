<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class QuotationCreated extends Mailable
{
    use Queueable, SerializesModels;

    public $data;
    public $product;
   

    public function __construct($data,$product)
    {
        $this->data = $data;
        $this->product = $product;
       
        

    }

    public function build()
    {

        return $this->view('emails.quotation_created')
             ->subject("Special Offer for {$this->product->title}");
    }
}
