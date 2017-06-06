<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Order\Order;
use App\User\User;
use Mail;

class EmailController extends Controller
{
    public function userActivation(Request $request, $userId)
    {
        $user = User::find($userId);

        $data = [
            'title' => 'Activate account',
            'user' => $user,
            'token' => 'testtesttest'
        ];

        // Mail::send('email.activate-user', $data, function ($message) {
        //     $message->from('info@localfoodnodes.org', 'Local Food Nodes');
        //     $message->to('davidajnered@gmail.com');
        //     $message->subject('Incoming order');
        // });

        return view('email.activate-user', $data);
    }

    public function resetPassword(Request $request, $userId)
    {
        $user = User::find($userId);

        $data = [
            'title' => 'Reset password',
            'user' => $user,
            'token' => 'testtesttest'
        ];

        // Mail::send('email.activate-user', $data, function ($message) {
        //     $message->from('info@localfoodnodes.org', 'Local Food Nodes');
        //     $message->to('davidajnered@gmail.com');
        //     $message->subject('Incoming order');
        // });

        return view('email.reset-password', $data);
    }

    public function orderProducer(Request $request)
    {
        $user = Auth::user();
        $orderItems = $user->orderItems();

        $data = [
            'title' => 'Order',
            'orderItems' => $orderItems
        ];

        // Mail::send('email.order-producer', $data, function ($message) {
        //     $message->from('info@localfoodnodes.org', 'Local Food Nodes');
        //     $message->to('davidajnered@gmail.com');
        //     $message->subject('Incoming order');
        // });

        return view('email.producer-order', $data);
    }

    public function orderCustomer(Request $request)
    {
        $user = Auth::user();
        $orderDates = $user->orderDates();

        $data = [
            'title' => 'Order confirmation',
            'orderDates' => $orderDates
        ];

        // Mail::send('email.order-customer', $data, function ($message) {
        //     $message->from('info@localfoodnodes.org', 'Local Food Nodes');
        //     $message->to('davidajnered@gmail.com');
        //     $message->subject('Order confirmation');
        // });

        return view('email.customer-order', $data);
    }
}
