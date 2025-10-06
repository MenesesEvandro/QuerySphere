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
    public int $maxRequests;

    /**
     * The time interval in seconds for the Token Bucket.
     *
     * @var int
     */
    public int $timeInterval = MINUTE;

    /**
     * Constructor.
     * Reads the configuration from the .env file.
     */
    public function __construct()
    {
        parent::__construct(); // Chama o construtor da classe pai

        // Atribui o valor do .env aqui, dentro do método
        $this->maxRequests = (int) env('throttler.maxRequests', 10);
    }
}
