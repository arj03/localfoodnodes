<?php

namespace App\Http\Controllers\Api\v1\Users;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NodesController extends \App\Http\Controllers\Controller
{
    public function nodes(Request $request)
    {
        $user = Auth::guard('api')->user();
        return  $user->nodes();
    }
}
