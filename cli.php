#!/usr/local/bin/php7-70STABLE-CLI
<?php

namespace www1601com\Agenturtools;
require_once 'bootstrap.php';


chdir('../');


$r = [

    'chDir' => git::chDirToGitToplevel(),
    'git version' => git::getGitVersion(),
    'git toplevel path' => git::getGitRootPath(),

    //git::getGitStatusFiles(),
    'gitStatusAsParsedMarkdown' => gitSniffer::getGitStatusAsParsedMarkdown(),
    'github' => gitSniffer::sendIssue(),
    'eof'
];

print_r($r);

