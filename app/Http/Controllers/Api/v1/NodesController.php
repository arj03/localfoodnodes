<?php

namespace App\Http\Controllers\Api\v1;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Node\Node;

class NodesController extends \App\Http\Controllers\Controller
{
    public function nodes(Request $request)
    {
        return Node::all();;
    }
}
