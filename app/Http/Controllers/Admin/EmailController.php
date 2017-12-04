<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Order\OrderDate;
use App\User\User;
use Mail;

class EmailController extends Controller
{
    public function index(Request $request)
    {
        return view('admin.email');
    }

    public function userActivation(Request $request)
    {
        $user = User::orderBy('created_at', 'desc')->first();

        $data = [
            'title' => 'Activate account',
            'user' => $user,
            'token' => 'testtesttest'
        ];

        return view('email.activate-user', $data);
    }

    public function resetPassword(Request $request)
    {
        $user = User::orderBy('created_at', 'desc')->first();

        $data = [
            'title' => 'Reset password',
            'user' => $user,
            'token' => 'testtesttest'
        ];

        return view('email.reset-password', $data);
    }

    public function orderProducer(Request $request)
    {
        $user = Auth::user();
        $producer = $user->producerAdminLinks()->first()->getProducer();
        $orderDates = $producer->orderDates();

        $data = [
            'title' => 'Order',
            'producer' => $producer,
            'orderDates' => $orderDates
        ];

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

        return view('email.customer-order', $data);
    }
}
