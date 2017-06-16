<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\User\User;
use App\User\UserMembershipPayment;

class PageController extends Controller
{
    /**
     * Instantiate a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        $this->middleware(function ($request, $next) {
            view()->composer('*', function($view) {
                $view_name =  'page ' . str_replace('.', '-', $view->getName());
                view()->share('viewName', $view_name);
            });
            return $next($request);
        });
    }

    public function findOutMore()
    {
        return view('public.pages.find-out-more');
    }

    public function membership()
    {
        $users = User::with(['membershipPaymentsRelationship'])->get();
        $members = $users->filter(function($user) {
            return $user->isMember(true);
        })->count();

        $allPayments = UserMembershipPayment::get();
        $totalMembershipPayments = $allPayments->map(function($payment) {
            return ($payment->amount > 2) ? $payment->amount : null;
        })->filter()->sum();

        $totalPayingMembers = $allPayments->unique('user_id')->count();
        $averageMembershipPayments = $members === 0 ? 0 : $totalMembershipPayments / $totalPayingMembers;

        return view('public.pages.membership', [
            'members' => $members,
            'averageMembership' => round($averageMembershipPayments)
        ]);
    }
}
