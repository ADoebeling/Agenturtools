<?php

namespace ADoebeling;


/**
 * Class gitSniffer
 *
 * Checks a git-repo for not yet committed changes and sends a friendly reminder as Github-Issue.
 *
 * ---------------------------------------------------------
 *
 * LABEL: BUG, GitSnifferReminder
 * SUBJECT: :eyeglasses: Commits missing: index.php, test.jpg, bla.sdf, sdfsdf.txt, ... (23 more)
 *
 * ---------------------------------------------------------
 *
 * @1601com/DigitalMedia The GitSniffer has found 5 changes at `/var/ww/bla/blubb/project` in branch `deployment/blubb`
 * that were not committed yet.
 * Please take two minutes to commit these files or update the .gitignore if these files should not be part of the repo.
 *
 * ## 1 new or untracked file/folder:
 *
 * * [ ] `file_1` (2017-05-03 15:22)
 *
 * ## 2 modified files/folders:
 *
 * * [ ] `file_2` (2017-05-03 15:22)
 * * [ ] `file_3` (2017-05-03 15:22)
 *
 * ## 3 deleted files/folders
 *
 * * [ ] `file_4` (2017-05-03 15:22)
 * * [ ] `file_5` (2017-05-03 15:22)
 * * [ ] `file_6` (2017-05-03 15:22)
 *
 * ## Changes
 *
 * ```diff
 * git diff
 * diff --git a/file2 b/file2
 * index e69de29..bee906d 100644
 * --- a/file2
 * +++ b/file2
 * @@ -0,0 +1,6 @@
 * +sfdsfd
 * +sdf
 * +sdf
 * +sfd
 * +sdf
 * ```
 *
 * ## Copy & paste: Adding listed files to git
 *
 * ```
 * cd /var/ww/bla/blubb/project
 *
 * git add file_1 # Adding the untracked file
 * git add file_2 # Adding the modified file
 * git add file_3 # Adding the modified file
 * git rm -r file_4 # Removing the deleted file or folder
 * git rm -r file_5 # Removing the deleted file or folder
 * git rm -r file_6 # Removing the deleted file or folder
 *
 * git commit -m "DESCRIPTION OF YOUR CHANGES WITH ISSUE-ID" --author="XXX <xx@xxx.xx>"
 * git push
 * ```
 *
 * ## ... OR: Exclude listed files in `.gitignore`
 *
 * ```
 * /file_1
 * /file_2
 * /file_3
 * ```
 *
 * ```
 * cd /var/ww/bla/blubb/project
 *
 * git rm -r --cached file_1 # Remove file from git
 * git rm -r --cached file_2 # Adding the modified file
 * git rm -r --cached file_3 # Adding the modified file
 * git rm -r --cached file_4 # Removing the deleted file or folder
 * git rm -r --cached file_5 # Removing the deleted file or folder
 * git rm -r --cached file_6 # Removing the deleted file or folder
 *
 * git add .gitignore
 * git commit -m "DESCRIPTION OF YOUR CHANGES WITH ISSUE-ID" --author="XXX <xx@xxx.xx>"
 * git push
 * ```
 *
 * Thanks a lot!
 * - [GitSniffer](https://github.com/ADoebeling/GitSniffer) by @ADoebeling
 *
 * <!-- Protocol: LINK -->
 *
 *
 * Request:
 * $domain.tld/gitSniffer.php?repo=ADoebeling/test&user=XXX&token=XXX
 *
 * ---------------------------------------------------------
 *
 * If the script was triggered by a updated Issue, it will additionally post a comment:
 *
 * ---------------------------------------------------------
 *
 * @lastUser Thanks for your contribution! Might it be that you haven't already committed all changes?
 * Please have a look at issue #123
 *
 * Tanks!
 * - [GitSniffer](https://github.com/ADoebeling/GitSniffer) by @ADoebeling
 *
 * ---------------------------------------------------------
 *
 * To prevent spamming the GitSniffer will check the lock-file before posting.
 * If the SHA256-Hash has not changed or the last comment is <2h old, it won't disturb the team again.
 *
 * ---------------------------------------------------------
 *
 * gitsniffer.lock
 * 4bac27393bdd9777ce02453256c5577cd02275510b2227f473d03f533924f877 << SHA256-Hash of git diff
 *
 * ---------------------------------------------------------
 *
 * @author      Andreas Doebeling <Kontakt@Doebeling.de>
 * @license     cc-by-sa <https://creativecommons.org/licenses/by-sa/3.0/>
 * @link        https://github.com/ADoebeling/GitSniffer
 * @version     0.1.170521-dev
 */
