<?php

namespace unapi\def\qiwi;

class Client extends \GuzzleHttp\Client
{
    /**
     * @param array $config
     */
    public function __construct(array $config = [])
    {
        $config['base_uri'] = 'https://qiwi.com/';
        $config['cookies'] = true;

        parent::__construct($config);
    }
}