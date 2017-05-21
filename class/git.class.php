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
        try
        {
            return cmd::run($cmd, $regEx, 'string');
        }
        catch (exception $e)
        {
            throw new exception("It seems like you don't have git installed or it's not within the \$PATH.\n".
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
    public static function getGitRootPath ($cmd = cmdGitRootPath, $regEx = regExGitRootPath)
    {
        return cmd::run($cmd, $regEx, 'string');
    }


    /**
     * Change to the git-root-directory (toplevel) of a given path
     *
     * @param string $startPath
     * @return string
     */
    public static function chDirToGitToplevel ($startPath = "{__DIR__}/../../")
    {
        chdir($startPath);
        $path = self::getGitRootPath();
        chdir ($path);
        return $path;
    }



    /**
     * Parse the files returned by `git status` including modified date and returns them as array:
     *
     * Array
     *   (
     *       [0] => Array
     *       (
     *           [original] => ?? 2017-05-20 20:14:08.000000000 +0200 someFile.php
     *           [modified] => 2017-05-20 20:14:08
     *           [name] => someFile.php
     *           [status] => untracked
     *      )
     *   )
     *
     * @param string $cmd
     * @param string $regEx
     * @return array
     */
    public static function getGitStatusFiles($cmd = cmdGitStatusFiles, $regEx = regExGitStatusFiles)
    {
        $return = array();
        $output = cmd::run($cmd, $regEx);

        print_r($output);

        foreach ($output as $line)
        {
            /*
            [0] => ?? 2017-05-20 14:17:54.207894588 +0200 folder/
            [1] => ?
            [2] => 2017-05-20 14:17:54
            [3] => folder/
            */

            $row = array();
            $row['original'] = $line[0];
            $row['modified'] = $line[2];
            $row['name'] = $line[3];

            switch ($line[1]) {
                case '?':
                    $row['status'] = 'untracked';
                    break;
                case 'M':
                    $row['status'] = 'modified';
                    break;
                case 'D':
                    $row['status'] = 'deleted';
                    break;
            }

            $return[] = $row;
        }
        return $return;
    }
}