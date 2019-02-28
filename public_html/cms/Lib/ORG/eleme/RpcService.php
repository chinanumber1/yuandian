<?php

require 'RpcClient.php';

class RpcService
{
    protected $client;

    public function __construct($token, $config)
    {
        $this->client = new RpcClient($token, $config);
    }
}