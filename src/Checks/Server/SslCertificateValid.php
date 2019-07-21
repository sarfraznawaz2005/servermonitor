<?php
/**
 * Created by PhpStorm.
 * User: Sarfraz
 * Date: 7/10/2019
 * Time: 2:39 PM
 */

namespace Sarfraznawaz2005\ServerMonitor\Checks\Server;

use Carbon\Carbon;
use Sarfraznawaz2005\ServerMonitor\Checks\Check;

class SslCertificateValid implements Check
{
    /**  @var array */
    protected $certificateInfo;

    /**  @var string */
    protected $certificateExpiration;

    /**  @var string */
    protected $certificateDomain;

    /**  @var array */
    protected $certificateAdditionalDomains = [];

    /**  @var int */
    protected $certificateDaysUntilExpiration;

    /**  @var string */
    protected $url;


    /**
     * Perform the actual verification of this check.
     *
     * @param array $config
     * @return bool
     */
    public function check(array $config): bool
    {
        $this->url = $config['url'];

        $urlParts = $this->parseUrl($this->url);

        if (!$urlParts) {
            return false;
        }

        try {
            $this->certificateInfo = $this->downloadCertificate($urlParts);
        } catch (\Exception $e) {
            return false;
        }

        $this->processCertificate($this->certificateInfo);

        if (
            $this->certificateDaysUntilExpiration < 0 ||
            !$this->hostCoveredByCertificate(
                $urlParts['host'], $this->certificateDomain,
                $this->certificateAdditionalDomains
            )
        ) {
            return false;
        }

        return true;
    }

    /**
     * The error message to display in case the check does not pass.
     *
     * @return string
     */
    public function message(): string
    {
        return 'SSL certificate is invalid';
    }

    protected function downloadCertificate($urlParts)
    {
        $streamContext = stream_context_create([
            'ssl' => [
                'capture_peer_cert' => true
            ]
        ]);

        $streamClient = stream_socket_client(
            "ssl://{$urlParts['host']}:443",
            $errno,
            $errstr,
            30,
            STREAM_CLIENT_CONNECT,
            $streamContext
        );

        $certificateContext = stream_context_get_params($streamClient);

        return openssl_x509_parse($certificateContext['options']['ssl']['peer_certificate']);
    }

    public function processCertificate($certificateInfo)
    {
        if (!empty($certificateInfo['subject']) && !empty($certificateInfo['subject']['CN'])) {
            $this->certificateDomain = $certificateInfo['subject']['CN'];
        }

        if (!empty($certificateInfo['validTo_time_t'])) {
            $validTo = Carbon::createFromTimestampUTC($certificateInfo['validTo_time_t']);
            $this->certificateExpiration = $validTo->toDateString();
            $this->certificateDaysUntilExpiration = -$validTo->diffInDays(Carbon::now(), false);
        }

        if (!empty($certificateInfo['extensions']) && !empty($certificateInfo['extensions']['subjectAltName'])) {
            $this->certificateAdditionalDomains = [];
            $domains = explode(', ', $certificateInfo['extensions']['subjectAltName']);

            foreach ($domains as $domain) {
                $this->certificateAdditionalDomains[] = str_replace('DNS:', '', $domain);
            }
        }
    }

    public function hostCoveredByCertificate($host, $certificateHost, array $certificateAdditionalDomains = [])
    {
        if ($host == $certificateHost) {
            return true;
        }

        // Determine if wildcard domain covers the host domain
        if ($certificateHost[0] === '*' && substr_count($host, '.') > 1) {
            $certificateHost = substr($certificateHost, 1);
            $host = substr($host, strpos($host, '.'));
            return $certificateHost == $host;
        }

        // Determine if the host domain is in the certificate's additional domains
        return in_array($host, $certificateAdditionalDomains, true);
    }

    protected function parseUrl($url)
    {
        $urlParts = parse_url($url);

        if (!$urlParts) {
            return false;
        }

        if (empty($urlParts['scheme']) || $urlParts['scheme'] !== 'https') {
            return false;
        }

        return $urlParts;
    }
}
