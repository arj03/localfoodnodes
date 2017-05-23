<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\MessageBag;

use Stripe\Stripe;
use Stripe\Charge;

use App\Http\Requests;
use App\Donation;
use App\Helpers\SlackHelper;

class PaymentController extends Controller
{
    /**
     * Early register of donor information. Ajax request -> ajax response.
     *
     * @param  Request $request
     */
    public function registerDonor(Request $request)
    {
        $data = $request->all();
        $data['form_token'] = $data['_token'];

        $rules = [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255',
            'uuid' => 'required|unique:donations',
        ];

        if ($data['paymentMethod'] === 'card') {
            $rules['zip'] = 'required';
        }

        $slackMessage = $data['name'] . ' (' . $data['email'] . ')';

        $donation = new Donation();
        $errors = $donation->validate($donation->sanitize($data), $rules);
        if ($errors->isEmpty()) {
            $donation->fill($donation->sanitize($data));
            $donation->save();

            SlackHelper::message('notification', "Donor and donation registration\n" . $slackMessage);

            return response()->json('registration ok', 200);
        } else {
            // Form token has already been used. Return 200 so user form continues to next step.
            if ($errors->count() === 1 && $errors->get('uuid')) {
                SlackHelper::message('notification', "Donor & donation registration\n" . $slackMessage);

                return response()->json('registration exist', 200);
            } else {
                return response()->json($errors, 400);
            }
        }
    }

    /**
     * [processPayment description]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function processPayment(Request $request)
    {
        $data = $request->all();
        $data['stripe_token'] = $data['stripeToken'];

        $errors = new MessageBag();

        Stripe::setApiKey(config('payment.stripe.live.secret_key'));

        try {
            $token = $data['stripeToken'];
            $amount = $data['amount'];

            if (!is_numeric($amount) && $amount < 3) {
                throw new Exception('Membership fee is not valid.');
            }

            $amount = ((int) $amount) * 100;

            $charge = Charge::create(array(
                'amount' => $amount,
                'currency' => 'sek',
                'source' => $token,
                'description' => $data['name']
            ));

            if ($charge['status'] === 'succeeded') {
                $donation = Donation::where('uuid', $data['uuid'])->first();
                $donation->fill($data);
                $donation->save();
            }
        } catch(Exception $e) {
            $errors->add('payment', $e->getMessage());
        }

        if ($errors->count() === 0) {
            $request->session()->flash('message', ['Thank you for your donation!']);
        }

        return redirect('/launch')->withErrors($errors);
    }
}
