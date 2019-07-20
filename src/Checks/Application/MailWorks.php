<?php
/**
 * Created by PhpStorm.
 * User: Sarfraz
 * Date: 7/10/2019
 * Time: 2:39 PM
 */

namespace Sarfraznawaz2005\ServerMonitor\Checks\Application;

use Illuminate\Mail\Message;
use Illuminate\Support\Facades\Mail;
use Sarfraznawaz2005\ServerMonitor\Checks\Check;

class MailWorks implements Check
{
    private $mailConfiguration;
    private $options;

    /**
     * The name of the check.
     *
     * @return string
     */
    public function name(): string
    {
        return 'Mail is Working';
    }

    /**
     * Perform the actual verification of this check.
     *
     * @param array $config
     * @return bool
     */
    public function check(array $config): bool
    {
        return $this->checkMail($config);
    }

    /**
     * The error message to display in case the check does not pass.
     *
     * @return string
     */
    public function message(): string
    {
        return 'The mail does not seem to work.';
    }

    /**
     * Configure mail for testing.
     * @param $config
     */
    private function configureMail($config)
    {
        $this->options = $config;

        $this->mailConfiguration = config('mail');

        config(['mail' => $this->options['mail_config']]);
    }

    /**
     * Send a test e-mail.
     * @param $config
     * @return bool
     */
    private function checkMail($config): bool
    {
        $this->configureMail($config);

        try {
            $this->sendMail();

            if (Mail::failures()) {
                $result = false;
            } else {
                $result = true;
            }

        } catch (\Exception $exception) {
            $result = false;
        }

        $this->restoreMailConfiguration();

        return $result;
    }

    /**
     * Restore mail configuration.
     */
    private function restoreMailConfiguration()
    {
        config(['mail' => $this->mailConfiguration]);
    }

    /**
     * Send a test e-mail message.
     */
    private function sendMail()
    {
        Mail::send([], [], function (Message $message) {
            $fromAddress = array_get($this->options['mail_config'], 'from.address');

            $message
                ->from($fromAddress)
                ->to($this->options['to'])
                ->subject($this->options['subject'])
                ->setBody($this->options['content']);
        });
    }
}