class gitSniffer
{
    protected static $cmdGitStatusFiles = 'IFS=\'\'; git status --porcelain | while read -n2 mode; read -n1; read file; do echo $mode $(stat -c %y "$file" 2> /dev/null) $file; done|sort';

    /** @var array cmd-output */
    protected $cmdGitStatusOutput = array();

    /** @var  string */
    protected $localBranch;

    /** @var string */
    protected $lastCommitString;

    /** @var int */
    protected $localAhead;

    /** @var int */
    protected $localBehind;


    public static function getGitStatusAsParsedMarkdown()
    {
        /*
         * Parse git status files
         */
        $files = git::getGitStatusFiles();

        $filesMd = ['untracked' => '', 'modified' => '', 'deleted' => ''];
        $count = ['untracked' => 0, 'modified' => 0, 'deleted' => 0];
        $md = '';

        foreach ($files as $file) {
            $filesMd[$file['status']] .= empty($file['modified']) ? "* `{$file['name']}`\n" : "* `{$file['name']}` (last modified: {$file['modified']})\n";
            $count[$file['status']]++;
        }

        if ($count['untracked'] == 1)
            $md .= "## One new or untracked file/folder\n\n{$filesMd['untracked']}\n";

        else if ($count['untracked'] >= 1)
            $md .= "## {$count['untracked']} new or untracked files/folders\n\n{$filesMd['untracked']}\n";


        if ($count['modified'] == 1)
            $md .= "## One modified file/folder\n\n{$filesMd['modified']}\n";

        else if ($count['modified'] >= 1)
            $md .= "## {$count['modified']} modified files/folders\n\n{$filesMd['modified']}";

        if ($count['deleted'] == 1)
            $md .= "## One deleted file/folder\n\n{$filesMd['deleted']}\n";

        else if ($count['deleted'] >= 1)
            $md .= "## {$count['deleted']} deleted files/folders\n\n{$filesMd['deleted']}\n";


        /*
         * Parse git diff
         */


        return $md;
    }






    /**
     * Parse the diff returned by `git diff`
     * @param string $cmd
     * @param int $limit max. returned lines
     * @return string
     */
    public static function getGitDiffAsParsedMarkdown($cmd = 'git diff --minimal | head -n40')
    {
        exec($cmd, $output, $cmdReturnVar);

        $return = '';
        foreach ($output as $row) $return .= "$row\n";

        return
            "## Changes\n" .
            "```diff\n" .
            "$return\n" .
            "```";
    }

    public static function getTextAsParsedMarkdown()
    {
        $status = self::getGitStatus();
        $files = self::getGitStatusFilesAsParsedMarkdown();
        $diff = self::getGitDiffAsParsedMarkdown();

        $text = "The GitSniffer has found multiple changes at `xxx`" .
            "in branch `{$status['branch']}` that were not committed yet.\n\n" .

            "{$files['untracked']}\n" .
            "{$files['modified']}\n" .
            "{$files['deleted']}\n" .
            "$diff\n" .

            "## Commit changes\n" .
            "Please take a minute and [click here to commit these files or update the .gitignore](#notyet) " .
            "if these files should not be part of the repo.\n\n\"" .

            "Thanks a lot!\n" .
            "\- [GitSniffer](https://github.com/ADoebeling/GitSniffer) by @ADoebeling\n\n" .

            "<!--\n" .
            "Script: {$_SERVER['SCRIPT_FILENAME']}\n" .
            "Request: {$_SERVER['REQUEST_URI']}\n" .
            "Remote: {$_SERVER['REMOTE_ADDR']} / {$_SERVER['REMOTE_HOST']}\n" .
            "Trackback: https://github.com/1601com/Hosting/issues/12\n" .
            "-->";

        return ['text' => $text];
    }

    public static function sendIssue()
    {
        $text = self::getTextAsParsedMarkdown()['text'];
        $client = new \Github\Client();
        $client->authenticate('ad1601com-test', 'f632d21c39d28a976bd87b2c9d8bb4e45aacf2d4', \Github\Client::AUTH_HTTP_PASSWORD);
        $client->api('issues')->create('ad1601com-test', 'test', array('title' => ':eyeglasses: Commits missing', 'body' => $text, 'assignee' => 'ADoebeling'));
    }
}