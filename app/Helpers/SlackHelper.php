<?php

namespace App\Helpers;

class SlackHelper
{
    /**
     * Main method used to send a message to Slack.
     *
     * @param string $channel
     * @param string $message
     */
    public static function message($channel, $message) {
        switch($channel) {
            case 'notification':
                self::notification($message);
                break;
            case 'error':
                self::error($message);
                break;

        }
    }

    /**
     * Send notification.
     *
     * @param string $message
     */
    private static function notification($message) {
        self::send('https://hooks.slack.com/services/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', $message);
    }

    /**
     * Send error.
     *
     * @param string $message
     */
    private static function error($message) {
        self::send('https://hooks.slack.com/services/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', $message);
    }

    /**
     * Send.
     *
     * @param string $url
     * @param string $message
     */
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
