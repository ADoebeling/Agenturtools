<?php

namespace www1601com\Agenturtools;
use \Github\client;

require_once '../vendor/knplabs/github-api/lib/Github/Client.php';

class githubIssue extends Client
{
    public function __construct($httpClientBuilder = null, $apiVersion = null, $enterpriseUrl = null)
    {
        $this->authenticate(githubUser, githubToken, Client::AUTH_HTTP_PASSWORD);
        parent::__construct($httpClientBuilder, $apiVersion, $enterpriseUrl);
    }
}