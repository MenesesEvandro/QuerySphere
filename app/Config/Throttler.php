<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class Throttler extends BaseConfig
{
    /**
     * The "token" in the Token Bucket algorithm.
     *
     * @var int
     */
    public int $maxRequests = env('throttler.maxRequests', 10);

    /**
     * The time interval in seconds for the Token Bucket.
     *
     * @var int
     */
    public int $timeInterval = MINUTE;
}