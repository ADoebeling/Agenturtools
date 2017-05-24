<?php
namespace www1601com\Agenturtools;


class hostname
{
    /**
     * Returns the hostname of the current machine.
     * If $byReverseDns == true it will return the reverse-dns-name of your machine instead of the default hostname.
     * If $managedServerWorkaround == true it will auto-detect a managed server at the german hoster domainfactory and
     * automatically use the ReverseDNS. Sorry for that dirty hack, but it was needed by the author of this script.
     *
     * @param bool $byReverseDns
     * @param string $cmd
     * @param string $regEx
     * @param bool $managedServerWorkaround
     * @return string
     */
    public static function getHostname(
        $byReverseDns = getHostnameByReverseDns,
        $cmd = cmdGetHostnameByReverseDns,
        $regEx = regExGetHostnameByReverseDns,
        $managedServerWorkaround = getHostnameWithManagedServerWorkaround)
    {
        $hostname = gethostname();
        if ($byReverseDns || $managedServerWorkaround && strstr($hostname, '.ispgateway.de'))
        {
            $hostname = cmd::run(sprintf($cmd, $hostname), $regEx, 'string');
        }
        return $hostname;
    }

    /**
     * @return string IPv4 by gethostbyname(gethostname())
     */
    public static function getExternalIp()
    {
        return gethostbyname(gethostname());
    }
}