<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class CustomerOrder extends Mailable
{
    use Queueable, SerializesModels;

    public $orderDates;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($orderDates)
    {
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
        ->subject(trans('public/email.order_confirmation'))
        ->view('email.customer-order');
    }
}
