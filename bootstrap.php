<?php

!file_exists('config/github.php') or require_once('config/github.php');
!file_exists('config/system.php') or require_once('config/system.php');

require_once 'config/github.default.php';
require_once 'config/system.default.php';

require_once 'vendor/autoload.php';

require_once 'class/exception.class.php';
require_once 'class/cmd.class.php';
require_once 'class/git.class.php';
require_once 'class/gitsniffer.class.php';
require_once 'class/githubIssue.php';
require_once 'class/hostname.class.php';
require_once 'class/textParser.class.php';