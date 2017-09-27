<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests;

class AdminController extends Controller
{
    public function index(Request $request)
    {
        return view('admin.index');
    }

    public function users(Request $request)
    {
        return view('admin.users');
    }

    public function orders(Request $request)
    {
        return view('admin.orders');
    }

    public function api(Request $request)
    {
        return view('admin.api');
    }
}
