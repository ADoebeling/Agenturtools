<?php
declare(strict_types=1);

namespace www1601com\Agenturtools;
//require_once '../config/config.default.php'; // TODO: Config mgnt


/**
 * Class git
 *
 * @author      Andreas Doebeling <Kontakt@Doebeling.de>
 * @license     cc-by-sa <https://creativecommons.org/licenses/by-sa/3.0/>
 * @link        https://github.com/ADoebeling/GitSniffer
 * @version     0.1.170521-dev
 */
class git
{
    /**
     * Get the installed git version
     *
     * @param string $cmd
     * @param string $regEx
     * @return string
     * @throws exception
     */
    public static function getGitVersion($cmd = cmdGitVersion, $regEx = regExGitVersion)
    {
        try {
            return cmd::run($cmd, $regEx, 'string');
        } catch (exception $e) {
            throw new exception("It seems like you don't have git installed or it's not within the \$PATH.\n" .
                "Please ensure the cmd 'git' is available before running the script again.\n");
        }
    }

    /**
     * Get the path to the git-rootpath (toplevel)
     *
     * @param string $cmd
     * @param string $regEx
     * @todo Exception handling
     * @return string
     */
    public static function getGitRootPath($cmd = cmdGitRootPath, $regEx = regExGitRootPath)
    {
        return cmd::run($cmd, $regEx, 'string');
    }


    /**
     * Change to the git-root-directory (toplevel) of a given path
     *
     * @param string $startPath
     * @return string|bool
     */
    public static function chDirToGitToplevel($startPath = gitPath)
    {
        try {
            chdir($startPath);
            $path = self::getGitRootPath();
            chdir($path);
            return $path;
        } catch (exception $e) {
            $e->errorMessage('Invalid startPath');
            return false;
        }
    }

    /**
     * @param string $cmd
     * @param string $regEx
     * @return string name of the current branch
     */
    public static function getGitBranch($cmd = cmdGitBranch, $regEx = regExGitBranch)
    {
        return cmd::run($cmd, $regEx, 'string');
    }


    /**
     * Parse the files returned by `git status` including modified date and returns them as array:
     *
     * [original] => MODE="??" MODIFIED="2017-05-21 19:50:51.536574362 +0200" FILE="test/" SIZE="4096" TYPE="directory"
     * [status] => ?
     * [modified] => 2017-05-21 19:50:51
     * [name] => test/
     * [size] => 4096
     * [type] => directory
     * [statusName] => untracked
     * [sizeReadable] => 4 KB
     *
     * @param string $cmd
     * @param string $regEx
     * @return array
     * @see https://git-scm.com/docs/git-status#_short_format
     */
    public static function getGitStatusFiles($cmd = cmdGitStatusFiles, $regEx = regExGitStatusFiles)
    {
        $return = array();
        $output = cmd::run($cmd, $regEx);

        foreach ($output as $line) {
            $row = array();
            $row['original'] = $line[0];
            $row['modified'] = $line[1];
            $row['name'] = $line[2];
            $row['status'] = $line[3];
            $row['sizeInByte'] = $line[4];
            $row['diskSizeInKb'] = $line[5];
            $row['type'] = $line[6];

            if ($row['sizeInByte'] < 1000)  // Max 3 chars!
                $row['sizeReadable'] = "{$row['sizeInByte']} Byte";

            elseif ($row['sizeInByte'] < 1024 * 1024)
                $row['sizeReadable'] = round($row['sizeInByte'] / 1024, 1) . ' KB';

            elseif ($row['sizeInByte'] < 1024 * 1024 * 1024)
                $row['sizeReadable'] = round($row['sizeInByte'] / 1024 / 1024, 1) . ' MB';

            elseif ($row['sizeInByte'] < 1024 * 1024 * 1024 * 1024)
                $row['sizeReadable'] = round($row['sizeInByte'] / 1024 / 1024 / 1024, 1) . ' GB';

            else
                $row['sizeReadable'] = round($row['sizeInByte'] / 1024 / 1024 / 1024 / 1024, 1) . ' TB';

            
            if ($row['diskSizeInKb'] < 1000)  // Max 3 chars!
                $row['diskSizeReadable'] = "{$row['diskSizeInKb']} KB";

            elseif ($row['diskSizeInKb'] < 1024 * 1024)
                $row['diskSizeReadable'] = round($row['diskSizeInKb'] / 1024, 1) . ' MB';

            elseif ($row['diskSizeInKb'] < 1024 * 1024 * 1024)
                $row['diskSizeReadable'] = round($row['diskSizeInKb'] / 1024 / 1024, 1) . ' GB';

            elseif ($row['diskSizeInKb'] < 1024 * 1024 * 1024 * 1024)
                $row['diskSizeReadable'] = round($row['diskSizeInKb'] / 1024 / 1024 / 1024, 1) . ' TB';


            switch ($row['type']) {
                case 'directory':
                    $row['typeShort'] = 'dir';
                    break;
                case 'regular file':
                    $row['typeShort'] = 'file';
                    break;
                case 'regular empty file':
                    $row['typeShort'] = 'empty file';
                    break;
                case 'symbolic link':
                    $row['typeShort'] = 'symlink';
                    break;
                default:
                    $row['typeShort'] = $row['type'];
            }

            switch ($row['status']) {
                case '?':
                    $row['statusName'] = 'untracked';
                    $row['statusDesc'] = "New {$row['typeShort']}";
                    break;
                case 'M':
                    $row['statusName'] = 'modified';
                    $row['statusDesc'] = "Modified {$row['typeShort']}";
                    break;
                case 'D':
                    $row['statusName'] = 'deleted';
                    $row['statusDesc'] = "Deleted element";
                    break;
                case 'R':
                    $row['statusName'] = 'renamed';
                    $row['statusDesc'] = "Renamed {$row['typeShort']}";
                    break;
                case 'C':
                    $row['statusName'] = 'copied';
                    $row['statusDesc'] = "Copied {$row['typeShort']}";
                    break;
                case 'U':
                    $row['statusName'] = 'unmerged';
                    $row['statusDesc'] = "Conflict in {$row['typeShort']}";
                    break;
                default:
                    $row['statusName'] = 'unknown';
                    $row['statusDesc'] = "Unknown changes in {$row['typeShort']}";
                    break;
            }

            if ($row['statusName'] == 'deleted')
            {
                $row['realSizeReadable'] = '';
            }
            else if ($row['type'] != 'directory')
            {
                $row['realSizeInKb'] = $row['sizeInByte']/1024;
                $row['realSizeReadable'] = $row['sizeReadable'];
            }
            else
            {
                $row['realSizeInKb'] = $row['diskSizeInKb'];
                $row['realSizeReadable'] = $row['diskSizeReadable'];
            }



            $return[] = $row;
        }
        return $return;
    }

    /**
     * Return git diff
     *
     * @param string $cmd
     * @param string $regEx
     * @return array
     */
    public static function getGitDiff($cmd = cmdGitDiff, $regEx = regExGitDiff)
    {
        $raw = cmd::run($cmd, $regEx, 'string');
        return $raw;
    }
}