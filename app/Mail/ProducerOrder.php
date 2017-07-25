<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ProducerOrder extends Mailable
{
    use Queueable, SerializesModels;

    public $producer;
    public $orderDates;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($producer, $orderDates)
    {
        $this->producer = $producer;
        $this->orderDates = $orderDates;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from(['address' => 'info@localfoodnodes.org', 'name' => 'Local Food Nodes'])
        ->subject(trans('public/email.incoming_order'))
        ->view('email.producer-order');
    }
}
