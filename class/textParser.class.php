<?php
/**
 * Created by PhpStorm.
 * User: Doebeling
 * Date: 23.05.2017
 * Time: 21:44
 */

namespace www1601com\Agenturtools;


class textParser
{
    public static function textParser ($source, array $vars)
    {
        foreach ($vars as $key => $val) $source = str_replace('%'.strtoupper($key).'%', $val, $source);
        return $source;
    }

    /**
     * @author Günther Hörandl <http://www.hoerandl.com>
     * @author David Losse <http://www.losse-david.de/>
     * @see http://www.hoerandl.com/code-schnipsel/php-codes/string-funktionen/item/einen-lange-text-auf-eine-bestimmte-laenge-kuerzen
     * @param string $string
     * @param int $length
     * @return string
     */
    public static function shortText(string $string,int $length) {
        if(strlen($string) > $length) {
            $string = substr($string,0,$length)."...";
            $string_ende = strrchr($string, " ");
            $string = str_replace($string_ende," ...", $string);
        }
        return $string;
    }
}