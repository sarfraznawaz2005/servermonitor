<?php

namespace Sarfraznawaz2005\ServerMonitor;

/**
 * DTO class for a server used by the ping check.
 *
 * @via BeyondCode\SelfDiagnosis
 */
class Server
{
    /** @var string */
    protected $host;

    /** @var int|null */
    protected $port;

    /** @var int */
    protected $timeout;

    public function __construct($host, $port, $timeout)
    {
        $this->host = $host;
        $this->port = $port;
        $this->timeout = $timeout;
    }

    public function getHost(): string
    {
        return $this->host;
    }

    public function getPort()
    {
        return $this->port;
    }

    public function getTimeout(): int
    {
        return $this->timeout;
    }
}
