<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\Controller;
use GuzzleHttp\Client;

class SwishController extends Controller
{
    public function swish(Request $request)
    {
        error_log(var_export('init swish', true));

        $data = [
            'payeePaymentReference' => '0123456789',
            'callbackUrl' => 'https://staging.localfoodnodes.org/membership/swish/callback',
            'payerAlias' => '46703633180',
            'payeeAlias' => '1231181189',
            'amount' => '1',
            'currency' => 'SEK',
            'message' => 'Local Food Nodes Membership'
        ];

        $url = 'https://mss.swicpc.bankgirot.se/swish-cpcapi/api/v1/paymentrequests/';
        $clientcert = storage_path('certificates/test_swish_root_ca_v1_test.pem');
        $keyfile = storage_path('certificates/swish_merchant_test_certificate_1231181189.p12');
        $challenge = "swish";
        // print "<bR><BR>$challenge<br><br>";
        // print "<bR><BR>$keyfile<br><br>";

        $header = Array();
        $header[] = "Content-Type: application/json";

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, 1);
        curl_setopt($ch, CURLOPT_VERBOSE, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_FAILONERROR, 1);
        // curl_setopt($ch, CURLOPT_SSLCERT, $clientcert);
        curl_setopt($ch, CURLOPT_SSLCERTPASSWD, $challenge);
        curl_setopt($ch, CURLOPT_SSLKEYTYPE, 'P12');
        curl_setopt($ch, CURLOPT_SSLKEY, $keyfile);
        curl_setopt($ch, CURLOPT_SSLKEYPASSWD, 'swish');

        // curl_setopt($ch, CURLOPT_SSLCERT, 'file.crt.pem');
        // curl_setopt($ch, CURLOPT_SSLKEY, 'file.key.pem');
        // curl_setopt($ch, CURLOPT_SSLCERTPASSWD, 'pass');
        // curl_setopt($ch, CURLOPT_SSLKEYPASSWD, 'pass');

        $ret = curl_exec($ch);

        error_log(var_export($ret, true));

        echo 'swish';
    }

    public function swishCallback(Request $request)
    {
        error_log(var_export($request->all(), true));
    }
}
