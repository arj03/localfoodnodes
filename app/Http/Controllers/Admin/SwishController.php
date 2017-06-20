<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\Controller;

class SwishController extends Controller
{
    public function swish(Request $request)
    {


        // openssl s_client -port 443 -host mss.swicpc.bankgirot.se -cert /home/davidajnered/Sites/local-food-nodes/storage/certificates/swish/clientcertTest.pem -key /home/davidajnered/Sites/local-food-nodes/storage/certificates/swish/keyTest.key -CAfile /home/davidajnered/Sites/local-food-nodes/storage/certificates/swish/cacertTest.pem


        error_log(var_export('init swish', true));

        $data = [
            'payeePaymentReference' => '0123456789',
            'callbackUrl' => 'http://localhost:8000/account/user/membership/swish/callback',
            'payerAlias' => '46703633180',
            'payeeAlias' => '1231181189',
            'amount' => '1',
            'currency' => 'SEK',
            'message' => 'Local Food Nodes Membership'
        ];

        $url = 'https://mss.swicpc.bankgirot.se/swish-cpcapi/api/v1/paymentrequests/';
        $cacert = storage_path('certificates/swish/cacertTest.pem');
        $clientcert = storage_path('certificates/swish/clientcertTest.pem');
        $key = storage_path('certificates/swish/keyTest.key');
        $challenge = 'swish';

        $header = Array();
        $header[] = "Content-Type: application/json";

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_VERBOSE, 1);;
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSLCERT, $clientcert);
        curl_setopt($ch, CURLOPT_SSLKEY, $key);
        curl_setopt($ch, CURLOPT_SSLKEYPASSWD, $challenge);
        curl_setopt($ch, CURLOPT_CAINFO, $cacert);

        $dataString = http_build_query($data);

        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);

        $ret = curl_exec($ch);

        error_log(var_export($ret, true));

        echo 'swish';
    }

    public function swishCallback(Request $request)
    {
        error_log('SWISH CALLBACK!');
        error_log(var_export($request->all(), true));
    }
}
