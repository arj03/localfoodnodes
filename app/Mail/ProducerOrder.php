<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ProducerOrder extends Mailable
{
    use Queueable, SerializesModels;

    public $orderItems;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($orderItems)
    {
        $this->orderItems = $orderItems;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from(['address' => 'info@localfoodnodes.org', 'name' => 'Local Food Nodes'])
        ->subject('Incoming order')
        ->view('email.producer-order');
    }
}
