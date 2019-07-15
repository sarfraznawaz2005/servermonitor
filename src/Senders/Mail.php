<?php
/**
 * Created by PhpStorm.
 * User: Sarfraz
 * Date: 7/15/2019
 * Time: 5:29 PM
 */

namespace Sarfraznawaz2005\ServerMonitor\Senders;

use Illuminate\Mail\Message;
use Sarfraznawaz2005\ServerMonitor\Checks\Check;

class Mail implements Sender
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
        $from = ($config['notification_mail_from'] ?? config('server-monitor.notifications.notification_mail_from')) ?? null;

        $name = $check->name();
        $error = $check->message();
        $body = "<strong>$name</strong><br><br>$error";

        $emails = config('server-monitor.notifications.notification_notify_emails');

        if ($emails) {
            foreach ($emails as $email) {
                \Mail::send([], [], static function (Message $message) use ($title, $from, $email, $body) {

                    if ($from) {
                        $message
                            ->subject($title)
                            ->from($from)
                            ->to($email)
                            ->setBody($body, 'text/html');
                    } else {
                        $message
                            ->subject($title)
                            ->to($email)
                            ->setBody($body, 'text/html');
                    }
                });
            }
        }

    }
}
