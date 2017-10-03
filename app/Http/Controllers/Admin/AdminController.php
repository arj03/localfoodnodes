<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use GuzzleHttp\Exception\RequestException;

use GuzzleHttp\Client;

class AdminController extends Controller
{
    /**
     * @var GuzzleHttp\Client
     */
    protected $http;

    /**
     * @var string
     */
    protected $accessToken;

    /**
     * Constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->http = new Client;

        if (!$this->accessToken) {
            try {
                $response = $this->http->post(app('url')->to('/oauth/token'), [
                    'form_params' => [
                        'grant_type' => 'client_credentials',
                        'client_id' => env('ADMIN_APP_CLIENT_ID'),
                        'client_secret' => env('ADMIN_APP_SECRET'),
                        'scope' => '',
                    ],
                ]);

                $token = json_decode((string) $response->getBody(), true);

                $this->accessToken = $token['access_token'];
            } catch (RequestException $e) {
                header('Location: /');
                exit;
            }
        }
    }

    /**
     * Get token from API.
     *
     * @return token
     */
    public function getApiAccessToken()
    {
        return $this->accessToken;
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

    public function api(Request $request)
    {
        return view('admin.api');
    }
}
