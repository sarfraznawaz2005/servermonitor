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
     * @throws \ReflectionException
     */
    public function send(Check $check, array $config)
    {
        $subject = $config['notification_title'] ?? config('server-monitor.notifications.notification_title');
        $from = ($config['notification_mail_from'] ?? config('server-monitor.notifications.notification_mail_from')) ?? null;

        $name = getCheckerName($check, $config);
        $error = $check->message();

        $body = "<strong>$name</strong><br><br>$error";

        $emails = $config['notification_notify_emails'] ?? config('server-monitor.notifications.notification_notify_emails');

        try {
            if ($emails) {
                foreach ($emails as $email) {
                    \Mail::send([], [], static function (Message $message) use ($subject, $from, $email, $body) {

                        if ($from) {
                            $message
                                ->subject($subject)
                                ->from($from)
                                ->to($email)
                                ->setBody($body, 'text/html');
                        } else {
                            $message
                                ->subject($subject)
                                ->to($email)
                                ->setBody($body, 'text/html');
                        }
                    });
                }
            }
        } catch (\Exception $e) {
            \Log::error('Server Monitor Error: ' . $e->getMessage());
        }

    }
}
