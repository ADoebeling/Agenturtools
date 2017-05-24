<?php

require_once __DIR__.'/../'.'bootstrap.php';
$gitHubResult = (new \www1601com\Agenturtools\gitSniffer())->sendIssue();

// Debug
print_r($gitHubResult);
