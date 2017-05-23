<?php

namespace App\Helpers;

class SlackHelper
{
    public static function message($channel, $message) {
        switch($channel) {
            case 'notification':
                self::notification($message);
                break;

        }
    }

    private static function notification($message) {
        self::send('https://hooks.slack.com/services/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', $message);
    }

    private static function send($url, $message) {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
            'username' => 'Local Food Nodes',
            'text' => $message
        ]));

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);

        curl_close($ch);
    }
}
