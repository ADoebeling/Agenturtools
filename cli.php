#!/usr/local/bin/php7-70STABLE-CLI
<?php

namespace ADoebeling;

require_once 'class/exception.class.php';
require_once 'config/config.default.php';
require_once 'php-github-api/vendor/autoload.php';
require_once 'class/cmd.class.php';
require_once 'class/git.class.php';
require_once 'class/gitsniffer.class.php';

chdir('../');


$r = [

    'chDir' => git::chDirToGitToplevel(),
    'git version' => git::getGitVersion(),
    'git toplevel path' => git::getGitRootPath(),

    //git::getGitStatusFiles(),
    'gitStatusAsParsedMarkdown' => gitSniffer::getGitStatusAsParsedMarkdown(),
    'eof'
];

print_r($r);

