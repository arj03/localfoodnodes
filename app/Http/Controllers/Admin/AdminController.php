<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use LocalFoodNodes\Sdk\LocalFoodNodes;

use App\Http\Controllers\Controller;
use App\Http\Requests;

class AdminController extends Controller
{
    /**
     * @var LocalFoodNodes
     */
    private $api;

    /**
     * Constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->api = new LocalFoodNodes(env('API_URL'), env('ADMIN_API_CLIENT_ID'), env('ADMIN_API_SECRET'), env('ADMIN_API_USERNAME'), env('ADMIN_API_PASSWORD'));
    }

    /**
     * [apiProxy description]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function apiProxy(Request $request)
    {
        $method = $request->method();
        $url = $request->input('url');
        $data = $request->has('data') ? $request->input('data') : [];

        return $this->api->{$method}($url, $data);
    }

    /**
     * Get API token.
     *
     * @return array
     */
    public function getApiAccessToken()
    {
        return $this->api->getToken();
    }

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

    public function economy(Request $request)
    {
        return view('admin.economy');
    }

    public function api(Request $request)
    {
        return view('admin.api');
    }
}
