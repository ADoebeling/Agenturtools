<?php

namespace www1601com\Agenturtools;
use \Github\client;

require_once __DIR__.'/../vendor/knplabs/github-api/lib/Github/Client.php';

class githubIssue extends Client
{
    public function __construct($httpClientBuilder = null, $apiVersion = null, $enterpriseUrl = null)
    {
        parent::__construct($httpClientBuilder, $apiVersion, $enterpriseUrl);
        //$this->authenticate('ad1601com-test', '7b19acc0e97d6bb5039a10dad20f1dc4abb16888');
    }
}