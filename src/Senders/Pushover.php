<?php
/**
 * Created by PhpStorm.
 * User: Sarfraz
 * Date: 7/15/2019
 * Time: 5:29 PM
 */

namespace Sarfraznawaz2005\ServerMonitor\Senders;

use Sarfraznawaz2005\ServerMonitor\Checks\Check;

class Pushover implements Sender
{
    /**
     * Sends notificatin/alert message.
     *
     * @param Check $check
     * @param array $config
     * @return mixed
     */
    public function send(Check $check, array $config)
    {
        $title = $config['notification_subject'] ?? config('server-monitor.notifications.notification_subject');
        $name = $check->name();
        $error = $check->message();

        $body = "$title : $name ($error)";

        curl_setopt_array($ch = curl_init(), [
            CURLOPT_URL => 'https://api.pushover.net/1/messages.json',
            CURLOPT_POSTFIELDS => [
                'token' => config('server-monitor.notifications.notification_pushover_token'),
                'user' => config('server-monitor.notifications.notification_pushover_user'),
                'title' => $title,
                'message' => $body,
                'sound' => 'siren'
            ],
            CURLOPT_SAFE_UPLOAD => true,
            CURLOPT_RETURNTRANSFER => true,
        ]);

        curl_exec($ch);
        curl_close($ch);
    }
}
