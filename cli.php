#!/usr/local/bin/php7-70STABLE-CLI
<?php

namespace www1601com\Agenturtools;
require_once 'bootstrap.php';

$gitSniffer = new gitSniffer();

$r = [

    'chDir' => git::chDirToGitToplevel(__DIR__),
    'git version' => git::getGitVersion(),
    'git toplevel path' => git::getGitRootPath(),

    //git::getGitStatusFiles(),
    //git::getGitDiff(),
    //'gitStatusAsParsedMarkdown' => gitSniffer::getGitStatusAsMarkdown(),
    'gitSnifferTitle' => $gitSniffer->getReminderTitle(),
    'gitSnifferText' => $gitSniffer->getTextAsMarkdown(),
    //'github' => gitSniffer::sendIssue(),
    'eof'
];

print_r($r);

