<?php

namespace www1601com\Agenturtools;


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
    /** @var git */
    protected $git;

    /** @var string git root path (toplevel) */
    protected $path;

    /** @var string */
    protected $hostname;

    /** @var string */
    protected $ip;

    /** @var  string current branch */
    protected $branch;

    /** @var array git status files */
    protected $files = [];

    /** @var string git diff */
    protected $diff;

    /**
     * gitSniffer constructor.
     * @param string $startPath
     */
    public function __construct($startPath = gitPath)
    {
        $this->git = new git();
        $this->git->chDirToGitToplevel($startPath);
        $this->path = $this->git->getGitRootPath();
        $this->branch = $this->git->getGitBranch();
        $this->files = $this->git->getGitStatusFiles();
        $this->hostname = hostname::getHostname();
        $this->ip = hostname::getExternalIp();
    }

    /**
     * @return string "git status" including file type, size and modified date as markdown
     */
    public function getGitStatusAsMarkdown()
    {
        $count = count($this->files);
        $md = '';
        if ($count > 0) {
            $md .= ($count == 1) ?
                "## One uncommitted file or directory\n\n" :
                "## $count uncommitted files and directories\n\n";

            $md .=
                "| Changes | File | Size | Modified |\n" .
                "|-------------|------|------|----------|\n";
            foreach ($this->files as $f) {
                $md .= "| {$f['statusDesc']}: | `{$f['name']}` | {$f['realSizeReadable']} | {$f['modified']} |\n";
            }
        }
        return $md;
    }

    /**
     * @return string "git diff" as markdown
     */
    public function getGitDiffAsMarkdown()
    {
        $diff = git::getGitDiff();
        $md = "## Changes in detail\n\n```diff\n$diff\n```\n\n";
        return $md;
    }

    /**
     * @return string
     */
    public function getTextAsMarkdown()
    {
        return textParser::textParser(reminderText,
            [
                'branch' => $this->branch,
                'path' => $this->path,
                'hostname' => $this->hostname,
                'gitStatus' => $this->getGitStatusAsMarkdown(),
                'gitDiff' => $this->getGitDiffAsMarkdown(),
            ]);
    }

    public function getReminderTitle($charLimit = 80)
    {
        $count = count($this->files);
        if ($count > 0)
        {
            $title = '';
            foreach ($this->files as $f) $title .= empty($title) ? $f['name'] : ", {$f['name']}";
            $title = $count == 1 ? "Found one uncommitted file: $title" : "Found $count uncommitted files: $title";

        }
        else
        {
            $title = 'Nothing to commit';
        }
        return textParser::shortText($title, $charLimit);
    }

    public function sendIssue()
    {
        $title = $this->getReminderTitle();
        $text = $this->getTextAsMarkdown();

        $gitHub = new githubIssue();
        $gitHub->authenticate(githubUser, githubToken);

        /** @var \Github\Api\Issue $issues */
        $issues = $gitHub->api('issues');

        return $issues->create(githubRepoOwner, githubRepo, array('title' => $title, 'body' => $text));
    }
}