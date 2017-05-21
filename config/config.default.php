<?php

namespace www1601com\Agenturtools;

/**
 * Show the git version
 *
 * @example git version 2.0.4
 */
const cmdGitVersion = 'git --version';


/**
 * Parse the git version
 *
 * @example 2.0.4
 */
const regExGitVersion = '/git version (.*)/';


/**
 * Show the path to the git top-level
 *
 * @example /var/www/project.com/www/
 * @see http://stackoverflow.com/questions/12293944
 */
const cmdGitRootPath = 'git rev-parse --show-toplevel'; //


/**
 * Parse the path to the git top-level
 *
 * @example /var/www/project.com/www/
 */
const regExGitRootPath = '/(.*)/';


/**
 * Show the git status (we only use the headline)
 */
const cmdGitStatus = 'git status -b --porcelain';


/**
 * Parse the git status headline
 */
const regExGitStatus = '## ([\w\d\/\-.]*)(?:\.\.\.)([\w\d\/\-.]*)(?: \[(?:ahead (\d*))?(?:(?:, )?behind (\d*))?\])?';


/**
 * Show all files from git status with mode and modified date
 *
 * @example
 * MODE=" D" MODIFIED="" FILE="3"
 * MODE=" D" MODIFIED="" FILE="4"
 * MODE=" M" MODIFIED="2017-05-21 14:23:34.288550354 +0200" FILE="1"
 * MODE="??" MODIFIED="2017-05-21 09:44:40.428401928 +0200" FILE="htdocs/"
 * MODE="??" MODIFIED="2017-05-21 14:22:48.369673948 +0200" FILE="5"
 *
 * @see http://stackoverflow.com/questions/14141344
 */
const cmdGitStatusFiles = 'IFS=\'\'; git status --porcelain | while read -n2 mode; read -n1; read file; do echo MODE=\"$mode\" MODIFIED=\"$(stat -c %y "$file" 2> /dev/null)\" FILE=\"$file\"; done|sort';


/**
 * Parse the git status result
 *
 * @example
 * [1] => Array
 * (
 *      [0] => MODE=" D" MODIFIED="" FILE="4"
 *      [1] => D
 *      [2] =>
 *      [3] => 4
 * )
 *
 * [2] => Array
 * (
 *      [0] => MODE=" M" MODIFIED="2017-05-21 14:23:34.288550354 +0200" FILE="1"
 *      [1] => M
 *      [2] => 2017-05-21 14:23:34
 *      [3] => 1
 *
 * @see https://regex101.com/r/l7QyRg/1
 */
const regExGitStatusFiles = '/MODE=\".(.)\" MODIFIED=\"([\d-: ]*).*\" FILE=\"(.*)\"/'; //


/**
 * Show the file-diff between filesystem and git, limited to XX rows
 *
 * @example
 * diff --git a/gitsniffer.class.php b/gitsniffer.class.php
 * deleted file mode 100644
 * index e4a031e..0000000
 * --- a/gitsniffer.class.php
 * +++ /dev/null
 * @@ -1,286 +0,0 @@
 * -<?php
 * -
 * -namespace ADoebeling;
 * -require_once 'php-github-api/vendor/autoload.php';
 */
const cmdGitDiff = 'git diff --minimal | head -n40';



/**
 * Parse file-diff
 */
const regExGitDiff = '/(.*)/';